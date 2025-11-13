<?php
$pId = 212;
include_once '../../First.php';
include_once PREPATH . 'header.php';

$tbl    = new Kullanici();
$klc    = Crud::getById(new Kullanici(), $_GET['id'])->basit();

?>

<div class="row">
    <div class="col-12">
        <div class="card shadow" >
        	<div class="card-header bg-gradient-primary py-3">
            	<h6 class="m-0 font-weight-bold text-gray-300"><?=$tbl->vt_Adi().' DÜZENLEME'?></h6>
            </div>
            <div class="card-body">
                <form class="user">
                    <div class="row mb-2">
                		<div class="col-lg-4 font-weight-bold text-gray-800 align-self-center text-right">Id :</div>
                		<div class="col-lg-8"><?=$klc['id'] ?></div>
                	</div>
                    <div class="row mb-2">
                		<div class="col-lg-4 font-weight-bold text-gray-800 align-self-center  text-right">Kullanıcı Adı :</div>
                		<div class="col-lg-8"><?=$klc['kullanici_adi'] ?></div>
                	</div>
                    <div class="row mb-2">
                		<div class="col-lg-4 font-weight-bold text-gray-800 align-self-center  text-right">Adı :</div>
                		<div class="col-lg-8"><?=$klc['ad'] ?></div>
                	</div>
                    <div class="row mb-2">
                		<div class="col-lg-4 font-weight-bold text-gray-800 align-self-center  text-right">Soyadı :</div>
                		<div class="col-lg-8"><?=$klc['soyad'] ?></div>
                	</div>
                    <div class="row mb-2">
                		<div class="col-lg-4 font-weight-bold text-gray-800 align-self-center  text-right">Email :</div>
                		<div class="col-lg-8"><?=$klc['email'] ?></div>
                	</div>
                    <div class="row mb-2">
                		<div class="col-lg-4 font-weight-bold text-gray-800 align-self-center  text-right">Telefon :</div>
                		<div class="col-lg-8"><?=$klc['telefon'] ?></div>
                	</div>
                	<!-- 
                    <div class="text-center p-3">
                    	<a href="<?=PREPATH.'pages/kullanici/kullaniciSifre.php?id='.$_SESSION['login']['id'] ?>" id="dznl_sil" class="btn btn-success col-lg-3" ><span class="text"> Şifre Değiştir</span></a>
                	</div>
                	 -->
                </form>
            </div>
        </div>
    </div>
</div>


<script >
</script>
<?php include (PREPATH.'footer.php'); ?>