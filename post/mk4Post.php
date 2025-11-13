<?php 
include_once 'basePost.php';
include_once PREPATH.'db/Crud.php';
include_once PREPATH.'soa/genelSoa.php';
include_once PREPATH.'soa/pdfSoa.php';
include_once PREPATH.'soa/mkSoa.php';
include_once PREPATH.'soa/driveSoa.php';

try {
    if ($_GET['tur']== 'denetci'){
       if($_GET['islem']== 'sorumluliste'){
           $dntList = mkSoa::getDenetciListResmi($_POST['tklf_id'],$_POST['link']);
           cevapDondur(json_encode($dntList,JSON_UNESCAPED_UNICODE));
        }
    }else if($_GET['tur'] == 'beyan'){
        $list = mkSoa::getBeyanListResmi($_POST['tklf_id'],$_POST['link']);
        cevapDondur(json_encode($list,JSON_UNESCAPED_UNICODE));
    }else if($_GET['tur'] == 'delete'){
        $tbl = Crud::getSqlTek(new MKDenetci(), MKDenetci::GET_TEKLIF_DENETCI_ID, array('tklf_id'=>$_POST['tklf_id'],'denetci_id'=>$_POST['denetciId']));
        driveSoa::belgeDrivedenSil($tbl->drive_id->deger,$_POST['link']);
        $tbl->drive_id->deger = null;
        $result = Crud::update($tbl);
        if ($result!=1){
            hataDondur($result);
        }
        mkSoa::mkDurumGuncelleme($_POST['tklf_id']);
        cevapDondur("");
    }else if($_GET['tur'] == 'beyanDelete'){
        $tbl = Crud::getSqlTek(new MusteriKabul(), MusteriKabul::GET_TEKLIF_ID, array('tklf_id'=>$_POST['tklf_id']));
        if (sablonConfig::MK_BEYAN_BAGIMSIZ == $_POST['key']){
            driveSoa::belgeDrivedenSil($tbl->belge1->deger,$_POST['link']);
            $tbl->belge1->deger = null;
        }else{
            driveSoa::belgeDrivedenSil($tbl->belge2->deger,$_POST['link']);
            $tbl->belge2->deger = null;
        }
        $result = Crud::update($tbl);
        if ($result!=1){
            hataDondur($result);
        }
        $a = mkSoa::mkDurumGuncelleme($_POST['tklf_id']);
        print_r($a);
        cevapDondur("");
    }else if($_GET['tur'] == 'sozlesmeIndir'){
        pdfSoa::mkDenetciSozlesmeIndir($_GET['tklfId'],$_GET['denetciId']);
    }else if($_GET['tur'] == 'beyanIndir'){
        pdfSoa::beyanIndir($_GET['tklfId'],$_GET['key']);
    }else if($_GET['tur'] == 'beyanBelegeYukle'){
        $result = pdfSoa::mkBeyanBelgeYukle($_POST['tklfid'],$_POST['link'],$_FILES,$_POST['key']);
        if ($result != 1){
            mesajSet('hata', $result);
        }
        mkSoa::mkDurumGuncelleme($_POST['tklfid']);
        mesajSet('onay', 'Kaydetme işlemi tamamlandı.');
        header("Location:". PREPATH.$_POST['link'].'&grup=MK4&kod=1');
        exit();
    }else if($_GET['tur'] == 'denetciBelegeYukle'){
        $result = pdfSoa::mkDenetciBelgeYukle($_POST['tklfid'],$_POST['denetciId'],$_POST['link'],$_FILES);
        if ($result != 1){
            mesajSet('hata', $result);
        }
        mkSoa::mkDurumGuncelleme($_POST['tklfid']);
        mesajSet('onay', 'Kaydetme işlemi tamamlandı.');
        header("Location:". PREPATH.$_POST['link'].'&grup=MK4&kod=1');
        exit();
    }
    hataDondur('Yanlış bir post.');
} catch (Exception $e) {
    hataDondur($e);
}