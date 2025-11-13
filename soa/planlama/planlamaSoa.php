<?php 
include_once  __DIR__.'/../../path.php';
include_once PREPATH . 'soa/baseSoa.php';
include_once PREPATH . 'soa/mkSoa.php';
include_once PREPATH . 'config/config.php';
include_once PREPATH . 'db/Crud.php';

Class planlamaSoa extends BaseSoa{
    
    public static function tumCiddiRiskGetir ($tklfId){
        $sql = 
         "SELECT                         "
        ."    r.id,                      "
        ."    m.grup as mgrup,           "
        ."    m.kod as mkod,             "
        ."    r.kod as rkod,             "
        ."    r.adi,                     "
        ."    p.sonuc,                   "
        ."    p.id as pid,               "
        ."    p.b80Aciklama              "
        ."FROM                           "
        ."    ".Config::DB_DATABASE.".mk_risk m,   "
        ."    ".Config::DB_DATABASE.".risk_list r, "
        ."    ".Config::DB_DATABASE.".prosedur p   "
        ."WHERE 1=1                      "
        ."    AND m.risk_id = r.id       "
        ."    AND p.tklf_id = m.tklf_id  "
        ."    AND p.grup = m.grup        "
        ."    AND p.kod = m.kod          "
        ."    AND p.sonuc = '". mkConfig::PROSEDUR_SONUC_CIDDI ."' "
        ."    AND m.tklf_id = ". $tklfId ." "
        ."ORDER BY r.kod,m.grup,m.kod    ";
        
        $result = Crud::selectSql($sql);
        $list = array();
        if ($result[0] != null){
            foreach ($result as $val){
                if (!isset($list[$val['rkod']]) || $list[$val['rkod']]['rkod'] != $val['rkod']){
                    $val['kods'] = array(); 
                    array_push($val['kods'], $val['mgrup'].'-'.$val['mkod']);
                    unset($val['mgrup']);
                    unset($val['mkod']);
                    $list[$val['rkod']] = $val;
                }else{
                    array_push($list[$val['rkod']]['kods'], $val['mgrup'].'-'.$val['mkod']);
                }
            }
        }
        return $list;
    }
    
    public static function prosedurGetir ($tklfId){
        $clm = '';
        foreach (mkConfig::RISK_ALTLAR as $a){
            $clm .= ",p.".$a[0];
        }
        $sql = 
         "select "
        ."	rp.id, "
        ."	rp.risk_prosedur_id, "
        ."	rp.kaynak, "
        ."  rp.duzey, "
        ."  p.kod as p_kod, "
        ."  p.adi as p_adi, "
        ."  r.kod as r_kod, "
        ."  r.adi as r_adi "
        . $clm . " " 
        ."from "
        ."  ".Config::DB_DATABASE.".plan_risk_prosedur rp, "
        ."  ".Config::DB_DATABASE.".risk_prosedur p, "
        ."  ".Config::DB_DATABASE.".risk_list r "
        ."where 1=1 "
        ."	AND p.id = rp.risk_prosedur_id "
        ."  AND p.risk_id = r.id "
        ."  AND rp.tklf_id=". $tklfId
        ." order by r.kod,p.kod";
        
        $result = Crud::selectSql($sql);
        return $result;
    }
    
    public static function prosedurlerHepsi($tklf_id){
        $list = Crud::getSqlCok(new Prosedur(), Prosedur::GET_BY_TEKLIF_TIP, array('tklf_id'=>$tklf_id,'tip'=>mkConfig::PROSEDUR_TIP_PLAN));
        $arr = array();
        if ($list != null){
            foreach ($list as $v){
                if (!isset($arr[$v->grup->deger])){
                    $arr[$v->grup->deger] = array();
                }
                $arr[$v->grup->deger][$v->kod->deger]=$v;
            }
        }
        return $arr;
    }
    
    public static function planListesiGetirHepsi(){
        $list = Crud::getSqlCok(new PlanListesi(), PlanListesi::GET_BY_AKTIF, array());
        $arr = array();
        foreach ($list as $v){
            if (!isset($arr[$v->grup->deger])){
                $arr[$v->grup->deger] = array();
            }
            $arr[$v->grup->deger][$v->kod->deger]=$v->aciklama->deger;
        }
        return $arr;
    }
    
    
    public static function getPlanlamaBelge($tklf_id,$link){
        $result=array();
        try {
            $plnm   = Crud::getSqlTek(new Planlama(), Planlama::GET_TEKLIF_ID, array('tklf_id'=>$tklf_id)) -> basit();
            $result = planlamaSoa::belgeGetir($result,sablonConfig::PLANLAMA_DENETIM_SIRKET_TR, 'sirket_tr'   ,$plnm['sirket_tr']  ,$link);
            $result = planlamaSoa::belgeGetir($result,sablonConfig::PLANLAMA_DENETIM_SIRKET_ENG,'sirket_eng'  ,$plnm['sirket_eng'] ,$link);
            $result = planlamaSoa::belgeGetir($result,sablonConfig::PLANLAMA_MUSTERI_TR,        'musteri_tr'  ,$plnm['musteri_tr'] ,$link);
            $result = planlamaSoa::belgeGetir($result,sablonConfig::PLANLAMA_MUSTERI_ENG,       'musteri_eng' ,$plnm['musteri_eng'],$link);
        } catch (Exception $e) {
            echo $e->getMessage();
            exit;
        }
        return $result;
    }
    
    public static function belgeGetir($result,$key,$kisaKey,$value,$link){
        $result[$key]['kisa']  = $kisaKey;
        if ($value != null){
            $drive = driveSoa::getir($value);
            $result[$key]['id']   = $drive->id;
            $result[$key]['url']  = $drive->url;
            $result[$key]['web']  = $drive->webUrl;
        }else{
            $result[$key]['id']   = null;
        }
        return $result;
    }

    public static function getRiskDrive($id,$link){
        $result=array();
        try {
            $mk = Crud::getById(new RiskProsedur(),$id);
            if ($mk->drive_id->deger != null){
                $drive = driveSoa::getir($mk->drive_id->deger);
                $result['id']   = $drive->id;
                $result['url']  = $drive->url;
                $result['web']  = $drive->webUrl;
            }else{
                $result['id']   = null;
            }
            
        } catch (Exception $e) {
            echo $e->getMessage();
            exit;
        }
        return $result;
    }
    
    public static function getB70List($tklf_id){
        $list = Base::basitList(Crud::getSqlCok(new PlanB70(), PlanB70::GET_TKLIF, array('tklf_id'=>$tklf_id)));
        $arr = array();
        foreach ($list as $v){
            $arr[$v['tur']]['id']          = $v['id'];
            $arr[$v['tur']]['oran']        = $v['oran'];
            $arr[$v['tur']]['tutar']       = $v['tutar'];
            $arr[$v['tur']]['performans']  = $v['performans'];
        }
        return $arr;
    }
    
}

//  echo '<pre>';
//  $a = planlamaSoa::getB70List(202130);
//  print_r($a);
//  echo '</pre>';

// $list = isOrtakSoa::payDegerGetir(20201);
// foreach ($list as $v){
// //     echo '<pre>';
// //     print_r($v);
// //     echo '</pre>';
//     print_r($v['id'].' - '.$v['tutar'].'</br>');
// }
