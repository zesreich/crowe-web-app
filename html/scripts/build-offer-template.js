#!/usr/bin/env node
/**
 * Teklif PowerPoint şablonlarını oluşturur.
 * Kurumsal şablonunuzu templates/ klasörüne koyarken aynı {{YER_TUTUCU}} adlarını koruyun.
 */
const fs = require('fs');
const path = require('path');
const PptxGenJS = require('pptxgenjs');

const OUT_DIR = path.join(__dirname, '..', 'templates');

function addTemplateSlide(pptx, lang) {
  const slide = pptx.addSlide();
  slide.background = { color: '053455' };

  slide.addText(lang === 'en' ? 'Audit Services Proposal' : 'Denetim Hizmet Teklifi', {
    x: 0.5, y: 0.5, w: 9, h: 0.5,
    fontSize: 14, color: 'FFFFFF', fontFace: 'Arial'
  });

  slide.addText('{{MUSTERI}}', {
    x: 0.5, y: 1.4, w: 9, h: 1,
    fontSize: 32, bold: true, color: 'FFC107', fontFace: 'Arial'
  });

  slide.addText(
    lang === 'en'
      ? 'Proposal date: {{TEKLIF_TARIHI}}'
      : 'Teklif tarihi: {{TEKLIF_TARIHI}}',
    { x: 0.5, y: 2.5, w: 9, h: 0.5, fontSize: 18, color: 'FFFFFF' }
  );

  slide.addText('{{TEKLIF_NO}}', {
    x: 0.5, y: 3.2, w: 9, h: 0.4,
    fontSize: 12, color: 'CCCCCC'
  });

  const slide2 = pptx.addSlide();
  slide2.addText(lang === 'en' ? 'Summary' : 'Teklif Özeti', {
    x: 0.5, y: 0.4, w: 9, h: 0.5, fontSize: 22, bold: true, color: '053455'
  });

  const labels = lang === 'en'
    ? ['Period', 'Amount (excl. VAT)', 'Currency', 'Reporting framework', 'Audit trigger', 'Type']
    : ['Dönem', 'Tutar (KDV hariç)', 'Para birimi', 'FRÇ', 'Denetime tabi olma nedeni', 'Teklif tipi'];

  const keys = ['DONEM', 'TUTAR', 'PARA_BIRIMI', 'FRC', 'DENETIM_NEDENI', 'TEKLIF_TIPI'];
  slide2.addTable(
    keys.map((k, i) => [labels[i], '{{' + k + '}}']),
    { x: 0.5, y: 1, w: 9, fontSize: 12, border: { type: 'solid', color: 'CCCCCC', pt: 1 } }
  );

  const slide3 = pptx.addSlide();
  slide3.addText(lang === 'en' ? 'Special terms' : 'Özel şartlar', {
    x: 0.5, y: 0.4, w: 9, h: 0.5, fontSize: 22, bold: true, color: '053455'
  });
  slide3.addText('{{OZEL_SARTLAR}}', {
    x: 0.5, y: 1, w: 9, h: 4, fontSize: 12, color: '333333', valign: 'top'
  });
}

async function build(lang, filename) {
  const pptx = new PptxGenJS();
  pptx.layout = 'LAYOUT_16x9';
  pptx.author = 'Crowe HSY';
  pptx.title = lang === 'en' ? 'Offer template EN' : 'Teklif şablonu TR';
  addTemplateSlide(pptx, lang);
  await pptx.writeFile({ fileName: path.join(OUT_DIR, filename) });
  console.log('Created', path.join(OUT_DIR, filename));
}

(async function main() {
  if (!fs.existsSync(OUT_DIR)) fs.mkdirSync(OUT_DIR, { recursive: true });
  await build('tr', 'teklif-sablon-tr.pptx');
  await build('en', 'teklif-sablon-en.pptx');
})();
