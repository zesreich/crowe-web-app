<?php
include_once 'Base.class.php';

Class GrupPrm extends Base{
    
    const KULLANICI_GRUPLARI = array("SELECT g.* FROM ".Config::DB_DATABASE.".grup_prm g WHERE g.kullanici_tur_id =:_kullaniciTurId",array('kullaniciTurId'=>Base::SAYI));
    
    public $adi;
    public $kullanici_tur_id;

    public function vt_Adi()    {return "GRUP PARAMETRELERİ";}
    public function vt_dbAdi()  {return "grup_prm";}
    public function vt_Order()  {return " order by adi";}
    
    function __construct(){
        parent::__construct();
        $this->adi = new Deger("adi","ADI",Base::KELIME, Base::HAYIR,null);
        $this->kullanici_tur_id = new Deger("kullanici_tur_id"  ,"KULLANICI TÜR", Base::SAYI,   Base::HAYIR,new Ref(new KullaniciTurPrm(),      Base::TEK,'id'));
    }

    
}