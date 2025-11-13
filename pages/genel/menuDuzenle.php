<?php
$pId = 110;
include_once '../../First.php';
include_once PREPATH . 'header.php';

$gelen = Crud::getSqlCok(new Program(), Program::MENU_SIRALI, array());
$ustMax =2;

function menuSirala($menu,$ust,$adm){
    global $ustMax;
    $snc = '';
    foreach ($menu as $m) {
        if($m->ust_id->deger ==$ust ){
            if ($m->ust_id->deger == -1){$ustMax++;}
            $snc = $snc.
            '<li id="'.$m->id.'" class="list-group-item list-group-item-action py-1 pr-0">'.
                '<div class="row">'.
                    '<div class="col-sm-6 align-self-center text-left">'.
                        '<div class="btn '. ($m->gorunsunmu->deger == "E" ? "btn-warning" : "btn-secondary") .' btn-circle mr-2 eleman" onclick="detayAc(\''.$m->id.'\')" >'.
                            '<p class="deger orj d-none">'.$m->id .'</p>'.
                            '<p class="klasor d-none">'.$m->klasor .'</p>'.
                            '<p class="icon d-none">'.$m->icon .'</p>'.
                            '<i class="fas fa-angle-double-right"></i>'.
                        '</div>'.
                        $m->program_adi.
                    '</div>'.
                    '<div class="col-sm-6 text-right ">'.
                    ($adm ?
                        '<div class="btn btn-danger btn-circle elemanSil" onclick="elemanSil(\''.$m->id.'\')">'.
                            '<p class="deger d-none">'.$m->id.'</p>'.
                            '<i class="fas fa-times"></i>'.
                        '</div>' : ''
                        ).
                    '</div>'.
                '</div>';
            if($m->klasor==Base::EVET){
                $snc = $snc.
                '<div class="my-2 mr-0">'.
                    '<ul class=" bg-secondary  droptrue list-group sortable">'.
                        menuSirala($menu,$m->id,$adm).
                    '</ul>'.
                '</div>';
            }
            $snc = $snc.'</li>';
        }
    }
    return $snc;
}

?>

<style>
    ul ul {
        min-height: 2em;
    }
    .form-check-label{
        width: 1.25rem;
        height: 1.25rem;
    }
