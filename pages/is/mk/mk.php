<?php
$pId = 204;
include_once '../../../First.php';
include_once PREPATH.'config/mkConfig.php';
include_once PREPATH.'soa/mkSoa.php';
include_once PREPATH.'soa/driveSoa.php';

$tklf_id = $_GET['id'];
$link = 'pages/is/mk/mk.php?id='.$tklf_id;
if (!mkSoa::mkDenetcisiMisin($tklf_id, $_SESSION['login']['id']) && !yetkiSoa::yetkiVarmi(yetkiConfig::MK_SAYFA_GORUNTULE)){
    hata('Bu müşteri kabul denetçisi değilsiniz.',PREPATH);
}
driveSoa::baglan($link);
include_once PREPATH . 'header.php';
//echo Config::DRIVE_CLIENT_ID;

$tklf   = Crud::getById(new Denetim(),$tklf_id) -> basit();

$mk     = Crud::getSqlTek(new MusteriKabul(), MusteriKabul::GET_TEKLIF_ID, array('tklf_id'=>$tklf_id));
$mk0    = Crud::getSqlTek(new MK0(), MK0::GET_TEKLIF_ID, array('tklf_id'=>$tklf_id));
$mk2    = Crud::getSqlTek(new MK2(), MK2::GET_TEKLIF_ID, array('tklf_id'=>$tklf_id));

$cmbProKap  = mkConfig::PROSEDUR_KAPSAM_MADDELERI;
$cmbProZam  = mkConfig::PROSEDUR_ZAMAN_MADDELERI;
$cmbProSnc  = mkConfig::PROSEDUR_SONUC_MADDELERI;

$eskiIsler      = mkSoa::eskifirmaGunluk($tklf['musteri_id']['id'], $tklf_id);
$eskiDenetci    = mkSoa::eskiDenetciListMstr($tklf_id, $eskiIsler);

$prosedurAcks   = mkSoa::mkListesiGetirHepsi();
$prosedurs      = mkSoa::prosedurlerHepsi($tklf_id);
$notCheckler    = mkSoa::notlarKontrol($tklf_id);

// $uyarilar       = mkSoa::mkKapakUyarilarTklfId($tklf_id);
// echo '<pre>';
// print_r($uyarilar['MK4']);
// echo '</pre>';


?>
<style>
.nav-item.nav-link.mk {
    background: #385FCF;
    color: white;
}
.nav-item.nav-link.mk.active {
    background: #FFFFFF;
    color: #535353;
    font-weight: 1000;
}
.tab-pane.fade{
    height: 100%;
}
</style>
<script type="text/javascript">

function prosedurSonuc(kod, id){
	var ele = $("#"+kod+"_"+id+"_sonuc");
	if (ele.val() == '<?= mkConfig::PROSEDUR_SONUC_YOK?>') {
		ele.addClass( "bg-success text-white" );
		ele.removeClass("bg-danger bg-warning");
	}else if (ele.val() == '<?= mkConfig::PROSEDUR_SONUC_NORMAL?>') {
		ele.addClass( "bg-warning text-white" );
		ele.removeClass("bg-danger bg-success");
	}else if (ele.val() == '<?= mkConfig::PROSEDUR_SONUC_CIDDI?>') {
		ele.addClass( "bg-danger text-white" );
		ele.removeClass("bg-success bg-warning");
	}
}

