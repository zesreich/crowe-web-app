<?php
Class PlanRiskProsedur extends Base{
    
    const GET_BY_KOD        = array("SELECT * FROM ".Config::DB_DATABASE.".plan_risk_prosedur WHERE kod = :_kod and aktif = 'E' ",array('kod'=>Base::KELIME,));
    const GET_BY_AKTIF      = array("SELECT * FROM ".Config::DB_DATABASE.".plan_risk_prosedur WHERE aktif = 'E' ",array());
    const DELETE_TEKLIF_ID  = array("DELETE   FROM ".Config::DB_DATABASE.".plan_risk_prosedur WHERE tklf_id = :_tklf_id ",array('tklf_id'=>Base::SAYI));
    
    public $tklf_id         ;
    public $risk_prosedur_id;
    public $kaynak          ;
    public $duzey           ;
    public $aciklama        ;
    public $drive_id        ;
    public $excel_drive_id  ;
    public $durum_id        ;
    public $kanit_varmi     ;
    public $bulgu_tutar     ;
    public $sonuc_aciklama  ;
    public $talep_edildi    ;
    public $tablo_duzelt    ;
    public $denetim_bulgu   ;
    public $muhtemel_etki   ;
    
    public function vt_Adi()    {return "PLAN RİSK PROSEDUR";}
    public function vt_dbAdi()  {return "plan_risk_prosedur";}
    public function vt_Order()  {return "";}
    
    function __construct(){
        parent::__construct();
        $this->tklf_id          = new Deger("tklf_id"           ,"TEKLİF ID"    ,Base::SAYI     ,Base::HAYIR, null);
        $this->risk_prosedur_id = new Deger("risk_prosedur_id"  ,"RİSK PROSEDUR",Base::SAYI     ,Base::HAYIR, new Ref(new RiskProsedur(), Base::TEK,'id'));
        $this->kaynak           = new Deger("kaynak"            ,"KAYNAK"       ,Base::KELIME   ,Base::EVET , null);
        $this->duzey            = new Deger("duzey"             ,"DÜZEY"        ,Base::KELIME   ,Base::EVET , null);
        $this->aciklama         = new Deger("aciklama"          ,"AÇIKLAMA"     ,Base::KELIME   ,Base::EVET , null);
        $this->drive_id         = new Deger("drive_id"          ,"DRIVE ID"     ,Base::KELIME   ,Base::EVET , null);
        $this->excel_drive_id   = new Deger("excel_drive_id"    ,"EXCEL DRIVE ID",Base::KELIME  ,Base::EVET , null);
        $this->durum_id         = new Deger("durum_id"          ,"DURUM"        ,Base::SAYI     ,Base::HAYIR, new Ref(new DenetimDurum(), Base::TEK,'id'));
        $this->kanit_varmi      = new Deger("kanit_varmi"       ,"KANIT VARMI"  ,Base::KELIME   ,Base::EVET,  null);
        $this->bulgu_tutar      = new Deger("bulgu_tutar"       ,"BULGU TUTAR"  ,Base::SAYI     ,Base::EVET,  null);
        $this->sonuc_aciklama   = new Deger("sonuc_aciklama"    ,"SONUÇ AÇIKLAMA",Base::KELIME  ,Base::EVET,  null);
        $this->talep_edildi     = new Deger("talep_edildi"      ,"TALEP EDİLDİ" ,Base::KELIME   ,Base::EVET,  null);
        $this->tablo_duzelt     = new Deger("tablo_duzelt"      ,"TABLO DUZELT" ,Base::KELIME   ,Base::EVET,  null);
        $this->denetim_bulgu    = new Deger("denetim_bulgu"     ,"DENETİM BULGU",Base::SAYI     ,Base::EVET,  null);
        $this->muhtemel_etki    = new Deger("muhtemel_etki"     ,"MUHTEMEL ETİK",Base::KELIME   ,Base::EVET,  null);
    }
    
}