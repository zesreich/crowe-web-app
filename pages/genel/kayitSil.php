<?php 
include_once '../../db/Crud.php';
?>

<div class="row">
    <div class="col-12 " >
        <div class="card shadow">
            <div class="card-header bg-gradient-danger ">
            	<h4 class="m-0 font-weight-bold text-gray-300">Silme Ekranı ?</h4>
            </div>
            <div class="card-body">
            	
            	<div class="row mb-2">
            		<div class="col-lg-12 font-weight-bold text-gray-800 align-self-center text-center"><h4><?= $_POST['mesaj'] ?></h4></div>
            	</div>
            	<div class="row mb-2">
            		<div class="col-lg-12 align-self-center text-center">
            		 <font size="2">Silmek için 'sil' yazıp butona basınız.</font>
            		</div>
            	</div>
                <div class="row mb-2">
            		<div class="col-lg-4"></div>
            		<div class="col-lg-4"><input type="text"  id="siltext" class="form-control form-control-user"></div>
            		<div class="col-lg-4"></div>
            	</div>
            	
            	<div class="row pt-2">
            		<div class="col-lg-3"></div>
                    <div class="col-lg-6 text-center">
                    	<button type="button" class="btn btn-danger col-lg-8" id='sil_sil'><i class="fa fa-trash"></i><span class="text">Sil</span></button> 
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
$("#sil_sil").click(function(){
	if ($('#siltext').val() == 'sil') {
    	$.post('<?= $_POST['nkt'].'post/silPost.php?tur='.$_POST['tablo'].(isset($_GET['tur']) ? '&ktur='.$_GET['tur'] : '')?>',
            {
    			id		: <?=$_POST['id'] ?>,
				link	: '<?=isset($_POST['link']) ? $_POST['link'] : '' ?>'
        	},
        	function(data, status){
    			console.log(data);
        		if(status == "success"){
        			 location.replace('<?=$_POST['donusLink'] ?>');
        		}else if(status == "error"){
        		    hataAc("Bir Sorun oluştu.");
        	    }
        	}
        );
	}
});


</script>