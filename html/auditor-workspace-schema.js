/**
 * Crowe HSY — Denetim dosya şablonu (Crowe_Cloud_WEB yapısı)
 * Her müşteri + ekip için otomatik oluşturulur.
 */
window.AUDIT_WORKSPACE_SCHEMA = {
  sections: [
    {
      id: 'b1',
      code: '1',
      title: 'Bölüm 1 — Kurulum ve yönetim',
      description: 'Müşteri kabulü öncesi idari süreçler',
      items: [
        { code: '1.1', title: 'Müşteri cari kartları' },
        { code: '2.1', title: 'Denetçi tanımlama / silme' },
        { code: '3.1', title: 'İş ortağı tanımlama / silme' },
        { code: '4.1', title: 'Teklif şablonları' },
        { code: '4.2', title: 'Teklif oluşturma' },
        { code: '5.0', title: 'Müşteri kabul — şirket bilgileri' },
        { code: '5.1', title: 'Müşteri kabul — dürüstlük' },
        { code: '5.2', title: 'Müşteri kabul — ön koşullar' },
        { code: '5.3', title: 'Müşteri kabul — ekip yetkinliği' },
        { code: '5.4', title: 'Müşteri kabul — bağımsızlık' },
        { code: '5.5', title: 'Müşteri kabul — riskler' },
        { code: '5.6', title: 'Müşteri kabul — sonuç' },
        { code: '6.1', title: 'Sözleşme şablonları' },
        { code: '6.2', title: 'Sözleşme oluşturma' },
        { code: '6.3', title: 'Beyanları oluşturma' }
      ]
    },
    {
      id: 'b2',
      code: '2',
      title: 'Bölüm 2 — Müşteri veri odası',
      description: 'Müşteriden gelen belgeler ve finansal veriler',
      groups: [
        {
          title: 'Genel dosya',
          items: [
            { code: 'GD-01', title: 'Ana sözleşme / esas sözleşme' },
            { code: 'GD-02', title: 'İmza sirküleri' },
            { code: 'GD-03', title: 'Vergi levhası' },
            { code: 'GD-04', title: 'Ticaret sicil gazetesi' },
            { code: 'GD-05', title: 'Yönetim kurulu kararları' },
            { code: 'GD-06', title: 'Faaliyet belgesi' },
            { code: 'GD-07', title: 'Ortaklık yapısı / pay defteri' }
          ]
        },
        {
          title: 'Finansal bilgiler',
          items: [
            { code: 'FB-01', title: 'Muavin defter' },
            { code: 'FB-02', title: 'Mizan' },
            { code: 'FB-03', title: 'Finansal tablolar (taslak)' }
          ]
        },
        {
          title: 'Dayanak ek bilgiler',
          items: [
            { code: 'C10', title: 'Nakit ve nakit benzerleri' },
            { code: 'C11', title: 'Finansal yatırımlar' },
            { code: 'C12', title: 'Ticari alacaklar' },
            { code: 'C13', title: 'Diğer alacaklar' },
            { code: 'C14', title: 'Stoklar' },
            { code: 'C15', title: 'Canlı varlıklar' },
            { code: 'C16', title: 'Maddi duran varlıklar' },
            { code: 'C17', title: 'Maddi olmayan duran varlıklar' },
            { code: 'C18', title: 'Kullanım hakkı varlıkları' }
          ]
        }
      ]
    },
    {
      id: 'b31',
      code: '3.1',
      title: 'Bölüm 3.1 — Denetim planı',
      description: 'Planlama ve risk değerlendirme çalışmaları',
      items: [
        { code: 'B00', title: 'Denetim zaman planı' },
        { code: 'B20', title: 'Risk değerlendirme' },
        { code: 'B30', title: 'Önemlilik düzeyleri' },
        { code: 'B40', title: 'Analitik prosedürler' },
        { code: 'B50', title: 'İç kontrol değerlendirmesi' },
        { code: 'B70', title: 'Önemlilik hesaplamaları' },
        { code: 'B71', title: 'Örnekleme planı' },
        { code: 'B100', title: 'Planlama özeti' }
      ]
    },
    {
      id: 'b32',
      code: '3.2',
      title: 'Bölüm 3.2 — Denetim çalışmaları',
      description: 'Hesap bazlı denetim programları',
      items: [
        { code: 'C10', title: 'Nakit ve nakit benzerleri' },
        { code: 'C12', title: 'Ticari alacaklar' },
        { code: 'C15', title: 'Stoklar' },
        { code: 'C16', title: 'Maddi duran varlıklar' },
        { code: 'C28', title: 'TFRS 16 / kiralama' },
        { code: 'C30', title: 'Finansal borçlar' },
        { code: 'C37', title: 'Vergi varlık / yükümlülükleri' },
        { code: 'C47', title: 'Karşılıklar' },
        { code: 'C50', title: 'Özkaynaklar' },
        { code: 'C60', title: 'Hasılat' },
        { code: 'C62', title: 'Satışların maliyeti' },
        { code: 'C63', title: 'Faaliyet giderleri' },
        { code: 'CV60', title: 'Devam eden işletme / özel riskler' }
      ]
    },
    {
      id: 'b33',
      code: '3.3',
      title: 'Bölüm 3.3 — Görüş oluşturma',
      description: 'Raporlama ve arşivleme',
      items: [
        { code: 'D10', title: 'Finansal tablo sunumu kontrolü' },
        { code: 'D20', title: 'Finansal tablo dipnotları' },
        { code: 'D21', title: 'Kilit denetim konuları (KDK)' },
        { code: 'D30', title: 'Bağımsız denetim raporu' },
        { code: 'D60', title: 'Arşivleme' }
      ]
    }
  ]
};

window.buildDefaultWorkspaceItems = function buildDefaultWorkspaceItems() {
  const items = {};
  const schema = window.AUDIT_WORKSPACE_SCHEMA;

  schema.sections.forEach(function (section) {
    if (section.items) {
      section.items.forEach(function (item) {
        const id = section.id + '_' + item.code.replace(/[^a-zA-Z0-9]/g, '_');
        items[id] = {
          id: id,
          sectionId: section.id,
          code: item.code,
          title: item.title,
          status: 'pending',
          note: '',
          updatedAt: null,
          updatedBy: null
        };
      });
    }
    if (section.groups) {
      section.groups.forEach(function (group) {
        group.items.forEach(function (item) {
          const id = section.id + '_' + item.code.replace(/[^a-zA-Z0-9]/g, '_');
          items[id] = {
            id: id,
            sectionId: section.id,
            group: group.title,
            code: item.code,
            title: item.title,
            status: 'pending',
            note: '',
            updatedAt: null,
            updatedBy: null
          };
        });
      });
    }
  });

  return items;
};
