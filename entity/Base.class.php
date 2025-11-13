<?php
include_once PREPATH . 'config/config.php';
abstract Class Base{
    
    //Liste Elemanlar�
    const   EVET        = 'E';
    const   HAYIR       = 'H';
    const   OZEL        = 'O';
    const   PASIF       = 'P';
    
    //Sayfa G�r�n�mleri
    const   DUZENLE     = 'D';
    const   DUZENLIST   = 'DL';
    const   ARAMA       = 'ARA';
    const   EKLEME      = 'E';
    const   NULLMU      = 'N';
    
    //Sorgu Tipi
    const   SAYI        = 'd';
    const   KELIME      = 's';
    
    //Ba�lant� t�r�
    const   TEK         = 'T';
    const   COK         = 'C';
    const   LIZT        = 'L';
    
    abstract function vt_Adi();
    abstract function vt_dbAdi();
    abstract function vt_Order();
    
    public $id;
    public $gmt;
    public $create_gmt;
    public $user_id;
    public $create_user_id;
       
    function __construct(){
        $this->id               = new Deger("id",               "ID",                   Base::SAYI,   Base::HAYIR,null);
        $this->gmt              = new Deger("gmt",              "DEĞİŞİKLİK TARİHİ",    Base::KELIME, Base::HAYIR,null);
        $this->create_gmt       = new Deger("create_gmt",       "OLUŞTURULMA TARİHİ",   Base::KELIME, Base::HAYIR,null);
        $this->user_id          = new Deger("user_id",          "DEĞİŞTİREN KULLANICI", Base::SAYI,   Base::HAYIR,null);
        $this->create_user_id   = new Deger("create_user_id",   "OLUŞTURAN KULLANICI",  Base::SAYI,   Base::HAYIR,null);
    }
    
    public function basit($tbb = null){
        if ($tbb == null) {
            $tbl = get_object_vars($this);
        }else{
            $tbl = $tbb;
        }
        return Base::basitS($tbl);
    }

    static public function basitList($lst = null){
        if ($lst == null){
            return null;
        }
        $tbl = array();
        foreach($lst as $value){
            array_push($tbl, $value->basit());
        }
        return $tbl;
    }
    
    
    static public function basitS($tbb){
        $arr = array();
        foreach($tbb as $key => $value){
            if ($value->ref == null || ($value->ref != null && $value->ref->deger == null)){
                $arr[$key] = $value->deger;
            }else{
                $arr[$key] = Base::basitS($value->ref->deger);
            }
        }
        return $arr;
    }

    public function entToJson($tbb = null){
        $arr = Base::basit($tbb);
        if ($tbb == null) {
            return json_encode($arr,JSON_UNESCAPED_UNICODE);
        }else{
            return $arr;
        }
    }
    
}

Class Deger{
    var $deger;
    var $dbAdi;
    var $adi;
    var $tur;
    var $null;
    var $ref;
    
    function __construct($dbAdi,$adi,$tur,$null,$ref) {
        $this->dbAdi = $dbAdi;
        $this->adi  = $adi;
        $this->tur  = $tur;
        $this->null = $null;
        $this->ref  = $ref;
    }
    
    public function __toString(){
        if ($this->ref == null || ($this->ref != null && $this->ref->deger == null)){
            return ($this->deger == null ? "" : $this->deger );
        }else{
            return $this->ref->deger;
        }
    }
    
}

Class Ref{
    public $deger;
    public $tablo;      //kullanici
    public $baglanti;   //cok
    public $pk;         //pk
    
    function __construct($tablo,$baglanti,$pk) {
        $this->tablo       = $tablo;
        $this->baglanti    = $baglanti;
        $this->pk          = $pk;
    }
}

