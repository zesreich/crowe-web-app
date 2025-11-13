<?php
$pId = 208;
include_once '../../../First.php';
include_once PREPATH.'config/sozlesmeConfig.php';
include_once PREPATH.'soa/driveSoa.php';
$tklf_id = $_GET['id'];
$link = 'pages/is/sozlesme/sozlesmeDuzenleme.php?id='.$tklf_id;
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

$mkLink = Crud::getById(new Program() , 204 ) -> basit();

?>

<div class="row">

    <div class="col-lg-12 col-xl-12">
        <div class="card shadow" >
        	<div class="card-header bg-gradient-primary py-3">
            	<h6 class="m-0 font-weight-bold text-gray-300">SÖZLEŞME DÜZENLEME</h6>
            </div>
            <div class="card-body">
                <form class="user">
                    <div class="row mb-2">
                		<div class="col-lg-4 font-weight-bold text-gray-800 align-self-center text-right">No :</div>
                		<div class="col-lg-8 col-xl-6"><input type="text"  id="dznl_no" class="form-control form-control-user"  disabled ></div>
                	</div>
                    <div class="row mb-2">
                		<div class="col-lg-4 font-weight-bold text-gray-800 align-self-center text-right">Id :</div>
                		<div class="col-lg-8 col-xl-6"><input type="text"  id="dznl_id" class="form-control form-control-user" disabled ></div>
                	</div>
                    <div class="row mb-2">
                		<div class="col-lg-4 font-weight-bold text-gray-800 align-self-center  text-right">Müşteri :</div>
                		<div class="col-lg-2 col-xl-2">
                			<input type="text"  id="dznl_musteri_id" class="form-control form-control-user"  disabled />
            			</div>
                		<div class="col-lg-6 col-xl-4">
                			<input type="text"  id="dznl_musteri_adi" class="form-control form-control-user"  disabled />
            			</div>
                	</div>
                	<div class="row mb-2">
                		<div class="col-lg-4 font-weight-bold text-gray-800 align-self-center text-right">Durum :</div>
                		<div class="col-lg-8 col-xl-6"><input type="text"  id="dznl_durum" class="form-control form-control-user"  disabled ></div>
                	</div>
                    <div class="row mb-2">
                		<div class="col-lg-4 font-weight-bold text-gray-800 align-self-center  text-right">Dönem :</div>
                		<div class="col-lg-4 col-xl-3">
                    		<input id="dznl_donem_bas_trh" type="date" class="form-control"  disabled>
                		</div>
                		<div class="col-lg-4 col-xl-3">
                    		<input id="dznl_donem_bts_trh" type="date" class="form-control"  disabled>
                		</div>
                	</div>
                    <div class="row mb-2">
                		<div class="col-lg-4 font-weight-bold text-gray-800 align-self-center  text-right">Finansal Raporlama Çerçevesi :</div>
                		<div class="col-lg-8 col-xl-6">
                    		<select class=" custom-select form-control " id="dznl_frc_id" disabled>
                    			<option class="dznl_val" value="" >Seçiniz</option>
                    			<?php 
                    			foreach ($cmbFr as $v){
                			        echo '<option class="dznl_val" value="'.$v['id'].'">'.$v['adi'].'</option>';
                			     }
                                ?>
                            </select>
                		</div>
                	</div>
                    <div class="row mb-2">
                		<div class="col-lg-4 font-weight-bold text-gray-800 align-self-center  text-right">Genel Kurul Tarihi :</div>
                		<div class="col-lg-8 col-xl-6">
            				<input id="dznl_genel_tarihi" type="date" class="form-control" >
                		</div>
                	</div>
                    <div class="row mb-2">
                		<div class="col-lg-4 font-weight-bold text-gray-800 align-self-center  text-right">Müşteri İmza Tarihi :</div>
                		<div class="col-lg-8 col-xl-6">
            				<input id="dznl_musteri_tarihi" type="date" class="form-control">
                		</div>
                	</div>
                    <div class="row mb-2">
                		<div class="col-lg-4 font-weight-bold text-gray-800 align-self-center  text-right">Denetim Şirketi İmza Tarihi :</div>
                		<div class="col-lg-8 col-xl-6">
            				<input id="dznl_denetim_tarihi" type="date" class="form-control">
                		</div>
                	</div>
                	<div class="row mb-2">
                		<div class="col-lg-4 font-weight-bold text-gray-800 align-self-center  text-right">Raporların Teslim Tarihi :</div>
                		<div class="col-lg-8 col-xl-6">
            				<input id="dznl_teslim_tarihi" type="date" class="form-control" >
                		</div>
                	</div>
                    <div class="row pt-2">
                        <div id="dznl_btn" class="col-lg-12 text-center">
                        	<a href="#" class="btn btn-primary col-lg-6">
                        		<i class="fa fa-floppy-o"></i>
                                <span  class="text">Kaydet</span>
                            </a>
                        </div>
                    </div>
                	<div class="row pt-2">
                		<div class="col-2"></div>
						<div class="col-4">
							<div class="card shadow h-100">
								<div class="card-body">
									<table class="table table-bordered">
										<tbody id="imzasiz_btn"></tbody>
									</table>
								</div>
							</div>
						</div>
						<div class="col-4">
							<div class="card shadow h-100">
								<div class="card-body">
									<table class="table table-bordered">
										<tbody id="imzali_btn"></tbody>
									</table>
								</div>
							</div>
						</div>
                	</div>
                </form>
            </div>
        </div>
    </div>
