<?php 
include_once 'basePost.php';
include_once PREPATH.'db/Crud.php';
//include_once PREPATH.'soa/genelSoa.php';
include_once PREPATH.'soa/takvimSoa.php';

try {
    if ($_GET['tur']== 'kayitDuzenle'){
        if ($_POST['aciklama'] == null ||
            $_POST['denetci_id'] == null ||
            $_POST['ilk'] == null ||
            $_POST['son'] == null ||
            $_POST['allDay'] == null ||
            $_POST['konu'] == null){
            hataDondur('Eksik parametreler var!');
        }
        $result = takvimSoa::kayitDuzenle(($_POST['id'] == '' ? null : $_POST['id']), $_POST['denetci_id'], $_POST['aciklama'], $_POST['konu'], $_POST['ilk'], $_POST['son'], ($_POST['allDay'] == 'true' ? 'E' : 'H'));
        if ($result!=1){
            hataDondur($result);
        }
        cevapDondur("İşlem tamamlandı.");
    }else if ($_GET['tur']== 'takvimGetir'){
        if ($_GET['denetci_id'] == null ){
            hataDondur('Denetçi Id eksik!');
        }
        $result = takvimSoa::takvimGetir($_GET['denetci_id']);
        echo $result;
        exit();
    }else if ($_GET['tur']== 'sil'){
        if ($_POST['id'] == null ){
            hataDondur('Id eksik!');
        }
        $result = takvimSoa::sil($_POST['id']);
        if ($result != 1){
            hataDondur($result);
        }
        cevapDondur("Silme işlemi tamamlandı.");
    }else if ($_GET['tur']== 'tek'){
        if ($_GET['id'] == null ){
            hataDondur('Id eksik!');
        }
        $result = Crud::getById(new Takvim(),$_GET['id'])->basit();
        cevapDondur($result);
    }
    hataDondur('Yanlış bir post.');
} catch (Exception $e) {
    hataDondur($e);
}