<?php
include_once 'Base.class.php';

Class YetkiKullanici extends Base{
    
    public $yetki_id;
    public $kullanici_id;

    public function vt_Adi()    {return "YETKİ KULLANICI";}
    public function vt_dbAdi()  {return "yetki_kullanici";}
    public function vt_Order()  {return "";}
    
    function __construct(){
        parent::__construct();
        $this->yetki_id     = new Deger("yetki_id",    "YETKI",     Base::SAYI, Base::HAYIR,new Ref(new YetkiProgram(), Base::TEK,'id'));
        $this->kullanici_id = new Deger("kullanici_id","KULLANICI", Base::SAYI, Base::HAYIR,new Ref(new Program(),      Base::TEK,'id'));
    }
}