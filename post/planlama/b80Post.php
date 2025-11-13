<?php 
include_once '../basePost.php';
include_once PREPATH.'soa/planlama/planlamaSoa.php';
include_once PREPATH.'soa/driveSoa.php';
include_once PREPATH.'config/planRiskProsedurConfig.php';

try {
    if ($_GET['tur']== 'riskleriGetir'){
        $result = mkSoa::riskGetir($_POST['tklf_id']);
        cevapDondur($result);
    }else if ($_GET['tur']== 'prosedurGetir'){
        $result = planlamaSoa::prosedurGetir($_GET['tklf_id']);
        cevapDondur($result);
    }else if ($_GET['tur']== 'prosedurEkle'){
        $tbl = Crud::getById(new RiskProsedur(),$_POST['risk_prosedur_id'])->basit();
        if ($tbl['drive_id'] == null){
            hataDondur('Excel ekli değil.');
        }
        $drive = driveSoa::getir($tbl['drive_id']);
        $name = $tbl['risk_id']['kod'].'_'.$tbl['kod'].substr($drive->name,strrpos($drive->name,'.'));
        if(!file_put_contents( PREPATH.config::GECICI_KLASOR.$name,file_get_contents($drive->url))) {
            throw new Exception('Dosya indirilemedi.');
        }
        
        $client  = driveSoa::baglan($_POST['link']);
        $driveId = driveSoa::riskOlustur($client,$_POST['tklf_id']);
        $id = driveSoa::dosyaYukle($driveId, $name, PREPATH.config::GECICI_KLASOR.$name);
        unlink(PREPATH.config::GECICI_KLASOR.$name);
        
        $tbl = new PlanRiskProsedur();
        $tbl->tklf_id->deger            = $_POST['tklf_id'];
        $tbl->risk_prosedur_id->deger   = $_POST['risk_prosedur_id'];
        $tbl->excel_drive_id->deger     = $id;
        $tbl->durum_id->deger           = planRiskProsedurConfig::DURUM_DEVAM_EDIYOR[0];
        $result = Crud::save($tbl);
        if ($result!=1){
            hataDondur($result);
        }
        cevapDondur($result);
    }else if ($_GET['tur']== 'prosedurSil'){
        $tbl = Crud::getById(new PlanRiskProsedur(),$_POST['id']);
        
        driveSoa::belgeDrivedenSil($tbl->drive_id->deger, $_POST['link']);
        driveSoa::belgeDrivedenSil($tbl->excel_drive_id->deger, $_POST['link']);
        
        $lst = Base::basitList(Crud::getSqlCok(new VeriodasiProsedur(), VeriodasiProsedur::GET_BY_TKLF_KOD, array('tklf'=>$tbl->tklf_id->deger,'kod'=>$tbl->risk_prosedur_id->deger)));
        $rst = array();
        if ($lst != null){
            foreach ($lst as $l){
                driveSoa::belgeDrivedenSil($l['klasor_drive_id'], $_POST['link']);
                $del1 = Crud::deleteSqlTek(new VeriodasiProsedur(),VeriodasiProsedur::DELETE_ID, array('id' => $l['id']));
                if ($del1!=1){
                    hataDondur($del1);
                }
            }
        }

        $result = Crud::delete(new PlanRiskProsedur(), $_POST['id']);
        if ($result!=1){
            hataDondur($result);
        }
        cevapDondur("Silindi.");
    }else if ($_GET['tur']== 'prosedurGuncelle'){
        $tbl = Crud::getById(new PlanRiskProsedur(),$_POST['id']);
        $tbl->kaynak->deger             = $_POST['kaynak'];
        $tbl->duzey->deger              = $_POST['duzey'];
        $result = Crud::update($tbl);
        if ($result!=1){
            hataDondur($result);
        }
        cevapDondur($result);
    }else if ($_GET['tur']== 'proAciklamaKaydet'){
        $tbl = Crud::getById(new prosedur(),$_POST['id']);
        $tbl->b80Aciklama->deger = $_POST['acik'];
        $result = Crud::update($tbl);
        if ($result!=1){
            hataDondur($result);
        }
        cevapDondur($result);
    }else if ($_GET['tur']== 'tespitKaydet'){
        $tbl = Crud::getById(new prosedur(),$_POST['id']);
        $tbl->b551Aciklama->deger = $_POST['aciklama'];
        $result = Crud::update($tbl);
        if ($result!=1){
            hataDondur($result);
        }
        cevapDondur($result);
    }else if ($_GET['tur']== 'b80Init'){
        $result = planlamaSoa::tumCiddiRiskGetir($_GET['tklf_id']);
        cevapDondur($result);
    }
    hataDondur('Yanlış bir post.');
} catch (Exception $e) {
    hataDondur($e);
}