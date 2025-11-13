<?php
$pId = 164;
include_once '../../First.php';
include_once PREPATH . 'header.php';

$tbl    = new Kullanici();
$cmb    = Base::basitList(Crud::getSqlCok(new GrupPrm(), GrupPrm::KULLANICI_GRUPLARI, array('kullaniciTurId'=> KullaniciTurPrm::DENETCI)));
$gelen  = Crud::getSqlCok($tbl, Kullanici::KULLANICI_TUR, array('tur'=>KullaniciTurPrm::DENETCI));

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
            					<th class="bg-gray-700 text-gray-200 text-center align-middle">Kullanıcı Adı  </th>
            					<th class="bg-gray-700 text-gray-200 text-center align-middle">Adı  </th>
            					<th class="bg-gray-700 text-gray-200 text-center align-middle">Soyadı  </th>
            					<th class="bg-gray-700 text-gray-200 text-center align-middle">Kullanici Türü  </th>
            					<th class="bg-gray-700 text-gray-200 text-center align-middle">Seç</th>
            				</tr>
            			</thead>
            			<tbody>
                			<?php 
                			if ($gelen != null){
                			 foreach ($gelen as $gln){
                            ?>
                        	<tr class="listeEleman"  >
                        		<td class="text-center align-middle" id="list_id"><?= $gln->id ?> </td>
                        		<td class="text-center align-middle"><?= $gln->kullanici_adi ?> </td>
                        		<td class="text-center align-middle"><?= $gln->ad ?> </td>
                        		<td class="text-center align-middle"><?= $gln->soyad ?> </td>
                        		<td class="text-center align-middle"><?= $gln->grup_id->ref->deger->adi->deger.' - '.$gln->grup_id->ref->deger->kullanici_tur_id->ref->deger->adi->deger ?> </td>
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
                		<div class="col-3"></div>
                		<div class="col-6" id = "sil_Div" style="visibility: hidden;" >
                			<button type="button" class="btn btn-danger col-lg-12" data-toggle="modal" onclick="dataSil()" data-target="#myModal" id="sil_buton" >
                				<i class="fas fa-exclamation"></i>     Denetçi Sil    <i class="fas fa-exclamation"></i>
                			</button>
                  		</div>
            		</div>
                    <div class="row mb-2">
                		<div class="col-lg-4 font-weight-bold text-gray-800 align-self-center text-right">Id :</div>
                		<div class="col-lg-8"><input type="text"  id="dznl_id" class="form-control form-control-user" disabled></div>
                	</div>
                    <div class="row mb-2">
                		<div class="col-lg-4 font-weight-bold text-gray-800 align-self-center  text-right">Kullanıcı Adı :</div>
                		<div class="col-lg-8"><input type="text"  id="dznl_kullanici_adi" class="form-control form-control-user"></div>
                	</div>
                	<div class="row mb-2">
                		<div class="col-lg-4 font-weight-bold text-gray-800 align-self-center  text-right">Kullanıcı Türü :</div>
                		<div class="col-lg-8"><input type="text" class="form-control form-control-user" value="Denetçi" disabled></div>
                		<input type="hidden" id="dznl_kullanici_tur" value="<?=KullaniciTurPrm::DENETCI?>" >
                	</div>
	            	<div class="row mb-2">
                		<div class="col-lg-4 font-weight-bold text-gray-800 align-self-center  text-right">Grup :</div>
                		<div class="col-lg-8">
                    		<select class=" custom-select form-control " id="dznl_grup_id">
                    			<option class="dznl_klcTur" value="" selected="selected">Seçiniz</option>
                    			<?php 
                    			 foreach ($cmb as $v){
                    			     echo '<option class="dznl_klcTur" value="'.$v['id'].'">'.$v['adi'].'</option>';
                			     }
                                ?>
                    			
                            </select>
                		</div>
                	</div>
                    <div class="row mb-2">
                		<div class="col-lg-4 font-weight-bold text-gray-800 align-self-center  text-right">Şifre :</div>
                		<div class="col-lg-8"><input type="text" class="form-control form-control-user" id="dznl_sifre" ></div>
                	</div>
                    <div class="row mb-2">
                		<div class="col-lg-4 font-weight-bold text-gray-800 align-self-center  text-right">Şifre tekrar :</div>
                		<div class="col-lg-8"><input type="text" class="form-control form-control-user" id="dznl_sifret" ></div>
                	</div>
                    <div class="row mb-2">
                		<div class="col-lg-4 font-weight-bold text-gray-800 align-self-center  text-right">Adı :</div>
                		<div class="col-lg-8"><input type="text" class="form-control form-control-user" id="dznl_ad" ></div>
                	</div>
                    <div class="row mb-2">
                		<div class="col-lg-4 font-weight-bold text-gray-800 align-self-center  text-right">Soyadı :</div>
                		<div class="col-lg-8"><input type="text" class="form-control form-control-user" id="dznl_soyad" ></div>
                	</div>
                    <div class="row mb-2">
                		<div class="col-lg-4 font-weight-bold text-gray-800 align-self-center  text-right">Email :</div>
                		<div class="col-lg-8"><input type="text" class="form-control form-control-user" id="dznl_email" ></div>
                	</div>
                    <div class="row mb-2">
                		<div class="col-lg-4 font-weight-bold text-gray-800 align-self-center  text-right">Telefon :</div>
                		<div class="col-lg-8"><input type="text" class="form-control form-control-user" id="dznl_telefon" ></div>
                	</div>
                	
                    <div class="row mb-2">
                		<div class="col-lg-4 font-weight-bold text-gray-800 align-self-center  text-right">KGK Sicil No :</div>
                		<div class="col-lg-8"><input type="text" class="form-control form-control-user" id="dznl_kgk_sicil_no" ></div>
                	</div>
                    <div class="row mb-2">
                		<div class="col-lg-4 font-weight-bold text-gray-800 align-self-center  text-right">TC Kimlik No :</div>
                		<div class="col-lg-8"><input type="text" class="form-control form-control-user" id="dznl_tc_no" ></div>
                	</div>
                    <div class="row mb-2">
                		<div class="col-lg-4 font-weight-bold text-gray-800 align-self-center  text-right">YMM Ruhsat No :</div>
                		<div class="col-lg-8"><input type="text" class="form-control form-control-user" id="dznl_ymm_ruhsat_no" ></div>
                	</div>
                    <div class="row mb-2">
                		<div class="col-lg-4 font-weight-bold text-gray-800 align-self-center  text-right">SMMM Ruhsat No :</div>
                		<div class="col-lg-8"><input type="text" class="form-control form-control-user" id="dznl_smmm_ruhsat_no" ></div>
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
		tableSirala("#tablebot");
		tableArama("#tablebot","#search");

		function dataSil(){
	    	$.post('<?php echo PREPATH.'pages/genel/kayitSil.php?tablo='.get_class($tbl).'&tur='.KullaniciTurPrm::DENETCI?>',
                {
	    			tablo		: '<?= get_class($tbl)?>',
	    			id			: $('#dznl_id').val(),
	    			mesaj		: 'Denetçi silmek istediğinize emin misiniz ?',
        			donusLink	: '<?= PREPATH.'pages/kullanici/denetciKullanici.php'?>',
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
		
		function detayAc(id){
	    	formTemizle();
			$.get( "<?php echo PREPATH.'post/genelPost.php?tur=getById&tablo='.get_class($tbl).'&id='?>"+id, function(data, status){
				if(status == "success"){
				    var objx = JSON.parse(data);
				    var obj = JSON.parse(objx.icerik);
				    $('#dznl_id').				val(obj.id);
				    $('#dznl_kullanici_adi').	val(obj.kullanici_adi);
				    $('#dznl_ad').				val(obj.ad);
				    $('#dznl_soyad').			val(obj.soyad);
				    $('#dznl_email').			val(obj.email);
				    $('#dznl_telefon').			val(obj.telefon);
				    $('#dznl_kgk_sicil_no').	val(obj.kgk_sicil_no);
				    $('#dznl_tc_no').			val(obj.tc_no);
				    $('#dznl_ymm_ruhsat_no').	val(obj.ymm_ruhsat_no);
				    $('#dznl_smmm_ruhsat_no').	val(obj.smmm_ruhsat_no);
				    $('#dznl_create_gmt').		val(obj.create_gmt);
				    $('#dznl_create_user_id').	val(obj.create_user_id);
				    $('#dznl_gmt').				val(obj.gmt);
				    $('#dznl_user_id').			val(obj.user_id);
				    $('#dznl_grup_id').			val(obj.grup_id.id);
				}else if(status == "error"){
				    hataAc("Bilgi çekilemedi.");
			    }
				btnDuzenle();
 			});
		}

		function formTemizle(valid = true){
		    $('#dznl_grup_id .data').remove();
		    $('#dznl_id').				val(null);
		    $('#dznl_kullanici_adi').	val(null);
		    $('#dznl_ad').				val(null);
		    $('#dznl_soyad').			val(null);
		    $('#dznl_sifre').			val(null);
		    $('#dznl_sifret').			val(null);
		    $('#dznl_email').			val(null);
		    $('#dznl_telefon').			val(null);
		    $('#dznl_kgk_sicil_no').	val(null);
		    $('#dznl_tc_no').			val(null);
		    $('#dznl_ymm_ruhsat_no').	val(null);
		    $('#dznl_smmm_ruhsat_no').	val(null);
		    $('#dznl_grup_id').			val(null);
		    $('#dznl_create_gmt').		val(null);
		    $('#dznl_create_user_id').	val(null);
		    $('#dznl_gmt').				val(null);
		    $('#dznl_user_id').			val(null);
		    btnDuzenle();
		    if (valid) {
			    fromValid(true);
		    }
		}

	   	$("#dznl_btn").click(function(){
		   	btnDuzenle();
			if (fromValid() ){
				var link = '';
				if ($('#dznl_id').val() == null || $('#dznl_id').val() == ""){
					link = "<?=PREPATH.'post/kullaniciPost.php?tur=denetciCreate&tablo='.get_class($tbl).'&mesaj=true' ?>";
				}else{
					link = "<?=PREPATH.'post/kullaniciPost.php?tur=denetciUpdate&tablo='.get_class($tbl).'&mesaj=true' ?>";
				}
		        $.post(link,
			        {
			    		id 					: $('#dznl_id').val(),
			    		kullanici_adi		: $('#dznl_kullanici_adi').val(),
			    		ad					: $('#dznl_ad').val(),
			    		soyad				: $('#dznl_soyad').val(),
			    		sifre				: $('#dznl_sifre').val(),
			    		email				: $('#dznl_email').val(),
			    		telefon				: $('#dznl_telefon').val(),
			    		kgk_sicil_no		: $('#dznl_kgk_sicil_no').val(),
			    		tc_no				: $('#dznl_tc_no').val(),
			    		ymm_ruhsat_no		: $('#dznl_ymm_ruhsat_no').val(),
						smmm_ruhsat_no		: $('#dznl_smmm_ruhsat_no').val(),
			    		grup_id				: $('#dznl_grup_id').val(),
			    		tur					: $('#dznl_kullanici_tur').val()
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
			    		    console.log(obj);
			    		}else if(status == "error"){
			    		    hataAc("Bir sorun oluştu.");
			    	    }
				    }
			    );
			}
		});
		   
	    function fromValid( sfr = false){
		    var snc1 = !sfr && ($('#dznl_kullanici_adi').val() == null || $('#dznl_kullanici_adi').val() == "");
		    var snc2 = !sfr && ($('#dznl_ad').val() == null || $('#dznl_ad').val() == "");
		    var snc3 = !sfr && ($('#dznl_soyad').val() == null || $('#dznl_soyad').val() == "");
		    var snc5 = !sfr && ($('#dznl_grup_id').val() == null || $('#dznl_grup_id').val() == "");
		    
			fromAlanValid('#dznl_kullanici_adi',snc1) ;
			fromAlanValid('#dznl_ad',snc2) ;
			fromAlanValid('#dznl_soyad',snc3) ;
			fromAlanValid('#dznl_grup_id',snc5) ;
			    
			if (snc1 || snc2 || snc3 || snc5) {
		    	hataAc("Eksik alanları doldurun.");
		    	return false;
		    }

			if ($('#dznl_id').val() == null || $('#dznl_id').val() == ""){
		    	var snc6 = !sfr && ($('#dznl_sifre').val() == null || $('#dznl_sifre').val() == "");
				fromAlanValid('#dznl_sifre',snc6) ;
				if (snc6) {
			    	hataAc("Yeni kayıt için şifre girmeniz gerekmektedir.");
			    	return false;
			    }
			}else{
				if ($('#dznl_sifre').val() != '' && $('#dznl_sifre').val()!=$('#dznl_sifret').val()) {
					hataAc("Şifreler uyuşmuyor.");
			    	return false;
				}
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
		    if($('#dznl_id').val() == null || $('#dznl_id').val() == ""){
		    	document.getElementById("sil_Div").style.visibility = "hidden";
			    $('#dznl_btn span').text("Kaydet");
			    $('#dznl_btn i').removeClass("fas").removeClass("fa-pencil-alt");
			    $('#dznl_btn i').addClass("fa").addClass("fa-floppy-o");
			}else{
				document.getElementById("sil_Div").style.visibility = "visible";
			    $('#dznl_btn span').text("Düzenle");
			    $('#dznl_btn i').removeClass("fa").removeClass("fa-floppy-o");
			    $('#dznl_btn i').addClass("fas").addClass("fa-pencil-alt");
			}
		}
		
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