<?php
$pId = 232;
include_once '../../../First.php';
include_once PREPATH.'config/sozlesmeConfig.php';
include_once PREPATH.'soa/driveSoa.php';
$tklf_id = $_GET['id'];
$link = 'pages/is/sozlesme/sozlesmeView.php?id='.$tklf_id;
driveSoa::baglan($link);

include_once PREPATH . 'header.php';

$tklf   = Crud::getById(new Denetim(),$tklf_id) -> basit();
$cmbFr  = Base::basitList(Crud::all(new TklfFinansRapor()));
$cmbDr  = sozlesmeConfig::DURUMLAR;

$jsCd = null;
foreach ($cmbDr as $v){
    if ($jsCd != null){
        $jsCd = $jsCd.',';
    }
    $jsCd = $jsCd.$v[0] .':"'. $v[1].'"';
}
$jsCd = '{'.$jsCd.'}';


$mkLink         = Crud::getById(new Program() , 204 ) -> basit();
$sozlesmeLink   = Crud::getById(new Program() , 208 ) -> basit();
$veriOdasiLink  = Crud::getById(new Program() , 231 ) -> basit();
$planlamaLink   = Crud::getById(new Program() , 227 ) -> basit();
$denetlemeLink  = Crud::getById(new Program() , 240 ) -> basit();
$denetSonucLink = Crud::getById(new Program() , 243 ) -> basit();

?>

<div class="row">

    <div class="col-lg-12 col-xl-12">
        <div class="card shadow" >
        	<div class="card-header bg-gradient-primary py-3">
            	<h6 class="m-0 font-weight-bold text-gray-300">SÖZLEŞME - <label id="dznl_musteri_adi"></label></h6>
            </div>
            <div class="card-body">
				<div class="row">

					<div class="col-xl-4 col-md-6 mb-4">
						<div class="card border-left-danger shadow h-100 ">
							<div class="card-header py-3 d-flex flex-row align-items-center justify-content-between ">
								<a href="<?=PREPATH.$sozlesmeLink['program_link'].'?id='.$tklf_id ?>" 	id="dznl_mk" class="m-0 font-weight-bold text-primary" ><span class="text">Sözleşme		</span></a>
								<i class="fas fa-file-alt  fa-2x text-gray-300"></i>
                            </div>
							<div class="card-body">
								<div class="row no-gutters align-items-center">
									<div class="col mr-2">
										<div class="row no-gutters align-items-center">
											<div class="col-auto">
												<div class="h5 mb-0 mr-3 font-weight-bold text-gray-800">20%</div>
											</div>
											<div class="col">
												<div class="progress progress-sm mr-2">
													<div class="progress-bar bg-danger" role="progressbar" style="width: 20%" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>

					<div class="col-xl-4 col-md-6 mb-4">
						<div class="card border-left-warning shadow h-100 ">
							<div class="card-header py-3 d-flex flex-row align-items-center justify-content-between ">
								<a href="<?=PREPATH.$mkLink['program_link'].'?id='.$tklf_id ?>" 	id="dznl_mk" class="m-0 font-weight-bold text-primary" ><span class="text">Müşteri Kabul		</span></a>
								<i class="fas fa-handshake  fa-2x text-gray-300"></i>
                            </div>
							<div class="card-body">
								<div class="row no-gutters align-items-center">
									<div class="col mr-2">
										<div class="row no-gutters align-items-center">
											<div class="col-auto">
												<div class="h5 mb-0 mr-3 font-weight-bold text-gray-800">40%</div>
											</div>
											<div class="col">
												<div class="progress progress-sm mr-2">
													<div class="progress-bar bg-warning" role="progressbar" style="width: 40%" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					
					<div class="col-xl-4 col-md-6 mb-4">
						<div class="card border-left-primary shadow h-100 ">
							<div class="card-header py-3 d-flex flex-row align-items-center justify-content-between ">
								<a href="<?=PREPATH.$veriOdasiLink['program_link'].'?id='.$tklf_id ?>" 	id="dznl_mk" class="m-0 font-weight-bold text-primary" ><span class="text">Veri Odası		</span></a>
								<i class="fas fa-database  fa-2x text-gray-300"></i>
                            </div>
							<div class="card-body">
								<div class="row no-gutters align-items-center">
									<div class="col mr-2">
										<div class="row no-gutters align-items-center">
											<div class="col-auto">
												<div class="h5 mb-0 mr-3 font-weight-bold text-gray-800">60%</div>
											</div>
											<div class="col">
												<div class="progress progress-sm mr-2">
													<div class="progress-bar bg-primary" role="progressbar" style="width: 60%"  aria-valuemin="0" aria-valuemax="100"></div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					
					<div class="col-xl-4 col-md-6 mb-4">
						<div class="card border-left-info shadow h-100 ">
							<div class="card-header py-3 d-flex flex-row align-items-center justify-content-between ">
								<a href="<?=PREPATH.$planlamaLink['program_link'].'?id='.$tklf_id ?>" 	id="dznl_mk" class="m-0 font-weight-bold text-primary" ><span class="text">Planlama</span></a>
								<i class="fas fa-seedling  fa-2x text-gray-300"></i>
                            </div>
							<div class="card-body">
								<div class="row no-gutters align-items-center">
									<div class="col mr-2">
										<div class="row no-gutters align-items-center">
											<div class="col-auto">
												<div class="h5 mb-0 mr-3 font-weight-bold text-gray-800">80%</div>
											</div>
											<div class="col">
												<div class="progress progress-sm mr-2">
													<div class="progress-bar bg-info" role="progressbar" style="width: 80%"  aria-valuemin="0" aria-valuemax="100"></div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					
					<div class="col-xl-4 col-md-6 mb-4">
						<div class="card border-left-success shadow h-100 ">
							<div class="card-header py-3 d-flex flex-row align-items-center justify-content-between ">
								<a href="<?=PREPATH.$denetlemeLink['program_link'].'?id='.$tklf_id ?>" 	id="dznl_mk" class="m-0 font-weight-bold text-primary" ><span class="text">Denetim</span></a>
								<i class="fas fa-seedling  fa-2x text-gray-300"></i>
                            </div>
							<div class="card-body">
								<div class="row no-gutters align-items-center">
									<div class="col mr-2">
										<div class="row no-gutters align-items-center">
											<div class="col-auto">
												<div class="h5 mb-0 mr-3 font-weight-bold text-gray-800">100%</div>
											</div>
											<div class="col">
												<div class="progress progress-sm mr-2">
													<div class="progress-bar bg-success" role="progressbar" style="width: 100%"  aria-valuemin="0" aria-valuemax="100"></div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					
					<div class="col-xl-4 col-md-6 mb-4">
						<div class="card border-left-danger shadow h-100 ">
							<div class="card-header py-3 d-flex flex-row align-items-center justify-content-between ">
								<a href="<?=PREPATH.$denetSonucLink['program_link'].'?id='.$tklf_id ?>" 	id="dznl_mk" class="m-0 font-weight-bold text-primary" ><span class="text">Denetim Sonuçları</span></a>
								<i class="fas fa-user-friends  fa-2x text-gray-300"></i>
                            </div>
							<div class="card-body">
								<div class="row no-gutters align-items-center">
									<div class="col mr-2">
										<div class="row no-gutters align-items-center">
											<div class="col-auto">
												<div class="h5 mb-0 mr-3 font-weight-bold text-gray-800">25%</div>
											</div>
											<div class="col">
												<div class="progress progress-sm mr-2">
													<div class="progress-bar bg-danger" role="progressbar" style="width: 25%"  aria-valuemin="0" aria-valuemax="100"></div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>

				</div>
			</div>
        </div>
    </div>
