<?php
Class PlanB70 extends Base{
    
    const GET_TKLIF         = array("SELECT * FROM ".Config::DB_DATABASE.".plan_b70 WHERE tklf_id = :_tklf_id ",array('tklf_id'=>Base::SAYI));
    const DELETE_TEKLIF_ID  = array("DELETE FROM   ".Config::DB_DATABASE.".plan_b70 WHERE tklf_id = :_tklf_id ",array('tklf_id'=>Base::SAYI));
    
    public $tklf_id ;
    public $tur;
    public $oran;
    public $tutar;
    public $performans;
    
    public function vt_Adi()    {return "PLAN B70";}
    public function vt_dbAdi()  {return "plan_b70";}
    public function vt_Order()  {return "";}
    
    function __construct(){
        parent::__construct();
        $this->tklf_id      = new Deger("tklf_id"   ,"TEKLİF ID",Base::SAYI,    Base::HAYIR, null);
        $this->tur          = new Deger("tur"       ,"TÜR"      ,Base::KELIME,  Base::HAYIR, null);
        $this->oran         = new Deger("oran"      ,"ORAN"     ,Base::SAYI,    Base::HAYIR, null);
        $this->tutar        = new Deger("tutar"     ,"TUTAR"    ,Base::SAYI,    Base::HAYIR, null);
        $this->performans   = new Deger("performans","PERFORMANS",Base::SAYI,   Base::HAYIR, null);
    }
    
}