<?php
Class TklfDil extends Base{
    
    public $adi;
    public $kisaltma;

    const TR        = '1';
    const ING       = '2';
    const TR_ING    = '3';
    
    public function vt_Adi()    {return "DİL";}
    public function vt_dbAdi()  {return "tklf_dil_prm";}
    public function vt_Order()  {return " order by id";}
    
    function __construct(){
        parent::__construct();
        $this->adi      = new Deger("adi",      "ADI",      Base::KELIME, Base::HAYIR,null);
        $this->kisaltma = new Deger("kisaltma", "KISALTMA", Base::KELIME, Base::HAYIR,null);
    }
}