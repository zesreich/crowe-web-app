<?php
Class PlanB55Kisi extends Base{
    
    const GET_TKLIF_AND_KOD = array("SELECT * FROM ".Config::DB_DATABASE.".plan_b55_kisi WHERE tklf_id = :_tklf_id and kod = :_kod ",array('tklf_id'=>Base::SAYI,'kod'=>Base::KELIME));
    const GET_BY_AKTIF      = array("SELECT * FROM ".Config::DB_DATABASE.".plan_b55_kisi WHERE aktif = 'E' ",array());
    const DELETE_TEKLIF_ID  = array("DELETE FROM   ".Config::DB_DATABASE.".plan_b55_kisi WHERE tklf_id = :_tklf_id ",array('tklf_id'=>Base::SAYI));
    
    public $tklf_id ;
    public $kod     ;
    public $adSoyad ;
    public $gorev   ;
    
    public function vt_Adi()    {return "PLAN B55 KİŞİLER";}
    public function vt_dbAdi()  {return "plan_b55_kisi";}
    public function vt_Order()  {return "";}
    
    function __construct(){
        parent::__construct();
        $this->tklf_id  = new Deger("tklf_id"   ,"TEKLİF ID"    ,Base::SAYI     ,Base::HAYIR, null);
        $this->kod      = new Deger("kod"       ,"KOD"          ,Base::KELIME,   Base::HAYIR, null);
        $this->adSoyad  = new Deger("adSoyad"   ,"AD SOYAD"     ,Base::KELIME,   Base::HAYIR, null);
        $this->gorev    = new Deger("gorev"     ,"GÖREV"        ,Base::KELIME,   Base::HAYIR, null);
    }
    
}