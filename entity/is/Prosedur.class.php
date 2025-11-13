 <?php
Class Prosedur extends Base{
    
    const DELETE_TEKLIF_ID      = array("DELETE FROM   ".Config::DB_DATABASE.".prosedur WHERE tklf_id = :_tklf_id ",                      array('tklf_id'=>Base::SAYI));
    const GET_TEKLIF_BY_GRUP    = array("SELECT * FROM ".Config::DB_DATABASE.".prosedur WHERE tklf_id = :_tklf_id and grup = :_grup ",  array('tklf_id'=>Base::SAYI,'grup'=>Base::KELIME));
    const GET_TEKLIF_BY_GRUP_KOD= array("SELECT * FROM ".Config::DB_DATABASE.".prosedur WHERE tklf_id = :_tklf_id and grup = :_grup and kod = :_kod ",  array('tklf_id'=>Base::SAYI,'grup'=>Base::KELIME,'kod'=>Base::KELIME));
    const GET_BY_TEKLIF         = array("SELECT * FROM ".Config::DB_DATABASE.".prosedur WHERE tklf_id = :_tklf_id order by grup,kod",   array('tklf_id'=>Base::SAYI));
    const GET_BY_TEKLIF_TIP     = array("SELECT * FROM ".Config::DB_DATABASE.".prosedur WHERE tklf_id = :_tklf_id and tip = :_tip order by id,grup,kod",   array('tklf_id'=>Base::SAYI,'tip'=>Base::KELIME));
    const GET_BY_ID             = array("SELECT * FROM ".Config::DB_DATABASE.".prosedur WHERE id = :_id ",   array('id'=>Base::SAYI));
//     const OZEL_NOTE_BY_ID       = array("SELECT note FROM prosedur WHERE id = :_id ",array('id'=>Base::SAYI));
    
    public $tklf_id     ;
    public $tip         ;
    public $ref_id      ;
    public $grup        ;
    public $kod         ;
    public $kapsami     ;
    public $zamani      ;
    public $aciklama    ;
    public $b80Aciklama ;
    public $b551Aciklama;
    public $sonuc       ;
    public $drive_id    ;
    
    public function vt_Adi()    {return "PROSEDÜR";}
    public function vt_dbAdi()  {return "prosedur";}
    public function vt_Order()  {return "";}
    
    function __construct(){
        parent::__construct();
        $this->tklf_id   = new Deger("tklf_id"  ,"TEKLİF ID",   Base::SAYI,     Base::HAYIR, null);
        $this->tip       = new Deger("tip"      ,"TIP",         Base::KELIME,   Base::HAYIR, null);
        $this->ref_id    = new Deger("ref_id"   ,"LIST ID",     Base::SAYI,     Base::HAYIR, null);
        $this->grup      = new Deger("grup"     ,"GRUP",        Base::KELIME,   Base::HAYIR, null);
        $this->kod       = new Deger("kod"      ,"KOD",         Base::KELIME,   Base::HAYIR, null);
        $this->kapsami   = new Deger("kapsami"  ,"KAPSAMI",     Base::KELIME,   Base::EVET,  null);
        $this->zamani    = new Deger("zamani"   ,"ZAMANI",      Base::KELIME,   Base::EVET,  null);
        $this->aciklama  = new Deger("aciklama" ,"AÇIKLAMA",    Base::KELIME,   Base::EVET,  null);
        $this->b80Aciklama=new Deger("b80Aciklama","B80 AÇIKLAMA",Base::KELIME, Base::EVET,  null);
        $this->b551Aciklama=new Deger("b551Aciklama","B55.1 AÇIKLAMA",Base::KELIME, Base::EVET,  null);
        $this->sonuc     = new Deger("sonuc"    ,"SONUÇ",       Base::KELIME,   Base::EVET,  null);
        $this->drive_id  = new Deger("drive_id" ,"DRIVE ID",    Base::KELIME,   Base::EVET,  null);
    }
}