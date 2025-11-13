<?php
Class RiskListesi extends Base{
    
    //const GET_BY_GRUP_KOD= array("SELECT * FROM ".Config::DB_DATABASE.".risk_list WHERE grup = :_grup and kod = :_kod and aktif = 'E' ",array('grup'=>Base::KELIME,'kod'=>Base::KELIME,));
    const GET_BY_KOD     = array("SELECT * FROM ".Config::DB_DATABASE.".risk_list WHERE kod = :_kod and aktif = 'E' ",array('kod'=>Base::KELIME,));
    const GET_BY_AKTIF   = array("SELECT * FROM ".Config::DB_DATABASE.".risk_list WHERE aktif = 'E' ",array());
    
    public $kod     ;
    public $adi;
    public $aktif   ;
    
    public function vt_Adi()    {return "RİSK LİSTESİ";}
    public function vt_dbAdi()  {return "risk_list";}
    public function vt_Order()  {return "";}
    
    function __construct(){
        parent::__construct();
        $this->kod      = new Deger("kod"       ,"KOD",     Base::KELIME,   Base::HAYIR, null);
        $this->adi      = new Deger("adi"       ,"ADI",     Base::KELIME,   Base::HAYIR, null);
        $this->aktif    = new Deger("aktif"     ,"AKTİF",   Base::KELIME,   Base::HAYIR, null);
    }
    
}