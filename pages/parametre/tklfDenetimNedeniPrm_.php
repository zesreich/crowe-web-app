<?php
$pId = 193;
include_once '../../First.php';
include_once PREPATH . 'header.php';

$tbl    = new TklfDenetimNedeni();

?>
<style>
</style>

<div  class="row" >
    <div class="col-lg-12 col-xl-12 py-3" >
        <div class="card-header bg-gradient-primary py-3">
        	<h6 class="m-0 font-weight-bold text-gray-300"><?=$tbl->vt_Adi()?></h6>
        </div>
    </div>
    <div class="col-lg-12 col-xl-12 py-3" >
    	<ul id="ndnYol" class="list-inline">
    	
        </ul>
    </div>
    <div class="col-lg-6 col-xl-6 " >
        <div class="card h-100 shadow">
            <div class="card-body">
            	<div class="table-responsive">
            		<table class="table table-bordered table-striped" >
            			<thead>
            				<tr>
            					<th class="bg-gray-700 text-gray-200 text-center align-middle">Id  </th>
            					<th class="bg-gray-700 text-gray-200 text-center align-middle">Adı  </th>
            					<th class="bg-gray-700 text-gray-200 text-center align-middle">Düzenle  </th>
            				</tr>
            			</thead>
            			<tbody id="tableNdn" >
            			
            			</tbody>
            		</table>
            	</div>
            </div>
        </div>
    </div>
    <div class="col-lg-6 col-xl-6 " >
        <div class="card h-100 shadow">
            <div class="card-body">
                <form class="user">
                    <div class="row mb-2">
                		<div class="col-lg-4 font-weight-bold text-gray-800 align-self-center text-right">Id :</div>
                		<div class="col-lg-8"><input type="text"  id="dznl_id" class="form-control form-control-user" disabled></div>
                	</div>
                    <div class="row mb-2">
                		<div class="col-lg-4 font-weight-bold text-gray-800 align-self-center text-right">Üst Id :</div>
                		<div class="col-lg-8"><input type="text"  id="dznl_ust_id" class="form-control form-control-user" disabled></div>
                	</div>
                    <div class="row mb-2">
                		<div class="col-lg-4 font-weight-bold text-gray-800 align-self-center  text-right">Açıklama :</div>
                		<div class="col-lg-8"><input type="text" class="form-control form-control-user" id="dznl_aciklama" ></div>
                	</div>
                    <div class="row mb-2">
                		<div class="col-lg-4 font-weight-bold text-gray-800 align-self-center  text-right">Adı :</div>
                		<div class="col-lg-8"><input type="text" class="form-control form-control-user" id="dznl_adi" ></div>
                	</div>
                	<div class="row mb-2">
                		<div class="col-lg-4 font-weight-bold text-gray-800 align-self-center  text-right">Klasör :</div>
                    	<div class="col-lg-8 align-self-center"><input type="checkbox" class="form-check-label " id="dznl_klasor"  value="E" ></div>
                	</div>
                    <div class="row mb-2">
                		<div class="col-lg-4 font-weight-bold text-gray-800 align-self-center  text-right">Oluşturulma Tarihi :</div>
                		<div class="col-lg-8"><input type="text" class="form-control form-control-user" id="dznl_create_gmt" disabled></div>
                	</div>
                    <div class="row mb-2">
                		<div class="col-lg-4 font-weight-bold text-gray-800 align-self-center  text-right">Oluşturan Kişi :</div>
                		<div class="col-lg-8"><input type="text" class="form-control form-control-user" id="dznl_create_user_id" disabled></div>
                	</div>
                    <div class="row mb-2">
                		<div class="col-lg-4 font-weight-bold text-gray-800 align-self-center  text-right">Son Düzenleme Tarihi :</div>
                		<div class="col-lg-8"><input type="text" class="form-control form-control-user" id="dznl_gmt" disabled ></div>
                	</div>
                    <div class="row mb-2	">
                		<div class="col-lg-4 font-weight-bold text-gray-800 align-self-center  text-right">Son Düzenleyen Kişi :</div>
                		<div class="col-lg-8"><input type="text" class="form-control form-control-user" id="dznl_user_id" disabled></div>
                	</div>
                    <div class="row pt-2">
                        <div id="dznl_btn" class="col-lg-6 text-center">
                        	<a href="#" class="btn btn-success col-lg-8">
                        		<i class="fa fa-floppy-o"></i>
                                <span  class="text">Kaydet</span>
                            </a>
                        </div>
                        <div class="col-lg-6 text-center">
                        	<a href="#" id="dznl_sil" class="btn btn-danger col-lg-8" onclick="temizle()">
                        		<i class="fa fa-trash"></i>
                                <span class="text">Temizle</span>
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<div  class="row" id="yetkiSelect"></div>

