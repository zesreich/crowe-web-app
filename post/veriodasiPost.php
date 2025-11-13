<?php 
include_once 'basePost.php';
include_once '../db/Crud.php';
include_once PREPATH.'soa/genelSoa.php';
include_once PREPATH.'soa/veriodasiSoa.php';
include_once PREPATH.'soa/driveSoa.php';

try {
    if ($_GET['tur']== 'veriodasiProsedurEkle'){
        $result = veriodasiSoa::veriOdasiKaydet($_POST);
        if ($result['result']==1){
            cevapDondur("Eklendi.");
        }else{
            hataDondur($result['result']);
        }
    }else if ($_GET['tur']== 'veriodasiGetir'){
        cevapDondur(veriodasiSoa::veriodasiGetir($_POST['tklf_id'], $_POST['grup'], $_POST['kod']));
    }else if ($_GET['tur']== 'veriodasiBelgeGetir'){
        $drives = veriodasiSoa::veriodasiBelgeGetir($_POST['tklf_id'], $_POST['grup'], $_POST['kod'],$_POST['link']);
        cevapDondur($drives);
    }else if ($_GET['tur']== 'belgeYukle'){
        $result = veriodasiSoa::veriodasiProsedurBelgeYukle($_POST, $_FILES);
        if ($result == 1){
            cevapDondur($result);
        }else{
            hataDondur($result);
        }
    }else if ($_GET['tur']== 'belgeSil'){
        $result = veriodasiSoa::veriOdasiBelgeSil($_POST);
        if ($result == 1){
            cevapDondur("Belge Silindi.");
        }else{
            hataDondur($result);
        }
    }
    hataDondur('Yanlış bir post.');
} catch (Exception $e) {
    hataDondur($e);
}