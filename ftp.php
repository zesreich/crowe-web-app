<?php
$pId = 114;
include_once 'First.php';
include_once PREPATH . 'header.php';
include_once 'ftpFunction.php';

$path = null;
$delPath = null;
if (isset($_GET['klasor'])){
    $path = $_GET['klasor'];
}

$ftp = Ff::baglan($path);
$file_list = Ff::belgeGetir($ftp);




//if (ftp_get($ftp,'C:\users\ahmethmo\Desktop\sol.JPG' ,'sol.JPG',  FTP_BINARY)) {
//     echo "başarıyla kaydedildi.\n";
// } else {
//     echo "Bir sorun çıktı\n";
// }




Ff::baglantiyiKes($ftp);
$pt = explode("/", $path);
for ($i = 0; $i < count($pt)-1 ; $i += 1) {
    if ($pt[$i] != null)
        $delPath .= '/'.$pt[$i];
}



?>
	
    <div class="col-lg-12">
        <div class="card shadow mb-4">
        	<div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
    			<a id="myLink" href="?klasor=<?=$delPath ?>" >Üst Klasöre Dön</a>
        	</div>
        </div>
	</div>

<div class="row">
<?php 
foreach ($file_list as $key=>$data){
    if ($data != '.' && $data != '..'){
?>
    <div class="col-lg-3">
        <div class="card shadow mb-4">
        	<div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
        		<h6 class="m-0 font-weight-bold text-primary"><?=$data ?></h6>
        		<?php if (!strpos($data,'.')) {?>
        			<a id="myLink" href="?klasor=<?=($path == null ? '' : $path.'/').$data ?>" >Klasör Aç</a>
        		<?php }?>
        	</div>
        	<div class="card-body">
        		<?='<a href="ftpp.php?klasor='.$path.'&adi='.$data.'"_blank">İndir</a>'?>
            	<br>
        	</div>
        </div>
	</div>
<?php 
    }
}?>

</div>
<script type="text/javascript">
</script>
<?php include (PREPATH.'footer.php'); ?>