<?php
$pId = 185;
include_once '../../../First.php';
include_once PREPATH . 'header.php';

$tbl    = new Denetim();
$gelen  = Crud::all($tbl);
// $cmbFr  = Base::basitList(Crud::all(new TklfFinansRapor()));
// $cmbPr  = Base::basitList(Crud::all(new TklfParaBirimi()));
// $cmbDl  = Base::basitList(Crud::all(new TklfDil()));

$lstPg    = Crud::getById(new Program() , 169 ) -> basit();

$link = 'pages/is/denetim/teklifYonOnyTanim.php'. (isset($_GET['id']) ? '?id='.$_GET['id'] : '');

?>
<div class="row">

    <div class="col-lg-12 col-xl-12">
        <div class="card shadow" >
        	<div class="card-header bg-gradient-primary py-3">
            	<h6 class="m-0 font-weight-bold text-gray-300"><?=$tbl->vt_Adi().' DÜZENLEME'?></h6>
            </div>
            <div class="card-body">
                <form class="user">
                	<div class="row mb-2">
                		<div class="col-lg-4"></div>
                      	<div class="col-6" id = "sil_Div"  >
                			<button type="button" class="btn btn-danger col-lg-12" data-toggle="modal" onclick="dataSil()" data-target="#myModal" id="sil_buton" >
                				<i class="fas fa-exclamation"></i>      Teklif Sil   <i class="fas fa-exclamation"></i>    
            				</button>
                  		</div>
            		</div>
                    <div class="row mb-2">
                		<div class="col-lg-4 font-weight-bold text-gray-800 align-self-center text-right">Id :</div>
                		<div class="col-lg-8" id="dznl_id2"></div>
                	</div>
                    <div class="row mb-2">
                		<div class="col-lg-4 font-weight-bold text-gray-800 align-self-center  text-right">Müşteri :</div>
                		<div class="col-lg-8" id="dznl_musteri_id2"></div>
                	</div>
                    <div class="row mb-2">
                		<div class="col-lg-4 font-weight-bold text-gray-800 align-self-center  text-right ">Müşteri Email :</div>
                		<div class="col-lg-8 text-danger" id="dznl_email2"></div>
                	</div>
                    <div class="row mb-2">
                		<div class="col-lg-4 font-weight-bold text-gray-800 align-self-center  text-right">Teklif Tarihi :</div>
                		<div class="col-lg-8" id="dznl_teklif_tarihi2"></div>
                	</div>
                    <div class="row mb-2">
                		<div class="col-lg-4 font-weight-bold text-gray-800 align-self-center  text-right">Denetime Tabi Olma Nedeni :</div>
                		<div class="col-lg-8" id="dznl_dton_id2"></div>
                	</div>
                    <div class="row mb-2">
                		<div class="col-lg-4 font-weight-bold text-gray-800 align-self-center  text-right">Dönem :</div>
                		<div class="col-lg-4" id="dznl_donem_bas_trh2"></div>
                	</div>
                    <div class="row mb-2">
                		<div class="col-lg-4 font-weight-bold text-gray-800 align-self-center  text-right">Finansal Raporlama Çerçevesi :</div>
                		<div class="col-lg-8" id="dznl_frc_id2"></div>
                	</div>
                    <div class="row mb-2">
                		<div class="col-lg-4 font-weight-bold text-gray-800 align-self-center  text-right">Tutar :</div>
                		<div class="col-lg-8" id="dznl_tutar2"></div>
                	</div>
                    <div class="row mb-2">
                		<div class="col-lg-4 font-weight-bold text-gray-800 align-self-center  text-right">Para Birimi</div>
                		<div class="col-lg-8" id="dznl_para_birimi_id2"></div>
                	</div>
                    <div class="row mb-2">
                		<div class="col-lg-4 font-weight-bold text-gray-800 align-self-center  text-right">Raporlama Dili</div>
                		<div class="col-lg-8" id="dznl_dil_id2"></div>
                	</div>
                    <div class="row mb-2">
                		<div class="col-lg-4 font-weight-bold text-gray-800 align-self-center  text-right">Raporlama Şekli</div>
                		<div class="col-lg-8" id="dznl_raporsekli_id2"></div>
                	</div>
                    <div class="row mb-2">
                		<div class="col-lg-4 font-weight-bold text-gray-800 align-self-center  text-right">Düzenleyici Kurum</div>
                		<div class="col-lg-8" id="dznl_duzenkurum_id2"></div>
                	</div>
                	<div class="row mb-2">
                		<div class="col-lg-4 font-weight-bold text-gray-800 align-self-center  text-right">Onaylayan Tarihi :</div>
                		<div class="col-lg-8" id="dznl_yonay_trh2"></div>
                	</div>
                    <div class="row mb-2">
                		<div class="col-lg-4 font-weight-bold text-gray-800 align-self-center  text-right">Onaylayan Kişi :</div>
                		<div class="col-lg-8" id="dznl_yonay_id2"></div>
                	</div>
                	<div class="row mb-2">
                		<div class="col-lg-4 font-weight-bold text-gray-800 align-self-center  text-right">Açıklama :</div>
                		<div class="col-lg-8  col-xl-6"><textarea rows="3" class="form-control" id="dznl_bilgi" ></textarea></div>
                	</div>
					<div class="row mb-2">
                		<div class="col-lg-4 font-weight-bold text-gray-800 align-self-center  text-right">Düzenleme isteme nedeni :</div>
                		<div class="col-lg-8 col-xl-6"><input type="text"  id="dznl_neden" class="form-control form-control-user"></div>
                	</div>
                    <div class="row pt-2">
                        <div class="col-lg-3 text-center">
                        	<a href="#" id="dznl_red" class="btn btn-danger col-lg-6" >
                        		<i class="fa fa-reply"></i>
                                <span class="text">Düzenlemeye Geri Gönder</span>
                            </a>
                        </div>
                        <div id="dznl_onay" class="col-lg-3 col-lg-offset-4 text-center">
                        	<a href="#"  class="btn btn-success col-lg-6" >
                        		<i class="fa fa-share"></i>
                                <span class="text">Müşteriye Onaya Gönder</span>
                            </a>
                        </div>
                        <div id="dznl_monay" class="col-lg-3 col-lg-offset-4 text-center">
                        	<a href="#"  class="btn btn-success col-lg-6" >
                        		<i class="fa fa-share"></i>
                                <span class="text">Müşteri İçin Onayla</span>
                            </a>
                        </div>
                        <div id="dznl_kaydet" class="col-lg-3 col-lg-offset-4 text-center">
                        	<a href="#" class="btn btn-primary col-lg-6">
                        		<i class="fa fa-floppy-o"></i>
                                <span  class="text">Kaydet</span>
                            </a>
                        </div>
                        <div id = "pdf" class="col-lg-3 text-center"></div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="myModal" role="dialog">
	<div class="modal-dialog modal-xl">
		<div class="modal-content">
			<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 ">
				<div class="card" >
					<div class="card-block">
						<div id="txtHint"></div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<script >

    function dataSil(){
    	$.post('<?php echo PREPATH.'pages/genel/kayitSil.php'?>',
            {
    			tablo		: '<?= get_class($tbl)?>',
    			id			: $('#dznl_id2').text(),
    			mesaj		: 'Teklifi ve bağlı olduğu tüm işlemleri silmek istediğinize emin misiniz ?',
    			donusLink	: '<?= PREPATH.'pages/is/denetim/teklifListesi.php'?>',
    			link 		: '<?= $link?>',
    			nkt			: '<?= PREPATH?>',
        	},
        	function(data, status){
        		if(status == "success"){
        			$("#txtHint").empty();
        			$("#txtHint").append(data);
        		}else if(status == "error"){
        		    hataAc("Bilgi çekilemedi.");
        	    }
        	}
        );
    }

    function sozlesmePdfGetir(driveId){
        if (driveId != null) {
        	$.post('<?= PREPATH.'post/drivePost.php?tur=belge_getir' ?>',
                {
        			link 	: '<?=$link ?>">',
        			driveId	: driveId,
        	    },
        	    function(data,status){
            		if(status == "success"){
            		    var objIc = JSON.parse(data);
            		    if (objIc.hata == true) {
            				hataAc(objIc.hataMesaj);
            		    }else{
            		    	objIcx = objIc.icerik;
            		    	var td = $('#pdf');
            		    	td.empty();
            		    	td.append('<a href="'+objIcx.web+'" target="_blank" title="PDF"  class="btn btn-warning col-lg-6 " ><span class="text"><i class="fas fa-file-pdf"></i>  Teklifi Göster</span></a>');
            		    }
            		}else if(status == "error"){
            		    hataAc("Bir sorun oluştu.");
            	    }
            		loadEkranKapat();
        	    }
            );
		}
    }

	function detayAc(id){
		loadEkranAc();
	    $.get( "<?php echo PREPATH.'post/genelPost.php?tur=getById&tablo=denetim&id='?>"+id, function(data, status){
			if(status == "success"){
			    var objx = JSON.parse(data);
			    var obj = JSON.parse(objx.icerik);
			    
			    if (obj.durum_id.id == <?=DenetimDurum::DURUM_ONAY_MUSTERI ?>) {
					$('#dznl_onay').hide();
				    $('#dznl_monay').show();
				}else if (obj.durum_id.id == <?=DenetimDurum::DURUM_ONAY_YONETICI ?>) {
					$('#dznl_onay').show();
				    $('#dznl_monay').hide();
				}else if (obj.durum_id.id == <?=DenetimDurum::DURUM_ONAYLI ?>) {
					$('#dznl_onay').hide();
				    $('#dznl_monay').hide();
				}else{
				    hataAc("Bir sorun oluştu.");
				    return;
				}
			    $('#dznl_id2').				text(obj.id);
			    $('#dznl_musteri_id2').		text(obj.musteri_id.id+' - '+obj.musteri_id.unvan);
			    $('#dznl_email2').			text(obj.email);
			    $('#dznl_teklif_tarihi2').	text(formatTarih(obj.teklif_tarihi));
			    $('#dznl_donem_bas_trh2').	text(formatTarih(obj.donem_bas_trh) +'  -  '+formatTarih(obj.donem_bts_trh));
			    $('#dznl_frc_id2').			text(obj.frc_id.adi);
			    $('#dznl_raporsekli_id2').	text(obj.raporsekli_id.adi);
			    $('#dznl_duzenkurum_id2').	text(obj.duzenkurum_id.adi);
			    $('#dznl_tutar2').			text(formatPara(obj.tutar));
			    $('#dznl_para_birimi_id2').	text(obj.para_birimi_id.adi);
			    $('#dznl_dil_id2').			text(obj.dil_id.adi);
			    $('#dznl_dton_id2').		text(obj.dton_id.aciklama);
			    $('#dznl_yonay_trh2').		text(formatTarih(obj.yonay_trh));
			    $('#dznl_bilgi').			text(obj.bilgi);

			    if(obj.dil_id.id != <?= TklfDil::TR?> || obj.dil_id.id != <?= TklfDil::TR_ING ?>){
			    	sozlesmePdfGetir(obj.tr_szlsm_drive_id);
			    }
			    if(obj.dil_id.id != <?= TklfDil::ING ?> || obj.dil_id.id != <?= TklfDil::TR_ING ?>){
			    	sozlesmePdfGetir(obj.eng_szlsm_drive_id);
			    }
			    
			    if (obj.yonay_id != null && obj.yonay_id != '') {
    			    $.get( "<?php echo PREPATH.'post/genelPost.php?tur=getById&tablo=kullanici&id='?>"+obj.yonay_id.id, function(data2, status2){
        				if(status2 == "success"){
        					var obj2 = JSON.parse(JSON.parse(data2).icerik);
            			    $('#dznl_yonay_id2').text(obj2.ad + ' '+obj2.soyad);
        				}
    			    });
			    }
			}else if(status == "error"){
			    hataAc("Bilgi çekilemedi.");
		    }
			loadEkranKapat();
		});
	}


	
	function selectDuzenle(){
		$('.dznl_val').remove('[selected="selected"]');
	}

	//Form Kaydet butonu 
    $("#dznl_red").click(function(){
    	loadEkranAc();
        $.post("<?=PREPATH.'post/denetimPost.php?fnk=tklfRed' ?>",
	        {
            	id		: $('#dznl_id2').text(),
            	neden	: $('#dznl_neden').val()
		    },
		    function(data,status){
	    		if(status == "success"){
	    		    var obj = JSON.parse(data);
	    		    if (obj.hata == true) {
	    				hataAc(obj.hataMesaj);
	    		    }else{
	    				window.open('<?=PREPATH.$lstPg['program_link'] ?>', '_self');
	    		    }
	    		}else if(status == "error"){
	    		    hataAc("Bir sorun oluştu.");
	    	    }
	    		loadEkranKapat();
		    }
	    );
	});

	$("#dznl_kaydet").click(function(){
		loadEkranAc();
        $.post("<?=PREPATH.'post/denetimPost.php?fnk=tklfAckKydt' ?>",
	        {
            	id		: $('#dznl_id2').text(),
            	bilgi	: $('#dznl_bilgi').val()
		    },
		    function(data,status){
	    		if(status == "success"){
	    		    var obj = JSON.parse(data);
	    		    if (obj.hata == true) {
	    				hataAc(obj.hataMesaj);
	    		    }else{
	    		    	detayAc($('#dznl_id2').text());
	    		    }
	    		}else if(status == "error"){
	    		    hataAc("Bir sorun oluştu.");
	    	    }
	    		loadEkranKapat();
		    }
	    );
	});

	$("#dznl_onay").click(function(){
		loadEkranAc();
        $.post("<?=PREPATH.'post/denetimPost.php?fnk=tklfOnay' ?>",
	        {
            	id		: $('#dznl_id2').text()
		    },
		    function(data,status){
	    		if(status == "success"){
	    		    var obj = JSON.parse(data);
	    		    if (obj.hata == true) {
	    				hataAc(obj.hataMesaj);
	    		    }else{
	    		    	detayAc($('#dznl_id2').text());
	    		    }
	    		}else if(status == "error"){
	    		    hataAc("Bir sorun oluştu.");
	    	    }
	    		loadEkranKapat();
		    }
	    );
	});

	$("#dznl_monay").click(function(){
		loadEkranAc();
        $.post("<?=PREPATH.'post/denetimPost.php?fnk=tklfMOnay' ?>",
	        {
            	id		: $('#dznl_id2').text()
		    },
		    function(data,status){
	    		if(status == "success"){
	    		    var obj = JSON.parse(data);
	    		    if (obj.hata == true) {
	    				hataAc(obj.hataMesaj);
	    		    }else{
	    		    	window.open('<?=PREPATH.$lstPg['program_link'] ?>', '_self');
	    		    }
	    		}else if(status == "error"){
	    		    hataAc("Bir sorun oluştu.");
	    	    }
	    		loadEkranKapat();
		    }
	    );
	});


	detayAc(<?=isset($_GET['id']) ? $_GET['id'] : null;?>);
	
</script>
<?php include (PREPATH.'footer.php'); ?>