</div>


<script >
	var jsCd = <?=$jsCd ?>;



    function getir(){
    	loadEkranAc();
    	$.post("<?=PREPATH.'post/sozlesmePost.php?tur=tekSozlesme' ?>",
			{
    			tklfid			: '<?=$tklf_id ?>'
    	    },
    	    function(data,status){
        		if(status == "success"){
        		    var obj = JSON.parse(data);
        		    if (obj.hata == true) {
        				hataAc(obj.hataMesaj);
        		    }else{
        		    	objx = obj.icerik;
        		    	console.log(objx.munvan);
        		    	$('#dznl_no'			).val(objx.tklfid);
        		    	$('#dznl_id'			).val(objx.id);
        		    	$('#dznl_musteri_id'	).val(objx.munvanid);
        		    	$('#dznl_musteri_adi'	).text(objx.munvan);
        		    	$('#dznl_durum'			).val(jsCd[objx.durum]);
        		    	$('#dznl_donem_bas_trh'	).val(formatTarihforForm(objx.donem_bas_trh));
        		    	$('#dznl_donem_bts_trh'	).val(formatTarihforForm(objx.donem_bts_trh));
        		    	$('#dznl_frc_id'		).val(objx.frc_id);
        		    	$('#dznl_genel_tarihi'	).val(formatTarihforForm(objx.genel_kurul_trh));
        		    	$('#dznl_musteri_tarihi').val(formatTarihforForm(objx.musteri_imza_trh));
        		    	$('#dznl_denetim_tarihi').val(formatTarihforForm(objx.denetim_imza_trh));
        		    	$('#dznl_teslim_tarihi' ).val(objx.teslim_tarihi == null ? '' : objx.teslim_tarihi.substring(0, 10));
        		    }
        		}else if(status == "error"){
        		   hataAc("Bir sorun oluştu.");
        	    }
        		loadEkranKapat();
    	    }
        );
    }
	
    getir();
</script>
<?php include (PREPATH.'footer.php'); ?>