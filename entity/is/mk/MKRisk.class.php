<?php
Class MKRisk extends Base{
    
    const GET_TEKLIF        = array("SELECT * FROM ".Config::DB_DATABASE.".mk_risk WHERE tklf_id = :_tklf_id ",array('tklf_id'=>Base::SAYI));
    const OZEL_TEKLIF       = array("SELECT p.id as pId, p.grup as pGrup, p.kod as pKod ,l.* FROM ".Config::DB_DATABASE.".mk_risk p, risk_list l WHERE p.risk_id = l.id and p.tklf_id = :_tklf_id ",array('tklf_id'=>Base::SAYI));
    const OZEL_TEKLIF_GRUP  = array("SELECT p.id as pId, p.grup as pGrup, p.kod as pKod ,l.* FROM ".Config::DB_DATABASE.".mk_risk p, risk_list l WHERE p.risk_id = l.id and p.tklf_id = :_tklf_id and p.grup = :_grup ",array('tklf_id'=>Base::SAYI,'grup'=>Base::KELIME));
    const OZEL_TEKLIF_GRUPKOD= array("SELECT p.id as pId, p.grup as pGrup, p.kod as pKod ,l.* FROM ".Config::DB_DATABASE.".mk_risk p, risk_list l WHERE p.risk_id = l.id and p.tklf_id = :_tklf_id and p.grup = :_grup and p.kod = :_kod ",array('tklf_id'=>Base::SAYI,'grup'=>Base::KELIME,'kod'=>Base::KELIME));
    const OZEL_ID           = array("SELECT p.id as pId, p.grup as pGrup, p.kod as pKod ,l.* FROM ".Config::DB_DATABASE.".mk_risk p, risk_list l WHERE p.risk_id = l.id and p.id = :_id ",array('id'=>Base::SAYI));
    const DELETE_TEKLIF_ID  = array("DELETE FROM   ".Config::DB_DATABASE.".mk_risk WHERE tklf_id = :_tklf_id ",array('tklf_id'=>Base::SAYI));

    public $tklf_id ;
    public $grup    ;
    public $kod     ;
    public $risk_id ;
    
    public function vt_Adi()    {return "MK RİSK";}
    public function vt_dbAdi()  {return "mk_risk";}
    public function vt_Order()  {return "";}
    
    function __construct(){
        parent::__construct();
        $this->tklf_id  = new Deger("tklf_id"   ,"TEKLİF ID",Base::KELIME,  Base::HAYIR, null);
        $this->grup     = new Deger("grup"      ,"GRUP",    Base::KELIME,   Base::HAYIR, null);
        $this->kod      = new Deger("kod"       ,"KOD",     Base::KELIME,   Base::HAYIR, null);
        $this->risk_id  = new Deger("risk_id"   ,"RİSK",    Base::KELIME,   Base::HAYIR, null);
    }
}