// Rotasyon store — Excel satırları + Rapor Listesi kayıtlarının birleşik görünümü
(function (global) {
  'use strict';

  var STORAGE_KEY = 'rotasyon';
  var RAW_STORAGE_KEY = 'rotasyon_last_excel';

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

  function isDateLike(value) {
    return value instanceof Date || (typeof value === 'object' && value && typeof value.getTime === 'function');
  }

  function normalizePeriod(value) {
    if (value == null || value === '') return '';
    if (isDateLike(value)) {
      var d = value instanceof Date ? value : new Date(value);
      if (!isNaN(d.getTime())) {
        return d.getFullYear() + '-' + String(d.getMonth() + 1).padStart(2, '0') + '-' + String(d.getDate()).padStart(2, '0');
      }
    }
    if (isExcelSerialDate(value)) {
      try {
        var parsedPeriod = global.XLSX.SSF.parse_date_code(value);
        if (parsedPeriod) {
          return parsedPeriod.y + '-' + String(parsedPeriod.m).padStart(2, '0') + '-' + String(parsedPeriod.d).padStart(2, '0');
        }
      } catch (e) {}
    }
    var raw = String(value).trim();
    if (/GMT|Standart Saati|Türkiye/.test(raw)) {
      var dt = new Date(raw);
      if (!isNaN(dt.getTime())) {
        return dt.getFullYear() + '-' + String(dt.getMonth() + 1).padStart(2, '0') + '-' + String(dt.getDate()).padStart(2, '0');
      }
      return '';
    }
    var iso = raw.match(/^(\d{4})-(\d{1,2})-(\d{1,2})/);
    if (iso) {
      return iso[1] + '-' + iso[2].padStart(2, '0') + '-' + iso[3].padStart(2, '0');
    }
    var tr = raw.match(/^(\d{1,2})[./](\d{1,2})[./](\d{2,4})$/);
    if (tr) {
      var yy = tr[3].length === 2 ? ('20' + tr[3]) : tr[3];
      return yy + '-' + tr[2].padStart(2, '0') + '-' + tr[1].padStart(2, '0');
    }
    return raw;
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

  function normalizeText(value) {
    if (value == null || value === '') return '';
    if (isDateLike(value)) return '';
    if (typeof value === 'number' && isFinite(value)) {
      if (isExcelSerialDate(value) || isCellDate(value)) return '';
      return String(value);
    }
    var raw = String(value).trim();
    if (/GMT|Standart Saati|Türkiye/.test(raw)) return '';
    return raw;
  }

  function formatPeriodDisplay(value) {
    if (value == null || value === '') return '—';
    var normalized = normalizePeriod(value);
    if (!normalized) return '—';
    if (/^\d{4}-\d{2}-\d{2}/.test(normalized)) {
      var p = normalized.slice(0, 10).split('-');
      return p[2] + '/' + p[1] + '/' + p[0];
    }
    return normalized;
  }

  function repairRow(row) {
    return {
      id: row.id,
      sozlesmeId: normalizeText(row.sozlesmeId),
      denetlenenSirket: normalizeText(row.denetlenenSirket),
      denetimeTabiOlmaNedeni: normalizeText(row.denetimeTabiOlmaNedeni),
      denetimKapsami: normalizeText(row.denetimKapsami),
      denetimBaslangicDonemi: normalizePeriod(row.denetimBaslangicDonemi),
      denetimBitisDonemi: normalizePeriod(row.denetimBitisDonemi),
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
      denetlenenSirket: report.company || '',
      denetimeTabiOlmaNedeni: report.reportType || '',
      denetimKapsami: report.service || '',
      denetimBaslangicDonemi: normalizePeriod(report.startDate),
      denetimBitisDonemi: normalizePeriod(report.endDate),
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

  function needsReasonScopeRepair(rows) {
    if (!rows || !rows.length) return false;
    var empty = rows.filter(function (row) {
      return !row.denetimeTabiOlmaNedeni && !row.denetimKapsami;
    }).length;
    return empty / rows.length >= 0.8;
  }

  async function listRotation() {
    var excelRows = dedupeRows(readLocal().map(function (row) {
      return repairRow(Object.assign({}, row, { source: row.source || 'excel' }));
    }));

    if (excelRows.length && needsReasonScopeRepair(excelRows)) {
      var raw = loadRawExcel();
      if (raw && raw.length) {
        var remapped = remapStoredExcelRows(excelRows, raw);
        if (!needsReasonScopeRepair(remapped)) {
          excelRows = remapped;
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
      return String(b.denetimBaslangicDonemi || '').localeCompare(String(a.denetimBaslangicDonemi || ''));
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

  function isCellDate(value) {
    if (value == null || value === '') return false;
    if (isDateLike(value)) return true;
    if (isExcelSerialDate(value)) return true;
    var raw = String(value).trim();
    if (/GMT|Standart Saati|Türkiye/.test(raw)) return true;
    if (/^\d{1,2}[./]\d{1,2}[./]\d{2,4}$/.test(raw)) return true;
    if (/^\d{4}-\d{2}-\d{2}/.test(raw)) return true;
    return false;
  }

  function isTextColumn(dataRows, colIdx) {
    if (colIdx < 0) return false;
    var textHits = 0;
    var dateHits = 0;
    var total = 0;
    dataRows.slice(0, 40).forEach(function (row) {
      var v = row[colIdx];
      if (v == null || String(v).trim() === '') return;
      total += 1;
      if (isCellDate(v)) dateHits += 1;
      else textHits += 1;
    });
    return total >= 2 && textHits >= dateHits;
  }

  function getSampleWidth(dataRows) {
    var width = 0;
    dataRows.forEach(function (row) {
      width = Math.max(width, (row || []).length);
    });
    return width;
  }

  function applyStandardSixColumnLayout(cols, dataRows) {
    if (getSampleWidth(dataRows) < 6) return cols;
    if (cols.sozlesmeId < 0) cols.sozlesmeId = 0;
    if (cols.denetlenenSirket < 0) cols.denetlenenSirket = 1;
    if (cols.denetimeTabiOlmaNedeni < 0 || cols.denetimeTabiOlmaNedeni === cols.denetimBaslangicDonemi || cols.denetimeTabiOlmaNedeni === cols.denetimBitisDonemi) {
      cols.denetimeTabiOlmaNedeni = 2;
    }
    if (cols.denetimKapsami < 0 || cols.denetimKapsami === cols.denetimBaslangicDonemi || cols.denetimKapsami === cols.denetimBitisDonemi) {
      cols.denetimKapsami = 3;
    }
    if (cols.denetimBaslangicDonemi < 0) cols.denetimBaslangicDonemi = 4;
    if (cols.denetimBitisDonemi < 0) cols.denetimBitisDonemi = 5;
    return cols;
  }

  function buildCombinedHeaderRow(rows, headerIdx) {
    var width = 0;
    var depth = Math.min(3, rows.length - headerIdx);
    var i;
    for (i = headerIdx; i < headerIdx + depth; i++) {
      width = Math.max(width, (rows[i] || []).length);
    }
    var combined = [];
    var c;
    for (c = 0; c < width; c++) {
      var parts = [];
      for (i = headerIdx; i < headerIdx + depth; i++) {
        var part = String((rows[i] || [])[c] || '').trim();
        if (part) parts.push(part);
      }
      combined[c] = parts.join(' ');
    }
    return combined;
  }

  function findRotationHeaderIndex(rows) {
    var aliasGroups = [
      ['sozlesme id', 'sozlesmeid', 'sozlesme no'],
      ['denetlenen sirket', 'denetlenen kurulus', 'sirket'],
      ['denetime tabi olma', 'tabi olma nedeni', 'tabi olma sebebi'],
      ['denetim kapsami', 'denetim kapsam', 'kapsam'],
      ['denetim baslangic', 'baslangic donemi', 'baslangic tarihi'],
      ['denetim bitis', 'bitis donemi', 'bitis tarihi']
    ];
    if (global.AtlasTableTools) {
      return global.AtlasTableTools.findBestHeaderRow(rows, aliasGroups, 25).index;
    }
    return 0;
  }

  function resolveRotationColumns(headerRow, dataRows) {
    var tools = global.AtlasTableTools;
    var entries = tools ? tools.buildHeaderEntries(headerRow) : [];
    var used = [];

    function take(aliases, options) {
      if (!tools) return -1;
      options = options || {};
      var idx = tools.findColumn(entries, aliases, {
        excludeIdx: used.concat(options.excludeIdx || []),
        keyFilter: options.keyFilter || null
      });
      if (idx >= 0) used.push(idx);
      return idx;
    }

    var cols = {
      sozlesmeId: take(['sozlesme id', 'sozlesmeid', 'sozlesme no', 'sozlesme']),
      denetlenenSirket: take(['denetlenen sirket', 'denetlenen sirketi', 'denetlenen kurulus', 'sirket unvan', 'sirket', 'musteri']),
      denetimeTabiOlmaNedeni: take(['denetime tabi olma nedeni', 'denetime tabi olma', 'denetime tabi olmasi', 'tabi olma nedeni', 'tabi olma sebebi', 'mevzuat']),
      denetimKapsami: take(['denetim kapsami', 'denetim kapsam', 'kapsam', 'denetim turu']),
      denetimBaslangicDonemi: take(['denetim baslangic donemi', 'denetim baslangic tarihi', 'denetim baslangic', 'baslangic donemi', 'baslangic tarihi'], {
        keyFilter: function (key) { return key.indexOf('bitis') === -1; }
      }),
      denetimBitisDonemi: take(['denetim bitis donemi', 'denetim bitis tarihi', 'denetim bitis', 'bitis donemi', 'bitis tarihi'])
    };

    var dateCols = [];
    var c;
    for (c = 0; c < 12; c++) {
      if (!isTextColumn(dataRows, c) && dataRows.slice(0, 20).some(function (row) { return isCellDate(row[c]); })) {
        dateCols.push(c);
      }
    }
    dateCols.sort(function (a, b) { return a - b; });

    if (cols.denetimBaslangicDonemi < 0 && dateCols.length) cols.denetimBaslangicDonemi = dateCols[0];
    if (cols.denetimBitisDonemi < 0 && dateCols.length > 1) cols.denetimBitisDonemi = dateCols[1];

    if (cols.denetimeTabiOlmaNedeni < 0 || !isTextColumn(dataRows, cols.denetimeTabiOlmaNedeni) || cols.denetimeTabiOlmaNedeni === cols.denetimBaslangicDonemi) {
      if (cols.denetimBaslangicDonemi === 4 && cols.denetimBitisDonemi === 5) {
        cols.denetimeTabiOlmaNedeni = 2;
        cols.denetimKapsami = 3;
      } else if (cols.denetimBaslangicDonemi === 2 && cols.denetimBitisDonemi === 3) {
        cols.denetimeTabiOlmaNedeni = 4;
        cols.denetimKapsami = 5;
      }
    }

    if (cols.denetimKapsami < 0 || !isTextColumn(dataRows, cols.denetimKapsami) || cols.denetimKapsami === cols.denetimBaslangicDonemi || cols.denetimKapsami === cols.denetimBitisDonemi) {
      var textCols = [];
      for (c = 0; c < 12; c++) {
        if ([cols.sozlesmeId, cols.denetlenenSirket, cols.denetimBaslangicDonemi, cols.denetimBitisDonemi, cols.denetimeTabiOlmaNedeni].indexOf(c) !== -1) continue;
        if (isTextColumn(dataRows, c)) textCols.push(c);
      }
      textCols.sort(function (a, b) { return a - b; });
      if (cols.denetimeTabiOlmaNedeni < 0 || !isTextColumn(dataRows, cols.denetimeTabiOlmaNedeni)) {
        cols.denetimeTabiOlmaNedeni = textCols[0] != null ? textCols[0] : cols.denetimeTabiOlmaNedeni;
      }
      if (cols.denetimKapsami < 0 || !isTextColumn(dataRows, cols.denetimKapsami)) {
        cols.denetimKapsami = textCols[1] != null ? textCols[1] : textCols[0];
      }
    }

    cols = applyStandardSixColumnLayout(cols, dataRows);
    if (cols.sozlesmeId < 0) cols.sozlesmeId = 0;
    if (cols.denetlenenSirket < 0) cols.denetlenenSirket = 1;

    return cols;
  }

  function resolveRotationColumnsFromSheet(rows) {
    var headerIdx = findRotationHeaderIndex(rows);
    var headerRow = buildCombinedHeaderRow(rows, headerIdx);
    var dataSample = rows.slice(headerIdx + 1, headerIdx + 41);
    var cols = resolveRotationColumns(headerRow, dataSample);
    return { headerIdx: headerIdx, cols: cols };
  }

  function parseRotationRow(raw, cols) {
    function textAt(idx) {
      if (idx < 0) return '';
      return normalizeText(raw[idx]);
    }
    function periodAt(idx) {
      if (idx < 0) return '';
      return normalizePeriod(raw[idx]);
    }
    return {
      sozlesmeId: textAt(cols.sozlesmeId),
      denetlenenSirket: textAt(cols.denetlenenSirket),
      denetimeTabiOlmaNedeni: textAt(cols.denetimeTabiOlmaNedeni),
      denetimKapsami: textAt(cols.denetimKapsami),
      denetimBaslangicDonemi: periodAt(cols.denetimBaslangicDonemi),
      denetimBitisDonemi: periodAt(cols.denetimBitisDonemi)
    };
  }

  function remapStoredExcelRows(rows, sheetRows) {
    if (!sheetRows || sheetRows.length < 2 || !rows || !rows.length) return rows;
    var resolved = resolveRotationColumnsFromSheet(sheetRows);
    var headerIdx = resolved.headerIdx;
    var cols = resolved.cols;
    var mapped = [];
    for (var i = headerIdx + 1; i < sheetRows.length; i++) {
      var parsed = parseRotationRow(sheetRows[i] || [], cols);
      if (!parsed.denetlenenSirket) continue;
      mapped.push(repairRow({
        id: 'excel_' + Date.now() + '_' + i,
        sozlesmeId: parsed.sozlesmeId,
        denetlenenSirket: parsed.denetlenenSirket,
        denetimeTabiOlmaNedeni: parsed.denetimeTabiOlmaNedeni,
        denetimKapsami: parsed.denetimKapsami,
        denetimBaslangicDonemi: parsed.denetimBaslangicDonemi,
        denetimBitisDonemi: parsed.denetimBitisDonemi,
        source: 'excel',
        createdAt: new Date().toISOString()
      }));
    }
    return mapped.length ? mapped : rows;
  }

  global.RotasyonStore = {
    listRotation: listRotation,
    addRows: addRows,
    clearExcelRows: clearExcelRows,
    saveRawExcel: saveRawExcel,
    loadRawExcel: loadRawExcel,
    normalizePeriod: normalizePeriod,
    normalizeText: normalizeText,
    formatPeriodDisplay: formatPeriodDisplay,
    repairRow: repairRow,
    resolveRotationColumns: resolveRotationColumns,
    resolveRotationColumnsFromSheet: resolveRotationColumnsFromSheet,
    parseRotationRow: parseRotationRow,
    remapStoredExcelRows: remapStoredExcelRows,
    buildCombinedHeaderRow: buildCombinedHeaderRow,
    findRotationHeaderIndex: findRotationHeaderIndex,
    isCellDate: isCellDate,
    isExcelSerialDate: isExcelSerialDate,
    isTextColumn: isTextColumn,
    applyStandardSixColumnLayout: applyStandardSixColumnLayout,
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