<script >

	nedenListele(-1);

	function temizle(){
	    nedenListele(-1);
	    formTemizle();
	}
	
	function nedenListele(ustid){
	    formTemizle();
		if (ustid != "") {
		    var table = $('#tableNdn');
		    var lili  = $('#ndnYol');
			table.empty();
			lili.empty();
    		lili.append('<li onclick="nedenListele(-1)" class="list-inline-item">Başlangıç <i class="fa fa-angle-right" aria-hidden="true"></i></li>');
			table.append(
    			'<tr>'+
            		'<td class="text-center align-middle" onclick="detayAc('+ustid+',true)" ><i class="fa fa-plus"></i></td>'+
            		'<td class="text-center align-middle">Yeni Ekle</td>'+
            		'<td class="text-center align-middle" onclick="detayAc('+ustid+',true)" ><i class="fa fa-plus"></i></td>'+
            	'</tr>'
			);
		    $.get( "<?php echo PREPATH.'post/denetimNedeniPost.php?tur=nedenlerByUstid&ustid='?>"+ustid, function(data, status){
    			if(status == "success"){
    			    var objx = JSON.parse(data);
    			    if (objx.hata == false) {
         			    var obj = JSON.parse(objx.icerik);
            			if (obj != null && obj != ""){
        					var fnk = ''; 
        					var klsmi = ''; 
                			obj.forEach(function(item){
                			    if (item.klasor == 'E') {
                					fnk = 'onclick="nedenListele('+item.id+')"';
                					klsmi = 'bg-warning';
								}else{
                					fnk = '';//'onclick="sec(data_'+item.id+')"';
                					klsmi = '';
								}
								
                				table.append(
                					'<tr class="'+klsmi+'" id="data_'+item.id+'" >'+
                                		'<td class="text-center" '+fnk+' id="klnc_dgr">'+item.id+'</td>'+
                                		'<td class="text-center" '+fnk+' >'+item.aciklama+'</td>'+
                                		'<td class="text-center" onclick="detayAc('+item.id+',false)" ><i class="fas fa-angle-double-right"></i></td>'+
                                	'</tr>'
                				);
                			});
            			}
        		    }
        			$.get( "<?php echo PREPATH.'post/denetimNedeniPost.php?tur=nedenler&id='?>"+ustid , function(data, status){
            			if(status == "success"){
            			    var objx = JSON.parse(data);
            			    if (objx.hata == false) {
                 			    var obj = JSON.parse(objx.icerik);
                    			if (obj != null && obj != ""){
                        			obj.forEach(function(item){
                    					lili.append('<li onclick="nedenListele('+item.id+')" class="list-inline-item">'+item.aciklama+' <i class="fa fa-angle-right" aria-hidden="true"></i></li>');
                        			});
                    			}
                		    }
            			}else if(status == "error"){
                		    hataAc("Bir sorun oluştu.");
                	    }
            	    });	
    			}else if(status == "error"){
        		    hataAc("Bir sorun oluştu.");
        	    }
    	    });
		}
	}

	function detayAc(id,yeni){
	    formTemizle();
	    if (yeni) {
			$('#dznl_ust_id').val(id);
	    }else{
    	    $.get( "<?=PREPATH.'post/genelPost.php?tur=getById&tablo=TklfDenetimNedeni&id=' ?>"+id, function(data, status){
    			if(status == "success"){
    			    var obj =  JSON.parse(JSON.parse(data).icerik);
    			    console.log(obj.klasor);
    			    $('#dznl_id').				val(obj.id);
    			    $('#dznl_aciklama').		val(obj.aciklama);
    			    $('#dznl_adi').				val(obj.adi);
    			    $('#dznl_klasor').			val(obj.klasor);
    			    $('#dznl_create_gmt').		val(obj.create_gmt);
    			    $('#dznl_create_user_id').	val(obj.create_user_id);
    			    $('#dznl_gmt').				val(obj.gmt);
    			    $('#dznl_user_id').			val(obj.user_id);
    				$('#dznl_ust_id').			val(obj.ust_id);
    			    $('#dznl_klasor').prop("checked", obj.klasor == 'E' ? true : false);
    			    btnDuzenle();
    			}else if(status == "error"){
    			    hataAc("Bilgi çekilemedi.");
    		    }
    			fromValid(true);
    		});
	    }
	    btnDuzenle();
	}

	function formTemizle(valid = true){
	    $('#dznl_yetki .data').remove();
	    $('#dznl_id').				val(null);
	    $('#dznl_aciklama').		val(null);
	    $('#dznl_adi').				val(null);
	    $('#dznl_klasor').			val("H");
	    $('#dznl_create_gmt').		val(null);
	    $('#dznl_create_user_id').	val(null);
	    $('#dznl_gmt').				val(null);
	    $('#dznl_user_id').			val(null);
	    $('#dznl_ust_id').			val(null);
	    $('#dznl_klasor').prop("checked", false);
	    btnDuzenle();
	    if (valid) {
		    fromValid(true);
	    }
	}

	function btnDuzenle(){
	    if($('#dznl_id').val() == null || $('#dznl_id').val() == ""){
		    $('#dznl_btn span').text("Kaydet");
		    $('#dznl_btn i').removeClass("fas").removeClass("fa-pencil-alt");
		    $('#dznl_btn i').addClass("fa").addClass("fa-floppy-o");
		}else{
		    $('#dznl_btn span').text("Düzenle");
		    $('#dznl_btn i').removeClass("fa").removeClass("fa-floppy-o");
		    $('#dznl_btn i').addClass("fas").addClass("fa-pencil-alt");
		}
	}

	function fromValid( sfr = false){
	    var snc1 = !sfr && ($('#dznl_aciklama').val() == null || $('#dznl_aciklama').val() == "");
	    var snc2 = !sfr && ($('#dznl_ust_id').val() == null || $('#dznl_ust_id').val() == "");
	    
		fromAlanValid('#dznl_aciklama',snc1) ;
		fromAlanValid('#dznl_ust_id',snc2) ;
		    
		if (snc1 || snc2) {
	    	hataAc("Eksik alanları doldurun.");
	    	return false;
	    }
		return true;
	}

	function fromAlanValid(gln,sfr){
	    if (sfr) {
		    $(gln).css("border","1px solid red");
		}else{
		    $(gln).css("border","1px solid Lavender");
		}
	}

    $("#dznl_btn").click(function(){
		btnDuzenle();
		if (fromValid() ){
			var link = '';
			if ($('#dznl_id').val() == null || $('#dznl_id').val() == ""){
    			link = "<?=PREPATH.'post/genelPost.php?tur=create&tablo='.get_class($tbl).'&mesaj=true' ?>";
			}else{
    			link = "<?=PREPATH.'post/genelPost.php?tur=update&tablo='.get_class($tbl).'&mesaj=true' ?>";
			}
	        $.post(link,
		        {
		    		id 			: $('#dznl_id').val(),
		    		ust_id 		: $('#dznl_ust_id').val(),
		    		aciklama	: $('#dznl_aciklama').val(),
		    		adi			: $('#dznl_adi').val(),
		    		klasor		: $('#dznl_klasor').val(),
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
		}
	});

    $("#dznl_klasor").click(function(){
	    klasorMuSecimi();
	});

    function klasorMuSecimi(){
	    if ($("#dznl_klasor").prop("checked") == true){
	    	$("#dznl_klasor").val("E");
	    }else{
	    	$("#dznl_klasor").val("H");
	    }
	}
	
</script>
<?php include (PREPATH.'footer.php'); ?>