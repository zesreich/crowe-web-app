<?php
Class MkDurum extends Base{

    public $adi     ;
    public $grup_id ;
    public $grup_adi;
    
    public function vt_Adi()    {return "MÜŞTERİ KABUL DURUMLAR";}
    public function vt_dbAdi()  {return "mk_durum";}
    public function vt_Order()  {return "";}
    
    function __construct(){
        parent::__construct();
        $this->adi      = new Deger("aciklama","AÇIKLAMA",Base::KELIME, Base::HAYIR, null);
    }
    
}