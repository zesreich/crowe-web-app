<?php
Class TklfRaporlamaSekli extends Base{
    
    public $adi;
    public $aciklama;

    public function vt_Adi()    {return "RAPORLAMA ŞEKLİ";}
    public function vt_dbAdi()  {return "tklf_raporlama_sekli";}
    public function vt_Order()  {return " order by id";}
    
    function __construct(){
        parent::__construct();
        $this->adi      = new Deger("adi",      "ADI",      Base::KELIME, Base::HAYIR,null);
        $this->aciklama = new Deger("aciklama", "AÇIKLAMA", Base::KELIME, Base::EVET,null);
    }
}