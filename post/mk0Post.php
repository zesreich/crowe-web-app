<?php 
include_once 'basePost.php';
include_once '../db/Crud.php';
include_once '../soa/genelSoa.php';
include_once PREPATH.'soa/mkSoa.php';

try {
    if ($_GET['tur']== 'denetci'){
        $tklf_id=$_POST['tklf_id'];
        $dntList = mkSoa::getDenetciList($tklf_id);
        if ($_GET['islem']== 'update' || $_GET['islem']== 'create'){
            if ($_POST['id'] == 0){
                $dntcTbl = new MKDenetci();
                $dntcTbl->tklf_id->deger    = $_POST['tklf_id'];
                $dntcTbl->denetci_id->deger = $_POST['denetci_id'];
                $dntcTbl->ekip->deger       = $_POST['ekip'];
                $dntcTbl->gorev->deger      = isset($_POST['gorev']) ? $_POST['gorev'] : '' ;
                $dntcTbl->pozisyon->deger   = isset($_POST['pozisyon']) ? $_POST['pozisyon'] : '';
                $dntcTbl->uygun->deger      = $_POST['uygun'];
                $dntcTbl->saat->deger       = !isset($_POST['saat']) || $_POST['saat']==''  ? 0 :  $_POST['saat'];
                $dntcTbl->saat_ucret->deger = !isset($_POST['saat_ucret']) || $_POST['saat_ucret'] == '' ? 0 : $_POST['saat_ucret'];
                $dntcTbl->ucret->deger      = $dntcTbl->saat->deger * $dntcTbl->saat_ucret->deger;
                if ($dntList == null){
                    $dntList = array();
                }
                array_push($dntList, $dntcTbl->basit());
            }else{
                $dntcTbl = Crud::getById(new MKDenetci(),$_POST['id']);
                $dntcTbl->ekip->deger       = $_POST['ekip'];
                $dntcTbl->gorev->deger      = isset($_POST['gorev']) ? $_POST['gorev'] : '' ;
                $dntcTbl->pozisyon->deger   = isset($_POST['pozisyon']) ? $_POST['pozisyon'] : '';
                $dntcTbl->saat->deger       = isset($_POST['saat']) ? $_POST['saat'] : 0 ;
                $dntcTbl->saat_ucret->deger = isset($_POST['saat_ucret']) ? $_POST['saat_ucret'] : 0 ;
                $dntcTbl->ucret->deger      = $dntcTbl->saat->deger * $dntcTbl->saat_ucret->deger;
                for ($i = 0; $i<count($dntList) ; $i++) {
                    if ($dntList[$i]['id']==$_POST['id']) {
                        $dntList[$i] = $dntcTbl->basit();
                        break;
                    }
                }
            }
            
            if ($dntList!= null){
                $iki = false;
                foreach ($dntList as $dnt){
                    $dId = null;
                    if (is_numeric($dnt['denetci_id'])){
                        $dId = $dnt['denetci_id'];
                    }else{
                        $dId = $dnt['denetci_id']['id'];
                    }
                    if(($dId == 89 || $dId == 90 ) && $dnt['ekip'] == mkConfig::EKIP_ASIL_EKIP){
                        if ($iki){
                            hataDondur("'Özkan Cengiz' ve 'Hakan Günaydın' bir denetimde asıl ekipte aynı anda görev alamaz.");
                        }else{
                            $iki = true;
                        }
                    }
                    if ($dnt['saat'] == '' && $dnt['ekip'] == mkConfig::EKIP_ASIL_EKIP){
                        hataDondur("Asıl denetçi için Saat alanı boş olamaz.");
                    }
                }
            }
            
            if ($_POST['id'] == 0){
                $result = Crud::save($dntcTbl);
            }else{
                $result = Crud::update($dntcTbl);
            }
            if ($result==1){
                mkSoa::mkDurumGuncelleme($dntcTbl->tklf_id->deger);
                cevapDondur("Denetçi düzenlendi/eklendi.");
            }else{
                hataDondur($result);
            }
        }else if($_GET['islem']== 'liste'){
            cevapDondur(json_encode($dntList,JSON_UNESCAPED_UNICODE));
        }else if($_GET['islem']== 'ekipListe'){
            $dnc = array();
            foreach ($dntList as $gln){
                if ($gln['ekip'] == $_POST['ekip']){
                    array_push($dnc, $gln);
                }
            }
            cevapDondur(json_encode($dnc,JSON_UNESCAPED_UNICODE));
        }else if($_GET['islem']== 'sil'){
            $tbl = Crud::getById(new MKDenetci(),$_POST['id']);
            driveSoa::belgeDrivedenSil($tbl->drive_id->deger,$_POST['link']);
            $result = Crud::delete(new MKDenetci(), $_POST['id']);
            if ($result==1){
                mkSoa::mkDurumGuncelleme($tklf_id);
                cevapDondur("Denetçi silindi.");
            }else{
                hataDondur($result);
            }
        }
    }else if($_GET['tur']== 'kimlikBilgiler'){
        if($_GET['islem']== 'kaydet'){
            $tbl = Crud::getById(new MK0(),$_POST['id']);
            $tbl->denetim_maddesi->deger= addslashes($_POST['kd']);
            $tbl->halka_acik->deger     = addslashes($_POST['halk']);
            $tbl->kayik->deger          = addslashes($_POST['kayik']);
            $tbl->kayik_ack->deger      = addslashes($_POST['kayik_ack']);
            $result = Crud::update($tbl);
            
            if ($result!=1){
                hataDondur($result);
            }
            
            $a = mkSoa::mkDurumGuncelleme($tbl->tklf_id->deger);
            
            //print_r($a);
            
            cevapDondur("Kişi bilgileri düzenlendi.");
        }
    }
    hataDondur('Yanlış bir post.');
} catch (Exception $e) {
    hataDondur($e->getMessage());
}