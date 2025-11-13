<?php 
include_once 'basePost.php';
include_once '../db/Crud.php';
include_once PREPATH.'soa/genelSoa.php';
try {
    if ($_GET['tur']== 'tum'){
        $result = genelSoa::sablolarGetir();
        cevapDondur($result);
    }else if ($_GET['tur']== 'grup'){
        $result = genelSoa::sablolarGetirByGrup($_GET['grup']);
        cevapDondur($result);
    }
} catch (Exception $e) {
    hataDondur($e);
}