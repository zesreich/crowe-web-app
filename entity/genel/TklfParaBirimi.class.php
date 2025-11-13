<?php
Class TklfParaBirimi extends Base{
    
    public $adi;
    public $sembol;

    public function vt_Adi()    {return "PARA BİRİMİ";}
    public function vt_dbAdi()  {return "tklf_para_birimi_prm";}
    public function vt_Order()  {return " order by id";}
    
    function __construct(){
        parent::__construct();
        $this->adi      = new Deger("adi",   "ADI",      Base::KELIME, Base::HAYIR,null);
        $this->sembol   = new Deger("sembol","SEMBOL",   Base::KELIME, Base::HAYIR,null);
    }
}