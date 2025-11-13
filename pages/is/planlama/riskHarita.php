<?php
$pId = 219;
include_once '../../../First.php';
include_once PREPATH.'soa/driveSoa.php';

$link = 'pages/is/planlama/riskHarita.php';
driveSoa::baglan($link);

include_once PREPATH . 'header.php';

$tbl = new RiskListesi();
?>
<style>
    ul ul {
        min-height: 2em;
    }
    .form-check-label{
        width: 1.25rem;
        height: 1.25rem;
    }
    .table-responsive{
        max-height: 50vh;
    }
</style>
<div class="row">

	<div class="col-lg-6 col-xl-4 pb-3" >
		<div class="row">
			<div class="col-12 pb-3" >
                <div class="card shadow">
        			<div class="card-header py-3 d-flex flex-row align-items-center justify-content-between bg-gradient-primary">
        				<h6 class="m-0 font-weight-bold text-gray-300">Risk</h6>
        				<div class="dropdown no-arrow">
        					<a id="riskEkleBtn" href="#" class="btn btn-warning float-right"> <i
        						class="fas fa-plus"></i>
        					</a>
        				</div>
        			</div>
        			<div class="card-body">
                    	<div class="table-responsive">
                    		<input id="search" type="text" class="form-control form-control-user"  placeholder="Arama">
                    		<br>
                    		<table id="tableRisk" class="table table-bordered table-striped" >
                    			<thead>
                    				<tr>
                    					<th class="bg-gray-700 text-gray-200 text-center align-middle">Id  </th>
                    					<th class="bg-gray-700 text-gray-200 text-center align-middle">Yetki Adı  </th>
                    				</tr>
                    			</thead>
                    			<tbody>
                    			</tbody>
                    		</table>
                    	</div>
                    </div>
                </div>
            </div>
            <div class="col-12 pb-3">
                <div class="card shadow">
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between bg-gradient-primary">
        				<h6 class="m-0 font-weight-bold text-gray-300">Prosedür</h6>
        				<div class="dropdown no-arrow">
        					<a id="prosedurEkleBtn" href="#" class="btn btn-warning float-right"> <i
        						class="fas fa-plus"></i>
        					</a>
        				</div>
        			</div>
                    <div class="card-body">
                    	<div class="table-responsive">
                    		<input id="searchPro" type="text" class="form-control form-control-user"  placeholder="Arama">
                    		<br>
                    		<table id="tableProsedur" class="table table-bordered table-striped" >
                    			<thead>
                    				<tr>
                    					<th class="bg-gray-700 text-gray-200 text-center align-middle">Id  </th>
                    					<th class="bg-gray-700 text-gray-200 text-center align-middle">Adı  </th>
                    				</tr>
                    			</thead>
                    			<tbody>
                    			</tbody>
                    		</table>
                    	</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-6 col-xl-4" id="riskDuzen">
        <div class="card shadow" >
        	<div class="card-header bg-gradient-primary py-3">
            	<h6 class="m-0 font-weight-bold text-gray-300">Risk Düzenleme</h6>
            </div>
            <div class="card-body">
                <form class="user">
                    <div class="row mb-2">
                		<div class="col-lg-4 font-weight-bold text-gray-800 align-self-center text-right">Id :</div>
                		<div class="col-lg-8"><input type="text"  id="dznl_risk_id" class="form-control form-control-user" disabled></div>
                	</div>
                    <div class="row mb-2">
                		<div class="col-lg-4 font-weight-bold text-gray-800 align-self-center  text-right">Kod :</div>
                		<div class="col-lg-8"><input type="text" class="form-control form-control-user" id="dznl_risk_kod" ></div>
                	</div>
                    <div class="row mb-2">
                		<div class="col-lg-4 font-weight-bold text-gray-800 align-self-center  text-right">Adı :</div>
                		<div class="col-lg-8"><input type="text" class="form-control form-control-user" id="dznl_risk_ad" ></div>
                	</div>
                	<div class="row mb-2">
                		<div class="col-lg-4 font-weight-bold text-gray-800 align-self-center  text-right">Aktif</div>
                		<div class="col-lg-8 ">
                    		<select class=" custom-select form-control " id="dznl_risk_aktif">
                    			<option class="dznl_val" value="" selected="selected">Seçiniz</option>
                    			<option class="dznl_val" value="E">Aktif</option>
                    			<option class="dznl_val" value="H">Pasif</option>
                            </select>
                		</div>
                	</div>
                    <div class="row mb-2">
                		<div class="col-lg-4 font-weight-bold text-gray-800 align-self-center  text-right">Oluşturulma Tarihi :</div>
                		<div class="col-lg-8"><input type="text" class="form-control form-control-user" id="dznl_risk_create_gmt" disabled></div>
                	</div>
                    <div class="row mb-2">
                		<div class="col-lg-4 font-weight-bold text-gray-800 align-self-center  text-right">Oluşturan Kişi :</div>
                		<div class="col-lg-8"><input type="text" class="form-control form-control-user" id="dznl_risk_create_user_id" disabled></div>
                	</div>
                    <div class="row mb-2">
                		<div class="col-lg-4 font-weight-bold text-gray-800 align-self-center  text-right">Son Düzenleme Tarihi :</div>
                		<div class="col-lg-8"><input type="text" class="form-control form-control-user" id="dznl_risk_gmt" disabled ></div>
                	</div>
                    <div class="row mb-2	">
                		<div class="col-lg-4 font-weight-bold text-gray-800 align-self-center  text-right">Son Düzenleyen Kişi :</div>
                		<div class="col-lg-8"><input type="text" class="form-control form-control-user" id="dznl_risk_user_id" disabled></div>
                	</div>
                    <div class="row pt-2">
                        <div id="dznl_risk_btn" class="col-lg-12 text-center">
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
    
    <div class="col-lg-6 col-xl-4" id="prosedurDuzen">
        <div class="card shadow" >
        	<div class="card-header bg-gradient-primary py-3">
            	<h6 class="m-0 font-weight-bold text-gray-300">Prosedür Düzenleme</h6>
            </div>
            <div class="card-body">
                <form class="user">
                	<input type="hidden" id="dznl_prosedur_riskid">
                    <div class="row mb-2">
                		<div class="col-lg-4 font-weight-bold text-gray-800 align-self-center text-right">Id :</div>
                		<div class="col-lg-8"><input type="text"  id="dznl_prosedur_id" class="form-control form-control-user" disabled></div>
                	</div>
                    <div class="row mb-2">
                		<div class="col-lg-4 font-weight-bold text-gray-800 align-self-center text-right">Risk :</div>
                		<div class="col-lg-8"><input type="text"  id="dznl_prosedur_riskadi" class="form-control form-control-user" disabled></div>
                	</div>
                    <div class="row mb-2">
                		<div class="col-lg-4 font-weight-bold text-gray-800 align-self-center  text-right">Kod :</div>
                		<div class="col-lg-8"><input type="text" class="form-control form-control-user" id="dznl_prosedur_kod" ></div>
                	</div>
                    <div class="row mb-2">
                		<div class="col-lg-4 font-weight-bold text-gray-800 align-self-center  text-right">Adı :</div>
                		<div class="col-lg-8"><input type="text" class="form-control form-control-user" id="dznl_prosedur_ad" ></div>
                	</div>
                	<div class="row mb-2">
                		<div class="col-lg-4 font-weight-bold text-gray-800 align-self-center  text-right">Aktif :</div>
                		<div class="col-lg-8 ">
                    		<select class=" custom-select form-control " id="dznl_prosedur_aktif">
                    			<option class="dznl_val" value="" selected="selected">Seçiniz</option>
                    			<option class="dznl_val" value="E">Aktif</option>
                    			<option class="dznl_val" value="H">Pasif</option>
                            </select>
                		</div>
                	</div>
                	<div class="row mb-2">
                		<div class="col-lg-4 font-weight-bold text-gray-800 align-self-center  text-right">Excel :</div>
                		<div class="col-lg-8 " id="drivelar"></div>
                	</div>
                	<div class="row my-2 py-2 border">
                		<div class="col-lg-4 font-weight-bold text-gray-800 align-self-center text-right">Bilanço :</div>
                		<div class="col-lg-8 ">
                			<div class="row">
                				<div class="col-lg-9 font-weight-bold text-gray-800 align-self-center  text-right">Var olma :</div>
                				<div class="col-lg-3"><div class="col-lg-8 align-self-center"><input type="checkbox" class="form-check-label " id="dzn_b_var"  value="H"></div></div>
                				<div class="col-lg-9 font-weight-bold text-gray-800 align-self-center  text-right">Haklar ve zorunluluklar :</div>
                				<div class="col-lg-3"><div class="col-lg-8 align-self-center"><input type="checkbox" class="form-check-label " id="dzn_b_haklar"  value="H"></div></div>
                				<div class="col-lg-9 font-weight-bold text-gray-800 align-self-center  text-right">Tamlık :</div>
                				<div class="col-lg-3"><div class="col-lg-8 align-self-center"><input type="checkbox" class="form-check-label " id="dzn_b_tamlik"  value="H"></div></div>
                				<div class="col-lg-9 font-weight-bold text-gray-800 align-self-center  text-right">Değerleme ve tahsis :</div>
                				<div class="col-lg-3"><div class="col-lg-8 align-self-center"><input type="checkbox" class="form-check-label " id="dzn_b_deger"  value="H"></div></div>
                			</div>
                		</div>
            		</div>
                	<div class="row mb-2 py-2 border">
                		<div class="col-lg-4 font-weight-bold text-gray-800 align-self-center  text-right">Gelir Tablosu :</div>
                		<div class="col-lg-8 ">
                			<div class="row">
                				<div class="col-lg-9 font-weight-bold text-gray-800 align-self-center  text-right">Meydana gelme :</div>
                				<div class="col-lg-3"><div class="col-lg-8 align-self-center"><input type="checkbox" class="form-check-label " id="dzn_g_meydan"  value="H"></div></div>
                				<div class="col-lg-9 font-weight-bold text-gray-800 align-self-center  text-right">Tamlık :</div>
                				<div class="col-lg-3"><div class="col-lg-8 align-self-center"><input type="checkbox" class="form-check-label " id="dzn_g_tamlik"  value="H"></div></div>
                				<div class="col-lg-9 font-weight-bold text-gray-800 align-self-center  text-right">Doğruluk :</div>
                				<div class="col-lg-3"><div class="col-lg-8 align-self-center"><input type="checkbox" class="form-check-label " id="dzn_g_dogru"  value="H"></div></div>
                				<div class="col-lg-9 font-weight-bold text-gray-800 align-self-center  text-right">Cutoff :</div>
                				<div class="col-lg-3"><div class="col-lg-8 align-self-center"><input type="checkbox" class="form-check-label " id="dzn_g_cutoff"  value="H"></div></div>
                				<div class="col-lg-9 font-weight-bold text-gray-800 align-self-center  text-right">Sınıflandırma :</div>
                				<div class="col-lg-3"><div class="col-lg-8 align-self-center"><input type="checkbox" class="form-check-label " id="dzn_g_sinif"  value="H"></div></div>
                			</div>
                		</div>
            		</div>
                	<div class="row mb-2 py-2 border">
                		<div class="col-lg-4 font-weight-bold text-gray-800 align-self-center  text-right">Sunum ve açıklama :</div>
                		<div class="col-lg-8 ">
                			<div class="row">
                				<div class="col-lg-9 font-weight-bold text-gray-800 align-self-center  text-right">Meydana gelme :</div>
                				<div class="col-lg-3"><div class="col-lg-8 align-self-center"><input type="checkbox" class="form-check-label " id="dzn_s_meydan"  value="H"></div></div>
                				<div class="col-lg-9 font-weight-bold text-gray-800 align-self-center  text-right">Tamlık :</div>
                				<div class="col-lg-3"><div class="col-lg-8 align-self-center"><input type="checkbox" class="form-check-label " id="dzn_s_tamlik"  value="H"></div></div>
                				<div class="col-lg-9 font-weight-bold text-gray-800 align-self-center  text-right">Sınıflandırma ve anlaşılabilirlik :</div>
                				<div class="col-lg-3"><div class="col-lg-8 align-self-center"><input type="checkbox" class="form-check-label " id="dzn_s_sinif"  value="H"></div></div>
                				<div class="col-lg-9 font-weight-bold text-gray-800 align-self-center  text-right">Doğruluk ve değerleme :</div>
                				<div class="col-lg-3"><div class="col-lg-8 align-self-center"><input type="checkbox" class="form-check-label " id="dzn_s_dogru"  value="H"></div></div>
                			</div>
                		</div>
            		</div>
                    <div class="row mb-2 py-2">
                		<div class="col-lg-4 font-weight-bold text-gray-800 align-self-center  text-right">Oluşturulma Tarihi :</div>
                		<div class="col-lg-8"><input type="text" class="form-control form-control-user" id="dznl_prosedur_create_gmt" disabled></div>
                	</div>
                    <div class="row mb-2">
                		<div class="col-lg-4 font-weight-bold text-gray-800 align-self-center  text-right">Oluşturan Kişi :</div>
                		<div class="col-lg-8"><input type="text" class="form-control form-control-user" id="dznl_prosedur_create_user_id" disabled></div>
                	</div>
                    <div class="row mb-2">
                		<div class="col-lg-4 font-weight-bold text-gray-800 align-self-center  text-right">Son Düzenleme Tarihi :</div>
                		<div class="col-lg-8"><input type="text" class="form-control form-control-user" id="dznl_prosedur_gmt" disabled ></div>
                	</div>
                    <div class="row mb-2	">
                		<div class="col-lg-4 font-weight-bold text-gray-800 align-self-center  text-right">Son Düzenleyen Kişi :</div>
                		<div class="col-lg-8"><input type="text" class="form-control form-control-user" id="dznl_prosedur_user_id" disabled></div>
                	</div>
                    <div class="row pt-2">
                        <div id="dznl_pro_btn" class="col-lg-12 text-center">
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
    
    <div class="col-lg-6 col-xl-4" id="veriOdaDuzen">
        <div class="card shadow" >
        	<div class="card-header py-3 d-flex flex-row align-items-center justify-content-between bg-gradient-primary">
				<h6 class="m-0 font-weight-bold text-gray-300">Veri Odası Dosyalar</h6>
				<div class="dropdown no-arrow">
					<a id="satirEkleBtn" href="#" class="btn btn-warning float-right"> <i class="fas fa-plus"></i></a>
				</div>
			</div>
            <div class="card-body">
        		<table class="table table-bordered table-striped " >
        			<thead>
        				<tr>
        					<th class="bg-gray-700 text-gray-200 text-center align-middle" style="width: 10%" >Sıra</th>
        					<th class="bg-gray-700 text-gray-200 text-center align-middle" style="width: 50%" >Belge Adı</th>
        					<th class="bg-gray-700 text-gray-200 text-center align-middle" style="width: 10%" >Aktif</th>
        					<th class="bg-gray-700 text-gray-200 text-center align-middle" style="width: 10%" ></th>
        				</tr>
        			</thead>
        			<tbody  id="veriOdasiList" >
        			</tbody>
        		</table>
            </div>
        </div>
    </div>
    
