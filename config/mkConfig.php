<?php
Class mkConfig{
    
    const ASIL_SAYISI = 3;
    const YEDEK_SAYISI = 3;
    const ASIL_SORUMLU_SAYISI = 1;
    const YEDEK_SORUMLU_SAYISI = 1;
    
    
    const MK0 = 'MK0';
    const MK1 = 'MK1';
    const MK2 = 'MK2';
    const MK3 = 'MK3';
    const MK4 = 'MK4';
    const MK5 = 'MK5';
    const MK6 = 'MK6';
    const MK_LIST = array(
        array(mkConfig::MK0,'Firma Bilgileri (KGK Portal)'),
        array(mkConfig::MK1,'Müşterinin Dürüstlüğü'),
        array(mkConfig::MK2,'Denetim Ön Şartları'),       
        array(mkConfig::MK3,'Beceri ve Uzmanlık'),         
        array(mkConfig::MK4,'Bağımsızlık'),          
        array(mkConfig::MK5,'Risk Değerlendirmesi'),      
        array(mkConfig::MK6,'SONUÇ')        
    );
    
    const B01    = 'B01';
    const B01_01 = 'B01.01';
    const B01_LIST = array( // PLANLAMA
        array(mkConfig::B01,    'Önceki Denetçi Dosya İncelemesi (File Review) ve Açılış Bakiyeleri Kontrolü'),
        array(mkConfig::B01_01, 'Sonuç'),
    );
    
    const B40_01 = 'B40.01';
    const B40_02 = 'B40.02';
    const B40_03 = 'B40.03';
    const B40_04 = 'B40.04';
    const B40_05 = 'B40.05';
    const B40_06 = 'B40.06';
    const B40_07 = 'B40.07';
    const B40_08 = 'B40.08';
    const B40_09 = 'B40.09';
    const B40_10 = 'B40.10';
    const B40_11 = 'B40.11';
    const B40_LIST = array( // PLANLAMA
        array(mkConfig::B40_01,'Sektöre İlişkin Etkenler'),
        array(mkConfig::B40_02,'Mevzuata İlişkin Etkenler'),
        array(mkConfig::B40_03,'Diğer Dış Faktörlere İlişkin Etkenler'),
        array(mkConfig::B40_04,'İşletmenin Faaliyetlerine İlişkin Etkenler'),
        array(mkConfig::B40_05,'Yatırımlar ve Yatırım Faaliyetlerine İlişkin Etkenler'),
        array(mkConfig::B40_06,'Finansman ve Finansman Faaliyetlerine İlişkin Etkenler'),
        array(mkConfig::B40_07,'Finansal Raporlama Faaliyetleri, Muhasebe Politikaları Seçimi ve Uygulamasına İlişkin Etkenler'),
        array(mkConfig::B40_08,'İşletmenin Organizasyon Yapısına İlişkin Etkenler'),
        array(mkConfig::B40_09,'İşletmenin Amaçları, Stratejileri ve İş Hayatına İlişkin İlgili Risklerine İlişkin Etkenler'),
        array(mkConfig::B40_10,'İşletmenin Finansal Performansının Ölçülmesi ve Gözden Geçirilmesine İlişkin Etkenler'),
        array(mkConfig::B40_11,'İşletmenin Kullandığı Para Birimlerine İlişkin Etkenler'),
    );
    
    const B55_01_1 = 'B55.01.1';
    const B55_01_2 = 'B55.01.2';
    const B55_01_3 = 'B55.01.3';
    const B55_01_LIST = array(
        array(mkConfig::B55_01_1,'Yönetim Soruşturmaları'),
        array(mkConfig::B55_01_2,'İç Denetim Fonksiyonunun Soruşturulması'),
        array(mkConfig::B55_01_3,'Şirket İçindeki Diğer Soruşturmalar'),
    );
    
    const B55_02 = 'B55.02';
    const B55_02_LIST = array(
        array(mkConfig::B55_02,'Denetim Ekibi İçinde Yapılan Müzakereler ve Bu Müzakerelerde Alınan Önemli Kararlar'),
    );
    
    const B20 = 'B20';
    const B20_LIST = array( // PLANLAMA
        array(mkConfig::B20,'Müşteriye Yapılan Başka Denetimlerden Elde Edilen Bilgilerin Önemli Yanlışlık Riski Açısından Değerlendirilmesi'),
    );
    
//    const PLAN_LIST = B40_LIST.concat(B20_LIST);
    
    const PROSEDUR_TIP_MK   = 'MK';
    const PROSEDUR_TIP_PLAN = 'PLAN';
    const PROSEDUR_TIPLER = array(
        mkConfig::PROSEDUR_TIP_MK,
        mkConfig::PROSEDUR_TIP_PLAN,
    );
    
    static function mkAck($grp){
        foreach (mkConfig::MK_LIST as $mk){
            if ($mk[0] == $grp){
                return $mk[1];
            }
        }
        return '';
    }
    
//     const MK_LIST_CLASS_KEY = array(
//         mkConfig::MK0 => new MK0(),
//         mkConfig::MK1 => new MK1(),
//         mkConfig::MK2 => new MK2(),
//         mkConfig::MK3 => new MK3(),
//         mkConfig::MK4 => new MK4(),
//         mkConfig::MK5 => new MK5(),
//         mkConfig::MK6 => new MK6()
//     );
    
    
    const KARAR_MADDESI_BULUNMAYAN_SIRKET   = 'Türkiye\'de Faaliyeti Bulunmayan Yabancı Şirketlere Sözleşme Bildirimi';
    const KARAR_MADDESI_BULUNAN_SIRKET      = 'Türkiye\'de Faaliyeti Bulunan Şirketlere Sözleşme Bildirimi';
    const KARAR_MADDELERI = array(mkConfig::KARAR_MADDESI_BULUNAN_SIRKET,mkConfig::KARAR_MADDESI_BULUNMAYAN_SIRKET);
    
    const KAYIK_HALK    = 'Halka açık şirketler';
    const KAYIK_BANKA   = 'Bankalar';
    const KAYIK_SIGORTA = 'Sigorta Reasürans ve Emeklilik Şirketleri';
    const KAYIK_FACTOR  = 'Factoring Şirketleri';
    const KAYIK_FINASMAN= 'Finansman Şirketleri';
    const KAYIK_FINANSAL= 'Finansal kiralama Şirketleri';
    const KAYIK_VARLIK  = 'Varlık yönetim Şirketleri';
    const KAYIK_EMEKLI  = 'Emeklilik Fonları';
    const KAYIK_IHRAC   = 'İhraççılar ve sermaye piyasası kurumları';
    const KAYIK_DIGER   = 'Diğer';
    const KAYIK_MADDELERI = array(
        mkConfig::KAYIK_HALK    ,
        mkConfig::KAYIK_BANKA   ,
        mkConfig::KAYIK_SIGORTA ,
        mkConfig::KAYIK_FACTOR  ,
        mkConfig::KAYIK_FINASMAN,
        mkConfig::KAYIK_FINANSAL,
        mkConfig::KAYIK_VARLIK  ,
        mkConfig::KAYIK_EMEKLI  ,
        mkConfig::KAYIK_IHRAC   ,
        mkConfig::KAYIK_DIGER   ,
    );
    
    const EKIP_ASIL_EKIP    = array('asil','Asıl Ekip');
    const EKIP_YEDEK_EKIP   = array('yedk','Yedek Ekip');
    const EKIP_YARDIMCI_EKIP= array('yrdm','Yardımcı Ekip');
    const EKIP_MADDELERI = array(
        mkConfig::EKIP_ASIL_EKIP,
        mkConfig::EKIP_YEDEK_EKIP,
        mkConfig::EKIP_YARDIMCI_EKIP
    );
    
    const GOREV_SORUMLU = array('srml','Sorumlu Denetçi');
    const GOREV_DENETCI = array('dntc','Denetçi');
    const GOREV_YARDIMCI= array('yrdm','Yardımcı Denetçi');
    const GOREV_MADDELERI = array(
        mkConfig::GOREV_SORUMLU,
        mkConfig::GOREV_DENETCI,
        mkConfig::GOREV_YARDIMCI
    );
    
    const POZISYON_HAZIRLAYAN   = array('hzrlyn','Hazırlayan');
    const POZISYON_KONTROL      = array('kntrl' ,'Kontrol Eden');
    const POZISYON_ONAYLAYAN    = array('onyln' ,'Onaylayan');
    const POZISYON_MADDELERI = array(
        mkConfig::POZISYON_HAZIRLAYAN,
        mkConfig::POZISYON_KONTROL,
        mkConfig::POZISYON_ONAYLAYAN
    );
    
    const PROSEDUR_KAPSAM_GOZLEM    = 'Gözlem ve Tetkik';
    const PROSEDUR_KAPSAM_SORGULAMA = 'Sorgulama';
    const PROSEDUR_KAPSAM_ANALITIK  = 'Analitik Prosedürler';
    const PROSEDUR_KAPSAM_MADDELERI = array(
        mkConfig::PROSEDUR_KAPSAM_GOZLEM,
        mkConfig::PROSEDUR_KAPSAM_SORGULAMA,
        mkConfig::PROSEDUR_KAPSAM_ANALITIK
    );
    
    const PROSEDUR_ZAMAN_ARA    = 'Ara Dönemde';
    const PROSEDUR_ZAMAN_BUTUN  = 'Bütün Denetim Boyunca';
    const PROSEDUR_ZAMAN_BELIRLI= 'Belirli Bir Tarihte';
    const PROSEDUR_ZAMAN_MADDELERI = array(
        mkConfig::PROSEDUR_ZAMAN_ARA,
        mkConfig::PROSEDUR_ZAMAN_BUTUN,
        mkConfig::PROSEDUR_ZAMAN_BELIRLI
    );
    
    const PROSEDUR_SONUC_YOK    = 'Düşük Risk';
    const PROSEDUR_SONUC_NORMAL = 'Normal Risk';
    const PROSEDUR_SONUC_CIDDI  = 'Ciddi Risk';
    const PROSEDUR_SONUC_MADDELERI = array(
        mkConfig::PROSEDUR_SONUC_YOK,
        mkConfig::PROSEDUR_SONUC_NORMAL,
        mkConfig::PROSEDUR_SONUC_CIDDI
    );
    
    const MK2_CHECK_KURULUSLAR      = array('kuruluslar'    ,"Bankalar/Finansal Kuruluşlar");
    const MK2_CHECK_VERGI_DAIRESI   = array('vergi_dairesi' ,"Vergi Dairesi");
    const MK2_CHECK_KURUMLAR        = array('kurumlar'      ,"Düzenleyici kurumlar (SPK, KGK, BDDK, EPDK vs)");
    const MK2_CHECK_YONETIM         = array('yonetim'       ,"Yönetim");
    const MK2_CHECK_ALACAKLILAR     = array('alacaklilar'   ,"Alacaklılar");
    const MK2_CHECK_YATIRIMCILAR    = array('yatirimcilar'  ,"Potansiyel yatırımcılar");
    const MK2_CHECK_UYELER          = array('uyeler'        ,"Hissedarlar/üyeler");
    const MK2_CHECK_DIGER           = array('diger'         ,"Diğer");
    const MK2_CHECK_MADDELERI = array(
        mkConfig::MK2_CHECK_KURULUSLAR   ,
        mkConfig::MK2_CHECK_VERGI_DAIRESI,
        mkConfig::MK2_CHECK_KURUMLAR     ,
        mkConfig::MK2_CHECK_YONETIM      ,
        mkConfig::MK2_CHECK_ALACAKLILAR  ,
        mkConfig::MK2_CHECK_YATIRIMCILAR ,
        mkConfig::MK2_CHECK_UYELER       ,
        mkConfig::MK2_CHECK_DIGER        
    );
    
    const DURUM_BASLANMADI       = array(40,"Yeni");
    const DURUM_DEVAM_EDIYOR     = array(41,"Devam Ediyor");
    const DURUM_TAMAMLANDI       = array(42,"Tamamlandı");
    const DURUM_KONTROL_EDILIYOR = array(43,"Kontrol Ediliyor");
    const DURUM_ONAYLANDI        = array(44,"Onaylandı");
    const MK_DURUMLAR = array(
        mkConfig::DURUM_BASLANMADI,
        mkConfig::DURUM_DEVAM_EDIYOR,     
        mkConfig::DURUM_TAMAMLANDI,       
        mkConfig::DURUM_KONTROL_EDILIYOR, 
        mkConfig::DURUM_ONAYLANDI        
    );
    
    const RISK_ALT_B = 'Bilanço';
    const RISK_ALT_G = 'Gelir Tablosu';
    const RISK_ALT_S = 'Sunum ve açıklama';
    
    const RISK_ALT_B_VAR    = array('b_var'   ,'Var olma');
    const RISK_ALT_B_HAKLAR = array('b_haklar','Haklar ve zorunluluklar');
    const RISK_ALT_B_TAMLIK = array('b_tamlik','Tamlık');
    const RISK_ALT_B_DEGER  = array('b_deger' ,'Değerleme ve tahsis');
    const RISK_ALT_G_MEYDAN = array('g_meydan','Meydana gelme');
    const RISK_ALT_G_TAMLIK = array('g_tamlik','Tamlık');
    const RISK_ALT_G_DOGRU  = array('g_dogru' ,'Doğruluk');
    const RISK_ALT_G_CUTOFF = array('g_cutoff','Cutoff');
    const RISK_ALT_G_SINIF  = array('g_sinif' ,'Sınıflandırma');
    const RISK_ALT_S_MEYDAN = array('s_meydan','Meydana gelme');
    const RISK_ALT_S_TAMLIK = array('s_tamlik','Tamlık');
    const RISK_ALT_S_SINIF  = array('s_sinif' ,'Snflndrm ve anlaşılabilirlik');
    const RISK_ALT_S_DOGRU  = array('s_dogru' ,'Doğruluk ve değerleme');
    const RISK_ALTLAR = array(
        mkConfig::RISK_ALT_B_VAR   ,
        mkConfig::RISK_ALT_B_HAKLAR,
        mkConfig::RISK_ALT_B_TAMLIK,
        mkConfig::RISK_ALT_B_DEGER ,
        mkConfig::RISK_ALT_G_MEYDAN,
        mkConfig::RISK_ALT_G_TAMLIK,
        mkConfig::RISK_ALT_G_DOGRU ,
        mkConfig::RISK_ALT_G_CUTOFF,
        mkConfig::RISK_ALT_G_SINIF ,
        mkConfig::RISK_ALT_S_MEYDAN,
        mkConfig::RISK_ALT_S_TAMLIK,
        mkConfig::RISK_ALT_S_SINIF ,
        mkConfig::RISK_ALT_S_DOGRU 
    );
    
    const RISK_PRO_KAYNAK_HATA = 'Hata Kaynaklı';
    const RISK_PRO_KAYNAK_HILE = 'Hile Kaynaklı';
    const RISK_PRO_KAYNAKLAR = array(
        mkConfig::RISK_PRO_KAYNAK_HATA,
        mkConfig::RISK_PRO_KAYNAK_HILE,
    );
    
    const RISK_PRO_DUZEY_FINANS = 'Finansal Tablo Düzeyinde';
    const RISK_PRO_DUZEY_YONETIM = 'Yönetim Beyanı Düzeyinde';
    const RISK_PRO_DUZEYLER = array(
        mkConfig::RISK_PRO_DUZEY_FINANS,
        mkConfig::RISK_PRO_DUZEY_YONETIM,
    );

}