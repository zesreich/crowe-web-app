<?php
$pId = 234;
include_once '../../../First.php';
include_once PREPATH . 'header.php';
include_once '../../../soa/takvimSoa.php';

// $rs = takvimSoa::takvimGetir($_SESSION['login']['id']);
// echo '<pre>';
// print_r($rs);
// echo '</pre>';

//$cmb = takvimConfig::KONULAR;
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
<button type="button" class="btn" data-toggle="modal"data-target="#myModalf" id="f_buton" hidden></button>
<div id='calendar'></div>

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
  var calendar;

  var dntc_id = <?=$_SESSION['login']['id'] ?>;
  
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
      events:'<?= PREPATH.'post/takvimPost.php?tur=takvimGetir&denetci_id='.$_SESSION['login']['id'] ?>',
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
  
</script>
<?php include (PREPATH.'footer.php'); ?>