<?php 
include_once 'basePost.php';
include_once PREPATH.'soa/denetimSoa.php';

try {
    if($_GET['tur'] == 'riskler'){
        $list = denetimSoa::denetimCiddiRiskleriGetir($_GET['tklf_id']);
        cevapDondur(json_encode($list,JSON_UNESCAPED_UNICODE));
    }else if($_GET['tur'] == 'planRiskKaydet'){
        $prsdr = Crud::getById(new PlanRiskProsedur(),$_POST['id']);
        $prsdr->talep_edildi->deger     = $_POST['talep'];
        $prsdr->tablo_duzelt->deger     = $_POST['tablo'];
        $prsdr->denetim_bulgu->deger    = $_POST['denetim'];
        $prsdr->muhtemel_etki->deger    = $_POST['muhtemel'];
        $result = Crud::update($prsdr);
        if ($result==1){
            cevapDondur("Düzenlendi.");
        }else{
            hataDondur($result);
        }
    }else if($_GET['tur'] == 'liste'){
        $sql = "SELECT "
            . "     d.id, "
            . "     mus.unvan as munvan, "
            . "     ort.unvan as iunvan, "
            . "     dt.id as dton_id, "
            . "     d.donem_bas_trh, "
            . "     d.donem_bts_trh "
            . " FROM "
            . "     " . Config::DB_DATABASE . ".denetim d, "
            . "     " . Config::DB_DATABASE . ".musteri mus, "
            . "     " . Config::DB_DATABASE . ".is_ortagi ort, "
            . "     " . Config::DB_DATABASE . ".tklf_denetim_nedeni_prm dt "
            . " where 1=1 "
            . "	    AND d.musteri_id = mus.id "
            . "	    AND mus.isortagi_id = ort.id "
            . "	    AND dt.id = d.dton_id ";
        
        if ($_POST['ara_id'      ] != null ){$sql = $sql. "AND d.id = ".$_POST['ara_id']." ";}
        if ($_POST['ara_unvan'   ] != null ){$sql = $sql. "AND mus.unvan like '%".$_POST['ara_unvan'   ]."%' ";}
        if ($_POST['ara_isortagi'] != null ){$sql = $sql. "AND ort.unvan like '%".$_POST['ara_isortagi']."%' ";}
        if ($_POST['ara_dton'    ] != null ){$sql = $sql. "AND d.dton_id = ".$_POST['ara_dton']." ";}
        if ($_POST['ara_dnm_alt' ] != null ){$sql = $sql. "AND d.donem_bas_trh > '".$_POST['ara_dnm_alt']."' ";}
        if ($_POST['ara_dnm_ust' ] != null ){$sql = $sql. "AND d.donem_bas_trh < '".$_POST['ara_dnm_ust']."' ";}
        
        $result = Crud::selectSql($sql);
        cevapDondur($result);
    }
    hataDondur('Yanlış bir post.');
} catch (Exception $e) {
    hataDondur($e);
}