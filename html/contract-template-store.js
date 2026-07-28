/**
 * Sözleşme Word şablon depolama — Supabase Storage + IndexedDB yedek
 */
(function (global) {
  'use strict';

  var DB_NAME = 'hsy-contract-templates';
  var DB_VERSION = 1;
  var STORE_NAME = 'templates';
  var BUCKET = 'contract-templates';
  var META_KEY = 'hsy-contract-template-meta';

  var TYPE_SLUGS = {
    'Bağımsız Denetim': 'bagimsiz-denetim',
    'BDDK EK-4': 'bddk-ek4',
    'BDDK 10265': 'bddk-10265',
    'Değerleme': 'degerleme',
    'Danışmanlık': 'danismanlik',
    'Diğer': 'diger',
    default: 'default'
  };

  function normalizeType(contractType) {
    return TYPE_SLUGS[contractType] || TYPE_SLUGS.default;
  }

  function pathForType(contractType) {
    return 'sozlesme-' + normalizeType(contractType) + '.docx';
  }

  function getMeta() {
    try {
      return JSON.parse(localStorage.getItem(META_KEY) || '{}') || {};
    } catch (e) {
      return {};
    }
  }

  function setMeta(meta) {
    localStorage.setItem(META_KEY, JSON.stringify(meta));
  }

  function updateMeta(typeKey, patch) {
    var meta = getMeta();
    meta[typeKey] = Object.assign({}, meta[typeKey] || {}, patch, { typeKey: typeKey });
    setMeta(meta);
    return meta[typeKey];
  }

  function clearMeta(typeKey) {
    var meta = getMeta();
    delete meta[typeKey];
    setMeta(meta);
  }

  function resolveSupabaseClient() {
    if (global.__supabaseClientInstance) return global.__supabaseClientInstance;
    if (typeof global.supabaseClient !== 'undefined' && global.supabaseClient) return global.supabaseClient;
    return null;
  }

  function openDb() {
    return new Promise(function (resolve, reject) {
      if (!global.indexedDB) {
        reject(new Error('IndexedDB desteklenmiyor.'));
        return;
      }
      var req = global.indexedDB.open(DB_NAME, DB_VERSION);
      req.onupgradeneeded = function (e) {
        var db = e.target.result;
        if (!db.objectStoreNames.contains(STORE_NAME)) {
          db.createObjectStore(STORE_NAME, { keyPath: 'typeKey' });
        }
      };
      req.onsuccess = function () { resolve(req.result); };
      req.onerror = function () { reject(req.error || new Error('IndexedDB açılamadı.')); };
    });
  }

  async function saveLocalTemplate(typeKey, file, options) {
    options = options || {};
    var db = await openDb();
    var buffer = await file.arrayBuffer();
    var record = {
      typeKey: typeKey,
      buffer: buffer,
      fileName: file.name,
      fileSize: file.size,
      uploadedAt: new Date().toISOString(),
      source: options.source || 'local'
    };
    await new Promise(function (resolve, reject) {
      var tx = db.transaction(STORE_NAME, 'readwrite');
      tx.objectStore(STORE_NAME).put(record);
      tx.oncomplete = resolve;
      tx.onerror = function () { reject(tx.error); };
    });
    if (!options.skipMeta) {
      updateMeta(typeKey, {
        fileName: file.name,
        fileSize: file.size,
        uploadedAt: record.uploadedAt,
        source: options.source || 'local',
        contractType: options.contractType || null
      });
    }
    return record;
  }

  async function getLocalTemplate(typeKey) {
    var db = await openDb();
    return new Promise(function (resolve, reject) {
      var tx = db.transaction(STORE_NAME, 'readonly');
      var req = tx.objectStore(STORE_NAME).get(typeKey);
      req.onsuccess = function () { resolve(req.result || null); };
      req.onerror = function () { reject(req.error); };
    });
  }

  async function removeLocalTemplate(typeKey) {
    var db = await openDb();
    await new Promise(function (resolve, reject) {
      var tx = db.transaction(STORE_NAME, 'readwrite');
      tx.objectStore(STORE_NAME).delete(typeKey);
      tx.oncomplete = resolve;
      tx.onerror = function () { reject(tx.error); };
    });
    clearMeta(typeKey);
  }

  async function uploadToSupabase(contractType, file) {
    var supabase = resolveSupabaseClient();
    if (!supabase) return null;
    var typeKey = normalizeType(contractType);
    var path = pathForType(contractType);
    var { error } = await supabase.storage.from(BUCKET).upload(path, file, {
      upsert: true,
      contentType: 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
      cacheControl: '3600'
    });
    if (error) throw error;
    updateMeta(typeKey, {
      fileName: file.name,
      fileSize: file.size,
      uploadedAt: new Date().toISOString(),
      source: 'supabase',
      storagePath: path,
      contractType: contractType
    });
    await saveLocalTemplate(typeKey, file, { skipMeta: true, source: 'supabase', contractType: contractType });
    return path;
  }

  async function downloadFromSupabase(contractType) {
    var supabase = resolveSupabaseClient();
    if (!supabase) return null;
    var typeKey = normalizeType(contractType);
    var path = pathForType(contractType);
    var { data, error } = await supabase.storage.from(BUCKET).download(path);
    if (error || !data) return null;
    var buffer = await data.arrayBuffer();
    var meta = getMeta()[typeKey] || {};
    if (!meta.fileName) {
      try {
        var listRes = await supabase.storage.from(BUCKET).list('', { search: path });
        var item = (listRes.data || []).find(function (f) { return f.name === path; });
        if (item) {
          meta.fileName = item.name;
          meta.fileSize = item.metadata && item.metadata.size ? Number(item.metadata.size) : buffer.byteLength;
          meta.uploadedAt = item.updated_at || item.created_at || new Date().toISOString();
        }
      } catch (e) { /* optional */ }
    }
    await saveLocalTemplate(typeKey, new File([buffer], meta.fileName || path, {
      type: 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'
    }), { skipMeta: true, source: 'supabase', contractType: contractType });
    updateMeta(typeKey, {
      fileName: meta.fileName || path,
      fileSize: meta.fileSize || buffer.byteLength,
      uploadedAt: meta.uploadedAt || new Date().toISOString(),
      source: 'supabase',
      storagePath: path,
      contractType: contractType
    });
    return buffer;
  }

  async function removeFromSupabase(contractType) {
    var supabase = resolveSupabaseClient();
    if (!supabase) return;
    var path = pathForType(contractType);
    await supabase.storage.from(BUCKET).remove([path]);
  }

  async function uploadTemplate(contractType, file) {
    if (!file || !/\.docx$/i.test(file.name)) {
      throw new Error('Lütfen .docx uzantılı bir Word dosyası seçin.');
    }
    if (file.size > 25 * 1024 * 1024) {
      throw new Error('Şablon dosyası en fazla 25 MB olabilir.');
    }
    var supabase = resolveSupabaseClient();
    if (supabase) {
      try {
        await uploadToSupabase(contractType, file);
        return getTemplateInfo(contractType);
      } catch (err) {
        console.warn('Supabase şablon yükleme başarısız, yerel depoya kaydediliyor:', err);
      }
    }
    var typeKey = normalizeType(contractType);
    await saveLocalTemplate(typeKey, file, { contractType: contractType });
    return getTemplateInfo(contractType);
  }

  async function getTemplateBuffer(contractType) {
    var typeKey = normalizeType(contractType);
    var supabase = resolveSupabaseClient();
    if (supabase) {
      try {
        var remote = await downloadFromSupabase(contractType);
        if (remote) return remote;
      } catch (err) {
        console.warn('Supabase şablon indirilemedi:', err);
      }
    }
    var local = await getLocalTemplate(typeKey);
    if (local && local.buffer) return local.buffer;
    return null;
  }

  function getTemplateInfo(contractType) {
    var typeKey = normalizeType(contractType);
    var meta = getMeta()[typeKey];
    if (!meta) return { typeKey: typeKey, contractType: contractType, uploaded: false };
    return {
      typeKey: typeKey,
      contractType: meta.contractType || contractType,
      uploaded: true,
      fileName: meta.fileName || pathForType(contractType),
      fileSize: meta.fileSize || 0,
      uploadedAt: meta.uploadedAt || null,
      source: meta.source || 'unknown'
    };
  }

  function getAllTemplateInfo() {
    var types = Object.keys(TYPE_SLUGS).filter(function (k) { return k !== 'default'; });
    var out = {};
    types.forEach(function (t) { out[t] = getTemplateInfo(t); });
    return out;
  }

  async function removeTemplate(contractType) {
    var typeKey = normalizeType(contractType);
    try { await removeFromSupabase(contractType); } catch (err) { console.warn(err); }
    try { await removeLocalTemplate(typeKey); } catch (err) { console.warn(err); }
    return getTemplateInfo(contractType);
  }

  async function downloadTemplateCopy(contractType) {
    var buffer = await getTemplateBuffer(contractType);
    if (!buffer) throw new Error('İndirilecek şablon bulunamadı.');
    var info = getTemplateInfo(contractType);
    return {
      blob: new Blob([buffer], { type: 'application/vnd.openxmlformats-officedocument.wordprocessingml.document' }),
      fileName: info.fileName || pathForType(contractType)
    };
  }

  async function syncFromSupabase() {
    var supabase = resolveSupabaseClient();
    if (!supabase) return;
    var types = Object.keys(TYPE_SLUGS).filter(function (k) { return k !== 'default'; });
    await Promise.all(types.map(async function (t) {
      try { await downloadFromSupabase(t); } catch (e) { /* yoksa sorun değil */ }
    }));
  }

  global.ContractTemplateStore = {
    BUCKET: BUCKET,
    TYPE_SLUGS: TYPE_SLUGS,
    uploadTemplate: uploadTemplate,
    getTemplateBuffer: getTemplateBuffer,
    getTemplateInfo: getTemplateInfo,
    getAllTemplateInfo: getAllTemplateInfo,
    removeTemplate: removeTemplate,
    downloadTemplateCopy: downloadTemplateCopy,
    syncFromSupabase: syncFromSupabase,
    normalizeType: normalizeType
  };
})(typeof window !== 'undefined' ? window : global);
