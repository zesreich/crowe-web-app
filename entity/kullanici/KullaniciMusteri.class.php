<?php
Class KullaniciMusteri extends Base{
    
    const FIND_KULLANICI = array("SELECT * FROM ".Config::DB_DATABASE.".kullanici_musteri k WHERE k.kullanici_id=:_id",array('id'=>Base::KELIME));
    const DELETE_KUL_ID  = array("DELETE FROM  ".Config::DB_DATABASE.".kullanici_musteri WHERE kullanici_id = :_kullanici_id" ,array('kullanici_id'=>Base::SAYI));
    
    public $kullanici_id;
    public $musteri_id;

    public function vt_Adi()    {return "KULLANICI MUSTERI";}
    public function vt_dbAdi()  {return "kullanici_musteri";}
    public function vt_Order()  {return "";}
    
    function __construct(){
        parent::__construct();
        $this->kullanici_id = new Deger("kullanici_id", "KULLANICI",    Base::SAYI,     Base::HAYIR,new Ref(new Kullanici(), Base::TEK,'id'));
        $this->musteri_id   = new Deger("musteri_id",   "MUSTERI",      Base::SAYI,     Base::HAYIR,new Ref(new Musteri(),   Base::TEK,'id'));
    }
}