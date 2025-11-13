<?php 
include_once '../basePost.php';
//include_once PREPATH.'soa/planlama/planlamaSoa.php';

try {
    if ($_GET['tur']== 'b55KisiEkle'){
        $tklf_id=$_POST['tklf_id'];
        if ($_POST['id'] == 0){
            $b55Kisi = new PlanB55Kisi();
        }else{
            $b55Kisi = Crud::getById(new PlanB55Kisi(),$_POST['id']);
        }
        
        $b55Kisi->tklf_id->deger    = $_POST['tklf_id'];
        $b55Kisi->kod->deger        = $_POST['kod'];
        $b55Kisi->adSoyad->deger    = $_POST['adSoyad'];
        $b55Kisi->gorev->deger      = $_POST['gorev'];
        
        if ($_POST['id'] == 0){
            $result = Crud::save($b55Kisi);
        }else{
            $result = Crud::update($b55Kisi);
        }
        if ($result==1){
            cevapDondur("Eklendi.");
        }else{
            hataDondur($result);
        }
    }else if($_GET['tur']== 'b55KisiGetir'){
        $list = Base::basitList(Crud::getSqlCok(new PlanB55Kisi(), PlanB55Kisi::GET_TKLIF_AND_KOD, array('tklf_id'=>$_POST['tklf_id'],'kod'=>$_POST['kod'])));
        cevapDondur(json_encode($list,JSON_UNESCAPED_UNICODE));
    }else if($_GET['tur']== 'b55KisiSil'){
        $result = Crud::delete(new PlanB55Kisi(), $_POST['id']);
        if ($result==1){
            cevapDondur("Silindi.");
        }else{
            hataDondur($result);
        }
    }
    hataDondur('Yanlış bir post.');
} catch (Exception $e) {
    hataDondur($e);
}