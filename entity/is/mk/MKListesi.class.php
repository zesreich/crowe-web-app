<?php
Class MKListesi extends Base{
    
    const GET_BY_GRUP_KOD= array("SELECT * FROM ".Config::DB_DATABASE.".mk_listesi WHERE grup = :_grup and kod = :_kod and aktif = 'E' ",array('grup'=>Base::KELIME,'kod'=>Base::KELIME,));
    const GET_BY_GRUP    = array("SELECT * FROM ".Config::DB_DATABASE.".mk_listesi WHERE grup = :_grup and aktif = 'E' ",array('grup'=>Base::KELIME,));
    const GET_BY_AKTIF   = array("SELECT * FROM ".Config::DB_DATABASE.".mk_listesi WHERE aktif = 'E' ",array());
    
    public $grup    ;
    public $kod     ;
    public $aciklama;
    public $aktif   ;
    
    public function vt_Adi()    {return "MK LİSTESİ";}
    public function vt_dbAdi()  {return "mk_listesi";}
    public function vt_Order()  {return "";}
    
    function __construct(){
        parent::__construct();
        $this->grup     = new Deger("grup"      ,"GRUP",    Base::KELIME,   Base::HAYIR, null);
        $this->kod      = new Deger("kod"       ,"KOD",     Base::KELIME,   Base::HAYIR, null);
        $this->aciklama = new Deger("aciklama"  ,"AÇIKLAMA",Base::KELIME,   Base::HAYIR, null);
        $this->aktif    = new Deger("aktif"     ,"AKTİF",   Base::KELIME,   Base::HAYIR, null);
    }
}