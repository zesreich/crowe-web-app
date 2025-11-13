<?php
$pId = 122;
include_once '../../First.php';
include_once PREPATH . 'header.php';

$kln    = new KullaniciTurPrm();
$klnList= Crud::all($kln);

$grp    = new GrupPrm();

$prg    = new Program();
$prgList= Crud::all($prg);
//$prgList= Crud::getSqlCok($prg, Program::YANLIZ_PROGRAMLAR, array());

?>
<style>
.table-responsive{
    max-height: 40vh;
}
.switch {
  position: relative;
  display: inline-block;
  width: 60px;
  height: 34px;
}

.switch input { 
  opacity: 0;
  width: 0;
  height: 0;
}

.slider {
  position: absolute;
  cursor: pointer;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: #ccc;
  -webkit-transition: .4s;
  transition: .4s;
}

.slider:before {
  position: absolute;
  content: "";
  height: 26px;
  width: 26px;
  left: 4px;
  bottom: 4px;
  background-color: white;
  -webkit-transition: .4s;
  transition: .4s;
}

input:checked + .slider {
  background-color: #0275d8;
}

input:focus + .slider {
  box-shadow: 0 0 1px #0275d8;
}

input:checked + .slider:before {
  -webkit-transform: translateX(26px);
  -ms-transform: translateX(26px);
  transform: translateX(26px);
}

/* Rounded sliders */
.slider.round {
  border-radius: 34px;
}

.slider.round:before {
  border-radius: 50%;
}
</style>


