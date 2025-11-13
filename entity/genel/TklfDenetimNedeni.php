<?php
Class TklfDenetimNedeni extends Base{
    
    const NEDEN_SIRALI_BY_USTID = array("SELECT * FROM tklf_denetim_nedeni_prm WHERE ust_id = :_ustid ORDER BY  ust_id",array('ustid'=>Base::SAYI));
    const NEDEN_SIRALI          = array("SELECT * FROM tklf_denetim_nedeni_prm ORDER BY  ust_id",array());
    const YANLIZ_DEGER          = array("SELECT * FROM tklf_denetim_nedeni_prm WHERE klasor <> 'E'",array());
    
    
    public $ust_id      ;
    public $aciklama    ;
    public $klasor      ;
    public $ozel_ack    ;

    public function vt_Adi()    {return "DENETİM NEDENİ";}
    public function vt_dbAdi()  {return "tklf_denetim_nedeni_prm";}
    public function vt_Order()  {return "order by id";}
    
    function __construct(){
        parent::__construct();
        $this->ust_id       = new Deger("ust_id",    "ÜST ID",      Base::SAYI,   Base::HAYIR,null);
        $this->aciklama     = new Deger("aciklama",  "ACIKLAMA",    Base::KELIME, Base::HAYIR,null);
        $this->klasor       = new Deger("klasor",    "KLASÖR MÜ",   Base::KELIME, Base::HAYIR,null);
        $this->ozel_ack     = new Deger("ozel_ack",  "ÖZEL AÇIKLAMA",Base::KELIME, Base::HAYIR,null);
    }
    
}