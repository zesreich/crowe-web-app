<?php
Class MK1 extends Base{
    
    const DELETE_TEKLIF_ID  = array("DELETE FROM    ".Config::DB_DATABASE.".mk1 WHERE tklf_id = :_tklf_id ",array('tklf_id'=>Base::SAYI));
    const GET_TEKLIF_ID     = array("SELECT * FROM  ".Config::DB_DATABASE.".mk1 WHERE tklf_id = :_tklf_id ",array('tklf_id'=>Base::SAYI));
    
    public $tklf_id ;
    
    public function vt_Adi()    {return "MK1";}
    public function vt_dbAdi()  {return "mk1";}
    public function vt_Order()  {return "";}
    
    function __construct(){
        parent::__construct();
        $this->tklf_id  = new Deger("tklf_id"   ,"TEKLİF ID",   Base::SAYI,     Base::HAYIR, null);
    }
}