<?php
Class MK2 extends Base{
    
    const DELETE_TEKLIF_ID  = array("DELETE FROM    ".Config::DB_DATABASE.".mk2 WHERE tklf_id = :_tklf_id ",array('tklf_id'=>Base::SAYI));
    const GET_TEKLIF_ID     = array("SELECT * FROM  ".Config::DB_DATABASE.".mk2 WHERE tklf_id = :_tklf_id ",array('tklf_id'=>Base::SAYI));
    const UPDATE_ALAN       = array("UPDATE ".Config::DB_DATABASE.".mk2 SET :_aln = :_alan_cvp, :_gmt_usr WHERE tklf_id = :_tklf_id ",array('aln'=>Base::SAYI,'alan_cvp'=>Base::KELIME,'gmt_usr'=>Base::SAYI,'tklf_id'=>Base::SAYI));
    
    public $tklf_id ;
    public $kuruluslar      ;
    public $vergi_dairesi   ;
    public $kurumlar        ;
    public $yonetim         ;
    public $alacaklilar     ;
    public $yatirimcilar    ;
    public $uyeler          ;
    public $diger           ;
    
    public function vt_Adi()    {return "MK2";}
    public function vt_dbAdi()  {return "mk2";}
    public function vt_Order()  {return "";}
    
    function __construct(){
        parent::__construct();
        $this->tklf_id          = new Deger("tklf_id"       ,"TEKLİF ID",   Base::SAYI,     Base::HAYIR, null);
        $this->kuruluslar       = new Deger("kuruluslar"    ,"KURULUŞLAR",  Base::KELIME,   Base::EVET,  null);
        $this->vergi_dairesi    = new Deger("vergi_dairesi" ,"VERGİ DAİRESİ",Base::KELIME,   Base::EVET,  null);
        $this->kurumlar         = new Deger("kurumlar"      ,"KURUMLAR",    Base::KELIME,   Base::EVET,  null);
        $this->yonetim          = new Deger("yonetim"       ,"YÖNETİM",     Base::KELIME,   Base::EVET,  null);
        $this->alacaklilar      = new Deger("alacaklilar"   ,"ALACAKLILAR", Base::KELIME,   Base::EVET,  null);
        $this->yatirimcilar     = new Deger("yatirimcilar"  ,"YATIRIMCILAR",Base::KELIME,   Base::EVET,  null);
        $this->uyeler           = new Deger("uyeler"        ,"UYELER",      Base::KELIME,   Base::EVET,  null);
        $this->diger            = new Deger("diger"         ,"DİĞER",       Base::KELIME,   Base::EVET,  null);
    }
}