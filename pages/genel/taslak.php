<?php
$pId = 206;
include_once '../../First.php';
include_once PREPATH . 'soa/driveSoa.php';
include_once PREPATH . 'soa/genelSoa.php';
include_once PREPATH . 'config/config.php';
include_once PREPATH . 'config/sozlesmeConfig.php';
include_once PREPATH . 'config/sablonConfig.php';

$buLink = 'pages/genel/taslak.php';

driveSoa::baglan($buLink);

include_once PREPATH . 'header.php';

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
<div class="row">
	<div class="col-lg-12 col-xl-12">
        <div class="card shadow" >
        
            <div class="card-body">
            	<nav >
                  <div class="nav nav-tabs"	id="nav-tab" role="tablist">
                    <a class="nav-item nav-link mk mr-1 text-center active" data-toggle="tab" id="btn_mk"  		role="tab" href="#nav-mk" 	>Müşteri Kabuller</a>
                    <a class="nav-item nav-link mk mr-1 text-center" 		data-toggle="tab" id="btn_tklf"		role="tab" href="#nav-tklf" >Teklif</a>
                    <a class="nav-item nav-link mk mr-1 text-center" 		data-toggle="tab" id="btn_szlsm"	role="tab" href="#nav-szlsm">Sözleşme</a>
                    <a class="nav-item nav-link mk mr-1 text-center" 		data-toggle="tab" id="btn_plnlm"	role="tab" href="#nav-plnlm">Planlama</a>
                  </div>
                </nav>
                <div class="border">
                    <div class="tab-content m-3" id="nav-tabContent">
                    	<div class="tab-pane fade show active"	id="nav-mk" 	role="tabpanel" >
            				<div class="row">
                            	<div class="col-xl-4 col-lg-5">
                            		<div class="card shadow mb-4">
                            			<div class="card-header py-3">
                            				<h6 class="m-0 font-weight-bold text-primary">Müşteri Kabul Beyan Belgesi</h6>
                            			</div>
                            			<div class="card-body" id="<?= sablonConfig::MK_BEYAN_GRUP?>"></div>
                            		</div>
                            	</div>
                            	<div class="col-xl-4 col-lg-5">
                            		<div class="card shadow mb-4">
                            			<div class="card-header py-3">
                            				<h6 class="m-0 font-weight-bold text-primary">Müşteri Kabul Denetçi Belgesi</h6>
                            			</div>
                            			<div class="card-body" id="<?= sablonConfig::MK_DENETCI_GRUP?>"></div>
                            		</div>
                            	</div>
            				</div>
                    	</div>
                      	<div class="tab-pane fade" 				id="nav-tklf" 	role="tabpanel" >
            				<div class="row">
                            	<div class="col-xl-4 col-lg-5">
                            		<div class="card shadow mb-4">
                            			<div class="card-header py-3">
                            				<h6 class="m-0 font-weight-bold text-primary">Teklif Taslakları</h6>
                            			</div>
                            			<div class="card-body" id="<?= sablonConfig::TEKLIF_GRUP?>"></div>
                            		</div>
                            	</div>
            				</div>
                      	</div>
                      	<div class="tab-pane fade" 				id="nav-szlsm" 	role="tabpanel" >
            				<div class="row">
                            	<div class="col-xl-4 col-lg-5">
                            		<div class="card shadow mb-4">
                            			<div class="card-header py-3">
                            				<h6 class="m-0 font-weight-bold text-primary">Sözleşmesi Taslakları</h6>
                            			</div>
                            			<div class="card-body" id="<?= sablonConfig::SOZLESME_GRUP?>"></div>
                            		</div>
                            	</div>
            				</div>
                      	</div>
                      	<div class="tab-pane fade" 				id="nav-plnlm" 	role="tabpanel" >
            				<div class="row">
                            	<div class="col-xl-4 col-lg-5">
                            		<div class="card shadow mb-4">
                            			<div class="card-header py-3">
                            				<h6 class="m-0 font-weight-bold text-primary">İnceleme izin</h6>
                            			</div>
                            			<div class="card-body" id="<?= sablonConfig::PLANLAMA_B01?>"></div>
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

