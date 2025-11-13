<?php
include_once 'Base.class.php';

Class Program extends Base{
    
    const MENU_SIRALI       = array("SELECT * FROM ".Config::DB_DATABASE.".program ORDER BY sira, ust_id",array());
    const YANLIZ_PROGRAMLAR = array("SELECT * FROM ".Config::DB_DATABASE.".program WHERE klasor <> 'E'",array());
    
    public $sira            ;
    public $ust_id          ;
    public $klasor          ;
    public $gorunsunmu      ;
    public $program_adi     ;
    public $program_link    ;
    public $icon            ;
    public $yetki           ;

    public function vt_Adi()    {return "PROGRAM";}
    public function vt_dbAdi()  {return "program";}
    public function vt_Order()  {return "order by program_adi";}
    
    function __construct(){
        parent::__construct();
        $this->sira         = new Deger("sira",         "SIRA",          Base::SAYI,   Base::HAYIR,null);
        $this->ust_id       = new Deger("ust_id",       "ÜST ID",        Base::SAYI,   Base::HAYIR,null);
        $this->klasor       = new Deger("klasor",       "KLASÖR MÜ",     Base::KELIME, Base::HAYIR,null);
        $this->gorunsunmu   = new Deger("gorunsunmu",   "GÖRÜNSÜN MÜ",   Base::KELIME, Base::HAYIR,null);
        $this->program_adi  = new Deger("program_adi",  "KULLANICI ADI", Base::KELIME, Base::HAYIR,null);
        $this->program_link = new Deger("program_link", "LINK",          Base::KELIME, Base::EVET, null);
        $this->icon         = new Deger("icon",         "ICON",          Base::KELIME, Base::EVET, null);
        $this->yetki        = new Deger("yetki",        "YETKI",         Base::SAYI,   Base::EVET, null);
    }
    
}