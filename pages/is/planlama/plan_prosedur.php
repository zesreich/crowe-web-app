<?php
$ntVar = false;
$ntStr = '';

//print_r($notCheckler);
// if (isset($notCheckler[$prosedurKod]) && isset($notCheckler[$prosedurKod][$p->kod->deger])){
//     if ($notCheckler[$prosedurKod][$p->kod->deger]){
//         $ntStr = 'btn-danger';
//         $ntVar = true;
//     }else{
//         $ntStr = 'btn-success';
//     }
// }else{
//     $ntStr = 'btn-outline-primary';
// }
?>
<div class="card-body" id = "<?='p_'.str_replace(".","",$prosedurKod).'_'.$p->kod->deger?>">
	<div class="border">
       	<div class="row py-3 ">
        	<div class="col justify-content-center" >
        		<a href="#" onclick="notDuzenle(<?="'".$prosedurKod."','".$p->kod->deger."'" ?>);" id="<?='pNot_'.str_replace(".","",$prosedurKod).'_'.$p->kod->deger ?>" data-toggle="modal" data-target="#myModalRisk" class="mx-2 btn <?= $ntStr ?> float-right"> 
        			<i id="<?='pZil_'.str_replace(".","",$prosedurKod).'_'.$p->kod->deger ?>" class="<?= $ntVar ? 'far fa-pulse ' : 'fas' ?> fa-bell " ></i>
        		</a>
        		<div class="h5 pl-5 font-weight-bold text-gray-800"><?= $p->kod->deger.') '.$prosedurAcks[$prosedurKod][$p->kod->deger] ?></div>
            </div>
  		</div>
        <div class="row">
        	<?php if(substr($prosedurKod, 0, 6) == 'B55.01'){?>
        	<div class="col-12">
            	<div class="card shadow h-100">
            		<div class="card-body">
            			<div class="row mb-2">
            				<div class="col-3 font-weight-bold text-gray-800 align-self-center  text-right">Şirket Görüşleri / Tespitleri / Açıklamaları : </div>
                            <div class="col-9"><textarea rows="3" class="form-control" id="<?=str_replace(".","",$prosedurKod).'_'.$p->id->deger.'_b551Aciklama' ?>" ><?= $p->b551Aciklama->deger ?></textarea></div>
                    	</div>
            		</div>
            	</div>
            </div>
            <?php }?>
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
    						<div class="col-3 align-self-center">				<button type="button" class="btn btn-primary col-lg-12" onclick="prosedurFormKaydetOncu(<?=$p->id->deger.",'".$prosedurKod."','".$p->kod->deger."'" ?>)" >Kaydet</button></div>
            				<div class="col-1 font-weight-bold text-gray-800 align-self-center  text-right">Açıklama :</div>
                            <div class="col-7">									<textarea rows="2" class="form-control" id="<?=str_replace(".","",$prosedurKod).'_'.$p->id->deger.'_aciklama' ?>" ><?= $p->aciklama->deger ?></textarea></div>
                        	<div class="col-1 text-center align-self-center">	
                        		<a href="<?=PREPATH.'pages/is/prosedurDosya.php?tklf_id='.$tklf_id.'&grup='.$prosedurKod.'&kod='.$p->kod->deger.'&drive_id='.$p->drive_id->deger.'&p_id='.$pId ?>"class="my-0 btn btn-primary" id="refsList_<?=str_replace(".","",$prosedurKod).'_'.$p->kod->deger ?>_da"  title="asd" ><i class="fas fa-folder-open fa-2x"></i></a>
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
