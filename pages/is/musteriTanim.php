<?php
$pId = 140;
include_once '../../First.php';

include_once PREPATH . 'header.php';

$tbl    = new Musteri();
$gelen  = Crud::all($tbl);
$cmbV   = Base::basitList(Crud::all(new VergiDairesi()));
$cmbI   = Base::basitList(Crud::all(new Il()));
$cmbO   = Base::basitList(Crud::all(new IsOrtagi()));
$cmbS   = Base::basitList(Crud::all(new Sektor()));

?>

<div class="row">
    <div class="col-lg-6 col-xl-7 pb-3">
        <div class="card shadow">
            <div class="card-header bg-gradient-primary py-3">
            	<h6 class="m-0 font-weight-bold text-gray-300"><?=$tbl->vt_Adi()?></h6>
            </div>
            <div class="card-body">
            	<div class="table-responsive">
            		<input id="search" type="text" class="form-control form-control-user"  placeholder="Arama">
            		<br>
            		<table id="tablebot" class="table table-bordered table-striped" >
            			<thead>
            				<tr>
            					<th class="bg-gray-700 text-gray-200 text-center align-middle">Vergi No</th>
            					<th class="bg-gray-700 text-gray-200 text-center align-middle">Unvan</th>
            					<th class="bg-gray-700 text-gray-200 text-center align-middle">İş Ortağı</th>
            					<th class="bg-gray-700 text-gray-200 text-center align-middle">Vergi Dairesi</th>
            					<th class="bg-gray-700 text-gray-200 text-center align-middle">Seç</th>
            				</tr>
            			</thead>
            			<tbody>
                			<?php 
                			if ($gelen!= null){
                    			foreach ($gelen as $gln){
                                ?>
                            	<tr class="listeEleman" >
                            		<td class="text-center align-middle" id="list_id"><?= $gln->vergi_no ?> </td>
                            		<td class="text-center align-middle"><?= $gln->unvan ?> </td>
                            		<td class="text-center align-middle"><?= $gln->isortagi_id->ref->deger->unvan ?> </td>
                            		<td class="text-center align-middle"><?= ($gln->vergi_daire_id->deger == null  ? '' : $gln->vergi_daire_id->ref->deger->adi) ?> </td>
                            		<td class="text-center align-middle">
                                    	<a href="#" id="dznl_sil" class="btn btn-warning" style="width: 60px;" onclick="detayAc('<?= $gln->id ?>')">
                                            <span class="text">Seç</span>
                                        </a>
                                    </td>
                            	</tr>
                                <?php
                    			}
                			}
                			?>
            			</tbody>
            		</table>
            	</div>
            </div>
        </div>
    </div>

    <div class="col-lg-6 col-xl-5">
        <div class="card shadow" >
        	<div class="card-header bg-gradient-primary py-3">
            	<h6 class="m-0 font-weight-bold text-gray-300"><?=$tbl->vt_Adi().' DÜZENLEME'?></h6>
            </div>
            <div class="card-body">
                <form class="user">
                    <div class="row mb-2">
                		<div class="col-lg-4 font-weight-bold text-gray-800 align-self-center text-right">Id :</div>
                		<div class="col-lg-8"><input type="text"  id="dznl_id" class="form-control form-control-user" disabled></div>
                	</div>
                    <div class="row mb-2">
                    	<input type="hidden" name="id">
                		<div class="col-lg-4 font-weight-bold text-gray-800 align-self-center text-right">Vergi No :</div>
                		<div class="col-lg-8"><input type="text"  id="dznl_vergi_no" class="form-control form-control-user" maxlength="10" onkeypress="return isNumberKey(event)"></div>
                	</div>
                    <div class="row mb-2">
                		<div class="col-lg-4 font-weight-bold text-gray-800 align-self-center  text-right"> Unvan :</div>
                		<div class="col-lg-7"><input type="text"  id="dznl_unvan" class="form-control form-control-user" style="text-transform: uppercase" maxlength="100"></div>
                		<div class="col-lg-1 align-self-center  text-right"><label id="chars">0</label></div>
                	</div>
                    <div class="row mb-2">
                		<div class="col-lg-4 font-weight-bold text-gray-800 align-self-center  text-right">İş Ortağı :</div>
                		<div class="col-lg-8">
                    		<select class=" custom-select form-control " id="dznl_isortagi_id">
                    			<option class="dznl_val" value="" selected="selected">Seçiniz</option>
                    			<?php 
                    			 foreach ($cmbO as $v){
                    			     echo '<option class="dznl_val" value="'.$v['id'].'">'.$v['unvan'].'</option>';
                			     }
                                ?>
                            </select>
                		</div>
                	</div>
                    <div class="row mb-2">
                		<div class="col-lg-4 font-weight-bold text-gray-800 align-self-center  text-right">Vergi Dairesi :</div>
                		<div class="col-lg-2"><input type="text"  id="dznl_vergidairesi_id" class="form-control form-control-user" disabled></div>
                		<div class="col-lg-4"><input type="text"  id="dznl_vergidairesi_adi" class="form-control form-control-user" disabled></div>
                		<div class="col-lg-2">
							<button type="button" class="btn btn-primary col-lg-12" data-toggle="modal" onclick="miniAra('VergiDairesi')" data-target="#myModal" id="mstrBtn" >Bul</button>
                		</div>
                	</div>
                    <div class="row mb-2">
                		<div class="col-lg-4 font-weight-bold text-gray-800 align-self-center  text-right">Adres :</div>
                		<div class="col-lg-8"><input type="text"  id="dznl_adres" class="form-control form-control-user"></div>
                	</div>
                    <div class="row mb-2">
                		<div class="col-lg-4 font-weight-bold text-gray-800 align-self-center  text-right">Telefon :</div>
                		<div class="col-lg-8"><input type="text"  id="dznl_telefon" class="form-control form-control-user"></div>
                	</div>
                    <div class="row mb-2">
                		<div class="col-lg-4 font-weight-bold text-gray-800 align-self-center  text-right">Faks :</div>
                		<div class="col-lg-8"><input type="text"  id="dznl_faks" class="form-control form-control-user"></div>
                	</div>
                    <div class="row mb-2">
                		<div class="col-lg-4 font-weight-bold text-gray-800 align-self-center  text-right">Web :</div>
                		<div class="col-lg-8"><input type="text"  id="dznl_web" class="form-control form-control-user"></div>
                	</div>
                    <div class="row mb-2">
                		<div class="col-lg-4 font-weight-bold text-gray-800 align-self-center  text-right">Email :</div>
                		<div class="col-lg-8"><input type="text"  id="dznl_email" class="form-control form-control-user"></div>
                	</div>
                    <div class="row mb-2">
                		<div class="col-lg-4 font-weight-bold text-gray-800 align-self-center  text-right">İl :</div>
                		<div class="col-lg-8">
                    		<select class=" custom-select form-control " id="dznl_il_id" onchange="ilSec(-1)">
                    			<option class="dznl_val" value="" selected="selected">Seçiniz</option>
                    			<?php 
                    			 foreach ($cmbI as $v){
                    			     echo '<option class="dznl_val" value="'.$v['id'].'">'.$v['adi'].'</option>';
                			     }
                                ?>
                            </select>
                		</div>
                	</div>
                	<div class="row mb-2">
                		<div class="col-lg-4 font-weight-bold text-gray-800 align-self-center  text-right">İlçe :</div>
                		<div class="col-lg-8">
                    		<select class=" custom-select form-control " id="dznl_ilce_id" >
                    		</select>
                		</div>
            		</div>
                    <div class="row mb-2">
                		<div class="col-lg-4 font-weight-bold text-gray-800 align-self-center  text-right">Sektör :</div>
                		<div class="col-lg-8">
                    		<select class=" custom-select form-control " id="dznl_sektor_id">
                    			<option class="dznl_val" value="" selected="selected">Seçiniz</option>
                    			<?php 
                    			 foreach ($cmbS as $v){
                    			     echo '<option class="dznl_val" value="'.$v['id'].'">'.$v['adi'].'</option>';
                			     }
                                ?>
                            </select>
                		</div>
                	</div>
                    <div class="row mb-2">
                		<div class="col-lg-4 font-weight-bold text-gray-800 align-self-center  text-right">Ticaret Sicil No :</div>
                		<div class="col-lg-8"><input type="text"  id="dznl_sicil_no" class="form-control form-control-user" ></div>
                	</div>
                    <div class="row mb-2">
                		<div class="col-lg-4 font-weight-bold text-gray-800 align-self-center  text-right">Mernis No :</div>
                		<div class="col-lg-8"><input type="text"  id="dznl_mernis_no" class="form-control form-control-user" maxlength="16" onkeypress="return isNumberKey(event)"></div>
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
                		<div class="col-lg-8"><input type="text" class="form-control form-control-user" id="dznl_gmt" disabled></div>
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
                        	<a href="#" id="dznl_sil" class="btn btn-danger col-lg-8" onclick="formTemizle()">
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