</div>


<script >
	var jsCd = <?=$jsCd ?>;

    $("#dznl_btn").click(function(){
        $.post("<?=PREPATH.'post/sozlesmePost.php?tur=sozlesmeDznl' ?>",
	        {
            	id				: $('#dznl_no'    	 	  	).val(),
            	genel_tarihi   	: $('#dznl_genel_tarihi' 	).val(),
            	musteri_tarihi  : $('#dznl_musteri_tarihi' 	).val(),
            	denetim_tarihi  : $('#dznl_denetim_tarihi' 	).val(),
            	teslim_tarihi   : $('#dznl_teslim_tarihi' 	).val(),
		    },
		    function(data,status){
	    		if(status == "success"){
	    		    var obj = JSON.parse(data);
	    		    if (obj.hata == true) {
	    				hataAc(obj.hataMesaj);
	    		    }else{
	    		    	onayAc(obj.icerik);
	    		    	getir();
	    		    }
	    		}else if(status == "error"){
	    		   hataAc("Bir sorun oluştu.");
	    	    }
		    }
	    );
    });

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
        		    	$('#dznl_no'			).val(objx.tklfid);
        		    	$('#dznl_id'			).val(objx.id);
        		    	$('#dznl_musteri_id'	).val(objx.munvanid);
        		    	$('#dznl_musteri_adi'	).val(objx.munvan);
        		    	$('#dznl_durum'			).val(jsCd[objx.durum]);
        		    	$('#dznl_donem_bas_trh'	).val(formatTarihforForm(objx.donem_bas_trh));
        		    	$('#dznl_donem_bts_trh'	).val(formatTarihforForm(objx.donem_bts_trh));
        		    	$('#dznl_frc_id'		).val(objx.frc_id);
        		    	$('#dznl_genel_tarihi'	).val(formatTarihforForm(objx.genel_kurul_trh));
        		    	$('#dznl_musteri_tarihi').val(formatTarihforForm(objx.musteri_imza_trh));
        		    	$('#dznl_denetim_tarihi').val(formatTarihforForm(objx.denetim_imza_trh));
        		    	$('#dznl_teslim_tarihi' ).val(objx.teslim_tarihi == null ? '' : objx.teslim_tarihi.substring(0, 10));

        		    	imzasizButon(objx.imzasiz_drive_id,objx.durum);
        		    	imzaliButon(objx.imzali_drive_id,objx.durum);
        		    }
        		}else if(status == "error"){
        		   hataAc("Bir sorun oluştu.");
        	    }
        		loadEkranKapat();
    	    }
        );
    }
	
    function imzasiz_hazirla(){
    	loadEkranAc();
    	$.post('<?= PREPATH.'post/sozlesmePost.php?tur=imzasizSozlesmeYukle' ?>',
	        {
				id		: '<?=$tklf_id ?>',
				link 	: '<?=$link ?>">'
		    },
		    function(data,status){
	    		if(status == "success"){
	    		    var obj = JSON.parse(data);
	    		    if (obj.hata == true) {
	    				hataAc(obj.hataMesaj);
	    		    }else{
	    		    	onayAc(obj.icerik);
	    		    	getir();
	    		    }
	    		}else if(status == "error"){
	    		    hataAc("Bir sorun oluştu.");
	    	    }
	    		loadEkranKapat();
		    }
	    );
    }

    function imzasizButon(id,durum){
    	loadEkranAc();
    	var imzasiz = $('#imzasiz_btn');
    	imzasiz.empty();
    	imzasiz.append("<tr><td class='text-center' colspan='3'>Sözleşme Belgesi</td></tr><tr id='imzasiz_btn_tr'></tr>");
		if (id == null) {
			var imzasiz_tr = $('#imzasiz_btn_tr');
			imzasiz_tr.empty();
			imzasiz_tr.append(
			"<td style='width: 50%' class='p-0 m-0 text-center' colspan='3'>"+
				"<a href='#' onclick='imzasiz_hazirla();' class='btn btn-primary col-lg-6'>"+
            		"<i class='fa fa-floppy-o'></i>"+
                    "<span  class='text'>Sözleşme Hazırla</span>"+
                "</a>"+
			"</td>");
			loadEkranKapat();
		}else{
			$.post('<?= PREPATH.'post/drivePost.php?tur=belge_getir' ?>',
		        {
					link 	: '<?=$link ?>">',
					driveId	: id,
			    },
			    function(data,status){
		    		if(status == "success"){
		    		    var objIc = JSON.parse(data);
		    		    if (objIc.hata == true) {
		    				hataAc(objIc.hataMesaj);
		    		    }else{
		    		    	objIcx = objIc.icerik;
		    		    	var imzasiz_tr = $('#imzasiz_btn_tr');
		    				imzasiz_tr.empty();
		    				imzasiz_tr.append(
                    			"<td style='width: 33.33%' class='p-0 m-0 text-center'><a href='#' onclick='imzasiz_hazirla();' id='imzasiz_guncelle' class='btn btn-primary' ><i class='fas fa-sync-alt'></i></a></td>"+
                    			"<td style='width: 33.33%' class='p-0 m-0 text-center'><a href='"+objIcx.url+"' id='imzasiz_indir' 	class='btn btn-primary' ><i class='fas fa-cloud-download-alt'></i></a></td>"+
                    			"<td style='width: 33.33%' class='p-0 m-0 text-center'><a href='"+objIcx.web+"' id='imzasiz_ac' 		class='btn btn-primary' target='_blank' ><i class='fas fa-external-link-alt'></i></i></a></td>");	
		    		    }
		    		}else if(status == "error"){
		    		    hataAc("Bir sorun oluştu.");
		    	    }
		    		loadEkranKapat();
			    }
		    );
		}
    }

    function imzaliButon(id,durum){
    	loadEkranAc();
    	var imzasiz = $('#imzali_btn');
    	imzasiz.empty();
    	imzasiz.append("<tr><td class='text-center' colspan='3'>İmzalı Sözleşme Belgesi</td></tr><tr id='imzali_btn_tr'></tr>");
		var imzasiz_tr = $('#imzali_btn_tr');
		imzasiz_tr.empty();
		if (durum == <?=sozlesmeConfig::DURUM_IMZAYA_GONDER[0] ?>) {
			imzasiz_tr.append(
			"<td style='width: 50%' class='p-0 m-0 text-center'>"+
				"<a href='#' onclick='emailGonder();' class='btn btn-primary col-lg-8'>"+
            		"<i class='far fa-envelope'></i>"+
                    "<span  class='text'>   Mail Gönder</span>"+
                "</a>"+
			"</td>"+
			"<td style='width: 0%' class='p-0 m-0 text-center'></td>"+
			"<td style='width: 50%' class='p-0 m-0 text-center'>"+
				"<a href='#' onclick='elleGonder();' class='btn btn-primary col-lg-8'>"+
            		"<i class='far fa-hand-paper'></i>"+
                    "<span  class='text'>   Elle Gönderildi</span>"+
                "</a>"+
			"</td>"
			);
			loadEkranKapat();
		}else if(durum == <?=sozlesmeConfig::DURUM_IMZAYI_BEKLE[0] ?>){
			imzasiz_tr.append(
			'<td style="width: 50%" class="p-0 m-0 text-center" colspan="3">'+
				'<form enctype="multipart/form-data" action="<?= PREPATH.'post/sozlesmePost.php?tur=imzaliSozlesmeYukle' ?>" method="POST">'+
					'<input name="dosya" type="file" id="fUpload" hidden />'+ 
					'<input type="hidden" name="link" value="<?=$link ?>">'+
					'<input type="hidden" name="tklfid" value="<?=$tklf_id ?>">'+
					'<input type="hidden" name="driveId" value="<?= $tklf['main_drive_id'] ?>">'+
					'<input type="submit" value="Submit" id="fsubmit" hidden>'+
					'<a id="upload" href="#" class="btn btn-success col-12" >'+
    					'<i class="fa fa-floppy-o"></i>'+
                        '<span  class="text">Sözleşme Yükle	</span>'+
					'</a>'+
				'</form>'+
			'</td>');
            $('#upload').click(function () {
                $('#fUpload').click();
            });
            
            $('#fUpload').bind("change", function () {
                $('#fsubmit').click();
            });
            loadEkranKapat();
		}else if(durum == <?=sozlesmeConfig::DURUM_TAMAMLADI[0] ?>){
			$.post('<?= PREPATH.'post/drivePost.php?tur=belge_getir' ?>',
		        {
					link 	: '<?=$link ?>">',
					driveId	: id,
			    },
			    function(data,status){
		    		if(status == "success"){
		    		    var objIc = JSON.parse(data);
		    		    if (objIc.hata == true) {
		    				hataAc(objIc.hataMesaj);
		    		    }else{
		    		    	objIcx = objIc.icerik;
		    		    	var imzasiz_tr = $('#imzali_btn_tr');
		    				imzasiz_tr.empty();
		    				imzasiz_tr.append(
                    			"<td style='width: 33.33%' class='p-0 m-0 text-center'>"+
                				'<form enctype="multipart/form-data" action="<?= PREPATH.'post/sozlesmePost.php?tur=imzaliSozlesmeYukle' ?>" method="POST">'+
                						'<input name="dosya" type="file" id="fUpload" hidden />'+ 
                						'<input type="hidden" name="link" value="<?=$link ?>">'+
                						'<input type="hidden" name="tklfid" value="<?=$tklf_id ?>">'+
                						'<input type="hidden" name="driveId" value="<?= $tklf['main_drive_id'] ?>">'+
                						'<input type="submit" value="Submit" id="fsubmit" hidden>'+
                						'<a id="upload" href="#" class="btn btn-primary" >'+
                							'<i class="fas fa-cloud-upload-alt"></i>'+
                						'</a>'+
                					'</form>'+
                    			"</td>"+
                    			"<td style='width: 33.33%' class='p-0 m-0 text-center'><a href='"+objIcx.url+"' id='imzali_indir' class='btn btn-primary' ><i class='fas fa-cloud-download-alt'></i></a></td>"+
                    			"<td style='width: 33.33%' class='p-0 m-0 text-center'><a href='"+objIcx.web+"' id='imzali_ac' 	class='btn btn-primary' target='_blank' ><i class='fas fa-external-link-alt'></i></i></a></td>");
		    		    }
		    		}else if(status == "error"){
		    		    hataAc("Bir sorun oluştu.");
		    	    }
		    		$('#upload').click(function () {
		                $('#fUpload').click();
		            });
		            
		            $('#fUpload').bind("change", function () {
		                $('#fsubmit').click();
		            });
		    		loadEkranKapat();
			    }
		    );
		}else{
			loadEkranKapat();
		}
    }


    function emailGonder(){
    	loadEkranAc();
    	$.post('<?= PREPATH.'post/sozlesmePost.php?tur=sozlesmeEmailGonder' ?>',
	        {
				id		: '<?=$tklf_id ?>',
				link 	: '<?=$link ?>">'
		    },
		    function(data,status){
	    		if(status == "success"){
	    		    var obj = JSON.parse(data);
	    		    if (obj.hata == true) {
	    				hataAc(obj.hataMesaj);
	    		    }else{
	    		    	getir();
	    		    	onayAc(obj.icerik);
	    		    }
	    		}else if(status == "error"){
	    		    hataAc("Bir sorun oluştu.");
	    	    }
	    		loadEkranKapat();
		    }
	    );
    }

    function elleGonder(){
    	loadEkranAc();
    	$.post('<?= PREPATH.'post/sozlesmePost.php?tur=sozlesmeElleGonder' ?>',
	        {
				id		: '<?=$tklf_id ?>',
		    },
		    function(data,status){
	    		if(status == "success"){
	    		    var obj = JSON.parse(data);
	    		    if (obj.hata == true) {
	    				hataAc(obj.hataMesaj);
	    		    }else{
	    		    	getir();
	    		    	onayAc(obj.icerik);
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