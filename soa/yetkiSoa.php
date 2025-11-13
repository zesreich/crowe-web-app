<?php 
include_once 'baseSoa.php';

Class yetkiSoa extends BaseSoa{
    public static function yetkiVarmi ($pYetki){
        if ($_SESSION['login']['yetki'] != null && $_SESSION['login']['yetki'] != ''){
            foreach ($_SESSION['login']['yetki'] as $y){
                if ($y == $pYetki) {
                    return true;
                }
            }
        }
        return FALSE;
    }
    
    public static function programYetkiler($programId){
        $proYet = Crud::getSqlCokTblsiz(new YetkiProgram(), YetkiProgram::FIND_PROGRAM_ID, array('programId'=>$programId));
        if ($proYet != null){
            $result = array();
            foreach ($proYet as $pro){
                array_push($result, $pro['id']);
            }
            return $result;
        }else {
            return null;
        }
    }
    
    public static function grupYetkiler($Id){
        $proYet = Crud::getSqlCokTblsiz(new YetkiGrup(), YetkiGrup::GRUP_YETKILER, array('grp'=>$Id));
        if ($proYet != null){
            $result = array();
            foreach ($proYet as $pro){
                array_push($result, $pro['yetki_id']);
            }
            return $result;
        }else {
            return null;
        }
    }
    
    public static function programGrupYetkiler($Id){
        $proYet = Crud::getSqlCokTblsiz(new YetkiGrup(), YetkiGrup::PROGRAM_YETKILERI, array('prgId'=>$Id));
        if ($proYet != null){
            $result = array();
            foreach ($proYet as $pro){
                array_push($result, $pro['id']);
            }
            return $result;
        }else {
            return null;
        }
    }
}