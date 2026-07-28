/**
 * Teklif PowerPoint şablon depolama — Supabase Storage + IndexedDB yedek
 */
(function (global) {
  'use strict';

  var DB_NAME = 'hsy-offer-templates';
  var DB_VERSION = 1;
  var STORE_NAME = 'templates';
  var BUCKET = 'offer-templates';
  var META_KEY = 'hsy-offer-template-meta';

  var PATHS = {
    tr: 'teklif-sablon-tr.pptx',
    en: 'teklif-sablon-en.pptx'
  };

  function normalizeLang(lang) {
    return (lang === 'en' || lang === 'eng') ? 'en' : 'tr';
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

  function updateMeta(lang, patch) {
    var meta = getMeta();
    meta[lang] = Object.assign({}, meta[lang] || {}, patch, { lang: lang });
    setMeta(meta);
    return meta[lang];
  }

  function clearMeta(lang) {
    var meta = getMeta();
    delete meta[lang];
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
          db.createObjectStore(STORE_NAME, { keyPath: 'lang' });
        }
      };
      req.onsuccess = function () { resolve(req.result); };
      req.onerror = function () { reject(req.error || new Error('IndexedDB açılamadı.')); };
    });
  }

  async function saveLocalTemplate(lang, file, options) {
    options = options || {};
    var db = await openDb();
    var buffer = await file.arrayBuffer();
    var record = {
      lang: lang,
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
      updateMeta(lang, {
        fileName: file.name,
        fileSize: file.size,
        uploadedAt: record.uploadedAt,
        source: options.source || 'local'
      });
    }
    return record;
  }

  async function getLocalTemplate(lang) {
    var db = await openDb();
    return new Promise(function (resolve, reject) {
      var tx = db.transaction(STORE_NAME, 'readonly');
      var req = tx.objectStore(STORE_NAME).get(lang);
      req.onsuccess = function () { resolve(req.result || null); };
      req.onerror = function () { reject(req.error); };
    });
  }

  async function removeLocalTemplate(lang) {
    var db = await openDb();
    await new Promise(function (resolve, reject) {
      var tx = db.transaction(STORE_NAME, 'readwrite');
      tx.objectStore(STORE_NAME).delete(lang);
      tx.oncomplete = resolve;
      tx.onerror = function () { reject(tx.error); };
    });
    clearMeta(lang);
  }

  async function uploadToSupabase(lang, file) {
    var supabase = resolveSupabaseClient();
    if (!supabase) return null;
    var path = PATHS[lang];
    var { error } = await supabase.storage.from(BUCKET).upload(path, file, {
      upsert: true,
      contentType: 'application/vnd.openxmlformats-officedocument.presentationml.presentation',
      cacheControl: '3600'
    });
    if (error) throw error;
    updateMeta(lang, {
      fileName: file.name,
      fileSize: file.size,
      uploadedAt: new Date().toISOString(),
      source: 'supabase',
      storagePath: path
    });
    await saveLocalTemplate(lang, file);
    return path;
  }

  async function downloadFromSupabase(lang) {
    var supabase = resolveSupabaseClient();
    if (!supabase) return null;
    var path = PATHS[lang];
    var { data, error } = await supabase.storage.from(BUCKET).download(path);
    if (error || !data) return null;
    var buffer = await data.arrayBuffer();
    var meta = getMeta()[lang] || {};
    if (!meta.fileName) {
      try {
        var listRes = await supabase.storage.from(BUCKET).list('', { search: path });
        var item = (listRes.data || []).find(function (f) { return f.name === path; });
        if (item) {
          meta.fileName = item.name;
          meta.fileSize = item.metadata && item.metadata.size ? Number(item.metadata.size) : buffer.byteLength;
          meta.uploadedAt = item.updated_at || item.created_at || new Date().toISOString();
        }
      } catch (e) { /* list optional */ }
    }
    await saveLocalTemplate(lang, new File([buffer], meta.fileName || path, {
      type: 'application/vnd.openxmlformats-officedocument.presentationml.presentation'
    }), { skipMeta: true, source: 'supabase' });
    updateMeta(lang, {
      fileName: meta.fileName || path,
      fileSize: meta.fileSize || buffer.byteLength,
      uploadedAt: meta.uploadedAt || new Date().toISOString(),
      source: 'supabase',
      storagePath: path
    });
    return buffer;
  }

  async function removeFromSupabase(lang) {
    var supabase = resolveSupabaseClient();
    if (!supabase) return;
    var path = PATHS[lang];
    await supabase.storage.from(BUCKET).remove([path]);
  }

  async function uploadTemplate(lang, file) {
    lang = normalizeLang(lang);
    if (!file || !/\.pptx$/i.test(file.name)) {
      throw new Error('Lütfen .pptx uzantılı bir PowerPoint dosyası seçin.');
    }
    if (file.size > 25 * 1024 * 1024) {
      throw new Error('Şablon dosyası en fazla 25 MB olabilir.');
    }

    var supabase = resolveSupabaseClient();
    if (supabase) {
      try {
        await uploadToSupabase(lang, file);
        return getTemplateInfo(lang);
      } catch (err) {
        console.warn('Supabase şablon yükleme başarısız, yerel depoya kaydediliyor:', err);
      }
    }

    await saveLocalTemplate(lang, file);
    return getTemplateInfo(lang);
  }

  async function getTemplateBuffer(lang) {
    lang = normalizeLang(lang);
    var supabase = resolveSupabaseClient();

    if (supabase) {
      try {
        var remote = await downloadFromSupabase(lang);
        if (remote) return remote;
      } catch (err) {
        console.warn('Supabase şablon indirilemedi:', err);
      }
    }

    var local = await getLocalTemplate(lang);
    if (local && local.buffer) return local.buffer;

    return null;
  }

  function getTemplateInfo(lang) {
    lang = normalizeLang(lang);
    var meta = getMeta()[lang];
    if (!meta) return { lang: lang, uploaded: false };
    return {
      lang: lang,
      uploaded: true,
      fileName: meta.fileName || PATHS[lang],
      fileSize: meta.fileSize || 0,
      uploadedAt: meta.uploadedAt || null,
      source: meta.source || 'unknown'
    };
  }

  function getAllTemplateInfo() {
    return {
      tr: getTemplateInfo('tr'),
      en: getTemplateInfo('en')
    };
  }

  async function removeTemplate(lang) {
    lang = normalizeLang(lang);
    try {
      await removeFromSupabase(lang);
    } catch (err) {
      console.warn('Supabase şablon silinemedi:', err);
    }
    try {
      await removeLocalTemplate(lang);
    } catch (err) {
      console.warn('Yerel şablon silinemedi:', err);
    }
    return getTemplateInfo(lang);
  }

  async function downloadTemplateCopy(lang) {
    lang = normalizeLang(lang);
    var buffer = await getTemplateBuffer(lang);
    if (!buffer) throw new Error('İndirilecek şablon bulunamadı.');
    var info = getTemplateInfo(lang);
    return {
      blob: new Blob([buffer], { type: 'application/vnd.openxmlformats-officedocument.presentationml.presentation' }),
      fileName: info.fileName || PATHS[lang]
    };
  }

  async function syncFromSupabase() {
    var supabase = resolveSupabaseClient();
    if (!supabase) return;
    await Promise.all(['tr', 'en'].map(async function (lang) {
      try {
        await downloadFromSupabase(lang);
      } catch (e) {
        /* şablon yoksa sorun değil */
      }
    }));
  }

  global.OfferTemplateStore = {
    BUCKET: BUCKET,
    PATHS: PATHS,
    uploadTemplate: uploadTemplate,
    getTemplateBuffer: getTemplateBuffer,
    getTemplateInfo: getTemplateInfo,
    getAllTemplateInfo: getAllTemplateInfo,
    removeTemplate: removeTemplate,
    downloadTemplateCopy: downloadTemplateCopy,
    syncFromSupabase: syncFromSupabase
  };
})(typeof window !== 'undefined' ? window : global);
