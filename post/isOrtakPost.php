<?php 
include_once 'basePost.php';
include_once PREPATH.'db/Crud.php';
include_once PREPATH.'soa/isOrtakSoa.php';

try {
    if($_GET['tur'] == 'ortakPaylariGetir'){
        $snc = isOrtakSoa::ortakPaylariGetir($_GET['id']);
        cevapDondur(json_encode($snc,JSON_UNESCAPED_UNICODE));
    }else if($_GET['tur'] == 'pay'){
        if ($_GET['islem']== 'update' || $_GET['islem']== 'create'){
            if ($_POST['id'] == 0){
                $tbl = new IsOrtakPay();
            }else{
                $tbl = Crud::getById(new IsOrtakPay(),$_POST['id']);
            }
            $tbl->ortak_id->deger       = $_POST['ortak_id'];
            $tbl->pay_ortak_id->deger   = $_POST['pay_ortak_id'];
            $tbl->pay->deger            = $_POST['pay'];
            
            if ($_POST['id'] == 0){
                $result = Crud::save($tbl);
            }else{
                $result = Crud::update($tbl);
            }
            if ($result==1){
                cevapDondur("Pay düzenlendi/eklendi.");
            }else{
                hataDondur($result);
            }
        }else if($_GET['islem']== 'sil'){
            $tbl = Crud::getById(new IsOrtakPay(),$_POST['id']);
            $result = Crud::delete(new IsOrtakPay(), $_POST['id']);
            if ($result==1){
                cevapDondur("Pay silindi.");
            }else{
                hataDondur($result);
            }
        }
    }else if($_GET['tur'] == 'ortakPayDegerGetir'){
        if (isset($_GET['ortak_id'])){
            isOrtakSoa::payDegerIsortakGetir($_GET['id'],$_GET['ortak_id']);
        }
        $snc = isOrtakSoa::payDegerGetir($_GET['id']);
        cevapDondur(json_encode($snc,JSON_UNESCAPED_UNICODE));
    }else if($_GET['tur'] == 'digerIsGetir'){
        $snc = isOrtakSoa::digerPayDegerGetir($_GET['id']);
        cevapDondur(json_encode($snc,JSON_UNESCAPED_UNICODE));
    }else if($_GET['tur'] == 'payDeger'){
        if ($_GET['islem']== 'update'){
            $result = isOrtakSoa::tekPayDegerKaydet($_POST);
            if ($result == true){
                cevapDondur("tamamlandı");
            }else{
                hataDondur($result);
            }
        }
    }
    hataDondur('Yanlış bir post.');
} catch (Exception $e) {
    hataDondur($e);
}