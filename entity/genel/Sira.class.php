<?php
Class Sira extends Base{
    
    const SIRA_BY_ADI     = array("SELECT * FROM sira WHERE sira_adi = :_sira_adi ",array('sira_adi'=>Base::KELIME));
    const SIRA_BY_ADI_YIL = array("SELECT * FROM sira WHERE sira_adi = :_sira_adi and yil = :_yil",array('sira_adi'=>Base::KELIME,'yil'=>Base::SAYI));
    
    public $sira_adi;
    public $sira;
    public $yil;

    public function vt_Adi()    {return "SIRA";}
    public function vt_dbAdi()  {return "sira";}
    public function vt_Order()  {return " order by sira ";}
    
    function __construct(){
        parent::__construct();
        $this->sira_adi = new Deger("sira_adi", "SIRA ADI", Base::KELIME, Base::HAYIR,null);
        $this->sira     = new Deger("sira",     "SIRA",     Base::SAYI, Base::HAYIR,null);
        $this->yil      = new Deger("yil",      "YIL",      Base::SAYI, Base::HAYIR,null);
    }
}