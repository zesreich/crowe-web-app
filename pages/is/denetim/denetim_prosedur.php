<?php

$ntVar = false;
$ntStr = 'btn-outline-primary';

//echo 'ms-excel:ofe|u|https://d.docs.live.net/'.Config::DRIVE_DRIVE_ID.'/Crowe/'.$tklf_id.'/Risk/'.$p['name'];
// echo '<pre>';
// print_r($p);
// echo '</pre>';

/*
$('.sil_'+grup+'_'+kod+'_'+belgeId).addClass("disabled");
$('#upload_'+grup+'_'+kod+'_'+belgeId).addClass("disabled").addClass("btn-secondary");
*/
?>
<div class="modal fade" id="verioda" role="dialog">
	<div class="modal-dialog modal-xl">
		<div class="modal-content">
			<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 mb-12">
				<div class="card" style="min-height:395px;">
					<div class="card-block">
						<div id="dialogverioda"></div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="sonuclandir" role="dialog">
	<div class="modal-dialog modal-xl">
		<div class="modal-content">
			<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 mb-12">
				<div class="card" >
					<div class="card-block">
						<div id="asd">
            				<div class="card shadow mb-1 ">
                	            <div class="card-header">
                	            	<h6 class="m-0 font-weight-bold text-primary">Sonuçlandırma Ekranı</h6>
                	            </div>
            					<div class="row card-body">
            						<div class="col-xl-12">
                                        <div class="row mb-2">
                                    		<div class="col-lg-3 font-weight-bold text-gray-800 align-self-center  text-right">Yeterli ve Uygun Denetim Kanıtı Elde Edildi mi?</div>
                                    		<div class="col-lg-9">
                                        		<select class=" custom-select form-control " id="chk_kanit_<?=$p['id'] ?>" onchange="kanit(<?=$p['id'] ?>)">
                                        			<option value=""  >Seçiniz</option>
                                        			<option value="E" >Evet</option>
                                        			<option value="H" >Hayır</option>
                                    			</select>
                                    		</div>
                                    	</div>
                                    	
                                    	<div class="row mb-2">
                                        	<div class="col-lg-3 font-weight-bold text-gray-800 align-self-center  text-right">Bulgu Var mı?</div>
                                        	<div class="col-lg-3 text-center">
                                        		<select class=" custom-select form-control " id="chk_bulgu_<?=$p['id'] ?>" onchange="bulgu(<?=$p['id'] ?>)">
                                        			<option value=""  >Seçiniz</option>
                                        			<option value="E" >Evet</option>
                                        			<option value="H" >Hayır</option>
                                    			</select>
                                			</div>
                                			<div class="col-lg-2 font-weight-bold text-gray-800 align-self-center  text-right">Bulgunun Tutarı</div>
                                			<div class="col-lg-4">
                                				<input id="bulgu_tutar_<?=$p['id'] ?>" type="text" class="form-control form-control-user" value="">
                                			</div>
                            			</div>
                                        <div class="row mb-2">
                                        	<div class="col-lg-3 font-weight-bold text-gray-800 align-self-center  text-right">Açıklama :</div>
                                    		<div class="col-lg-9">
                                    			<textarea rows="3" id="aciklama_<?=$p['id'] ?>" class="form-control"></textarea>
                                			</div>
                                    	</div>
                                        <div class="row mb-2">
                                        	<div class="col-lg-3 font-weight-bold text-gray-800 align-self-center  text-right"></div>
                                    		<div class="col-lg-6">
                                    			<button type="button" class="btn btn-primary col-lg-12" onclick="sonuclandirKaydet(<?=$p['id'] ?>)" >Kaydet</button>
                                			</div>
                                    	</div>
                                    </div>
            					</div>
							</div>
						</div>
					</div>	
				</div>
			</div>
		</div>
	</div>
</div>

