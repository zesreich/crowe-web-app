<?php
Class TklfOzelSartlar extends Base{
    
    public $aciklama;
    
    public function vt_Adi()    {return "ÖZEL ŞARTLAR";}
    public function vt_dbAdi()  {return "tklf_ozel_sartlar_prm";}
    public function vt_Order()  {return " order by id";}
    
    function __construct(){
        parent::__construct();
        $this->aciklama      = new Deger("aciklama",      "AÇIKLAMA",      Base::KELIME, Base::HAYIR,null);
    }
}