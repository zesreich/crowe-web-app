<?php
Class RiskProsedur extends Base{
    
    const GET_BY_KOD     = array("SELECT * FROM ".Config::DB_DATABASE.".risk_prosedur WHERE kod = :_kod and aktif = 'E' ",array('kod'=>Base::KELIME,));
    const GET_BY_AKTIF   = array("SELECT * FROM ".Config::DB_DATABASE.".risk_prosedur WHERE aktif = 'E' ",array());
    const GET_BY_RISK    = array("SELECT * FROM ".Config::DB_DATABASE.".risk_prosedur WHERE risk_id = :_risk ",array('risk'=>Base::SAYI,));
    const ARA_RISK_ID    = array("SELECT r.id,r.kod,r.adi FROM ".Config::DB_DATABASE.".risk_prosedur r where r.risk_id = :_risk",array('risk'=>Base::SAYI,));
    
    public $risk_id ;
    public $kod     ;
    public $adi     ;
    public $aktif   ;
    public $drive_id;
    
    public $b_var   ;
    public $b_haklar;
    public $b_tamlik;
    public $b_deger ;
    
    public $g_meydan;
    public $g_tamlik;
    public $g_dogru ;
    public $g_cutoff;
    public $g_sinif ;
    
    public $s_meydan;
    public $s_tamlik;
    public $s_sinif ;
    public $s_dogru ;
    
    public function vt_Adi()    {return "RİSK PROSEDÜR";}
    public function vt_dbAdi()  {return "risk_prosedur";}
    public function vt_Order()  {return "";}
    
    function __construct(){
        parent::__construct();
        $this->risk_id   = new Deger("risk_id"  ,"RİSK",                            Base::SAYI,   Base::EVET,  new Ref(new RiskListesi(), Base::TEK,'id'));
        $this->kod       = new Deger("kod"      ,"KOD",                             Base::KELIME, Base::HAYIR, null);
        $this->adi       = new Deger("adi"      ,"ADI",                             Base::KELIME, Base::HAYIR, null);
        $this->aktif     = new Deger("aktif"    ,"AKTİF",                           Base::KELIME, Base::HAYIR, null);
        $this->drive_id  = new Deger("drive_id" ,"DRIVE ID",                        Base::KELIME, Base::EVET,  null);
        
        $this->b_var     = new Deger("b_var"    ,"Bilanço Var olma",                Base::KELIME, Base::HAYIR,null);
        $this->b_haklar  = new Deger("b_haklar" ,"Bilanço Haklar ve zorunluluklar", Base::KELIME, Base::HAYIR,null);
        $this->b_tamlik  = new Deger("b_tamlik" ,"Bilanço Tamlık",                  Base::KELIME, Base::HAYIR,null);
        $this->b_deger   = new Deger("b_deger"  ,"Bilanço Değerleme ve tahsis",     Base::KELIME, Base::HAYIR,null);

        $this->g_meydan  = new Deger("g_meydan" ,"Gelir Tablosu Meydana gelme",     Base::KELIME, Base::HAYIR,null);
        $this->g_tamlik  = new Deger("g_tamlik" ,"Gelir Tablosu Tamlık",            Base::KELIME, Base::HAYIR,null);
        $this->g_dogru   = new Deger("g_dogru"  ,"Gelir Tablosu Doğruluk",          Base::KELIME, Base::HAYIR,null);
        $this->g_cutoff  = new Deger("g_cutoff" ,"Gelir Tablosu Cutoff",            Base::KELIME, Base::HAYIR,null);
        $this->g_sinif   = new Deger("g_sinif"  ,"Gelir Tablosu Sınıflandırma",     Base::KELIME, Base::HAYIR,null);

        $this->s_meydan  = new Deger("s_meydan" ,"Sunum ve açıklama Meydana gelme", Base::KELIME, Base::HAYIR,null);
        $this->s_tamlik  = new Deger("s_tamlik" ,"Sunum ve açıklama Tamlık",        Base::KELIME, Base::HAYIR,null);
        $this->s_sinif   = new Deger("s_sinif"  ,"Sunum ve açıklama Sınıflandırma ve anlaşılabilirlik", Base::KELIME, Base::HAYIR,null);
        $this->s_dogru   = new Deger("s_dogru"  ,"Sunum ve açıklama Doğruluk ve değerleme",              Base::KELIME, Base::HAYIR,null);
    }
}