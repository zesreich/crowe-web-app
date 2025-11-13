<?php
include_once PREPATH . 'config/config.php';
Class MusteriKabul extends Base{
    
    const GET_TEKLIF_ID         = array("SELECT * FROM ".Config::DB_DATABASE.".musteri_kabul WHERE tklf_id = :_tklf_id ",array('tklf_id'=>Base::SAYI));
    const DELETE_TEKLIF_ID      = array("DELETE FROM ".Config::DB_DATABASE.".musteri_kabul WHERE tklf_id = :_tklf_id ",array('tklf_id'=>Base::SAYI));
    const GECMIS_BY_MUSTERIID   = array("SELECT mk.tklf_id,DATE_FORMAT(d.donem_bas_trh, '%d.%m.%Y') as donem_bas_trh , DATE_FORMAT(d.donem_bts_trh, '%d.%m.%Y') as donem_bts_trh,DATE_FORMAT(d.donem_bas_trh, '%Y') as trh FROM ".Config::DB_DATABASE.".musteri_kabul mk, ".Config::DB_DATABASE.".denetim d WHERE d.id= mk.no and d.musteri_id = :_mstr_id order by  d.donem_bas_trh desc",
        array('mstr_id'=>Base::SAYI));
    
    public $no      ;
    public $tklf_id ;
    public $durum   ;
    public $drive_id;
    public $denetci_drive_id;
    public $belge1  ;
    public $belge2  ;
    public $kabul   ;
    public $degerlendirme ;
    
    public function vt_Adi()    {return "MÜŞTERİ KABUL";}
    public function vt_dbAdi()  {return "musteri_kabul";}
    public function vt_Order()  {return "";}
    
    function __construct(){
        parent::__construct();
        $this->no               = new Deger("no"        ,"NO",          Base::SAYI,     Base::HAYIR, null);
        $this->tklf_id          = new Deger("tklf_id"   ,"TEKLİF ID",   Base::KELIME,   Base::HAYIR, null);
        $this->durum            = new Deger("durum"     ,"DURUM",       Base::SAYI,     Base::HAYIR, null);
//         $this->durum            = new Deger("durum"     ,"DURUM",       Base::SAYI,     Base::HAYIR, new Ref(new MkDurum(), Base::TEK,'id'));
        $this->drive_id         = new Deger("drive_id"  ,"DRIVE ID",    Base::KELIME,   Base::EVET, null);
        $this->denetci_drive_id = new Deger("denetci_drive_id"  ,"DENETÇİ DRIVE ID",    Base::KELIME,     Base::EVET, null);
        $this->belge1           = new Deger("belge1"    ,"BELGE 1",     Base::KELIME,   Base::EVET,  null);
        $this->belge2           = new Deger("belge2"    ,"BELGE 2",     Base::KELIME,   Base::EVET,  null);
        $this->kabul            = new Deger("kabul"     ,"KABUL",       Base::KELIME,   Base::EVET,  null);
        $this->degerlendirme    = new Deger("degerlendirme","DEĞERLENDİRME",Base::KELIME,Base::EVET,  null);
    }
}