<div class="card-body" id = "<?='p_'.str_replace(".","",$prosedurKod).'_'.$p['rpKod']?>">
	<div class="border">
       	<div class="row py-3 ">
        	<div class="col justify-content-center" >
        		<a href="#" onclick="notDuzenle(<?="'".$prosedurKod."','".$p['rpKod']."'" ?>);" id="<?='pNot_'.str_replace(".","",$prosedurKod).'_'.$p['rpKod'] ?>" data-toggle="modal" data-target="#myModalRisk" class="mx-2 btn <?= $ntStr ?> float-right"> 
        			<i id="<?='pZil_'.str_replace(".","",$prosedurKod).'_'.$p['rpKod'] ?>" class="<?= $ntVar ? 'far fa-pulse ' : 'fas' ?> fa-bell " ></i>
        		</a>
        		<a href="#" onclick="veriodasiAc(<?=$p['rlid'].','.$p['rpid'] ?>);" data-toggle="modal" data-target="#verioda" class="mx-2 btn btn-outline-primary float-right"> 
        			Veri Odası
        		</a>
        		<a href="#" onclick="sonuclandirAc(<?=$p['id']?>);" data-toggle="modal" data-target="#sonuclandir" class="mx-2 btn btn-outline-dark float-right"> 
        			Sonuçlandır
        		</a>
        		<div class="h5 pl-5 font-weight-bold text-gray-800"><?= $p['rpKod'].') '.$p['rpAdi'] ?></div>
            </div>
  		</div>
        <div class="row">
            <div class="col-4">
                <div class="card shadow h-100">
                    <div class="card-header ">
                    	<h6 class="mt-2 font-weight-bold ">Referanslar</h6>
                    </div>
                    <div class="card-body">
                        <div class="row" id="refsList_<?=str_replace(".","",$prosedurKod)?>_<?=$p['rpKod'] ?>">
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-6">
            	<div class="card-header ">
                	<h6 class="mt-2 font-weight-bold ">Açıklama</h6>
                </div>
            	<div class="card shadow">
            		<div class="card-body">
            			<div class="row m-2">
                            <div class="col-10"><textarea rows="3" class="form-control" id="<?=str_replace(".","",$prosedurKod).'_'.$p['rpKod'].'_aciklama' ?>" ><?= $p['aciklama']?></textarea></div>
                        	<div class="col-2 text-center align-self-center ">
                    			<button type="button" class="btn btn-primary col-lg-12" onclick="riskFormKaydet(<?=$p['id'].",'".$prosedurKod."','".$p['rpKod']."'" ?>)" >Kaydet</button>
                    		</div>
                    	</div>
            		</div>
            	</div>
            </div>
            <div class="col-2">
            	<div class="card shadow h-100">
            		<div class="card-header ">
                    	<h6 class="mt-2 font-weight-bold ">Çalışma Kağıdı</h6>
                    </div>
            		<div class="card-body">
            			<div class="row m-2">
                        	<div class="col-6 text-center align-self-center">
                        		<a href="<?='ms-excel:ofe|u|https://hsyserberst-my.sharepoint.com/personal/hsy365_crowehsy_net/Documents/HSY365/'.$tklf_id.'/Risk/'.$p['name']?>" class="my-0 btn btn-primary"><i class="fas fa-desktop fa-2x"></i></a>
                    		</div>
                        	<div class="col-6 text-center align-self-center">
                    			<a href="<?=$p['web'] ?>"	class="btn btn-primary" target="_blank" ><i class="fas fa-external-link-alt fa-2x"></i></a>
                    		</div>
            			</div>
        			</div>
    			</div>
			</div>
        </div>
    </div>
</div>
<script type="text/javascript">
<?php 
    $arr = '';
    foreach ($p['belgeler'] as $list){
        $arr   = ($arr != '' ? $arr = $arr . ',' : '').$list['id']." : '".$list['sira']." - ".$list['adi']."'";
    }
    echo 'var belgeList = {'.$arr.'};';
    echo 'durumlar.set(\'refsDurum'.$key.$p['rpKod'].'\', '.$p['durum_id'].');';
?>
prosedurSonuc(<?="'".$prosedurKod."','".$p['rpKod']."'" ?>);

</script>
