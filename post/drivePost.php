<?php 
include_once 'basePost.php';
include_once PREPATH.'db/Crud.php';
include_once PREPATH.'soa/genelSoa.php';
include_once PREPATH.'soa/driveSoa.php';
include_once PREPATH.'config/sablonConfig.php';
try {
    if (isset($_GET['tur'])){
        $client = driveSoa::baglan($_POST['link']);
        $drive  = driveSoa::clientGetir($client, (isset($_POST['driveId']) ? $_POST['driveId'] : Config::DRIVE_ROOT_ID));
        if($_GET['tur'] == 'drive_post'){
            for ($i = 0; $i < count($_FILES['dosya']['name']) ; $i++) {
                $stream = fopen($_FILES['dosya']['tmp_name'][$i], "rb");
                $uploadSession =$drive->startUpload($_FILES['dosya']['name'][$i], $stream);
                $uploadSession->complete();
            }
        }else if($_GET['tur'] == 'sablon_yukle'){
            $uzanti = 'pdf';
            foreach (sablonConfig::PLANLAMALAR as $key => $val) {
                if ($key == $_POST['sablon_key']){
                    $uzanti = 'doc';
                    break;
                }
            }
            if (
                strrpos($_FILES['dosya']['name'],'.') != false && (
                    ($uzanti == 'doc' && (
                        substr($_FILES['dosya']['name'],strrpos($_FILES['dosya']['name'],'.')+1) == 'doc'  ||
                        substr($_FILES['dosya']['name'],strrpos($_FILES['dosya']['name'],'.')+1) == 'docx' ||
                        substr($_FILES['dosya']['name'],strrpos($_FILES['dosya']['name'],'.')+1) == 'DOC'  ||
                        substr($_FILES['dosya']['name'],strrpos($_FILES['dosya']['name'],'.')+1) == 'DOCX' 
                    )) ||
                    ($uzanti == 'pdf' && (
                        substr($_FILES['dosya']['name'],strrpos($_FILES['dosya']['name'],'.')+1) == 'pdf'  ||
                        substr($_FILES['dosya']['name'],strrpos($_FILES['dosya']['name'],'.')+1) == 'PDF' 
                    )) 
                )       
            ){
                $stream = fopen($_FILES['dosya']['tmp_name'], "rb");
                $uploadSession = $drive->startUpload($_POST['sablon_key'].substr($_FILES['dosya']['name'],strrpos($_FILES['dosya']['name'],'.')),$stream);
                $driveItem = $uploadSession->complete();
                $sb = Crud::getSqlTek(new Sablonlar(), Sablonlar::GEY_BY_KEY, array('anahtar'=>$_POST['sablon_key']));
                $sb->deger->deger = addslashes($driveItem->id);
                $result = Crud::update($sb);
                if ($result!=1){
                    throw new Exception($result);
                }
            }else{
                if ($uzanti == 'doc'){
                    mesajSet('hata', 'World dosya yüklemelisiniz.');
                }else{
                    mesajSet('hata', 'pdf uzantılı bir dosya yüklemelisiniz.');
                }
            }
            /*
            if(
                strrpos($_FILES['dosya']['name'],'.') == false ||
                (substr($_FILES['dosya']['name'],strrpos($_FILES['dosya']['name'],'.')+1) != 'pdf' &&
                substr($_FILES['dosya']['name'],strrpos($_FILES['dosya']['name'],'.')+1) != 'PDF')
            ){
                mesajSet('hata', 'pdf uzantılı bir dosya yüklemelisiniz.');
            }else{
                $stream = fopen($_FILES['dosya']['tmp_name'], "rb");
                $uploadSession = $drive->startUpload($_POST['sablon_key'].substr($_FILES['dosya']['name'],strrpos($_FILES['dosya']['name'],'.')),$stream);
                $driveItem = $uploadSession->complete();
                $sb = Crud::getSqlTek(new Sablonlar(), Sablonlar::GEY_BY_KEY, array('anahtar'=>$_POST['sablon_key']));
                $sb->deger->deger = addslashes($driveItem->id);
                $result = Crud::update($sb);
                if ($result!=1){
                    throw new Exception($result);
                }
            }*/
        }else if($_GET['tur'] == 'delete'){
            try{
                $drive->delete();
            } catch (Exception $e) {
                hataDondur($e);
            }
            cevapDondur("");
        }else if($_GET['tur'] == 'deleteSozlesmeBelge'){
            $drive->delete();
            $sb = Crud::getSqlTek(new Sablonlar(), Sablonlar::GEY_BY_KEY, array('anahtar'=>$_POST['sablon_key']));
            $sb->deger->deger = '';
            $result = Crud::update($sb);
            if ($result!=1){
                hataDondur($result);
            }
            cevapDondur("");
        }else if($_GET['tur'] == 'denetci_belge_upload'){
            $drive  = driveSoa::clientGetir($client, driveSoa::denetciDriveDosyasiIdGetir($client, $_POST['tklf_id']));
            $data = $_POST['data'];
            $image = file_get_contents($data);
            $stream = fopen($data, "rb");
            
            $dosyaAdi = $_POST['name'];
            $noktaKonum = strrpos($dosyaAdi, '.');
            $result = $drive->upload($_POST['adSoyad'].substr($dosyaAdi,$noktaKonum), $stream);
            driveSoa::denetciDriveIdKaydet($client, $_POST['id'], $result->id);
            cevapDondur("");
        }else if($_GET['tur'] == 'sil'){
            $result = $drive->delete();
            cevapDondur($result);
        }else if($_GET['tur'] == 'belge_getir'){
            $result['url']  = $drive->url;
            $result['web']  = $drive->webUrl;
            $result['id']   = $drive->id;
            cevapDondur($result);
        }else if($_GET['tur'] == 'belge_isim_List'){
            $list = $drive->children;
            $arr = array();
            if ($list != null ){
                foreach ($list as $one){
                    array_push($arr, $one->name);
                }
            }
            cevapDondur($arr);
        }
        header("Location:". PREPATH.$_POST['link']);
        exit();
    }else{
        hataDondur("Parametreler düzgün değil.");
    }
} catch (Exception $e) {
    echo '<pre>';
    print_r($e);
    echo '</pre>';
    hataDondur($e);
}