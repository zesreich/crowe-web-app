<?php 
include_once 'basePost.php';
include_once PREPATH . 'db/Crud.php';
include_once PREPATH . 'config/sozlesmeConfig.php';
include_once PREPATH . 'soa/pdfSoa.php';
include_once PREPATH . 'soa/sozlesmeSoa.php';
include_once PREPATH . 'config/config.php';

try {
    if (isset($_GET['tur'])){
        if($_GET['tur'] == 'sozlesmeList'){
            
            $sql = "".
            " SELECT                           ".
            "     mk.id as mkid,               ".
            "     mk.tklf_id as mkno,          ".
            "     mus.unvan as munvan,         ".
            "     ort.unvan as iunvan,         ".
            "     d.donem_bas_trh,             ".
            "     d.donem_bts_trh,             ".
            "     dt.aciklama as dton,         ".
            "     dt.id as dton_id,            ".
            "     mk.durum                     ".
            " FROM                             ".
            "     ".Config::DB_DATABASE.".sozlesme mk,   ".
            "     ".Config::DB_DATABASE.".denetim d,     ".
            "     ".Config::DB_DATABASE.".musteri mus,   ".
            "     ".Config::DB_DATABASE.".tklf_denetim_nedeni_prm dt,".
            "     ".Config::DB_DATABASE.".is_ortagi ort  ".
            " where 1=1                        ".
            "     AND d.id= mk.tklf_id         ".
            "     AND d.musteri_id = mus.id    ".
            "     AND dt.id = d.dton_id         ".
            "     AND mus.isortagi_id = ort.id "; 
            
            if ($_POST['ara_id'      ] != null ){$sql = $sql. "AND mk.tklf_id = ".$_POST['ara_id']." ";}
            if ($_POST['ara_unvan'   ] != null ){$sql = $sql. "AND mus.unvan like '%".$_POST['ara_unvan'   ]."%' ";}
            if ($_POST['ara_isortagi'] != null ){$sql = $sql. "AND ort.unvan like '%".$_POST['ara_isortagi']."%' ";}
            //if ($_POST['ara_dnm_alt' ] != null ){$sql = $sql. "AND d.donem_bas_trh > '".$_POST['ara_dnm_alt']."' ";}
            //if ($_POST['ara_dnm_ust' ] != null ){$sql = $sql. "AND d.donem_bas_trh = '".$_POST['ara_dnm_ust']."' ";}
            if ($_POST['ara_dnm_ust' ] != null ){$sql = $sql. "AND d.donem_bts_trh = '".$_POST['ara_dnm_ust']."' ";}
            if ($_POST['ara_durum'   ] != null ){$sql = $sql. "AND mk.durum = ".$_POST['ara_durum']." ";}
            
            $result = Crud::selectSql($sql);
            cevapDondur($result);
        }else if($_GET['tur'] == 'tekSozlesme'){
            $sql = "".
                "SELECT                         ".
                " 	  mk.id as id,              ".
                "     d.id as tklfid,           ".
                "     mus.id as munvanid,       ".
                "     mus.unvan as munvan,      ".
                "     d.donem_bas_trh,          ".
                "     d.donem_bts_trh,          ".
                "     mk.durum,                 ".
                "     d.frc_id,                 ".
                "     mk.genel_kurul_trh,       ".
                "     mk.musteri_imza_trh,      ".
                "     mk.denetim_imza_trh,      ".
                "     mk.imzasiz_drive_id,      ".
                "     mk.imzali_drive_id ,      ".
                "     mk.teslim_tarihi          ".
                " FROM                          ".
                "     ".Config::DB_DATABASE.".sozlesme mk,".
                "     ".Config::DB_DATABASE.".denetim d,  ".
                "     ".Config::DB_DATABASE.".musteri mus ".
                " where 1=1                     ".
                "     AND d.id= mk.tklf_id      ".
                "     AND d.musteri_id = mus.id ".
                "     AND d.id = ".$_POST['tklfid'];
            $result = Crud::selectSqlTek($sql);
            
            cevapDondur($result);
        }else if($_GET['tur'] == 'sozlesmeDznl'){

            $tbl = Crud::getSqlTek(new Sozlesme(), Sozlesme::GET_TEKLIF_ID, array('tklf_id'=>$_POST['id']));
            
            if ($_POST['genel_tarihi']  !=null){
                $tbl->genel_kurul_trh->deger = $_POST['genel_tarihi'];
            }
            if ($_POST['musteri_tarihi']!=null){
                $tbl->musteri_imza_trh->deger = $_POST['musteri_tarihi'];
            }
            if ($_POST['denetim_tarihi']!=null){
                $tbl->denetim_imza_trh->deger = $_POST['denetim_tarihi'];
            }
            if ($_POST['teslim_tarihi']!=null){
                $tbl->teslim_tarihi->deger = $_POST['teslim_tarihi'];
            }
            
            if ($tbl->durum->deger == sozlesmeConfig::DURUM_OLUSMADI[0]){
                $tbl->durum->deger = sozlesmeConfig::DURUM_IMZAYA_GONDER[0];
            }
            $result = Crud::update($tbl);
            if ($result==1){
                cevapDondur("Kaydedildi");
            }else{
                hataDondur($result);
            }
        }else if($_GET['tur'] == 'imzasizSozlesmeYukle'){
            try {
                $result = pdfSoa::imzasizSozlesmeYukle($_POST['id']);
                cevapDondur("Tamamlandı");
            } catch (Exception $e) {
                hataDondur($e->getMessage());
            }
        }else if($_GET['tur'] == 'sozlesmeEmailGonder'){
            $result = sozlesmeSoa::sozlesmeEmailGonder($_POST['id']);
            if ($result == true){
                cevapDondur("Email Gönderdi.");
            }else{
                hataDondur($result);
            }
        }else if($_GET['tur'] == 'sozlesmeElleGonder'){
            $szlsm  = Crud::getSqlTek(new Sozlesme(), Sozlesme::GET_TEKLIF_ID, array('tklf_id'=>$_POST['id']));
            $szlsm->durum->deger = sozlesmeConfig::DURUM_IMZAYI_BEKLE[0];
            $result = Crud::update($szlsm);
            if ($result != 1){
                hataDondur($result);
            }else{
                cevapDondur("Durum güncellendi.");
            }
        }else if($_GET['tur'] == 'imzaliSozlesmeYukle'){
            $result = pdfSoa::imzaliSozlesmeYukle($_POST['tklfid'],$_POST['link'],$_FILES);
            if ($result != 1){
                hataDondur($result);
            }
        }
        header("Location:". PREPATH.$_POST['link']);
        exit();
    }else{
        hataDondur("Parametreler düzgün değil.");
    }
} catch (Exception $e) {
    hataDondur($e);
}


