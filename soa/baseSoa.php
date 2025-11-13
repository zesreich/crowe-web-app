<?php 
include_once  __DIR__.'/../path.php';
abstract class BaseSoa
{
//     public static function path()
//     {
//         $pathlist = array_values(array_filter(explode('/', $_SERVER['PHP_SELF'])));
//         $listSay = 0;
//         $lnk='';
//         foreach ($pathlist as $pth){
//             if ($listSay>1){
//                 $lnk=$lnk.'../';
//             }else {
//                 $listSay++;
//             }
//         }
//         return $lnk;
//     }
    
    //YYYY-MM-DD h:i:sa => DD/MM/YYYY

    
    private static function trhParcala($str){
        $result = array();
        $result['y'] = substr($str, 0, 4);
        $result['a'] = substr($str, 5, 2);
        $result['g'] = substr($str, 8, 2);
        $result['s'] = substr($str, 10,2);
        $result['d'] = substr($str, 12,2);
        $result['sn']= substr($str, 14,2);
        return $result;
    }
    //YYYY-MM-DD
    private static function trhParcalaGun($str){
        $result = array();
        $result['y'] = substr($str, 0, 4);
        $result['a'] = substr($str, 5, 2);
        $result['g'] = substr($str, 8, 2);
        return $result;
    }
    //DD-MM-YYYY
    public static function trhParcalaGun2($str){
        $result = array();
        $result['g'] = substr($str, 0, 2);
        $result['a'] = substr($str, 3, 2);
        $result['y'] = substr($str, 6, 4);
        return $result;
    }
    
    public static function donemGetir ($adet ,$yil = null){
        $lst = array();
        if ($yil != null){
            $yil = BaseSoa::trhParcalaGun2($yil)['y'];
        }else{
            $yil = date('Y');
        }
        for ($i = 0; $i < $adet; $i++) {
            array_push($lst, ($yil-$i));    
        }
        return $lst;
    }
    
    public static function strDateToStr($str){
        $d = BaseSoa::trhParcala($str);
        return $d['g'].'/'.$d['a'].'/'.$d['y'];
    }
    
    public static function strToForm($str){
        if ($str == null){
            return null;            
        }else{
            $d = BaseSoa::trhParcala($str);
            return $d['y'].'-'.$d['a'].'-'.$d['g'];
        }
    }
    
    public static function yilGetir($str){
        $d = BaseSoa::trhParcala($str);
        return $d['y'];
    }
    
    public static function paraFormat($val,$vr=2)
    {
        if (strpos($val,'.') != null){
            $ilk = substr($val,0,strpos($val,"."));
            $son = substr($val,strpos($val,"."));
        }else{
            $ilk = $val;
            $son = null;
        }
        $dgr = '';
        $uc = 0;
        for ($i = strlen($ilk); $i > 0; $i--) {
            if ($uc == 3){
                $uc = 0;
                $dgr = '.'.$dgr;
            }
            $dgr = substr($ilk, $i-1, 1).$dgr;
            $uc++;
        }
        if ($vr > 0){
            $dgr .= ',';
            for ($i = 0; $i < $vr; $i++) {
                if ($i+1 > strlen($son)-1){
                    $dgr .= '0';
                }else{
                    $dgr .= substr($son, $i+1, 1);
                }
            }
        }
        return  $dgr;
    }
    
}


