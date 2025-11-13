<?php
Class MKRefs extends Base{
    
    const OZEL_TEKLIF       = array("SELECT id, grup as pGrup, kod as pKod, refs_id,  FROM ".Config::DB_DATABASE.".mk_refs WHERE tklf_id = :_tklf_id ",array('tklf_id'=>Base::SAYI));
    const OZEL_TEKLIF_GRUP  = array("SELECT m.id, m.grup as pGrup, m.kod as pKod, r.kod, r.adi  FROM ".Config::DB_DATABASE.".mk_refs m, ".Config::DB_DATABASE.".referanslar r WHERE r.id = m.refs_id and  m.tklf_id = :_tklf_id  and m.grup = :_grup ",array('tklf_id'=>Base::SAYI,'grup'=>Base::KELIME));
    const OZEL_TEKLIF_GRUPKOD= array("SELECT id, grup as pGrup, kod as pKod, refs_id FROM ".Config::DB_DATABASE.".mk_refs WHERE tklf_id = :_tklf_id and grup = :_grup and kod = :_kod ",array('tklf_id'=>Base::SAYI,'grup'=>Base::KELIME,'kod'=>Base::KELIME));
    const DELETE_TEKLIF_ID  = array("DELETE FROM ".Config::DB_DATABASE.".mk_refs WHERE tklf_id = :_tklf_id ",array('tklf_id'=>Base::SAYI));
    
    public $tklf_id ;
    public $grup    ;
    public $kod     ;
    public $refs_id;
    
    public function vt_Adi()    {return "MK REFS";}
    public function vt_dbAdi()  {return "mk_refs";}
    public function vt_Order()  {return "";}
    
    function __construct(){
        parent::__construct();
        $this->tklf_id  = new Deger("tklf_id"   ,"TEKLİF ID",Base::KELIME,  Base::HAYIR, null);
        $this->grup     = new Deger("grup"      ,"GRUP",    Base::KELIME,   Base::HAYIR, null);
        $this->kod      = new Deger("kod"       ,"KOD",     Base::KELIME,   Base::HAYIR, null);
        $this->refs_id  = new Deger("refs_id"   ,"REFERANS",Base::KELIME,   Base::HAYIR, null);
    }
}