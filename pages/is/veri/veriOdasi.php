<?php
$pId = 231;
include_once '../../../First.php';
include_once PREPATH.'config/mkConfig.php';
include_once PREPATH.'soa/mkSoa.php';
include_once PREPATH.'soa/denetimSoa.php';
include_once PREPATH.'soa/driveSoa.php';

$tklf_id = $_GET['id'];
$link = 'pages/is/veri/veriOdasi.php?id='.$tklf_id;
driveSoa::baglan($link);

include_once PREPATH . 'header.php';

$tklf   = Crud::getById(new Denetim(),$tklf_id) -> basit();
$riskler= denetimSoa::denetimCiddiRiskleriGetirWithDosyaLink($tklf_id);

$musteriMi = 'H';
$denetciMi = 'H';

if ($_ADMIN){
    $musteriMi = 'E';
    $denetciMi = 'E';
}else{ 
    if (yetkiSoa::yetkiVarmi(yetkiConfig::VERIODASI_MUSTERI_ONAY)){
        $musteriMi = 'E';
    }
    if (yetkiSoa::yetkiVarmi(yetkiConfig::VERIODASI_DENETCI_ONAY)){
        $denetciMi = 'E';
    }
}

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
.switch {
  position: relative;
  display: inline-block;
  width: 60px;
  height: 34px;
}

.switch input { 
  opacity: 0;
  width: 0;
  height: 0;
}

.slider {
  position: absolute;
  cursor: pointer;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: #ccc;
  -webkit-transition: .4s;
  transition: .4s;
}

.slider:before {
  position: absolute;
  content: "";
  height: 26px;
  width: 26px;
  left: 4px;
  bottom: 4px;
  background-color: white;
  -webkit-transition: .4s;
  transition: .4s;
}

input:checked + .slider {
  background-color: #d9534f;
}

input:focus + .slider {
  box-shadow: 0 0 1px #d9534f;
}

input:checked + .slider:before {
  -webkit-transform: translateX(26px);
  -ms-transform: translateX(26px);
  transform: translateX(26px);
}

/* Rounded sliders */
.slider.round {
  border-radius: 34px;
}

