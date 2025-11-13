<?php
Class TakvimPrm extends Base{
    
    public $adi;
    public $color;

    public function vt_Adi()    {return "TAKVİM PARAMETRE";}
    public function vt_dbAdi()  {return "takvim_prm";}
    public function vt_Order()  {return " order by adi";}
    
    function __construct(){
        parent::__construct();
        $this->adi      = new Deger("adi",      "ADI",      Base::KELIME, Base::HAYIR,null);
        $this->color    = new Deger("color",    "COLOR",    Base::KELIME, Base::HAYIR,null);
    }
}