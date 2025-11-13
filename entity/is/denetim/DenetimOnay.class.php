<?php
Class DenetimOnay extends Base{
    
    const DENETIM_SIFRE     = array("SELECT * FROM  ".Config::DB_DATABASE.".denetim_onay WHERE sifre = :_sifre ",array('sifre'=>Base::KELIME));
    const DENETIM_REF_ID    = array("SELECT * FROM  ".Config::DB_DATABASE.".denetim_onay WHERE tamammi='H' AND referans_id = :_refId ",array('refId'=>Base::KELIME));
    const DELETE_TEKLIF_ID  = array("DELETE FROM    ".Config::DB_DATABASE.".denetim_onay WHERE referans_id = :_tklf_id ",array('tklf_id'=>Base::SAYI));
    
    public $referans_id ;
    public $sifre       ;
    public $link        ;
    public $son_trh     ;
    public $tamammi     ;
    
    public function vt_Adi()    {return "DENETİM ONAY";}
    public function vt_dbAdi()  {return "denetim_onay";}
    public function vt_Order()  {return "";}
    
    function __construct(){
        parent::__construct();
        $this->referans_id= new Deger("referans_id" ,"REFERANS ID",Base::SAYI,  Base::HAYIR, null);
        $this->sifre      = new Deger("sifre"       ,"ŞİFRE",      Base::KELIME,Base::HAYIR, null);
        $this->link       = new Deger("link"        ,"LINK",       Base::KELIME,Base::HAYIR, null);
        $this->son_trh    = new Deger("son_trh"     ,"SON TARIHI", Base::KELIME,Base::HAYIR, null);
        $this->tamammi    = new Deger("tamammi"     ,"TAMAM MI",   Base::KELIME,Base::HAYIR, null);
    }
    
}