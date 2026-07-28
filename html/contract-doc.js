/**
 * Sözleşme Word üretici — şablon doldurma veya yedek belge oluşturma
 * Şablonda {{SIRKET}}, {{SOZLESME_NO}}, {{DONEM}} vb. yer tutucular kullanın.
 */
(function (global) {
  'use strict';

  var PLACEHOLDERS = [
    'SIRKET', 'MUSTERI', 'SOZLESME_NO', 'SOZLESME_TIPI', 'SOZLESME_KATEGORI',
    'DONEM', 'BITIS', 'DURUM', 'FINANSAL_RAPORLAMA', 'DUZENLEYICI',
    'DENETCI1', 'DENETCI2', 'DENETCI3',
    'YEDEK_DENETCI1', 'YEDEK_DENETCI2', 'YEDEK_DENETCI3',
    'SAAT', 'SOZLESME_ALINDI', 'KGK_BILDIRIM', 'TARIH'
  ];

  var FALLBACK_PATH = 'templates/sozlesme-default.docx';

  function formatDateTR(value) {
    if (!value) return '';
    if (/^\d{2}\/\d{2}\/\d{4}$/.test(String(value))) return String(value);
    var d = new Date(value);
    if (isNaN(d.getTime())) return String(value);
    return d.toLocaleDateString('tr-TR');
  }

  function buildReplacements(contract, contractType) {
    var period = contract.period || '';
    var bitis = contract.end_date || contract.end || '';
    return {
      SIRKET: contract.company || '',
      MUSTERI: contract.company || '',
      SOZLESME_NO: contract.contract_no || contract.contractNo || contract.contract_name || contract.contract || '',
      SOZLESME_TIPI: contractType || contract.contract_type || '',
      SOZLESME_KATEGORI: contract.contract_category || contract.contract_type || contractType || '',
      DONEM: period,
      BITIS: bitis,
      DURUM: contract.status || '',
      FINANSAL_RAPORLAMA: contract.financial_reporting || contract.financialReporting || contract.frc || '',
      DUZENLEYICI: contract.regulator || '',
      DENETCI1: contract.auditor1 || '',
      DENETCI2: contract.auditor2 || '',
      DENETCI3: contract.auditor3 || '',
      YEDEK_DENETCI1: contract.backup_auditor1 || contract.backupAuditor1 || '',
      YEDEK_DENETCI2: contract.backup_auditor2 || contract.backupAuditor2 || '',
      YEDEK_DENETCI3: contract.backup_auditor3 || contract.backupAuditor3 || '',
      SAAT: contract.hours || '',
      SOZLESME_ALINDI: contract.contract_received || contract.contractReceived || '',
      KGK_BILDIRIM: contract.kgk_reported || contract.kgkReported || '',
      TARIH: formatDateTR(new Date())
    };
  }

  function escapeXml(str) {
    return str
      .replace(/&/g, '&amp;')
      .replace(/</g, '&lt;')
      .replace(/>/g, '&gt;')
      .replace(/"/g, '&quot;')
      .replace(/'/g, '&apos;');
  }

  function applyReplacements(text, map) {
    var out = text;
    PLACEHOLDERS.forEach(function (key) {
      var val = map[key] != null ? String(map[key]) : '';
      var patterns = [
        new RegExp('\\{\\{' + key + '\\}\\}', 'g'),
        new RegExp('\\{\\{' + key.toLowerCase() + '\\}\\}', 'g')
      ];
      patterns.forEach(function (re) {
        out = out.replace(re, escapeXml(val));
      });
    });
    return out;
  }

  async function fillDocxFromTemplate(arrayBuffer, contract, contractType) {
    if (!global.JSZip) throw new Error('JSZip kütüphanesi yüklenmedi.');
    var zip = await global.JSZip.loadAsync(arrayBuffer);
    var map = buildReplacements(contract, contractType);
    var files = Object.keys(zip.files);

    await Promise.all(files.map(async function (path) {
      var entry = zip.files[path];
      if (entry.dir) return;
      if (!/\.xml$/.test(path)) return;
      var content = await entry.async('string');
      var updated = applyReplacements(content, map);
      if (updated !== content) zip.file(path, updated);
    }));

    return zip.generateAsync({
      type: 'blob',
      mimeType: 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'
    });
  }

  async function fetchTemplate(contractType) {
    if (global.ContractTemplateStore) {
      try {
        var uploaded = await global.ContractTemplateStore.getTemplateBuffer(contractType);
        if (uploaded) return uploaded;
        var fallbackType = await global.ContractTemplateStore.getTemplateBuffer('default');
        if (fallbackType) return fallbackType;
      } catch (err) {
        console.warn('Yüklenen şablon alınamadı:', err);
      }
    }
    var res = await fetch(FALLBACK_PATH, { cache: 'no-store' });
    if (!res.ok) return null;
    return res.arrayBuffer();
  }

  function textRun(text) {
    return '<w:r><w:t xml:space="preserve">' + escapeXml(text) + '</w:t></w:r>';
  }

  function paragraph(text) {
    return '<w:p>' + textRun(text) + '</w:p>';
  }

  async function generateFallbackDocx(contract, contractType) {
    if (!global.JSZip) throw new Error('JSZip kütüphanesi yüklenmedi.');
    var map = buildReplacements(contract, contractType);
    var zip = new global.JSZip();
    var body = [
      paragraph('SÖZLEŞME'),
      paragraph('Şirket: ' + map.SIRKET),
      paragraph('Sözleşme No: ' + map.SOZLESME_NO),
      paragraph('Tür: ' + map.SOZLESME_TIPI),
      paragraph('Durum: ' + map.DURUM),
      paragraph('Finansal raporlama çerçevesi: ' + map.FINANSAL_RAPORLAMA),
      paragraph('Düzenleyici: ' + map.DUZENLEYICI),
      paragraph('Dönem: ' + map.DONEM),
      paragraph('Bitiş: ' + map.BITIS),
      paragraph('Denetçi 1: ' + map.DENETCI1),
      paragraph('Denetçi 2: ' + map.DENETCI2),
      paragraph('Denetçi 3: ' + map.DENETCI3),
      paragraph('Yedek Denetçi 1: ' + map.YEDEK_DENETCI1),
      paragraph('Yedek Denetçi 2: ' + map.YEDEK_DENETCI2),
      paragraph('Yedek Denetçi 3: ' + map.YEDEK_DENETCI3),
      paragraph('Tarih: ' + map.TARIH)
    ].join('');
    var documentXml = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>' +
      '<w:document xmlns:w="http://schemas.openxmlformats.org/wordprocessingml/2006/main">' +
      '<w:body>' + body + '<w:sectPr><w:pgSz w:w="11906" w:h="16838"/></w:sectPr></w:body></w:document>';
    zip.file('[Content_Types].xml',
      '<?xml version="1.0" encoding="UTF-8"?><Types xmlns="http://schemas.openxmlformats.org/package/2006/content-types">' +
      '<Default Extension="rels" ContentType="application/vnd.openxmlformats-package.relationships+xml"/>' +
      '<Default Extension="xml" ContentType="application/xml"/>' +
      '<Override PartName="/word/document.xml" ContentType="application/vnd.openxmlformats-officedocument.wordprocessingml.document.main+xml"/>' +
      '</Types>');
    zip.file('_rels/.rels',
      '<?xml version="1.0" encoding="UTF-8"?><Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">' +
      '<Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/officeDocument" Target="word/document.xml"/>' +
      '</Relationships>');
    zip.file('word/document.xml', documentXml);
    zip.file('word/_rels/document.xml.rels',
      '<?xml version="1.0" encoding="UTF-8"?><Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships"></Relationships>');
    return zip.generateAsync({
      type: 'blob',
      mimeType: 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'
    });
  }

  function downloadBlob(blob, filename) {
    var url = URL.createObjectURL(blob);
    var a = document.createElement('a');
    a.href = url;
    a.download = filename;
    document.body.appendChild(a);
    a.click();
    document.body.removeChild(a);
    setTimeout(function () { URL.revokeObjectURL(url); }, 1000);
  }

  function buildFilename(contract) {
    var company = (contract.company || 'sirket').replace(/[^\w\-]+/g, '_').slice(0, 30);
    var name = (contract.contract_no || contract.contractNo || contract.contract_name || contract.contract || 'sozlesme').replace(/[^\w\-]+/g, '_').slice(0, 30);
    return 'sozlesme_' + company + '_' + name + '.docx';
  }

  async function generateContractDocument(contract, contractType) {
    var type = contractType || contract.contract_type || 'Bağımsız Denetim';
    var template = await fetchTemplate(type);
    var blob;
    var usedTemplate = false;

    if (template) {
      try {
        blob = await fillDocxFromTemplate(template, contract, type);
        usedTemplate = true;
      } catch (err) {
        console.warn('Şablon doldurma başarısız, yedek belge kullanılıyor:', err);
        blob = await generateFallbackDocx(contract, type);
      }
    } else {
      blob = await generateFallbackDocx(contract, type);
    }

    var filename = buildFilename(contract);
    downloadBlob(blob, filename);
    return { filename: filename, usedTemplate: usedTemplate };
  }

  function getJsPdf() {
    if (global.jspdf && global.jspdf.jsPDF) return global.jspdf.jsPDF;
    if (global.jsPDF) return global.jsPDF;
    return null;
  }

  async function generateContractPdf(contract, contractType) {
    var JsPDF = getJsPdf();
    if (!JsPDF) throw new Error('jsPDF kütüphanesi yüklenmedi.');
    var map = buildReplacements(contract, contractType);
    var doc = new JsPDF({ unit: 'pt', format: 'a4' });
    var y = 48;
    var line = function (label, value) {
      doc.setFont('helvetica', 'bold');
      doc.text(String(label), 48, y);
      doc.setFont('helvetica', 'normal');
      var text = doc.splitTextToSize(String(value || '—'), 340);
      doc.text(text, 200, y);
      y += Math.max(18, text.length * 14);
    };
    doc.setFontSize(16);
    doc.text('Sozlesme Ozeti', 48, y);
    y += 28;
    doc.setFontSize(11);
    line('Sirket', map.SIRKET);
    line('Sozlesme No', map.SOZLESME_NO);
    line('Tur', map.SOZLESME_TIPI);
    line('Durum', map.DURUM);
    line('Finansal Raporlama', map.FINANSAL_RAPORLAMA);
    line('Duzenleyici', map.DUZENLEYICI);
    line('Donem', map.DONEM);
    line('Bitis', map.BITIS);
    line('Denetci 1', map.DENETCI1);
    line('Denetci 2', map.DENETCI2);
    line('Denetci 3', map.DENETCI3);
    line('Yedek Denetci 1', map.YEDEK_DENETCI1);
    line('Yedek Denetci 2', map.YEDEK_DENETCI2);
    line('Yedek Denetci 3', map.YEDEK_DENETCI3);
    line('Tarih', map.TARIH);
    var company = (contract.company || 'sirket').replace(/[^\w\-]+/g, '_').slice(0, 30);
    var name = (map.SOZLESME_NO || 'sozlesme').replace(/[^\w\-]+/g, '_').slice(0, 30);
    var filename = 'sozlesme_' + company + '_' + name + '.pdf';
    doc.save(filename);
    return { filename: filename };
  }

  global.ContractDoc = {
    buildReplacements: buildReplacements,
    generateContractDocument: generateContractDocument,
    generateContractPdf: generateContractPdf,
    fillDocxFromTemplate: fillDocxFromTemplate
  };
})(typeof window !== 'undefined' ? window : global);
