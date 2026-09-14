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

  function normalizePeriod(value) {
    if (value == null || value === '') return '';
    if (typeof value === 'number' && isFinite(value) && global.XLSX && global.XLSX.SSF) {
      try {
        var parsed = global.XLSX.SSF.parse_date_code(value);
        if (parsed) {
          return parsed.y + '-' + String(parsed.m).padStart(2, '0') + '-' + String(parsed.d).padStart(2, '0');
        }
      } catch (e) {}
    }
    var raw = String(value).trim();
    var iso = raw.match(/^(\d{4})-(\d{1,2})-(\d{1,2})$/);
    if (iso) {
      return iso[1] + '-' + iso[2].padStart(2, '0') + '-' + iso[3].padStart(2, '0');
    }
    var tr = raw.match(/^(\d{1,2})[./](\d{1,2})[./](\d{2,4})$/);
    if (tr) {
      var yy = tr[3].length === 2 ? ('20' + tr[3]) : tr[3];
      return yy + '-' + tr[2].padStart(2, '0') + '-' + tr[1].padStart(2, '0');
    }
    // Dönem metni (ör. 2024 / 2024-Q1) olduğu gibi sakla
    return raw;
  }

  function formatPeriodDisplay(value) {
    if (!value) return '—';
    var s = String(value);
    if (/^\d{4}-\d{2}-\d{2}/.test(s)) {
      var p = s.slice(0, 10).split('-');
      return p[2] + '/' + p[1] + '/' + p[0];
    }
    return s;
  }

  function mapReportToRotation(report) {
    return {
      id: 'report_' + String(report.id),
      sozlesmeId: '',
      denetlenenSirket: report.company || '',
      denetimeTabiOlmaNedeni: report.reportType || '',
      denetimKapsami: report.service || '',
      denetimBaslangicDonemi: report.startDate || '',
      denetimBitisDonemi: report.endDate || '',
      source: 'report'
    };
  }

  async function listRotation() {
    var excelRows = readLocal().map(function (row) {
      return Object.assign({}, row, { source: row.source || 'excel' });
    });

    var reportRows = [];
    if (global.ReportsStore && typeof global.ReportsStore.listReports === 'function') {
      try {
        var reports = await global.ReportsStore.listReports();
        reportRows = (reports || []).map(mapReportToRotation);
      } catch (e) {
        console.warn('listRotation reports:', e);
      }
    }

    // Excel önce, sonra raporlar; aynı şirket+dönem tekrarını basitçe göster (ayrı kaynaklar)
    var all = excelRows.concat(reportRows);
    all.sort(function (a, b) {
      return String(b.denetimBaslangicDonemi || '').localeCompare(String(a.denetimBaslangicDonemi || ''));
    });
    return all;
  }

  function addRows(rows) {
    if (!rows || !rows.length) return { success: true, count: 0 };
    var local = readLocal();
    var added = rows.map(function (r, i) {
      return {
        id: 'excel_' + Date.now() + '_' + i,
        sozlesmeId: String(r.sozlesmeId || '').trim(),
        denetlenenSirket: String(r.denetlenenSirket || '').trim(),
        denetimeTabiOlmaNedeni: String(r.denetimeTabiOlmaNedeni || '').trim(),
        denetimKapsami: String(r.denetimKapsami || '').trim(),
        denetimBaslangicDonemi: normalizePeriod(r.denetimBaslangicDonemi),
        denetimBitisDonemi: normalizePeriod(r.denetimBitisDonemi),
        source: 'excel',
        createdAt: new Date().toISOString()
      };
    });
    writeLocal(local.concat(added));
    return { success: true, count: added.length, data: added };
  }

  global.RotasyonStore = {
    listRotation: listRotation,
    addRows: addRows,
    normalizePeriod: normalizePeriod,
    formatPeriodDisplay: formatPeriodDisplay,
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
