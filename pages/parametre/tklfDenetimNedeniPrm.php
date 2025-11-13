<?php
$pId = 193;
include_once '../../First.php';
include_once PREPATH . 'header.php';

$tbl    = new TklfDenetimNedeni();

?>
<style>
</style>


<div  class="row" >
	<input type="hidden" id="secili" >
    <div class="col-lg-12 col-xl-12 py-3" >
        <div class="card-header bg-gradient-primary py-3">
        	<h6 class="m-0 font-weight-bold text-gray-300"><?=$tbl->vt_Adi()?></h6>
        </div>
    </div>
    <div class="col-lg-7 col-xl-7 " >
        <div class="card h-100 shadow">
            <div class="card-body">
                <div id="cmb">
                	<div  class="row" >
						<div class="col-10" >
                    		<select class="custom-select form-control my-2" id="s0" onchange="sec(0,this)"></select>
                		</div>
                		<div class="col-2" >
                			<a href="#" onclick="detayAc(0,-1);" class="btn btn-warning my-2" ><i class="fas fa-edit"></i></a>
                		</div>
            		</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-5 col-xl-5 " >
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
                		<div class="col-lg-8"><textarea rows="3" class="form-control" id="dznl_aciklama" ></textarea></div>
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

	nedenListele2(-1);

	function temizle(){
	    nedenListele(-1);
	    formTemizle();
	}
	
	function nedenListele2(ustid){
		if (ustid != "") {
		    var table = $('#s0');
			table.empty();
	    	table.append('<option class="dznl_val" value="" selected="selected">Seçiniz...</option>');
		    $.get( "<?php echo PREPATH.'post/denetimNedeniPost.php?tur=nedenlerByUstid&ustid='?>"+ustid, function(data, status){
    			if(status == "success"){
    			    var objx = JSON.parse(data);
    			    if (objx.hata == false) {
         			    var obj = JSON.parse(objx.icerik);
            			if (obj != null && obj != ""){
        					var fnk = ''; 
        					var klsmi = ''; 
                			obj.forEach(function(item){
                			    table.append('<option class="dznl_val" value="'+item.id+'">'+item.aciklama+'</option>');
                			});
            			}
        		    }
    			}else if(status == "error"){
        		    hataAc("Bir sorun oluştu.");
        	    }
    	    });
		}

	}

	function sec(sira,dgr){
	    var kod = dgr.value;
		$.get( "<?php echo PREPATH.'post/denetimNedeniPost.php?tur=nedenlerByUstid&ustid='?>"+kod, function(data, status){

		    $("[class*=slct]").each(function( index ) {
    		    if (index >= sira) {
    		    	$(this).remove();
    			}
    		});

		    if (kod != null & kod != '' ){
    			if(status == "success"){
    			    var objx = JSON.parse(data);
    			    if (objx.hata == false) {
                		var table = $('#cmb');
                		var son 	= '';
            		    
            			son += '<div  class="row slct'+sira+'" >'
						son += '<div class="col-10" >'
					    son += '<select class="my-2 custom-select form-control" id="s'+(sira+1)+'" onchange="sec('+(sira+1)+',this)" >';
         			    var obj = JSON.parse(objx.icerik);
            			if (obj != null && obj != ""){
            				son += '<option class="dznl_val" value="" selected="selected">Seçiniz...</option>';
                			obj.forEach(function(item){
                			    son += '<option class="dznl_val" value="'+item.id+'" >'+item.aciklama+'</option>';
                			});
            			}
            			son += '</select></div>';
        			    son += '<div class="col-2" ><a href="#" onclick="detayAc('+(sira+1)+','+kod+');" class="btn btn-warning my-2" ><i class="fas fa-edit"></i></a></div>';
        			    son += '</div>';
        				table.append(son);
                		
                		$('#secili').text();;
        		    }else{
            		    $('#secili').text(kod);
        		    }
    			}else if(status == "error"){
        		    hataAc("Bir sorun oluştu.");
        	    }
    	    }
	    });
	};

	function detayAc(sira,yeni){
	    formTemizle();
		var id = $('#s'+sira).val();
	    if (id == null || id == '') {
			$('#dznl_ust_id').val(yeni);
	    }else{
    	    $.get( "<?=PREPATH.'post/genelPost.php?tur=getById&tablo=TklfDenetimNedeni&id=' ?>"+id, function(data, status){
    			if(status == "success"){
    			    var obj =  JSON.parse(JSON.parse(data).icerik);
    			    $('#dznl_id').				val(obj.id);
    			    $('#dznl_aciklama').		val(obj.aciklama);
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



    function klasorMuSecimi(){
	    if ($("#dznl_klasor").prop("checked") == true){
	    	$("#dznl_klasor").val("E");
	    }else{
	    	$("#dznl_klasor").val("H");
	    }
	}
	
</script>
<?php include (PREPATH.'footer.php'); ?>