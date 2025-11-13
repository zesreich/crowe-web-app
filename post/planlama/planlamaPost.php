<?php 
include_once '../basePost.php';
include_once PREPATH.'soa/planlama/planlamaSoa.php';
include_once PREPATH.'soa/pdfSoa.php';

try {
    if ($_GET['tur']== 'uygulamaGerekYok'){
        array("UPDATE ".Config::DB_DATABASE.".planlama SET uygula = :_uygula, :_gmt_usr WHERE tklf_id = :_tklf_id ",array('uygula'=>Base::KELIME,'gmt_usr'=>Base::SAYI,'tklf_id'=>Base::SAYI));
        $snc = Crud::deleteSqlTek(new Planlama(), Planlama::UPDATE_DUZENLENMEYECEK, array('uygula'=>$_POST['uygula'],'gmt_usr'=>(" gmt='".date("Y:m:d H:i:s")."', user_id = ".$_POST['usrId'].' '),'tklf_id'=>$_POST['tklf_id']));
        if ($snc==1){
            cevapDondur("İşlem tamamlandı.");
        }else{
            $result['hata'] = true;
            $result['ht_ack']=$snc;
            hataDondur($result);
        }
    }else if($_GET['tur'] == 'belgeler'){
        $list = planlamaSoa::getPlanlamaBelge($_POST['tklf_id'],$_POST['link']);
        cevapDondur(json_encode($list,JSON_UNESCAPED_UNICODE));
    }else if($_GET['tur'] == 'belgeIndir'){
        try {
            $sbln   = Crud::getSqlTek(new Sablonlar(), Sablonlar::GEY_BY_KEY, array('anahtar'=>$_GET['key']));
            if ($sbln->deger->deger == null){
                throw new Exception('Taslak yok.');
            }
            $drive = driveSoa::getir($sbln->deger->deger);
            header('Location:'.$drive->url);
            exit();
        } catch (Exception $e) {
            print_r($e->getMessage());
            return;
        }
    }else if($_GET['tur'] == 'belgeYukle'){
        $result = pdfSoa::planlamaBelgeYukle($_POST['tklfid'],$_POST['link'],$_FILES,$_POST['key']);
        if ($result != 1){
            mesajSet('hata', $result);
        }
        header("Location:". PREPATH.$_POST['link']);
        exit();
    }else if($_GET['tur'] == 'belgeDelete'){
        $tbl = Crud::getSqlTek(new Planlama(), Planlama::GET_TEKLIF_ID, array('tklf_id'=>$_POST['tklf_id']));
        if ($_POST['key'] == sablonConfig::PLANLAMA_DENETIM_SIRKET_TR){
            $tbl->sirket_tr->deger = null;
        }else if ($_POST['key'] == sablonConfig::PLANLAMA_DENETIM_SIRKET_ENG){
            $tbl->sirket_eng->deger = null;
        }else if ($_POST['key'] == sablonConfig::PLANLAMA_MUSTERI_TR){
            $tbl->musteri_tr->deger = null;
        }else if ($_POST['key'] == sablonConfig::PLANLAMA_MUSTERI_ENG){
            $tbl->musteri_eng->deger = null;
        }
        $result = Crud::update($tbl);
        if ($result!=1){
            hataDondur($result);
        }
        cevapDondur("");
    }else if($_GET['tur'] == 'riskDrive'){
        $rslt = planlamaSoa::getRiskDrive($_POST['id'],$_POST['link']);
        cevapDondur(json_encode($rslt,JSON_UNESCAPED_UNICODE));
    }else if($_GET['tur'] == 'riskBelegeYukle'){
        $result = pdfSoa::riskBelegeYukle($_POST['id'],$_POST['link'],$_FILES);
        if ($result != 1){
            mesajSet('hata', $result);
        }
        header("Location:". PREPATH.$_POST['link']);
        exit();
    }else if($_GET['tur'] == 'getPlan'){
        $result   = Crud::getSqlTek(new Planlama(), Planlama::GET_TEKLIF_ID, array('tklf_id'=>$_POST['tklf_id']))-> basit();
        cevapDondur(json_encode($result,JSON_UNESCAPED_UNICODE));
    }else if($_GET['tur'] == 'beyanDelete'){
        $tbl = Crud::getById(new RiskProsedur(),$_POST['id']);
        driveSoa::belgeDrivedenSil($tbl->drive_id->deger,$_POST['link']);
        $tbl->drive_id->deger = null;
        $result = Crud::update($tbl);
        if ($result!=1){
            hataDondur($result);
        }
        cevapDondur("");
    }
    
    hataDondur('Yanlış bir post.');
} catch (Exception $e) {
    hataDondur($e);
}