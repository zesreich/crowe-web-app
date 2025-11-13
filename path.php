<?php
if(!defined('PREPATH')){
    
    if ($_SERVER['SERVER_NAME'] == 'localhost') {
        $listSay = 2;
    } else {
        $listSay = 1;
    }
    $prePath='';
    for($pathSayi = $listSay; $pathSayi < count(array_values(array_filter(explode('/', $_SERVER['PHP_SELF'])))); $pathSayi++) {
        $prePath=$prePath.'../';
    }
    define("PREPATH" , $prePath);
    
//     echo count(array_values(array_filter(explode('/', $_SERVER['PHP_SELF'])))).'</br>';
//     echo $_SERVER['PHP_SELF'].'</br>';
//     echo PREPATH.'</br>';
    
}
