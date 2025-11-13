<?php
include_once 'Base.class.php';
include_once 'Program.php';

Class YetkiProgram extends Base{
    
    const FIND_PROGRAM_ID   = array("SELECT g.* FROM ".Config::DB_DATABASE.".yetki_program g WHERE g.program_id =:_programId",array('programId'=>Base::SAYI));
    
    public $yetki_adi;
    public $program_id;

    public function vt_Adi()    {return "PROGRAM YETKİLERİ";}
    public function vt_dbAdi()  {return "yetki_program";}
    public function vt_Order()  {return "";}
    
    function __construct(){
        parent::__construct();
        $this->yetki_adi    = new Deger("yetki_adi",    "YETKI",    Base::KELIME,   Base::HAYIR,null);
        $this->program_id   = new Deger("program_id",   "PROGRAM",  Base::SAYI,     Base::HAYIR,new Ref(new Program(), Base::TEK,'id'));
    }
}