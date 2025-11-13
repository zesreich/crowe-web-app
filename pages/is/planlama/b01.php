<style>
.switch {
  position: relative;
  display: inline-block;
  width: 60px;
  height: 34px;
}

.switch input { 
  opacity: 0;
  width: 0;
  height: 0;
}

.slider {
  position: absolute;
  cursor: pointer;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: #ccc;
  -webkit-transition: .4s;
  transition: .4s;
}

.slider:before {
  position: absolute;
  content: "";
  height: 26px;
  width: 26px;
  left: 4px;
  bottom: 4px;
  background-color: white;
  -webkit-transition: .4s;
  transition: .4s;
}

input:checked + .slider {
  background-color: #d9534f;
}

input:focus + .slider {
  box-shadow: 0 0 1px #d9534f;
}

input:checked + .slider:before {
  -webkit-transform: translateX(26px);
  -ms-transform: translateX(26px);
  transform: translateX(26px);
}

/* Rounded sliders */
.slider.round {
  border-radius: 34px;
}

.slider.round:before {
  border-radius: 50%;
}
</style>
<div class="card shadow mb-1">
	<div>
		<div class="py-3 px-3 text-left border">
        	<div class="align-self-center float-right mx-2"><label class="switch"><input id="chk_dis" type="checkbox" onclick="disB01()"  ><span class="slider round"></span></label></div>
    		<h6 class="pt-3 h5  font-weight-bold text-gray-800">Cari dönem sorumlu denetçi ile önceki dönem sorumlu denetçi aynı kişiler olduğundan bu prosedürün uygulamasına gerek yoktur.</h6>
		</div>
    </div>
    <div class="card-body" id="b01Main">
    	<nav >
          	<div class="nav nav-tabs "	id="navb01Baslik" role="tablist" >
            <?php 
                foreach (mkConfig::B01_LIST  as $pln){
                    echo '<a class="nav-item nav-link mk mr-1 text-center" data-toggle="tab" role="tab" id="b01_baslik_'.$pln[0].'"  href="#nav-a'.str_replace(".","",$pln[0]).'" onclick="prosedurYukle(\''.$pln[0].'\')">'.$pln[0].'</a>';
                }
            ?>
            <a data-toggle="tab" id="b01_hidden_btn"	role="tab" href="#nav-aa" style="display: none;">asdasd</a> 
			</div>
       	</nav>
        <div class="border">
            <div class="tab-content m-3" id="nav-tabContent">
            <?php 
                foreach (mkConfig::B01_LIST as $pln){
                    $prosedurKod = $pln[0];
                    $prosedurs   = $pros[$prosedurKod];
                    echo '<div class="tab-pane fade " id="nav-a'.str_replace(".","",$pln[0]).'" role="tabpanel" >';
                    echo '<div class="text-center"><h5>'.$pln[1].'</h5></div>';
                    foreach ($prosedurs as $p){
                        if ($pln[0] == mkConfig::B01 && $p->kod->deger == '1'){
                            ?>
<?php
$ntVar = false;
$ntStr = '';
if (isset($notCheckler[$prosedurKod]) && isset($notCheckler[$prosedurKod][$p->kod->deger])){
    if ($notCheckler[$prosedurKod][$p->kod->deger]){
        $ntStr = 'btn-danger';
        $ntVar = true;
    }else{
        $ntStr = 'btn-success';
    }
}else{
    $ntStr = 'btn-outline-primary';
}

?>
<div class="card-body" id = "<?='p_'.str_replace(".","",$prosedurKod).'_'.$p->kod->deger?>">
	<div class="border">
       	<div class="row py-3 ">
        	<div class="col justify-content-center" >
        		<a href="#" onclick="notDuzenle(<?="'".$prosedurKod."','".$p->kod->deger."'" ?>);" id="<?='pNot_'.str_replace(".","",$prosedurKod).'_'.$p->kod->deger ?>" data-toggle="modal" data-target="#myModalRisk" class="mx-2 btn <?= $ntStr ?> float-right"> 
        			<i id="<?='pZil_'.str_replace(".","",$prosedurKod).'_'.$p->kod->deger ?>" class="<?= $ntVar ? 'far fa-pulse ' : 'fas' ?> fa-bell " ></i>
        		</a>
        		<a href="<?=PREPATH.'pages/is/planlama/yetkiBelgeleri.php?id='.$tklf_id?>"class="btn btn-primary float-right" title="asd" >Yetki Belgeleri</a>
        		<div class="h5 pl-5 font-weight-bold text-gray-800"><?= $p->kod->deger.') '.$prosedurAcks[$prosedurKod][$p->kod->deger] ?></div>
            </div>
  		</div>
        <div class="row">
            <div class="col-3">
            	<div class="card shadow">
                    <div class="card-body">
                        <div class="row ">
                        	<div class="col-lg-4 font-weight-bold text-gray-800 align-self-center  text-right">Kapsamı :</div>
                    		<div class="col-lg-8">
                        		<select class=" custom-select form-control" id="<?=str_replace(".","",$prosedurKod).'_'.$p->id->deger.'_kapsam' ?>">
                        			<option class="dznl_val" value="" selected="selected">Seçiniz</option>
                        			<?php 
                        			foreach ($cmbProKap as $v){
                        			    echo '<option class="dznl_val" value="'.$v.'"'. ((trim($v) === trim($p->kapsami->deger)) ? 'selected="selected"' : '') .'>'.$v.'</option>';
                    			     }
                                    ?>
                                </select>
                    		</div>
                        </div>
                    	<div class="row">
                        	<div class="col-lg-4 font-weight-bold text-gray-800 align-self-center  text-right">Zamanı :</div>
                    		<div class="col-lg-8">
                        		<select class=" custom-select form-control " id="<?=str_replace(".","",$prosedurKod).'_'.$p->id->deger.'_zaman' ?>">
                        			<option class="dznl_val" value="" selected="selected">Seçiniz</option>
                        			<?php 
                        			foreach ($cmbProZam as $v){
                        			    echo '<option class="dznl_val" value="'.$v.'"'. ((trim($v) === trim($p->zamani->deger)) ? 'selected="selected"' : '') .'>'.$v.'</option>';
                    			     }
                                    ?>
                                </select>
                    		</div>
                        </div>
                        <div class="row">
                        	<div class="col-lg-4 font-weight-bold text-gray-800 align-self-center  text-right">Sonuç :</div>
                        	<div class="col-lg-8">
                        		<select class=" custom-select form-control " id="<?=str_replace(".","",$prosedurKod).'_'.$p->id->deger.'_sonuc' ?>" onchange="prosedurSonuc(<?='\''.$prosedurKod.'\',\''.$p->id->deger.'\'' ?>)" >
                        			<option class="dznl_val" value="" selected="selected">Seçiniz</option>
                        			<?php 
                        			foreach ($cmbProSnc as $v){
                        			    echo '<option class="dznl_val" value="'.$v.'"'. ((trim($v) === trim($p->sonuc->deger)) ? 'selected="selected"' : '') .'>'.$v.'</option>';
                    			     }
                                    ?>
                                </select>
                    		</div>
                        </div>
                        
                	</div>
            	</div>
            </div>
            <div class="col-9">
                <div class="row">
                    <div class="col-6">
                        <div class="card shadow">
                            <div class="card-header ">
                            	<h6 class="mt-2 font-weight-bold ">Risk</h6>
                            </div>
                            <div class="card-body">
                                <div class="row" 	id="riskList_<?=str_replace(".","",$prosedurKod)?>_<?=$p->kod->deger ?>">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="card shadow h-100">
                            <div class="card-header ">
                            	<h6 class="mt-2 font-weight-bold ">Referanslar</h6>
                            </div>
                            <div class="card-body">
                                <div class="row" id="refsList_<?=str_replace(".","",$prosedurKod)?>_<?=$p->kod->deger ?>">
                                </div>
                            </div>
                        </div>
                    </div>
        		</div>
        	</div>
            <div class="col-12">
            	<div class="card shadow h-100">
            		<div class="card-body">
            			<div class="row mb-2">
    						<div class="col-3 align-self-center">				<button type="button" class="btn btn-primary col-lg-12" onclick="prosedurFormKaydet(<?=$p->id->deger.",'".$prosedurKod."','".$p->kod->deger."'" ?>)" >Kaydet</button></div>
            				<div class="col-1 font-weight-bold text-gray-800 align-self-center  text-right">Açıklama :</div>
                            <div class="col-7">									<textarea rows="2" class="form-control" id="<?=str_replace(".","",$prosedurKod).'_'.$p->id->deger.'_aciklama' ?>" ><?= $p->aciklama->deger ?></textarea></div>
                        	<div class="col-1 text-center align-self-center">	
                        		<a href="<?=PREPATH.'pages/is/prosedurDosya.php?tklf_id='.$tklf_id.'&grup='.$prosedurKod.'&kod='.$p->kod->deger.'&drive_id='.$p->drive_id->deger ?>"class="my-0 btn btn-primary" id="refsList_<?=$prosedurKod.'_'.$p->kod->deger ?>_da"  title="asd" ><i class="fas fa-folder-open fa-2x"></i></a>
                    		</div>
                    	</div>
            		</div>
            	</div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
prosedurSonuc(<?="'".$prosedurKod."','".$p->id->deger."'" ?>);
</script>
                            <?php 
                        }else{
                            include 'plan_prosedur.php';
                        }
                    }
                    echo '</div>';
                }
            ?>
            <div class="tab-pane fade " id="nav-aa" role="tabpanel" ></div>
            
          	</div>
    	</div>
    </div>
</div>

<script type="text/javascript">
<?php
    echo "var ch = '".$plnm['uygula']."';";
?>
function disB01(gln){
	
	if (typeof(gln) == 'undefined' ) {
		if (ch == 'H') {
			ch = 'E';
		}else{
			ch = 'H';
		}
	}
	
	if (ch == 'E') {
    	$( "#navb01Baslik" ).addClass( "invisible" );
    	$( "#b01_hidden_btn").click();
    	document.getElementById("chk_dis").checked = true;
	}else{
    	$( "#navb01Baslik" ).removeClass( "invisible" );
    	$( "#b01_baslik_B01").click();
	    document.getElementById("chk_dis").checked = false;
	}
	
	if (typeof(gln) == 'undefined' ) {
		uygulamaGerekYok();
	}
}

function uygulamaGerekYok(){
    $.post("<?=PREPATH.'post/planlama/planlamaPost.php?tur=uygulamaGerekYok' ?>",{
        	tklf_id : <?=$tklf_id ?>,
        	uygula	: ch,
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