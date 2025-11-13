<?php
Class VergiDairesi extends Base{
    
    const TABLO_ARA = array("SELECT m.id, m.adi FROM ".Config::DB_DATABASE.".vergi_dairesi_prm m",array());
    
    public $adi;

    public function vt_Adi()    {return "VERGİ DAİRESİ";}
    public function vt_dbAdi()  {return "vergi_dairesi_prm";}
    public function vt_Order()  {return " order by adi";}
    
    function __construct(){
        parent::__construct();
        $this->adi = new Deger("adi","ADI",Base::KELIME, Base::HAYIR,null);
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