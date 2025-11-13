<?php
include_once 'Base.class.php';
//include_once 'YetkiProgram.php';
//include_once 'GrupPrm.php';

Class YetkiGrup extends Base{
    
    const PROGRAM_YETKILERI     = array("SELECT g.* FROM ".Config::DB_DATABASE.".yetki_grup g, ".Config::DB_DATABASE.".yetki_program p WHERE g.yetki_id = p.id AND p.program_id=:_prgId",array('prgId'=>Base::SAYI));
    const GRUP_PROGRAM_YETKILER = array("SELECT g.* FROM ".Config::DB_DATABASE.".yetki_grup g, ".Config::DB_DATABASE.".yetki_program p WHERE g.grup_id=:_grpId AND g.yetki_id = p.id AND p.program_id=:_prgId",array('grpId'=>Base::SAYI,'prgId'=>Base::SAYI));
    const YETKI_GRUP_TEK        = array("SELECT g.* FROM ".Config::DB_DATABASE.".yetki_grup g WHERE g.yetki_id =:_ytk AND g.grup_id=:_grp",array('ytk'=>Base::SAYI,'grp'=>Base::SAYI));
    const GRUP_YETKILER         = array("SELECT g.* FROM ".Config::DB_DATABASE.".yetki_grup g WHERE g.grup_id=:_grp",array('grp'=>Base::SAYI));
    
    public $yetki_id;
    public $grup_id;

    public function vt_Adi()    {return "GRUP YETKİLERİ";}
    public function vt_dbAdi()  {return "yetki_grup";}
    public function vt_Order()  {return "";}
    
    function __construct(){
        parent::__construct();
        $this->yetki_id = new Deger("yetki_id", "YETKI",    Base::SAYI,     Base::HAYIR,new Ref(new YetkiProgram(), Base::TEK,'id'));
        $this->grup_id  = new Deger("grup_id",  "PROGRAM",  Base::SAYI,     Base::HAYIR,new Ref(new GrupPrm(),      Base::TEK,'id'));
    }
}