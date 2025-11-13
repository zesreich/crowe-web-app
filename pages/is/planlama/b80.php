<style>
    .rotalar{
        writing-mode: vertical-lr;
        text-align: center;
        vertical-align: middle;
    }
</style>

<div id="b80init"></div>

<div class="modal fade" id="myModalProsedur" role="dialog">
	<div class="modal-dialog modal-xl">
		<div class="modal-content">
			<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 mb-12">
				<div class="card" style="min-height:395px;">
					<div class="card-block">
						<div id="txtHintPRisk"></div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">

    function b80Init(id){
    	$.get( "<?php echo PREPATH.'post/planlama/b80Post.php?tur=b80Init&tklf_id='?>"+id, function(data, status){
    		if(status == "success"){
    			var objx = JSON.parse(data);
    			var table = $('#b80init');
    	        table.find("div").remove();
    			for (const [key, value] of Object.entries(objx.icerik)) {
        			var item = value; 

        			var cRisk = '';
        			for (var i = 0; i < item.kods.length; i++) {
            			var kod = item.kods[i];
            			cRisk +=
            			'<div class="col-2 border ">'+
        				'<div class="card-body">'+
        				'<div class=" mb-0 text-gray-800 text-center">'+kod+'</div>'+
        				'</div>'+
        				'</div>';
            		}
        			for (var i = 0; i < 6-item.kods.length; i++) {
        				cRisk += '<div class="col-2 border text-center text-white" >&nbsp;<br/>&nbsp;</div>';
        			}
        			
    				var dgr = 
    					'<div class="card-body" id = "r_'+item.rkod+'">'+
            				'<div class="border">'+
            			       	'<div class="row py-3 ">'+
            			        	'<div class="col justify-content-center" >'+
            			        		'<a href="#" onclick="rikProsedurEkle('+item.id+')" data-toggle="modal" data-target="#myModalProsedur" class="mx-2 btn btn-primary float-right">'+ 
            			        			'<i class="fas fa-plus"></i>'+
            			        		'</a>'+
            			        		'<div class="h5 pl-5 font-weight-bold text-gray-800">'+item.rkod+') '+item.adi+'</div>'+
            			            '</div>'+
            			  		'</div>'+
            			        '<div class="row m-0">'+
            			        	'<div class="col-6  my-0 py-0">'+
            			        		'<div class="row">'+
            			                    '<div class="col-12">'+
            			                        '<div class="card shadow">'+
            			                            '<div class="card-header ">'+
            			                            	'<h6 class="font-weight-bold ">Ciddi Risk Dayanağı</h6>'+
            			                            '</div>'+
            			                            '<div class="card-body">'+
            			                                '<div class="row">'+
            			                                	cRisk+
            			                                '</div>'+
            			                            '</div>'+
            			                        '</div>'+
            			                    '</div>'+
            			                	'<div class="col-12">'+
            			                    	'<div class="card shadow ">'+
            			                    		'<div class="card-body">'+
            			                    			'<div class="row p-0">'+
            			                    				'<div class="col-2 font-weight-bold text-gray-800 align-self-center  text-right">Açıklama :</div>'+
            			                                    '<div class="col-7"><textarea rows="1" class="form-control" id="proAcik_'+item.pid+'" >'+(item.b80Aciklama == null ? '' : item.b80Aciklama )+'</textarea></div>'+
            			            						'<div class="col-3 align-self-center"><button type="button" class="btn btn-primary col-lg-12" onclick="proAcikKaydet('+item.pid+');">Kaydet</button></div>'+
            			                            	'</div>'+
            			                    		'</div>'+
            			                    	'</div>'+
            			                    '</div>'+
            			        		'</div>'+
            			        	'</div>'+
            			    		'<div class="col-6 pl-0">'+
            							'<table class="table table-bordered ">'+
            			               		'<thead>'+
            			               			'<tr>'+
            			               				'<th colspan="4"><?=mkConfig::RISK_ALT_B ?></th>'+
            			               				'<th colspan="5"><?=mkConfig::RISK_ALT_G ?></th>'+
            			               				'<th colspan="4"><?=mkConfig::RISK_ALT_S ?></th>'+
            			               			'</tr>'+
            			                        '<tr>'+
            			            				<?php 
            			            				foreach (mkConfig::RISK_ALTLAR as $v){
            			                                echo '\'<th scope="col "><div class="rotalar" style="font-size: 13px;" >'.$v[1].'</div></th>\'+';
            			            				}
            			            				?>
            			                        '</tr>'+
            			                      '</thead>'+
            			                  '</table>'+
            			              '</div>'+
            			              '<div id="risk_prosedur_'+item.rkod+'" class="p-2 proTemizle" style="width: 100%;">'+
            			            '</div>'+
            			        '</div>'+
            			    '</div>'+
            			'</div>';
                    table.append(dgr);
    			}
    			detayAc (<?=$tklf_id ?>);
    		}else if(status == "error"){
    		    hataAc("Bilgi çekilemedi.");
    	    }
    	});
    }

