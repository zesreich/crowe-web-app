<div id="uyariList"></div>
<script type="text/javascript">



    function uyariDuzenle(){
        var table = $('#uyariList');
        table.find("div").remove();
        $.post( "<?=PREPATH.'post/mkPost.php?tur=uyarilar'?>",
    		{
    			tklf_id	: <?=$tklf_id?>,
    		}, 
    	    function(data, status){
    		if(status == "success"){

    			var objx = JSON.parse(data);
    			console.log(objx.hata);
    		    if (objx.hata == false) {
		    		var obj = JSON.parse(objx.icerik);
     			   	for (var [key, v] of Object.entries(obj)) {
     			   		var uyr = '';
     			   		uyr += '<div class="card">';
     			   		uyr += '<div class="card">';
     			   		uyr += '<div class="card-header" data-toggle="collapse" data-target="#'+key+'list">';
     			   		
     	                if (v.durum == 0){
     	                	uyr += '<i class="fas fa-pause-circle fa-2x" style="color: #ff0000;"></i>';
     	                }else if(v.durum == 1){
     	                	uyr += '<i class="fas fa-play-circle fa-2x" style="color: #0000ff;"></i>';
     	                }else if(v.durum == 2){
     	                	uyr += '<i class="fas fa-check-circle fa-2x" style="color: #00ff00;"></i>';
     	                }

     	                var notRenk = '';
     	                var notVr = false;
     	               	if (typeof v.notVarmi !== 'undefined') {
         	               	if (v.sayilar.not > 0) {
     	                		notRenk = 'btn-danger';
     	                		notVr = true;
         	               	}else{
         	               		notRenk = 'btn-success';
         	               	}
     	               	}else{
							notRenk = 'btn-outline-primary';
     	               	}
     	                
     	               	uyr += '    '+v.baslik;
                        <?php if (KullaniciTurPrm::ISORTAGI != $_SESSION['login']['tur'] && KullaniciTurPrm::MUSTERI != $_SESSION['login']['tur']) {?>
         	               	uyr += '<a href="#" onclick="notDuzenle(\''+key+'\',\''+key+'\');" data-toggle="modal" data-target="#myModalRisk" class="mx-2 btn '+notRenk+' float-right">';
         	                uyr += '<i class="'+ (notVr ? ' far fa-pulse ' : 'fas')+'  fa-bell " ></i>';
         	                uyr += '</a>';
     	                <?php }?>

     	                if (v.sayilar.eksik > 0) {
     	                	uyr += '<span class="badge-danger badge-pill float-right">Eksik : '+v.sayilar.eksik+'</span>';	
						}
     	               	if (v.sayilar.not > 0) {
     	               		uyr += '<span class="badge-danger badge-pill float-right">Not : '+v.sayilar.not+'</span>';
     	               	}
     	               	if (v.sayilar.prdr > 0) {
     	               		uyr += '<span class="badge-danger badge-pill float-right">Prosedur : '+v.sayilar.prdr+'</span>';
     	               	}
     	               	if (v.sayilar.prdrOK > 0) {
     	               		uyr += '<span class="badge-success badge-pill float-right">Prosedür : '+v.sayilar.prdrOK+'</span>';
     	               	}
     	               	uyr += '</div>';
     	               	uyr += '<div id="'+key+'list" class="collapse" >';
     	                uyr += '<div class="card-body">';

     	               	for (ia = 0; ia < v.eksik.length; ia++) {
     	               		uyr += '<div class="alert alert-danger" role="alert">';
     	               		uyr += '<strong>Eksik Alan !</strong>   '+v.eksik[ia];
     	               		uyr += '</div>';
     	            	}

     	              	if (typeof v.notVarmi !== 'undefined') {
         	               for (var [kn, vn] of Object.entries(v.notVarmi)) {
             	               if (vn == 1) {
             	               		uyr += '<div class="alert alert-danger" role="alert">';
             	               		uyr += '<strong>Not var!</strong>   '+key+' - '+kn;
								}else{
             	               		uyr += '<div class="alert alert-success" role="alert">';
             	               		uyr += '<strong>Cevaplanmış not.</strong>   '+key+' - '+kn;
								}
             	               uyr += '</div>';
         	               }
     	               	}

     	               	if (typeof v.prosedur !== 'undefined') {
         	               for (var [kp, vp] of Object.entries(v.prosedur)) {
             	               uyr += '<div class="alert alert-danger" role="alert">';
             	               uyr += '<strong>Eksik Prosedür!</strong>   '+key+' - '+kp;
             	               uyr += '</div>';
         	               }
     	               	}
     	               	uyr += '</div>';
     	               	uyr += '</div>';
 	               		uyr += '</div>';
 	               		table.append(uyr);
     				}
    		    }
        	}else if(status == "error"){
        	    hataAc("Bir sorun oluştu.");
            }
        });
        
    }

    uyariDuzenle();
</script>
