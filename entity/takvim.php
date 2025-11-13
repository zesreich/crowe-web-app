<?php
include_once 'Base.class.php';

Class Takvim extends Base{
    
    const LIST_TEKLIF_ID    = array("SELECT * FROM  ".Config::DB_DATABASE.".takvim t WHERE t.denetci_id = :_did ",array('did'=>Base::SAYI));
    const DELETE_ID         = array("DELETE FROM    ".Config::DB_DATABASE.".takvim WHERE id = :_id ",array('id'=>Base::SAYI));
    
    public $denetci_id  ;
    public $aciklama    ;
    public $ilk         ;
    public $son         ;
    public $allDay      ;
    public $konu        ;

    public function vt_Adi()    {return "TAKVIM";}
    public function vt_dbAdi()  {return "takvim";}
    public function vt_Order()  {return "order by aciklama";}
    
    function __construct(){
        parent::__construct();
        $this->denetci_id = new Deger("denetci_id","DENETÇİ",  Base::SAYI,Base::HAYIR,null);
        $this->aciklama   = new Deger("aciklama","AÇIKLAMA",Base::KELIME, Base::HAYIR,null);
        $this->ilk        = new Deger("ilk",    "ILK",      Base::KELIME, Base::HAYIR,null);
        $this->son        = new Deger("son",    "SON",      Base::KELIME, Base::HAYIR,null);
        $this->allDay     = new Deger("allDay", "ALL DAY",  Base::KELIME, Base::HAYIR,null);
        $this->konu       = new Deger("konu",   "KONU",     Base::SAYI,   Base::HAYIR,null);
    }
    
}