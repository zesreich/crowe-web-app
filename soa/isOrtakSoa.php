<?php 
include_once 'baseSoa.php';
include_once PREPATH . 'config/config.php';
include_once PREPATH.'db/Crud.php';

Class isOrtakSoa extends BaseSoa{
    
    public static function ortakPaylariGetir($ortak_id){
        return  Base::basitList(Crud::getSqlCok(new IsOrtakPay(), IsOrtakPay::GET_ORTAK_ID, array('ortak_id'=>$ortak_id)));
    }
    
    public static function payDegerIsortakGetir($id,$isOrtakId){
        $list = Crud::getSqlCok(new IsOrtakPayDeger(), IsOrtakPayDeger::GET_TEKLIF_ORTAK_ID, array('tklf_id'=>$id,'pay_ortak_id'=>$isOrtakId));
        if ($list==null){
            isOrtakSoa::payDegerOlustur($id);
            $list = Crud::getSqlCok(new IsOrtakPayDeger(), IsOrtakPayDeger::GET_TEKLIF_ORTAK_ID, array('tklf_id'=>$id,'pay_ortak_id'=>$isOrtakId));
        }
        return Base::basitList($list);
    }
    
    public static function payDegerGetir($id){
        $list = Crud::getSqlCok(new IsOrtakPayDeger(), IsOrtakPayDeger::GET_TEKLIF_ID, array('tklf_id'=>$id));
        if ($list==null){
            isOrtakSoa::payDegerOlustur($id);
            $list = Crud::getSqlCok(new IsOrtakPayDeger(), IsOrtakPayDeger::GET_TEKLIF_ID, array('tklf_id'=>$id));
        }
        return Base::basitList($list);
    }
    
    public static function digerPayDegerGetir($id){
        $list = Crud::getSqlCok(new IsOrtakPayDeger(), IsOrtakPayDeger::GET_PAYORTAK_ID, array('pay_ortak_id'=>$id,'ortak_id'=>$id));
        return Base::basitList($list);
    }
    
    public static function payDegerOlustur($id){
        $db = new Db();
        $ses = $db->getCon();
        try {
            $tklf   = Crud::getById(new Denetim(),$id);
            if ($tklf == null){
                return null;  
            }else{
                $tklf = $tklf->basit();
            }
            
            $paylar = Base::basitList(Crud::getSqlCok(new IsOrtakPay(), IsOrtakPay::GET_ORTAK_ID, array('ortak_id'=>$tklf['musteri_id']['isortagi_id']['id'])));
            if ($paylar == null){
                return null;    
            }
            
            $list = array();
            foreach ($paylar as $v){
                $iopd = new IsOrtakPayDeger();
                $iopd->tklf_id->deger = $id;
                $iopd->ortak_id->deger= $tklf['musteri_id']['isortagi_id']['id'];
                $iopd->pay_ortak_id->deger = $v['pay_ortak_id']['id'];
                $iopd->pay->deger     = $v['pay'];
                $iopd->tutar->deger   = 0;
                $iopd->fatura->deger  = 'H';
                $iopd->odeme->deger   = 'H';
                array_push($list, $iopd);
            }
            
            isOrtakSoa::payDegerTutarDuzenle($tklf['tutar'],$list);
            foreach ($list as $v){
                $snc = Crud::save($v,$ses);
                if ($snc!=1){
                    throw new Exception($snc);
                }else{
                    $v->id->deger = $ses->insert_id;
                }
            }
            return $list;
        } catch (Exception $e) {
            $ses->rollback();
            $result['ht_ack'] = $e;
            return $result;
        }finally {
            mysqli_close($ses);
        }
    }
    
    public static function tekPayDegerKaydet($gelen){
        $db = new Db();
        $ses = $db->getCon();
        try {
            $tbl = Crud::getById(new IsOrtakPayDeger(),$gelen['id']);
            $tklf   = Crud::getById(new Denetim(),$tbl->tklf_id->deger)->basit();
            
            $list = Crud::getSqlCok(new IsOrtakPayDeger(), IsOrtakPayDeger::GET_TEKLIF_ID, array('tklf_id'=>$tbl->tklf_id->deger));
            
            for ($i = 0; $i < count($list); $i++) {
                if ($list[$i]->id->deger == $gelen['id']){
                    $tbl->pay->deger    = $gelen['pay'];
                    $tbl->fatura->deger = ($gelen['fatura'] == 'true' ? 'E' : 'H');
                    $tbl->odeme->deger  = ($gelen['odeme'] == 'true' ? 'E' : 'H');
                    $list[$i] = $tbl;
                    break;
                }
            }
            
            isOrtakSoa::payDegerTutarDuzenle($tklf['tutar'],$list);
            foreach ($list as $v){
                $snc = Crud::update($v,$ses);
                if ($snc!=1){
                    throw new Exception($snc);
                }
            }
            return true;
        } catch (Exception $e) {
            $ses->rollback();
            $result['ht_ack'] = $e;
            return $result;
        }finally {
            mysqli_close($ses);
        }

    }
    
    public static function payDegerTutarDuzenle($tutar,$list){
        $tplmPay = 0;
        foreach ($list as $v){
            $tplmPay += $v->pay->deger;
        }
        foreach ($list as $v){
            $tPay = floor((($tutar * $v->pay->deger)/$tplmPay)*100)/100;
            $v->tutar->deger = $tPay;
        }
    }
    
}

// $list = isOrtakSoa::payDegerGetir(20201);
// foreach ($list as $v){
// //     echo '<pre>';
// //     print_r($v);
// //     echo '</pre>';
//     print_r($v['id'].' - '.$v['tutar'].'</br>');
// }