<?php 
    $kaynaklar = '';
    foreach (mkConfig::RISK_PRO_KAYNAKLAR as $k){
        $kaynaklar .= ($kaynaklar   == '' ? "'".$k."'" : ",'".$k."'");
    }
    echo 'var kaynaklar = ['.$kaynaklar.'];';
    
    $duzeyler = '';
    foreach (mkConfig::RISK_PRO_DUZEYLER as $k){
        $duzeyler .= ($duzeyler   == '' ? "'".$k."'" : ",'".$k."'");
    }
    echo 'var duzeyler = ['.$duzeyler.'];';
    
    $riskAltList = '';
    foreach (mkConfig::RISK_ALTLAR as $riskAlt){
        $riskAltList .= ($riskAltList   == '' ? "'".$riskAlt[0]."'" : ",'".$riskAlt[0]."'");
    }
    echo 'var raList = ['.$riskAltList.'];';
?>



    function detayAc(tklf_id){
        $.get( "<?php echo PREPATH.'post/planlama/b80Post.php?tur=prosedurGetir&tklf_id='?>"+tklf_id, function(data, status){
    		if(status == "success"){
        		var prolar = $(".proTemizle");
        		for (var i = 0; i < prolar.length; i++) {
            		prolar.empty();
        		}
    		    var objx = JSON.parse(data);
    		    if (objx.icerik != null && objx.icerik != ""){
    		    	for (var x = 0; x < objx.icerik.length; x++) {
        		    	var item = objx.icerik[x];
    		    		var ral = kay = duz = '';
						for (var i = 0; i < raList.length; i++) {
							ral +='<td class="text-center col" >'+(item[raList[i]] == 'E' ? 'O' : 'X' )+'</td>';    	
						}
						for (var i = 0; i < kaynaklar.length; i++) {
							kay += '<option class="dznl_val" value="'+kaynaklar[i]+'"'+ ((kaynaklar[i] == item.kaynak) ? 'selected="selected"' : '') +'>'+kaynaklar[i]+'</option>';
						}
						
						for (var i = 0; i < duzeyler.length; i++) {
							duz += '<option class="dznl_val" value="'+duzeyler[i]+'"'+ ((duzeyler[i] == item.duzey) ? 'selected="selected"' : '') +'>'+duzeyler[i]+'</option>';
						}
        		    	var snc =
        		    		'<div class="col-12 p-0 m-0 prolar" id="ps_'+item.risk_prosedur_id+'">'+
        	        			'<div class="row m-0 p-0 border" >'+
        	    					'<div class="col-3 mt-2  align-self-center">'+
        	        					'<div style="font-size: 13px;" ><b> <a href="#" onclick="riskProSil('+item.id+');" class="btn" ><i class="fas fa-minus-square" style="color: #ff4444;"></i></a>      '+item.p_kod+' )</b> '+item.p_adi+'</div>'+
        	        				'</div>'+
        	    					'<div class="col-3 mt-2 align-self-center">'+    						
        	        					'<div class="row">'+
        	                            	'<div class="col-lg-6">'+
        	                            		'<select class=" custom-select form-control " onchange="riskProGuncelle('+item.id+');" id="proKaynak_'+item.id+'" >'+
        	                            			'<option class="dznl_val" value="" '+ ((item.kaynak == null ) ? 'selected="selected"' : '') +'  >Seçiniz</option>'+
        	                            			kay+
        	                                    '</select>'+
        	                        		'</div>'+
        	                            	'<div class="col-lg-6">'+
        	                            		'<select class=" custom-select form-control " onchange="riskProGuncelle('+item.id+');" id="proDuzey_'+item.id+'">'+
        	                            			'<option class="dznl_val" value="" '+ ((item.duzey == null ) ? 'selected="selected"' : '') +' >Seçiniz</option>'+
        	                            			duz+
        	                                    '</select>'+
        	                        		'</div>'+
        	        					'</div>'+
        	        				'</div>'+
        	        				'<div class="col-6 px-0 align-self-center">'+
        	            				'<table class="table table-bordered align-self-center m-0">'+
        	                                  '<tbody>'+
        	                                    '<tr class="row m-0">'+
        	                                    	ral+
        	                                    '</tr>'+
        	                                '</tbody>'+
        	                            '</table>'+
        	                        '</div>'+
        	                	'</div>'+
        	            	'</div>';
        		    	$('#risk_prosedur_'+item.r_kod).append(snc);
        			}
    		    }
    		}else if(status == "error"){
    		    hataAc("Bilgi çekilemedi.");
    	    }
    	});
    }

	function rikProsedurEkle(id){ // elleme
    	$.get( "<?php echo PREPATH.'pages/genel/riskProsedurAra.php?risk='?>"+id, function(data, status){
    		if(status == "success"){
    			$("#txtHintPRisk").empty();
    			$("#txtHintPRisk").append(data);
    		}else if(status == "error"){
    		    hataAc("Bilgi çekilemedi.");
    	    }
    	});
	}

	function riskProSil(id){ //elleme
		var c = confirm("Silmek istediğinize emin misiniz? İçerisinde dosyalar olabilir.");
		loadEkranAc();
		if (c) {
            $.post("<?=PREPATH.'post/planlama/b80Post.php?tur=prosedurSil' ?>",
                {
            		id : id, 
            		link : '<?=$link?>',
        	    },
        	    function(data,status){
            	    console.log(data);
            		if(status == "success"){
            			detayAc(<?=$tklf_id ?>);
            		}else if(status == "error"){
            		    hataAc("Bir sorun oluştu.");
            	    }
            		loadEkranKapat();
        	    }
            );
		}
	}

	function riskProEkle(id){ //elleme
		var prolar = $(".prolar");
		for (var i = 0; i < prolar.length; i++) {
            if (prolar[i].id.substring(3) == id){
    			hataAc("Bilgi çekilemedi.");
                return; 
            }
		}
		loadEkranAc();
        $.post("<?=PREPATH.'post/planlama/b80Post.php?tur=prosedurEkle' ?>",
            {
        		tklf_id 			: <?=$tklf_id ?>, 
				risk_prosedur_id 	: id,
				link 				: '<?=$link?>',
    	    },
    	    function(data,status){
    	    	if(status == "success"){
	    		    var obj = JSON.parse(data);
	    		    if (obj.hata == true) {
	    				hataAc(obj.hataMesaj);
	    		    }else{
	    		    	detayAc(<?=$tklf_id ?>);
	    		    }
	    		    loadEkranKapat();
    		    }else if(status == "error"){
        		    hataAc("Bir sorun oluştu.");
        		    loadEkranKapat();
        	    }
    	    }
        );
    }

	function riskProGuncelle(id){
        $.post("<?=PREPATH.'post/planlama/b80Post.php?tur=prosedurGuncelle' ?>",
            {
            	id		: id,
        		kaynak 	: $('#proKaynak_'+id).val(), 
        		duzey 	: $('#proDuzey_'+id).val(),
    	    },
    	    function(data,status){
        		if(status == "success"){
        			detayAc(<?=$tklf_id ?>);
        		}else if(status == "error"){
        		    hataAc("Bir sorun oluştu.");
        	    }
    	    }
        );
    }

	function proAcikKaydet(id){ // elleme
        $.post("<?=PREPATH.'post/planlama/b80Post.php?tur=proAciklamaKaydet' ?>",
            {
            	id		: id,
        		acik 	: $('#proAcik_'+id).val(), 
    	    },
    	    function(data,status){
        		if(status == "success"){
        			onayAc('Kayit tamamlandı.');
        		}else if(status == "error"){
        		    hataAc("Bir sorun oluştu.");
        	    }
    	    }
        );
    }

</script>