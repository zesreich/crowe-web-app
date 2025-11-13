<?php 
$mk2Prs = $prosedurs[mkConfig::MK2]; //= Crud::getSqlCok(new Prosedur(), Prosedur::GET_TEKLIF_BY_GRUP, array('tklf_id'=>$_GET['id'],'grup'=>'MK2'));
?>

<div class="card shadow mb-1">
    <?php 
    foreach ($mk2Prs as $p){
        $prosedurKod = mkConfig::MK2;
        include 'mk_d_prosedur.php';
    }
    ?>
</div>

<div class="card shadow mb-1">
    <div class="card-header">
    	<h6 class="m-0 font-weight-bold text-primary">Mali Tabloların Muhtemel Kullanıcıları</h6>
    </div>
    <div class="card-body">
    	<?php 
    	   $mk2Basit = $mk2->basit();
    	   foreach (mkConfig::MK2_CHECK_MADDELERI as $v){
    	       $d = $v[0];
    	?>
            <ul class="list-group">
            	<li class="list-group-item">
              		<div>
              			<div class="align-self-center float-right mx-2"><label class="switch"><input id="chk_<?=$v[0]?>" type="checkbox" onclick="mk2_chekler(<?=$tklf_id.",'".$v[0]."'"?>)" <?= $mk2Basit[$v[0]] == 'E' ? 'checked' : '' ?>><span class="slider round"></span></label></div>
              			<h6 class="pt-3"><?=$v[1]?></h6>
              		</div>
              	</li>
            </ul>
    	<?php 
    	   }
    	?>
    </div>
</div>
<script type="text/javascript">
function mk2_chekler(tklf_id,deger){
	var ch = 'H';
	if (document.getElementById('chk_'+deger).checked){
		ch = 'E';
	} else{
		ch = 'H';
	}
	$.post("<?=PREPATH.'post/mkPost.php?tur=mk2_check' ?>",{
			tklf_id : tklf_id,
        	chk	: ch,
        	dgr	: deger,
        	usrId : <?=$_SESSION['login']['id'] ?>
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
