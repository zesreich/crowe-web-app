<?php
include_once __DIR__ . '/../path.php';
include_once PREPATH . 'soa/baseSoa.php';
include_once PREPATH . 'soa/mkSoa.php';
include_once PREPATH . 'config/config.php';
include_once PREPATH . 'db/Crud.php';

class denetimSoa extends BaseSoa
{
    public static function denetimCiddiRiskleriGetir($tklfId){
        return denetimSoa::denetimCiddiRiskleriDosyaLink($tklfId, false);
    }
    
    public static function denetimCiddiRiskleriGetirWithDosyaLink($tklfId){
        return denetimSoa::denetimCiddiRiskleriDosyaLink($tklfId, true);
    }
    
    private static function denetimCiddiRiskleriDosyaLink($tklfId,$belge){
        $sql ="select "
        . "	    prp.id as id, "
        . "     prp.aciklama, "
        . "     prp.drive_id, "
        . "     prp.durum_id , "
        . "     prp.excel_drive_id, "
        . "     prp.kaynak, "
        . "     prp.duzey, "
        . "     prp.kanit_varmi as kanit, "
        . "     prp.bulgu_tutar as tutar, "
        . "     prp.talep_edildi, "
        . "     prp.tablo_duzelt, "
        . "     prp.denetim_bulgu, "
        . "     prp.muhtemel_etki, "
        . "	    rl.kod as rlKod, "
        . "     rl.adi as rlAdi, "
        . "	    rp.kod as rpKod, "
        . "     rp.adi as rpAdi, "
        . "     rp.id as rpid, "
        . "	    rl.id as rlid "
        . " from "
        . "	    " . Config::DB_DATABASE . ".plan_risk_prosedur prp,"
        . "     " . Config::DB_DATABASE . ".risk_prosedur rp,"
        . "     " . Config::DB_DATABASE . ".risk_list rl"
        . " where 1=1 "
        . "	    AND rp.risk_id =  rl.id"
        . "	    AND prp.risk_prosedur_id = rp.id"
        . "	    AND prp.tklf_id = " . $tklfId . " " 
        . " order by rl.kod,rp.kod";
        $result = Crud::selectSql($sql);
        $list = array();
        if ($result[0] != null) {
            foreach ($result as $val) {
                if (!isset($list[$val['rlKod']])){
                    $ic = denetimSoa::denetimCiddiRiskleriGetirWithDosyaLinkMini($val,$belge);
                    $val['kods'] = array(); 
                    array_push($val['kods'], $ic);
                    unset($val['id']);
                    unset($val['rpKod']);
                    unset($val['rpAdi']);
                    unset($val['rpid']);
                    unset($val['rlid']);
                    unset($val['aciklama']);
                    unset($val['durum_id']);
                    unset($val['drive_id']);
                    unset($val['excel_drive_id']);
                    unset($val['kaynak']);
                    unset($val['kanit']);
                    unset($val['duzey']);
                    unset($val['kanit']);
                    unset($val['tutar']);
                    unset($val['talep_edildi']);
                    unset($val['tablo_duzelt']);
                    unset($val['denetim_bulgu']);
                    unset($val['muhtemel_etki']);
                    
                    $list[$val['rlKod']] = $val;
                }else{
                    $ic = denetimSoa::denetimCiddiRiskleriGetirWithDosyaLinkMini($val,$belge);
                    array_push($list[$val['rlKod']]['kods'], $ic);
                }
            }
        }
        return $list;
    }
    
    private static function denetimCiddiRiskleriGetirWithDosyaLinkMini($val,$belge){
        $ic = array();
        $ic['id']       = $val['id'];
        $ic['rpid']     = $val['rpid'];
        $ic['rlid']     = $val['rlid'];
        $ic['rpKod']    = $val['rpKod'];
        $ic['rpAdi']    = $val['rpAdi'];
        $ic['aciklama'] = $val['aciklama'];
        $ic['durum_id'] = $val['durum_id'];
        $ic['drive_id'] = $val['drive_id'];
        $ic['excel_drive_id'] = $val['excel_drive_id'];
        $ic['kaynak']   = $val['kaynak'];
        $ic['duzey']    = $val['duzey'];
        $ic['kanit']    = $val['kanit'];
        $ic['tutar']    = $val['tutar'];
        $ic['talep_edildi'] = $val['talep_edildi'];
        $ic['tablo_duzelt'] = $val['tablo_duzelt'];
        $ic['denetim_bulgu'] = $val['denetim_bulgu'];
        $ic['muhtemel_etki'] = $val['muhtemel_etki'];
        
        if ($belge){
            $belgeler  = Base::basitList(Crud::getSqlCok(new RiskBelge(), RiskBelge::GET_BY_PROSEDUR, array('prsdr'=>$val['rpid'])));
            if ($belgeler != null){
                foreach ($belgeler as $bel) {
                    $arr = array();
                    $arr['id'] =  $bel['id'];
                    $arr['sira'] =  $bel['sira'];
                    $arr['adi'] =  $bel['adi'];
                    $ic['belgeler'][$arr['id']] = $arr;
                }
            }
            
            $drive = driveSoa::getir($val['excel_drive_id']);
            $ic['dId']  = $drive->id;
            $ic['url']  = $drive->url;
            $ic['web']  = $drive->webUrl;
            $ic['name'] = $drive->name;
        }
        
        return $ic;
    }
    
    public static function riskDriveGetir($tklfId,$kod)
    {
        $sql ="select "
        . "	    prp.id as id, "
        . "     prp.drive_id, "
        . "	    rl.kod as rlKod, "
        . "	    rp.kod as rpKod "
        . " from "
        . "	    " . Config::DB_DATABASE . ".plan_risk_prosedur prp, "
        . "     " . Config::DB_DATABASE . ".risk_prosedur rp, "
        . "     " . Config::DB_DATABASE . ".risk_list rl "
        . " where 1=1 "
        . "	    AND rp.risk_id =  rl.id "
        . "	    AND prp.risk_prosedur_id = rp.id "
        . "	    AND prp.tklf_id = " . $tklfId . " "
        . "     AND rl.kod = '". $kod ."' "
        . " order by rl.kod,rp.kod";
        $result = Crud::selectSql($sql);
        return $result;
    }
    
    public static function riskRefsGrupListesiGetir($tklfId,$grup){
        $list = Crud::selectSqlWithPrm(MKRefs::OZEL_TEKLIF_GRUP, array('tklf_id'=>$tklfId,'grup'=>$grup));
        $arr = array();
        if (isset($list)){
            foreach ($list as $v){
                if (!isset($arr[$v['pKod']])){
                    $arr[$v['pKod']] = array();
                }
                array_push($arr[$v['pKod']], $v);
            }
        }
        return $arr;
    }
    
}

// $a = denetimSoa::denetimCiddiRiskleriGetirWithDosyaLink(202127);
// echo '<pre>';
// print_r($a);
// echo '</pre>';