<script type="text/javascript">

	<?php
    	$str = '{';
    	foreach (sablonConfig::LISTE as $key => $val){
    	    $str = $str.$key.":{";
    	    foreach ($val as $k => $v){
    	       $str = $str.$k.":'".$v."',";
    	    }
    	   $str = $str.'},';
    	}
    	$str = $str.'};';
    	echo 'const baslik = ' .$str;
	?>

	
	function getirListele(grup){
		loadEkranAc();
		var table = $("#"+grup);
        table.find("div").remove();
		$.get('<?= PREPATH.'post/sablonPost.php?tur=grup&grup='?>'+grup,
		    function(data,status){
	    		if(status == "success"){
	    		    var obj = JSON.parse(data);
	    		    if (obj.hata == true) {
	    				hataAc(obj.hataMesaj);
	    		    }else{
		    		    //console.log(obj.icerik);
		    		    for (const key in obj.icerik) {
			    		    var tbl = "";
							tbl =
								'<div class="col-12 my-2">'+
						    		'<div class="card shadow h-100">'+
						    			'<div class="card-body">'+
						    				'<table class="table table-bordered">'+
						    					'<tbody>'+
						    						'<tr>'+
						    							'<td class="text-center">'+baslik[grup][key]+'</td>'+
						    						'</tr>'+
						    						'<tr>'+
						    							'<td class="text-center" id="'+key+'_div">'
									;
			    		    if (obj.icerik[key] == null) {
			    		    	tbl = tbl +
		    		        		'<div class="col-12">'+
		    		    				'<form enctype="multipart/form-data" action="<?= PREPATH.'post/drivePost.php?tur=sablon_yukle' ?>" method="POST">'+
		    		    					'<input name="dosya" type="file" id="fUpload_'+key+'" hidden />'+ 
		    		    					'<input type="hidden" name="link" value="<?=$buLink ?>">'+
		    		    					'<input type="hidden" name="sablon_key" value="'+key+'">'+
		    		    					'<input type="hidden" name="driveId" value="<?= Config::DRIVE_SABLON_ID ?>">'+
		    		    					'<input type="submit" value="Submit" id="fsubmit_'+key+'" hidden>'+
		    		    					'<a id="'+key+'_upload" href="#" class="btn btn-success col-12" >'+
		    		    						'<i class="fa fa-share"></i>'+
		    		    						'<span id=\'upload-percentage\' class="text">Dosya yükle</span>'+
		    		    					'</a>'+
		    		    				'</form>'+
		    		    			'</div>'
		    		            ;
			    		    	loadEkranKapat();
							}else{
								var drvId = obj.icerik[key];
			    		    	$.post('<?= PREPATH.'post/drivePost.php?tur=belge_getir' ?>',
		    				        {
		    							link 	: '<?=$buLink ?>">',
		    							driveId	: drvId,
		    					    },
		    					    function(data,status){
		    				    		if(status == "success"){
		    				    		    var objIc = JSON.parse(data);
		    				    		    if (objIc.hata == true) {
		    				    				hataAc(objIc.hataMesaj);
		    				    		    }else{
		    					    		    objIcx = objIc.icerik;
		    					    		    var tableIc = $('#'+key+'_div');
		    					    		    tableIc.append(
		    	                        			'<div  class="row mx-2">'+
		    	                            			'<a href="'+objIcx.url+'"  class="col btn  btn-primary" ><i class="fas fa-cloud-download-alt"></i></a>'+
		    	                            			'<a href="'+objIcx.web+'"  class="col mx-2 btn btn-primary" target="_blank" ><i class="fas fa-external-link-alt"></i></a>'+
		    	                            			'<a href="#"			   class="col btn  btn-primary"  onclick="belgeSil(\''+objIcx.id+'\',\''+key+'\')" ><i class="fas fa-times"></i></a>'+
		    	                            		'</div>'
		    	                        		)
		    				    		    }
		    				    		}else if(status == "error"){
		    				    		    hataAc("Bir sorun oluştu.");
		    				    	    }
		    				    		loadEkranKapat();
		    					    }
		    				    );
							}
			    		    tbl = tbl +
            			    		    			'</td>'+
            		                    		'</tr>'+
            		                    	'</tbody>'+
            		                    '</table>'+
            		                '</div>'+
            		            '</div>'+
            		        '</div>'
			    		    ;
			    		    table.append(tbl);
			    		    
			    		    $('#'+key+'_upload').click(function () {
		    		            $('#fUpload_'+key).click();
		    		        });

		    		        $('#fUpload_'+key).bind("change", function () {
		    		            $('#fsubmit_'+key).click();
		    		        });
	    		    	}
		    		    
	    		    }
            		return tbl;
	    		}else if(status == "error"){
	    		    hataAc("Bir sorun oluştu.");
	    	    }
		    }
	    );
	}
    
    function getir(){
        getirListele('<?=sablonConfig::SOZLESME_GRUP ?>');
        getirListele('<?=sablonConfig::TEKLIF_GRUP ?>');
        getirListele('<?=sablonConfig::MK_DENETCI_GRUP ?>');
        getirListele('<?=sablonConfig::MK_BEYAN_GRUP ?>');
        getirListele('<?=sablonConfig::PLANLAMA_B01 ?>');
    }

    function belgeSil(driveId,key){
    	loadEkranAc();
        var c = confirm("Silmek istediğinize emin misiniz?");
        if (c) {
        	$.post("<?=PREPATH.'post/drivePost.php?tur=deleteSozlesmeBelge' ?>",{
        		link 		: <?="'".$buLink."'" ?>,
        		driveId		: driveId,
        		sablon_key	: key 
                },function(data,status){
    	    		if(status == "success"){
    	    			getir();
    	    		}else if(status == "error"){
    	    		    hataAc("Bir sorun oluştu.");
    	    	    }
    	    		loadEkranKapat();
        	    }
            );
        }else{
        	loadEkranKapat();
        }
    }
    
    getir();
    
</script>
<?php include (PREPATH.'footer.php'); ?>