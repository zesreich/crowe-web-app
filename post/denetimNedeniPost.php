<?php 
include_once 'basePost.php';
include_once '../db/Crud.php';
try {
    if ($_GET['tur']== 'nedenlerByUstid'){
        $result = Crud::getSqlCok(new TklfDenetimNedeni(), TklfDenetimNedeni::NEDEN_SIRALI_BY_USTID, array('ustid'=>$_GET['ustid']));
        if ($result!=null){
            $result = json_encode(Base::basitList($result),JSON_UNESCAPED_UNICODE) ;
            cevapDondur($result);
        }else{
            hataDondur($result);
        }
    }else if ($_GET['tur']== 'nedenler'){
        $list = Crud::all(new TklfDenetimNedeni());
        $result = array();
        $sclId = $_GET['id'];
        for ($i = 0; $i < count($list); $i++) {
            if ($list[$i]->id->deger == -1){
                break;
            }else if ($list[$i]->id->deger == $sclId){
                array_unshift($result,$list[$i]);
                $sclId = $list[$i]->ust_id->deger;
                $i = -1;
            }
        }
        if ($result!=null){
            $result = json_encode(Base::basitList($result),JSON_UNESCAPED_UNICODE) ;
            cevapDondur($result);
        }else{
            hataDondur($result);
        }
    }else if ($_GET['tur']== 'all'){
        $id = $_GET['id'];
        $list = Base::basitList(Crud::all(new TklfDenetimNedeni()));
        $tbl = array();
        for ($i = 0; $i < count($list); $i++) {
            if ($id == $list[$i]['id']){
                array_unshift($tbl, $list[$i]);
                if ($list[$i]['id'] == -1){
                    break;
                }
                $id = $list[$i]['ust_id'];
                $i = -1;
            }
        }
        cevapDondur($tbl);
    }
} catch (Exception $e) {
    hataDondur($e);
}

