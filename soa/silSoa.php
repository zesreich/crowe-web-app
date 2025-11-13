<?php 
include_once 'baseSoa.php';
include_once PREPATH.'db/Crud.php';
include_once PREPATH.'soa/driveSoa.php';

Class silSoa extends BaseSoa{
    
    
    public static function denetciKullaniciSil($id){
        $result = Crud::deleteSqlTek(new Kullanici(), Kullanici::DELETE_ID_TUR, array('id'=>$id,'tur'=>KullaniciTurPrm::DENETCI));
        if ($result==1){
            return true;
        }else{
            return false;
        }
    }
    
    public static function musteriKullaniciSil($id){
        $result = Crud::deleteSqlTek(new Kullanici(), Kullanici::DELETE_ID_TUR, array('id'=>$id,'tur'=>KullaniciTurPrm::MUSTERI));
        if ($result==1){
            $res = Crud::deleteSqlTek(new KullaniciMusteri(), KullaniciMusteri::DELETE_KUL_ID, array('kullanici_id'=>$id));
            if ($res==1){
                return true;
            }else{
                return false;
            }
        }else{
            return false;
        }
    }
    
    public static function musteriSil($id){
        $list  = Crud::getSqlCok(new Kullanici(), Kullanici::KULLANICI_MUSTERI, array('musteri'=>$id));
        if ($list != null){
            foreach ($list as $bir){
                if (!silSoa::musteriKullaniciSil($bir->id->deger)){
                    return false;
                }
            }
        }
        $result = Crud::delete(new Musteri(), $id);
        if ($result!=1){
            return true;
        }else{
            return false;
        }
    }
    
    public static function isOrtagiKullaniciSil($id){
        $result = Crud::deleteSqlTek(new Kullanici(), Kullanici::DELETE_ID_TUR, array('id'=>$id,'tur'=>KullaniciTurPrm::ISORTAGI));
        if ($result==1){
            $res = Crud::deleteSqlTek(new KullaniciIsortagi(), KullaniciIsortagi::DELETE_KUL_ID, array('kullanici_id'=>$id));
            if ($res==1){
                return true;
            }else{
                return false;
            }
        }else{
            return false;
        }
    }
    
    public static function isOrtagiSil($id){
        $list  = Crud::getSqlCok(new Kullanici(), Kullanici::KULLANICI_ISORTAG, array('isortagi'=>$id));
        if ($list != null){
            foreach ($list as $bir){
                if (!silSoa::isOrtagiKullaniciSil($bir->id->deger)){
                    return false;                
                }
            }
        }
        $result = Crud::delete(new IsOrtagi(), $id);
        if ($result!=1){
            return true;
        }else{
            return false;
        }
    }
    
    public static function teklifSil($tklf_id,$link){
        
        $tklf   = Crud::getById(new Denetim(),$tklf_id) -> basit();
        if ($tklf['main_drive_id'] != null){
            driveSoa::belgeDrivedenSil($tklf['main_drive_id'], $link);
        }
        
        Crud::deleteSqlTek(new Denetim()        , Denetim::DELETE_TEKLIF_ID         , array('id'        =>$tklf_id));
        Crud::deleteSqlTek(new DenetimOnay()    , DenetimOnay::DELETE_TEKLIF_ID     , array('tklf_id'   =>$tklf_id));
        
        Crud::deleteSqlTek(new Sozlesme()       , Sozlesme::DELETE_TEKLIF_ID        , array('tklf_id'   =>$tklf_id));
        
        Crud::deleteSqlTek(new MusteriKabul()   , MusteriKabul::DELETE_TEKLIF_ID    , array('tklf_id'   =>$tklf_id));
        Crud::deleteSqlTek(new MK0()            , MK0::DELETE_TEKLIF_ID             , array('tklf_id'   =>$tklf_id));
        Crud::deleteSqlTek(new MK2()            , MK2::DELETE_TEKLIF_ID             , array('tklf_id'   =>$tklf_id));
        Crud::deleteSqlTek(new MKDenetci()      , MKDenetci::DELETE_TEKLIF_ID       , array('tklf_id'   =>$tklf_id));
        Crud::deleteSqlTek(new MKNot()          , MKNot::DELETE_TEKLIF_ID           , array('tklf_id'   =>$tklf_id));
        Crud::deleteSqlTek(new MKRefs()         , MKRefs::DELETE_TEKLIF_ID          , array('tklf_id'   =>$tklf_id));
        Crud::deleteSqlTek(new MKRisk()         , MKRisk::DELETE_TEKLIF_ID          , array('tklf_id'   =>$tklf_id));
        Crud::deleteSqlTek(new Prosedur()       , Prosedur::DELETE_TEKLIF_ID        , array('tklf_id'   =>$tklf_id));
        Crud::deleteSqlTek(new IsOrtakPayDeger(), IsOrtakPayDeger::DELETE_TEKLIF_ID , array('tklf_id'   =>$tklf_id));
        
        Crud::deleteSqlTek(new PlanRiskProsedur(),PlanRiskProsedur::DELETE_TEKLIF_ID, array('tklf_id'   =>$tklf_id));
        Crud::deleteSqlTek(new Planlama()       , Planlama::DELETE_TEKLIF_ID        , array('tklf_id'   =>$tklf_id));
        Crud::deleteSqlTek(new PlanB55Kisi()    , PlanB55Kisi::DELETE_TEKLIF_ID     , array('tklf_id'   =>$tklf_id));
        Crud::deleteSqlTek(new VeriodasiProsedur(),VeriodasiProsedur::DELETE_TEKLIF_ID,array('tklf_id'  =>$tklf_id));
    }
    
}

// silSoa::teklifSil(20209,'asd');
