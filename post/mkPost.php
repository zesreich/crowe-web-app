<?php 
include_once 'basePost.php';
include_once PREPATH . 'config/config.php';
include_once PREPATH . 'db/Crud.php';
include_once PREPATH . 'soa/genelSoa.php';
include_once PREPATH . 'soa/driveSoa.php';
include_once PREPATH . 'soa/mkSoa.php';

try {
    if (isset($_GET['tur'])){
        if($_GET['tur'] == 'mstrKabulList'){
            $sql = "select mk.id as mkid, mk.no as mkno,mus.unvan as munvan, ort.unvan as iunvan, dt.aciklama as dton, dt.id as dton_id, d.donem_bas_trh, d.donem_bts_trh,mk.durum FROM ";
            $sql = $sql. Config::DB_DATABASE.".musteri_kabul mk, ".Config::DB_DATABASE.".denetim d, ".Config::DB_DATABASE.".musteri mus, ".Config::DB_DATABASE.".is_ortagi ort, ".Config::DB_DATABASE.".tklf_denetim_nedeni_prm dt";
            $sql = $sql. " where d.id= mk.no and d.musteri_id = mus.id AND mus.isortagi_id = ort.id  AND dt.id = d.dton_id ";
            
            if ($_POST['ara_id'      ] != null ){$sql = $sql. "AND mk.no = ".$_POST['ara_id'      ]." ";}
            if ($_POST['ara_unvan'   ] != null ){$sql = $sql. "AND mus.unvan like '%".$_POST['ara_unvan'   ]."%' ";}
            if ($_POST['ara_isortagi'] != null ){$sql = $sql. "AND ort.unvan like '%".$_POST['ara_isortagi']."%' ";}
            if ($_POST['ara_dton'    ] != null ){$sql = $sql. "AND d.dton_id = ".$_POST['ara_dton']." ";}
            if ($_POST['ara_dnm_alt' ] != null ){$sql = $sql. "AND d.donem_bas_trh > '".$_POST['ara_dnm_alt']."' ";}
            if ($_POST['ara_dnm_ust' ] != null ){$sql = $sql. "AND d.donem_bas_trh < '".$_POST['ara_dnm_ust']."' ";}
            if ($_POST['ara_durum'   ] != null ){$sql = $sql. "AND mk.durum = ".$_POST['ara_durum'   ]." ";}
            
            $result = Crud::selectSql($sql);
            cevapDondur($result);
            
        }else if($_GET['tur'] == 'mk2_check'){
            $snc = Crud::deleteSqlTek(new MK2(), MK2::UPDATE_ALAN, array('aln'=>$_POST['dgr'],'alan_cvp'=>$_POST['chk'],'gmt_usr'=>(" gmt='".date("Y:m:d H:i:s")."', user_id = ".$_POST['usrId'].' '),'tklf_id'=>$_POST['tklf_id']));
            if ($snc==1){
                mkSoa::mkDurumGuncelleme($_POST['tklf_id']);
                cevapDondur("İşlem tamamlandı.");
            }else{
                $result['hata'] = true;
                $result['ht_ack']=$snc;
                hataDondur($result);
            }
        }else if($_GET['tur'] == 'not_list'){
            cevapDondur(json_encode(mkSoa::getNotList($_POST['tklf_id'],$_POST['grup'],$_POST['kod']),JSON_UNESCAPED_UNICODE));
        }else if($_GET['tur'] == 'not_ekle'){
            if ($_POST['id'] == null || $_POST['id'] == ''){
                $tbl = new MKNot();
                $tbl->tklf_id->deger    = $_POST['tklf_id'];
                $tbl->grup->deger       = $_POST['grup'];
                $tbl->kod->deger        = $_POST['kod'];
                $tbl->soru->deger       = $_POST['soru'];
                $tbl->cevap->deger      = $_POST['cevap'];
                $result = Crud::save($tbl);
                mkSoa::mkDurumGuncelleme($_POST['tklf_id']);
            }else{
                $tbl = Crud::getById(new MKNot(),$_POST['id']);
                $tbl->tklf_id->deger    = $_POST['tklf_id'];
                $tbl->soru->deger       = $_POST['soru'];
                $tbl->cevap->deger      = $_POST['cevap'];
                $result = Crud::update($tbl);
                mkSoa::mkDurumGuncelleme($_POST['tklf_id']);
            }
            if ($result==1){
                cevapDondur("Denetçi düzenlendi/eklendi.");
            }else{
                hataDondur($result);
            }
        }else if($_GET['tur'] == 'drive_post'){
            $client = driveSoa::baglan($_POST['link']);
            $drive  = driveSoa::clientGetir($client, $_POST['driveId']);
            for ($i = 0; $i < count($_FILES['dosya']['name']) ; $i++) {
                //if (isset($_FILES['dosya']) && is_uploaded_file($_FILES['dosya']['tmp_name'])){
                    $stream = fopen($_FILES['dosya']['tmp_name'][$i], "rb");
                    $drive->upload($_FILES['dosya']['name'][$i], $stream);
                //}
            }
            header("Location:". $_POST['link']);
            exit();
        }else if($_GET['tur'] == 'uyarilar'){
            cevapDondur(json_encode(mkSoa::mkKapakUyarilarTklfId($_POST['tklf_id']),JSON_UNESCAPED_UNICODE));
        }else if($_GET['tur'] == 'mkRefs'){
            cevapDondur(json_encode(mkSoa::mkRefsListesiGetir($_POST['tklf_id']),JSON_UNESCAPED_UNICODE));
        }else if($_GET['tur'] == 'mkRisk'){
            cevapDondur(json_encode(mkSoa::mkRiskListesiGetir($_POST['tklf_id']),JSON_UNESCAPED_UNICODE));
        }else if($_GET['tur'] == 'mkRiskGrup'){
            cevapDondur(json_encode(mkSoa::mkRiskGrupListesiGetir($_POST['tklf_id'],$_POST['grup']),JSON_UNESCAPED_UNICODE));
        }else if($_GET['tur'] == 'mkRefsGrup'){
            cevapDondur(json_encode(mkSoa::mkRefsGrupListesiGetir($_POST['tklf_id'],$_POST['grup']),JSON_UNESCAPED_UNICODE));
        }else if($_GET['tur'] == 'mkNotGrup'){
            cevapDondur(json_encode(mkSoa::notlarKontrolGrup($_POST['tklf_id'],$_POST['grup']),JSON_UNESCAPED_UNICODE));
        }else if($_GET['tur'] == 'mkDriveIdGrup'){
            cevapDondur(json_encode(mkSoa::prosedurGrupDriveId($_POST['tklf_id'],$_POST['grup']),JSON_UNESCAPED_UNICODE));
        }else if($_GET['tur'] == 'mkDriveIdGrupKod'){
            cevapDondur(json_encode(mkSoa::prosedurGrupKodDriveId($_POST['tklf_id'],$_POST['grup'],$_POST['kod']),JSON_UNESCAPED_UNICODE));
        }else if($_GET['tur'] == 'denetcilist'){
            cevapDondur(mkSoa::getDenetciIsimler($_POST['tklf_id']));
        }
    }else{
        hataDondur("Parametreler düzgün değil.");
    }
} catch (Exception $e) {
    hataDondur($e);
}




