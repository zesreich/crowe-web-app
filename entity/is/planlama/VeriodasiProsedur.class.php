<?php
Class VeriodasiProsedur extends Base{
    
    const GET_BY_PROSEDUR       = array("SELECT * FROM ".Config::DB_DATABASE.".veriodasi_prosedur WHERE prosedur_id = :_prsdr ",array('prsdr'=>Base::SAYI,));
    const GET_BY_TKLF_GRUP_KOD  = array("SELECT * FROM ".Config::DB_DATABASE.".veriodasi_prosedur WHERE tklf_id = :_tklf and grup = :_grup and kod = :_kod order by kod  ",array('tklf'=>Base::SAYI,'grup'=>Base::SAYI,'kod'=>Base::SAYI));
    const DELETE_ID             = array("DELETE   FROM ".Config::DB_DATABASE.".veriodasi_prosedur WHERE id = :_id ",array('id'=>Base::SAYI));
    const DELETE_TEKLIF_ID      = array("DELETE   FROM ".Config::DB_DATABASE.".veriodasi_prosedur WHERE tklf_id = :_tklf_id ",array('tklf_id'=>Base::SAYI));
    const GET_BY_TKLF_KOD       = array("SELECT * FROM ".Config::DB_DATABASE.".veriodasi_prosedur WHERE tklf_id = :_tklf and kod = :_kod",array('tklf'=>Base::SAYI,'kod'=>Base::SAYI));
    
    
    public $musteri_tarihi  ;
    public $denetci_tarihi  ;
    public $musteri_bitir   ;
    public $denetci_onay    ;
    public $aciklama        ;
    public $tklf_id         ;
    public $klasor_drive_id ;
    public $risk_belge_id   ;
    public $grup            ;
    public $kod             ;
    public $belge_sayi      ;
    
    public function vt_Adi()    {return "Veri Odası Prosedur";}
    public function vt_dbAdi()  {return "veriodasi_prosedur";}
    public function vt_Order()  {return "";}
    
    function __construct(){
        parent::__construct();
        $this->musteri_tarihi   = new Deger("musteri_tarihi","Müşteri Tarihi",  Base::KELIME,   Base::EVET, null);
        $this->denetci_tarihi   = new Deger("denetci_tarihi","Denetçi Tarihi",  Base::KELIME,   Base::EVET, null);
        $this->musteri_bitir    = new Deger("musteri_bitir" ,"Müşteri Bitir",   Base::KELIME,   Base::HAYIR, null);
        $this->denetci_onay     = new Deger("denetci_onay"  ,"Denetçi Onay",    Base::KELIME,   Base::HAYIR, null);
        $this->aciklama         = new Deger("aciklama"      ,"AÇIKLAMA",        Base::KELIME,   Base::EVET,  null);
        $this->klasor_drive_id  = new Deger("klasor_drive_id","Klasör Drive ID",Base::KELIME,   Base::HAYIR, null);
        $this->tklf_id          = new Deger("tklf_id"       ,"Teklif Id",       Base::SAYI,     Base::HAYIR, null);
        $this->risk_belge_id    = new Deger("risk_belge_id" ,"RiskBelge Id",    Base::SAYI,     Base::HAYIR, null);
        $this->grup             = new Deger("grup"          ,"Grup",            Base::SAYI,     Base::HAYIR, null);
        $this->kod              = new Deger("kod"           ,"Kod",             Base::SAYI,     Base::HAYIR, null);
        $this->belge_sayi       = new Deger("belge_sayi"    ,"Belge Kod",       Base::SAYI,     Base::EVET,  null);
    }
    
}