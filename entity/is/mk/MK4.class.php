<?php
Class MK4 extends Base{
    
    const DELETE_TEKLIF_ID  = array("DELETE FROM    ".Config::DB_DATABASE.".mk4 WHERE tklf_id = :_tklf_id ",array('tklf_id'=>Base::SAYI));
    const GET_TEKLIF_ID     = array("SELECT * FROM  ".Config::DB_DATABASE.".mk4 WHERE tklf_id = :_tklf_id ",array('tklf_id'=>Base::SAYI));
    
    public $tklf_id ;
    public $belge1  ;
    public $belge2  ;
    
    public function vt_Adi()    {return "MK4";}
    public function vt_dbAdi()  {return "mk4";}
    public function vt_Order()  {return "";}
    
    function __construct(){
        parent::__construct();
        $this->tklf_id  = new Deger("tklf_id"   ,"TEKLİF ID",   Base::SAYI,     Base::HAYIR, null);
        $this->belge1   = new Deger("belge1"    ,"BELGE 1",     Base::KELIME,   Base::EVET,  null);
        $this->belge2   = new Deger("belge2"    ,"BELGE 2",     Base::KELIME,   Base::EVET,  null);
    }
}