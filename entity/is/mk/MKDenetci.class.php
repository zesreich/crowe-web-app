<?php
Class MKDenetci extends Base{
    
    const GET_TEKLIF_ID             = array("SELECT * FROM ".Config::DB_DATABASE.".mk_denetci WHERE tklf_id = :_tklf_id order by ekip, gorev desc, id",array('tklf_id'=>Base::SAYI));
    const GET_TEKLIF_EKIP           = array("SELECT * FROM ".Config::DB_DATABASE.".mk_denetci WHERE tklf_id = :_tklf_id and ekip = :_ekip order by ekip, gorev desc",array('tklf_id'=>Base::SAYI,'ekip'=>Base::KELIME));
    const GET_TEKLIF_DENETCI_ID     = array("SELECT * FROM ".Config::DB_DATABASE.".mk_denetci WHERE tklf_id = :_tklf_id and denetci_id = :_denetci_id ",array('tklf_id'=>Base::SAYI,'denetci_id'=>Base::SAYI));
    const GET_TEKLIF_BY_DENETCI_ID  = array("SELECT * FROM ".Config::DB_DATABASE.".mk_denetci WHERE tklf_id = :_tklf_id and denetci_id in (:_dntcLst) order by ekip, gorev desc",array('tklf_id'=>Base::SAYI,'dntcLst'=>Base::KELIME));
    const DELETE_TEKLIF_ID          = array("DELETE FROM   ".Config::DB_DATABASE.".mk_denetci WHERE tklf_id = :_tklf_id ",array('tklf_id'=>Base::SAYI));
    
    public $tklf_id ;
    public $denetci_id ;
    public $ekip    ;
    public $gorev   ;
    public $pozisyon;
    public $saat    ;
    public $saat_ucret;
    public $ucret   ;
    public $drive_id;
    public $uygun   ;
    
    public function vt_Adi()    {return "MÜŞTERİ KABUL DENETÇİ";}
    public function vt_dbAdi()  {return "mk_denetci";}
    public function vt_Order()  {return "";}
    
    function __construct(){
        parent::__construct();
        $this->tklf_id  = new Deger("tklf_id"   ,"TEKLİF ID",Base::SAYI,     Base::HAYIR, null);
        $this->denetci_id= new Deger("denetci_id","DENETÇİ", Base::SAYI,     Base::HAYIR, new Ref(new Kullanici(), Base::TEK,'id'));
        $this->ekip     = new Deger("ekip"      ,"EKIP",     Base::KELIME,   Base::HAYIR, null);
        $this->gorev    = new Deger("gorev"     ,"GOREV",    Base::KELIME,   Base::EVET, null);
        $this->pozisyon = new Deger("pozisyon"  ,"POZİSYON", Base::KELIME,   Base::EVET, null);
        $this->saat     = new Deger("saat"      ,"SAAT",     Base::SAYI,     Base::EVET,  null);
        $this->saat_ucret= new Deger("saat_ucret","SAAT ÜCRET",Base::SAYI,   Base::EVET,  null);
        $this->ucret    = new Deger("ucret"     ,"ÜCRET",    Base::SAYI,     Base::EVET,  null);
        $this->drive_id = new Deger("drive_id"  ,"DRIVE ID", Base::KELIME,   Base::EVET,  null);
        $this->uygun    = new Deger("uygun"     ,"UYGUN",    Base::KELIME,   Base::HAYIR, null);
    }
}