<?php
Class Sablonlar extends Base{
    
    const GEY_BY_KEY    = array("SELECT g.* FROM sablonlar g WHERE g.anahtar =:_anahtar ",  array('anahtar'=>Base::KELIME));
    const GEY_BY_GRUP   = array("SELECT g.* FROM sablonlar g WHERE g.grup =:_grup ",        array('grup'=>Base::KELIME));
    
    public $grup;
    public $anahtar;
    public $deger;

    public function vt_Adi()    {return "Şablonlar";}
    public function vt_dbAdi()  {return "sablonlar";}
    public function vt_Order()  {return " order by anahtar";}
    
    function __construct(){
        parent::__construct();
        $this->grup     = new Deger("grup",     "GRUP",     Base::KELIME, Base::HAYIR,null);
        $this->anahtar  = new Deger("anahtar",  "ANAHTAR",  Base::KELIME, Base::HAYIR,null);
        $this->deger    = new Deger("deger",    "DEĞER",    Base::KELIME, Base::EVET, null);
    }
}