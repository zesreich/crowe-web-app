<?php 

$pId = 211;
include_once '../../First.php';
include_once PREPATH . 'header.php';

if (!isset($_GET['snc']) || $_GET['snc'] == 'false'){
?>
	<div class="jumbotron text-center">
      <h1 class="display-3 font-weight-bold text-danger">Bir Hata Oluştu.</h1>
      <h3 class="text-danger"><?= $_GET['mesaj']?></h3>
      <i class="fas fa-times fa-10x text-danger"></i>
    </div>
<?php
}else{
?>
    <div class="jumbotron text-center">
      <h1 class="display-3 font-weight-bold text-success">İşleminiz Onaylanmıştır.</h1>
      <h3 class="text-success"><?= $_GET['mesaj']?></h3>
      <i class="fas fa-check fa-10x text-success"></i>
    </div>
<?php
}
include (PREPATH.'footer.php'); 
?>