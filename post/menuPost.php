<?php 
include_once 'basePost.php';
include_once '../entity/Program.php';

try {
    if ($_GET['tur']== 'listeSiraKaydet'){
//         if (!yetkiSoa::yetkiVarmi(yetkiConfig::MENU_DUZENLE_SIRA_KAYDET)){
//             hataDondur('Menü kayıt ve düzenleme yetkiniz bulunmamaktadır.',$prePath);
//         }
        $bg = new Db();
        $ses = $bg->getCon();
        $ynList = json_decode($_POST['data'], true);
        $dbList = Crud::all(new Program(),false,$ses);
        
        foreach ($dbList as $db){
            $var = false;
            foreach ($ynList as $yn){
                if ($yn['id'] == $db->id->deger){
                    $db->sira->deger = $yn['sira'];
                    $db->ust_id->deger = $yn['ustId'];
                    $var = true;
//                     if ($yn['id'] == 202){
//                         echo 'içerder';
//                         print_r($db);
//                     }
                    break;
                }
            }
            if ($var){
                Crud::update($db,$ses);
            }else{
                foreach (yetkiSoa::programYetkiler($db->id->deger) as $prmYetki){
//                     Crud::delete(new YetkiProgram(), $prmYetki, $ses);
                }
                foreach (yetkiSoa::programGrupYetkiler($db->id->deger) as $prmGrp){
//                     Crud::delete(new YetkiProgram(), $prmGrp, $ses);
                }
//                 Crud::delete($db, $db->id->deger,$ses);
            }
        }
        $ses->commit();
        mesajSet("onay", "Sıralama kaydedildi.");
        cevapDondur("");
    }else if ($_GET['tur']== 'tekMenuKaydet'){
//         if (!yetkiSoa::yetkiVarmi(yetkiConfig::MENU_DUZENLE_KAYDET_DUZENLE)){
//             hataDondur('Menü kayıt ve düzenleme yetkiniz bulunmamaktadır.',$prePath);
//         }
        if (!is_numeric($_POST['id'])){
            $bg = new Db();
            $ses = $bg->getCon();
            try{
                $tbl = new Program();
                $tbl->program_adi->deger    = $_POST['program_adi'];
                $tbl->program_link->deger   = $_POST['program_link'];
                $tbl->icon->deger           = $_POST['icon'];
                $tbl->klasor->deger         = $_POST['klasor'];
                $tbl->gorunsunmu->deger     = $_POST['gorunsunmu'];
                $tbl->ust_id->deger         = $_POST['ust_id'];
                $tbl->sira->deger           = $_POST['sira'];
                $tbl->yetki->deger          = ($_POST['yetki'] == null || $_POST['yetki'] == '' ? -1 : $_POST['yetki']);
                $result = Crud::save($tbl,$ses);
                if ($result!=1){
                    hataDondur($result);
                }
                
                $mtbl = new YetkiProgram();
                $mtbl->yetki_adi->deger = 'Giriş';
                $mtbl->program_id->deger= $ses->insert_id;
                $mresult = Crud::save($mtbl,$ses);
                if ($mresult!=1){
                    hataDondur($mresult);
                }
                
                $ses->commit();
                if (isset($_GET['mesaj'])){
                    mesajSet('onay', 'Kaydetme işlemi tamamlandı.');
                }
                cevapDondur("Tamam");
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
        }else{
            $tbl = Crud::getById(new Program(), $_POST['id']) ;
            $tbl->program_adi->deger    = $_POST['program_adi'];
            $tbl->program_link->deger   = $_POST['program_link'];
            $tbl->icon->deger           = $_POST['icon'];
            $tbl->klasor->deger         = $_POST['klasor'];
            $tbl->gorunsunmu->deger     = $_POST['gorunsunmu'];
            $tbl->yetki->deger          = ($_POST['yetki'] == null || $_POST['yetki'] == '' ? -1 : $_POST['yetki']);
            $result = Crud::update($tbl);
        }
        if ($result != 1){
            hataDondur($result);
        }else{
            mesajSet("onay", "Yeni program tanımlandı.");
            cevapDondur("");
        }
    }
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
    

