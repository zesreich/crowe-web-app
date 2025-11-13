<?php
Class sablonConfig{
    const SOZLESME_GRUP     = 'sozlesme';
    const TEKLIF_GRUP       = 'teklif';
    const MK_DENETCI_GRUP   = 'mkDenetci';
    const MK_BEYAN_GRUP     = 'mkBeyan';
    const PLANLAMA_B01      = 'plnlmB01';
    
    
    const LISTE = array(
        sablonConfig::SOZLESME_GRUP     => sablonConfig::SOZLESMELER,
        sablonConfig::TEKLIF_GRUP       => sablonConfig::TEKLIFLER,
        sablonConfig::MK_DENETCI_GRUP   => sablonConfig::MKDENETCILER,
        sablonConfig::MK_BEYAN_GRUP     => sablonConfig::MKBEYAN,
        sablonConfig::PLANLAMA_B01      => sablonConfig::PLANLAMALAR
    );
    
    //PLANLAMA/////////////
    const PLANLAMA_DENETIM_SIRKET_TR    = "planlama_denetim_sirket_tr";
    const PLANLAMA_DENETIM_SIRKET_ENG   = "planlama_denetim_sirket_eng";
    const PLANLAMA_MUSTERI_TR           = "planlama_musteri_tr";
    const PLANLAMA_MUSTERI_ENG          = "planlama_musteri_eng";
    
    const PLANLAMALAR            = array(
        sablonConfig::PLANLAMA_DENETIM_SIRKET_TR   => 'Planlama Denetim Şirket TR',
        sablonConfig::PLANLAMA_DENETIM_SIRKET_ENG  => 'Planlama Denetim Şirket ENG',
        sablonConfig::PLANLAMA_MUSTERI_TR          => 'Planlama Müşteri TR',
        sablonConfig::PLANLAMA_MUSTERI_ENG         => 'Planlama Müşteri ENG',
    );
    //PLANLAMA/////////////
    
    
    //SÖZLEŞME/////////////
    const BAGIMSIZ_DENETİM_SOZLESMESI   = "bagimsiz_denetim_sozlesme";
    const TCMB_SOZLESMESI               = "tcmb_sozlesme";
    const BDDK_EK4_SOZLESMESI           = "bddk_ek4_sozlesme";
    
    const SOZLESMELER            = array(
        sablonConfig::BAGIMSIZ_DENETİM_SOZLESMESI => 'Bağımsız Denetim Sözleşmesi',
        sablonConfig::TCMB_SOZLESMESI             => 'TCMB Sözleşmesi',
        sablonConfig::BDDK_EK4_SOZLESMESI         => 'BDDK ek4 Sözleşmesi'
    );
    //SÖZLEŞME/////////////
    
    //TEKLIF//////////////
    const TEKLIF_TASLAK_TR  = "taslak_tr";
    const TEKLIF_TASLAK_ENG = "taslak_eng";
    const TEKLIFLER            = array(
        sablonConfig::TEKLIF_TASLAK_TR  => 'Teklif Taslağı Türkçe',
        sablonConfig::TEKLIF_TASLAK_ENG => 'Teklif Taslağı İngilizce',
    );
    //TEKLIF//////////////
    
    //MK DENETCI//////////////
    const MK_DENETCI = "mk_denetci_belge";
    const MKDENETCILER            = array(
        sablonConfig::MK_DENETCI  => 'Müşteri Kabul Denetçi Belgesi',
    );
    //MK DENETCI//////////////
    
    const MK_BEYAN_BAGIMSIZ = "beyan_bagimsiz";
    const MK_BEYAN_SOZLESME = "beyan_sozlesme";
    const MKBEYAN   = array(
        sablonConfig::MK_BEYAN_BAGIMSIZ => 'Bagimsizlik Beyani',
        sablonConfig::MK_BEYAN_SOZLESME => 'Sozlesme Kabul Beyani',
    );
    
}