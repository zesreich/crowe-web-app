<?php
$pId = 227;
include_once '../../../First.php';
include_once PREPATH.'config/mkConfig.php';
include_once PREPATH.'soa/planlama/planlamaSoa.php';
include_once PREPATH.'soa/driveSoa.php';
include_once PREPATH.'config/planRiskProsedurConfig.php';

$tklf_id = $_GET['id'];
include_once PREPATH . 'header.php';

$tklf   = Crud::getById(new Denetim(),$tklf_id) -> basit();
$plnm   = Crud::getSqlTek(new Planlama(), Planlama::GET_TEKLIF_ID, array('tklf_id'=>$tklf_id)) -> basit();
// echo '<pre>';
// print_r($plnm);
// echo '</pre>';
//$b70List= planlamaSoa::getB70List($tklf_id);

$pros    = planlamaSoa::prosedurlerHepsi($tklf_id);

$prosedurAcks = planlamaSoa::planListesiGetirHepsi();

$link = 'pages/is/planlama/planlama.php?id='.$tklf_id;

$cmbProKap  = mkConfig::PROSEDUR_KAPSAM_MADDELERI;
$cmbProZam  = mkConfig::PROSEDUR_ZAMAN_MADDELERI;
$cmbProSnc  = mkConfig::PROSEDUR_SONUC_MADDELERI;

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
	var ele = $("#"+kod.replace(".", "")+"_"+id+"_sonuc");
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
            	<h6 class="m-0 font-weight-bold text-gray-300">Planlama</h6>
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
                     	Hazırlayan</br>
                     	Kontrol Eden</br>
                     	Onaylayan
                    </div>
                    
          		</div>
            
            <div class="card-body">
            	<nav >
                  <div class="nav nav-tabs "	id="nav-tab" role="tablist">
                    <a class="nav-item nav-link mk mr-1 text-center active" data-toggle="tab" id="bkp_btn"  role="tab" href="#nav-bkp" >KAPAK</a>
                    <a class="nav-item nav-link mk mr-1 text-center" 		data-toggle="tab" id="b01_btn"	role="tab" href="#nav-b01" onclick="disB01(0)">B01</a>
                    <a class="nav-item nav-link mk mr-1 text-center" 		data-toggle="tab" id="b20_btn"	role="tab" href="#nav-b20" onclick="prosedurYukle('<?=mkConfig::B20?>')">B20</a>
                    <a class="nav-item nav-link mk mr-1 text-center" 		data-toggle="tab" id="b30_btn"	role="tab" href="#nav-b30" >B30</a>
                    <a class="nav-item nav-link mk mr-1 text-center" 		data-toggle="tab" id="b40_btn"	role="tab" href="#nav-b40" >B40</a>
                    <a class="nav-item nav-link mk mr-1 text-center" 		data-toggle="tab" id="b50_btn"	role="tab" href="#nav-b50" >B50</a>
                    <a class="nav-item nav-link mk mr-1 text-center" 		data-toggle="tab" id="b55_btn"	role="tab" href="#nav-b55" >B55</a>
                    <a class="nav-item nav-link mk mr-1 text-center" 		data-toggle="tab" id="b60_btn"	role="tab" href="#nav-b60" >B60</a>
                    <a class="nav-item nav-link mk mr-1 text-center" 		data-toggle="tab" id="b70_btn"	role="tab" href="#nav-b70" >B70</a>
                    <a class="nav-item nav-link mk mr-1 text-center" 		data-toggle="tab" id="b71_btn"	role="tab" href="#nav-b71" >B71</a>
                    <a class="nav-item nav-link mk mr-1 text-center" 		data-toggle="tab" id="b72_btn"	role="tab" href="#nav-b72" >B72</a>
                    <a class="nav-item nav-link mk mr-1 text-center" 		data-toggle="tab" id="b80_btn"	role="tab" href="#nav-b80" onclick="b80Init(<?=$tklf_id ?>)">B80</a>
                    <a class="nav-item nav-link mk mr-1 text-center" 		data-toggle="tab" id="b99_btn"	role="tab" href="#nav-b99" >B99</a>
                  </div>
                </nav>
                <div class="border">
                    <div class="tab-content m-3" id="nav-tabContent">
                    	<div class="tab-pane fade show active"	id="nav-bkp" 	role="tabpanel" ></div>
                      	<div class="tab-pane fade" 				id="nav-b01" 	role="tabpanel" ><?php include 'b01.php';?></div>
                      	<div class="tab-pane fade" 				id="nav-b20" 	role="tabpanel" ><?php include 'b20.php';?></div>
                      	<div class="tab-pane fade" 				id="nav-b30" 	role="tabpanel" ></div>
                      	<div class="tab-pane fade" 				id="nav-b40" 	role="tabpanel" ><?php include 'b40.php';?></div>
                      	<div class="tab-pane fade" 				id="nav-b50" 	role="tabpanel" ></div>
                      	<div class="tab-pane fade" 				id="nav-b55" 	role="tabpanel" ><?php include 'b55.php';?></div>
                      	<div class="tab-pane fade" 				id="nav-b60" 	role="tabpanel" ></div>
                      	<div class="tab-pane fade" 				id="nav-b70" 	role="tabpanel" ><?php include 'b70.php';?></div>
                      	<div class="tab-pane fade" 				id="nav-b71" 	role="tabpanel" ></div>
                      	<div class="tab-pane fade" 				id="nav-b72" 	role="tabpanel" > deneme	</div>
                      	<div class="tab-pane fade" 				id="nav-b80" 	role="tabpanel" ><?php include 'b80.php';?></div>
                      	<div class="tab-pane fade" 				id="nav-b99" 	role="tabpanel" ></div>
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

<script type="text/javascript">
<?php 
    $listlist = array();
    foreach (mkConfig::B55_01_LIST as $b20)
        array_push($listlist, $b20);
    
    foreach (mkConfig::B55_02_LIST as $b20)
        array_push($listlist, $b20);
    
    foreach (mkConfig::B40_LIST as $b20)
        array_push($listlist, $b20);
    
    foreach (mkConfig::B20_LIST as $b20)
        array_push($listlist, $b20);

    foreach (mkConfig::B01_LIST as $b20)
        array_push($listlist, $b20);
        
	$arrPr  = '';
    $arr       = '';
    $arrPA     = '';
    foreach ($listlist as $mkL){
        if (isset($pros[$mkL[0]])){
	        $ar    = '';
	        $arP   = '';
	        foreach ($pros[$mkL[0]] as $pjs){
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
</script>

<?php include (PREPATH.'front/js/prosedur.js.php'); ?>
<?php include (PREPATH.'footer.php'); ?>