<?php
$pId = 209;
include_once '../../../First.php';
include_once PREPATH.'config/sozlesmeConfig.php';
include_once PREPATH . 'header.php';

$szLink = Crud::getById(new Program() , 232 ) -> basit();
$mkLink = Crud::getById(new Program() , 204 ) -> basit();
$cmbDr  = sozlesmeConfig::DURUMLAR;

$jsCd = null;
foreach ($cmbDr as $v){
    if ($jsCd != null){
        $jsCd = $jsCd.',';
    }
    $jsCd = $jsCd.$v[0] .':"'. $v[1].'"';
}
$jsCd = '{'.$jsCd.'}';


?>

<div class="row">
    <div class="col-lg-12 col-xl-12 pb-3">
        <div class="card shadow">
            <div class="card-header bg-gradient-primary py-3">
            	<h6 class="m-0 font-weight-bold text-gray-300">Sözleşme Listesi</h6>
            </div>
            <div class="card-body">
            	<div class="table-responsive">
            		<div class="container col-12">
                		<div class="row">
                    		<div  class="col-12">
                        		<table id="tablebot" class="table table-bordered table-striped" >
                        			<thead>
                        				<tr>
                        					<th class="bg-gray-700 text-gray-200 text-center align-middle" style="width: 10%"  ><input id="ara_id" 			placeholder="Id" 		type="text" class="form-control form-control-user "><i id="i1" class="srt fas fa-sort" aria-hidden="true"></i></th>
                        					<th class="bg-gray-700 text-gray-200 text-center align-middle" style="width: 25%"  ><input id="ara_unvan" 		placeholder="Unvan" 	type="text" class="form-control form-control-user "><i id="i2" class="srt fas fa-sort" aria-hidden="true"></i></th>
                        					<th class="bg-gray-700 text-gray-200 text-center align-middle" style="width: 25%"  ><input id="ara_isortagi" 	placeholder="İş Ortağı" type="text" class="form-control form-control-user "><i id="i3" class="srt fas fa-sort" aria-hidden="true"></i></th>
                        					<th class="bg-gray-700 text-gray-200 text-center align-middle" style="width: 10%"  ><input id="ara_dton" 	 	placeholder="DTON" 		type="text" class="form-control form-control-user "><i id="i5" class="srt fas fa-sort" aria-hidden="true"></i></th>
                        					
                        					<th class="bg-gray-700 text-gray-200 text-center align-middle" style="width: 15%" >
                        						<input id="ara_dnm_ust" 	placeholder="Dönem Üst" type="date" class="form-control form-control-user ">
                        						<i id="i4" class="srt fas fa-sort" aria-hidden="true"></i>
                    						</th>
                        					
                        					<th class="bg-gray-700 text-gray-200 text-center align-middle" style="width: 10%" >
					                    		<select class=" custom-select form-control " id="ara_durum">
                                        			<option class="dznl_val" value="" selected="selected">Durumu</option>
                                        			<?php 
                                        			foreach ($cmbDr as $v){
                                        			     echo '<option class="dznl_val" value="'.$v[0].'">'.$v[1].'</option>';
                                    			     }
                                                    ?>
                                                </select>
                                                <i id="i5" class="srt fas fa-sort" aria-hidden="true"></i>
                        					</th>
                      						<th class="bg-gray-700 text-gray-200 text-center align-middle" style="width: 10%"  >EKİP</i></th>
                        					<th class="bg-gray-700 text-gray-200 text-center align-middle" style="width: 10%"  >
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



