<?php
Class IsOrtakPayDeger extends Base{
    
    const DELETE_TEKLIF_ID      = array("DELETE   FROM  ".Config::DB_DATABASE.".is_ortak_pay_deger WHERE tklf_id = :_tklf_id ",array('tklf_id'=>Base::SAYI));
    const GET_TEKLIF_ID         = array("SELECT * FROM  ".Config::DB_DATABASE.".is_ortak_pay_deger WHERE tklf_id = :_tklf_id ",array('tklf_id'=>Base::SAYI));
    const GET_PAYORTAK_ID       = array("SELECT * FROM  ".Config::DB_DATABASE.".is_ortak_pay_deger WHERE ortak_id <> :_ortak_id and pay_ortak_id = :_pay_ortak_id ",array('pay_ortak_id'=>Base::SAYI,'ortak_id'=>Base::SAYI));
    const GET_TEKLIF_ORTAK_ID   = array("SELECT * FROM  ".Config::DB_DATABASE.".is_ortak_pay_deger WHERE tklf_id = :_tklf_id and pay_ortak_id = :_pay_ortak_id ",array('tklf_id'=>Base::SAYI,'pay_ortak_id'=>Base::SAYI));
    
    public $tklf_id     ;
    public $ortak_id    ;
    public $pay_ortak_id;
    public $pay         ;
    public $tutar       ;
    public $fatura      ;
    public $odeme       ;

    public function vt_Adi()    {return "İŞ ORTAK DEĞER";}
    public function vt_dbAdi()  {return "is_ortak_pay_deger";}
    public function vt_Order()  {return "";}
    
    function __construct(){
        parent::__construct();
        $this->tklf_id      = new Deger("tklf_id"       ,"TEKLİF ID", Base::SAYI,   Base::HAYIR, null);
        $this->ortak_id     = new Deger("ortak_id"      ,"ORTAK"    , Base::KELIME, Base::HAYIR, new Ref(new IsOrtagi(), Base::TEK,'id'));
        $this->pay_ortak_id = new Deger("pay_ortak_id"  ,"PAY ORTAK", Base::SAYI,   Base::HAYIR, new Ref(new IsOrtagi(), Base::TEK,'id'));
        $this->pay          = new Deger("pay"           ,"PAY"      , Base::KELIME, Base::HAYIR, null);
        $this->tutar        = new Deger("tutar"         ,"TUTAR"    , Base::SAYI,   Base::HAYIR, null);
        $this->fatura       = new Deger("fatura"        ,"FATURA"   , Base::KELIME, Base::HAYIR, null);
        $this->odeme        = new Deger("odeme"         ,"ÖDEME"    , Base::KELIME, Base::HAYIR, null);
    }
}