.slider.round:before {
  border-radius: 50%;
}
</style>
<div class="row">
	<div class="col-lg-12 col-xl-12">
        <div class="card shadow" >
        	<div class="card-header bg-gradient-primary py-3">
            	<h6 class="m-0 font-weight-bold text-gray-300">Denetim</h6>
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
                     	Hazırlayan<br/>
                     	Kontrol Eden<br/>
                     	Onaylayan
                    </div>
                    
          		</div>
            
            <div class="card-body">
            	<nav >
                  <div class="nav nav-tabs "	id="nav-tab" role="tablist">
                    <a class="nav-item nav-link mk mr-1 text-center active" data-toggle="tab" href="#nav-mkh"  id="mkh_btn" role="tab" href="#mkh" >KAPAK</a>
                    	<?php foreach ($riskler as $key => $value){ ?>
                    	<a class="nav-item nav-link mk mr-1 text-center" data-toggle="tab" href="#nav-<?=$key?>" id="<?=$key?>_btn" role="tab" href="#<?=$key?>" ><?=$key ?></a>
                    	<?php }?>
                  </div>
                </nav>
                <div class="border">
                    <div class="tab-content m-3" id="nav-tabContent">
                    	<div class="tab-pane fade show active"	id="nav-mkh" 	role="tabpanel" ></div>
                    	<?php
                    	   foreach ($riskler as $key => $value){
                    	       echo '<div class="tab-pane fade" id="nav-'.$key.'" role="tabpanel" >';
                	           ?>
                    	       <div class="card shadow mb-1">
                                    <div class="card-body">
                                    	<nav >
                                          	<div class="nav nav-tabs "	id="nav-tabiki" role="tablist">
                                            <?php
                                                foreach ($value['kods'] as $p){
                                                    echo '<a class="nav-item nav-link mk mr-1 text-center" onclick="sayfaYukle('.$p['rlid'].','.$p['rpid'].')" data-toggle="tab" id="b00_btn"	role="tab" href="#nav-a'.str_replace(".","",$p['id']).'" >'.$p['rpKod'].'</a>';
                                                }
                                            ?>
                                			</div>
                                       	</nav>
                                        <div class="border">
                                            <div class="tab-content m-3" id="nav-tabContent">
                                                <?php 
                                                    foreach ($value['kods'] as $p){
                                                        echo '<div class="tab-pane fade " id="nav-a'.str_replace(".","",$p['id']).'" role="tabpanel" >';
                                                        foreach ($p['belgeler'] as $b){
                                                        ?>
                                                        	<div class="card shadow mb-1">
                                                                <div class="card-header">
                                                                    <a href="#" onclick="notDuzenle(<?="'".$p['rlid'].'_'.$p['rlid']."',".$b['id'] ?>);" id="<?='pNot_'.$p['rlid']."_".$p['rpid']."_".$b['id'] ?>" data-toggle="modal" data-target="#myModalRisk" class="mx-2 btn btn-outline-primary float-right <?= $denetciMi == 'E' ? '' : 'disabled' ?>"> 
                                                            			<i id="<?='pZil_'.$p['rlid']."_".$p['rpid']."_".$b['id'] ?>" class="fas fa-bell " ></i>
                                                            		</a>
                                                                	<h6 class="m-0 font-weight-bold text-primary"><?=$b['sira'].' - '.$b['adi'] ?></h6>
                                                                </div>
                                                                <div class="row">
                                                                	<div class="col-xl-6 col-lg-12">
                                                    		    		<div class="card-body">
                                                                    		<table class="table table-bordered table-striped " >
                                                                    			<thead>
                                                                    				<tr>
                                                                    					<th class="bg-gray-700 text-gray-200 text-center align-middle" style="width:25%">Adı</th>
                                                                    					<th class="bg-gray-700 text-gray-200 text-center align-middle" style="width:25%">İndir</th>
                                                                    					<th class="bg-gray-700 text-gray-200 text-center align-middle" style="width:25%">Göster</th>
                                                                    					<th class="bg-gray-700 text-gray-200 text-center align-middle" style="width:25%">Sil</th>
                                                                    				</tr>
                                                                    			</thead>
                                                                    			<tbody  id="belgeList_<?=$p['rlid']."_".$p['rpid']."_".$b['id']?>" >
        																		</tbody>
                                                                    		</table>
                                                                    		<table class="table table-bordered table-striped ">
                                                                    			<tbody>
                                                                    				<tr>
                                                    									<td style="width: 100%" class="text-center">
                                                    										<form id="<?='form_'.$p['rlid']."_".$p['rpid']."_".$b['id']?>" enctype="multipart/form-data"  method="POST">
                                                                                                <input name="dosya[]" type="file" id = "fUpload_<?= $b['id'] ?>" multiple hidden/>
                                                                                                <input type="hidden" name="link" value="<?=$link ?>">
                                                                                                <input type="hidden" name="id" id="<?='formid_'.$p['rlid']."_".$p['rpid']."_".$b['id']?>" value="0">
                                                                                                <input type="hidden" name="grup"			value="<?=$p['rlid']?>">
                                                                                                <input type="hidden" name="kod"				value="<?=$p['rpid']?>">
                                                                                                <input type="hidden" name="adi"				value="<?=$key.'-'.$p['rpKod'].'-'.$b['id']?>">
                                                                                                <input type="hidden" name="tklf_id"			value="<?=$tklf_id ?>">
                                                                                                <input type="hidden" name="risk_belge_id"	value="<?=$b['id'] ?>">
                                                                                                <input type="submit" value="Submit" id = "fsubmit_<?= $b['id'] ?>" hidden>
                                                                                                <a href="#" id="<?='upload_'.$p['rlid']."_".$p['rpid']."_".$b['id']?>" class="btn btn-success col-12" onclick="belgeYukle(<?=$p['rlid'].",".$p['rpid'].",".$b['id']?>);return false;">
                                                                    			        			<i class="fas fa-plus"></i>
                                                                    			        		</a>
                                                                                            </form>
																						</td>
                                                    				      			</tr>
                                                                    			</tbody>
                                                                    		</table>
                                                                		</div>
                                                                	</div>
                                                                	<div class="col-xl-6 col-lg-12">
                                                                		<div class="card-body">
                                                                			<input type="hidden" id="<?='formid_'.$p['rlid']."_".$p['rpid']."_".$b['id']?>" value="0">
                                                                    		<table id="xx" class="table table-bordered table-striped " >
                                                                    			<tbody>
                                                                    				<tr>
                                                                        				<th colspan="2" class="bg-gray-700 text-gray-200 text-center align-middle">Müşteri</th>
                                                                        				<th colspan="2" class="bg-gray-700 text-gray-200 text-center align-middle">Denetçi</th>
                                                                    				</tr>
                                                                    				<tr>
                                                                        				<td class="text-center align-middle" >Tarih</td>
                                                                        				<td class="text-center align-middle" >Bitir</td>
                                                                        				<td class="text-center align-middle" >Termin</td>
                                                                        				<td class="text-center align-middle" >Onay</td>
                                                                    				</tr>
                                                                    				<tr>
                                                                        				<td><input disabled id="<?='musteri_tarih_'.$p['rlid']."_".$p['rpid']."_".$b['id']?>" type="date" class="form-control form-control-user" ></td>
                                                                        				<td class="text-center align-middle" ><label class="switch"><input disabled id="<?='chk_mt_'.$p['rlid']."_".$p['rpid']."_".$b['id']?>" type="checkbox" onclick="chckMusteriTarihi(<?=$p['rlid'].",".$p['rpid'].",".$b['id']?>)" value="H" ><span class="slider round"></span></label></td>
                                                                        				<td><input <?= $denetciMi == 'E' ? '' : 'disabled' ?> id="<?='denetci_tarih_'.$p['rlid']."_".$p['rpid']."_".$b['id']?>" type="date" class="form-control form-control-user" ></td>
                                                                        				<td class="text-center align-middle" ><label class="switch"><input disabled id="<?='chk_dt_'.$p['rlid']."_".$p['rpid']."_".$b['id']?>" type="checkbox" onclick="checkDenetciTarih(<?=$p['rlid'].",".$p['rpid'].",".$b['id']?>)" value="H" ><span class="slider round"></span></label></td>
                                                                    				</tr>
                                                                    				<tr>
                                                                    					<td colspan="3"><textarea <?= $denetciMi == 'E' ? '' : 'disabled' ?>  id="<?='aciklama_'.$p['rlid']."_".$p['rpid']."_".$b['id']?>" rows="3" class="form-control"  ></textarea></td>
                                                                    					<td class="text-center align-middle" ><a id="<?='kaydet_'.$p['rlid']."_".$p['rpid']."_".$b['id']?>" href="#" onclick="formKaydet(<?="'".$key.'-'.$p['rpKod'].'-'.$b['id']."',".$p['rlid'].",".$p['rpid'].",".$b['id']?>);" class="my-0 btn btn-primary " >Kaydet</a></td>
                                                                    				</tr>
                                                                    			</tbody>
                                                                    		</table>
                                                                		</div>
                                                                	</div>
                                                                </div>
                                                        	</div>
                                                        <?php 
                                                        }
                                                        echo '</div>';
                                                    }
                                                ?>
                                            </div>
                                    	</div>
                                	</div>
                                </div>    
                    	       <?php 
                    	       echo '</div>';
                	       }
                	    ?>
                    </div>
                </div>
            </div>
    	</div>
    </div>
