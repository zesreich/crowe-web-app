<?php
$buLink = __DIR__ . '/pages/is/prosedurDosya.php?tklf_id=' . $tklf_id . '&grup=' . mkConfig::MK4 . '&kod=1';

?>
<div class="card shadow mb-1">
	<div class="card-header py-3">
		<h6 class="mt-2 font-weight-bold text-primary">Yazılı Taahhütler</h6>
	</div>
	<div class="card-body">
		<input name="dosya" type="file" id="denetciUpload" hidden />
		<div class="row" id="denetci_list_row"></div>
	</div>
</div>

<div class="card shadow mb-1">
	<div class="card-header">
		<h6 class="m-0 font-weight-bold text-primary">Yakınlık Tehdidi</h6>
	</div>
	<div class="card-body">
		<div class="col justify-content-center">
			<h5 class="pl-5">Şirketin önceki denetimleri</h5>
		</div>
		<div class="row">
        <?php
        $yasak = true;
        foreach ($eskiIsler as $k => $v) {
            if ($v == null){
                $yasak = false;
            }
        }
        foreach ($eskiIsler as $k => $v) {
            echo '<div class="col-sm">';
            if ($v != null && ($tklf_id != $v['tklf_id'] || !$yasak)) {
                echo '<div class="card bg-success text-white shadow">';
            } else if ($v != null ) {
                echo '<div class="card bg-danger text-white shadow">';
            } else {
                echo '<div class="card bg-secondary text-white shadow">';
            }
            echo '<div class="card-body">';
            echo $k;
            if ($v != null) {
                echo '<div class="text-white-50 small">Teklif Id : ' . $v['tklf_id'] . '</div>';
            } else {
                echo '<div>.</div>';
            }
            echo '</div>';
            echo '</div>';
            echo '</div>';
        }
        ?>
    	</div>
	</div>
	<div class="card-body">
	<?php
    foreach ($eskiDenetci as $k1 => $v1) {
        echo '<div class="col justify-content-center" >';
        echo '	<h5 class="pl-5">' . $v1['ad_soyad'] .'</h5>';
        echo '</div>';
        echo '<div class="row">';
        foreach ($v1['isler'] as $k => $v) {
            echo '<div class="col-sm">';
            if ($v != null && ($tklf_id != $v['tklf_id'] || ($v1['uygun'] == 'true' && $tklf_id == $v['tklf_id'])) ) {
                echo '<div class="card bg-success text-white shadow">';
            } else if ($v != null && $v1['uygun'] == 'false' && $tklf_id == $v['tklf_id']) {
                echo '<div class="card bg-danger text-white shadow">';
            } else {
                echo '<div class="card bg-secondary text-white shadow">';
            }
            echo '<div class="card-body">';
            echo $k;
            if ($v != null) {
                echo '<div class="text-white-50 small">Teklif Id : ' . $v['tklf_id'] . '</div>';
            } else {
                echo '<div>.</div>';
            }
            echo '</div>';
            echo '</div>';
            echo '</div>';
        }
        echo '</div>';
    }
    ?>
	</div>
</div>
<div class="card shadow mb-1">
	<div class="card-header py-3">
		<h6 class="mt-2 font-weight-bold text-primary">Yasal Beyanlar</h6>
	</div>
	<div class="card-body">
		<input name="dosya" type="file" id="denetciUpload" hidden />
		<div class="row" id="beyan_list_row"></div>
	</div>
</div>




<script>

var duId = null;
var duAd = null;

function _denetci_upload(id,adSoyad){
	duId = id;
	duAd = adSoyad;
    $("#denetciUpload").click();
}

$("_#denetciUpload").bind("change", function () {
	var my_files = document.getElementById("denetciUpload");
	if (my_files.files && my_files.files[0] ){
		var reader = new FileReader();
		reader.onload = function() {
			var file_data = reader.result;
        	$.post('<?=PREPATH.'post/drivePost.php?tur=denetci_belge_upload'?>',
                {
            		tklf_id		: <?=$tklf_id ?>,
            		link		: <?="'".$buLink."'" ?>,
            		data		: file_data,
            		id			: duId,
            		adSoyad		: duAd,
            		name 		: my_files.files[0].name
        	    },
        	    function(data,status){
        		    denetciListesiCalistir();
        	    }
            );

			
		}
		reader.readAsDataURL(my_files.files[0]); //oku
	}
});

