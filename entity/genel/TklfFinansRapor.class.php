<?php
Class TklfFinansRapor extends Base{
    
    public $adi;
    public $aciklama;

    public function vt_Adi()    {return "FİNANS RAPORLAMA ÇERÇEVESİ";}
    public function vt_dbAdi()  {return "tklf_finans_rapor_prm";}
    public function vt_Order()  {return " order by id";}
    
    function __construct(){
        parent::__construct();
        $this->adi      = new Deger("adi",      "ADI",      Base::KELIME, Base::HAYIR,null);
        $this->aciklama = new Deger("aciklama", "AÇIKLAMA", Base::KELIME, Base::HAYIR,null);
    }
}