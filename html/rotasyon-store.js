// Rotasyon store — Excel satırları + Rapor Listesi kayıtlarının birleşik görünümü
(function (global) {
  'use strict';

  var STORAGE_KEY = 'rotasyon';

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
    if (typeof value === 'number' && isFinite(value) && global.XLSX && global.XLSX.SSF) {
      try {
        var parsed = global.XLSX.SSF.parse_date_code(value);
        if (parsed) {
          return parsed.y + '-' + String(parsed.m).padStart(2, '0') + '-' + String(parsed.d).padStart(2, '0');
        }
      } catch (e) {}
    }
    var raw = String(value).trim();
    // JS Date string kalıntısı
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

  function normalizeText(value) {
    if (value == null || value === '') return '';
    if (isDateLike(value)) return '';
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

  async function listRotation() {
    var excelRows = dedupeRows(readLocal().map(function (row) {
      return repairRow(Object.assign({}, row, { source: row.source || 'excel' }));
    }));

    // Bozuk kayıtları düzeltip geri yaz
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
    return { success: true };
  }

  global.RotasyonStore = {
    listRotation: listRotation,
    addRows: addRows,
    clearExcelRows: clearExcelRows,
    normalizePeriod: normalizePeriod,
    normalizeText: normalizeText,
    formatPeriodDisplay: formatPeriodDisplay,
    repairRow: repairRow,
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
