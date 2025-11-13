<?php
Class TklfRaporDil extends Base{
    
    public $adi;
    public $kisaltma;
    
    public function vt_Adi()    {return "RAPOR DİL";}
    public function vt_dbAdi()  {return "tklf_rapor_dil_prm";}
    public function vt_Order()  {return " order by id";}
    
    function __construct(){
        parent::__construct();
        $this->adi      = new Deger("adi",      "ADI",      Base::KELIME, Base::HAYIR,null);
        $this->kisaltma = new Deger("kisaltma", "KISALTMA", Base::KELIME, Base::HAYIR,null);
    }
}