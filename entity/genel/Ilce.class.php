<?php
Class Ilce extends Base{
    
    const GET_BY_IL_ID = array("SELECT * FROM ilce WHERE il_id = :_il_id order by adi",array('il_id'=>Base::SAYI));
    
    public $adi;
    public $il_id;

    public function vt_Adi()    {return "İLÇELER";}
    public function vt_dbAdi()  {return "ilce";}
    public function vt_Order()  {return " order by adi";}
    
    function __construct(){
        parent::__construct();
        $this->adi      = new Deger("adi"   , "ADI"  , Base::KELIME   , Base::HAYIR,null);
        $this->il_id    = new Deger("il_id" , "IL ID", Base::SAYI     , Base::HAYIR,null);
    }
}