</div>



<script >

	$("#satirEkleBtn").click(function(){
		var belge = $("#belge_tr_0");
		if (belge.length == 0) {
			satırEkle({id: '0'});
		}else{
			hataAc("Zaten ekli");
		}
	});

	function satırEkle(obj){
		var table = $('#veriOdasiList');
		var str = '';
		str = str + ('<tr id="belge_tr_'+obj.id+'" >');
        str = str + ('	<input type="hidden" id="belge_id_'+obj.id+'" value="'+obj.id+'"/>');
        str = str + ('	<td class="text-center align-middle"><input id="belge_sira_'+obj.id+'" 	type="text"		class="form-control form-control-user" 	value="'+(obj.id != 0 ? obj.sira : '')+'" onkeypress="return isNumberKey(event)"	></td>');
        str = str + ('	<td class="text-center align-middle"><input id="belge_ad_'+obj.id+'"  	type="text" 	class="form-control form-control-user" 	value="'+(obj.id != 0 ? obj.adi : '')+'"></td>');
        str = str + ('	<td class="text-center align-middle"><input id="belge_aktif_'+obj.id+'"	type="checkbox" class="form-check-label " '+((obj.id != 0) && obj.aktif=='E' ? 'checked="true"' : '')+' onclick="belgeCheckSwitch('+obj.id+')" value="'+(obj.id != 0 ? obj.aktif : 'H')+'"></td>');
        str = str + ('	<td class="text-center align-middle">');
        str = str + ('		<a href="#" onclick="belgeKaydet('+obj.id+')" class="btn btn-primary" ><i class="fas fa-check"></i></a>');
        str = str + ('	</td>');
    	str = str + ('</tr>');
    	table.append(str);
	}

	function belgeCheckSwitch(id){
    	if($("#belge_aktif_"+id).prop("checked") == true){
    		$("#belge_aktif_"+id).val('E');
    	}else{
    		$("#belge_aktif_"+id).val("H");   
    	}
	}

	function belgeKaydet(id){
		var ad		= $('#belge_ad_'+id).val();
		var sira	= $('#belge_sira_'+id).val();
		
		if (ad == null || ad == "" ||
			sira == null || sira == "" ) {
			hataAc("Alanlar eksik.");
			return;
		}

    	$.post("<?=PREPATH.'post/riskPost.php?tur=belge&mesaj=true' ?>",
            {
        		id 		: $('#belge_id_'+id).val(),
        		ad		: $('#belge_ad_'+id).val(),
				sira	: $('#belge_sira_'+id).val(),
        		aktf	: $('#belge_aktif_'+id).val(),
        		prsdr	: $('#dznl_prosedur_id').val(),
    	    },
    	    function(data,status){
        		if(status == "success"){
        		    var obj = JSON.parse(data);
        		    if (obj.hata == true) {
        				hataAc(obj.hataMesaj);
        		    }else{
        		    	onayAc(obj.icerik);
        		    	belgeleriYukle($('#dznl_prosedur_id').val());
        		    }
        		}else if(status == "error"){
        		    hataAc("Bir sorun oluştu.");
        	    }
    	    }
        );
    }

	function belgeleriYukle(id){
		var table = $('#veriOdasiList');
	    table.find("tr").remove();
        $.get( "<?php echo PREPATH.'post/riskPost.php?tur=belgeGetir&prosedur_id='?>"+id, function(data, status){
    		if(status == "success"){
    			var belgex = JSON.parse(data);
    			var belge = JSON.parse(belgex.icerik);
    			if (belge != null) {
    				for (var i = 0; i < belge.length; i++) {
    					satırEkle(belge[i]);
    				}
    			}
    		}else if(status == "error"){
    		    hataAc("Belgeler çekilemedi.");
    	    }
	    });
	}
	
	tableSirala("#tableRisk");
	tableArama("#tableRisk","#search");
	tableSirala("#tableProsedur");
	tableArama("#tableProsedur","#searchPro");

	riskListesiGetir();
	riskGosterPGizle();

	function riskListesiGetir(){
		var table = $('#tableRisk');
	    table.find("tbody tr").remove();
	    $.get( "<?php echo PREPATH.'post/riskPost.php?tur=allRisk'?>", function(data, status){
			if(status == "success"){
			    var objx = JSON.parse(data);
			    if (objx.hata == false) {
     			    var obj = JSON.parse(objx.icerik);
        			if (obj != null && obj != ""){
            			obj.forEach(function(item){
            				table.append(
                        		'<tr class="listeEleman risk_select" id="risk_select_'+ item.id +'" onclick="prosedurGetir('+ item.id +',\''+item.kod+' - '+item.adi+'\')" >'+
                            		'<td class="text-center align-middle" id="list_id">'+ item.kod +'</td>'+
                            		'<td class="text-center align-middle">'+ item.adi +'</td>'+
                            	'</tr>'
            				);
            			});
        			}
    		    }
			    //$('#yetkiSelect').find("div").remove();
			}else if(status == "error"){
    		    hataAc("Bir sorun oluştu.");
    	    }
	    });
	}

	function prosedurGetir(id,adi){
		riskGosterPGizle();
		riskDetayAc(id);
		
		$('#dznl_prosedur_riskid').val(id);
		$('#dznl_prosedur_riskadi').val(adi);
		
		var table = $('#tableProsedur');
	    table.find("tbody tr").remove();
		$.get( "<?php echo PREPATH.'post/riskPost.php?tur=prosedurList&riskId='?>"+id, function(data, status){
			if(status == "success"){
			    var objx = JSON.parse(data);
			    if (objx.hata == false) {
     			    var obj = JSON.parse(objx.icerik);
        			if (obj != null && obj != ""){
            			obj.forEach(function(item){
            				table.append(
                        		'<tr class="listeEleman pro_select" id="pro_select_'+ item.id +'" onclick="proDetayAc('+ item.id +',\''+item.kod+'  '+item.adi+'\')" >'+
                            		'<td class="text-center align-middle" id="list_id">'+ item.kod +'</td>'+
                            		'<td class="text-center align-middle">'+ item.adi +'</td>'+
                            	'</tr>'
            				);
            			});
        			}
    		    }
			    //$('#yetkiSelect').find("div").remove();
			}else if(status == "error"){
    		    hataAc("Bir sorun oluştu.");
    	    }
	    });
	}

	function rGizlePGoster(){
		document.getElementById("riskDuzen").style.display = "none";
		document.getElementById("prosedurDuzen").style.display = "block";
		document.getElementById("veriOdaDuzen").style.display = "block";
	}
	
	function riskGosterPGizle(){
		document.getElementById("prosedurDuzen").style.display = "none";
		document.getElementById("veriOdaDuzen").style.display = "none";
		document.getElementById("riskDuzen").style.display = "block";
	}
	
	$("#riskEkleBtn").click(function(){
		riskGosterPGizle();
		riskFormTemizle();
		var divs = $('[id^=risk_select]');
		divs.each(function( index ) {
			$(this).removeClass();
		});
	});
	
	$("#prosedurEkleBtn").click(function(){
		if ($('#dznl_prosedur_riskid').val() != '') {
    		rGizlePGoster();
    		proFormTemizle();
		}
	});
	
    $("#dznl_risk_btn").click(function(){
    	//riskBtnDuzenle();
		if (riskFromValid() ){
			var link = '';
			if ($('#dznl_risk_id').val() == null || $('#dznl_risk_id').val() == ""){
				link = "<?=PREPATH.'post/genelPost.php?tur=create&tablo='.get_class(new RiskListesi()).'&mesaj=true' ?>";
			}else{
				link = "<?=PREPATH.'post/genelPost.php?tur=update&tablo='.get_class(new RiskListesi()).'&mesaj=true' ?>";
			}
	        $.post(link,
		        {
		    		id 	: $('#dznl_risk_id').val(),
		    		adi	: $('#dznl_risk_ad').val(),
		    		kod	: $('#dznl_risk_kod').val(),
		    		aktif:$('#dznl_risk_aktif').val(),
			    },
			    function(data,status){
		    		if(status == "success"){
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
		}
	});
	
    $("#dznl_pro_btn").click(function(){
		if (riskFromValid() ){
			var link = '';
			if ($('#dznl_prosedur_id').val() == null || $('#dznl_prosedur_id').val() == ""){
				link = "<?=PREPATH.'post/genelPost.php?tur=create&tablo='.get_class(new RiskProsedur()).'&mesaj=true' ?>";
			}else{
				link = "<?=PREPATH.'post/genelPost.php?tur=update&tablo='.get_class(new RiskProsedur()).'&mesaj=true' ?>";
			}
	        $.post(link,
		        {
		    		id 		: $('#dznl_prosedur_id').val(),
		    		adi		: $('#dznl_prosedur_ad').val(),
		    		kod		: $('#dznl_prosedur_kod').val(),
		    		aktif	: $('#dznl_prosedur_aktif').val(),
		    		risk_id	: $('#dznl_prosedur_riskid').val(),
		    		b_var   : $('#dzn_b_var').val(),
		    		b_haklar: $('#dzn_b_haklar').val(),
		    		b_tamlik: $('#dzn_b_tamlik').val(),
		    		b_deger : $('#dzn_b_deger').val(),
		    		g_meydan: $('#dzn_g_meydan').val(),
		    		g_tamlik: $('#dzn_g_tamlik').val(),
		    		g_dogru : $('#dzn_g_dogru').val(),
		    		g_cutoff: $('#dzn_g_cutoff').val(),
		    		g_sinif : $('#dzn_g_sinif').val(),
		    		s_meydan: $('#dzn_s_meydan').val(),
		    		s_tamlik: $('#dzn_s_tamlik').val(),
		    		s_sinif : $('#dzn_s_sinif').val(),
		    		s_dogru : $('#dzn_s_dogru').val(),
			    },
			    function(data,status){
		    		if(status == "success"){
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
		}
	});
	
	function riskDetayAc(id){
    	riskFormTemizle(false);
    	$('#risk_select_'+id).siblings().removeClass();
	    $('#risk_select_'+id).addClass("table-danger").addClass("klncSecili");
	    $.get( "<?php echo PREPATH.'post/genelPost.php?tur=getById&tablo='.get_class($tbl).'&id='?>"+id, function(data, status){
			if(status == "success"){
			    var objx = JSON.parse(data);
			    var obj = JSON.parse(objx.icerik);
			    $('#dznl_risk_id').				val(obj.id);
			    $('#dznl_risk_kod').			val(obj.kod);
			    $('#dznl_risk_ad').				val(obj.adi);
			    $('#dznl_risk_aktif').			val(obj.aktif);
			    $('#dznl_risk_create_gmt').		val(obj.create_gmt);
			    $('#dznl_risk_create_user_id').	val(obj.create_user_id);
			    $('#dznl_risk_gmt').			val(obj.gmt);
			    $('#dznl_risk_user_id').		val(obj.user_id);
			}else if(status == "error"){
			    hataAc("Bilgi çekilemedi.");
		    }
			riskBtnDuzenle();
		});
	}
	
	function proDetayAc(id){
		rGizlePGoster();
    	proFormTemizle(false);
    	$('#pro_select_'+id).siblings().removeClass();
	    $('#pro_select_'+id).addClass("table-danger").addClass("klncSecili");
    	rGizlePGoster();
	    $.get( "<?php echo PREPATH.'post/genelPost.php?tur=getById&tablo='.get_class(new RiskProsedur()).'&id='?>"+id, function(data, status){
			if(status == "success"){
			    var objx = JSON.parse(data);
			    var obj = JSON.parse(objx.icerik);
			    $('#dznl_prosedur_id').				val(obj.id);
			    $('#dznl_prosedur_kod').			val(obj.kod);
			    $('#dznl_prosedur_ad').				val(obj.adi);
			    $('#dznl_prosedur_aktif').			val(obj.aktif);
			    $('#dznl_prosedur_create_gmt').		val(obj.create_gmt);
			    $('#dznl_prosedur_create_user_id').	val(obj.create_user_id);
			    $('#dznl_prosedur_gmt').			val(obj.gmt);
			    $('#dznl_prosedur_user_id').		val(obj.user_id);
			    driveKontrol(obj.id);
			    for (const [key, value] of Object.entries(obj)) {
				    if (key.includes("b_") || key.includes("g_") || key.includes("s_")) {
			    		checkKontroller(key,value);
					}
		    	}
			    belgeleriYukle(id);
			}else if(status == "error"){
			    hataAc("Bilgi çekilemedi.");
		    }
			proBtnDuzenle();
		});
	}

	function driveKontrol(id){
		var table = $('#drivelar');
	    table.find("div").remove();
	    $.post( "<?=PREPATH.'post/planlama/planlamaPost.php?tur=riskDrive'?>",
    		{
    			id	:id,
    			link: <?="'".$link."'" ?>
    		}, 
    	    function(data, status){
    		if(status == "success"){
    		    var objx = JSON.parse(data);
    		    if (objx.hata == false) {
     			    var item = JSON.parse(objx.icerik);
					var btnler = '';
					if (item.id == null) {
						btnler =
							'<tr>'+
				        		'<td style="width: 50%" class="p-0 m-0 text-center">'+
									'<form enctype="multipart/form-data" action="<?= PREPATH.'post/planlama/planlamaPost.php?tur=riskBelegeYukle' ?>" method="POST">'+
										'<input name="dosya" type="file" id="driveUpload" hidden />'+ 
										'<input type="hidden" name="link" value="<?=$link ?>">'+
										'<input type="hidden" name="id" value="'+id+'">'+
										'<input type="hidden" name="key" value="'+'s'+'">'+
										'<input type="submit" value="Submit" id="fsubmit" hidden>'+
										'<a id="button-upload" href="#" class="btn btn-primary " >'+
										'<i class="fas fa-arrow-up"></i>'+
										'</a>'+
									'</form>'+
				    			'</td>'+
				    		'</tr>';
					}else{
						btnler=
							'<tr>'+
								'<td style="width: 33%" class="p-0 m-0 text-center"><a href="'+item.url+'"	class="btn btn-primary" ><i class="fas fa-cloud-download-alt"></i></a></td>'+
								'<td style="width: 33%" class="p-0 m-0 text-center"><a href="'+item.web+'"	class="btn btn-primary" target="_blank" ><i class="fas fa-external-link-alt"></i></i></a></td>'+
								'<td style="width: 33%" class="p-0 m-0 text-center"><a href="#" onclick="belgeSil('+id+')" class="btn btn-primary" ><i class="fas fa-times"></i></input></td>'+
				      		'</tr>';
					}
					
			    	table.append(
				    	'<div class="row ">'+
    		        		'<table class="table table-borderless p-0 m-0">'+
    		        			'<tbody>'+
    		                    	btnler+
    		              		'</tbody>'+
    		                '</table>'+
		                '</div>'
		            	);
                	
                    $("#button-upload").click(function () {
                        $("#driveUpload").click();
                    });

                    $("#driveUpload").bind("change", function () {
                        $("#fsubmit").click();
                    });
    		    }else{
    		    	hataAc(objx.hataMesaj);
    		    }
        	}else if(status == "error"){
        	    hataAc("Bir sorun oluştu.");
            }
        });
	}

	function belgeSil(id){
		loadEkranAc();
	    var c = confirm("Silmek istediğinize emin misiniz?");
	    if (c) {
	    	$.post("<?=PREPATH.'post/planlama/planlamaPost.php?tur=beyanDelete' ?>",{
	    		link: <?="'".$link."'" ?>,
	    		id	: id
	        },function(data,status){
	    		if(status == "success"){
	    			driveKontrol(id);
	    		}else if(status == "error"){
	    		    hataAc("Bir sorun oluştu.");
	    	    }
	    		loadEkranKapat();
		    });
	    }
	}

    function riskFromValid( sfr = false){
	    var snc1 = !sfr && ($('#dznl_risk_ad').val() == null || $('#dznl_risk_ad').val() == "");
	    var snc2 = !sfr && ($('#dznl_risk_kod').val() == null || $('#dznl_risk_kod').val() == "");
	    
		fromAlanValid('#dznl_risk_ad',snc1) ;
		fromAlanValid('#dznl_risk_kod',snc2) ;
		    
		if (snc1 || snc2) {
	    	hataAc("Eksik alanları doldurun.");
	    	return false;
	    }
		return true;
	}

    function proFromValid( sfr = false){
	    var snc1 = !sfr && ($('#dznl_prosedur_ad').val() == null || $('#dznl_prosedur_ad').val() == "");
	    var snc2 = !sfr && ($('#dznl_prosedur_kod').val() == null || $('#dznl_prosedur_kod').val() == "");
	    
		fromAlanValid('#dznl_prosedur_ad',snc1) ;
		fromAlanValid('#dznl_prosedur_kod',snc2) ;
		    
		if (snc1 || snc2) {
	    	hataAc("Eksik alanları doldurun.");
	    	return false;
	    }
		return true;
	}

	//Form Valid stil Fonksiyon
    function fromAlanValid(gln,sfr){
	    if (sfr) {
		    $(gln).css("border","1px solid red");
		}else{
		    $(gln).css("border","1px solid Lavender");
		}
	}
	
	function riskFormTemizle(valid = true){
	    $('#dznl_risk_id').				val(null);
	    $('#dznl_risk_kod').			val(null);
	    $('#dznl_risk_ad').				val(null);
	    $('#dznl_risk_aktif').			val(null);
	    $('#dznl_risk_create_gmt').		val(null);
	    $('#dznl_risk_create_user_id').	val(null);
	    $('#dznl_risk_gmt').			val(null);
	    $('#dznl_risk_user_id').		val(null);
	    riskBtnDuzenle();
	    if (valid) {
	    	riskFromValid(true);
	    }
	}
	
	function proFormTemizle(valid = true){
	    $('#dznl_prosedur_id').				val(null);
	    $('#dznl_prosedur_kod').			val(null);
	    $('#dznl_prosedur_ad').				val(null);
	    $('#dznl_prosedur_aktif').			val(null);
	    $('#dznl_prosedur_create_gmt').		val(null);
	    $('#dznl_prosedur_create_user_id').	val(null);
	    $('#dznl_prosedur_gmt').			val(null);
	    $('#dznl_prosedur_user_id').		val(null);
	    $(".form-check-label").each(function (col) {
    		$(this).val("H");
    		$(this).prop("checked", false);   
	    });
	    proBtnDuzenle();
	    if (valid) {
		    proFromValid(true);
	    }
	}

	function riskBtnDuzenle(){
	    if($('#dznl_risk_id').val() == null || $('#dznl_risk_id').val() == ""){
		    $('#dznl_risk_btn span').text("Kaydet");
		    $('#dznl_risk_btn i').removeClass("fas").removeClass("fa-pencil-alt");
		    $('#dznl_risk_btn i').addClass("fa").addClass("fa-floppy-o");
		}else{
		    $('#dznl_risk_btn span').text("Düzenle");
		    $('#dznl_risk_btn i').removeClass("fa").removeClass("fa-floppy-o");
		    $('#dznl_risk_btn i').addClass("fas").addClass("fa-pencil-alt");
		}
	}
	
	function proBtnDuzenle(){
	    if($('#dznl_prosedur_id').val() == null || $('#dznl_prosedur_id').val() == ""){
		    $('#dznl_pro_btn span').text("Kaydet");
		    $('#dznl_pro_btn i').removeClass("fas").removeClass("fa-pencil-alt");
		    $('#dznl_pro_btn i').addClass("fa").addClass("fa-floppy-o");
		}else{
		    $('#dznl_pro_btn span').text("Düzenle");
		    $('#dznl_pro_btn i').removeClass("fa").removeClass("fa-floppy-o");
		    $('#dznl_pro_btn i').addClass("fas").addClass("fa-pencil-alt");
		}
	}

	//Tablo sırala fonksiyonu
    function tableSirala(tbl){ 
	    $(tbl+' th').each(function (col) {
            $(this).click(function () {
        		$(tbl+" th i").remove();
                if ($(this).is('.asc')) {
                    $(this).removeClass('asc');
                    $(this).addClass('desc');
                    $(this).append('<i class="fa fa-sort-desc" aria-hidden="true"/>');
                    sortOrder = -1;
                } else {
                    $(this).addClass('asc');
                    $(this).removeClass('desc');
                    $(this).append('<i class="fa fa-sort-asc" aria-hidden="true"/>');
                    sortOrder = 1;
                }
                $(this).siblings().removeClass('asc');
                $(this).siblings().removeClass('desc');
                var arrData = $(tbl).find('tbody >tr:has(td)').get();
                arrData.sort(function (a, b) {
                    var val1 = $(a).children('td').eq(col).text().toUpperCase();
                    var val2 = $(b).children('td').eq(col).text().toUpperCase();
                    if ($.isNumeric(val1) && $.isNumeric(val2))
                        return sortOrder == 1 ? val1 - val2 : val2 - val1;
                    else
                        return (val1 < val2) ? -sortOrder : (val1 > val2) ? sortOrder : 0;
                });
                $.each(arrData, function (index, row) {
                    $(tbl+' tbody').append(row);
                });
            });
        });
    }

    //List arama fonksiyonu
    function tableArama(tbl, edt){
		$(edt).on("keyup",function(){
			var value = $(this).val().toLowerCase();
			$(tbl+" tbody tr").filter(function(){
				if ($(this).text().toLowerCase().indexOf(value)>-1) {
				    $(this).toggle(true);    
				}else{
				    $(this).toggle(false);
				}
				
			});
		});
    }

    $(".form-check-label").click(function(){
    	if($(this).prop("checked") == true){
    		$(this).val('E');
    	}else{
    		$(this).val("H");   
    	}
    });

    function checkKontroller(degisken,secili){
		$('#dzn_'+degisken).prop("checked", secili == 'E' ? true : false);
		$('#dzn_'+degisken).val(secili);
    }
</script>
<?php include (PREPATH.'footer.php'); ?>