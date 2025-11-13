<?php 
include_once '../basePost.php';
try {
    if ($_GET['tur']== 'b70Ekle'){
        $tklf_id=$_POST['tklf_id'];
        if ($_POST['id'] == 0){
            $b70 = new PlanB70();
        }else{
            $b70 = Crud::getById(new PlanB70(),$_POST['id']);
        }
        $b70->tklf_id->deger    = $_POST['tklf_id'];
        $b70->tur->deger        = $_POST['tur'];
        $b70->oran->deger       = $_POST['oran'];
        $b70->tutar->deger      = $_POST['tutar'];
        $b70->performans->deger = $_POST['performans'];
        if ($_POST['id'] == 0){
            $result = Crud::save($b70);
        }else{
            $result = Crud::update($b70);
        }
        if ($result==1){
            cevapDondur("Eklendi.");
        }else{
            hataDondur($result);
        }
    }else if($_GET['tur']== 'b70sEkle'){
        $b70 = Crud::getById(new Planlama(),$_POST['id']);
        $b70->god->deger    = $_POST['god'];
        $b70->pod->deger    = $_POST['pod'];
        $b70->dfe->deger    = $_POST['dfe'];
        $b70->nots->deger    = $_POST['nots'];
        $b70->degisik->deger= $_POST['degisik'];
        $result = Crud::update($b70);
        if ($result==1){
            cevapDondur("Düzenlendi.");
        }else{
            hataDondur($result);
        }
    }else if($_GET['tur']== 'b70Getir'){
        $list = Base::basitList(Crud::getSqlCok(new PlanB70(), PlanB70::GET_TKLIF, array('tklf_id'=>$_POST['tklf_id'])));
        cevapDondur(json_encode($list,JSON_UNESCAPED_UNICODE));
    }
    hataDondur('Yanlış bir post.');
} catch (Exception $e) {
    hataDondur($e);
}