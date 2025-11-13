<div class="card shadow mb-1">
    <div class="card-body">
    	<nav >
          	<div class="nav nav-tabs "	id="nav-tabiki" role="tablist">
            <?php 
                foreach (mkConfig::B55_01_LIST  as $pln){
                    echo '<a class="nav-item nav-link mk mr-1 text-center" 		data-toggle="tab" id="b00_btn"	role="tab" href="#nav-a'.str_replace(".","",$pln[0]).'" onclick="sayfaYukle(\''.$pln[0].'\')">'.$pln[0].'</a>';
                }
                
            ?>
			</div>
       	</nav>
        <div class="border">
            <div class="tab-content m-3" id="nav-tabContent">
            <?php 
                foreach (mkConfig::B55_01_LIST  as $pln){
                    $prosedurKod = $pln[0];
                    $prosedurs   = $pros[$prosedurKod];
                    echo '<div class="tab-pane fade " id="nav-a'.str_replace(".","",$pln[0]).'" role="tabpanel" >';
                    echo '<div class="text-center"><h5>'.$pln[1].'</h5></div>';
                        ?>
                    	<div class="card-body">
                    		<table id="tablebot_<?=str_replace('.','_',$prosedurKod) ?>" class="table table-bordered table-striped " >
                    			<thead>
                    				<tr>
                    					<th class="bg-gray-700 text-gray-200 text-center align-middle col-5">Görüşülen kişilerin</th>
                    					<th class="bg-gray-700 text-gray-200 text-center align-middle col-5">Görev</th>
                    					<th class="bg-gray-700 text-gray-200 text-center align-middle col-2">
                    						<button type="button" class="btn btn-primary col-11" data-toggle="modal" onclick="bosEkle('<?=str_replace('.','_',$prosedurKod) ?>')" data-target="#myModal" id="mstrBtn" >BOŞ EKLE</button>
                    					</th>
                    				</tr>
                    			</thead>
                    			<tbody  id="<?=str_replace('.','_',$prosedurKod)?>_tableLst" >
                    			</tbody>
                    		</table>
                		</div>
                        <?php 
                    foreach ($prosedurs as $p){
                        include 'plan_prosedur.php';
                    }
                    echo '</div>';
                }
            ?>
          	</div>
    	</div>
    </div>
</div>
<script type="text/javascript">

function sayfaYukle(kod){
	prosedurYukle(kod);
	kisileriGetir(kod.replaceAll('.','_'));
}

function kisileriGetir(kod){
	var table = $('#'+kod+'_tableLst');
	table.find("tr").remove();
	
    $.post( "<?=PREPATH.'post/planlama/b50Post.php?tur=b55KisiGetir'?>",{
    	tklf_id	: <?=$tklf_id?>,
    	kod		: kod
    },function(data, status){
    	if(status == "success"){
    		var objx = JSON.parse(data);
    	    if (objx.hata == false) {
			    var obj = JSON.parse(objx.icerik);
    			if (obj != null && obj != ""){
        			for (var i = 0; i < obj.length; i++) {
        				kisiDuzenle(kod,obj[i]);
    				}
    			}
    	    }
    	}else if(status == "error"){
    	    hataAc("Bir sorun oluştu.");
        }
    });
}

function bosEkle(kod){
	var item =
	{
		id			: 0,
		adSoyad		: '',
		gorev		: '',
	};
	kisiDuzenle(kod,item);
}

function kisiDuzenle(kod,item){
	var str = '';
	str = str + ('<tr id="'+kod+'_eleman_'+item.id+'" >');
	str = str + ('	<input type="hidden" id="'+kod+'_id_'+item.id+'" value="'+item.id+'"/>');
	str = str + ('  <td class="text-center align-middle"><input id="'+kod+'_adSoyad_'+item.id+'" type="text" class="form-control form-control-user" value="'+item.adSoyad+'"></td>');
	str = str + ('  <td class="text-center align-middle"><input id="'+kod+'_gorev_'+item.id+'" type="text" class="form-control form-control-user" value="'+item.gorev+'"></td>');
	str = str + ('  <td class="text-center align-middle">');
	str = str + ('  	<button type="button" class="btn btn-primary col-5" onclick="postEkle(\''+kod+'\','+item.id+')" >Kaydet</button>');
	str = str + ('      <button type="button" class="btn btn-danger  col-5" onclick="postSil(\''+kod+'\','+item.id+')" >Sil</button>');
	str = str + ('  </td>');
	str = str + ('</tr>');
	
	var table = $('#'+kod+'_tableLst');
	table.append(str);
}

function postSil(kod,id){
	console.log('#'+kod+'_id_'+id);
	console.log($('#'+kod+'_id_'+id).val());
	if (id == 0) {
		$('#'+kod+'_eleman_'+id).remove();
	}else{
		$.post( "<?=PREPATH.'post/planlama/b50Post.php?tur=b55KisiSil'?>",
			{
				id	: $('#'+kod+'_id_'+id).val(),
			}, 
		    function(data,status){
	    		if(status == "success"){
	    		    var obj = JSON.parse(data);
	    		    if (obj.hata == true) {
	    				hataAc(obj.hataMesaj);
	    		    }else{
	    		    	onayAc(obj.icerik);
	    		    	kisileriGetir(kod.replaceAll('.','_'));
	    		    }
	    		}else if(status == "error"){
	    		    hataAc("Bir sorun oluştu.");
	    	    }
		    }
	    );
	}
}

function postEkle(kod,id){
	var link;
	link = "<?=PREPATH.'post/planlama/b50Post.php?tur=b55KisiEkle' ?>";
	$.post(link,
        {
    		id 			: $('#'+kod+'_id_'+id).val(),
    		tklf_id		: <?=$tklf_id?>,
        	kod			: kod,
        	adSoyad		: $('#'+kod+'_adSoyad_'+id).val(),
        	gorev		: $('#'+kod+'_gorev_'+id).val(),
	    },
	    function(data,status){
    		if(status == "success"){
    		    var obj = JSON.parse(data);
    		    if (obj.hata == true) {
    				hataAc(obj.hataMesaj);
    		    }else{
    		    	onayAc(obj.icerik);
    		    	kisileriGetir(kod.replaceAll('.','_'));
    		    }
    		}else if(status == "error"){
    		    hataAc("Bir sorun oluştu.");
    	    }
	    }
    );
}

function prosedurFormKaydetOncu(vid,grup,kod){
	if (grup.substring(0, 6) == 'B55.01') {
    	$.post("<?=PREPATH.'post/planlama/b80Post.php?tur=tespitKaydet' ?>",
            {
        		id 		: vid,
     	       	aciklama: $('#'+grup.replaceAll(".", "")+'_'+vid+'_b551Aciklama').val(),
    	    },
    	    function(data,status){
        		if(status == "success"){
        		    var obj = JSON.parse(data);
        		    if (obj.hata == true) {
        				hataAc(obj.hataMesaj);
        		    }else{
            		    //tamamlandı.
        		    }
        		}else if(status == "error"){
        		    hataAc("Bir sorun oluştu.");
        	    }
    	    }
        );
	}
	prosedurFormKaydet(vid,grup,kod);
}

</script>