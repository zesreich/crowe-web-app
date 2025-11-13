<?php
$pId = 235;
include_once '../../../First.php';
include_once PREPATH . 'header.php';
include_once '../../../soa/takvimSoa.php';

//cmb = takvimConfig::KONULAR;

$gelen  = Crud::getSqlCok(new Kullanici(), Kullanici::KULLANICI_TUR, array('tur'=>KullaniciTurPrm::DENETCI));
$prms = takvimSoa::takvimPrm();

?>
<link href='lib/main.css' rel='stylesheet' />
<script src='lib/main.js'></script>
<script src='lib/locales-all.js'></script>

<style>
#calendar {
	height: 85vh;
}
</style>

<div class="row">
    <div class="col-lg-4">
    	<div class="table-responsive">
    		<input id="search" type="text" class="form-control form-control-user"  placeholder="Arama">
    		<br>
    		<table id="tablebot" class="table table-bordered table-striped" >
    			<thead>
    				<tr>
    					<th class="bg-gray-700 text-gray-200 text-center align-middle">Id</th>
    					<th class="bg-gray-700 text-gray-200 text-center align-middle">Ad Soyad</th>
    					<th class="bg-gray-700 text-gray-200 text-center align-middle">Kullanıcı Adı</th>
    					<th class="bg-gray-700 text-gray-200 text-center align-middle">Unvan</th>
    				</tr>
    			</thead>
    			<tbody>
        			<?php 
        			if ($gelen != null){
            			foreach ($gelen as $gln){
                        ?>
                    	<tr class="listeEleman <?=(((isset($_GET['id']) && $_GET['id'] == $gln->id) || $gln->id == $_SESSION['login']['id'] ) ? 'table-danger' : '' )?>" onclick="detayAc('<?= $gln->id ?>')" >
                    		<td class="text-center align-middle" id="list_id"><?= $gln->id ?> </td>
                    		<td class="text-center align-middle"><?= $gln->ad.' '.$gln->soyad  ?> </td>
                    		<td class="text-center align-middle"><?= $gln->kullanici_adi ?> </td>
                    		<td class="text-center align-middle"><?= $gln->grup_id->ref->deger->adi->deger.' - '.$gln->grup_id->ref->deger->kullanici_tur_id->ref->deger->adi->deger ?> </td>
                    	</tr>
                        <?php 			    
            			}
        			}
        			?>
    			</tbody>
    		</table>
    	</div>
    </div>
    <div class="col-lg-8">
        <button type="button" class="btn" data-toggle="modal"data-target="#myModalf" id="f_buton" hidden></button>
        <div id='calendar'></div>
    </div>
</div>
    

