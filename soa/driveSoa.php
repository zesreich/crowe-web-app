<?php 
include_once 'baseSoa.php';
include_once PREPATH . 'config/config.php';
require_once PREPATH . 'composer/vendor/autoload.php';
include_once PREPATH . '/config/config.php';
use Krizalys\Onedrive\Onedrive;

Class driveSoa extends BaseSoa{
    
    static function  baglan($url){
        driveSoa::baglanClientsiz($url);
        $client = Onedrive::client(
            Config::DRIVE_CLIENT_ID,
            [
                'state' => $_SESSION['onedrive.client.state'],
            ]
        );
        
        if ($client->getAccessTokenStatus() == -2 || $client->getAccessTokenStatus() == 0){
            $client->renewAccessToken(Config::DRIVE_CLIENT_SECRET);
        }
        return $client;
    }
    
    static function  baglanClientsiz($url){
        if (!isset($_SESSION['onedrive.client.state']) || $_SESSION['onedrive.client.state']->token == null) {
            $client = Onedrive::client(Config::DRIVE_CLIENT_ID);
            $urlx = $client->getLogInUrl([
                'files.read',
                'files.read.all',
                'files.readwrite',
                'files.readwrite.all',
                'offline_access',
            ], Config::DRIVE_REDIRECT_URI);
            $_SESSION['onedrive.client.state'] = $client->getState();
            $_SESSION['onedrive.backUrl'] = $url;
            header('HTTP/1.1 302 Found', true, 302);
            header('Location:'.$urlx);
            exit();
        }
    }
    
    static function prosedurDosyaOlustur($client,$tklfId,$grup,$kod){
        $mk = Crud::getSqlTek(new MusteriKabul(), MusteriKabul::GET_TEKLIF_ID, array('tklf_id'=>$tklfId));
        $tklfDriveId = $mk->drive_id->deger;
        if ($mk->drive_id->deger == null){
            $tklf = Crud::getSqlTek(new Denetim(), Denetim::GET_ID, array('id'=>$tklfId));
            $tklfDriveId =  driveSoa::klasorOlustur($client,$tklf->main_drive_id->deger, 'Müşteri Kabul');
            $mk->drive_id->deger = addslashes($tklfDriveId);
            $result = Crud::update($mk);
            if ($result!=1){
                throw new Exception($result);
            }
            
        }
        $driveId =  driveSoa::klasorOlustur($client,$tklfDriveId, $grup.'.'.$kod);
        mkSoa::prosedurDriveIdSetle($tklfId,$grup,$kod,$driveId);
        return $driveId;
    }
    
    static function riskOlustur($client,$tklfId){
        $mk = Crud::getById(new Denetim(),$tklfId);
        $tklfDriveId = $mk->risk_drive_id->deger;
        if ($mk->risk_drive_id->deger == null){
            $tklfDriveId =  driveSoa::klasorOlustur($client,$mk->main_drive_id->deger, 'Risk');
            $mk->risk_drive_id->deger = addslashes($tklfDriveId);
            $result = Crud::update($mk);
            if ($result!=1){
                throw new Exception($result);
            }
        }
        return $tklfDriveId;
    }
    
    static function veriOdasiOlustur($client,$tklfId){
        $mk = Crud::getById(new Denetim(),$tklfId);
        $veriodaDriveId = $mk->veriodasi_drive_id->deger;
        if ($mk->veriodasi_drive_id->deger == null){
            $tklfDriveId =  driveSoa::klasorOlustur($client,$mk->main_drive_id->deger, 'Veri Odası');
            $mk->veriodasi_drive_id->deger = addslashes($tklfDriveId);
            $veriodaDriveId = $mk->veriodasi_drive_id->deger;
            $result = Crud::update($mk);
            if ($result!=1){
                throw new Exception($result);
            }
        }
        return $veriodaDriveId;
    }
    
    static function riskDosyaOlustur($client,$id,$tklfId,$grup,$kod){
        $tklfDriveId = driveSoa::riskOlustur($client,$tklfId);
        $driveId =  driveSoa::klasorOlustur($client,$tklfDriveId, $grup.'.'.$kod);
        
        $psdr = Crud::getById(new PlanRiskProsedur(),$id);
        $psdr->drive_id->deger = addslashes($driveId);
        $resultx = Crud::update($psdr);
        if ($resultx!=1){
            throw new Exception($resultx);
        }
        
        return $driveId;
    }
    
    static function teklifDosyaOlustur($client,$tklfId){
        $tklf = Crud::getById(new Denetim(),$tklfId);
        if ($tklf->main_drive_id->deger == null){
            $mainDriveId =  driveSoa::klasorOlustur($client,Config::DRIVE_ROOT_ID, $tklfId);
            print_r($mainDriveId);
            $tklf->main_drive_id->deger = addslashes($mainDriveId);
        }
        $driveId =  driveSoa::klasorOlustur($client,$mainDriveId, 'Teklif');
        $tklf->drive_id->deger = addslashes($driveId);
        $result = Crud::update($tklf);
        if ($result!=1){
            throw new Exception($result);
        }
        return $driveId;
    }
    
    static function denetciDriveDosyasiIdGetir($client,$tklfId){
        $mk = Crud::getSqlTek(new MusteriKabul(), MusteriKabul::GET_TEKLIF_ID, array('tklf_id'=>$tklfId));
        $tklfDriveId = null ;
        if (isset($mk) && $mk->denetci_drive_id->deger == null){
            $tklf = Crud::getSqlTek(new Denetim(), Denetim::GET_ID, array('id'=>$tklfId));
            $tklfDriveId =  driveSoa::klasorOlustur($client,$tklf->main_drive_id->deger, 'Denetçi Belge');
            $mk->denetci_drive_id->deger = addslashes($tklfDriveId);
            $result = Crud::update($mk);
            if ($result!=1){
                throw new Exception($result);
            }
        }else{
            $tklfDriveId = $mk->denetci_drive_id->deger ;
        }
        return $tklfDriveId;
    }
    
    static function planlamaDriveDosyasiIdGetir($client,$tklfId,$key){
        $plnm   = Crud::getSqlTek(new Planlama(), Planlama::GET_TEKLIF_ID, array('tklf_id'=>$tklfId));
        $tklfDriveId = null ;
        
        if (isset($plnm) && $plnm->$key->deger == null){
            $tklf = Crud::getSqlTek(new Denetim(), Denetim::GET_ID, array('id'=>$tklfId));
            $tklfDriveId =  driveSoa::klasorOlustur($client,$tklf->main_drive_id->deger, 'Planlama');
            $plnm->$key->deger = addslashes($tklfDriveId);
            $result = Crud::update($plnm);
            if ($result!=1){
                throw new Exception($result);
            }
        }else{
            $tklfDriveId = $plnm->$key->deger ;
        }
        return $tklfDriveId;
    }
    
    static function denetciDriveIdKaydet($client,$denetciId,$drive_Id){
        $tbl = Crud::getById(new MKDenetci(),$denetciId);
        $tbl->drive_id->deger = "'".addslashes($drive_Id)."'";
        $result = Crud::update($tbl);
        if ($result!=1){
            throw new Exception($result);
        }
        return $result;
    }
    
    static function belgeDrivedenSil($driveId,$url,$client = null){
        try {
            if ($driveId != null){
                if ($client == null){
                    $client = driveSoa::baglan($url);
                }
                $result = driveSoa::clientGetir($client, $driveId)->delete();
            }
        } catch (Exception $e) {
            throw new Exception($result);
        }
    }
    
    static function dosyaYukle($driveId,$ad,$link){
        $cc = driveSoa::getir($driveId);
        $stream = fopen($link, "rb");
        $result = $cc->upload($ad, $stream);
        return $result->id;
    }
    
    static function dosyaYukleBasla($driveId,$ad,$link){
        $cc = driveSoa::getir($driveId);
        $stream = fopen($link, "rb");
        $uploadSession = $cc->startUpload($ad,$stream);
        $result = $uploadSession->complete();
        return $result->id;
    }
    
    static function dosyaListesiWithLink($driveId,$link){
        $client = driveSoa::baglan($link);
        return driveSoa::clientGetir($client, $driveId)->children;
    }
    
    static function dosyaListesi($client,$driveId){
        return driveSoa::clientGetir($client, $driveId)->children;
    }
    
    static function klasorOlusturClsiz($driveId,$adi){
        $client = driveSoa::baglan(null);
        $snc = driveSoa::klasorOlustur($client, $driveId, $adi);
        return  $snc;
    }
    
    static function klasorOlustur($client,$driveId,$adi){
        $snc = driveSoa::clientGetir($client, $driveId)->createFolder($adi);
        return  $snc->id;
    }
    
    static function clientGetir($client,$driveId){
        return $client->getDriveItemById(Config::DRIVE_DRIVE_ID,$driveId);
    }
    
    static function getir($driveId,$link=null){
        $client = driveSoa::baglan($link);
        return  driveSoa::clientGetir($client, $driveId);
//        $drive  = driveSoa::clientGetir($client, $driveId);
        //echo 'aşama1';
//        return $client->getDriveItemById($driveId);
//        return $drive->getDriveItemById($driveId);
    }
    
}

//driveSoa::klasorOlustur('680BFF350FA4AE0E!2867', 'mkYeni');
