<?php
//include_once '../Base.class.php';

Class Il extends Base{
    
    public $adi;

    public function vt_Adi()    {return "İLLER";}
    public function vt_dbAdi()  {return "il";}
    public function vt_Order()  {return " order by adi";}
    
    function __construct(){
        parent::__construct();
        $this->adi = new Deger("adi","ADI",Base::KELIME, Base::HAYIR,null);
    }
}