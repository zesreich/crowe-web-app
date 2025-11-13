<?php 
include_once 'basePost.php';
include_once '../db/Crud.php';
include_once '../soa/genelSoa.php';
include_once PREPATH.'soa/mkSoa.php';

try {
    if($_GET['tur']== 'form'){
        if($_GET['islem']== 'update'){
            
            $tbl = Crud::getById(new Prosedur(),$_POST['id']);
            $tbl->kapsami->deger    = addslashes($_POST['kapsami']);
            $tbl->zamani->deger     = addslashes($_POST['zamani']);
            $tbl->aciklama->deger   = addslashes($_POST['aciklama']);
            $tbl->sonuc->deger      = addslashes($_POST['sonuc']);
            
            $result = Crud::update($tbl);
            if ($result==1){
                mkSoa::mkDurumGuncelleme($tbl->tklf_id->deger);
                cevapDondur("Kişi bilgileri düzenlendi.");
            }else{
                hataDondur($result);
            }
        }
    }else if($_GET['tur']== 'risk'){
        if($_GET['islem']== 'insert'){
            $bg = new Db();
            $ses = $bg->getCon();
            try {
                $tekRisk   = Crud::getById(new RiskListesi(),$_POST['risk_id']) -> basit();
                $tbl = new MKRisk();
                $tbl->tklf_id->deger    = $_POST['tklf_id'];
                $tbl->grup->deger       = $_POST['grup'];
                $tbl->kod->deger        = $_POST['kod'];
                $tbl->risk_id->deger    = $_POST['risk_id'];                
                $result = Crud::save($tbl,$ses);
                if ($result!=1){
                    hataDondur($result);
                }
                $id = $ses->insert_id;
                $ses->commit();
                if (isset($_GET['mesaj'])){
                    mesajSet('onay', 'Kaydetme işlemi tamamlandı.');
                }
                $tekRisk['prId'] = $id;
                mkSoa::mkDurumGuncelleme($tbl->tklf_id->deger);
                cevapDondur($tekRisk);
            } catch (Exception $e) {
                if (isset($ses)){
                    $ses->rollback();
                }
                hataDondur($e);
            } finally {
                if (isset($ses)){
                    mysqli_close($ses);
                }
            }
        }else if($_GET['islem']== 'delete'){
            $result = Crud::delete(new MKRisk(), $_POST['prId']);
            if ($result==1){
                cevapDondur("Risk silindi.");
            }else{
                hataDondur($result);
            }
        }
    }else if($_GET['tur']== 'refs'){
        if($_GET['islem']== 'insert'){
            $bg = new Db();
            $ses = $bg->getCon();
            try {
                $tbl = new MKRefs();
                $tbl->tklf_id->deger    = $_POST['tklf_id'];
                $tbl->grup->deger       = $_POST['grup'];
                $tbl->kod->deger        = $_POST['kod'];
                $tbl->refs_id->deger    = $_POST['refs_id'];
                $result = Crud::save($tbl,$ses);
                if ($result!=1){
                    hataDondur($result);
                }
                $tbl->id->deger = $ses->insert_id;
                $ses->commit();
                $tbl = $tbl->basit();
                if (isset($_GET['mesaj'])){
                    mesajSet('onay', 'Kaydetme işlemi tamamlandı.');
                }
                mkSoa::mkDurumGuncelleme($_POST['tklf_id']);
                cevapDondur($tbl);
            } catch (Exception $e) {
                if (isset($ses)){
                    $ses->rollback();
                }
                hataDondur($e);
            } finally {
                if (isset($ses)){
                    mysqli_close($ses);
                }
            }
        }else if($_GET['islem']== 'delete'){
            $result = Crud::delete(new MKRefs(), $_POST['prId']);
            if ($result==1){
                cevapDondur("Referans silindi.");
            }else{
                hataDondur($result);
            }
        }
    }
    hataDondur('Yanlış bir post.');
} catch (Exception $e) {
    hataDondur($e);
}