<div  class="row" >
    <div class="col-lg-6 col-xl-4 py-3" >
        <div class="card h-100 shadow">
            <div class="card-header bg-gradient-primary py-3">
            	<h6 class="m-0 font-weight-bold text-gray-300"><?=$kln->vt_Adi()?></h6>
            </div>
            <div class="card-body">
            	<div class="table-responsive">
            		<input id="searchKln" type="text" class="form-control form-control-user"  placeholder="Arama">
            		<br>
            		<table id="tableKln" class="table table-bordered table-striped" >
            			<thead>
            				<tr>
            					<th class="bg-gray-700 text-gray-200 text-center align-middle">Id  </th>
            					<th class="bg-gray-700 text-gray-200 text-center align-middle">Yetki Adı  </th>
            				</tr>
            			</thead>
            			<tbody>
                			<?php 
                			if ($klnList!= null){
                			    foreach ($klnList as $gln){
                                ?>
                            	<tr class="" id="klnc_<?= $gln->id ?>" onclick="kllncTurSec('<?= $gln->id ?>')">
                            		<td class="text-center align-middle" id="klnc_dgr"><?= $gln->id ?> </td>
                            		<td class="text-center align-middle"><?= $gln->adi ?> </td>
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
    <div class="col-lg-6 col-xl-4 py-3">
        <div class="card h-100 shadow">
            <div class="card-header bg-gradient-primary py-3">
            	<h6 class="m-0 font-weight-bold text-gray-300"><?=$grp->vt_Adi()?></h6>
            </div>
            <div class="card-body">
            	<div class="table-responsive">
            		<input id="searchGrp" type="text" class="form-control form-control-user"  placeholder="Arama">
            		<br>
            		<table id="tableGrp" class="table table-bordered table-striped" >
            			<thead>
            				<tr>
            					<th class="bg-gray-700 text-gray-200 text-center align-middle">Id  </th>
            					<th class="bg-gray-700 text-gray-200 text-center align-middle">Adı  </th>
            				</tr>
            			</thead>
            			<tbody>

            			</tbody>
            		</table>
            	</div>
            </div>
        </div>
    </div>
    <div class="col-lg-6 col-xl-4 py-3" >
        <div class=" card h-100 shadow">
            <div class="card-header bg-gradient-primary py-3">
            	<h6 class="m-0 font-weight-bold text-gray-300"><?=$prg->vt_Adi()?></h6>
            </div>
            <div class="card-body">
            	<div class="table-responsive">
            		<input id="searchPrg" type="text" class="form-control form-control-user"  placeholder="Arama">
            		<br>
            		<table id="tablePrg" class="table table-bordered table-striped" >
            			<thead>
            				<tr>
            					<th class="bg-gray-700 text-gray-200 text-center align-middle">Id  </th>
            					<th class="bg-gray-700 text-gray-200 text-center align-middle">Yetki Adı  </th>
            					<th class="bg-gray-700 text-gray-200 text-center align-middle">Link  </th>
            				</tr>
            			</thead>
            			<tbody>
                			<?php 
                			if ($prgList!= null){
                			    foreach ($prgList as $gln){
                                ?>
                            	<tr class="" id="prg_<?= $gln->id ?>" onclick="programSec('<?= $gln->id ?>')">
                            		<td class="text-center align-middle" id="prg_dgr"><?= $gln->id ?> </td>
                            		<td class="text-center align-middle"><?= $gln->program_adi.($gln->klasor->deger == 'E' ? ' (K)' : '') ?> </td>
                            		<td class="text-center align-middle"><?= $gln->program_link ?> </td>
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
</div>
<div  class="row" id="yetkiSelect"></div>

<script >
	tableSirala("#tablePrg");
	tableArama("#tablePrg","#searchPrg");
	
	tableSirala("#tableGrp");
	tableArama("#tableGrp","#searchGrp");
	
	tableSirala("#tableKln");
	tableArama("#tableKln","#searchKln");

	function yetkiGetir(){
		var klc = $('.klncSecili #klnc_dgr').text();
		var grp = $('.grpSecili #grp_dgr').text();
		var prg = $('.prgSecili #prg_dgr').text();
		if (klc != "" && grp != "" && prg != "") {
		    var table = $('#yetkiSelect');
		    table.find("div").remove();
		    $.get( "<?php echo PREPATH.'post/yetkiPost.php?tur=yetkiProgramListesi&programId='?>"+prg, function(data, status){
    			if(status == "success"){
    			    var objx = JSON.parse(data);
    			    if (objx.hata == false) {
         			    var obj = JSON.parse(objx.icerik);
            			if (obj != null && obj != ""){
                			obj.forEach(function(item){
                				table.append(
            				        '<div class="col-md-6 col-lg-4 col-xl-3 py-3" >'+
                			        	'<div class=" card h-100 shadow">'+
                			                '<div class="card-header bg-gradient-info py-3">'+
                			                	'<h5 class="m-0 font-weight-bold text-gray-300">'+item.id+' - '+ item.yetki_adi +'</h5>'+
                			                '</div>'+
                			            	'<div class="card-body align-self-center">'+
                			            		'<label class="switch">'+
                			            			'<input id="chk_'+item.id+'_'+grp+'" type="checkbox" >'+
                			            			'<span class="slider round"></span>'+
                			        			'</label>'+
                			                '</div>'+
                			            '</div>'+
                			        '</div>'
                				);
                				$('#chk_'+item.id+'_'+grp).change(function() {
                					$.post("<?php echo PREPATH.'post/yetkiPost.php?tur=yetkiCheckle&ytk='?>"+item.id+'&klc='+klc+'&grp='+grp+'&chk='+this.checked, function( dataCk ) {
                    					console.log(dataCk);
                					    var objCk = JSON.parse(dataCk);
            			    		    if (objCk.hata == true) {
            			    				hataAc(objCk.hataMesaj);
            			    		    }else{
            			    				onayAc("İşlem Tamamlandı.");
            			    		    }
                					});
                    			});
                			});
            			}
        		    }
    			    $.get( "<?php echo PREPATH.'post/yetkiPost.php?tur=yetkiGrupProgramListesi&grpId='?>"+grp+'&prgId='+prg, function(data, status){
    	    			if(status == "success"){
    	    			    var objx = JSON.parse(data);
    	    			    if (objx.hata == false) {
    	         			    var obj = JSON.parse(objx.icerik);
    	            			if (obj != null && obj != ""){
    	                			obj.forEach(function(item){
        	                			$("#chk_"+item.yetki_id.id+"_"+item.grup_id.id).prop('checked', true);
    	                			});
    	            			}
    	        		    }
    	    			}else if(status == "error"){
    	        			table.find("div").remove();
    	        		    hataAc("Bir sorun oluştu.");
    	        	    }
    	    	    });
    			}else if(status == "error"){
    				table.find("div").remove();
        		    hataAc("Bir sorun oluştu.");
        	    }
    	    });
		}
	}
	
	function kllncTurSec(id){
	    var table = $('#tableGrp');
	    table.find("tbody tr").remove();
	    $('#klnc_'+id).siblings().removeClass();
	    $('#klnc_'+id).addClass("table-danger").addClass("klncSecili");
	    $.get( "<?php echo PREPATH.'post/yetkiPost.php?tur=kullaniciTurGruplari&kullaniciTurId='?>"+id, function(data, status){
			if(status == "success"){
			    var objx = JSON.parse(data);
			    if (objx.hata == false) {
     			    var obj = JSON.parse(objx.icerik);
        			if (obj != null && obj != ""){
            			obj.forEach(function(item){
            				table.append(
            					'<tr class="" id="grp_'+ item.id +'" onclick="grupSec('+item.id+')">'+
                            		'<td class="text-center align-middle" id="grp_dgr">'+item.id+'</td>'+
                            		'<td class="text-center align-middle">'+item.adi+'</td>'+
                            	'</tr>'
            				);
            			});
        			}
    		    }
			    $('#yetkiSelect').find("div").remove();
			}else if(status == "error"){
    		    hataAc("Bir sorun oluştu.");
    	    }
	    });
	}

	function grupSec(id){
	    $('#grp_'+id).siblings().removeClass();
	    $('#grp_'+id).addClass("table-danger").addClass("grpSecili");
	    yetkiGetir();
	}

	function programSec(id){
	    $('#prg_'+id).siblings().removeClass();
	    $('#prg_'+id).addClass("table-danger").addClass("prgSecili");
	    yetkiGetir();
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