</script>
<div class="row">
	<div class="col-lg-12 col-xl-12">
        <div class="card shadow" >
        	<div class="card-header bg-gradient-primary py-3">
            	<h6 class="m-0 font-weight-bold text-gray-300">Müşteri Kabul</h6>
            </div>
            
            	<div class="row  py-4 text-center">
                	<div class="col justify-content-center">
                		<h5>Şirket Ünvan</h5>
                  		<?=$tklf['musteri_id']['unvan'] ?>
                    </div>
                    <div class="col">
                     	<h5>Denetim Dönemi</h5>
                  		<?=BaseSoa::strDateToStr($tklf['donem_bas_trh']).'</br>' ?>
                  		<?=BaseSoa::strDateToStr($tklf['donem_bts_trh']) ?>
                    </div>
                    <div class="col text-left">
                     	Hazırlayan &nbsp;&nbsp;&nbsp;&nbsp;:&nbsp; <?=isset($dntcs[mkConfig::POZISYON_HAZIRLAYAN[1]]) ? $dntcs[mkConfig::POZISYON_HAZIRLAYAN[1]] : '' ?></br>
                     	Kontrol Eden :&nbsp; <?=isset($dntcs[mkConfig::POZISYON_KONTROL[1]]) ? $dntcs[mkConfig::POZISYON_KONTROL[1]] : '' ?> </br>
                     	Onaylayan &nbsp;&nbsp;&nbsp;&nbsp;:&nbsp; <?=isset($dntcs[mkConfig::POZISYON_ONAYLAYAN[1]]) ? $dntcs[mkConfig::POZISYON_ONAYLAYAN[1]] : '' ?>
                    </div>
                    
          		</div>
            
            <div class="card-body">
            	<nav >
                  <div class="nav nav-tabs "	id="nav-tab" role="tablist">
                    <a class="nav-item nav-link mk mr-1 text-center active" data-toggle="tab" href="#nav-mkh"  id="mkh_btn" role="tab" href="#mkh" onclick="uyariDuzenle()">KAPAK</a>
                    <?php if (KullaniciTurPrm::ISORTAGI != $_SESSION['login']['tur'] && KullaniciTurPrm::MUSTERI != $_SESSION['login']['tur']) {?>
                    <a class="nav-item nav-link mk mr-1 text-center" 		data-toggle="tab" href="#nav-mk0"  id="mk0_btn"	role="tab" href="#mk0" >MK0</a>
                    <a class="nav-item nav-link mk mr-1 text-center" 		data-toggle="tab" href="#nav-mk1"  id="mk1_btn"	role="tab" href="#mk1" onclick="prosedurYukle('<?=mkConfig::MK1 ?>')">MK1</a>
                    <a class="nav-item nav-link mk mr-1 text-center" 		data-toggle="tab" href="#nav-mk2"  id="mk2_btn"	role="tab" href="#mk2" onclick="prosedurYukle('<?=mkConfig::MK2 ?>')">MK2</a>
                    <a class="nav-item nav-link mk mr-1 text-center" 		data-toggle="tab" href="#nav-mk3"  id="mk3_btn"	role="tab" href="#mk3" onclick="prosedurYukle('<?=mkConfig::MK3 ?>')">MK3</a>
                    <a class="nav-item nav-link mk mr-1 text-center" 		data-toggle="tab" href="#nav-mk4"  id="mk4_btn"	role="tab" href="#mk4" onclick="beyanVeDenetciYukle()">MK4</a>
                    <a class="nav-item nav-link mk mr-1 text-center" 		data-toggle="tab" href="#nav-mk5"  id="mk5_btn"	role="tab" href="#mk5" onclick="prosedurYukle('<?=mkConfig::MK5 ?>')">MK5</a>
                    <a class="nav-item nav-link mk mr-1 text-center" 		data-toggle="tab" href="#nav-mk6"  id="mk6_btn"	role="tab" href="#mk6" onclick="prosedurTureGore()">MK6</a>
                    <?php }?>
                  </div>
                </nav>
                <div class="border">
                    <div class="tab-content m-3" id="nav-tabContent">
                    	<div class="tab-pane fade show active"	id="nav-mkh" 	role="tabpanel" ><?php include 'mk_d_kapak.php';?></div>
                    	<?php if (KullaniciTurPrm::ISORTAGI != $_SESSION['login']['tur'] && KullaniciTurPrm::MUSTERI != $_SESSION['login']['tur']) {?>
                      	<div class="tab-pane fade" 				id="nav-mk0" 	role="tabpanel" ><?php include 'mk_d_0.php';?></div>
                      	<div class="tab-pane fade" 				id="nav-mk1" 	role="tabpanel" ><?php include 'mk_d_1.php';?></div>
                      	<div class="tab-pane fade" 				id="nav-mk2" 	role="tabpanel" ><?php include 'mk_d_2.php';?></div>
                      	<div class="tab-pane fade" 				id="nav-mk3" 	role="tabpanel" ><?php include 'mk_d_3.php';?></div>
                      	<div class="tab-pane fade" 				id="nav-mk4" 	role="tabpanel" ><?php include 'mk_d_4.php';?></div>
                      	<div class="tab-pane fade" 				id="nav-mk5" 	role="tabpanel" ><?php include 'mk_d_5.php';?></div>
                      	<div class="tab-pane fade" 				id="nav-mk6" 	role="tabpanel" ><?php include 'mk_d_6.php';?></div>
                      	<?php }?>
                    </div>
                </div>
            </div>
    	</div>
    </div>
</div>

