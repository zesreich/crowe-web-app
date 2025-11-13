<?php
Class IsOrtakPay extends Base{
    
    const GET_ORTAK_ID = array("SELECT * FROM ".Config::DB_DATABASE.".is_ortak_pay WHERE ortak_id = :_ortak_id ",array('ortak_id'=>Base::SAYI));
    
    public $ortak_id    ;
    public $pay_ortak_id;
    public $pay         ;

    public function vt_Adi()    {return "İŞ ORTAĞI";}
    public function vt_dbAdi()  {return "is_ortak_pay";}
    public function vt_Order()  {return "";}
    
    function __construct(){
        parent::__construct();
        $this->ortak_id     = new Deger("ortak_id"      ,"ORTAK"    , Base::KELIME, Base::HAYIR, new Ref(new IsOrtagi(), Base::TEK,'id'));
        $this->pay_ortak_id = new Deger("pay_ortak_id"  ,"PAY ORTAK", Base::SAYI,   Base::HAYIR, new Ref(new IsOrtagi(), Base::TEK,'id'));
        $this->pay          = new Deger("pay"           ,"PAY"      , Base::KELIME, Base::HAYIR, null);
    }
}