<div class="modal fade" id="myModalf" role="dialog">
	<div class="modal-dialog modal-xl">
		<div class="modal-content">
			<div class="card">
				<div class="card-block">
					<div id="txtHint">
						<div class="row">
                            <div class="col-12 " >
                                <div class="card shadow">
                                    <div class="card-header bg-gradient-primary ">
                                    	<h4 class="m-0 font-weight-bold text-gray-300">Etkinlik</h4>
                                    </div>
                                    <div class="card-body">
                                    	<input type="hidden" id="dznl_ilk" value="">
                                    	<input type="hidden" id="dznl_son" value="">
                                    	<input type="hidden" id="dznl_allDay" value="">
                                        <div class="row mb-2">
                                    		<div class="col-lg-4 font-weight-bold text-gray-800 align-self-center text-right">Id :</div>
                                    		<div class="col-lg-8 col-xl-6"><input type="text"  id="dznl_id" class="form-control form-control-user" disabled></div>
                                    	</div>
                                    	<div class="row mb-2">
                                        	<div class="col-lg-4 font-weight-bold text-gray-800 align-self-center  text-right">Açıklama : </div>
                                    		<div class="col-lg-6">
                                    			<input type="text"  id="dznl_aciklama" class="form-control form-control-user" >
                                			</div>
                                    	</div>
                                       <div class="row mb-2">
                                    		<div class="col-lg-4 font-weight-bold text-gray-800 align-self-center  text-right">Konu :</div>
                                    		<div class="col-lg-6 ">
                                        		<select class=" custom-select form-control " id="dznl_konu">
                                        			<option class="dznl_val" value="" selected="selected">Seçiniz</option>
                                        			<?php
                                        			foreach ($prms as $val){
                                        			    echo '<option class="dznl_val" value="'.$val['id'].'">'.$val['adi'].'</option>';
                                    			    }
                                                    ?>
                                                </select>
                                    		</div>
                                    	</div>
                                    	<div class="row py-2">
											<div class="col-lg-4 text-center">
												<button type="button" class="btn btn-primary col-lg-8" onclick="kaydet()" data-dismiss="modal">
													<i class="fas fa-save"></i><span class="text">  Kaydet</span>
												</button>
											</div>
											<div class="col-lg-4 text-center">
												<button type="button" class="btn btn-danger col-lg-8" onclick="sil()" data-dismiss="modal">
													<i class="fa fa-trash"></i><span class="text">  Sil</span>
												</button>
											</div>
											<div class="col-lg-4 text-center">
												<button type="button" class="btn btn-secondary col-lg-8" data-dismiss="modal">
													<i class="fas fa-times"></i><span class="text">  Kapat</span>
												</button>
											</div>
										</div>
									
                                    </div>
                                </div>
                            </div>
                        </div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<script>

	tableSirala("#tablebot");
	tableArama("#tablebot","#search");

	var dntc_id = <?=(isset($_GET['id']) ? $_GET['id'] : $_SESSION['login']['id'] ) ?>;
  
    var calendar;
    document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');
    calendar = new FullCalendar.Calendar(calendarEl, {
      headerToolbar: {
        left: 'prev,next today',
        center: 'title',
        right: 'dayGridMonth,timeGridWeek,timeGridDay,listMonth'
      },
      locale: 'tr',
      buttonIcons: true,
      weekNumbers: true, 
      navLinks: true,
      editable: true,
      dayMaxEvents: true,
      selectable: true,
      events:'<?= PREPATH.'post/takvimPost.php?tur=takvimGetir&denetci_id=' ?>'+dntc_id,
      select: function(data){
          form(data);
      },
      eventClick: function(data) {
    	  form(data.event);
      },
      eventDrop:function(data){
    	  form(data.event);
      },
      eventResize:function(data){
    	  form(data.event);
      }
    });
    calendar.render();
    
    });
  
  	function form(data){
		$('#dznl_id').val(null);	
  		$('#dznl_ilk').val(formatTarihSaatforForm(data.start));
  		$('#dznl_son').val(formatTarihSaatforForm(data.end));
  		$('#dznl_allDay').val(data.allDay);
  		
  		if (typeof(data.id) != 'undefined') {
  			$('#dznl_id').val(data.id);	
            $.get( "<?= PREPATH.'post/takvimPost.php?tur=tek&id='?>"+data.id, function(data, status){
        		if(status == "success"){
        			var objIc = JSON.parse(data);
        			if (objIc.hata == true) {
          				hataAc(objIc.hataMesaj);
          		    }else{
                	    $('#dznl_aciklama').val(objIc.icerik.aciklama);
                	    $('#dznl_konu').	val(objIc.icerik.konu);
          		    }
        		}else if(status == "error"){
        		    hataAc("Bilgi çekilemedi.");
        	    }
        	});
  		}else{
  			$('#dznl_aciklama').val(null);
    	    $('#dznl_konu').	val(null);
  		}
	    $("#f_buton").click();
    }

  	function kaydet(){
    	$.post('<?= PREPATH.'post/takvimPost.php?tur=kayitDuzenle' ?>',
        {
        	id 			: $('#dznl_id').val(),
            denetci_id	: dntc_id,
            ilk 		: $('#dznl_ilk').val(),
            son 		: $('#dznl_son').val(),
            allDay 		: $('#dznl_allDay').val(),
            aciklama 	: $('#dznl_aciklama').val(),
            konu 		: $('#dznl_konu').val()
    	},
      	function(data,status){
      		if(status == "success"){
      		    var objIc = JSON.parse(data);
      		    if (objIc.hata == true) {
      				hataAc(objIc.hataMesaj);
      		    }else{
      		    	calendar.refetchEvents();
      		    }
      		}else if(status == "error"){
      		    hataAc("Bir sorun oluştu.");
      	    }
        });
  	}
  	
    function sil(){
        $.post('<?= PREPATH.'post/takvimPost.php?tur=sil' ?>',
          {
            id : $('#dznl_id').val()
          },
          function(data,status){
      		if(status == "success"){
      		    var objIc = JSON.parse(data);
      		    if (objIc.hata == true) {
      				hataAc(objIc.hataMesaj);
      		    }else{
      		    	calendar.refetchEvents();
      		    }
      		}else if(status == "error"){
      		    hataAc("Bir sorun oluştu.");
      	    }
          });
      }

    function detayAc(id){
    	window.open('<?=PREPATH.'pages/genel/takvim/takvimListe.php?id='?>'+id, "_self");
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