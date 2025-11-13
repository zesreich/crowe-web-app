<?php
include_once 'Base.class.php';

Class mailSablon extends Base{
    
    const GET_KEY = array("SELECT g.* FROM ".Config::DB_DATABASE.".mail_sablon g WHERE g.skey =:_skey",array('skey'=>Base::KELIME));
    
    public $skey;
    public $baslik;
    public $mesaj;
    public $aciklama;

    public function vt_Adi()    {return "MAIL ŞABLON";}
    public function vt_dbAdi()  {return "mail_sablon";}
    public function vt_Order()  {return " order by skey";}
    
    function __construct(){
        parent::__construct();
        $this->skey     = new Deger("skey"      ,"KEY"      , Base::KELIME,   Base::HAYIR,null);
        $this->baslik   = new Deger("baslik"    ,"BASLIK"    , Base::KELIME,   Base::HAYIR,null);
        $this->mesaj    = new Deger("mesaj"     ,"MESAJ"    , Base::KELIME,   Base::HAYIR,null);
        $this->aciklama = new Deger("aciklama"  ,"ACIKLAMA" , Base::KELIME,   Base::HAYIR,null);
    }
}