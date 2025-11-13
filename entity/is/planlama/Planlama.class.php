<?php
Class Planlama extends Base{
    
    const DELETE_TEKLIF_ID  = array("DELETE FROM    ".Config::DB_DATABASE.".planlama WHERE tklf_id = :_tklf_id ",array('tklf_id'=>Base::SAYI));
    const GET_TEKLIF_ID     = array("SELECT * FROM  ".Config::DB_DATABASE.".planlama WHERE tklf_id = :_tklf_id ",array('tklf_id'=>Base::SAYI));
    const UPDATE_DUZENLENMEYECEK = array("UPDATE ".Config::DB_DATABASE.".planlama SET uygula = :_uygula, :_gmt_usr WHERE tklf_id = :_tklf_id ",array('uygula'=>Base::KELIME,'gmt_usr'=>Base::SAYI,'tklf_id'=>Base::SAYI));
    
    public $tklf_id ;
    
    public $uygula;
    public $sirket_tr;
    public $sirket_eng;
    public $musteri_tr;
    public $musteri_eng;
    public $god;
    public $pod;
    public $dfe;
    public $nots;
    public $degisik;
    
    
    public function vt_Adi()    {return "PLANLAMA";}
    public function vt_dbAdi()  {return "planlama";}
    public function vt_Order()  {return "";}
    
    function __construct(){
        parent::__construct();
        $this->tklf_id      = new Deger("tklf_id"      ,"TEKLİF ID"     , Base::SAYI  , Base::HAYIR, null);
        $this->uygula       = new Deger("uygula"       ,"UYGUNLA"       , Base::KELIME, Base::HAYIR, null);
        $this->sirket_tr    = new Deger("sirket_tr  "  ,"SİRKET TR"     , Base::KELIME, Base::EVET,  null);
        $this->sirket_eng   = new Deger("sirket_eng "  ,"SİRKET ENG"    , Base::KELIME, Base::EVET,  null);
        $this->musteri_tr   = new Deger("musteri_tr "  ,"MUSTERİ TR"    , Base::KELIME, Base::EVET,  null);
        $this->musteri_eng  = new Deger("musteri_eng"  ,"MUSTERİ ENG"   , Base::KELIME, Base::EVET,  null);
        $this->god          = new Deger("god"          ,"GOD"           , Base::SAYI,   Base::EVET,  null);
        $this->pod          = new Deger("pod"          ,"POD"           , Base::SAYI,   Base::EVET,  null);
        $this->dfe          = new Deger("dfe"          ,"DFE"           , Base::SAYI,   Base::EVET,  null);
        $this->nots         = new Deger("nots"         ,"NOTS"          , Base::KELIME, Base::EVET, null);
        $this->degisik      = new Deger("degisik"      ,"DEĞİŞİKLİK"    , Base::KELIME, Base::EVET, null);
    }
}