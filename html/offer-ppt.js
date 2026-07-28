/**
 * Teklif PowerPoint üretici — şablon doldurma veya yedek slayt oluşturma
 * Şablonda {{MUSTERI}}, {{TEKLIF_TARIHI}} vb. yer tutucular kullanın.
 */
(function (global) {
  'use strict';

  var PLACEHOLDERS = [
    'TEKLIF_NO', 'MUSTERI', 'TEKLIF_TARIHI', 'DONEM', 'TUTAR', 'PARA_BIRIMI',
    'FRC', 'DENETIM_NEDENI', 'OZEL_SARTLAR', 'ACIKLAMA', 'TEKLIF_TIPI', 'DIL'
  ];

  var TEMPLATE_TR = 'templates/teklif-sablon-tr.pptx';
  var TEMPLATE_EN = 'templates/teklif-sablon-en.pptx';

  function formatDateTR(value) {
    if (!value) return '';
    var d = new Date(value);
    if (isNaN(d.getTime())) return String(value);
    return d.toLocaleDateString('tr-TR');
  }

  function formatMoney(value) {
    if (value === null || value === undefined || value === '') return '';
    var num = Number(String(value).replace(/\./g, '').replace(',', '.'));
    if (isNaN(num)) return String(value);
    return num.toLocaleString('tr-TR', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
  }

  function buildReplacements(offer) {
    var details = offer.details || {};
    var period = '';
    if (details.period_start || details.period_end) {
      period = formatDateTR(details.period_start) + ' – ' + formatDateTR(details.period_end);
    }
    var lang = details.language || 'tr';
    return {
      TEKLIF_NO: offer.offer_no || offer.offerNo || '',
      MUSTERI: offer.client_name || offer.clientName || '',
      TEKLIF_TARIHI: formatDateTR(offer.send_date || offer.sendDate),
      DONEM: period,
      TUTAR: formatMoney(details.amount),
      PARA_BIRIMI: details.currency || 'TRY',
      FRC: details.frc || '',
      DENETIM_NEDENI: details.audit_reason || '',
      OZEL_SARTLAR: details.special_terms || '',
      ACIKLAMA: details.notes || '',
      TEKLIF_TIPI: offer.offer_type || offer.offerType || '',
      DIL: lang === 'en' ? 'English' : 'Türkçe'
    };
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

  function escapeXml(str) {
    return str
      .replace(/&/g, '&amp;')
      .replace(/</g, '&lt;')
      .replace(/>/g, '&gt;')
      .replace(/"/g, '&quot;')
      .replace(/'/g, '&apos;');
  }

  async function fillPptxFromTemplate(arrayBuffer, offer) {
    if (!global.JSZip) throw new Error('JSZip kütüphanesi yüklenmedi.');
    var zip = await global.JSZip.loadAsync(arrayBuffer);
    var map = buildReplacements(offer);
    var files = Object.keys(zip.files);

    await Promise.all(files.map(async function (path) {
      var entry = zip.files[path];
      if (entry.dir) return;
      if (!/\.xml$/.test(path) && path !== 'docProps/core.xml') return;
      var content = await entry.async('string');
      var updated = applyReplacements(content, map);
      if (updated !== content) zip.file(path, updated);
    }));

    return zip.generateAsync({ type: 'blob', mimeType: 'application/vnd.openxmlformats-officedocument.presentationml.presentation' });
  }

  async function fetchTemplate(lang) {
    if (global.OfferTemplateStore) {
      try {
        var uploaded = await global.OfferTemplateStore.getTemplateBuffer(lang);
        if (uploaded) return uploaded;
      } catch (err) {
        console.warn('Yüklenen şablon alınamadı:', err);
      }
    }

    var url = (lang === 'en' || lang === 'eng') ? TEMPLATE_EN : TEMPLATE_TR;
    var res = await fetch(url, { cache: 'no-store' });
    if (!res.ok) return null;
    return res.arrayBuffer();
  }

  async function generateWithPptxGen(offer) {
    if (!global.PptxGenJS) throw new Error('PptxGenJS kütüphanesi yüklenmedi.');
    var map = buildReplacements(offer);
    var pptx = new global.PptxGenJS();
    pptx.layout = 'LAYOUT_16x9';
    pptx.author = 'HSY Crowe';
    pptx.title = 'Teklif — ' + map.MUSTERI;

    var slide = pptx.addSlide();
    slide.background = { color: '053455' };
    slide.addText('Denetim Hizmet Teklifi', {
      x: 0.5, y: 0.6, w: 9, h: 0.6,
      fontSize: 14, color: 'FFFFFF', fontFace: 'Arial'
    });
    slide.addText(map.MUSTERI, {
      x: 0.5, y: 1.5, w: 9, h: 1,
      fontSize: 28, bold: true, color: 'FFC107', fontFace: 'Arial'
    });
    slide.addText('Teklif tarihi: ' + map.TEKLIF_TARIHI, {
      x: 0.5, y: 2.6, w: 9, h: 0.5,
      fontSize: 16, color: 'FFFFFF', fontFace: 'Arial'
    });

    var slide2 = pptx.addSlide();
    slide2.addText('Teklif Özeti', { x: 0.5, y: 0.4, w: 9, h: 0.5, fontSize: 22, bold: true, color: '053455' });
    var rows = [
      ['Teklif No', map.TEKLIF_NO],
      ['Dönem', map.DONEM],
      ['Tutar (KDV hariç)', map.TUTAR + ' ' + map.PARA_BIRIMI],
      ['FRÇ', map.FRC],
      ['Denetime tabi olma nedeni', map.DENETIM_NEDENI],
      ['Teklif tipi', map.TEKLIF_TIPI]
    ].filter(function (r) { return r[1]; });

    slide2.addTable(rows, {
      x: 0.5, y: 1.1, w: 9,
      fontSize: 12,
      border: { type: 'solid', color: 'CCCCCC', pt: 1 },
      fill: { color: 'F8F9FA' }
    });

    if (map.OZEL_SARTLAR) {
      var slide3 = pptx.addSlide();
      slide3.addText('Özel Şartlar', { x: 0.5, y: 0.4, w: 9, h: 0.5, fontSize: 22, bold: true, color: '053455' });
      slide3.addText(map.OZEL_SARTLAR, { x: 0.5, y: 1, w: 9, h: 4.5, fontSize: 12, color: '333333', valign: 'top' });
    }

    return pptx.write({ outputType: 'blob' });
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

  function buildFilename(offer) {
    var no = (offer.offer_no || offer.offerNo || 'teklif').replace(/[^\w\-]+/g, '_');
    var client = (offer.client_name || offer.clientName || 'musteri').replace(/[^\w\-]+/g, '_').slice(0, 30);
    return no + '_' + client + '.pptx';
  }

  async function generateOfferPresentation(offer) {
    var lang = (offer.details && offer.details.language) || 'tr';
    var template = await fetchTemplate(lang);
    var blob;
    var usedTemplate = false;

    if (template) {
      try {
        blob = await fillPptxFromTemplate(template, offer);
        usedTemplate = true;
      } catch (err) {
        console.warn('Şablon doldurma başarısız, yedek üretici kullanılıyor:', err);
        blob = await generateWithPptxGen(offer);
      }
    } else {
      blob = await generateWithPptxGen(offer);
    }

    var filename = buildFilename(offer);
    downloadBlob(blob, filename);
    return { filename: filename, usedTemplate: usedTemplate };
  }

  function getJsPdf() {
    if (global.jspdf && global.jspdf.jsPDF) return global.jspdf.jsPDF;
    if (global.jsPDF) return global.jsPDF;
    return null;
  }

  async function generateOfferPdf(offer) {
    var JsPDF = getJsPdf();
    if (!JsPDF) throw new Error('jsPDF kütüphanesi yüklenmedi.');
    var map = buildReplacements(offer);
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
    doc.text('Teklif Ozeti', 48, y);
    y += 28;
    doc.setFontSize(11);
    line('Teklif No', map.TEKLIF_NO);
    line('Musteri', map.MUSTERI);
    line('Tarih', map.TEKLIF_TARIHI);
    line('Donem', map.DONEM);
    line('Tutar', map.TUTAR + ' ' + map.PARA_BIRIMI);
    line('Tip', map.TEKLIF_TIPI);
    line('FRC', map.FRC);
    line('Aciklama', map.ACIKLAMA);
    var no = (offer.offer_no || offer.offerNo || 'teklif').replace(/[^\w\-]+/g, '_');
    var client = (offer.client_name || offer.clientName || 'musteri').replace(/[^\w\-]+/g, '_').slice(0, 30);
    var filename = no + '_' + client + '.pdf';
    doc.save(filename);
    return { filename: filename };
  }

  global.OfferPpt = {
    buildReplacements: buildReplacements,
    generateOfferPresentation: generateOfferPresentation,
    generateOfferPdf: generateOfferPdf,
    fillPptxFromTemplate: fillPptxFromTemplate,
    TEMPLATE_TR: TEMPLATE_TR,
    TEMPLATE_EN: TEMPLATE_EN
  };
})(typeof window !== 'undefined' ? window : global);
