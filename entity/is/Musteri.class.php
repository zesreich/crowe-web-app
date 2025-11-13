<?php
Class Musteri extends Base{
    
    const ALL       = array("SELECT * FROM ".Config::DB_DATABASE.".musteri ",array());
    const TABLO_ARA = array("SELECT m.id, m.unvan as adi, i.unvan as isortagi, m.email FROM ".Config::DB_DATABASE.".musteri m,is_ortagi i where m.isortagi_id  = i.id",array());
    
    public $unvan           ;
    public $vergi_no        ;
    public $vergi_daire_id  ;
    public $adres           ;
    public $telefon         ;
    public $faks            ;
    public $web             ;
    public $email           ;
    public $il_id           ;
    public $ilce_id         ;
    public $isortagi_id     ;
    public $sektor_id       ;
    public $sicil_no        ;
    public $mernis_no       ;
    
    public function vt_Adi()    {return "MÜŞTERİ";}
    public function vt_dbAdi()  {return "musteri";}
    public function vt_Order()  {return "";}
    
    function __construct(){
        parent::__construct();
        $this->unvan             = new Deger("unvan"            ,"ÜNVAN",           Base::KELIME, Base::HAYIR,null);
        $this->vergi_no          = new Deger("vergi_no"         ,"VERGİ NO",        Base::KELIME, Base::HAYIR, null);
        $this->vergi_daire_id    = new Deger("vergi_daire_id"   ,"VERGİ DAİRESİ",   Base::SAYI,   Base::EVET, new Ref(new VergiDairesi(), Base::TEK,'id'));
        $this->adres             = new Deger("adres"            ,"ADRES",           Base::KELIME, Base::EVET, null);
        $this->telefon           = new Deger("telefon"          ,"TELEFON",         Base::KELIME, Base::EVET, null);
        $this->faks              = new Deger("faks"             ,"FAKS",            Base::KELIME, Base::EVET, null);
        $this->web               = new Deger("web"              ,"WEB",             Base::KELIME, Base::EVET, null);
        $this->email             = new Deger("email"            ,"EMAIL",           Base::KELIME, Base::EVET, null);
        $this->il_id             = new Deger("il_id"            ,"IL",              Base::SAYI,   Base::HAYIR,new Ref(new Il(), Base::TEK,'id'));
        $this->ilce_id           = new Deger("ilce_id"          ,"İLÇE",            Base::SAYI,   Base::EVET,new Ref(new Ilce(), Base::TEK,'id'));
        $this->isortagi_id       = new Deger("isortagi_id"      ,"İŞ ORTAĞI",       Base::SAYI,   Base::HAYIR,new Ref(new IsOrtagi(), Base::TEK,'id'));
        $this->sektor_id         = new Deger("sektor_id"        ,"SEKTÖR",          Base::SAYI,   Base::EVET, new Ref(new Sektor(), Base::TEK,'id'));
        $this->sicil_no          = new Deger("sicil_no"         ,"SICIL NO",        Base::KELIME, Base::EVET, null);
        $this->mernis_no         = new Deger("mernis_no"        ,"MERNİS NO",       Base::SAYI,   Base::EVET, null);
    }

    const tabloAra = array(
        "alan" => array(
                    "adi"   => array("Id" ,"Adi", "İş Ortağı",),
                    "db"    => array("id" ,"adi", "isortagi",),
                ),
        "donen"  => array("id" ,"adi","email"),
        "sql"    => Musteri::TABLO_ARA
    );
    
}