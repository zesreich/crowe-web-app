<?php
Class Denetim extends Base{
    
    const GET_ID            = array("SELECT * FROM  ".Config::DB_DATABASE.".denetim WHERE id = :_id ",array('id'=>Base::SAYI));
    const GET_MUSTERI_DONEM = array("SELECT * FROM  ".Config::DB_DATABASE.".denetim WHERE musteri_id = :_musteri_id and dton_id = :_dton_id and donem_bas_trh = :_donem_bas_trh and donem_bts_trh = :_donem_bts_trh ",array('musteri_id'=>Base::SAYI, 'dton_id' => Base::SAYI,'donem_bas_trh'=>Base::KELIME,'donem_bts_trh'=>Base::KELIME));
    const DELETE_TEKLIF_ID  = array("DELETE FROM    ".Config::DB_DATABASE.".denetim WHERE id = :_id ",array('id'=>Base::SAYI));
    
    public $musteri_id      ;
    public $email           ;
    public $durum_id        ;
    public $teklif_tarihi   ;
    public $teklif_red_ack  ;
    public $bilgi           ;
    public $dton_id         ;
    public $donem_bas_trh   ;
    public $donem_bts_trh   ;
    public $frc_id          ;
    public $tutar           ;
    public $para_birimi_id  ;
    public $dil_id          ;
    public $rapor_dil_id    ;
    public $yonay_id        ;
    public $yonay_trh       ;
    public $monay_id        ;
    public $monay_trh       ;
    public $raporsekli_id   ;
    public $duzenkurum_id   ;
    public $drive_id        ;
    public $main_drive_id   ;
    public $tr_szlsm_drive_id;
    public $eng_szlsm_drive_id;
    public $risk_drive_id   ;
    public $veriodasi_drive_id;
    public $ozel_sart       ;

    
    public function vt_Adi()    {return "DENETİM";}
    public function vt_dbAdi()  {return "denetim";}
    public function vt_Order()  {return "";}
    
    function __construct(){
        parent::__construct();
        $this->musteri_id        = new Deger("musteri_id"       ,"MÜŞTERİ",                     Base::SAYI,     Base::HAYIR, new Ref(new Musteri(), Base::TEK,'id'));
        $this->email             = new Deger("email"            ,"EMAIL",                       Base::KELIME,   Base::EVET,  null);
        $this->durum_id          = new Deger("durum_id"         ,"DURUM",                       Base::SAYI,     Base::HAYIR, new Ref(new DenetimDurum(), Base::TEK,'id'));
        $this->teklif_tarihi     = new Deger("teklif_tarihi"    ,"TEKLİF TARİHİ",               Base::KELIME,   Base::HAYIR, null);
        $this->teklif_red_ack    = new Deger("teklif_red_ack"   ,"TEKLİF RED AÇIKLAMA",         Base::KELIME,   Base::EVET , null);
        $this->bilgi             = new Deger("bilgi"            ,"BİLGİ AÇIKLAMA",              Base::KELIME,   Base::EVET , null);
        $this->dton_id           = new Deger("dton_id"          ,"DENETİME TABİ OLMA NEDENİ",   Base::SAYI,     Base::HAYIR, new Ref(new TklfDenetimNedeni(), Base::TEK,'id'));
        $this->donem_bas_trh     = new Deger("donem_bas_trh"    ,"DÖNEM BAŞLAMA TARİHİ",        Base::KELIME,   Base::HAYIR, null);
        $this->donem_bts_trh     = new Deger("donem_bts_trh"    ,"DÖNEM BİTİŞ TARİHİ",          Base::KELIME,   Base::HAYIR, null);
        $this->frc_id            = new Deger("frc_id"           ,"FİNANSAL RAPORLAMA ÇERÇEVESİ",Base::SAYI,     Base::HAYIR, new Ref(new TklfFinansRapor(), Base::TEK,'id'));
        $this->tutar             = new Deger("tutar"            ,"TUTAR",                       Base::SAYI,     Base::HAYIR, null);
        $this->para_birimi_id    = new Deger("para_birimi_id"   ,"PARA BİRİMİ",                 Base::SAYI,     Base::HAYIR, new Ref(new TklfParaBirimi(), Base::TEK,'id'));
        $this->dil_id            = new Deger("dil_id"           ,"TEKLİF DİLİ",                 Base::SAYI,     Base::HAYIR, new Ref(new TklfDil(), Base::TEK,'id'));
        $this->rapor_dil_id      = new Deger("rapor_dil_id"     ,"RAPORLAMA DİLİ",              Base::SAYI,     Base::HAYIR, new Ref(new TklfRaporDil(), Base::TEK,'id'));
        $this->yonay_id          = new Deger("yonay_id"         ,"Y. ONAY",                     Base::SAYI,     Base::EVET, new Ref(new Musteri(), Base::TEK,'id'));
        $this->yonay_trh         = new Deger("yonay_trh"        ,"Y. ONAY TARİHİ",              Base::KELIME,   Base::EVET, null);
        $this->monay_id          = new Deger("monay_id"         ,"M. ONAY",                     Base::SAYI,     Base::EVET, new Ref(new Musteri(), Base::TEK,'id'));
        $this->monay_trh         = new Deger("monay_trh"        ,"M. ONAY TARİHİ",              Base::KELIME,   Base::EVET, null);
        $this->raporsekli_id     = new Deger("raporsekli_id"    ,"RAPORLAMA ŞEKLİ",             Base::SAYI,     Base::HAYIR, new Ref(new TklfRaporlamaSekli(), Base::TEK,'id'));
        $this->duzenkurum_id     = new Deger("duzenkurum_id"    ,"DÜZENLEYİCİ KURUM",           Base::SAYI,     Base::HAYIR, new Ref(new TklfDuzenleyiciKurum(), Base::TEK,'id'));
        $this->ozel_sart         = new Deger("ozel_sart"        ,"ÖZEL ŞARTLAR",                Base::KELIME,   Base::HAYIR, NULL);
        $this->drive_id          = new Deger("drive_id"         ,"DRIVE ID",                    Base::KELIME,   Base::EVET, NULL);
        $this->main_drive_id     = new Deger("main_drive_id"    ,"MAIN DRIVE ID",               Base::KELIME,   Base::EVET, NULL);
        $this->tr_szlsm_drive_id = new Deger("tr_szlsm_drive_id","TR SÖZLEŞME DRIVE ID",        Base::KELIME,   Base::EVET, NULL);
        $this->eng_szlsm_drive_id= new Deger("eng_szlsm_drive_id","ENG SÖZLEŞME DRIVE ID",      Base::KELIME,   Base::EVET, NULL);
        $this->risk_drive_id     = new Deger("risk_drive_id"    ,"RİSK DRIVE ID",               Base::KELIME,   Base::EVET, NULL);
        $this->veriodasi_drive_id= new Deger("veriodasi_drive_id","VERIODASI DRIVE ID",         Base::KELIME,   Base::EVET, NULL);
    }

    public static function idGetir($yil){
        $yeni = null;
        $tbl = Crud::getSqlTek(new Sira(), Sira::SIRA_BY_ADI_YIL, array('sira_adi'=>'DENETIM_SIRA_ID','yil'=>$yil));
        if($tbl == null){
            $yeni = 1;
            $tbl = new Sira();
            $tbl->sira->deger = $yeni;
            $tbl->sira_adi->deger = 'DENETIM_SIRA_ID';
            $tbl->yil->deger = $yil;
            Crud::save($tbl);
        }else{
            $yeni = $tbl->sira->deger+1;
            $tbl->sira->deger = $yeni;
            Crud::update($tbl);
        }
        return $yil.$yeni;
    }
    
}
