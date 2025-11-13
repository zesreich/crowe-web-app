<?php
Class Referanslar extends Base{
    
    const TABLO_ARA = array("SELECT m.id, m.adi FROM ".Config::DB_DATABASE.".referanslar m",array());
    const ALL   = array("SELECT * FROM ".Config::DB_DATABASE.".referanslar m",array());
    
    public $adi;
    public $kod;

    public function vt_Adi()    {return "REFERANSLAR";}
    public function vt_dbAdi()  {return "referanslar";}
    public function vt_Order()  {return " order by adi";}
    
    function __construct(){
        parent::__construct();
        $this->adi = new Deger("adi","ADI",Base::KELIME, Base::HAYIR,null);
        $this->kod = new Deger("kod","KOD",Base::KELIME, Base::HAYIR,null);
    }
    
    const tabloAra = array(
        "alan" => array(
            "adi"   => array("Id" ,"Adi"),
            "db"    => array("id" ,"adi"),
        ),
        "donen"  => array("id" ,"adi"),
        "sql"    => VergiDairesi::TABLO_ARA
    );
    
}