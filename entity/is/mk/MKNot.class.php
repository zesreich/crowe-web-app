<?php
Class MKNot extends Base{
    
    const GET_TEKLIF_ID         = array("SELECT * FROM ".Config::DB_DATABASE.".mk_not WHERE tklf_id = :_tklf_id order by grup,kod",array('tklf_id'=>Base::SAYI));
    const DELETE_TEKLIF_ID      = array("DELETE FROM   ".Config::DB_DATABASE.".mk_not WHERE tklf_id = :_tklf_id ",array('tklf_id'=>Base::SAYI));
    const GET_TEKLIF_ID_CEVAPSIZ= array("SELECT
        grup,
        kod,
        (CASE 
            WHEN cevap is null THEN 1
            ELSE 0
        END) AS snc
        FROM ".Config::DB_DATABASE.".mk_not WHERE tklf_id = :_tklf_id order by grup, kod",array('tklf_id'=>Base::SAYI));
    const GET_TEKLIF_ID_GRUP_CEVAPSIZ= array("SELECT
        grup,
        kod,
        (CASE
            WHEN cevap is null THEN 1
            ELSE 0
        END) AS snc
        FROM ".Config::DB_DATABASE.".mk_not WHERE tklf_id = :_tklf_id and grup = :_grup order by  kod",array('tklf_id'=>Base::SAYI,'grup'=>Base::KELIME));
    const GET_TEKLIF_GRUP_KOD   = array("SELECT * FROM ".Config::DB_DATABASE.".mk_not WHERE tklf_id = :_tklf_id and grup = :_grup and kod = :_kod ",array('tklf_id'=>Base::SAYI,'grup'=>Base::KELIME,'kod'=>Base::KELIME));
    const NOTLAR_KISI_ISIMLER   = array("SELECT 
                                            m.id,
                                            m.grup,
                                            m.kod,
                                            m.soru,
                                            m.cevap,
                                            m.gmt,
                                            m.create_gmt,
                                            kc.ad as c_ad,
                                            kc.soyad as c_soyad,
                                            ku.ad as u_ad,
                                            ku.soyad as u_soyad 
                                        FROM ".Config::DB_DATABASE.".mk_not m, ".Config::DB_DATABASE.".kullanici kc, ".Config::DB_DATABASE.".kullanici ku 
                                        WHERE m.user_id=kc.id and m.create_user_id=ku.id and m.tklf_id = :_tklf_id and m.grup = :_grup and m.kod = :_kod",array('tklf_id'=>Base::SAYI,'grup'=>Base::KELIME,'kod'=>Base::KELIME));

    public $tklf_id ;
    public $grup    ;
    public $kod     ;
    public $soru    ;
    public $cevap   ;
    
    public function vt_Adi()    {return "MK NOT";}
    public function vt_dbAdi()  {return "mk_not";}
    public function vt_Order()  {return "";}
    
    function __construct(){
        parent::__construct();
        $this->tklf_id  = new Deger("tklf_id"   ,"TEKLİF ID",   Base::KELIME,   Base::HAYIR, null);
        $this->grup     = new Deger("grup"      ,"GRUP",        Base::KELIME,   Base::HAYIR, null);
        $this->kod      = new Deger("kod"       ,"KOD",         Base::KELIME,   Base::HAYIR, null);
        $this->soru     = new Deger("soru"      ,"SORU",        Base::KELIME,   Base::HAYIR, null);
        $this->cevap    = new Deger("cevap"     ,"CEVAP",       Base::KELIME,   Base::EVET,  null);
    }
}