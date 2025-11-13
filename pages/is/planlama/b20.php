<?php 
foreach (mkConfig::B20_LIST as $pln){
    $prosedurKod = $pln[0];
    $prosedurs   = $pros[$prosedurKod];
    foreach ($prosedurs as $p){
        include 'plan_prosedur.php';
    }
}
?>