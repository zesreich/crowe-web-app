<?php
Class Sektor extends Base{
    
    public $adi;

    public function vt_Adi()    {return "SEKTÖR";}
    public function vt_dbAdi()  {return "sektor_prm";}
    public function vt_Order()  {return " order by adi";}
    
    function __construct(){
        parent::__construct();
        $this->adi = new Deger("adi","ADI",Base::KELIME, Base::HAYIR,null);
    }
}