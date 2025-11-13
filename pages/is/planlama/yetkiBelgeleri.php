<?php
$pId = 233;
include_once '../../../First.php';
include_once PREPATH . 'config/sozlesmeConfig.php';
include_once PREPATH . 'soa/driveSoa.php';
include_once PREPATH . 'config/sablonConfig.php';
$tklf_id = $_GET['id'];
$link = 'pages/is/planlama/yetkiBelgeleri.php?id='.$tklf_id;
driveSoa::baglan($link);
include_once PREPATH . 'header.php';

$tklf   = Crud::getById(new Denetim(),$tklf_id) -> basit();
$plnm   = Crud::getSqlTek(new Planlama(), Planlama::GET_TEKLIF_ID, array('tklf_id'=>$tklf_id)) -> basit();

?>

<div class="row">
    <div class="col-lg-12 col-xl-12">
        <div class="card shadow" >
        	<div class="card-header bg-gradient-primary py-3">
            	<h6 class="m-0 font-weight-bold text-gray-300">SÖZLEŞME DÜZENLEME</h6>
            </div>
            <div class="row">
                <?php
                foreach (sablonConfig::PLANLAMALAR as $key => $value){
                ?>
                    <div class="card-body col-4">
                		<div class="card shadow mb-4">
                			<div class="card-header py-3">
                				<h6 class="m-0 font-weight-bold text-primary"><?= $value ?></h6>
                			</div>
                			<div class="card-body" id="<?= $key?>"></div>
                		</div>
                	</div>
                <?php 
                }
                ?>
        	</div>
    	</div>
	</div>
</div>
<script type="text/javascript">
<?php
$str = '[';
foreach (sablonConfig::PLANLAMALAR as $key => $value){
    $str = $str."'".$key."',";
}
$str = $str.'];';
echo 'const bsk = ' .$str;
?>

//console.log(bsk);


belgeGetir();

function belgeGetir(){
    $.post( "<?=PREPATH.'post/planlama/planlamaPost.php?tur=belgeler'?>",
		{
			tklf_id	: <?=$tklf_id?>,
			link	: <?="'".$link."'" ?>
		}, 
	    function(data, status){
		if(status == "success"){
			//console.log(data);
		    var objx = JSON.parse(data);
		    if (objx.hata == false) {
 			    var obj = JSON.parse(objx.icerik);
                bsk.forEach(function(x){
                    var item = obj[x];
                    var table = $('#'+x);
                    table.find("div").remove();
					var btnler = '';
					if (item.id == null) {
						btnler =
						'<tr>'+
                    		'<td style="width: 50%" class="p-0 m-0 text-center"><a href="#"  class="btn btn-primary" onclick="taslakGetir(\''+x+'\');"><i class="fas fa-arrow-down"></i></a></td>'+
                    		'<td style="width: 50%" class="p-0 m-0 text-center">'+
    							'<form enctype="multipart/form-data" action="<?= PREPATH.'post/planlama/planlamaPost.php?tur=belgeYukle' ?>" method="POST">'+
    								'<input name="dosya" type="file" id="fUpload_'+x+'" hidden />'+ 
    								'<input type="hidden" name="link" value="<?=$link ?>">'+
    								'<input type="hidden" name="tklfid" value="<?=$tklf_id ?>">'+
    								'<input type="hidden" name="key" value="'+x+'">'+
    								'<input type="submit" value="Submit" id="fsubmit_'+x+'" hidden>'+
    								'<a id="button-upload_'+x+'" href="#" class="btn btn-primary " >'+
    								'<i class="fas fa-arrow-up"></i>'+
    								'</a>'+
    							'</form>'+
                			'</td>'+
                		'</tr>';
					}else{
						btnler=
						'<tr>'+
							'<td style="width: 33%" class="p-0 m-0 text-center"><a href="'+item.url+'" 		class="btn btn-primary" ><i class="fas fa-cloud-download-alt"></i></a></td>'+
							'<td style="width: 33%" class="p-0 m-0 text-center"><a href="'+item.web+'"	class="btn btn-primary" target="_blank" ><i class="fas fa-external-link-alt"></i></i></a></td>'+
							'<td style="width: 33%" class="p-0 m-0 text-center"><a href="#" onclick="belgeSil(\''+x+'\')" class="btn btn-primary" ><i class="fas fa-times"></i></input></td>'+
                  		'</tr>';
					}
					
                    table.append(
                        '<div class="card shadow h-100" style="width: 100%">'+
                            '<div class="card-body">'+
                        		'<table class="table table-bordered">'+
                                  '<tbody>'+
                                    '<tr>'+
                                      '<td class="text-center" colspan="4">'+x+'</td>'+
                                    '</tr>'+
                                    	btnler+
                              		'</tbody>'+
                                '</table>'+
                            '</div>'+
                        '</div>'
                	);
                	
                    $("#button-upload_"+x).click(function () {
                    	console.log('üst');
                        $("#fUpload_"+x).click();
                    });
					
                    $("#fUpload_"+x).bind("change", function () {
                        console.log('alt');
                        $("#fsubmit_"+x).click();
                    });
                });
		    }else{
		    	hataAc(objx.hataMesaj);
		    }
    	}else if(status == "error"){
    	    hataAc("Bir sorun oluştu.");
        }
    });
    
}

function belgeSil(key){
	loadEkranAc();
    var c = confirm("Silmek istediğinize emin misiniz?");
    if (c) {
    	$.post("<?=PREPATH.'post/planlama/planlamaPost.php?tur=belgeDelete' ?>",{
    		link 	  : <?="'".$link."'" ?>,
    		key 	  : key,
    		tklf_id	  : <?= $tklf_id?>
        },function(data,status){
            console.log(data);
    		if(status == "success"){
    			belgeGetir();
    		}else if(status == "error"){
    		    hataAc("Bir sorun oluştu.");
    	    }
    		loadEkranKapat();
	    });
    }
}

function taslakGetir(key){
	window.open("<?=PREPATH.'post/planlama/planlamaPost.php?tur=belgeIndir&key=' ?>"+key);
}
</script>
<?php include (PREPATH.'footer.php'); ?>