<div class="modal fade" id="myModal" role="dialog">
	<div class="modal-dialog modal-xl">
		<div class="modal-content">
			<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 mb-12">
				<div class="card" style="min-height:395px;">
					<div class="card-block">
						<div id="txtHint"></div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<script >

	tableSirala("#tablebot");
	tableArama("#tablebot","#search");

	$( "#dznl_unvan" ).keyup(function() {
		kelimeUzunluk();
	})

	function kelimeUzunluk(){
		var sayi = $('#dznl_unvan').val().length;
		if (sayi > 90) {
			$('#chars').css("color", 'red');
		}else{
			$('#chars').css("color", 'black');
		}
		$('#chars').text($('#dznl_unvan').val().length);
	}
	
	function ilSec(val){
		var id = document.getElementById("dznl_il_id").value;
		var table = $('#dznl_ilce_id');
		table.empty();
	    $.get( "<?php echo PREPATH.'post/genelPost.php?tur=ilce&il_id='?>"+id, function(data, status){
			if(status == "success"){
			    var objx = JSON.parse(data).icerik;
			    table.append('<option class="dznl_val" value="" '+ ((val == -1) ? 'selected="selected"' : '') +'>Seçiniz</option>');
				for (var x in objx) {
				  table.append('<option class="dznl_val" value="'+objx[x].id+'" '+ ((val == objx[x].id) ? 'selected="selected"' : '') +'>'+objx[x].adi+'</option>');
				}

			}else if(status == "error"){
			    hataAc("Bilgi çekilemedi.");
		    }
		});
	}

	
	//Listeden Tıklanıp formun doldurulması
	function detayAc(id){
	    formTemizle(false);
	    $.get( "<?php echo PREPATH.'post/genelPost.php?tur=getById&tablo='.get_class($tbl).'&id='?>"+id, function(data, status){
			if(status == "success"){
			    var objx = JSON.parse(data);
			    var obj = JSON.parse(objx.icerik);
			    $('#dznl_id').				val(obj.id);
			    $('#dznl_vergi_no').		val(obj.vergi_no);
			    $('#dznl_unvan').			val(obj.unvan         );
			    $('#dznl_isortagi_id').		val(obj.isortagi_id.id);
			    $('#dznl_vergidairesi_id').	val(obj.vergi_daire_id != null ? obj.vergi_daire_id.id : null);
			    $('#dznl_vergidairesi_adi').val(obj.vergi_daire_id != null ? obj.vergi_daire_id.adi : null);
			    $('#dznl_adres').			val(obj.adres         );
			    $('#dznl_telefon').			val(obj.telefon       );
			    $('#dznl_faks').			val(obj.faks          );
			    $('#dznl_web').				val(obj.web           );
			    $('#dznl_email').			val(obj.email         );
			    $('#dznl_il_id').			val(obj.il_id.id      );
			    $('#dznl_ilce_id').			val(obj.ilce_id != null ? obj.ilce_id.id : null);
			    $('#dznl_sektor_id').		val(obj.sektor_id != null ? obj.sektor_id.id : null  );
			    $('#dznl_mernis_no').		val(obj.mernis_no     );
			    $('#dznl_sicil_no').		val(obj.sicil_no     );
			    $('#dznl_create_gmt').		val(obj.create_gmt	  );
			    $('#dznl_create_user_id').	val(obj.create_user_id);
			    $('#dznl_gmt').				val(obj.gmt			  );
			    $('#dznl_user_id').			val(obj.user_id		  );
			    ilSec(obj.ilce_id != null ? obj.ilce_id.id : -1);
			    kelimeUzunluk();
			}else if(status == "error"){
			    hataAc("Bilgi çekilemedi.");
		    }
			btnDuzenle();
		});
	}

	function selectDuzenle(){
		$('.dznl_val').remove('[selected="selected"]');
	}

	//Form Kaydet butonu 
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
		    		vergi_no 	: $('#dznl_vergi_no').val(),
		    		unvan       : $('#dznl_unvan').val().toLocaleUpperCase('tr-TR'),
		    		vergi_daire_id:$('#dznl_vergidairesi_id').val(),
		    		isortagi_id	:$('#dznl_isortagi_id').val(),
		    		adres		: $('#dznl_adres').val(),
		    		telefon		: $('#dznl_telefon').val(),
		    		faks        : $('#dznl_faks').val(),
		    		web         : $('#dznl_web').val(),
		    		email       : $('#dznl_email').val(),
		    		il_id       : $('#dznl_il_id').val(),
		    		ilce_id     : $('#dznl_ilce_id').val(),
		    		sektor_id   : $('#dznl_sektor_id').val(),
		    		mernis_no   : $('#dznl_mernis_no').val(),
		    		sicil_no    : $('#dznl_sicil_no').val()
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

	//Form Valid Fonksiyon
    function fromValid( sfr = false){
	    var snc0 = !sfr && ($('#dznl_vergi_no').val() == null || $('#dznl_vergi_no').val() == "");
	    var snc1 = !sfr && ($('#dznl_unvan').val() == null || $('#dznl_unvan').val() == "");
	    var snc2 = !sfr && ($('#dznl_isortagi_id').val() == null || $('#dznl_isortagi_id').val() == "");
	    var snc3 = !sfr && ($('#dznl_il_id').val() == null || $('#dznl_il_id').val() == "");
	    
		fromAlanValid('#dznl_vergi_no',snc0) ;
		fromAlanValid('#dznl_unvan',snc1) ;
		fromAlanValid('#dznl_isortagi_id',snc2) ;
		fromAlanValid('#dznl_il_id',snc3) ;
		    
		if (snc0 || snc1 || snc2 || snc3) {
	    	hataAc("Eksik alanları doldurun.");
	    	return false;
	    }

		if (!fromUzunlukValid("Vergi No",'#dznl_vergi_no',10)) {
			return false;
		}

		if (!fromUzunlukValid("Mernis No",'#dznl_mernis_no',16)) {
			return false;
		}
	    
		return true;
	}

    function fromUzunlukValid(name,key,sayi){
    	var dgr = $(key).val();
		if (dgr!='' && dgr != null && dgr.length!=sayi) {
			fromAlanValid(key,true) ;
			hataAc(name+" kısmı "+sayi+" haneli olmalıdır.");
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
	    $('#dznl_id').				val(null);
	    $('#dznl_vergi_no').		val(null);
	    $('#dznl_unvan').			val(null);
	    $('#dznl_isortagi_id').		val(null);
	    $('#dznl_vergidairesi_id'). val(null);
	    $('#dznl_vergidairesi_adi').val(null);
	    $('#dznl_adres').			val(null);
	    $('#dznl_telefon').			val(null);
	    $('#dznl_faks').			val(null);
	    $('#dznl_web').				val(null);
	    $('#dznl_email').			val(null);
	    $('#dznl_il_id').			val(null);
	    $('#dznl_ilce_id').			val(null);
	    $('#dznl_sektor_id').		val(null);
	    $('#dznl_mernis_no').		val(null);
	    $('#dznl_sicil_no').		val(null);
	    $('#dznl_create_gmt').		val(null);
	    $('#dznl_create_user_id').	val(null);
	    $('#dznl_gmt').				val(null);
	    $('#dznl_user_id').			val(null);
	    var table = $('#dznl_ilce_id');
		table.empty();
	    btnDuzenle();
	    if (valid) {
		    fromValid(true);
	    }
	}
	//Düzenle butonu düzenleme
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
</script>
<?php include (PREPATH.'footer.php'); ?>