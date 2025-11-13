<?php
Class MK6 extends Base{
    
    const DELETE_TEKLIF_ID  = array("DELETE FROM    ".Config::DB_DATABASE.".mk6 WHERE tklf_id = :_tklf_id ",array('tklf_id'=>Base::SAYI));
    const GET_TEKLIF_ID     = array("SELECT * FROM  ".Config::DB_DATABASE.".mk6 WHERE tklf_id = :_tklf_id ",array('tklf_id'=>Base::SAYI));
    
    public $tklf_id ;
    public $kabul   ;
    public $degerlendirme ;
    
    public function vt_Adi()    {return "MK6";}
    public function vt_dbAdi()  {return "mk6";}
    public function vt_Order()  {return "";}
    
    function __construct(){
        parent::__construct();
        $this->tklf_id  = new Deger("tklf_id"   ,"TEKLİF ID",   Base::SAYI,     Base::HAYIR, null);
        $this->kabul    = new Deger("kabul"     ,"KABUL",       Base::KELIME,   Base::EVET,  null);
        $this->degerlendirme= new Deger("degerlendirme","DEĞERLENDİRME",Base::KELIME,Base::EVET,  null);
    }
}