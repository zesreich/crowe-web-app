<?php
$pId = 149;
include_once '../../First.php';

//PARAMETRE KONTROLU//
if (isset($_GET['id'])){
    $Id = $_GET['id'];
}else if ($_SESSION['login']['musteri_id'] != null){
    $Id = $_SESSION['login']['musteri_id'];
}else{
    hata('Parametreler eksik : id',PREPATH);
}
//PARAMETRE KONTROLU//

include_once PREPATH . 'header.php';

$mstr = Crud::getById(new Musteri(), $Id)->basit();
$tbl = new Kullanici();
$gelen = Crud::getSqlCok($tbl, Kullanici::KULLANICI_MUSTERI, array('musteri'=>$Id));

$prg = Crud::getById(new Program() , 150 ) -> basit();

$swLink = Crud::getById(new Program() , 232 ) -> basit();


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
            	<h6  class="m-0 font-weight-bold text-gray-300"><?=$mstr['unvan']?></h6>
            </div>
            <div class="row">
      			<div class="col-xl-6">
                    <div class="card-body collapse" id="demo">
                    	<?php if (KullaniciTurPrm::DENETCI == $_SESSION['login']['tur']) {?>
                        <div class="row mb-2">
                    		<div class="col-lg-4 font-weight-bold text-gray-800 align-self-center text-right">Id :</div>
                    		<div class="col-lg-4"><?=$mstr['id']  ?></div>
                    		<div class="col-4" >
                    			<button type="button" class="btn btn-danger col-lg-12" data-toggle="modal" onclick="dataSil()" data-target="#myModal" id="sil_buton" >
                    				<i class="fas fa-exclamation"></i>      Müşteri Sil    <i class="fas fa-exclamation"></i>
                    			</button>
                      		</div>
                    	</div>
                    	<?php }?>
                        <div class="row mb-2">
                    		<div class="col-lg-4 font-weight-bold text-gray-800 align-self-center  text-right">Unvan :</div>
                    		<div class="col-lg-8"><?=$mstr['unvan']?></div>
                    	</div>
                        <div class="row mb-2">
                    		<div class="col-lg-4 font-weight-bold text-gray-800 align-self-center  text-right">İş Ortağı :</div>
                    		<div class="col-lg-8"><?=$mstr['isortagi_id']['unvan']?></div>
                    	</div>
                        <div class="row mb-2">
                    		<div class="col-lg-4 font-weight-bold text-gray-800 align-self-center  text-right">Vergi Dairesi :</div>
                    		<div class="col-lg-8"><?=$mstr['vergi_daire_id']['adi']?></div>
                    	</div>
                        <div class="row mb-2">
                    		<div class="col-lg-4 font-weight-bold text-gray-800 align-self-center  text-right">Vergi No :</div>
                    		<div class="col-lg-8"><?=$mstr['vergi_no']?></div>
                    	</div>
                        <div class="row mb-2">
                    		<div class="col-lg-4 font-weight-bold text-gray-800 align-self-center  text-right">Adres :</div>
                    		<div class="col-lg-8"><?=$mstr['adres']?></div>
                    	</div>
                        <div class="row mb-2">
                    		<div class="col-lg-4 font-weight-bold text-gray-800 align-self-center  text-right">Telefon :</div>
                    		<div class="col-lg-8"><?=$mstr['telefon']?></div>
                    	</div>
                        <div class="row mb-2">
                    		<div class="col-lg-4 font-weight-bold text-gray-800 align-self-center  text-right">Faks :</div>
                    		<div class="col-lg-8"><?=$mstr['faks']?></div>
                    	</div>
                        <div class="row mb-2">
                    		<div class="col-lg-4 font-weight-bold text-gray-800 align-self-center  text-right">Web :</div>
                    		<div class="col-lg-8"><?=$mstr['web']?></div>
                    	</div>
                        <div class="row mb-2">
                    		<div class="col-lg-4 font-weight-bold text-gray-800 align-self-center  text-right">Email :</div>
                    		<div class="col-lg-8"><?=$mstr['email']?></div>
                    	</div>
                        <div class="row mb-2">
                    		<div class="col-lg-4 font-weight-bold text-gray-800 align-self-center  text-right">İl :</div>
                    		<div class="col-lg-8"><?=$mstr['il_id']['adi']?></div>
                    	</div>
                        <div class="row mb-2">
                    		<div class="col-lg-4 font-weight-bold text-gray-800 align-self-center  text-right">Sektör :</div>
                    		<div class="col-lg-8"><?=$mstr['sektor_id'] != '' ? $mstr['sektor_id']['adi'] : ''?></div>
                    	</div>
                        <div class="row mb-2">
                    		<div class="col-lg-4 font-weight-bold text-gray-800 align-self-center  text-right">Mernis No :</div>
                    		<div class="col-lg-8"><?=$mstr['mernis_no']?></div>
                    	</div>
                    </div>
      			</div>
      			
      			
      			
      			
      			<div class="col-xl-6">
                    <div class="card-body collapse" id="demo">
                    	<div class="table-responsive">
                    		<div class="container col-12">
                        		<div class="row">
                            		<div class="col-10 mb-2" >
                            			<input id="search" type="text" class="form-control form-control-user "  placeholder="Arama">
                            		</div>
                            		<div class="col-2 mb-2">
                                      	<a href="<?=PREPATH.$prg['program_link'].'?id='. $Id ?>"  class="btn btn-success col-lg-12" >
                                      		<i class="fa fa-plus"></i><span class="text"> </span>
                                  		</a>
                            		</div>
                            		<div  class="col-12">
                                		<table id="tablebot" class="table table-bordered table-striped" >
                                			<thead>
                                				<tr>
                                					<th class="bg-gray-700 text-gray-200 text-center align-middle">Id  </th>
                                					<th class="bg-gray-700 text-gray-200 text-center align-middle">Kullanıcı Adı  </th>
                                					<th class="bg-gray-700 text-gray-200 text-center align-middle">Adı  </th>
                                					<th class="bg-gray-700 text-gray-200 text-center align-middle">Soyadı  </th>
                                					<th class="bg-gray-700 text-gray-200 text-center align-middle">Kullanici Türü  </th>
                                				</tr>
                                			</thead>
                                			<tbody>
                                    			<?php 
                                    			if ($gelen!= null){
                                        			foreach ($gelen as $gln){
                                                    ?>
                                                	<tr class="listeEleman" onclick="detayAc('<?= $gln->id ?>')">
                                                		<td class="text-center align-middle" id="list_id"><?= $gln->id ?> </td>
                                                		<td class="text-center align-middle"><?= $gln->kullanici_adi ?> </td>
                                                		<td class="text-center align-middle"><?= $gln->ad ?> </td>
                                                		<td class="text-center align-middle"><?= $gln->soyad ?> </td>
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
                    		</div>
                    	</div>
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
                        					<th class="bg-gray-700 text-gray-200 text-center align-middle" style="width: 10%"   ><input id="ara_id" 	placeholder="Id" 			 	type="text" class="form-control form-control-user "><i id="i1" class="srt fas fa-sort" aria-hidden="true"></i></th>
                        					<th class="bg-gray-700 text-gray-200 text-center align-middle" style="width: 20%"  ><input id="ara_munvan"  placeholder="Müşteri Unvan" 	type="text" class="form-control form-control-user "><i id="i2" class="srt fas fa-sort" aria-hidden="true"></i></th>
                        					<th class="bg-gray-700 text-gray-200 text-center align-middle" style="width: 20%" >
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

function dataSil(){
	$.post('<?php echo PREPATH.'pages/genel/kayitSil.php?tablo='.get_class(new Musteri())?>',
        {
			tablo		: '<?= get_class(new Musteri())?>',
			id			: <?=$Id ?>,
			mesaj		: 'Müşteri silmek istediğinize emin misiniz ?',
			donusLink	: '<?= PREPATH.'pages/is/musteriListesi.php'?>',
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


    $('#dznl_bul').click(function(e){
    	listele();
    });
    
    $('#dznl_temiz').click(function(e){
    	$('#ara_id').val(null);       
    	$('#ara_munvan').val(null);    
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
    	$.post("<?= PREPATH.'post/denetimPost.php?fnk=musteriIsListesi&id='.$Id ?>",
    	{
    		ara_id		: $('#ara_id').val(),     
    		ara_munvan  : $('#ara_munvan').val(),
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
            			ob.forEach(function(item){
            				var tklf = '';
    						if (item.mk_id != null) {
                			    tklf = tklf + '<a href="<?=PREPATH.$swLink['program_link'].'?id=' ?>'+item.teklif_id+'" class="btn btn-warning ml-2" ><i class="fa fa-hand-pointer-o"></i><span class="text">Detay</span></a>';
    						}
    			    		
            			    table.append('<tr class="listeEleman" >'+
            				'<td class="text-center align-middle">'+item.teklif_id+'</td>'+
            				'<td class="text-center align-middle">'+item.munvan+'</td>'+
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