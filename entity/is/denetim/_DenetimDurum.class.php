<?php
Class DenetimDurum extends Base{
    
    const GRUP_TEKLIF         = 100;
    
    const DURUM_TASLAK        = 101;
    const DURUM_DUZENLE       = 102;
    const DURUM_DUZENLENDI    = 106;
    const DURUM_ONAY_YONETICI = 103;
    const DURUM_ONAY_MUSTERI  = 104;
    const DURUM_ONAYLI        = 105;

    public $adi     ;
    public $grup_id ;
    public $grup_adi;
    
    public function vt_Adi()    {return "DENETİM DURUM";}
    public function vt_dbAdi()  {return "denetim_durum";}
    public function vt_Order()  {return "";}
    
    function __construct(){
        parent::__construct();
        $this->adi      = new Deger("adi"      ,"ADI",         Base::KELIME,   Base::HAYIR, null);
        $this->grup_id  = new Deger("grup_id"  ,"GRUP ID",     Base::SAYI,     Base::HAYIR, null);
        $this->grup_adi = new Deger("grup_adi" ,"GRUP ADI",    Base::KELIME,   Base::HAYIR, null);
    }
    
}