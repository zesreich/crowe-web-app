<?php
include_once 'Base.class.php';

Class KullaniciTurPrm extends Base{
    
    const MUSTERI    = 9;
    const DENETCI    = 75;
    const ISORTAGI   = 76;
    const IT         = 77;
    
    public $adi;

    public function vt_Adi()    {return "KULLANICI TÜRÜ";}
    public function vt_dbAdi()  {return "kullanici_tur_prm";}
    public function vt_Order()  {return "";}
    
    function __construct(){
        parent::__construct();
        $this->adi              = new Deger("adi"               ,"ADI"          , Base::KELIME, Base::HAYIR,null);
    }

    
}