</style>
<div class="row">
    <div class="col-lg-6 col-xl-7 pb-3">
        <div class="card shadow">
            <div class="card-header bg-gradient-primary py-3">
            	<h6 class="m-0 font-weight-bold text-gray-300">Menü Sıralama</h6>
            </div>
            <div class="card-body">
            	<ul class=" bg-secondary  droptrue list-group sortable">  
    				<?= menuSirala($gelen, -1, $_ADMIN) ?>
                </ul>
            </div>
            <div class="text-center p-3">
            	<a href="#" id="list_kaydet" class="btn btn-primary col-lg-6">
            		<i class="fa fa-floppy-o"></i>
                    <span  class="text">SIRALAMAYI KAYDET</span>
                </a>
            </div>
        </div>
    </div>
    <div class="col-lg-6 col-xl-5">
        <div class="card shadow" >
        	<div class="card-header bg-gradient-primary py-3">
            	<h6 class="m-0 font-weight-bold text-gray-300">Menü Düzenleme</h6>
            </div>
            <div class="card-body">
                <div class="row mb-2">
            		<div class="col-lg-4 font-weight-bold text-gray-800 align-self-center text-right">Id :</div>
            		<div class="col-lg-8"><input type="text" class="form-control form-control-user" id="dzn_id"  disabled></div>
            	</div>
                <div class="row mb-2">
            		<div class="col-lg-4 font-weight-bold text-gray-800 align-self-center  text-right">Program Adı :</div>
            		<div class="col-lg-8"><input type="text" class="form-control form-control-user" id="dzn_adi" ></div>
            	</div>
                <div class="row mb-2">
            		<div class="col-lg-4 font-weight-bold text-gray-800 align-self-center  text-right">Program Link :</div>
            		<div class="col-lg-8"><input type="text" class="form-control form-control-user" id="dzn_link" disabled></div>
            	</div>
                <div class="row mb-2">
            		<div class="col-lg-4 font-weight-bold text-gray-800 align-self-center  text-right">Klasör :</div>
                	<div class="col-lg-8 align-self-center"><input type="checkbox" class="form-check-label " id="dzn_klasor"  value="E" checked  <?= ($_ADMIN ? '' : 'disabled')?> ></div>
            	</div>
                <div class="row mb-2">
            		<div class="col-lg-4 font-weight-bold text-gray-800 align-self-center  text-right">Görünsün mü :</div>
                	<div class="col-lg-8 align-self-center"><input type="checkbox" class="form-check-label " id="dzn_gorunsunmu"  value="H"></div>
            	</div>
            	
            	<div class="row mb-2">
            		<div class="col-lg-4 font-weight-bold text-gray-800 align-self-center  text-right">Yetki :</div>
            		<div class="col-lg-8">
                		<select class=" custom-select form-control " id="dzn_yetki">
                			<option class="dznl_klcTur" value="" selected="selected">Seçiniz</option>
                        </select>
            		</div>
            	</div>
            	
                <div class="row mb-2	">
            		<div class="col-lg-4 font-weight-bold text-gray-800 align-self-center  text-right">Son Düzenleme Tarihi :</div>
            		<div class="col-lg-1 align-self-center text-right">
                    	<i id="dzn_icon" class="" aria-hidden="true"></i>
            		</div>
            		<div class="col-lg-5">
                    	<input type="text" class="form-control form-control-user" id="dzn_iconText" >
            		</div>
            		<div class="col-lg-2 align-self-center  text-center ">
                    	<a target="_blank" href="https://fontawesome.com/icons?d=gallery&m=free" >ICONS</a>
            		</div>
            	</div>
                <div class="row mb-2">
            		<div class="col-lg-4 font-weight-bold text-gray-800 align-self-center  text-right">Oluşturulma Tarihi :</div>
            		<div class="col-lg-8"><input type="text" class="form-control form-control-user" id="dzn_create_gmt" disabled></div>
            	</div>
                <div class="row mb-2">
            		<div class="col-lg-4 font-weight-bold text-gray-800 align-self-center  text-right">Oluşturan Kişi :</div>
            		<div class="col-lg-8"><input type="text" class="form-control form-control-user" id="dzn_create_user_id" disabled></div>
            	</div>
                <div class="row mb-2">
            		<div class="col-lg-4 font-weight-bold text-gray-800 align-self-center  text-right">Son Düzenleme Tarihi :</div>
            		<div class="col-lg-8"><input type="text" class="form-control form-control-user" id="dzn_gmt" disabled></div>
            	</div>
                <div class="row mb-2	">
            		<div class="col-lg-4 font-weight-bold text-gray-800 align-self-center  text-right">Son Düzenleyen Kişi :</div>
            		<div class="col-lg-8"><input type="text" class="form-control form-control-user" id="dzn_user_id" disabled></div>
            	</div>
                <div class="row pt-2">
                    <div id="dznl_btn" class="col-lg-6 text-center">
                    	<a href="#" id="dznl_kaydet" class="btn btn-success col-lg-8">
                    		<i class="fa fa-floppy-o"></i>
                            <span  class="text">KAYDET</span>
                        </a>
                    </div>
                    <div class="col-lg-6 text-center">
                    	<a href="#" id="dznl_sil" class="btn btn-danger col-lg-8" onclick="formTemizle()">
                    		<i class="fa fa-trash"></i>
                            <span class="text">Temizle</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">


class menu{
    constructor(id,sira,ustId){//,icon,gorunsunmu){
	    this.ustId 		= ustId;
	    this.id 		= id;
 	    this.sira 		= sira;
    }

    toJSON() {
	    return {
			ustId:		this.ustId,
			id:			this.id,
			sira:   	this.sira,
	    };
	  }
}

function programYetkiYukle(id){
	var prg 	= $('#dzn_id').val();
	var table	= $('#dzn_yetki');
	table.find(".data").remove();
	$.get( "<?php echo PREPATH.'post/yetkiPost.php?tur=yetkiProgramListesi&programId='?>"+prg, function(data, status){
    	if(status == "success"){
    	    var objx = JSON.parse(data);
    	    if (objx.hata == false) {
    		    var obj = JSON.parse(objx.icerik);
        		if (obj != null && obj != ""){
        			obj.forEach(function(item){
        				table.append(
        					'<option class="dznl_val data" value="'+item.id+'">'+item.yetki_adi+'</option>'
        				);
        			});
        		}
    	    }
        	if (id != null && id != -1) {
        		$('#dzn_yetki').val(id);
        	}
    	}else if(status == "error"){
    		table.find(".data").remove();
    	    hataAc("Bir sorun oluştu.");
    	}
    });
}


$( "ul" ).sortable({
	connectWith: "ul"
});

$("#list_kaydet").click(function(){
    var liste = menuOku($("ul.sortable:first"),-1);
    $.post("<?= PREPATH.'post/menuPost.php?tur=listeSiraKaydet' ?>",
    {
		data : JSON.stringify(liste)
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
    });
    
});

$("#dzn_klasor").click(function(){
    klasorMuSecimi();
});

function klasorMuSecimi(){
    if ($("#dzn_klasor").prop("checked") == true){
    	$("#dzn_klasor").val("E");
    	$("#dzn_link").prop( "disabled", true ); 
    	$("#dzn_link").val(null);
    }else{
    	$("#dzn_klasor").val("H");
    	$("#dzn_link").prop( "disabled", (<?php echo $_ADMIN ? 'true' : 'false' ?> == true ? false : true)  );   
    }
}