<div class="modal fade" id="myModalRisk" role="dialog">
	<div class="modal-dialog modal-xl">
		<div class="modal-content">
			<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 mb-12">
				<div class="card" style="min-height:395px;">
					<div class="card-block">
						<div id="txtHintRisk"></div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<script >


	function detayAc(id){
	    $.get( "<?php echo PREPATH.'post/genelPost.php?tur=getById&tablo=denetim&id='?>"+id, function(data, status){
			if(status == "success"){
			    var objx = JSON.parse(data);
			    var obj = JSON.parse(objx.icerik);
			    $('#dznl_id2').				text(obj.id);
			    $('#dznl_musteri_id2').		text(obj.musteri_id.id+' - '+obj.musteri_id.unvan);
			    $('#dznl_email2').			text(obj.email);
			    $('#dznl_teklif_tarihi2').	text(formatTarih(obj.teklif_tarihi));
			    $('#dznl_donem_bas_trh2').	text(formatTarih(obj.donem_bas_trh) +'  -  '+formatTarih(obj.donem_bts_trh));
			    $('#dznl_frc_id2').			text(obj.frc_id.adi);
			    $('#dznl_raporsekli_id2').	text(obj.raporsekli_id.adi);
			    $('#dznl_duzenkurum_id2').	text(obj.duzenkurum_id.adi);
			    $('#dznl_tutar2').			text(formatPara(obj.tutar));
			    $('#dznl_para_birimi_id2').	text(obj.para_birimi_id.adi);
			    $('#dznl_dil_id2').			text(obj.dil_id.adi);
			    $('#dznl_dton_id2').		text(obj.dton_id.aciklama);
			    $('#dznl_yonay_trh2').		text(formatTarih(obj.yonay_trh));
			    $('#dznl_bilgi').			text(obj.bilgi);
			}else if(status == "error"){
			    hataAc("Bilgi çekilemedi.");
		    }
		});
	}
	
	function selectDuzenle(){
		$('.dznl_val').remove('[selected="selected"]');
	}

	detayAc(<?=isset($_GET['id']) ? $_GET['id'] : null;?>);

///////PROSEDÜRLER///////////////////

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
	    		    hataAc("Bir sorun oluÅŸtu.");
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
    
    function prosedurFormKaydet(vid,mkId,kod){
    	if ($('#'+mkId+'_'+vid+'_sonuc').val() != '<?=mkConfig::PROSEDUR_SONUC_CIDDI?>' || $("."+mkId+"-"+kod.replace(".", "\\.")).length != 0 ){
        	$.post("<?=PREPATH.'post/mk1Post.php?tur=form&islem=update' ?>",
                {
            		id 			: vid,
    				kapsami 	: $('#'+mkId+'_'+vid+'_kapsam').val(),
    				zamani		: $('#'+mkId+'_'+vid+'_zaman').val(),
    				sonuc		: $('#'+mkId+'_'+vid+'_sonuc').val(),
            		aciklama	: $('#'+mkId+'_'+vid+'_aciklama').val(),
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
	
	<?php 
	$arrPr  = '';
    $arr       = '';
    $arrPA     = '';
	foreach (mkConfig::MK_LIST as $mkL){
	    if (isset($prosedurs[$mkL[0]])){
	        $ar    = '';
	        $arP   = '';
	        foreach ($prosedurs[$mkL[0]] as $pjs){
	            if ($pjs->drive_id->deger != null){
    	            $ar = ($ar != '' ? $ar = $ar .',' : ''  ) ."[". $pjs->id->deger.",'".$pjs->drive_id->deger."']" ;
	            }
	            $arP = ($arP != '' ? $arP = $arP .',' : '')   ."[".$pjs->id->deger.",'".$pjs->kod->deger."']";
	        }
	        
	        $arrPr = ($arrPr   != '' ? $arrPr  = $arrPr. ',' : '')."'".$mkL[0]."': [".$arP."]";
	        $arr   = ($arr     != '' ? $arr    = $arr  . ',' : '')."'".$mkL[0]."': [".$ar."]";
	        $arrPA = ($arrPA   != '' ? $arrPA  = $arrPA. ',' : '')."'".$mkL[0]."':'".$mkL[1]."'";
	    }
	}
	
	echo 'var aList = {'.$arrPA.'};';
	echo 'var pList = {'.$arrPr.'};';
    echo 'var lst = {'.$arr.'};';
	?>


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
							var btn = $('#pNot_'+grup+'_'+l.replace(".", "\\.")); 
							var zil = $('#pZil_'+grup+'_'+l.replace(".", "\\."));
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
							
							var str = '#riskList_'+grup+'_'+pList[grup][i][1].replace(".", "\\.");
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
	    		    	console.log(snc);
						for(var i in pList[grup]){
							var str = '#refsList_'+grup+'_'+pList[grup][i][1].replace(".", "\\.");
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
    			                "	<a href='#' onclick=\"refsEkle("+pList[grup][i][0]+",'"+grup+"','"+pList[grup][i][1]+"');\" data-toggle='modal' data-target='#myModalRisk' class='btn btn-primary '><i class='fas fa-plus'></i></a>"+
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

    function prosedurRefsEkle(grup,kod,item){
        var str = '#refsList_'+grup+'_'+kod.replace(".", "\\.");
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
        var str = '#riskList_'+grup+'_'+kod.replace(".", "\\.");
    	var table = $(str);
    	if (item != null) {
        	table.append(
    			'<div class="col-2 border text-center bg-danger text-white '+grup+'-'+kod+'" id = "risk_'+item.id+'">'+
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
    
	function prosedurDosyaYukle(grup,kod,drvId){
		var table = $('#refsList_'+grup+'_'+kod.replace(".", "\\.")+'_da');
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

	window.onload = function (event) {
	    window.location.hash = "#my-new-hash";
	};

///////PROSEDÜRLER///////////////////
</script>
<?php include (PREPATH.'footer.php'); ?>