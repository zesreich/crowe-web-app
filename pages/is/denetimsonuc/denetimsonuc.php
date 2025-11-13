<?php
$pId = 243;
include_once '../../../First.php';
include_once PREPATH.'config/mkConfig.php';
include_once PREPATH.'soa/planlama/planlamaSoa.php';
include_once PREPATH.'soa/driveSoa.php';
$tklf_id = $_GET['id'];
include_once PREPATH . 'header.php';
$tklf   = Crud::getById(new Denetim(),$tklf_id) -> basit();
$link = 'pages/is/planlama/planlama.php?id='.$tklf_id;

?>
<style>
.nav-item.nav-link.mk {
    background: #385FCF;
    color: white;
}
.nav-item.nav-link.mk.active {
    background: #FFFFFF;
    color: #535353;
    font-weight: 1000;
}
.tab-pane.fade{
    height: 100%;
}
</style>
<script type="text/javascript">

function prosedurSonuc(kod, id){
	var ele = $("#"+kod.replace(".", "")+"_"+id+"_sonuc");
	if (ele.val() == '<?= mkConfig::PROSEDUR_SONUC_YOK?>') {
		ele.addClass( "bg-success text-white" );
		ele.removeClass("bg-danger bg-warning");
	}else if (ele.val() == '<?= mkConfig::PROSEDUR_SONUC_NORMAL?>') {
		ele.addClass( "bg-warning text-white" );
		ele.removeClass("bg-danger bg-success");
	}else if (ele.val() == '<?= mkConfig::PROSEDUR_SONUC_CIDDI?>') {
		ele.addClass( "bg-danger text-white" );
		ele.removeClass("bg-success bg-warning");
	}
}
</script>
<div class="row">
	<div class="col-lg-12 col-xl-12">
        <div class="card shadow" >
        	<div class="card-header bg-gradient-primary py-3">
            	<h6 class="m-0 font-weight-bold text-gray-300">Planlama</h6>
            </div>
            	<div class="row  py-4 text-center">
                	<div class="col justify-content-center">
                		<h5>Şirket Ünvan</h5>
                  		<?=$tklf['musteri_id']['unvan'] ?>
                    </div>
                    <div class="col">
                     	<h5>Denetim Dönemi</h5>
                  		<?=BaseSoa::strDateToStr($tklf['donem_bas_trh']).'</br>' ?>
                  		<?=BaseSoa::strDateToStr($tklf['donem_bts_trh']) ?>
                    </div>
                    <div class="col text-left">
                     	Hazırlayan</br>
                     	Kontrol Eden</br>
                     	Onaylayan
                    </div>
                    
          		</div>
            
            <div class="card-body">
            	<nav >
                  <div class="nav nav-tabs "	id="nav-tab" role="tablist">
                    <a class="nav-item nav-link mk mr-1 text-center active" data-toggle="tab" id="bkp_btn"  role="tab" href="#nav-bkp" >KAPAK</a>
                    <a class="nav-item nav-link mk mr-1 text-center" 		data-toggle="tab" id="D10_btn"	role="tab" href="#nav-D10" >D10</a>
                    <a class="nav-item nav-link mk mr-1 text-center" 		data-toggle="tab" id="D20_btn"	role="tab" href="#nav-D20" >D20</a>
                    <a class="nav-item nav-link mk mr-1 text-center" 		data-toggle="tab" id="D30_btn"	role="tab" href="#nav-D30" >D30</a>
                  </div>
                </nav>
                <div class="border">
                    <div class="tab-content m-3" id="nav-tabContent">
                    	<div class="tab-pane fade show active"	id="nav-bkp" 	role="tabpanel" ></div>
                      	<div class="tab-pane fade" 				id="nav-D10" 	role="tabpanel" ><?php include 'd01.php';?></div>
                    </div>
                </div>
            </div>
    	</div>
    </div>
</div>
<?php include (PREPATH.'footer.php'); ?>