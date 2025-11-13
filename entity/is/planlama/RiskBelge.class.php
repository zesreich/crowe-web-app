<?php
Class RiskBelge extends Base{
    
    const GET_BY_PROSEDUR = array("SELECT * FROM ".Config::DB_DATABASE.".risk_belge WHERE prosedur_id = :_prsdr ",array('prsdr'=>Base::SAYI,));
    
    public $sira    ;
    public $adi     ;
    public $aktif   ;
    public $prosedur_id;
    
    public function vt_Adi()    {return "Risk Belge";}
    public function vt_dbAdi()  {return "risk_belge";}
    public function vt_Order()  {return "";}
    
    function __construct(){
        parent::__construct();
        $this->sira     = new Deger("sira"      ,"SIRA",    Base::SAYI,     Base::HAYIR, null);
        $this->adi      = new Deger("adi"       ,"ADI",     Base::KELIME,   Base::HAYIR, null);
        $this->aktif    = new Deger("aktif"     ,"AKTİF",   Base::KELIME,   Base::HAYIR, null);
        $this->prosedur_id= new Deger("prosedur_id","PROSEDUR",Base::SAYI,     Base::HAYIR, null);
    }
    
}