<?php
$pId = 116;
include_once '../../First.php';
include_once PREPATH . 'header.php';

$tbl = new GrupPrm();
$gelen = Crud::all($tbl);
$cmb   = Base::basitList(Crud::all(new KullaniciTurPrm()));
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
            					<th class="bg-gray-700 text-gray-200 text-center align-middle">Id  </th>
            					<th class="bg-gray-700 text-gray-200 text-center align-middle">Adı  </th>
            					<th class="bg-gray-700 text-gray-200 text-center align-middle">Kullanici Türü  </th>
            				</tr>
            			</thead>
            			<tbody>
                			<?php 
                			if ($gelen != null){
                			    foreach ($gelen as $gln){
                                ?>
                            	<tr class="listeEleman" onclick="detayAc('<?= $gln->id ?>')" >
                            		<td class="text-center align-middle" id="list_id"><?= $gln->id ?> </td>
                            		<td class="text-center align-middle"><?= $gln->adi ?> </td>
                            		<td class="text-center align-middle"><?= $gln->kullanici_tur_id->ref->deger->adi ?> </td>
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
                		<div class="col-lg-4 font-weight-bold text-gray-800 align-self-center  text-right">Adı :</div>
                		<div class="col-lg-8"><input type="text" class="form-control form-control-user" id="dznl_ad" ></div>
                	</div>
                	<div class="row mb-2">
                		<div class="col-lg-4 font-weight-bold text-gray-800 align-self-center  text-right">Kullanıcı Türü :</div>
                		<div class="col-lg-8">
<!--                 		<input type="text" id="dznl_program_id" class="form-control form-control-user"  > -->
                    		<select class=" custom-select form-control " id="dznl_kullanici_tur_id">
                    			<option class="dznl_klcTur" value="" selected="selected">Seçiniz</option>
                    			<?php 
                    			 foreach ($cmb as $v){
                    			     echo '<option class="dznl_val" value="'.$v['id'].'">'.$v['adi'].'</option>';
                			     }
                                ?>
                            </select>
                		</div>
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



<script >
	tableSirala("#tablebot");
	tableArama("#tablebot","#search");

	//Listeden Tıklanıp formun doldurulması
	function detayAc(id){
    	formTemizle(false);
	    $.get( "<?php echo PREPATH.'post/genelPost.php?tur=getById&tablo='.get_class($tbl).'&id='?>"+id, function(data, status){
			if(status == "success"){
			    var objx = JSON.parse(data);
			    var obj = JSON.parse(objx.icerik);
			    $('#dznl_id').				val(obj.id);
			    $('#dznl_ad').				val(obj.adi);
			    $('#dznl_kullanici_tur_id').val(obj.kullanici_tur_id.id);
			    $('#dznl_create_gmt').		val(obj.create_gmt);
			    $('#dznl_create_user_id').	val(obj.create_user_id);
			    $('#dznl_gmt').				val(obj.gmt);
			    $('#dznl_user_id').			val(obj.user_id);
			}else if(status == "error"){
			    hataAc("Bilgi çekilemedi.");
		    }
			btnDuzenle();
		});
	}

	function selectDuzenle(){
		$('.dznl_klcTur').remove('[selected="selected"]');
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
		    		id 					: $('#dznl_id').val(),
		    		adi					: $('#dznl_ad').val(),
		    		kullanici_tur_id 	: $('#dznl_kullanici_tur_id').val(),
			    },
			    function(data,status){
				    console.log(data);
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
	    var snc1 = !sfr && ($('#dznl_ad').val() == null || $('#dznl_ad').val() == "");
	    var snc2 = !sfr && ($('#dznl_kullanici_tur_id').val() == null || $('#dznl_kullanici_tur_id').val() == "");
	    
		fromAlanValid('#dznl_ad',snc1) ;
		fromAlanValid('#dznl_kullanici_tur_id',snc2) ;
		    
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
	
	//Form temizleme butonu
	function formTemizle(valid = true){
	    $('#dznl_id').				val(null);
	    $('#dznl_ad').				val(null);
	    $('#dznl_create_gmt').		val(null);
	    $('#dznl_create_user_id').	val(null);
	    $('#dznl_gmt').				val(null);
	    $('#dznl_user_id').			val(null);
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
</script>
<?php include (PREPATH.'footer.php'); ?>