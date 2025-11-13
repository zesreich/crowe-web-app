<?php
Class KullaniciIsortagi extends Base{
    
    const FIND_KULLANICI = array("SELECT * FROM ".Config::DB_DATABASE.".kullanici_isortagi k WHERE k.kullanici_id=:_id",array('id'=>Base::KELIME));
    const DELETE_KUL_ID  = array("DELETE FROM  ".Config::DB_DATABASE.".kullanici_isortagi WHERE kullanici_id = :_kullanici_id" ,array('kullanici_id'=>Base::SAYI));
    
    public $kullanici_id;
    public $isortagi_id;

    public function vt_Adi()    {return "KULLANICI MUSTERI";}
    public function vt_dbAdi()  {return "kullanici_isortagi";}
    public function vt_Order()  {return "";}
    
    function __construct(){
        parent::__construct();
        $this->kullanici_id = new Deger("kullanici_id", "KULLANICI",    Base::SAYI,     Base::HAYIR,new Ref(new Kullanici(), Base::TEK,'id'));
        $this->isortagi_id  = new Deger("isortagi_id",  "İŞ ORTAĞI",    Base::SAYI,     Base::HAYIR,new Ref(new IsOrtagi(),  Base::TEK,'id'));
    }
}