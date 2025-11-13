<?php 
include_once 'basePost.php';
include_once PREPATH.'soa/mkSoa.php';

try {
    if ($_GET['tur']== 'allRisk'){
        $list = Crud::getSqlCok(new RiskListesi(), RiskListesi::GET_BY_AKTIF, array());
        $list = json_encode(Base::basitList($list),JSON_UNESCAPED_UNICODE) ;
        cevapDondur($list);
    }else if ($_GET['tur']== 'prosedurList'){
        $list = Crud::getSqlCok(new RiskProsedur(), RiskProsedur::GET_BY_RISK, array('risk'=>$_GET['riskId']));
        $list = json_encode(Base::basitList($list),JSON_UNESCAPED_UNICODE) ;
        cevapDondur($list);
    }else if ($_GET['tur']== 'belgeGetir'){
        $list = Crud::getSqlCok(new RiskBelge(), RiskBelge::GET_BY_PROSEDUR, array('prsdr'=>$_GET['prosedur_id']));
        $list = json_encode(Base::basitList($list),JSON_UNESCAPED_UNICODE) ;
        cevapDondur($list);
    }else if ($_GET['tur']== 'belge'){
        if ($_POST['id'] == 0){
            $tbl = new RiskBelge();
        }else{
            $tbl = Crud::getById(new RiskBelge(),$_POST['id']);
        }
        $tbl->adi->deger    = $_POST['ad'];
        $tbl->sira->deger   = $_POST['sira'];
        $tbl->aktif->deger  = $_POST['aktf'] == null ? 'E' : $_POST['aktf'];
        $tbl->prosedur_id->deger= $_POST['prsdr'];
        if ($_POST['id'] == 0){
            $result = Crud::save($tbl);
        }else{
            $result = Crud::update($tbl);
        }
        if ($result==1){
            cevapDondur("Belge Ekled.");
        }else{
            hataDondur($result);
        }
    }
    hataDondur('Yanlış bir post.');
} catch (Exception $e) {
    hataDondur($e);
}