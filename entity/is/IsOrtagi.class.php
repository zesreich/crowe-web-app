<?php
Class IsOrtagi extends Base{
    
    const TABLO_ARA         = array("SELECT i.id, i.unvan FROM ".Config::DB_DATABASE.".is_ortagi i",array());
    
    public $unvan           ;
    public $vergi_daire_id  ;
    public $vergi_no        ;
    public $adres           ;
    public $telefon         ;
    public $faks            ;
    public $web             ;
    public $email           ;
    public $il_id           ;
    public $sektor_id       ;
    public $mernis_no       ;

    
    public function vt_Adi()    {return "İŞ ORTAĞI";}
    public function vt_dbAdi()  {return "is_ortagi";}
    public function vt_Order()  {return "";}
    
    function __construct(){
        parent::__construct();
        $this->unvan             = new Deger("unvan"            ,"ÜNVAN / AD SOYAD",Base::KELIME, Base::HAYIR,null);
        $this->vergi_daire_id    = new Deger("vergi_daire_id"   ,"VERGİ DAİRESİ",   Base::SAYI,   Base::EVET, new Ref(new VergiDairesi(), Base::TEK,'id'));
        $this->vergi_no          = new Deger("vergi_no"         ,"VERGİ NO",        Base::KELIME, Base::EVET, null);
        $this->adres             = new Deger("adres"            ,"ADRES",           Base::KELIME, Base::EVET, null);
        $this->telefon           = new Deger("telefon"          ,"TELEFON",         Base::KELIME, Base::EVET, null);
        $this->faks              = new Deger("faks"             ,"FAKS",            Base::KELIME, Base::EVET, null);
        $this->web               = new Deger("web"              ,"WEB",             Base::KELIME, Base::EVET, null);
        $this->email             = new Deger("email"            ,"EMAIL",           Base::KELIME, Base::EVET, null);
        $this->il_id             = new Deger("il_id"            ,"IL",              Base::SAYI,   Base::HAYIR,new Ref(new Il(), Base::TEK,'id'));
        $this->sektor_id         = new Deger("sektor_id"        ,"SEKTÖR",          Base::SAYI,   Base::EVET, new Ref(new Sektor(), Base::TEK,'id'));
        $this->mernis_no         = new Deger("mernis_no"        ,"MERNİS NO",       Base::SAYI,   Base::EVET, null);
    }

    const tabloAra = array(
        "alan" => array(
            "adi"   => array("Id" ,"ÜNVAN"),
            "db"    => array("id" ,"unvan"       ),
        ),
        "donen"  => array("id" ,"unvan"),
        "sql"    => IsOrtagi::TABLO_ARA
    );
    
}