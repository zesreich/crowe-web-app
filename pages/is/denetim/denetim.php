<?php
$pId = 240;
include_once '../../../First.php';
include_once PREPATH.'config/mkConfig.php';
include_once PREPATH.'soa/mkSoa.php';
include_once PREPATH.'soa/denetimSoa.php';
include_once PREPATH.'soa/driveSoa.php';
include_once PREPATH.'config/planRiskProsedurConfig.php';

$tklf_id = $_GET['id'];
$link = 'pages/is/denetim/denetim.php?id='.$tklf_id;
driveSoa::baglan($link);

include_once PREPATH . 'header.php';

$tklf   = Crud::getById(new Denetim(),$tklf_id) -> basit();
$riskler= denetimSoa::denetimCiddiRiskleriGetirWithDosyaLink($tklf_id);

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
	var ele = $("#"+kod+"_"+id+"_sonuc");
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

var durumlar = new Map();

</script>
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
                    	<a class="nav-item nav-link mk mr-1 text-center" data-toggle="tab" href="#nav-<?=$key?>" id="<?=$key?>_btn" role="tab" href="#<?=$key?>" onclick="riskYukle('<?=$key?>')" ><?=$key ?></a>
                    	<?php }?>
                  </div>
                </nav>
                <div class="border">
                    <div class="tab-content m-3" id="nav-tabContent">
                    	<div class="tab-pane fade show active"	id="nav-mkh" 	role="tabpanel" ></div>
                    	<?php
                    	   foreach ($riskler as $key => $value){
                    	       echo '<div class="tab-pane fade" id="nav-'.$key.'" role="tabpanel" >';
                    	       $prosedurKod = $key;
                    	       $prosedurs   = $value['kods'];
                    	       foreach ($value['kods'] as $p){
                    	           include 'denetim_prosedur.php';
                    	       }
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
    <?php 
    $arr = '';
    $ar  = '';
    foreach ($riskler as $key => $value){
        foreach ($value['kods'] as $p){
            $ar = ($ar != '' ? $ar = $ar .',' : ''  ) ."[". $p['id'].",'".$p['rpKod']."']" ;
        }
        $arr   = ($arr     != '' ? $arr    = $arr  . ',' : '')."'".$key."': [".$ar."]";
    }
	echo 'var pList = {'.$arr.'};';
	
    ?>
    function riskYukle(grup){
    	riskDosya(grup);
    	prosedurNot(grup);
    	prosedurRefs(grup);
    	//prosedurRisk(grup);
    }

    function riskButonEngelleme(grup,kod){
        var durum = durumlar.get('refsDurum'+grup+kod);
    	if (durum == '<?= planRiskProsedurConfig::DURUM_TAMAMLANDI[0] ?>') {
    		$('#refsEkle_'+grup+'_'+kod).addClass("disabled");
    	}else{
    		$('#refsEkle_'+grup+'_'+kod).removeClass("disabled");
    	}
    }
    
    function veriodasiAc(grup,kod){
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
    					veriodasiDetay(objx.icerik,grup,kod);
    			    }
    			}else if(status == "error"){
        		    hataAc("Bir sorun oluştu.");
        	    }
    	    }
        );
    }

    async function veriodasiDetay(obj,grup,kod){
    	$("#dialogverioda").empty();
    	var rslt = '';
    	for (var i = 0; i < obj.length; i++) {
    		item = obj[i];
    		rslt = rslt + 
    	        '<div class="card shadow mb-1">'+
    	            '<div class="card-header">'+
    	            	'<h6 class="m-0 font-weight-bold text-primary">'+belgeList[item.risk_belge_id]+'</h6>'+
    	            '</div>'+
    	            '<div class="row">'+
    	            	'<div class="col-xl-5 col-lg-12">'+
    	            		'<div class="card-body">'+
    	                		'<table class="table table-bordered table-striped " >'+
    	                			'<thead>'+
    	                				'<tr>'+
    	                					'<th class="bg-gray-700 text-gray-200 text-center align-middle" style="width:50%">Adı</th>'+
    	                					'<th class="bg-gray-700 text-gray-200 text-center align-middle" style="width:25%">İndir</th>'+
    	                					'<th class="bg-gray-700 text-gray-200 text-center align-middle" style="width:25%">Göster</th>'+
    	                				'</tr>'+
    	                			'</thead>'+
    	                			'<tbody  id="belgeList_'+item.id+'" >'+
    	        					'</tbody>'+
    	                		'</table>'+
    	            		'</div>'+
    	            	'</div>'+
    	            	'<div class="col-xl-7 col-lg-12">'+
    	            		'<div class="card-body">'+
    	                		'<table id="xx" class="table table-bordered table-striped " >'+
    	                			'<tbody>'+
    	                				'<tr>'+
    	                    				'<th colspan="2" class="bg-gray-700 text-gray-200 text-center align-middle">Müşteri</th>'+
    	                    				'<th colspan="2" class="bg-gray-700 text-gray-200 text-center align-middle">Denetçi</th>'+
    	                				'</tr>'+
    	                				'<tr>'+
    	                    				'<td class="text-center align-middle" style="width:35%">Tarih</td>'+
    	                    				'<td class="text-center align-middle" style="width:15%">Bitir</td>'+
    	                    				'<td class="text-center align-middle" style="width:35%">Termin</td>'+
    	                    				'<td class="text-center align-middle" style="width:15%">Onay</td>'+
    	                				'</tr>'+
    	                				'<tr>'+
    	                    				'<td class="text-center align-middle" >'+(item.musteri_tarihi == null ? '' : formatSaatTarihforForm(item.musteri_tarihi))+'</td>'+
    	                    				'<td class="text-center align-middle" ><input disabled '+(item.musteri_bitir == 'E' ? 'checked' : '' )+' type="checkbox" ></td>'+
    	                    				'<td class="text-center align-middle" >'+(item.denetci_tarihi == null ? '' : formatSaatTarihforForm(item.denetci_tarihi))+'</td>'+
    	                    				'<td class="text-center align-middle" ><input disabled '+(item.denetci_onay == 'E' ? 'checked' : '' )+' type="checkbox" ></td>'+
    	                				'</tr>'+
    	                				'<tr>'+
    	                					'<td colspan="4"><textarea disabled rows="2" class="form-control" >'+item.aciklama+'</textarea></td>'+
    	                				'</tr>'+
    	                			'</tbody>'+
    	                		'</table>'+
    	            		'</div>'+
    	            	'</div>'+
    	            '</div>'+
    	        '</div>';
    	}
    	$("#dialogverioda").append(rslt);
    	await belgeListesiBelgeler(grup,kod);
    }
    
    async function belgeListesiBelgeler(grup,kod){
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
            				var table = $('#belgeList_'+item.id);
                    	    item.belge.forEach(function(itemx){
    							var str =
                        	    '<tr>'+
    							'<td class="text-center">'+itemx.name+'</td>'+
    							'<td class="text-center"><a href="'+itemx.url+'"	class="btn btn-primary " ><i class="fas fa-cloud-download-alt"></i></a></td>'+
    							'<td class="text-center"><a href="'+itemx.web+'"	class="btn btn-primary " target="_blank" ><i class="fas fa-external-link-alt"></i></i></a></td>'+
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


    function sonuclandirAc(id){
    	$.post( "<?php echo PREPATH.'post/riskDenetimPost.php?tur=getById'?>",
    		{
    			id	: id
        	},
    	    function(data, status){
    			if(status == "success"){
    				var objx = JSON.parse(data);
    			    if (objx.hata == false) {
    				    var item = objx.icerik;
    				    var blg = null;
    					if (item.bulgu_tutar == 0){
    						blg = 'H';
    					}else if (item.bulgu_tutar > 0){
    						blg = 'E';
    					}
    				    $('#chk_kanit_'+item.id).	val(item.kanit_varmi);
    		    		$('#chk_bulgu_'+item.id).	val(blg);
    		    		$('#bulgu_tutar_'+item.id).	val(item.bulgu_tutar);
    		    		$('#aciklama_'+item.id).	val(item.sonuc_aciklama);
    		    		kanitSelect(item.id);
    			    }
    			}else if(status == "error"){
        		    hataAc("Bir sorun oluştu.");
        	    }
    	    }
        );
    }

    function sonuclandirKaydet(id){
    	if ($('#chk_bulgu_'+id).val() == 'E' && $('#bulgu_tutar_'+id).val() == '') {
    		hataAc("'Bulgunun Tutarı' boş olamaz.");
    	}else if ($('#chk_bulgu_'+id).val() == 'E' && $('#bulgu_tutar_'+id).val() == '0'){
    		hataAc("'Bulgunun Tutarı' 0 girilemez.");
    	}else{
        	$.post( "<?php echo PREPATH.'post/riskDenetimPost.php?tur=kaydet'?>",
        		{
        			id		: id,
        			kanit 	:$('#chk_kanit_'+id).val(),
        			tutar 	:$('#bulgu_tutar_'+id).val(),
        			aciklama:$('#aciklama_'+id).val()
            	},
        	    function(data, status){
        			if(status == "success"){
        				var objx = JSON.parse(data);
        				var item = objx.icerik;
        			    if (objx.hata == false) {
        			    	durumlar.set('refsDurum'+item.risk_prosedur_id.risk_id.kod+item.risk_prosedur_id.kod, item.durum_id );
        			    	riskButonEngelleme(item.risk_prosedur_id.risk_id.kod,item.risk_prosedur_id.kod);
            			    onayAc('Düzenlendi.');
        			    }else{
        			    	hataAc(objx.hataMesaj);
        			    }
        			}else if(status == "error"){
            		    hataAc("Bir sorun oluştu.");
            	    }
        	    }
            );
    	}
    }

    function kanit(id){
    	kanitSelect(id);
    }

    function bulgu(id){
    	bulguSelect(id);
    }

    function kanitSelect(id){
        if ($('#chk_kanit_'+id).val() != 'E') {
    		$('#chk_bulgu_'+id).prop( "disabled", true );
        	$('#chk_bulgu_'+id).val("");
    		$('#bulgu_tutar_'+id).prop( "disabled", true );
        	$('#bulgu_tutar_'+id).val(null);
    	}else{
    		$('#chk_bulgu_'+id).prop( "disabled", false );
    		$('#bulgu_tutar_'+id).prop( "disabled", false );
    	}
        bulguSelect(id);
    }

    function bulguSelect(id){
        if ($('#chk_bulgu_'+id).val() != 'E') {
    		$('#bulgu_tutar_'+id).prop( "disabled", true );
    		if ($('#chk_bulgu_'+id).val() == 'H') {
    			$('#bulgu_tutar_'+id).val(0);	
    		}else{
        		$('#bulgu_tutar_'+id).val(null);
    		}
    	}else{
    		$('#bulgu_tutar_'+id).prop( "disabled", false );
    	}
    }
</script>
<?php include (PREPATH.'front/js/prosedur.js.php'); ?>
<?php include (PREPATH.'footer.php'); ?>