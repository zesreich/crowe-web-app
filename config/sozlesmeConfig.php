<?php
Class sozlesmeConfig{

//     const BAGIMSIZ_DENETİM_SOZLESMESI   = "bagimsiz_denetim_sozlesme";
//     const TCMB_SOZLESMESI               = "tcmb_sozlesme";
//     const BDDK_EK4_SOZLESMESI           = "bddk_ek4_sozlesme";
//     const SOZLESMELER            = array(
//         sozlesmeConfig::BAGIMSIZ_DENETİM_SOZLESMESI => 'Bağımsız Denetim Sözleşmesi',  
//         sozlesmeConfig::TCMB_SOZLESMESI             => 'TCMB Sözleşmesi',  
//         sozlesmeConfig::BDDK_EK4_SOZLESMESI         => 'BDDK ek4 Sözleşmesi'  
//     );
    const SOZLESMELER_ID_KEY     = array(
        7 => sablonConfig::BAGIMSIZ_DENETİM_SOZLESMESI, 
        12 => sablonConfig::BAGIMSIZ_DENETİM_SOZLESMESI, 
        13 => sablonConfig::BAGIMSIZ_DENETİM_SOZLESMESI, 
        14 => sablonConfig::BAGIMSIZ_DENETİM_SOZLESMESI, 
        15 => sablonConfig::BAGIMSIZ_DENETİM_SOZLESMESI, 
        16 => sablonConfig::BAGIMSIZ_DENETİM_SOZLESMESI, 
        17 => sablonConfig::BAGIMSIZ_DENETİM_SOZLESMESI, 
        18 => sablonConfig::BAGIMSIZ_DENETİM_SOZLESMESI, 
        19 => sablonConfig::BAGIMSIZ_DENETİM_SOZLESMESI, 
        20 => sablonConfig::BAGIMSIZ_DENETİM_SOZLESMESI, 
        21 => sablonConfig::BAGIMSIZ_DENETİM_SOZLESMESI, 
        22 => sablonConfig::BAGIMSIZ_DENETİM_SOZLESMESI, 
        23 => sablonConfig::BAGIMSIZ_DENETİM_SOZLESMESI, 
        24 => sablonConfig::BAGIMSIZ_DENETİM_SOZLESMESI, 
        25 => sablonConfig::BAGIMSIZ_DENETİM_SOZLESMESI, 
        28 => sablonConfig::TCMB_SOZLESMESI            ,
        35 => sablonConfig::BDDK_EK4_SOZLESMESI        ,
    );
    
    const DURUM_OLUSMADI        = array(51,"Sözleşme Oluşturulmadı");
    const DURUM_IMZAYA_GONDER   = array(52,"Müşteriye İmzaya Gönder");
    const DURUM_IMZAYI_BEKLE    = array(53,"Müşteri İmza Aşamasında");
    const DURUM_TAMAMLADI       = array(54,"İmzalı Sözleşme");
    const DURUMLAR = array(
        sozlesmeConfig::DURUM_OLUSMADI,
        sozlesmeConfig::DURUM_IMZAYA_GONDER,
        sozlesmeConfig::DURUM_IMZAYI_BEKLE,
        sozlesmeConfig::DURUM_TAMAMLADI
    );
 
    
    
    
}