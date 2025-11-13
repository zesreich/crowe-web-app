<?php 
include_once 'basePost.php';
include_once PREPATH.'soa/denetimSoa.php';
include_once PREPATH.'config/planRiskProsedurConfig.php';

try {
    if ($_GET['tur']== 'riskDriveIdGrup'){
        cevapDondur(json_encode(denetimSoa::riskDriveGetir($_POST['tklf_id'],$_POST['grup']),JSON_UNESCAPED_UNICODE));
    }else if($_GET['tur']== 'insert'){
        $tekRisk   = Crud::getById(new PlanRiskProsedur(),$_POST['id']);
        $tekRisk->aciklama->deger = $_POST['aciklama'];
        $result = Crud::update($tekRisk);
        if ($result!=1){
            hataDondur($result);
        }
        cevapDondur($tekRisk);
    }else if($_GET['tur']== 'getById'){
        $result   = Crud::getById(new PlanRiskProsedur(),$_POST['id'])->basit();
        cevapDondur($result);
    }else if($_GET['tur']== 'kaydet'){
        $prsdr = Crud::getById(new PlanRiskProsedur(),$_POST['id']);
        
        $prsdr->kanit_varmi->deger    = $_POST['kanit'];
        $prsdr->bulgu_tutar->deger    = $_POST['tutar'];
        $prsdr->sonuc_aciklama->deger = $_POST['aciklama'];
        
        if ($prsdr->kanit_varmi->deger == 'H' || $prsdr->bulgu_tutar->deger != ''){
            $prsdr->durum_id->deger       = planRiskProsedurConfig::DURUM_TAMAMLANDI[0];
        }else{
            $prsdr->durum_id->deger       = planRiskProsedurConfig::DURUM_DEVAM_EDIYOR[0];
        }
        
        $result = Crud::update($prsdr);
        if ($result==1){
            cevapDondur($prsdr->basit());
        }else{
            hataDondur($result);
        }
    }
    hataDondur('Yanlış bir post.');
} catch (Exception $e) {
    hataDondur($e);
}