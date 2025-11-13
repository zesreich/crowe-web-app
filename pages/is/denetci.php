<?php
$pId = 161;
include_once '../../First.php';
//PARAMETRE KONTROLU//
if (isset($_GET['id'])){
    $Id = $_GET['id'];
}else{
    hata('Parametreler eksik : id',PREPATH);
}
//PARAMETRE KONTROLU//

include_once PREPATH . 'header.php';

$mstr   = Crud::getById(new Kullanici(), $Id)->basit();
$tbl    = new Kullanici();


$yni    = Crud::getById(new Program() , 170 ) -> basit();
$ynt    = Crud::getById(new Program() , 185 ) -> basit();
$mkLink = Crud::getById(new Program() , 204 ) -> basit();
$szLink = Crud::getById(new Program() , 208 ) -> basit();

?>
<style>
.table-responsive{
    max-height: 40vh;
}
</style>
<div class="row">

    <div class="col-lg-12 col-xl-12 pb-3">
        <div class="card shadow">
            <div class="card-header bg-gradient-primary py-3" data-toggle="collapse" data-target="#demo">
            	<h6  class="m-0 font-weight-bold text-gray-300"><?=$mstr['ad'].' '.$mstr['soyad']?></h6>
            </div>
            <div class="row">
      			<div class="col-xl-6">
                    <div class="card-body collapse" id="demo">
                        <div class="row mb-2">
                    		<div class="col-lg-4 font-weight-bold text-gray-800 align-self-center text-right">Id :</div>
                    		<div class="col-lg-8"><?=$mstr['id']  ?></div>
                    	</div>
                        <div class="row mb-2">
                    		<div class="col-lg-4 font-weight-bold text-gray-800 align-self-center  text-right">Kullanıcı Adı :</div>
                    		<div class="col-lg-8"><?=$mstr['kullanici_adi']?></div>
                    	</div>
                        <div class="row mb-2">
                    		<div class="col-lg-4 font-weight-bold text-gray-800 align-self-center  text-right">Adı :</div>
                    		<div class="col-lg-8"><?=$mstr['ad']?></div>
                    	</div>
                        <div class="row mb-2">
                    		<div class="col-lg-4 font-weight-bold text-gray-800 align-self-center  text-right">Soyadı :</div>
                    		<div class="col-lg-8"><?=$mstr['soyad']?></div>
                    	</div>
                        <div class="row mb-2">
                    		<div class="col-lg-4 font-weight-bold text-gray-800 align-self-center  text-right">Email :</div>
                    		<div class="col-lg-8"><?=$mstr['email']?></div>
                    	</div>
                        <div class="row mb-2">
                    		<div class="col-lg-4 font-weight-bold text-gray-800 align-self-center  text-right">Telefon :</div>
                    		<div class="col-lg-8"><?=$mstr['telefon']?></div>
                    	</div>
                        <div class="row mb-2">
                    		<div class="col-lg-4 font-weight-bold text-gray-800 align-self-center  text-right">Kullanıcı Türü :</div>
                    		<div class="col-lg-8"><?=$mstr['tur']['adi']?></div>
                    	</div>
                        <div class="row mb-2">
                    		<div class="col-lg-4 font-weight-bold text-gray-800 align-self-center  text-right">Grup :</div>
                    		<div class="col-lg-8"><?=$mstr['grup_id']['adi']?></div>
                    	</div>
                    </div>
      			</div>
      			<div class="col-xl-6">
                    <div class="card-body collapse" id="demo">
                    </div>
      			</div>
  			</div>
        </div>
    </div>
    
    <div class="col-lg-12 col-xl-12 pb-3">
        <div class="card shadow">
            <div class="card-header bg-gradient-primary py-3">
            	<h6 class="m-0 font-weight-bold text-gray-300">İŞ LİSTESİ</h6>
            </div>
            <div class="card-body">
            	<div class="table-responsive">
            		<div class="container col-12">
                		<div class="row">
                    		<div  class="col-12">
                        		<table id="tablebot" class="table table-bordered table-striped" >
                        			<thead>
                        				<tr>
                        					<th class="bg-gray-700 text-gray-200 text-center align-middle" style="width: 10%"  ><input id="ara_id" 		 placeholder="Id" 			 	type="text" class="form-control form-control-user "><i id="i1" class="srt fas fa-sort" aria-hidden="true"></i></th>
                        					<th class="bg-gray-700 text-gray-200 text-center align-middle" style="width: 15%"  ><input id="ara_munvan"   placeholder="Müşteri Unvan" 	type="text" class="form-control form-control-user "><i id="i2" class="srt fas fa-sort" aria-hidden="true"></i></th>
                        					<th class="bg-gray-700 text-gray-200 text-center align-middle" style="width: 15%"  ><input id="ara_iunvan"   placeholder="İş Ortağı Unvan" 	type="text" class="form-control form-control-user "><i id="i3" class="srt fas fa-sort" aria-hidden="true"></i></th>
                        					<th class="bg-gray-700 text-gray-200 text-center align-middle" style="width: 10%" >
                        						<input id="ara_dnm_alt" 	placeholder="Dönem Alt" type="date" class="form-control form-control-user" >
                        						<input id="ara_dnm_ust" 	placeholder="Dönem Üst" type="date" class="form-control form-control-user ">
                        						<i id="i4" class="srt fas fa-sort" aria-hidden="true"></i>
                    						</th>
                    						<th class="bg-gray-700 text-gray-200 text-center align-middle" style="width: 10%"  ><input id="ara_dton" 	 placeholder="DTON" 			type="text" class="form-control form-control-user "><i id="i5" class="srt fas fa-sort" aria-hidden="true"></i></th>
                        					<th class="bg-gray-700 text-gray-200 text-center align-middle" style="width: 10%"  ><input id="ara_tdurum"   placeholder="Teklif Durumu" 	type="text" class="form-control form-control-user "><i id="i3" class="srt fas fa-sort" aria-hidden="true"></i></th>
                        					<th class="bg-gray-700 text-gray-200 text-center align-middle" style="width: 10%"  ><input id="ara_mdurum"   placeholder="Müşteri Kabul Durumu" 	type="text" class="form-control form-control-user "><i id="i3" class="srt fas fa-sort" aria-hidden="true"></i></th>
                        					<th class="bg-gray-700 text-gray-200 text-center align-middle" style="width: 10%"  ><input id="ara_sdurum"   placeholder="Sözleşme Durumu" 	type="text" class="form-control form-control-user "><i id="i3" class="srt fas fa-sort" aria-hidden="true"></i></th>
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
    $('#dznl_bul').click(function(e){
    	listele();
    });

    $('#dznl_temiz').click(function(e){
    	$('#ara_id').val(null);       
    	$('#ara_munvan').val(null);    
    	$('#ara_iunvan').val(null); 
    	$('#ara_dnm_alt').val(null);  
    	$('#ara_dnm_ust').val(null);  
    	$('#ara_tdurum').val(null);     
    	$('#ara_mdurum').val(null);
    	$('#ara_sdurum').val(null);  
	});
    
    listele();
    function listele(){
    	loadEkranAc();
		var table = $('#tableLst');
		table.empty();
    	$.post("<?= PREPATH.'post/denetimPost.php?fnk=denetciIsListesi&id='.$Id ?>",
    	{
    		ara_id		: $('#ara_id').val(),     
    		ara_munvan  : $('#ara_munvan').val(),
    		ara_iunvan	: $('#ara_iunvan').val(),
    		ara_dnm_alt	: $('#ara_dnm_alt').val(),
    		ara_dnm_ust	: $('#ara_dnm_ust').val(),
    		ara_tdurum  : $('#ara_tdurum').val(),
    		ara_mdurum  : $('#ara_mdurum').val(),
    		ara_sdurum	: $('#ara_sdurum').val(),
    	},
        function(data,status){
        	if(status == "success"){
        	    var obj = JSON.parse(data);
        	    if (obj.hata == true) {
        			hataAc(obj.hataMesaj);
        	    }else{
        			var ob = obj.icerik;
        			if (ob != null && ob != ""){
            			console.log(ob);
            			ob.forEach(function(item){
            				var tklf = '';
            			    if (item.teklif_durum_id == <?= DenetimDurum::DURUM_TASLAK ?> || item.teklif_durum_id == <?= DenetimDurum::DURUM_DUZENLE ?> || item.teklif_durum_id == <?= DenetimDurum::DURUM_DUZENLENDI ?>) {
            			      <?php if (yetkiSoa::yetkiVarmi(yetkiConfig::TEKLIF_DUZENLEME)){ ?>
            			      	tklf = '<a href="<?=PREPATH.$yni['program_link'].'?id=' ?>'+item.teklif_id+'" data-toggle="tooltip" title="Düzenle" data-placement="top" class="btn btn-warning ml-2 tooltp" >Tklf</a>';
							  <?php } ?>
				    		}else if (item.teklif_durum_id == <?= DenetimDurum::DURUM_ONAY_YONETICI ?> || item.teklif_durum_id == <?= DenetimDurum::DURUM_ONAY_MUSTERI ?> || item.teklif_durum_id == <?= DenetimDurum::DURUM_ONAYLI ?>) {
				    		  <?php if (yetkiSoa::yetkiVarmi(yetkiConfig::TEKLIF_YONETICI_ONAYLAMA)){ ?>
				    		  	tklf = '<a href="<?=PREPATH.$ynt['program_link'].'?id=' ?>'+item.teklif_id+'" data-toggle="tooltip" title="Düzenle" data-placement="top" class="btn btn-warning ml-2 tooltp" >Tklf</a>';
							  <?php } ?>
				    		}else{
				    			tklf = '';
				    		}

				    		console.log(item.s_id+" - "+item.mk_id);
							if (item.s_id != null) {
	            			    tklf = tklf + '<a href="<?=PREPATH.$szLink['program_link'].'?id=' ?>'+item.teklif_id+'" class="btn btn-warning ml-2" ><i class="fa fa-hand-pointer-o"></i><span class="text">SZLSM</span></a>';
							}
							if (item.mk_id != null) {
                			    tklf = tklf + '<a href="<?=PREPATH.$mkLink['program_link'].'?id=' ?>'+item.teklif_id+'" class="btn btn-warning ml-2" ><i class="fa fa-hand-pointer-o"></i><span class="text">MK</span></a>';
							}
				    		
            			    table.append('<tr class="listeEleman" >'+
            				'<td class="text-center align-middle">'+item.teklif_id+'</td>'+
            				'<td class="text-center align-middle">'+item.munvan+'</td>'+
            				'<td class="text-center align-middle">'+item.iunvan+'</td>'+
            				'<td class="text-center align-middle">'+formatTarih(item.donem_bas)+' - '+formatTarih(item.donem_bts)+'</td>'+
            				'<td class="text-center align-middle"><a data-toggle="tooltip" title="'+item.dton+'" data-placement="top"  class = "tooltp" >'+item.dton_id+'</a></td>'+
            				'<td class="text-center align-middle">'+item.teklif_durum+'</td>'+
            				'<td class="text-center align-middle">'+item.mk_durum+'</td>'+
            				'<td class="text-center align-middle">'+item.s_durum+'</td>'+
            				'<td class="text-center align-middle" id="'+item.teklif_id+'_link" >'+tklf+'</td>'+
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

</script>
<?php include (PREPATH.'footer.php'); ?>