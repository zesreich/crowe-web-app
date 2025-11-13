<?php 
include_once 'basePost.php';
include_once '../db/Crud.php';
try {
    if ($_GET['tur']== 'getById'){
        $result = (Crud::getById(new $_GET['tablo'](),$_GET['id']))->entToJson();
        if (isset($_GET['mesaj'])){
            mesajSet('onay', 'Kaydetme işlemi tamamlandı.');
        }
        cevapDondur($result);
    }else if ($_GET['tur']== 'create'){
        $tbl = new $_GET['tablo']();
        foreach ($_POST as $key => $value){
            $tbl->$key->deger=$value;
        }
        $result = Crud::save($tbl);
        if ($result==1){
            if (isset($_GET['mesaj'])){
                mesajSet('onay', 'Kaydetme işlemi tamamlandı.');
            }
            cevapDondur("Tamam");
        }else{
            hataDondur($result);
        }
    }else if ($_GET['tur']== 'update'){
        $tbl = Crud::getById(new $_GET['tablo'](),$_POST['id']);
        foreach ($_POST as $key => $value){
            $tbl->$key->deger=$value;
        }
        $result = Crud::update($tbl);
        if ($result==1){
            if (isset($_GET['mesaj'])){
                mesajSet('onay', 'Düzenleme işlemi tamamlandı.');
            }
            cevapDondur("Tamam");
        }else{
            hataDondur($result);
        }
    }else if ($_GET['tur']== 'ilce'){
        $lst = Base::basitList(Crud::getSqlCok(new Ilce(), Ilce::GET_BY_IL_ID, array('il_id'=>$_GET['il_id'])));
        cevapDondur($lst);
    }
} catch (Exception $e) {
    hataDondur($e);
}