
<?php
Class Sozlesme extends Base{
    
    const GET_TEKLIF_ID         = array("SELECT * FROM  ".Config::DB_DATABASE.".sozlesme WHERE tklf_id = :_tklf_id ",array('tklf_id'=>Base::SAYI));
    const DELETE_TEKLIF_ID      = array("DELETE FROM    ".Config::DB_DATABASE.".sozlesme WHERE tklf_id = :_tklf_id ",array('tklf_id'=>Base::SAYI));
    
    public $no      ;
    public $tklf_id ;
    public $durum   ;
    public $genel_kurul_trh;
    public $musteri_imza_trh;
    public $denetim_imza_trh;
    public $teslim_tarihi   ;
    public $imzasiz_drive_id;
    public $imzali_drive_id;
    
    public function vt_Adi()    {return "SÖZLEŞME";}
    public function vt_dbAdi()  {return "sozlesme";}
    public function vt_Order()  {return "";}
    
    function __construct(){
        parent::__construct();
        $this->no               = new Deger("no"                ,"NO",                  Base::KELIME,   Base::HAYIR, null);
        $this->tklf_id          = new Deger("tklf_id"           ,"TEKLİF ID",           Base::KELIME,   Base::HAYIR, null);
        $this->durum            = new Deger("durum"             ,"DURUM",               Base::SAYI,     Base::HAYIR, null);
        $this->genel_kurul_trh  = new Deger("genel_kurul_trh"   ,"Genel Kurul Tarihi",  Base::KELIME,   Base::EVET,  null);
        $this->musteri_imza_trh = new Deger("musteri_imza_trh"  ,"Müşteri İmza Tarihi", Base::KELIME,   Base::EVET,  null);
        $this->denetim_imza_trh = new Deger("denetim_imza_trh"  ,"Denetim İmza Tarihi", Base::KELIME,   Base::EVET,  null);
        $this->teslim_tarihi    = new Deger("teslim_tarihi"     ,"Raporların Teslim Tarihi",Base::KELIME,   Base::EVET, null);
        $this->imzasiz_drive_id = new Deger("imzasiz_drive_id"  ,"İmzasız Drive Id",    Base::KELIME,   Base::EVET, null);
        $this->imzali_drive_id  = new Deger("imzali_drive_id"   ,"İmzalı Drive Id",     Base::KELIME,   Base::EVET, null);
    }
}