<?php
$pId = 213;
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
                    	<input type="hidden" id="dznl_id" value="<?=$klc['id']?>" >
                		<div class="col-lg-4 font-weight-bold text-gray-800 align-self-center  text-right">Eski Şifre :</div>
                		<div class="col-lg-8"><input type="text"  id="dznl_eskiSifre" class="form-control form-control-user" ></div>
                	</div>
                    <div class="row mb-2">
                		<div class="col-lg-4 font-weight-bold text-gray-800 align-self-center  text-right">Yeni Şifre :</div>
                		<div class="col-lg-8"><input type="text"  id="dznl_yeniSifre" class="form-control form-control-user" ></div>
                	</div>
                    <div class="row mb-2">
                		<div class="col-lg-4 font-weight-bold text-gray-800 align-self-center  text-right">Yeni Şifre Tekrar :</div>
                		<div class="col-lg-8"><input type="text"  id="dznl_yeniSifret" class="form-control form-control-user" ></div>
                	</div>
                    <div class="row pt-2">
                        <div id="kydt_btn" class="col-lg-12 text-center">
                        	<a href="#" class="btn btn-success col-lg-8">
                        		<i class="fa fa-floppy-o"></i>
                                <span  class="text">Kaydet</span>
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


<script >
    $("#kydt_btn").click(function(){
		var link = "<?=PREPATH.'post/kullaniciPost.php?tur=sifreDegistir' ?>";
        $.post(link,
	        {
	    		id 			: $('#dznl_id').val(),
	    		eSifre     	: $('#dznl_eskiSifre').val(),
	    		ySifre		: $('#dznl_yeniSifre').val(),
	    		ySifret		: $('#dznl_yeniSifret').val(),
		    },
		    function(data,status){
	    		if(status == "success"){
	        		console.log(data);
	    		    var obj = JSON.parse(data);
	    		    if (obj.hata == true) {
	    				hataAc(obj.hataMesaj);
	    		    }else{
	    				location.reload();
	    		    }
	    		}else if(status == "error"){
	    		    hataAc("Bir sorun oluştu.");
	    	    }
		    }
	    );
    });
</script>
<?php include (PREPATH.'footer.php'); ?>