<?php
$pId = 115;
include_once 'First.php';
if ($_SESSION['login']['id'] == -1){
    header('Location:'.PREPATH.'pages/genel/login.php');
    exit();
}else if(KullaniciTurPrm::ISORTAGI == $_SESSION['login']['tur']){
    header('Location:'.PREPATH.'pages/is/isOrtagi.php?id='.$_SESSION['login']['isortagi_id']);
    exit();
}else if(KullaniciTurPrm::DENETCI == $_SESSION['login']['tur']){
    header('Location:'.PREPATH.'pages/is/denetci.php?id='.$_SESSION['login']['id']);
    exit();
}else if(KullaniciTurPrm::MUSTERI == $_SESSION['login']['tur']){
    header('Location:'.PREPATH.'pages/is/musteri.php?id='.$_SESSION['login']['musteri_id']);
    exit();
}
include_once PREPATH . 'header.php';
?>
<div class="row">
</div>
<?php include (PREPATH.'footer.php'); ?>