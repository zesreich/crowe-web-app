<?php 
include_once 'basePost.php';
include_once '../db/Crud.php';
include_once PREPATH . 'soa/silSoa.php';
try {
    if ($_GET['tur']== 'Denetim'){
        silSoa::teklifSil($_POST['id'],$_POST['link']);
        cevapDondur('silindi');
    }else if ($_GET['tur']== 'Kullanici' && $_GET['ktur']==KullaniciTurPrm::ISORTAGI){
        silSoa::isOrtagiKullaniciSil($_POST['id']);
        cevapDondur('silindi');
    }else if ($_GET['tur']== 'Kullanici' && $_GET['ktur']==KullaniciTurPrm::MUSTERI){
        silSoa::musteriKullaniciSil($_POST['id']);
        cevapDondur('silindi');
    }else if ($_GET['tur']== 'Kullanici' && $_GET['ktur']==KullaniciTurPrm::DENETCI){
        silSoa::denetciKullaniciSil($_POST['id']);
        cevapDondur('silindi');
    }else if ($_GET['tur']== 'IsOrtagi'){
        silSoa::isOrtagiSil($_POST['id']);
        cevapDondur('silindi');
    }else if ($_GET['tur']== 'Musteri'){
        silSoa::musteriSil($_POST['id']);
        cevapDondur('silindi');
    }
} catch (Exception $e) {
    hataDondur($e);
}