</div>
<div class="modal fade" id="myModalRisk" role="dialog">
	<div class="modal-dialog modal-xl">
		<div class="modal-content">
			<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 mb-12">
				<div class="card" style="min-height:395px;">
					<div class="card-block">
						<div id="txtHintRisk"></div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">

    function notDuzenle(grup,kod) {
        $.get( "<?php echo PREPATH.'pages/genel/notDuzenle.php?'?>"+'tklf_id='+<?=$tklf_id ?>+'&grup='+grup+'&kod='+kod, function(data, status){
    		if(status == "success"){
    			$("#txtHintRisk").empty();
    			$("#txtHintRisk").append(data);
    		}else if(status == "error"){
    		    hataAc("Bilgi çekilemedi.");
    	    }
    	});
    }
    
    function belgeYukle(grup,kod,belgeId){
		$("#fUpload_"+belgeId).click();
    }

  	<?php
 	   foreach ($riskler as $key => $value){
 	       foreach ($value['kods'] as $p){
 	           foreach ($p['belgeler'] as $b){
 	            ?>
                    $("#fUpload_<?=$b['id']?>").bind("change", function () {
                        var formData = new FormData($('form#form_<?=$p['rlid']."_".$p['rpid']."_".$b['id']?>')[0]);
                        $.ajax({
                    	  url: "<?=PREPATH.'post/veriodasiPost.php?tur=belgeYukle'?>",
                    	  data: formData,
                    	  processData: false,
                    	  contentType: false,
                    	  type: 'POST',
                    	  success: function(data){
                    		  belgeListesiGetir(<?=$p['rlid'].",".$p['rpid']?>,true);
                    	  },
                    	  error: function (jqXhr, textStatus, errorMessage) {
                        	  console.log(errorMessage);
                    	  }
                    	});
            		});
                <?php
 	           }
 	       }
 	   }
   ?>          

   function checkDenetciTarih(grup,kod,belgeId){
        if ($('#chk_dt_'+grup+'_'+kod+'_'+belgeId).prop("checked") == true){
    		$('#chk_dt_'+grup+'_'+kod+'_'+belgeId).val("E");
        }else{
    		$('#chk_dt_'+grup+'_'+kod+'_'+belgeId).val("H");   
        }
    }

    function chckMusteriTarihi(grup,kod,belgeId){
        if ($('#chk_mt_'+grup+'_'+kod+'_'+belgeId).prop("checked") == true){
    		$('#chk_mt_'+grup+'_'+kod+'_'+belgeId).val("E");
        }else{
    		$('#chk_mt_'+grup+'_'+kod+'_'+belgeId).val("H");   
        }
    }
    
    function sayfaYukle(grup,kod){
    	prosedurNot(grup+'_'+kod);
		belgeListesiGetir(grup,kod,true);
	}
    
	function belgeListesiGetir(grup,kod,list){
		var table = $('#tableRisk');
	    table.find("tbody tr").remove();
	    $.post( "<?php echo PREPATH.'post/veriodasiPost.php?tur=veriodasiGetir'?>",
    		{
	    		tklf_id	: <?=$tklf_id ?>,
				grup	: grup,
				kod		: kod
	    	},
    	    function(data, status){
    			if(status == "success"){
    				var objx = JSON.parse(data);
    			    if (objx.hata == false) {
    					formuDoldur(grup,kod,list,objx.icerik);
    			    }
    			}else if(status == "error"){
        		    hataAc("Bir sorun oluştu.");
        	    }
    	    }
	    );
    }

	async function formuDoldur(grup,kod,list,obj){
		if (list) {
			await belgeListesiBelgeler(grup,kod);
		}
	    if (obj != null && obj != ""){
		   	obj.forEach(function(item){
			    $('#formid_'+grup+'_'+kod+'_'+item.risk_belge_id).			val(item.id);
			    $('#musteri_tarih_'+grup+'_'+kod+'_'+item.risk_belge_id).	val(formatTarihforForm(item.musteri_tarihi));
			    $('#denetci_tarih_'+grup+'_'+kod+'_'+item.risk_belge_id).	val(formatTarihforForm(item.denetci_tarihi));
			    $('#aciklama_'+grup+'_'+kod+'_'+item.risk_belge_id).		val(item.aciklama);
			    $('#chk_mt_'+grup+'_'+kod+'_'+item.risk_belge_id).			val(item.musteri_bitir);
				$('#chk_dt_'+grup+'_'+kod+'_'+item.risk_belge_id).			val(item.denetci_onay);
				$('#chk_mt_'+grup+'_'+kod+'_'+item.risk_belge_id).prop("checked", item.musteri_bitir == 'E' ? true : false);
			    $('#chk_dt_'+grup+'_'+kod+'_'+item.risk_belge_id).prop("checked", item.denetci_onay == 'E' ? true : false);

			    disableAcKapa(grup,kod,item.risk_belge_id,item);
		    });
	    }
    }

	async function belgeListesiBelgeler(grup,kod){
    	$('[id^=belgeList_'+grup+'_'+kod+']').find("tr").remove();
    	await $.post( "<?php echo PREPATH.'post/veriodasiPost.php?tur=veriodasiBelgeGetir'?>",
    		{
	    		tklf_id	: <?=$tklf_id ?>,
				grup	: grup,
				kod		: kod,
				link	: <?="'".$link."'" ?>
	    	},
    	    function(data, status){
    			if(status == "success"){
    			    var objx = JSON.parse(data);
    			    if (objx.hata == false) {
        			    var obj = objx.icerik;
        			    obj.forEach(function(item){
            				var table = $('#belgeList_'+grup+'_'+kod+'_'+item.belge_id);
                    	    item.belge.forEach(function(itemx){
								var str =
                        	    '<tr>'+
								'<td style="width: 25%" class="text-center">'+itemx.name+'</td>'+
								'<td style="width: 25%" class="text-center"><a href="'+itemx.url+'"	class="btn btn-primary " ><i class="fas fa-cloud-download-alt"></i></a></td>'+
								'<td style="width: 25%" class="text-center"><a href="'+itemx.web+'"	class="btn btn-primary " target="_blank" ><i class="fas fa-external-link-alt"></i></i></a></td>'+
								'<td style="width: 25%" class="text-center"><a href="#" onclick="belgeSil(\''+itemx.key+'\','+item.id+','+grup+','+kod+')" class="btn btn-primary disabled sil_'+grup+'_'+kod+'_'+item.belge_id+'" ><i class="fas fa-times"></i></input></td>'+
				      			'</tr>';
                        	    table.append(str);
                    	    });
                    	    
        			    });
        		    }
    			}else if(status == "error"){
        		    hataAc("Bir sorun oluştu.");
        	    }
    	    }
	    );
    }

    function disableAcKapa(grup,kod,belgeId,item){
    	if (<?=$denetciMi == 'E' ? 'true' : 'false' ?>) {
    		$('#pNot_'+grup+'_'+kod+'_'+belgeId).removeClass("disabled");
    		$('#aciklama_'+grup+'_'+kod+'_'+belgeId).prop( "disabled", false );
    		if (item.musteri_bitir == 'H' && item.denetci_onay == 'H') {
    			$('#denetci_tarih_'+grup+'_'+kod+'_'+belgeId).prop( "disabled", false );
			}
			if (item.musteri_bitir == 'E') {
    			$('#chk_dt_'+grup+'_'+kod+'_'+belgeId).prop( "disabled", false );
			}
    	}

    	if (<?=$musteriMi == 'E' ? 'true' : 'false' ?>) {
        	if (item.denetci_onay == 'H') {
        		$('#chk_mt_'+grup+'_'+kod+'_'+belgeId).prop( "disabled", false );
			}
    	}

		if ((<?=$musteriMi == 'E' ? 'true' : 'false' ?> && item.musteri_bitir == 'H') ||  <?=$denetciMi == 'E' ? 'true' : 'false' ?>) {
			$('.sil_'+grup+'_'+kod+'_'+belgeId).removeClass("disabled");
			$('#upload_'+grup+'_'+kod+'_'+belgeId).removeClass("disabled").removeClass("btn-secondary");
			$('#upload_'+grup+'_'+kod+'_'+belgeId).addClass("btn-success");
		}else{
			$('.sil_'+grup+'_'+kod+'_'+belgeId).addClass("disabled");
			$('#upload_'+grup+'_'+kod+'_'+belgeId).addClass("disabled").addClass("btn-secondary");
			$('#upload_'+grup+'_'+kod+'_'+belgeId).removeClass("btn-success");
		}
		
    }
    
	function formKaydet(adi,grup,kod,belgeId){
		$.post("<?=PREPATH.'post/veriodasiPost.php?tur=veriodasiProsedurEkle'?>",
	        {
	    		id				: $('#formid_'+grup+'_'+kod+'_'+belgeId).val(),
	    		adi				: adi,
				grup   			: grup,
				kod   			: kod,
				tklf_id   		: <?=$tklf_id ?>,
				risk_belge_id   : belgeId,
				link			: <?="'".$link."'" ?>,
				denetci_tarihi  : $('#denetci_tarih_'+grup+'_'+kod+'_'+belgeId).val(),
				aciklama   		: $('#aciklama_'+grup+'_'+kod+'_'+belgeId).val(),
				musteri_bitir	: $('#chk_mt_'+grup+'_'+kod+'_'+belgeId).val(),
				denetci_onay	: $('#chk_dt_'+grup+'_'+kod+'_'+belgeId).val()
		    },
		    function(data,status){
	    		if(status == "success"){
	    		    var obj = JSON.parse(data);
	    		    if (obj.hata == true) {
	    				hataAc(obj.hataMesaj);
	    		    }else{
	    		    	onayAc(obj.icerik);
	    		    	belgeListesiGetir(grup,kod,false);
	    		    }
	    		}else if(status == "error"){
	    		    hataAc("Bir sorun oluştu.");
	    	    }
		    }
	    );
	}

	function belgeSil(driveId,belgeId,grup,kod){
		$.post("<?=PREPATH.'post/veriodasiPost.php?tur=belgeSil'?>",
	        {
	    		driveId	: driveId,
	    		belgeId : belgeId,
				link	: <?="'".$link."'" ?>,
		    },
		    function(data,status){
	    		if(status == "success"){
	    		    var obj = JSON.parse(data);
	    		    if (obj.hata == true) {
	    				hataAc(obj.hataMesaj);
	    		    }else{
	    		    	onayAc(obj.icerik);
	    		    	belgeListesiBelgeler(grup,kod);
	    		    }
	    		}else if(status == "error"){
	    		    hataAc("Bir sorun oluştu.");
	    	    }
		    }
	    );
	}
</script>
<?php include (PREPATH.'front/js/prosedur.js.php'); ?>
<?php include (PREPATH.'footer.php'); ?>