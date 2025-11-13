<?php 
include_once  __DIR__.'/../path.php';
include_once PREPATH.'db/Crud.php';
include_once PREPATH.'config/yetkiConfig.php';

// SecurityHelper'ı yükle (varsa)
if (file_exists(__DIR__.'/../helpers/SecurityHelper.php')) {
    include_once __DIR__.'/../helpers/SecurityHelper.php';
}

session_start();

// if ($_SERVER['SERVER_NAME'] == 'localhost') {
//     $listSay = 0;
// } else {
//     $listSay = 1;
// }
// foreach (array_values(array_filter(explode('/', $_SERVER['PHP_SELF']))) as $pth){
//     if ($listSay>1){
//         PREPATH=PREPATH.'../';
//     }else {
//         $listSay++;
//     }
// }

include_once PREPATH.'soa/yetkiSoa.php';

$jsonArray = array();
$jsonArray["hata"] = FALSE;

// function path()
// {
//     $pathlist = array_values(array_filter(explode('/', $_SERVER['PHP_SELF'])));
//     $listSay = 0;
//     $prePath='';
//     foreach ($pathlist as $pth){
//         if ($listSay>1){
//             $prePath=$prePath.'../';
//         }else {
//             $listSay++;
//         }
//     }
//     return $prePath;
// }

function onayHataSayfası($tur,$mesaj){
    $lnk = 'pages/genel/onayHataSonuc.php?snc='.$tur.'&mesaj='.$mesaj;
    header('Location:'.PREPATH.$lnk);
    exit();
}

function hataDondur($mesaj){
    global $jsonArray;
    // XSS koruması
    if (class_exists('SecurityHelper')) {
        $mesaj = SecurityHelper::escape($mesaj);
    }
    $jsonArray["hata"] = TRUE;
    $jsonArray["hataMesaj"] = $mesaj;
    echo json_encode($jsonArray,JSON_UNESCAPED_UNICODE);
    exit();
}

function cevapDondur($mesaj){
    global $jsonArray;
    // XSS koruması
    if (class_exists('SecurityHelper')) {
        $mesaj = SecurityHelper::escape($mesaj);
    }
    $jsonArray["hata"] = FALSE;
    $jsonArray["icerik"] = $mesaj;
    echo json_encode($jsonArray,JSON_UNESCAPED_UNICODE);
    exit();
}

function mesajSet($tur,$sonuc){
    $_SESSION['mesaj']['tur']   = $tur;
    $_SESSION['mesaj']['mesaj'] = $sonuc;
}