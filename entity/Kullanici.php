<?php
include_once 'Base.class.php';

Class Kullanici extends Base{
    
    const KULLANICI_WTH_ADI = array("SELECT * FROM ".Config::DB_DATABASE.".kullanici WHERE ad=:_ad",array('ad'=>Base::KELIME));
    const LOGIN             = array("SELECT * FROM ".Config::DB_DATABASE.".kullanici WHERE kullanici_adi=:_kAdi AND sifre_md5=:_sfr",array('kAdi'=>Base::KELIME,'sfr'=>Base::KELIME));
    const KULLANICI_MUSTERI = array("SELECT * FROM ".Config::DB_DATABASE.".kullanici k WHERE k.id in (SELECT m.kullanici_id FROM ".Config::DB_DATABASE.".kullanici_musteri m WHERE m.musteri_id =:_musteri )",array('musteri'=>Base::SAYI));
    const KULLANICI_ISORTAG = array("SELECT * FROM ".Config::DB_DATABASE.".kullanici k WHERE k.id in (SELECT m.kullanici_id FROM ".Config::DB_DATABASE.".kullanici_isortagi m WHERE m.isortagi_id =:_isortagi )",array('isortagi'=>Base::SAYI));
    const KULLANICI_TUR     = array("SELECT * FROM ".Config::DB_DATABASE.".kullanici k WHERE k.tur = :_tur ",array('tur'=>Base::SAYI));
    const TABLO_ARA         = array("SELECT m.id, m.tc_no, m.kgk_sicil_no, m.ad, m.soyad FROM ".Config::DB_DATABASE.".kullanici m",array());
    const TABLO_ARA_DENETCI = array("SELECT m.id, m.tc_no, m.kgk_sicil_no, m.ad, m.soyad, g.adi as grup FROM ".Config::DB_DATABASE.".kullanici m, ".Config::DB_DATABASE.".grup_prm g WHERE g.id = m.grup_id and m.tur=75 and m.grup_id<>18 and m.kgk_sicil_no is not null",array());
    const TABLO_ARA_YARDIMCI= array("SELECT m.id, m.tc_no, m.kgk_sicil_no, m.ad, m.soyad, g.adi as grup FROM ".Config::DB_DATABASE.".kullanici m, ".Config::DB_DATABASE.".grup_prm g WHERE g.id = m.grup_id and m.tur=75 ",array());
    const DELETE_ID_TUR     = array("DELETE FROM  ".Config::DB_DATABASE.".kullanici WHERE id = :_id and  tur = :_tur" ,array('id'=>Base::SAYI,'tur'=>Base::KELIME));
    
    public $kullanici_adi   ;
    public $ad              ;
    public $soyad           ;
    public $email           ;
    public $telefon         ;
    public $sifre           ;
    public $sifre_md5       ;
    public $tur             ;
    public $grup_id         ;
    public $duyuru_id       ;
    
    //denetci
    public $kgk_sicil_no    ;//KGK Sicil No
    public $tc_no           ;//TC Kimlik No
    public $ymm_ruhsat_no   ;//YMM Ruhsat No
    public $smmm_ruhsat_no  ;//SMMM Ruhsat No

    public function vt_Adi()    {return "KULLANICI";}
    public function vt_dbAdi()  {return "kullanici";}
    public function vt_Order()  {return "";}
    
    function __construct(){
        parent::__construct();
        $this->kullanici_adi    = new Deger("kullanici_adi" ,"KULLANICI ADI", Base::KELIME, Base::HAYIR,null);
        $this->ad               = new Deger("ad"            ,"AD",            Base::KELIME, Base::HAYIR,null);
        $this->soyad            = new Deger("soyad"         ,"SOYAD",         Base::KELIME, Base::HAYIR,null);
        $this->email            = new Deger("email"         ,"E-MAIL",        Base::KELIME, Base::EVET, null);
        $this->telefon          = new Deger("telefon"       ,"TELEFON",       Base::KELIME, Base::EVET, null);
        $this->sifre            = new Deger("sifre"         ,"ŞİFRE",         Base::KELIME, Base::HAYIR,null);
        $this->sifre_md5        = new Deger("sifre_md5"     ,"ŞİFRE MD5",     Base::KELIME, Base::HAYIR,null);
        $this->tur              = new Deger("tur"           ,"TÜR",           Base::SAYI,   Base::HAYIR,new Ref(new KullaniciTurPrm(), Base::TEK,'id'));
        $this->grup_id          = new Deger("grup_id"       ,"GRUP",          Base::SAYI,   Base::HAYIR,new Ref(new GrupPrm(), Base::TEK,'id'));
        $this->duyuru_id        = new Deger("duyuru_id"     ,"DUYURU",        Base::SAYI,   Base::EVET, null);
        $this->kgk_sicil_no     = new Deger("kgk_sicil_no"  ,"KGK Sicil No",  Base::KELIME, Base::EVET,null);
        $this->tc_no            = new Deger("tc_no"         ,"TC Kimlik No",  Base::KELIME, Base::EVET,null);
        $this->ymm_ruhsat_no    = new Deger("ymm_ruhsat_no" ,"YMM Ruhsat No", Base::KELIME, Base::EVET,null);
        $this->smmm_ruhsat_no   = new Deger("smmm_ruhsat_no","SMMM Ruhsat No",Base::KELIME, Base::EVET,null);
    }

    public static function denetciSqlGetir()
    {
        return Kullanici::TABLO_ARA_DENETCI;
    }

    public static function yardimciSqlGetir()
    {
        return Kullanici::TABLO_ARA_YARDIMCI;
    }
    
    const tabloAra = array(
        "alan" => array(
            "adi"   => array("Id" ,"TC Kimlik No"   ,"Adı"  , "Soyadı"  ,"Grup"),
            "db"    => array("id" ,"tc_no"          ,"ad"   , "soyad"   ,"grup"),
        ),
        "donen"  => array("id" ,"tc_no", "kgk_sicil_no", "ad","soyad"),
        "sql"    => Kullanici::TABLO_ARA
    );
    
}