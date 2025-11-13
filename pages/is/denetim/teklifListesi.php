<?php
$pId = 169;
include_once '../../../First.php';
include_once PREPATH.'soa/driveSoa.php';

$link = 'pages/is/denetim/teklifListesi.php';
driveSoa::baglan($link);
include_once PREPATH . 'header.php';

$yni    = Crud::getById(new Program() , 170 ) -> basit();
$ynt    = Crud::getById(new Program() , 185 ) -> basit();
$dsy    = Crud::getById(new Program() , 199 ) -> basit();

$cmbFr  = Base::basitList(Crud::all(new TklfFinansRapor()));
$cmbPr  = Base::basitList(Crud::all(new TklfParaBirimi()));
$cmbDr  = Base::basitList(Crud::all(new DenetimDurum()));
$cmbDl  = Base::basitList(Crud::all(new TklfDil()));

?>

<div class="row">
    <div class="col-lg-12 col-xl-12 pb-3">
        <div class="card shadow">
            <div class="card-header bg-gradient-primary py-3">
            	<h6 class="m-0 font-weight-bold text-gray-300">TEKLİF LİSTESİ</h6>
            </div>
            <div class="card-body">
            	<div class="table-responsive">
            		<div class="container col-12">
                		<div class="row">
                    		<div  class="col-12">
                        		<table id="tablebot" class="table table-bordered table-striped" >
                        			<thead>
                        				<tr>
                        					<th class="bg-gray-700 text-gray-200 text-center align-middle" style="width: 6%"  ><input id="ara_id" 		placeholder="Id" 		type="text" class="form-control form-control-user " value="<?=date("Y") ?>"><i id="i1" class="srt fas fa-sort" aria-hidden="true"></i></th>
                        					<th class="bg-gray-700 text-gray-200 text-center align-middle" style="width: 10%" ><input id="ara_unvan" 	placeholder="Unvan" 	type="text" class="form-control form-control-user "><i id="i2" class="srt fas fa-sort" aria-hidden="true"></i></th>
                        					<th class="bg-gray-700 text-gray-200 text-center align-middle" style="width: 10%" ><input id="ara_isortagi" placeholder="İş Ortağı" type="text" class="form-control form-control-user "><i id="i3" class="srt fas fa-sort" aria-hidden="true"></i></th>
                        					<th class="bg-gray-700 text-gray-200 text-center align-middle" style="width: 15%" >
                        						<input id="ara_dnm_ust" 	placeholder="Dönem Üst" type="date" class="form-control form-control-user ">
                        						<i id="i4" class="srt fas fa-sort" aria-hidden="true"></i>
                    						</th>
                        					<th class="bg-gray-700 text-gray-200 text-center align-middle" style="width: 6%" ><input id="ara_dton" 	placeholder="DTON" type="text" class="form-control form-control-user "><i id="i5" class="srt fas fa-sort" aria-hidden="true"></i></th>
                        					<th class="bg-gray-700 text-gray-200 text-center align-middle" style="width: 10%" >
                        						<select class=" custom-select form-control " id="ara_frc">
                                        			<option class="dznl_val" value="" selected="selected">FRÇ</option>
                                        			<?php 
                                        			foreach ($cmbFr as $v){
                                        			     echo '<option class="dznl_val" value="'.$v['id'].'">'.$v['adi'].'</option>';
                                    			     }
                                                    ?>
                                                </select>
                                                <i id="i6" class="srt fas fa-sort" aria-hidden="true"></i>
                        					</th>
                        					<th class="bg-gray-700 text-gray-200 text-center align-middle" style="width: 10%" >
                        						<input id="ara_ttr_alt" 	placeholder="Alt Tutar" type="text" class="form-control form-control-user ">
                        						<input id="ara_ttr_ust" 	placeholder="Üst Tutar" type="text" class="form-control form-control-user ">
                        						<i id="i7" class="srt fas fa-sort" aria-hidden="true"></i>
                    						</th>
                        					<th class="bg-gray-700 text-gray-200 text-center align-middle" style="width: 8%" >
                            					<select class=" custom-select form-control " id="ara_para">
                                        			<option class="dznl_val" value="" selected="selected">Para Birimi</option>
                                        			<?php 
                                        			foreach ($cmbPr as $v){
                                        			     echo '<option class="dznl_val" value="'.$v['id'].'">'.$v['adi'].'</option>';
                                    			     }
                                                    ?>
                                                </select>
                                                <i id="i8" class="srt fas fa-sort" aria-hidden="true"></i>
                        					</th>
                        					<th class="bg-gray-700 text-gray-200 text-center align-middle" style="width: 10%" >
					                    		<select class=" custom-select form-control " id="ara_durum">
                                        			<option class="dznl_val" value="" selected="selected">Durumu</option>
                                        			<?php 
                                        			foreach ($cmbDr as $v){
                                        			     echo '<option class="dznl_val" value="'.$v['id'].'">'.$v['adi'].'</option>';
                                    			     }
                                                    ?>
                                                </select>
                                                <i id="i9" class="srt fas fa-sort" aria-hidden="true"></i>
                        					</th>
                        					<th class="bg-gray-700 text-gray-200 text-center align-middle" style="width: 15%"  >
                        						<a id="dznl_temiz" class="btn btn-danger col-lg-12" >
			                              			<i class="fa fa-eraser"></i><span class="text">Temizle</span>
                          						</a>
                        						<a id="dznl_bul" class="btn btn-primary col-lg-12" >
			                              			<i class="fa fa-search"></i><span class="text">Bul</span>
                          						</a>
                      						</th>
                        				</tr>
                        			</thead>
                        			<tbody id="tableLst" >
                    				</tbody>
                        		</table>
                    		</div>
                		</div>
            		</div>
            	</div>
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
    function tableSirala(tbl){
    	$(".srt").each(function (dt) {
    		$(this).click(function () {
				var id =$(this).attr('id');
        		if ($(this).is('.fa-sort-down')) {
            		$(this).removeClass('fa-sort-down');
                    $(this).addClass('fa-sort-up');
                    sortOrder = -1;
                } else {
                    $(this).removeClass('fa-sort');
                    $(this).removeClass('fa-sort-up');
                    $(this).addClass('fa-sort-down');
                    sortOrder = 1;
                }

    			$(".srt").each(function (dt) {
        			if ($(this).attr('id') != id) {
                    $(this).removeClass('fa-sort-down');
                    $(this).removeClass('fa-sort-up');
                    $(this).addClass('fa-sort');
        			}
    			});

    			var arrData = $(tbl).find('tbody >tr:has(td)').get();
                arrData.sort(function (a, b) {
                    var val1 = $(a).children('td').text().toUpperCase();
                    var val2 = $(b).children('td').text().toUpperCase();
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

    $('#dznl_bul').click(function(e){
		listele(null);
	});

    $('#dznl_temiz').click(function(e){
    	$('#ara_id')		.val(null);       
    	$('#ara_unvan')		.val(null);    
    	$('#ara_isortagi')	.val(null); 
    	$('#ara_dnm_ust')	.val(null);  
    	$('#ara_dton')		.val(null);     
    	$('#ara_frc')		.val(null);
    	$('#ara_ttr_alt')	.val(null);  
    	$('#ara_ttr_ust')	.val(null);  
    	$('#ara_para')		.val(null);     
    	$('#ara_durum')		.val(null);	
	});
	
    listele(<?=date("Y") ?>);
    function listele(id){
    	//loadEkranAc();
		var table = $('#tableLst');
		table.empty();
    	$.post("<?= PREPATH.'post/denetimPost.php?fnk=tklfList' ?>",
        {
        	ara_id		: (id == null ? $('#ara_id').val() : id),     
        	ara_unvan   : $('#ara_unvan').val(),
        	ara_isortagi: $('#ara_isortagi').val(),
        	ara_dnm_ust	: $('#ara_dnm_ust').val(),
        	ara_dton  	: $('#ara_dton').val(),
        	ara_frc    	: $('#ara_frc').val(),
        	ara_ttr_alt : $('#ara_ttr_alt').val(),
        	ara_ttr_ust	: $('#ara_ttr_ust').val(),
        	ara_para  	: $('#ara_para').val(), 
        	ara_durum  	: $('#ara_durum').val(),
        	durum  		: '<?= DenetimDurum::GRUP_TEKLIF ?>',
        },
        function(data,status){
        	if(status == "success"){
        	    var obj = JSON.parse(data);
        	    if (obj.hata == true) {
        			hataAc(obj.hataMesaj);
        	    }else{
            	    //console.log(obj);
        			var ob = obj.icerik;
        			if (ob != null && ob != ""){
            			ob.forEach(function(item){
							var tklf = '';
            			    if (item.drmId == <?= DenetimDurum::DURUM_TASLAK ?> || item.drmId == <?= DenetimDurum::DURUM_DUZENLE ?> || item.drmId == <?= DenetimDurum::DURUM_DUZENLENDI ?>) {
            			      <?php if (yetkiSoa::yetkiVarmi(yetkiConfig::TEKLIF_DUZENLEME)){ ?>
            			      	tklf = '<a href="<?=PREPATH.$yni['program_link'].'?id=' ?>'+item.id+'" data-toggle="tooltip" title="Düzenle" data-placement="top" class="btn btn-warning ml-2 tooltp" ><i class="fas fa-edit"></i></a>';
							  <?php } ?>
				    		}else if (item.drmId == <?= DenetimDurum::DURUM_ONAY_YONETICI ?> || item.drmId == <?= DenetimDurum::DURUM_ONAY_MUSTERI ?> || item.drmId == <?= DenetimDurum::DURUM_ONAYLI ?>) {
				    		  <?php if (yetkiSoa::yetkiVarmi(yetkiConfig::TEKLIF_YONETICI_ONAYLAMA)){ ?>
				    		  	tklf = '<a href="<?=PREPATH.$ynt['program_link'].'?id=' ?>'+item.id+'" data-toggle="tooltip" title="Düzenle" data-placement="top" class="btn btn-warning ml-2 tooltp" ><i class="fas fa-edit"></i></a>';
							  <?php } ?>
				    		}else{
				    			tklf = '';
				    		}
            				tklf = tklf + '<a href="<?=PREPATH.$dsy['program_link'].'?id=' ?>'+item.id+'" data-toggle="tooltip" title="Dosya Görüntüle/Yükle" data-placement="top" class="btn btn-warning ml-2 tooltp" ><i class="fas fa-folder-open"></i></a>';
            				tklf = tklf + '<a href="#"  id="notBtn"	 onClick="notAc('+item.id+');"		  data-toggle="tooltip" title="Notlar" 		data-placement="top" class="btn btn-warning ml-2 tooltp" ><i class="far fa-sticky-note"></i></a>';
            				
            			    table.append('<tr class="listeEleman" >'+
            				'<td class="text-center align-middle">'+item.id+'</td>'+
            				'<td class="text-center align-middle">'+item.munvan+'</td>'+
            				'<td class="text-center align-middle">'+item.iunvan+'</td>'+
            				'<td class="text-center align-middle">'+formatTarihNoktali(item.donem_bts_trh)+'</td>'+
            				'<td class="text-center align-middle"><a data-toggle="tooltip" title="'+item.dton+'" data-placement="top"  class = "tooltp" >'+item.dton_id+'</a></td>'+
            				'<td class="text-center align-middle">'+item.frc_id+'</td>'+
            				'<td class="text-center align-middle">'+formatPara(item.tutar)+'</td>'+
            				'<td class="text-center align-middle">'+item.para_birimi_id+'</td>'+
            				'<td class="text-center align-middle" >'+item.durum_id+'</td>'+
            				'<td class="text-center align-middle" id="'+item.id+'_link" >'+tklf+'</td>'+
                			'</tr>');
                			
            			});
        				$('.tooltp').tooltip();
        			}
                }
        	}else if(status == "error"){
        	    hataAc("Bir sorun oluştu.");
            }
    		loadEkranKapat();
        });
    }

    function notAc(id){
    	$("#myModal").modal();
    	$.get( "<?php echo PREPATH.'pages/genel/tekNotDuzenle.php?'?>"+'id='+id, function(data, status){
    		if(status == "success"){
    			$("#txtHint").empty();
    			$("#txtHint").append(data);
    		}else if(status == "error"){
    		    hataAc("Bilgi çekilemedi.");
    	    }
    	});

    	
    }
    
    
    function notDuzenle(grup,kod) {
        $.get( "<?php echo PREPATH.'pages/genel/tekNotDuzenle.php?'?>"+'grup='+grup+'&kod='+kod, function(data, status){
    		if(status == "success"){
    			$("#txtHint").empty();
    			$("#txtHint").append(data);
    		}else if(status == "error"){
    		    hataAc("Bilgi çekilemedi.");
    	    }
    	});
    }

/////////////////////////////////////////////////////////////////////////////////////
</script>
<script async defer src="https://apis.google.com/js/api.js" >
</script>
<?php include (PREPATH.'footer.php'); ?>