<?php 
include_once '../../db/Crud.php';
include_once '../../soa/yetkiSoa.php';

?>

<div class="modal-body">
    <div class="row">
    	<table id="tablebot" class="table table-bordered table-striped " >
    		<thead>
    			<tr>
    				<th style="width:27%" class="bg-gray-700 text-gray-200 text-center align-middle">Not</th>
    				<th style="width:17%" class="bg-gray-700 text-gray-200 text-center align-middle">Not Ekleyen</th>
    				<th style="width:27%" class="bg-gray-700 text-gray-200 text-center align-middle">Cevap</th>
    				<th style="width:17%" class="bg-gray-700 text-gray-200 text-center align-middle">Cevap Ekleyen</th>
    				<th style="width:12%" class="bg-gray-700 text-gray-200 text-center align-middle">
    					<button type="button" class="btn btn-primary col-lg-12"  onclick="bosNotEkle()"  >Not Ekle</button>
    				</th> 
    			</tr>
    		</thead>
    		<tbody  id="notLst" >
    		</tbody>
    	</table>
    </div>
</div>

<script type="text/javascript">
    function notListesi(){
		$('#notLst').find("tr").remove();
        $.post( "<?=PREPATH.'../post/mkPost.php?tur=not_list'?>",
    		{
    			tklf_id	: <?=$_GET['tklf_id']?>,
        		grup	: '<?=$_GET['grup']?>',
				kod		: '<?=$_GET['kod']?>'
			}, 
    	    function(data, status){
        		if(status == "success"){
        		    var objx = JSON.parse(data);
        		    if (objx.hata == false) {
         			    var obj = JSON.parse(objx.icerik);
            			if (obj != null && obj != ""){
            				var tablex = $('#notLst');
                			obj.forEach(function(item){
                			    tablex.append('<tr id="notBosEleman" >'+
                			    		'<td class="text-center align-middle"><textarea rows="3" class="form-control" id="not_soru_'+item.id+'" >'+item.soru+'</textarea></td>'+
                			    		'<td class="text-center align-middle">'+item.c_ad+' '+item.c_soyad+'<br/>'+item.create_gmt+'</td>'+
                			    		'<td class="text-center align-middle"><textarea rows="3" class="form-control" id="not_cevap_'+item.id+'" >'+(item.cevap != null ? item.cevap : '' )+'</textarea></td>'+
                			    		'<td class="text-center align-middle">'+item.u_ad+' '+item.u_soyad+'<br/>'+item.gmt+'</td>'+
                			    		'<td class="text-center align-middle">'+
                			    		'<button type="button" class="btn btn-primary col-lg-12"  onclick="not_ekleme('+item.id+')" >Düzenle</button>'+
                			    		'<button type="button" class="btn btn-danger  col-lg-12"  onclick="not_sil('+item.id+')" >Sil</button>'+
                			    		'</td>'+
                			    		'</tr>'
                					);
                			});
            			}
        		    }
        		}else if(status == "error"){
        		    hataAc("Bir sorun oluştu.");
        	    }
       	 });
    }

    function bosNotEkle() {
		var table = $('#notLst');
	    table.append('<tr id="notBosEleman" >'+
    		'<td class="text-center align-middle"><textarea rows="3" class="form-control" id="bos_soru"></textarea></td>'+
    		'<td class="text-center align-middle">-</td>'+
    		'<td class="text-center align-middle"><textarea rows="3" class="form-control" id="bos_cevap"></textarea></td>'+
    		'<td class="text-center align-middle">-</td>'+
    		'<td class="text-center align-middle">'+
    		'<button type="button" class="btn btn-primary col-lg-12"  onclick="not_ekleme(-1)" >Ekle</button>'+
    		'<button type="button" class="btn btn-danger  col-lg-12"  onclick="not_sil(-1)" >Sil</button>'+
    		'</td>'+
    		'</tr>'
		);
    }


    function not_ekleme(id){
    	var v_id,v_soru,v_cevap,link;
    	if (id == -1) {
    		v_id	= '';
    		v_soru   = $('#bos_soru').val();
    		v_cevap  = $('#bos_cevap').val();
    		link = "<?=PREPATH.'../post/mkPost.php?tur=not_ekle' ?>";
    	}else{
    		v_id	 = id;
    		v_soru   = $('#not_soru_'+id).val();
    		v_cevap  = $('#not_cevap_'+id).val();
    		link = "<?=PREPATH.'../post/mkPost.php?tur=not_ekle' ?>";
    	}
    	$.post(link,
	        {
	    		id 			: v_id,
	    		tklf_id		: <?=$_GET['tklf_id']?>,
				grup 		: '<?=$_GET['grup']?>',
				kod			: '<?=$_GET['kod']?>',
				soru		: v_soru,
				cevap		: v_cevap
		    },
		    function(data,status){
	    		if(status == "success"){
	    		    var obj = JSON.parse(data);
	    		    if (obj.hata == true) {
	    				hataAc(obj.hataMesaj);
	    		    }else{
	    		    	notListesi();
	    		    	prosedurNot('<?=$_GET['grup']?>');
	    		    }
	    		}else if(status == "error"){
	    		    hataAc("Bir sorun oluştu.");
	    	    }
		    }
	    );
    }

    notListesi();
</script>