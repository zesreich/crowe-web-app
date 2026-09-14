// Rotasyon store — Excel satırları + Rapor Listesi kayıtlarının birleşik görünümü
(function (global) {
  'use strict';

  var STORAGE_KEY = 'rotasyon';
  var RAW_STORAGE_KEY = 'rotasyon_last_excel';

  var ROTATION_HEADER_GROUPS = [
    ['sozlesme id', 'sozlesmeid', 'sozlesme no', 'sozlesme'],
    ['denetlenen sirket', 'denetlenen sirketi', 'denetlenen kurulus', 'sirket unvan', 'sirket'],
    ['denetime tabi olma nedeni', 'denetime tabi olma', 'denetime tabi olmasi', 'tabi olma nedeni', 'tabi olma sebebi', 'mevzuat'],
    ['denetim kapsami', 'denetim kapsam', 'kapsam'],
    ['denetim baslangic donemi', 'denetim baslangic tarihi', 'denetim baslangic', 'baslangic donemi', 'baslangic tarihi'],
    ['denetim bitis donemi', 'denetim bitis tarihi', 'denetim bitis', 'bitis donemi', 'bitis tarihi']
  ];

  var PAYMENT_HEADER_MARKERS = ['hesap kodu', 'hesap adi', 'cari kod', 'cari unvan', 'borc bakiyesi', 'alacak bakiyesi'];

  function readLocal() {
    try {
      return JSON.parse(localStorage.getItem(STORAGE_KEY) || '[]');
    } catch (e) {
      return [];
    }
  }

  function writeLocal(list) {
    localStorage.setItem(STORAGE_KEY, JSON.stringify(list || []));
  }

  function trimDisplay(value) {
    if (value == null || value === '') return '';
    if (value instanceof Date) {
      return String(value.getDate()).padStart(2, '0') + '/' +
        String(value.getMonth() + 1).padStart(2, '0') + '/' +
        value.getFullYear();
    }
    return String(value).trim();
  }

  function isDateLike(value) {
    return value instanceof Date || (typeof value === 'object' && value && typeof value.getTime === 'function');
  }

  function isExcelSerialDate(value) {
    if (typeof value !== 'number' || !isFinite(value)) return false;
    if (value < 20000) return false;
    if (!global.XLSX || !global.XLSX.SSF) return false;
    try {
      var parsed = global.XLSX.SSF.parse_date_code(value);
      return !!(parsed && parsed.y >= 1990 && parsed.y <= 2100);
    } catch (e) {
      return false;
    }
  }

  function formatPeriodDisplay(value) {
    if (value == null || value === '') return '—';
    var raw = trimDisplay(value);
    if (!raw) return '—';
    if (/^\d{1,2}[./]\d{1,2}[./]\d{2,4}$/.test(raw)) return raw;
    if (/^\d{4}-\d{2}-\d{2}/.test(raw)) {
      var p = raw.slice(0, 10).split('-');
      return p[2] + '/' + p[1] + '/' + p[0];
    }
    if (isDateLike(value)) {
      var d = value instanceof Date ? value : new Date(value);
      if (!isNaN(d.getTime())) {
        return String(d.getDate()).padStart(2, '0') + '/' +
          String(d.getMonth() + 1).padStart(2, '0') + '/' +
          d.getFullYear();
      }
    }
    if (isExcelSerialDate(value)) {
      try {
        var parsed = global.XLSX.SSF.parse_date_code(value);
        if (parsed) {
          return String(parsed.d).padStart(2, '0') + '/' +
            String(parsed.m).padStart(2, '0') + '/' +
            parsed.y;
        }
      } catch (e) {}
    }
    return raw;
  }

  function repairRow(row) {
    return {
      id: row.id,
      sozlesmeId: trimDisplay(row.sozlesmeId),
      denetlenenSirket: trimDisplay(row.denetlenenSirket),
      denetimeTabiOlmaNedeni: trimDisplay(row.denetimeTabiOlmaNedeni),
      denetimKapsami: trimDisplay(row.denetimKapsami),
      denetimBaslangicDonemi: trimDisplay(row.denetimBaslangicDonemi),
      denetimBitisDonemi: trimDisplay(row.denetimBitisDonemi),
      source: row.source || 'excel',
      createdAt: row.createdAt || null
    };
  }

  function rowKey(row) {
    return [
      row.sozlesmeId,
      row.denetlenenSirket,
      row.denetimeTabiOlmaNedeni,
      row.denetimKapsami,
      row.denetimBaslangicDonemi,
      row.denetimBitisDonemi
    ].join('|').toLocaleLowerCase('tr');
  }

  function dedupeRows(rows) {
    var seen = {};
    var out = [];
    rows.forEach(function (row) {
      var key = rowKey(row);
      if (seen[key]) return;
      seen[key] = true;
      out.push(row);
    });
    return out;
  }

  function mapReportToRotation(report) {
    return {
      id: 'report_' + String(report.id),
      sozlesmeId: '',
      denetlenenSirket: trimDisplay(report.company),
      denetimeTabiOlmaNedeni: trimDisplay(report.reportType),
      denetimKapsami: trimDisplay(report.service),
      denetimBaslangicDonemi: trimDisplay(report.startDate),
      denetimBitisDonemi: trimDisplay(report.endDate),
      source: 'report'
    };
  }

  function saveRawExcel(rows) {
    try {
      localStorage.setItem(RAW_STORAGE_KEY, JSON.stringify(rows || []));
    } catch (e) {
      console.warn('saveRawExcel:', e);
    }
  }

  function loadRawExcel() {
    try {
      return JSON.parse(localStorage.getItem(RAW_STORAGE_KEY) || 'null');
    } catch (e) {
      return null;
    }
  }

  function looksLikeAccountCode(value) {
    return /^\d{3}\.\d{2}\.\d{2}(\.\d+)?$/.test(String(value || '').trim());
  }

  function isCorruptedRotationData(rows) {
    if (!rows || !rows.length) return false;
    var excelRows = rows.filter(function (r) { return r.source !== 'report'; });
    if (!excelRows.length) return false;
    var bad = excelRows.filter(function (row) {
      return looksLikeAccountCode(row.sozlesmeId) || looksLikeAccountCode(row.denetimeTabiOlmaNedeni);
    }).length;
    return bad / excelRows.length >= 0.3;
  }

  async function listRotation() {
    var excelRows = dedupeRows(readLocal().map(function (row) {
      return repairRow(Object.assign({}, row, { source: row.source || 'excel' }));
    }));

    if (excelRows.length && isCorruptedRotationData(excelRows)) {
      var raw = loadRawExcel();
      if (raw && raw.length) {
        var reparsed = importRotationRows(raw);
        if (reparsed.success && reparsed.rows.length) {
          excelRows = reparsed.rows;
        }
      }
    }

    if (excelRows.length) writeLocal(excelRows);

    var reportRows = [];
    if (global.ReportsStore && typeof global.ReportsStore.listReports === 'function') {
      try {
        var reports = await global.ReportsStore.listReports();
        reportRows = (reports || []).map(mapReportToRotation);
      } catch (e) {
        console.warn('listRotation reports:', e);
      }
    }

    var all = dedupeRows(excelRows.concat(reportRows));
    all.sort(function (a, b) {
      return String(b.denetimBaslangicDonemi || '').localeCompare(String(a.denetimBaslangicDonemi || ''), 'tr');
    });
    return all;
  }

  function addRows(rows, options) {
    options = options || {};
    if (!rows || !rows.length) return { success: true, count: 0 };

    var added = rows.map(function (r, i) {
      return repairRow({
        id: 'excel_' + Date.now() + '_' + i,
        sozlesmeId: r.sozlesmeId,
        denetlenenSirket: r.denetlenenSirket,
        denetimeTabiOlmaNedeni: r.denetimeTabiOlmaNedeni,
        denetimKapsami: r.denetimKapsami,
        denetimBaslangicDonemi: r.denetimBaslangicDonemi,
        denetimBitisDonemi: r.denetimBitisDonemi,
        source: 'excel',
        createdAt: new Date().toISOString()
      });
    });

    var base = options.replace ? [] : readLocal().map(repairRow);
    writeLocal(dedupeRows(base.concat(added)));
    return { success: true, count: added.length, data: added };
  }

  function clearExcelRows() {
    writeLocal([]);
    localStorage.removeItem(RAW_STORAGE_KEY);
    return { success: true };
  }

  function sheetToDisplayGrid(sheet) {
    if (!sheet || !sheet['!ref']) return [];
    var range = global.XLSX.utils.decode_range(sheet['!ref']);
    var rows = [];
    var R;
    var C;
    for (R = range.s.r; R <= range.e.r; R++) {
      var row = [];
      for (C = range.s.c; C <= range.e.c; C++) {
        var addr = global.XLSX.utils.encode_cell({ r: R, c: C });
        var cell = sheet[addr];
        if (!cell) {
          row.push('');
          continue;
        }
        if (cell.w != null && String(cell.w).trim() !== '') {
          row.push(String(cell.w).trim());
        } else if (cell.v instanceof Date) {
          row.push(trimDisplay(cell.v));
        } else if (cell.v == null) {
          row.push('');
        } else {
          row.push(String(cell.v).trim());
        }
      }
      rows.push(row);
    }
    return rows;
  }

  function normalizeHeaderKey(value) {
    return global.AtlasTableTools
      ? global.AtlasTableTools.normalizeKey(value)
      : String(value || '').toLowerCase();
  }

  function buildHeaderEntries(headerRow) {
    return global.AtlasTableTools
      ? global.AtlasTableTools.buildHeaderEntries(headerRow)
      : [];
  }

  function findColumn(entries, aliases, used) {
    if (!global.AtlasTableTools) return -1;
    return global.AtlasTableTools.findColumn(entries, aliases, {
      excludeIdx: used || []
    });
  }

  function isPaymentExcelHeader(headerRow) {
    var joined = (headerRow || []).map(normalizeHeaderKey).join(' ');
    return PAYMENT_HEADER_MARKERS.some(function (marker) {
      return joined.indexOf(marker) !== -1;
    });
  }

  function findRotationHeaderIndex(rows) {
    var bestIdx = -1;
    var bestScore = 0;
    var limit = Math.min(rows.length, 30);
    var i;
    for (i = 0; i < limit; i++) {
      var headerRow = buildCombinedHeaderRow(rows, i, 1);
      if (isPaymentExcelHeader(headerRow)) continue;
      var entries = buildHeaderEntries(headerRow);
      var score = 0;
      ROTATION_HEADER_GROUPS.forEach(function (aliases) {
        if (findColumn(entries, aliases, []) >= 0) score += 1;
      });
      if (score > bestScore) {
        bestScore = score;
        bestIdx = i;
      }
    }
    return { index: bestIdx >= 0 ? bestIdx : 0, score: bestScore };
  }

  function buildCombinedHeaderRow(rows, headerIdx, depth) {
    var width = 0;
    var maxDepth = Math.min(depth || 2, rows.length - headerIdx);
    var i;
    for (i = headerIdx; i < headerIdx + maxDepth; i++) {
      width = Math.max(width, (rows[i] || []).length);
    }
    var combined = [];
    var c;
    for (c = 0; c < width; c++) {
      var parts = [];
      for (i = headerIdx; i < headerIdx + maxDepth; i++) {
        var part = String((rows[i] || [])[c] || '').trim();
        if (part) parts.push(part);
      }
      combined[c] = parts.join(' ');
    }
    return combined;
  }

  function resolveRotationColumnsStrict(headerRow) {
    var entries = buildHeaderEntries(headerRow);
    var used = [];
    function take(aliases) {
      var idx = findColumn(entries, aliases, used);
      if (idx >= 0) used.push(idx);
      return idx;
    }
    return {
      sozlesmeId: take(['sozlesme id', 'sozlesmeid', 'sozlesme no', 'sozlesme']),
      denetlenenSirket: take(['denetlenen sirket', 'denetlenen sirketi', 'denetlenen kurulus', 'sirket unvan', 'sirket', 'musteri']),
      denetimeTabiOlmaNedeni: take(['denetime tabi olma nedeni', 'denetime tabi olma', 'denetime tabi olmasi', 'tabi olma nedeni', 'tabi olma sebebi', 'mevzuat']),
      denetimKapsami: take(['denetim kapsami', 'denetim kapsam', 'kapsam', 'denetim turu']),
      denetimBaslangicDonemi: take(['denetim baslangic donemi', 'denetim baslangic tarihi', 'denetim baslangic', 'baslangic donemi', 'baslangic tarihi']),
      denetimBitisDonemi: take(['denetim bitis donemi', 'denetim bitis tarihi', 'denetim bitis', 'bitis donemi', 'bitis tarihi'])
    };
  }

  function countMatchedColumns(cols) {
    return Object.keys(cols).filter(function (key) { return cols[key] >= 0; }).length;
  }

  function cellAt(raw, idx) {
    if (idx < 0) return '';
    return trimDisplay(raw[idx]);
  }

  function parseRotationRow(raw, cols) {
    return {
      sozlesmeId: cellAt(raw, cols.sozlesmeId),
      denetlenenSirket: cellAt(raw, cols.denetlenenSirket),
      denetimeTabiOlmaNedeni: cellAt(raw, cols.denetimeTabiOlmaNedeni),
      denetimKapsami: cellAt(raw, cols.denetimKapsami),
      denetimBaslangicDonemi: cellAt(raw, cols.denetimBaslangicDonemi),
      denetimBitisDonemi: cellAt(raw, cols.denetimBitisDonemi)
    };
  }

  function isJunkRotationRow(item, raw) {
    var joined = (raw || []).map(function (v) { return String(v || '').trim(); }).join(' ').toLocaleLowerCase('tr');
    if (/^d[oö]nem\s*:/.test(joined)) return true;
    if (/^toplam|^genel toplam/.test(joined)) return true;
    if (/sozlesme id/.test(joined) && /denetlenen sirket/.test(joined)) return true;
    if (/hesap kodu/.test(joined) && /hesap adi|borc|alacak/.test(joined)) return true;
    if (!item.denetlenenSirket && !item.sozlesmeId) return true;
    if (looksLikeAccountCode(item.sozlesmeId)) return true;
    return false;
  }

  function importRotationRows(rows) {
    if (!rows || rows.length < 2) {
      return { success: false, error: 'Excel dosyası boş veya geçersiz.' };
    }

    var headerMatch = findRotationHeaderIndex(rows);
    var headerIdx = headerMatch.index;
    var headerRow = buildCombinedHeaderRow(rows, headerIdx, 2);

    if (isPaymentExcelHeader(headerRow)) {
      return {
        success: false,
        error: 'Bu dosya Ödeme Listesi formatında görünüyor. Rotasyon sayfasına Ödeme Listesi Excel\'i yüklenemez. Doğru rotasyon dosyasını veya taslağı kullanın.'
      };
    }

    var cols = resolveRotationColumnsStrict(headerRow);
    if (countMatchedColumns(cols) < 4) {
      return {
        success: false,
        error: 'Rotasyon başlıkları bulunamadı. Excel\'de şu sütunlar olmalı: Sozlesme ID, Denetlenen Sirket, Denetime Tabi Olma Nedeni, Denetim Kapsamı, Denetim Baslangic Donemi, Denetim Bitis Donemi'
      };
    }

    if (cols.denetlenenSirket < 0) {
      return { success: false, error: '“Denetlenen Sirket” sütunu bulunamadı.' };
    }

    var imported = [];
    var skipped = 0;
    var i;
    for (i = headerIdx + 1; i < rows.length; i++) {
      var raw = rows[i] || [];
      var item = parseRotationRow(raw, cols);
      if (isJunkRotationRow(item, raw)) {
        skipped += 1;
        continue;
      }
      if (!item.denetlenenSirket) {
        skipped += 1;
        continue;
      }
      imported.push(item);
    }

    if (!imported.length) {
      return { success: false, error: 'Aktarılacak geçerli rotasyon satırı bulunamadı.' };
    }

    return {
      success: true,
      rows: imported.map(function (item, idx) {
        return repairRow({
          id: 'excel_' + Date.now() + '_' + idx,
          sozlesmeId: item.sozlesmeId,
          denetlenenSirket: item.denetlenenSirket,
          denetimeTabiOlmaNedeni: item.denetimeTabiOlmaNedeni,
          denetimKapsami: item.denetimKapsami,
          denetimBaslangicDonemi: item.denetimBaslangicDonemi,
          denetimBitisDonemi: item.denetimBitisDonemi,
          source: 'excel',
          createdAt: new Date().toISOString()
        });
      }),
      skipped: skipped,
      headerIdx: headerIdx,
      cols: cols
    };
  }

  function parseRotationSheet(sheet) {
    var rows = sheetToDisplayGrid(sheet);
    return importRotationRows(rows);
  }

  global.RotasyonStore = {
    listRotation: listRotation,
    addRows: addRows,
    clearExcelRows: clearExcelRows,
    saveRawExcel: saveRawExcel,
    loadRawExcel: loadRawExcel,
    formatPeriodDisplay: formatPeriodDisplay,
    repairRow: repairRow,
    sheetToDisplayGrid: sheetToDisplayGrid,
    parseRotationSheet: parseRotationSheet,
    importRotationRows: importRotationRows,
    isCorruptedRotationData: isCorruptedRotationData,
    looksLikeAccountCode: looksLikeAccountCode,
    HEADERS: [
      'Sozlesme ID',
      'Denetlenen Sirket',
      'Denetime Tabi Olma Nedeni',
      'Denetim Kapsamı',
      'Denetim Baslangic Donemi',
      'Denetim Bitis Donemi'
    ]
  };
})(typeof window !== 'undefined' ? window : this);
