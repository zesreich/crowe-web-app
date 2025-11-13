<?php
Class MK0 extends Base{
    
    const DELETE_TEKLIF_ID  = array("DELETE FROM    ".Config::DB_DATABASE.".mk0 WHERE tklf_id = :_tklf_id ",array('tklf_id'=>Base::SAYI));
    const GET_TEKLIF_ID     = array("SELECT * FROM  ".Config::DB_DATABASE.".mk0 WHERE tklf_id = :_tklf_id ",array('tklf_id'=>Base::SAYI));
    
    public $tklf_id ;

    public $denetim_maddesi     ;
    public $kayik               ;
    public $kayik_ack           ;
    public $halka_acik          ;
    public $d1_aktif_toplam     ;
    public $d1_net_satis        ;
    public $d1_calisan_sayisi   ;
    public $d2_aktif_toplam     ;
    public $d2_net_satis        ;
    public $d2_calisan_sayisi   ;
    
    public function vt_Adi()    {return "MK0";}
    public function vt_dbAdi()  {return "mk0";}
    public function vt_Order()  {return "";}
    
    function __construct(){
        parent::__construct();
        $this->tklf_id  = new Deger("tklf_id"   ,"TEKLİF ID",   Base::SAYI,     Base::HAYIR, null);

        $this->denetim_maddesi    = new Deger("denetim_maddesi"     ,"DENETİM MADDESİ",     Base::KELIME,   Base::EVET, null);
        $this->kayik              = new Deger("kayik"               ,"KAYIK",               Base::KELIME,   Base::EVET, null);
        $this->kayik_ack          = new Deger("kayik_ack"           ,"KAYIK AÇIKLAMA",      Base::KELIME,   Base::EVET, null);
        $this->halka_acik         = new Deger("halka_acik"          ,"HALKA AÇIK MI",       Base::KELIME,   Base::EVET, null);
        $this->d1_aktif_toplam    = new Deger("d1_aktif_toplam"     ,"1 AKTİF TOPLAM",      Base::SAYI,   Base::EVET, null);
        $this->d1_net_satis       = new Deger("d1_net_satis"        ,"1 NET SATIŞ",         Base::SAYI,   Base::EVET, null);
        $this->d1_calisan_sayisi  = new Deger("d1_calisan_sayisi"   ,"1 ÇALIŞAN SAYISI",    Base::SAYI,   Base::EVET, null);
        $this->d2_aktif_toplam    = new Deger("d2_aktif_toplam"     ,"2 AKTİF TOPLAM",      Base::SAYI,   Base::EVET, null);
        $this->d2_net_satis       = new Deger("d2_net_satis"        ,"2 NET SATIŞ",         Base::SAYI,   Base::EVET, null);
        $this->d2_calisan_sayisi  = new Deger("d2_calisan_sayisi"   ,"2 ÇALIŞAN SAYISI",    Base::SAYI,   Base::EVET, null);
    }
}