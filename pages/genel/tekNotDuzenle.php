<?php 
include_once '../../db/Crud.php';
include_once '../../soa/yetkiSoa.php';

?>
<div class="modal-body">
    <div class="row">
    	<table id="tablebot" class="table table-bordered table-striped " >
    		<thead>
    			<tr>
    				<th class="bg-gray-700 text-gray-200 text-center align-middle">Not:</th>
				</tr>
    		</thead>
    		<tbody >
    			<tr>
    				<td class="text-center align-middle">
    					<textarea rows="8" class="form-control" id="dznl_bilgi" ></textarea>
					</td>
    			</tr>
    			<tr>
    				<td >
    					<button id="dznl_btn" type="button" class="btn btn-primary col-lg-12"  >Not Ekle</button>
    				</td>
    			</tr>
    		</tbody>
    	</table>
    </div>
</div>
<script type="text/javascript">


async function  detayAc(){
    const res = await $.get( "<?php echo PREPATH.'../post/genelPost.php?tur=getById&tablo=denetim&id='.$_GET['id']?>");
    var objx = JSON.parse(res);
    var obj = JSON.parse(objx.icerik);
    $('#dznl_bilgi').			val(obj.bilgi);
}

$("#dznl_btn").click(function(){
	loadEkranAc();
    $.post("<?=PREPATH.'../post/denetimPost.php?fnk=tklfAckKydt' ?>",
        {
        	id				: <?=$_GET['id']?>,
            bilgi           : $('#dznl_bilgi').val(),
	    },
	    function(data,status){
    		if(status == "success"){
    		    var obj = JSON.parse(data);
    		    if (obj.hata == true) {
    				hataAc(obj.hataMesaj);
    		    }else{
    		    	onayAc('Kayıt işlemi tamamlandı.');
    		    }
    		}else if(status == "error"){
    		   hataAc("Bir sorun oluştu.");
    	    }
    		loadEkranKapat();
	    }
    );
});


detayAc();
</script>