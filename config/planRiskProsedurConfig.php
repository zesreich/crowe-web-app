<?php
Class planRiskProsedurConfig{
    
    const DURUM_DEVAM_EDIYOR     = array(51,"Devam Ediyor");
    const DURUM_TAMAMLANDI       = array(52,"Tamamlandı");
    const PLAN_RISK_PROSEDUR_DURUMLAR = array(
        planRiskProsedurConfig::DURUM_DEVAM_EDIYOR,
        planRiskProsedurConfig::DURUM_TAMAMLANDI
    );
    
    
    const B70_HASILAT   = array(801,"Hasılat"                   ,0.5,2    ,"%0,5-%2 arası");
    const B70_BRUT      = array(802,"Brüt Kar"                  ,2  ,5    ,"%2-%5 arası");
    const B70_GIDER     = array(803,"Giderler"                  ,3  ,10   ,"%3-%10 arası");
    const B70_VERGI     = array(804,"Vergi Öncesi Kar"          ,5  ,20   ,"%5-%20 arası");
    const B70_TOPLAM    = array(805,"Toplam Varlıklar/Kaynaklar",1  ,2    ,"%1-%2 arası");
    const B70_OZ        = array(806,"Özkaynaklar"               ,2  ,5    ,"%2-%5 arası");
    
    
    const B70_HESAPLAMA_KRITERLERI = array(
        planRiskProsedurConfig::B70_HASILAT,
        planRiskProsedurConfig::B70_BRUT,
        planRiskProsedurConfig::B70_GIDER,
        planRiskProsedurConfig::B70_VERGI,
        planRiskProsedurConfig::B70_TOPLAM,
        planRiskProsedurConfig::B70_OZ     
    );
}