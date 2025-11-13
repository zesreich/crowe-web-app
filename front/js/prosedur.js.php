<script type="text/javascript">
    function prosedurYukle(grup){
    	prosedurDosya(grup);
    	prosedurNot(grup);
    	prosedurRefs(grup);
    	prosedurRisk(grup);
    }

    function prosedurDosya(grup){
		$.post('<?= PREPATH.'post/mkPost.php?tur=mkDriveIdGrup' ?>',
	        {
				tklf_id : <?=$tklf_id ?>,
        		grup 	: grup,
		    },
		    function(data,status){
	    		if(status == "success"){
	    		    var objIc = JSON.parse(data);
	    		    if (objIc.hata == true) {
	    				hataAc(objIc.hataMesaj);
	    		    }else{
	    		    	var snc = JSON.parse(objIc.icerik);
	    		    	for (var l in snc) {
		    		    	if (snc[l] != null) {
		    		    		prosedurDosyaYukle(grup,l,snc[l]);
							}
	    		    	}
	    		    }
	    		}else if(status == "error"){
	    		    hataAc("Bir sorun oluştu.");
	    	    }
		    }
	    );
	}

    function riskDosya(grup){
		$.post('<?= PREPATH.'post/riskDenetimPost.php?tur=riskDriveIdGrup' ?>',
	        {
				tklf_id : <?=$tklf_id ?>,
        		grup 	: grup,
		    },
		    function(data,status){
	    		if(status == "success"){
	    		    var objIc = JSON.parse(data);
	    		    if (objIc.hata == true) {
	    				hataAc(objIc.hataMesaj);
	    		    }else{
	    		    	var snc = JSON.parse(objIc.icerik);
	    		    	for (var l in snc) {
		    		    	if (snc[l]['drive_id'] != null) {
			    		    	prosedurDosyaYukle(snc[l]['rlKod'], snc[l]['rpKod'],snc[l]['drive_id']);
							}
	    		    	}
	    		    }
	    		}else if(status == "error"){
	    		    hataAc("Bir sorun oluştu.");
	    	    }
		    }
	    );
	}

	function prosedurNot(grup){
		$.post('<?= PREPATH.'post/mkPost.php?tur=mkNotGrup' ?>',
	        {
				tklf_id : <?=$tklf_id ?>,
        		grup 	: grup,
		    },
		    function(data,status){
	    		if(status == "success"){
	    		    var objIc = JSON.parse(data);
	    		    if (objIc.hata == true) {
	    				hataAc(objIc.hataMesaj);
	    		    }else{
	    		    	var snc = JSON.parse(objIc.icerik);
	    		    	for (var l in snc) {
							var btn = $('#pNot_'+grup.replaceAll(".", "")+'_'+l.replaceAll(".", "\\.")); 
							var zil = $('#pZil_'+grup.replaceAll(".", "")+'_'+l.replaceAll(".", "\\."));
							zil.removeClass("fas far fa-pulse"); 
		    		    	btn.removeClass("btn-outline-primary btn-success btn-danger");
		    		    	if(snc[l]==1){
		    		    		btn.addClass( "btn-danger" );
		    		    		zil.addClass("far fa-pulse");
		    		    	}else{
		    		    		btn.addClass( "btn-success" );
		    		    		zil.addClass("fas");
		    		    	}
	    		    	}
	    		    }
	    		}else if(status == "error"){
	    		    hataAc("Bir sorun oluştu.");
	    	    }
		    }
	    );
	}
	
	function prosedurRisk(grup){
		$.post('<?= PREPATH.'post/mkPost.php?tur=mkRiskGrup' ?>',
	        {
				tklf_id : <?=$tklf_id ?>,
        		grup 	: grup,
		    },
		    function(data,status){
	    		if(status == "success"){
	    		    var objIc = JSON.parse(data);
	    		    if (objIc.hata == true) {
	    				hataAc(objIc.hataMesaj);
	    		    }else{
		    		    var snc = JSON.parse(objIc.icerik);
						for(var i in pList[grup]){
							var str = '#riskList_'+grup.replaceAll(".", "")+'_'+pList[grup][i][1].replaceAll(".", "\\.");
					    	var table = $(str);
					    	
					    	table.empty();
							var tur = 5;
							if (typeof snc[pList[grup][i][1]] !== 'undefined'){
    		    		    	for (var l in snc[pList[grup][i][1]]) {
        		    		    	tur--;
        		    		    	var item = snc[pList[grup][i][1]][l];
        		    		    	prosedurRiskEkle(grup,pList[grup][i][1],item);
    		    		    	}
							}
							for (var j = 0; j < tur; j++) {
								prosedurRiskEkle(grup,pList[grup][i][1],null);
				 			}
				 			table.append(
			 					"<div class='col-2 border d-flex align-items-center justify-content-center' >"+
                            	"	<a href='#' onclick=\"riskEkle("+pList[grup][i][0]+",'"+grup+"','"+pList[grup][i][1]+"');\" data-toggle='modal' data-target='#myModalRisk' class='btn btn-primary' ><i class='fas fa-plus'></i></a>"+
                            	"</div>"
    			 			);
		    		    }
	    		    }
	    		}else if(status == "error"){
	    		    hataAc("Bir sorun oluştu.");
	    	    }
		    }
	    );
	}
	
	function prosedurRefs(grup){
		$.post('<?= PREPATH.'post/mkPost.php?tur=mkRefsGrup' ?>',
	        {
				tklf_id : <?=$tklf_id ?>,
        		grup 	: grup,
		    },
		    function(data,status){
	    		if(status == "success"){
	    		    var objIc = JSON.parse(data);
	    		    if (objIc.hata == true) {
	    				hataAc(objIc.hataMesaj);
	    		    }else{
		    		    var snc = JSON.parse(objIc.icerik);
						for(var i in pList[grup]){
							var str = '#refsList_'+grup.replaceAll(".", "")+'_'+pList[grup][i][1].replaceAll(".", "\\.");
					    	var table = $(str);
					    	table.empty();
							var tur = 5;
							if (typeof snc[pList[grup][i][1]] !== 'undefined'){
    		    		    	for (var l in snc[pList[grup][i][1]]) {
        		    		    	tur--;
        		    		    	var item = snc[pList[grup][i][1]][l];
        		    		    	prosedurRefsEkle(grup,pList[grup][i][1],item);
    		    		    	}
							}
							for (var j = 0; j < tur; j++) {
				     			prosedurRefsEkle(grup,pList[grup][i][1],null);
				 			}
				 			table.append(
    			 				"<div class='col-2 border d-flex align-items-center justify-content-center' >"+
    			                "	<a id='refsEkle_"+grup+"_"+pList[grup][i][1]+"' href='#' onclick=\"refsEkle("+pList[grup][i][0]+",'"+grup+"','"+pList[grup][i][1]+"');\" data-toggle='modal' data-target='#myModalRisk' class='btn btn-primary '><i class='fas fa-plus'></i></a>"+
    			             	"</div>"
    			 			);
		    		    }
		    		    if (typeof riskButonEngelleme === "function") {
		    		    	riskButonEngelleme(grup,pList[grup][i][1]);
						}
						
	    		    }
	    		}else if(status == "error"){
	    		    hataAc("Bir sorun oluştu.");
	    	    }
		    }
	    );
	}

    function prosedurRefsEkle(grup,kod,item){
        var str = '#refsList_'+grup.replaceAll(".", "")+'_'+kod.replaceAll(".", "\\.");
    	var table = $(str);
    	if (item != null) {
        	table.append(
    	  	'<div class="col-2 border text-center bg-success text-white" id = "refs_'+item.id+'">'+
    		'	<a data-toggle="tooltip" title="'+item.adi+'" data-placement="top"  class = "tooltp" >'+item.kod+'</a>'+
    		'	<br/>'+
    		'	<a href="#" onclick="refsSil('+item.id+',\''+grup+'\');" class="btn" ><i class="fas fa-times" style="color: #FFFFFF;"></i></a>'+
    		'</div>');
		}else{
        	table.append(
    	  	'<div class="col-2 border text-center text-white" >'+
    		'&nbsp;<br/>&nbsp;'+
    		'</div>');
		}
    }

    function prosedurRiskEkle(grup,kod,item){
        var str = '#riskList_'+grup.replaceAll(".", "")+'_'+kod.replaceAll(".", "\\.");
    	var table = $(str);
    	if (item != null) {
        	table.append(
    			'<div class="col-2 border text-center bg-danger text-white '+grup.replaceAll(".", "")+'-'+kod+'" id = "risk_'+item.id+'">'+
				'<a data-toggle="tooltip" title="'+item.adi+'" data-placement="top"  class = "tooltp" >'+item.kod+'</a>'+
				'<br/>'+
				'<a href="#" onclick="riskSil('+item.pId+',\''+grup+'\');" class="btn" ><i class="fas fa-times" style="color: #FFFFFF;"></i></a>'+
    			'</div>'
    		);
		}else{
        	table.append(
    	  	'<div class="col-2 border text-center text-white" >'+
    		'&nbsp;<br/>&nbsp;'+
    		'</div>');
		}
    }

    function notDuzenle(grup,kod) {
        $.get( "<?php echo PREPATH.'pages/genel/notDuzenle.php?'?>"+'tklf_id='+<?=$tklf_id ?>+'&grup='+grup+'&kod='+kod, function(data, status){
    		if(status == "success"){
    			$("#txtHintRisk").empty();
    			$("#txtHintRisk").append(data);
    		}else if(status == "error"){
    		    hataAc("Bilgi çekilemedi.");
    	    }
    	});
    }


    function riskEkle(pId,grup,kod) {
        $.get( "<?php echo PREPATH.'pages/genel/riskAra.php?pId='?>"+pId+'&grup='+grup+'&kod='+kod, function(data, status){
    		if(status == "success"){
    			$("#txtHintRisk").empty();
    			$("#txtHintRisk").append(data);
    		}else if(status == "error"){
    		    hataAc("Bilgi çekilemedi.");
    	    }
    	});
    }


    function refsEkle(pId,grup,kod) {
        $.get( "<?php echo PREPATH.'pages/genel/refsAra.php?pId='?>"+pId+'&grup='+grup+'&kod='+kod+'&tklf='+<?=$tklf_id ?>, function(data, status){
    		if(status == "success"){
    			$("#txtHintRisk").empty();
    			$("#txtHintRisk").append(data);
    		}else if(status == "error"){
    		    hataAc("Bilgi çekilemedi.");
    	    }
    	});
    }

    function refsAppend(refsId,pId,grup,kod) {
        $.post("<?=PREPATH.'post/mk1Post.php?tur=refs&islem=insert' ?>",
                {
            		tklf_id : <?=$tklf_id ?>,
            		grup 	: grup,
            		kod		: kod,
            		refs_id : refsId,
        	    },
        	    function(data,status){
            		if(status == "success"){
            		    var obj = JSON.parse(data);
            		    if (obj.hata == true) {
            				hataAc(obj.hataMesaj);
            		    }else{
            		    	//obj = obj.icerik;
            		    	prosedurRefs(grup);
            		    	onayAc('Kayit tamamlandı.');
            		    }
            		}else if(status == "error"){
            		    hataAc("Bir sorun oluştu.");
            	    }
        	    }
            );
    }
    
    function riskAppend(riskId,pId,grup,kod) {
    	$.post("<?=PREPATH.'post/mk1Post.php?tur=risk&islem=insert' ?>",
            {
        		tklf_id : <?=$tklf_id ?>,
        		grup 	: grup,
        		kod		: kod,
        		risk_id	: riskId,
    	    },
    	    function(data,status){
        		if(status == "success"){
        		    var obj = JSON.parse(data);
        		    if (obj.hata == true) {
        				hataAc(obj.hataMesaj);
        		    }else{
        		    	prosedurRisk(grup);
        		    	onayAc('Kayit tamamlandı.');
        		    }
        		}else if(status == "error"){
        		    hataAc("Bir sorun oluştu.");
        	    }
    	    }
        );
    }

    function riskFormKaydet(vid,grup,kod){
    	$.post("<?=PREPATH.'post/riskDenetimPost.php?tur=insert' ?>",
            {
        		id 			: vid,
        		aciklama	: $('#'+grup.replaceAll(".", "")+'_'+kod+'_aciklama').val(),
    	    },
    	    function(data,status){
        		if(status == "success"){
        		    var obj = JSON.parse(data);
        		    if (obj.hata == true) {
        				hataAc(obj.hataMesaj);
        		    }else{
        		    	onayAc('Kaydedildi.');
        		    }
        		}else if(status == "error"){
        		    hataAc("Bir sorun oluştu.");
        	    }
    	    }
        );
    }

    function prosedurFormKaydet(vid,mkId,kod){
    	if ($('#'+mkId.replaceAll(".", "")+'_'+vid+'_sonuc').val() != '<?=mkConfig::PROSEDUR_SONUC_CIDDI?>' || $("."+mkId.replaceAll(".", "")+"-"+kod.replaceAll(".", "\\.")).length != 0 ){
        	$.post("<?=PREPATH.'post/mk1Post.php?tur=form&islem=update' ?>",
                {
            		id 			: vid,
    				kapsami 	: $('#'+mkId.replaceAll(".", "")+'_'+vid+'_kapsam').val(),
    				zamani		: $('#'+mkId.replaceAll(".", "")+'_'+vid+'_zaman').val(),
    				sonuc		: $('#'+mkId.replaceAll(".", "")+'_'+vid+'_sonuc').val(),
            		aciklama	: $('#'+mkId.replaceAll(".", "")+'_'+vid+'_aciklama').val(),
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
    	}else{
    		hataAc("Sonuç : 'Ciddi Risk' seçili ise risk seçilmesi gerekmektedir.");
    	}
    }

    function prosedurDosyaYukle(grup,kod,drvId){
		var table = $('#refsList_'+grup.replaceAll(".", "")+'_'+kod.replaceAll(".", "\\.")+'_da');
		table.removeAttr("title");
		var deger = '';
		$.post('<?= PREPATH.'post/drivePost.php?tur=belge_isim_List' ?>',
	        {
				link 	: '<?=$link ?>">',
				driveId	: drvId,
		    },
		    function(data,status){
	    		if(status == "success"){
	    		    var objIc = JSON.parse(data);
	    		    if (objIc.hata == true) {
	    				hataAc(objIc.hataMesaj);
	    		    }else{
		    		    for(var ic in objIc.icerik){
		    		    	deger = deger + objIc.icerik[ic]+"\n";
		    		    }
		    		    if(objIc.icerik.length){
                    		table.removeClass('btn-primary');
                    		table.addClass('btn-success');
		    		    }else{
		    		    	table.removeClass('btn-success');
                    		table.addClass('btn-primary');
		    		    }
		    		    table.attr("title", deger);
	    		    }
	    		}else if(status == "error"){
	    		    hataAc("Bir sorun oluştu.");
	    	    }
		    }
	    );
	}

    function riskSil(prId,grup) {
    	$.post("<?=$prePath.'post/mk1Post.php?tur=risk&islem=delete' ?>",
            {prId 	: prId},
    	    function(data,status){
	    		if(status == "success"){
	    		    var obj = JSON.parse(data);
	    		    if (obj.hata == true) {
	    				hataAc(obj.hataMesaj);
	    		    }else{
	    		    	onayAc(obj.icerik);
	    		    	prosedurRisk(grup);
	    		    }
	    		}else if(status == "error"){
	    		    hataAc("Bir sorun oluştu.");
	    	    }
    	    }
        );
    }
    
    function refsSil(prId,grup) {
    	$.post("<?=PREPATH.'post/mk1Post.php?tur=refs&islem=delete' ?>",
            {prId 	: prId},
    	    function(data,status){
	    		if(status == "success"){
	    		    var obj = JSON.parse(data);
	    		    if (obj.hata == true) {
	    				hataAc(obj.hataMesaj);
	    		    }else{
	    		    	onayAc(obj.icerik);
	    		    	prosedurRefs(grup);
	    		    }
	    		}else if(status == "error"){
	    		    hataAc("Bir sorun oluştu.");
	    	    }
    	    }
        );
    }
</script>       