function beyanVeDenetciYukle(){
	beyanListesiCalistir();
	denetciListesiCalistir();
}

function beyanListesiCalistir(){
    var table = $('#beyan_list_row');
    table.find("div").remove();
    $.post( "<?=PREPATH.'post/mk4Post.php?tur=beyan'?>",
		{
			tklf_id	: <?=$tklf_id?>,
			link	: <?="'".$link."'" ?>
		}, 
	    function(data, status){
		if(status == "success"){
		    var objx = JSON.parse(data);
		    if (objx.hata == false) {
 			    var obj = JSON.parse(objx.icerik);
				obj.forEach(function(item){
					var btnler = '';
					if (item.id == null) {
						btnler =
						'<tr>'+
                    		'<td style="width: 50%" class="p-0 m-0 text-center"><a href="#"  class="btn btn-primary" onclick="pdfBeyanIndir(\''+item.key+'\');"><i class="fas fa-arrow-down"></i></a></td>'+
                    		'<td style="width: 50%" class="p-0 m-0 text-center">'+
    							'<form enctype="multipart/form-data" action="<?= PREPATH.'post/mk4Post.php?tur=beyanBelegeYukle' ?>" method="POST">'+
    								'<input name="dosya" type="file" id="fUpload_'+item.key+'" hidden />'+ 
    								'<input type="hidden" name="link" value="<?=$link ?>">'+
    								'<input type="hidden" name="tklfid" value="<?=$tklf_id ?>">'+
    								'<input type="hidden" name="key" value="'+item.key+'">'+
    								'<input type="submit" value="Submit" id="fsubmit_'+item.key+'" hidden>'+
    								'<a id="button-upload_'+item.key+'" href="#" class="btn btn-primary " >'+
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
							'<td style="width: 33%" class="p-0 m-0 text-center"><a href="#" onclick="beyanBelgeSil(\''+item.key+'\')" class="btn btn-primary" ><i class="fas fa-times"></i></input></td>'+
                  		'</tr>';
					}
					
                    table.append(
            		'<div class="col-xl-4">'+
                        '<div class="card shadow h-100">'+
                            '<div class="card-body">'+
                        		'<table class="table table-bordered">'+
                                  '<tbody>'+
                                    '<tr>'+
                                      '<td class="text-center" colspan="4">'+item.key+'</td>'+
                                    '</tr>'+
                                    	btnler+
                              		'</tbody>'+
                                '</table>'+
                            '</div>'+
                        '</div>'+
                    '</div>'
                	);
                	
                    $("#button-upload_"+item.key).click(function () {
                        $("#fUpload_"+item.key).click();
                    });

                    $("#fUpload_"+item.key).bind("change", function () {
                        $("#fsubmit_"+item.key).click();
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

function denetciListesiCalistir(){
    var table = $('#denetci_list_row');
    table.find("div").remove();
    $.post( "<?=PREPATH.'post/mk4Post.php?tur=denetci&islem=sorumluliste'?>",
		{
			tklf_id	: <?=$tklf_id?>,
			link	: <?="'".$link."'" ?>
		}, 
	    function(data, status){
		if(status == "success"){
		    var objx = JSON.parse(data);
		    if (objx.hata == false) {
 			    var obj = JSON.parse(objx.icerik);
				obj.forEach(function(item){
					var btnler = '';
					if (item.drive_id == null) {
						btnler =
						'<tr>'+
                    		'<td style="width: 50%" class="p-0 m-0 text-center"><a href="#"  class="btn btn-primary" onclick="pdfIndir('+item.denetci_id.id+');"><i class="fas fa-arrow-down"></i></a></td>'+
                    		'<td style="width: 50%" class="p-0 m-0 text-center">'+
    							'<form enctype="multipart/form-data" action="<?= PREPATH.'post/mk4Post.php?tur=denetciBelegeYukle' ?>" method="POST">'+
    								'<input name="dosya" type="file" id="fUpload_'+item.denetci_id.id+'" hidden />'+ 
    								'<input type="hidden" name="link" value="<?=$link ?>">'+
    								'<input type="hidden" name="tklfid" value="<?=$tklf_id ?>">'+
    								'<input type="hidden" name="denetciId" value="'+item.denetci_id.id+'">'+
    								'<input type="submit" value="Submit" id="fsubmit_'+item.denetci_id.id+'" hidden>'+
    								'<a id="button-upload_'+item.denetci_id.id+'" href="#" class="btn btn-primary " >'+
    								'<i class="fas fa-arrow-up"></i>'+
    								'</a>'+
    							'</form>'+
                			'</td>'+
                		'</tr>';
					}else{
						btnler=
						'<tr>'+
							'<td style="width: 33%" class="p-0 m-0 text-center"><a href="'+item.url+'" 		class="btn btn-primary" ><i class="fas fa-cloud-download-alt"></i></a></td>'+
							'<td style="width: 33%" class="p-0 m-0 text-center"><a href="'+item.webUrl+'"	class="btn btn-primary" target="_blank" ><i class="fas fa-external-link-alt"></i></i></a></td>'+
							'<td style="width: 33%" class="p-0 m-0 text-center"><a href="#" onclick="denetciBelgeSil('+item.denetci_id.id+')" class="btn btn-primary" ><i class="fas fa-times"></i></input></td>'+
                  		'</tr>';
					}
					
                    table.append(
            		'<div class="col-xl-4">'+
                        '<div class="card shadow h-100">'+
                            '<div class="card-body">'+
                        		'<table class="table table-bordered">'+
                                  '<tbody>'+
                                    '<tr>'+
                                      '<td class="text-center" colspan="4">'+item.denetci_id.ad+' '+item.denetci_id.soyad+'</td>'+
                                    '</tr>'+
                                    '<tr>'+
                                      '<td class="text-center" colspan="4">'+item.ekip+' - '+item.gorev+'</td>'+
                                    '</tr>'+
                                    	btnler+
                              		'</tbody>'+
                                '</table>'+
                            '</div>'+
                        '</div>'+
                    '</div>'
                	);
                	
                    $("#button-upload_"+item.denetci_id.id).click(function () {
                        $("#fUpload_"+item.denetci_id.id).click();
                    });

                    $("#fUpload_"+item.denetci_id.id).bind("change", function () {
                        $("#fsubmit_"+item.denetci_id.id).click();
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

function denetciBelgeSil(denetciId){
	loadEkranAc();
    var c = confirm("Silmek istediğinize emin misiniz?");
    if (c) {
    	$.post("<?=PREPATH.'post/mk4Post.php?tur=delete' ?>",{
    		link 	  : <?="'".$link."'" ?>,
    		denetciId : denetciId,
    		tklf_id	  : <?= $tklf_id?>
        },function(data,status){
    		if(status == "success"){
    			denetciListesiCalistir();
    		}else if(status == "error"){
    		    hataAc("Bir sorun oluştu.");
    	    }
    		loadEkranKapat();
	    });
    }
}

function beyanBelgeSil(key){
	loadEkranAc();
    var c = confirm("Silmek istediğinize emin misiniz?");
    if (c) {
    	$.post("<?=PREPATH.'post/mk4Post.php?tur=beyanDelete' ?>",{
    		link 	  : <?="'".$link."'" ?>,
    		key 	  : key,
    		tklf_id	  : <?= $tklf_id?>
        },function(data,status){
    		if(status == "success"){
    			beyanListesiCalistir();
    		}else if(status == "error"){
    		    hataAc("Bir sorun oluştu.");
    	    }
    		loadEkranKapat();
	    });
    }
}

function pdfBeyanIndir(key){
	window.open("<?= PREPATH.'post/mk4Post.php?tur=beyanIndir&tklfId='.$tklf_id.'&key=' ?>"+key);
}

function pdfIndir(dntId){
	window.open("<?= PREPATH.'post/mk4Post.php?tur=sozlesmeIndir&tklfId='.$tklf_id.'&denetciId=' ?>"+dntId);
}

// denetciListesiCalistir();
</script>