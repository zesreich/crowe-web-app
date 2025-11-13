<?php
$pId = 170;
include_once '../../../First.php';
include_once PREPATH.'soa/driveSoa.php';

$link = 'pages/is/denetim/teklifTanim.php'. (isset($_GET['id']) ? '?id='.$_GET['id'] : '');
driveSoa::baglan($link);
include_once PREPATH . 'header.php';

$tbl    = new Denetim();
$gelen  = Crud::all($tbl);
$cmbFr  = Base::basitList(Crud::all(new TklfFinansRapor()));
$cmbPr  = Base::basitList(Crud::all(new TklfParaBirimi()));
$cmbDl  = Base::basitList(Crud::all(new TklfDil()));
$cmbRd  = Base::basitList(Crud::all(new TklfRaporDil()));
$cmbDk  = Base::basitList(Crud::all(new TklfDuzenleyiciKurum()));
$cmbRs  = Base::basitList(Crud::all(new TklfRaporlamaSekli()));
$cmbOs  = Base::basitList(Crud::all(new TklfOzelSartlar()));

$lstPg    = Crud::getById(new Program() , 169 ) -> basit();

?>
<div class="row">

    <div class="col-lg-12 col-xl-12">
        <div class="card shadow" >
        	<div class="card-header bg-gradient-primary py-3">
            	<h6 class="m-0 font-weight-bold text-gray-300">YENİ TEKLİF OLUŞTUR</h6>
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
                	<div class="row mb-2" id="red_neden"  >
                		<div class="col-lg-4 font-weight-bold text-gray-800 align-self-center text-danger text-right">Red Nedeni :</div>
                		<div class="col-lg-8 font-weight-bold text-danger" id="dznl_red_neden"></div>
                	</div>
                    <div class="row mb-2">
                		<div class="col-lg-4 font-weight-bold text-gray-800 align-self-center text-right">Id :</div>
                		<div class="col-lg-8 col-xl-6"><input type="text"  id="dznl_id" class="form-control form-control-user" disabled></div>
                	</div>
                    <div class="row mb-2">
                		<div class="col-lg-4 font-weight-bold text-gray-800 align-self-center  text-right">Müşteri :</div>
                		<div class="col-lg-2">
                			<input type="text"  id="dznl_musteri_id" class="form-control form-control-user" disabled>
            			</div>
                		<div class="col-lg-1">
							<button type="button" class="btn btn-primary col-lg-12" data-toggle="modal" onclick="miniAra('Musteri')" data-target="#myModal" id="mstrBtn" >Bul</button>
                		</div>
                		<div class="col-lg-5 col-xl-3">
                			<input type="text"  id="dznl_musteri_adi" class="form-control form-control-user" disabled>
            			</div>
                	</div>
                	<div class="row mb-2">
                		<div class="col-lg-4 font-weight-bold text-gray-800 align-self-center  text-right">Müşteri email : <a href="#" data-toggle="tooltip" title="Noktalı virgülle ayırarak birden fazla mail girilebilir. Örnek: bir@mail.com;iki@mail.com"><i style="font-size:18px" class="fa tooltp">&#xf05a;</i></a> </div>
                		<div class="col-lg-8 col-xl-6"><input type="text"  id="dznl_musteri_email" class="form-control form-control-user"></div>
                	</div>
                    <div class="row mb-2">
                		<div class="col-lg-4 font-weight-bold text-gray-800 align-self-center  text-right">Teklif Tarihi :</div>
                		<div class="col-lg-8 col-xl-6">
            				<input id="dznl_teklif_tarihi" type="date" class="form-control" >
                		</div>
                	</div>
                    <div class="row mb-2">
                		<div class="col-lg-4 font-weight-bold text-gray-800 align-self-center  text-right">Denetime Tabi Olma Nedeni :</div>
                		<div class="col-lg-8 col-xl-6">
                			<input type="hidden" id="dznl_dton_id" />
                    		<div id="cmb">
                            </div>
                		</div>
                	</div>
                    <div class="row mb-2">
                		<div class="col-lg-4 font-weight-bold text-gray-800 align-self-center  text-right">Dönem :</div>
                		<div class="col-lg-4 col-xl-3">
                    		<input id="dznl_donem_bas_trh" type="date" class="form-control" >
                		</div>
                		<div class="col-lg-4 col-xl-3">
                    		<input id="dznl_donem_bts_trh" type="date" class="form-control" >
                		</div>
                	</div>
                    <div class="row mb-2">
                		<div class="col-lg-4 font-weight-bold text-gray-800 align-self-center  text-right">Finansal Raporlama Çerçevesi :</div>
                		<div class="col-lg-8 col-xl-6">
                    		<select class=" custom-select form-control " id="dznl_frc_id">
                    			<option class="dznl_val" value="" selected="selected">Seçiniz</option>
                    			<?php 
                    			foreach ($cmbFr as $v){
                    			     echo '<option class="dznl_val" value="'.$v['id'].'">'.$v['adi'].'</option>';
                			     }
                                ?>
                            </select>
                		</div>
                	</div>
                    <div class="row mb-2">
                		<div class="col-lg-4 font-weight-bold text-gray-800 align-self-center  text-right">Tutar (KDV Hariç) :</div>
                		<div class="col-lg-8 col-xl-6"><input type="text"  id="dznl_tutar" class="form-control form-control-user" ></div>
                	</div>
                    <div class="row mb-2">
                		<div class="col-lg-4 font-weight-bold text-gray-800 align-self-center  text-right">Para Birimi</div>
                		<div class="col-lg-8 col-xl-6">
                    		<select class=" custom-select form-control " id="dznl_para_birimi_id">
                    			<option class="dznl_val" value="" selected="selected">Seçiniz</option>
                    			<?php 
                    			foreach ($cmbPr as $v){
                    			     echo '<option class="dznl_val" value="'.$v['id'].'">'.$v['adi'].'</option>';
                			     }
                                ?>
                            </select>
                		</div>
                	</div>
                    <div class="row mb-2">
                		<div class="col-lg-4 font-weight-bold text-gray-800 align-self-center  text-right">Teklif Dili</div>
                		<div class="col-lg-8 col-xl-6">
                    		<select class=" custom-select form-control " id="dznl_dil_id">
                    			<option class="dznl_val" value="" selected="selected">Seçiniz</option>
                    			<?php 
                    			foreach ($cmbDl as $v){
                    			     echo '<option class="dznl_val" value="'.$v['id'].'">'.$v['adi'].'</option>';
                			     }
                                ?>
                            </select>
                		</div>
                	</div>
                    <div class="row mb-2">
                		<div class="col-lg-4 font-weight-bold text-gray-800 align-self-center  text-right">Raporlama Şekli</div>
                		<div class="col-lg-8 col-xl-6">
                    		<select class=" custom-select form-control " id="dznl_raporsekli_id">
                    			<option class="dznl_val" value="" selected="selected">Seçiniz</option>
                    			<?php 
                    			foreach ($cmbRs as $v){
                    			     echo '<option class="dznl_val" value="'.$v['id'].'">'.$v['adi'].'</option>';
                			     }
                                ?>
                            </select>
                		</div>
                	</div>
                    <div class="row mb-2">
                		<div class="col-lg-4 font-weight-bold text-gray-800 align-self-center  text-right">Düzenleyici Kurum</div>
                		<div class="col-lg-8 col-xl-6">
                    		<select class=" custom-select form-control " id="dznl_duzenkurum_id">
                    			<option class="dznl_val" value="" selected="selected">Seçiniz</option>
                    			<?php 
                    			foreach ($cmbDk as $v){
                    			     echo '<option class="dznl_val" value="'.$v['id'].'">'.$v['adi'].'</option>';
                			     }
                                ?>
                            </select>
                		</div>
                	</div>
                	
                	<div class="row mb-2">
                		<div class="col-lg-4 font-weight-bold text-gray-800 align-self-center  text-right">Rapor Dili</div>
                		<div class="col-lg-8 col-xl-6">
                    		<select class=" custom-select form-control " id="dznl_rapor_dil_id">
                    			<option class="dznl_val" value="" selected="selected">Seçiniz</option>
                    			<?php 
                    			foreach ($cmbRd as $v){
                    			     echo '<option class="dznl_val" value="'.$v['id'].'">'.$v['adi'].'</option>';
                			     }
                                ?>
                            </select>
                		</div>
                	</div>
                	
                	<div class="row mb-2">
                		<div class="col-lg-4 font-weight-bold text-gray-800 align-self-center  text-right">Özel Şartlar</div>
                		<div class="col-lg-8 col-xl-6">
                			<div class="row mb-2">
                				<div class="col-10 text-center">
                            		<select id="ozelsartLst" class=" custom-select form-control " >
                            			<option class="dznl_val" value="" selected="selected">Seçiniz</option>
                            			<?php 
                            			foreach ($cmbOs as $v){
                            			     echo '<option class="dznl_val" value="'.$v['id'].'">'.$v['aciklama'].'</option>';
                        			     }
                                        ?>
                                    </select>
                                </div>
                                <div id="btn_ozel_sart" class="col-2 text-center">
                                	<a href="#" class="btn btn-primary col-lg-6">
                                		<i class="fas fa-angle-down"></i>
                                    </a>
                                </div>
                            </div>
                            <textarea rows="5" class="form-control" id="dznl_ozel_sart" ></textarea>
                		</div>
                	</div>
                	
                	<div class="row mb-2">
                		<div class="col-lg-4 font-weight-bold text-gray-800 align-self-center  text-right">Açıklama :</div>
                		<div class="col-lg-8  col-xl-6"><textarea rows="3" class="form-control" id="dznl_bilgi" ></textarea></div>
                	</div>

                    <div class="row pt-2">
                    	<div class="col-3 text-center"></div>
                        <div id="dznl_btn" class="col-3 text-center">
                        	<a href="#" class="btn btn-primary col-lg-6">
                        		<i class="fa fa-floppy-o"></i>
                                <span  class="text">Kaydet</span>
                            </a>
                        </div>
                        <div class="col-3 text-center">
                        	<a href="#" id="dznl_sil" class="btn btn-danger col-lg-6" onclick="formTemizle()">
                        		<i class="fa fa-trash"></i>
                                <span class="text">Temizle</span>
                            </a>
                        </div>
                    </div>
                    
                    <div class="row pt-2">
                    	<div class="col-3 text-center"></div>
                        <?php  if(isset($_GET['id'])){?>
                        <div id="dznl_onay" class="col-3 text-center">
                        	<a href="#"  class="btn btn-success col-lg-6" >
                        		<i class="fa fa-share"></i>
                                <span class="text">Yönetim Onaya Gönder</span>
                            </a>
                        </div>
                        <?php }?>
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
			<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 mb-12">
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
    function uygunIdGetir() {
        var trh = $('#dznl_teklif_tarihi' ).val();
        date = (typeof trh) == 'string' ? new Date(trh) : trh;
	    let year = date.getFullYear();
    	$.post('<?= PREPATH.'post/denetimPost.php?fnk=uygunIdler&trh='?>'+year,
        	function(data, status){
        		if(status == "success"){
        			var snc = JSON.parse(data).icerik;
        			var txt = '';
        			for (i = 0; i < snc.length; i++) {
            			if (txt != '') {
        					txt += ', '+snc[i];
						}else{
							txt = snc[i];
						}
        			}
        			$('#dznl_id').attr("placeholder", '>> '+txt+' <<');
        		}else if(status == "error"){
        		    hataAc("Bilgi çekilemedi.");
        	    }
        	}
        );
    }

    function silBtnDzn(){
        if($('#dznl_id').val() == null || $('#dznl_id').val() == ""){
    		document.getElementById("sil_Div").style.visibility = "hidden";
    	}else{
        	document.getElementById("sil_Div").style.visibility = "visible";
    	}
    }
    
    function dataSil(){
    	$.post('<?php echo PREPATH.'pages/genel/kayitSil.php'?>',
            {
    			tablo		: '<?= get_class($tbl)?>',
    			id			: $('#dznl_id').val(),
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
	    		    	td.append('<a href="'+objIcx.web+'" target="_blank" title="PDF"  class="btn btn-warning col-lg-6 " ><span class="text"><i class="fas fa-file-pdf"></i>  Teklifi Göster</span></a>');
	    		    }
	    		}else if(status == "error"){
	    		    hataAc("Bir sorun oluştu.");
	    	    }
	    		loadEkranKapat();
		    }
	    );
    }

	$('.tooltp').tooltip();

	document.getElementById("dznl_tutar").onblur =function (){    
        this.value = tutarDuzenle(this.value);
    }

	function tutarDuzenle(value){
		return parseFloat(value.replace(/,/g, ""))
        .toFixed(2)
        .toString()
        .replace(/\B(?=(\d{3})+(?!\d))/g, ",")
	}
	
	function sec(sira,dgr){
	    if (dgr == -1) {
		    kod = -1;
	    }else{
    		kod = dgr.value;
	    }
	    secdeger(sira,kod,-1);
	}

	async function secdeger(sira,kod,val){
		$('#dznl_dton_id').val(null);

	    $("[class*=slct]").each(function( index ) {
		    if (index >= sira) {
		    	$(this).remove();
			}
		});

	    if (kod != null & kod != '' ){
			const rslt = await $.get("<?php echo PREPATH.'post/denetimNedeniPost.php?tur=nedenlerByUstid&ustid='?>"+kod);
			var objx = JSON.parse(rslt);
		    if (objx.hata == false) {
        		var table = $('#cmb');
        		var son 	= '';
    		    
    			son += '<div  class="row slct" >'
				son += '<div class="col-12" >'
			    son += '<select class="my-2 custom-select form-control" id="s'+(sira)+'" onchange="sec('+(sira+1)+',this)" >';
 			    var obj = JSON.parse(objx.icerik);
    			if (obj != null && obj != ""){
    				son += '<option class="dznl_val" value="" '+ ((val == -1) ? 'selected="selected"' : '') +'>Seçiniz...</option>';
    				for (i = 0; i < obj.length; i++) {
    					son += '<option class="dznl_val" value="'+obj[i].id+'" '+ ((val == obj[i].id) ? 'selected="selected"' : '') +'>'+obj[i].aciklama+'</option>';
    				}
    			}
    			son += '</select></div>';
			    son += '</div>';
				table.append(son);
				if (val != -1) {
				    $('#dznl_dton_id').val(val);
				}
		    }else{
    		    $('#dznl_dton_id').val(kod);
		    }

		}
    };


    formTemizle();

    async function  detayAc(id){
    	loadEkranAc();
		if (id == null) {
		    sec(0,-1);
		}else{
		    const res = await $.get( "<?php echo PREPATH.'post/genelPost.php?tur=getById&tablo=denetim&id='?>"+id);
		    var objx = JSON.parse(res);
		    var obj = JSON.parse(objx.icerik);
			if (obj.durum_id.id == <?=DenetimDurum::DURUM_DUZENLE ?>) {
				$('#dznl_red_neden').text(obj.teklif_red_ack);
			}else{
				$('#red_neden').hide();
			}

		    $('#dznl_id').				val(obj.id);
		    $('#dznl_musteri_id').		val(obj.musteri_id.id);
		    $('#dznl_musteri_adi').		val(obj.musteri_id.unvan);
		    $('#dznl_teklif_tarihi').	val(obj.teklif_tarihi.substring(0, 10));
		    $('#dznl_donem_bas_trh').	val(obj.donem_bas_trh.substring(0, 10));
		    $('#dznl_donem_bts_trh').	val(obj.donem_bts_trh.substring(0, 10));
		    $('#dznl_frc_id').			val(obj.frc_id.id);
		    $('#dznl_raporsekli_id').	val(obj.raporsekli_id.id);
		    $('#dznl_duzenkurum_id').	val(obj.duzenkurum_id.id);
		    $('#dznl_tutar').			val(tutarDuzenle(obj.tutar));
		    $('#dznl_para_birimi_id').	val(obj.para_birimi_id.id);
		    $('#dznl_dil_id').			val(obj.dil_id.id);
		    $('#dznl_rapor_dil_id').	val(obj.rapor_dil_id.id);
		    $('#dznl_musteri_email').	val(obj.email);
		    $('#dznl_bilgi').			val(obj.bilgi);
		    $('#dznl_ozel_sart').		val(obj.ozel_sart);

			var pdf = $("#dznl_pdf");
		    if(obj.dil_id.id != <?= TklfDil::TR?> || obj.dil_id.id != <?= TklfDil::TR_ING ?>){
		    	pdf.append(sozlesmePdfGetir(obj.tr_szlsm_drive_id));
		    }else if(obj.dil_id.id != <?= TklfDil::ING ?> || obj.dil_id.id != <?= TklfDil::TR_ING ?>){
		    	pdf.append(sozlesmePdfGetir(obj.eng_szlsm_drive_id));
		    }

		    
	    	silBtnDzn();
			const res2 = await $.get( '<?php echo PREPATH.'post/denetimNedeniPost.php?tur=all&id='?>'+obj.dton_id.id);
			var srr = 0;
			var snclar = JSON.parse(res2).icerik;
	    	for (var j = 0; j < snclar.length; j++) {
	        	await secdeger(srr,snclar[j].ust_id,snclar[j].id);
	    		srr = srr + 1 ;
	    	}
		}
	    silBtnDzn();
    	loadEkranKapat();
	}

	
	tableSirala("#tablebot");
	tableArama("#tablebot","#search"); 

	function selectDuzenle(){
		$('.dznl_val').remove('[selected="selected"]');
	}

	$("#btn_ozel_sart").click(function(){
		var deger = $( "#ozelsartLst option:selected" ).text();
		if ($('#dznl_ozel_sart').val() == null || $('#dznl_ozel_sart').val() == '') {
			$('#dznl_ozel_sart').val(deger);
		}else{
			$('#dznl_ozel_sart').val($('#dznl_ozel_sart').val()+'\n\n'+deger);
		}
	});
	
	//Form Kaydet butonu 
    $("#dznl_btn").click(function(){
    	loadEkranAc();
		if (fromValid() ){
	        $.post("<?=PREPATH.'post/denetimPost.php?fnk=tklfDznl' ?>",
		        {
		        	//yeni			:  //(isset($_GET['id']) ? $_GET['id'] : '-1') ,
	            	id				: $('#dznl_id'    	 	  ).val(),
    	            musteri_id    	: $('#dznl_musteri_id'    ).val(), 
    	            teklif_tarihi   : $('#dznl_teklif_tarihi' ).val(),
    	            dton_id         : $('#dznl_dton_id'       ).val(),
    	            donem_bas_trh   : $('#dznl_donem_bas_trh' ).val(),
    	            donem_bts_trh   : $('#dznl_donem_bts_trh' ).val(),
    	            frc_id          : $('#dznl_frc_id'        ).val(),
    	        	raporsekli_id   : $('#dznl_raporsekli_id' ).val(),
    	        	duzenkurum_id   : $('#dznl_duzenkurum_id' ).val(),
    	            tutar           : $('#dznl_tutar'         ).val().replace(/,/g,""),
    	            para_birimi_id  : $('#dznl_para_birimi_id').val(),
    	            dil_id          : $('#dznl_dil_id'        ).val(),
    	            rapor_dil_id    : $('#dznl_rapor_dil_id'  ).val(),
    	            email           : $('#dznl_musteri_email' ).val(),
    	            bilgi           : $('#dznl_bilgi'         ).val(),
    	            ozel_sart       : $('#dznl_ozel_sart'     ).val(),
    	        	islem			: 'duzenleme'
			    },
			    function(data,status){
		    		if(status == "success"){
			    		console.log(data);
		    		    var obj = JSON.parse(data);
		    		    if (obj.hata == true) {
		    				hataAc(obj.hataMesaj);
		    		    }else{
		    		    	onayAc('Kayıt işlemi tamamlandı, Pdf oluşturuldu.');
		    		    	detayAc(obj.icerik);
		    		    }
		    		}else if(status == "error"){
		    		   hataAc("Bir sorun oluştu.");
		    	    }
		    		loadEkranKapat();
			    }
		    );
		}else{
			loadEkranKapat();
		}
	});

	$("#dznl_onay").click(function(){
		loadEkranAc();
		if (fromValid() ){
	        $.post("<?=PREPATH.'post/denetimPost.php?fnk=tklfDznl' ?>",
		        {
	            	id				: $('#dznl_id'    	 	  ).val(),
    	            musteri_id    	: $('#dznl_musteri_id'    ).val(), 
    	            teklif_tarihi   : $('#dznl_teklif_tarihi' ).val(),
    	            dton_id         : $('#dznl_dton_id'       ).val(),
    	            donem_bas_trh   : $('#dznl_donem_bas_trh' ).val(),
    	            donem_bts_trh   : $('#dznl_donem_bts_trh' ).val(),
    	            frc_id          : $('#dznl_frc_id'        ).val(),
    	        	raporsekli_id   : $('#dznl_raporsekli_id' ).val(),
    	        	duzenkurum_id   : $('#dznl_duzenkurum_id' ).val(),
    	            tutar           : $('#dznl_tutar'         ).val().replace(/,/g,""),
    	            para_birimi_id  : $('#dznl_para_birimi_id').val(),
    	            dil_id          : $('#dznl_dil_id'        ).val(),
    	            rapor_dil_id    : $('#dznl_rapor_dil_id'  ).val(),
    	            email           : $('#dznl_musteri_email' ).val(),
    	            bilgi           : $('#dznl_bilgi'         ).val(),
    	            ozel_sart       : $('#dznl_ozel_sart'     ).val(),
    	        	islem			: 'onay'
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
		}
		loadEkranKapat();
	});

	//Form Valid Fonksiyon
    function fromValid( sfr = false){

	    //var snc1  = !sfr && ($('#dznl_id'    ).val() == null	|| $('#dznl_id'    ).val() == "");
	    var snc2  = !sfr && ($('#dznl_musteri_id'    ).val() == null	|| $('#dznl_musteri_id'    ).val() == "");
	    var snc3  = !sfr && ($('#dznl_teklif_tarihi' ).val() == null	|| $('#dznl_teklif_tarihi' ).val() == "");
	    var snc4  = !sfr && ($('#dznl_dton_id'       ).val() == null	|| $('#dznl_dton_id'       ).val() == "");
	    var snc5  = !sfr && ($('#dznl_donem_bas_trh' ).val() == null	|| $('#dznl_donem_bas_trh' ).val() == "");
	    var snc6  = !sfr && ($('#dznl_donem_bts_trh' ).val() == null	|| $('#dznl_donem_bts_trh' ).val() == "");
	    var snc7  = !sfr && ($('#dznl_frc_id'        ).val() == null	|| $('#dznl_frc_id'        ).val() == "");
	    var snc8  = !sfr && ($('#dznl_tutar'         ).val() == null	|| $('#dznl_tutar'         ).val() == "");
	    var snc9  = !sfr && ($('#dznl_para_birimi_id').val() == null	|| $('#dznl_para_birimi_id').val() == "");
	    var snc10 = !sfr && ($('#dznl_dil_id'        ).val() == null	|| $('#dznl_dil_id'        ).val() == "");
	    var snc11 = !sfr && ($('#dznl_duzenkurum_id' ).val() == null	|| $('#dznl_duzenkurum_id' ).val() == "");
	    var snc12 = !sfr && ($('#dznl_raporsekli_id' ).val() == null	|| $('#dznl_raporsekli_id' ).val() == "");
	    var snc13 = !sfr && ($('#dznl_rapor_dil_id'  ).val() == null	|| $('#dznl_rapor_dil_id'  ).val() == "");
	    
	//	fromAlanValid('#dznl_id'    ,snc1 ) ;
		fromAlanValid('#dznl_musteri_id'    ,snc2 ) ;
		fromAlanValid('#dznl_teklif_tarihi' ,snc3 ) ;
		fromAlanValid('#cmb'       ,snc4 ) ;
		fromAlanValid('#dznl_donem_bas_trh' ,snc5 ) ;
		fromAlanValid('#dznl_donem_bts_trh' ,snc6 ) ;
		fromAlanValid('#dznl_frc_id'        ,snc7 ) ;
		fromAlanValid('#dznl_tutar'         ,snc8 ) ;
		fromAlanValid('#dznl_para_birimi_id',snc9 ) ;
		fromAlanValid('#dznl_dil_id'        ,snc10) ;
		fromAlanValid('#dznl_duzenkurum_id' ,snc11) ;
		fromAlanValid('#dznl_raporsekli_id' ,snc12) ;
		fromAlanValid('#dznl_rapor_dil_id' 	,snc13) ;
		    
		if ( snc2 || snc3 || snc4 || snc5 || snc6 || snc7 || snc8 || snc9 || snc10 || snc11 || snc12 || snc13) {
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
		
  	//Form temizleme butonu
	function formTemizle(valid = true){
	    //nedenListele(-1);
	    //sec(0,-1);
	    $('#dznl_musteri_id').		val(null);
	    $('#dznl_musteri_adi').		val(null);
	    $('#dznl_teklif_tarihi').	val(null);
	    $('#dznl_dton_id').			val(null);
	    $('#dznl_donem_bas_trh').	val(null);
	    $('#dznl_donem_bts_trh').	val(null);
	    $('#dznl_frc_id').			val(null);
	    $('#dznl_raporsekli_id').	val(null);
	    $('#dznl_duzenkurum_id').	val(null);
	    $('#dznl_tutar').			val(null);
	    $('#dznl_para_birimi_id').	val(null);
	    $('#dznl_dil_id').			val(null);
	    $('#dznl_rapor_dil_id').	val(null);
	    $('#dznl_musteri_email').	val(null);
	    $('#dznl_bilgi').			val(null);
	    $('#dznl_ozel_sart').		val(null);
	    if (valid) {
		    fromValid(true);
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

	function miniAra(prm) {
	    $.get( "<?php echo PREPATH.'pages/genel/tabloAra.php?tablo='?>"+prm, function(data, status){
    		if(status == "success"){
    			$("#txtHint").empty();
    			$("#txtHint").append(data);
    		}else if(status == "error"){
    		    hataAc("Bilgi çekilemedi.");
    	    }
    	});
	}

	detayAc(<?=isset($_GET['id']) ? $_GET['id'] : null;?>);
	
</script>
<?php include (PREPATH.'footer.php'); ?>