<script >
	var jsCd = <?=$jsCd ?>;
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
		listele();
	});

    $('#dznl_temiz').click(function(e){
    	$('#ara_id')		.val(null);       
    	$('#ara_unvan')		.val(null);    
    	$('#ara_isortagi')	.val(null); 
    	$('#ara_dnm_ust')	.val(null);  
    	$('#ara_durum')		.val(null);	
	});
	
    listele();
    function listele(){
		var table = $('#tableLst');
		table.empty();
    	$.post("<?= PREPATH.'post/sozlesmePost.php?tur=sozlesmeList' ?>",
        {
        	ara_id		: $('#ara_id').val(),     
        	ara_unvan   : $('#ara_unvan').val(),
        	ara_isortagi: $('#ara_isortagi').val(),
        	ara_dnm_ust	: $('#ara_dnm_ust').val(),
        	ara_durum  	: $('#ara_durum').val(),
        },
        function(data,status){
        	if(status == "success"){
        	    var obj = JSON.parse(data);
        	    if (obj.hata == true) {
        			hataAc(obj.hataMesaj);
        	    }else{
        			var ob = obj.icerik;
        			if (ob != null && ob != ""){
            			ob.forEach(function(item){
                			var btn 	= '<a href="<?=PREPATH.$szLink['program_link'].'?id=' ?>'+item.mkno+'" class="btn btn-warning ml-2" ><i class="fa fa-hand-pointer-o"></i><span class="text"> SEÇ</span></a>';
                			//var mkbtn 	= '<a href=" class="btn btn-warning ml-2" ><i class="fa fa-hand-pointer-o"></i><span class="text"> MK</span></a>';
                			if (item.durum == <?= sozlesmeConfig::DURUM_OLUSMADI[0] ?>) {
							}else if(item.durum == <?= sozlesmeConfig::DURUM_IMZAYA_GONDER[0] ?>){
							}
							
            			    table.append('<tr class="listeEleman" >'+
            				'<td class="text-center align-middle">'+item.mkno+'</td>'+
            				'<td class="text-center align-middle">'+item.munvan+'</td>'+
            				'<td class="text-center align-middle">'+item.iunvan+'</td>'+
            				'<td class="text-center align-middle"><a data-toggle="tooltip" title="'+item.dton+'" data-placement="top"  class = "tooltp" >'+item.dton_id+'</a></td>'+
            				'<td class="text-center align-middle">'+formatTarih(item.donem_bts_trh)+'</td>'+
            				'<td class="text-center align-middle">'+jsCd[item.durum] +'</td>'+
            				'<td class="text-center align-middle" id="'+item.mkno+'_denetci"></td>'+
            				'<td class="text-center align-middle">'+
            				btn+//mkbtn+
                      		'</td>'+
                			'</tr>');

               		    	$.post("<?= PREPATH.'post/mkPost.php?tur=denetcilist' ?>",
        		    	        {
               		    			tklf_id		: item.mkno,     
        		    	        },
        		    	        function(datax,status){
        		    	        	if(status == "success"){
        		    	        		var snc = JSON.parse(datax).icerik;
        		    	        		if (snc[0] != null) {
            		    	        		var dntc = '';
            		    	        		for (var i = 0; i < snc.length; i++) {
												var n = snc[i];
        		    	        				dntc += n.adSoyad+'('+n.ekip+')\n';
											}
         		    	        			$('#'+item.mkno+'_denetci').append('<a data-toggle="tooltip" title="'+dntc+'" data-placement="top"  class = "tooltp" ><i class="fas fa-check-circle fa-2x" style="color: #51cf66;"></i></a>');
         		    	        			$('.tooltp').tooltip();
										}else{
											$('#'+item.mkno+'_denetci').append('<i class="fas fa-check-circle fa-2x"></i>');
										}
        		    	        	}else if(status == "error"){
        		    	            }
        		    	        }
    		    	        );
                			
            			});
        				$('.tooltp').tooltip();
        			}
                }
        	}else if(status == "error"){
        	    hataAc("Bir sorun oluştu.");
            }
        });
    }

/////////////////////////////////////////////////////////////////////////////////////
</script>
<script async defer src="https://apis.google.com/js/api.js" >
</script>
<?php include (PREPATH.'footer.php'); ?>