$("#dzn_gorunsunmu").click(function(){
    if ($("#dzn_gorunsunmu").prop("checked") == true){
		$("#dzn_gorunsunmu").val("E");
    }else{
		$("#dzn_gorunsunmu").val("H");   
    }    
});
    
function menuOku(icerik,ustId){
    var liste = [];
    icerik.each(function( index ) {
    	$(this).find( "> li.list-group-item" ).each(function( index ) {
    		var id = $(this).find('p.deger.orj:first').text();
    		liste.push(new menu(id,(index+1),ustId).toJSON());
			var dnn = menuOku($(this).find("ul.sortable:first"),id);
			dnn.forEach(function(element) {
    			liste.push(element);
			});
    	});
    });
    return liste;
}

$("#dznl_kaydet").click(function(){
	if (fromValid() ){
        $.post("<?=PREPATH.'post/menuPost.php?tur=tekMenuKaydet' ?>",
        {
    		id 			: $('#dzn_id').val(),
    		program_adi : $('#dzn_adi').val(),
    		program_link: $('#dzn_link').val(),
    		icon		: $('#dzn_iconText').val(),
    		klasor		: $('#dzn_klasor').val(),
    		gorunsunmu	: $('#dzn_gorunsunmu').val(),
    		yetki		: $('#dzn_yetki').val(),
    		ust_id		: '-1',
			sira        : '<?= $ustMax ?>'
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
	    });
	}
});

function detayAc(id){
    formTemizle();
    $.get( "<?=PREPATH.'post/genelPost.php?tur=getById&tablo=program&id=' ?>"+id, function(data, status){
		if(status == "success"){
		    var obj =  JSON.parse(JSON.parse(data).icerik);
		    $('#dzn_id').				val(obj.id);
		    $('#dzn_adi').				val(obj.program_adi);
		    $('#dzn_link').				val(obj.program_link);
		    $('#dzn_klasor').			val(obj.klasor);
		    $('#dzn_gorunsunmu').		val(obj.gorunsunmu);
		    $('#dzn_icon').				val(obj.icon);
		    $('#dzn_iconText').			val(obj.icon);
		    $('#dzn_icon').addClass(obj.icon);
		    $('#dzn_create_gmt').		val(obj.create_gmt);
		    $('#dzn_create_user_id').	val(obj.create_user_id);
		    $('#dzn_gmt').				val(obj.gmt);
		    $('#dzn_user_id').			val(obj.user_id);
		    $('#dzn_klasor').prop("checked", obj.klasor == 'E' ? true : false);
		    $('#dzn_gorunsunmu').prop("checked", obj.gorunsunmu == 'E' ? true : false);
		    btnDuzenle();
		    programYetkiYukle(obj.yetki);
		}else if(status == "error"){
		    hataAc("Bilgi çekilemedi.");
	    }
		fromValid(true);
		klasorMuSecimi();
	});
}

function formTemizle(valid = true){
    $('#dznl_yetki .data').remove();
    $('#dzn_id').				val(null);
    $('#dzn_adi').				val(null);
    $('#dzn_link').				val(null);
    $('#dzn_klasor').			val("H");
    $('#dzn_gorunsunmu').		val("H");
    $('#dzn_icon').				val(null);
    $('#dzn_yetki').			val(null);
    $('#dzn_iconText').			val(null);
    $('#dzn_icon').removeClass();
    $('#dzn_create_gmt').		val(null);
    $('#dzn_create_user_id').	val(null);
    $('#dzn_gmt').				val(null);
    $('#dzn_user_id').			val(null);
    $('#dzn_klasor').prop("checked", false);
    btnDuzenle();
    if (valid) {
	    fromValid(true);
    }
}

function elemanSil(id){
	if ($("#"+id+" li").length == 0) {
    	$("#"+id).remove();
	}else{
	    hataAc("İçi boş olan klasörler silinebilir.");
	}
}

function fromValid( sfr = false){
    var snc1 = !sfr && ($('#dzn_adi').val() == null || $('#dzn_adi').val() == "");
    var snc2 = !sfr && ($('#dzn_klasor').val() == 'H' && ($('#dzn_link').val() == null || $('#dzn_link').val() == ""));
    
	fromAlanValid('#dzn_adi',snc1) ;
	fromAlanValid('#dzn_link',snc2) ;
	    
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

function btnDuzenle(){
    if($('#dzn_id').val() == null || $('#dzn_id').val() == ""){
	    $('#dznl_btn span').text("Kaydet");
	    $('#dznl_btn i').removeClass("fas").removeClass("fa-pencil-alt");
	    $('#dznl_btn i').addClass("fa").addClass("fa-floppy-o");
	}else{
	    $('#dznl_btn span').text("Düzenle");
	    $('#dznl_btn i').removeClass("fa").removeClass("fa-floppy-o");
	    $('#dznl_btn i').addClass("fas").addClass("fa-pencil-alt");
	}
}

</script>
<?php include (PREPATH.'footer.php'); ?>