<?php 
// echo '<pre>';
// print_r($mk);
// echo '</pre>';
?>

<div class="card shadow mb-4">
	<div class="card-header py-3">
		<h6 class="m-0 font-weight-bold text-danger">Ciddi Riskler</h6>
	</div>
	<div class="card-body">
		<ul class="list-group" id="mk6_ciddi">
		</ul>
	</div>
</div>
<div class="card shadow mb-4">
	<div class="card-header py-3">
		<a href="#" onclick="mk6DegerlendirmeKaydet();" class="my-0 btn btn-primary float-right" >Kaydet</a>
		<h6 class="m-0 font-weight-bold ">Genel Değerlendirme</h6>
	</div>
	<div class="card-body">
		<div class="col-lg-12">1) Müşteri kabulune iişkin çalışmalarınızın kısa bir değerlendirmesini yapınız.</div>
		<div class="col-lg-12"><textarea rows="3" class="form-control" id="mk6_deger" ><?= $mk->degerlendirme->deger ?></textarea></div><br/>
		<div class="col-lg-12">2) Müşteri Kabul Edildi mi?</div>
		
		<a href="#" onclick="mk6Onay();" id="mk6_onay" class="btn btn-<?= $mk->kabul->deger != 'E' ? 'secondary':'success' ?> btn-icon-split">
			<span class="icon text-white-50">
				<i class="fas fa-check"></i>
			</span>
            <span class="text">KABUL</span>
        </a>
        
		<a href="#" onclick="mk6Red();" id="mk6_red" class="btn btn-<?= $mk->kabul->deger != 'H' ? 'secondary':'danger' ?> btn-icon-split">
            <span class="icon text-white-50">
              <i class="fas fa-times"></i>
            </span>
            <span class="text">RED</span>
          </a>
	</div>
</div>
<script type="text/javascript">



function prosedurTureGore(){
    
	var ciddi = $('#mk6_ciddi');
    ciddi.find("li").remove();
	
    $.post( "<?=PREPATH.'post/mk6Post.php?tur=prosedur&islem=riskleriGetir'?>",
		{
			tklf_id	: <?=$tklf_id?>,
		}, 
	    function(data, status){
    		if(status == "success"){
    		    var objx = JSON.parse(data);
    		    if (objx.hata == false) {
     			    var obj = objx.icerik;
    				obj.forEach(function(item){
        				if (item.sonuc != null && item.sonuc == '<?= mkConfig::PROSEDUR_SONUC_CIDDI ?>') {
        					ciddi.append('<li class="list-group-item"><h5>'+item.mgrup+'-'+item.mkod+' : '+item.rkod+'  :<small> '+item.adi+'</small><h5>');
						}
    				});
    		    }
    		}
		}
	);
}

function mk6Onay(dgr){
	mk6OnayRed('E');
}

function mk6Red(dgr){
	mk6OnayRed('H');
}

function mk6OnayRed(dgr){
	$.post("<?=PREPATH.'post/mk6Post.php?tur=degerlendirme&islem=onayRed'?>",
        {
    		id		: <?= $mk->id->deger?>,
			onayRed	: dgr
	    },
	    function(data,status){
    		if(status == "success"){
        		
    		    var obj = JSON.parse(data);
    		    if (obj.hata == true) {
    				hataAc(obj.hataMesaj);
    		    }else{
    		    	onayAc(obj.icerik);
    		    	$("#mk6_onay").removeClass("btn-success");
    		    	$("#mk6_red").removeClass("btn-danger");
    		    	$("#mk6_onay").removeClass("btn-secondary");
    		    	$("#mk6_red").removeClass("btn-secondary");
    		    	if (dgr == 'E') {
    		    		$("#mk6_onay").addClass("btn-success");
    		    		$("#mk6_red").addClass("btn-secondary");
					}else if (dgr == 'H') {
        		    	$("#mk6_red").addClass("btn-danger");
        		    	$("#mk6_onay").addClass("btn-secondary");
					} 
    		    }
    		}else if(status == "error"){
    		    hataAc("Bir sorun oluştu.");
    	    }
	    }
    );
}

function mk6DegerlendirmeKaydet(){
	$.post("<?=PREPATH.'post/mk6Post.php?tur=degerlendirme&islem=kaydet'?>",
        {
    		id		: <?= $mk->id->deger?>,
			deger	: $('#mk6_deger').val()
	    },
	    function(data,status){
    		if(status == "success"){
        		
    		    var obj = JSON.parse(data);
    		    if (obj.hata == true) {
    				hataAc(obj.hataMesaj);
    		    }else{
    		    	onayAc(obj.icerik);
    		    }
    		}else if(status == "error"){
    		    hataAc("Bir sorun oluştu.");
    	    }
	    }
    );
}

</script>