<?php 
$pId = 242;
include_once '../../First.php';
include_once PREPATH . 'soa/driveSoa.php';
include_once PREPATH . 'soa/mkSoa.php';
include_once PREPATH . 'config/mkConfig.php';

$buLink = 'pages/is/prosedurDosya.php?tklf_id='.$_GET['tklf_id'].'&grup='.$_GET['grup'].'&kod='.$_GET['kod'].'&drive_id='.$_GET['drive_id'].'&p_id='.$_GET['p_id'].(isset($_GET['riskId']) ? '&riskId='.$_GET['riskId'] : '' );
$client = driveSoa::baglan($buLink);
include_once PREPATH . 'header.php';

$program = Crud::getById(new Program() , $_GET['p_id'] ) -> basit();
if (isset($_GET['riskId'])){
    if (!isset($_GET['drive_id']) || $_GET['drive_id'] == ''){
        $psdr = Crud::getById(new PlanRiskProsedur(),$_GET['riskId']);
        if ($psdr->drive_id->deger != null){
            $driveId = $psdr->drive_id->deger;
        }else{
            $driveId =  driveSoa::riskDosyaOlustur($client,$_GET['riskId'],$_GET['tklf_id'], $_GET['grup'],$_GET['kod']);
        }
        $buLink = 'pages/is/prosedurDosya.php?tklf_id='.$_GET['tklf_id'].'&grup='.$_GET['grup'].'&kod='.$_GET['kod'].'&drive_id='.$driveId.'&p_id='.$_GET['p_id'].(isset($_GET['riskId']) ? '&riskId='.$_GET['riskId'] : '' );
    }else{
        $driveId = $_GET['drive_id'];
    }
}else{
    if (!isset($_GET['drive_id']) || $_GET['drive_id'] == ''){
        $psdr = Crud::getSqlTek(new Prosedur(), Prosedur::GET_TEKLIF_BY_GRUP_KOD, array('tklf_id'=>$_GET['tklf_id'],'grup'=>$_GET['grup'],'kod'=>$_GET['kod']));
        if ($psdr->drive_id->deger != null){
            $driveId = $psdr->drive_id->deger;
        }else{
            $driveId =  driveSoa::prosedurDosyaOlustur($client,$_GET['tklf_id'], $_GET['grup'],$_GET['kod']);
        }
        $buLink = 'pages/is/prosedurDosya.php?tklf_id='.$_GET['tklf_id'].'&grup='.$_GET['grup'].'&kod='.$_GET['kod'].'&drive_id='.$driveId.'&p_id='.$_GET['p_id'].(isset($_GET['riskId']) ? '&riskId='.$_GET['riskId'] : '' );
    }else{
        $driveId = $_GET['drive_id'];
    }
}

$list = driveSoa::dosyaListesi($client, $driveId);
?>
<div class="row" id="drive-box">
    <div class="col-lg-12 col-xl-12 pb-3">
        <div class="card shadow">
            <div class="card-header bg-gradient-primary py-3">
            	<a href="<?=PREPATH.$program['program_link'].'?id='.$_GET['tklf_id']?>"class="btn btn-primary float-right" ><i class="fas fa-arrow-circle-left"></i></a>
            	<h6 class="m-0 font-weight-bold text-gray-300"><?='Dosyalar ('.$_GET['tklf_id'].' - '.$_GET['grup'].'.'.$_GET['kod'].')' ?></h6>
            </div>
            <div class="card-body">
            	<div class="table-responsive">
            		<div class="container col-12">
                		<div class="row">
                    		<div class="col-10 mb-2" >
                    			<input id="search" type="text" class="form-control form-control-user "  placeholder="Arama">
                    		</div>
                          	<div id="button-upload" class="col-2 mb-2">                          	
                          		<form enctype="multipart/form-data" action="<?= PREPATH.'post/drivePost.php?tur=drive_post' ?>" method="POST">
                                    <input name="dosya[]" type="file" id = "fUpload" multiple hidden/>
                                    <input type="hidden" name="link" value="<?=$buLink ?>">
                                    <input type="hidden" name="driveId" value="<?=$driveId?>">
                                    <input type="submit" value="Submit" id = "fsubmit" hidden>
                                	<a href="#"  class="btn btn-success col-12" id = "button-upload" >
                                		<i class="fa fa-share"></i>
                                		<span id='upload-percentage' class="text">Dosya yükle</span>
                                    </a>
                                </form>
                            </div>
                    		<div  class="col-12">
                        		<table id="tablebot" class="table table-bordered table-striped" >
                        			<thead>
                        				<tr>
                        					<th class="bg-gray-700 text-gray-200 text-center align-middle">Dosya adı</th>
                        					<th class="bg-gray-700 text-gray-200 text-center align-middle">Son değişiklik Yapan</th>
                        					<th class="bg-gray-700 text-gray-200 text-center align-middle">İndir</th>
                        					<th class="bg-gray-700 text-gray-200 text-center align-middle">Aç</th>
                        					<th class="bg-gray-700 text-gray-200 text-center align-middle">Sil</th>
                        				</tr>
                        			</thead>
                        			<tbody id="tableLst">
                        			<?php 
                        			foreach ($list as $one){
                    				    echo '<tr class="listeEleman">';
                    				    echo '<td class="text-center align-middle" >'.$one->name.'</td>';
                    				    echo '<td class="text-center align-middle" >'.$one->lastModifiedBy->user->displayName.'</td>';
                    				    echo '<td class="text-center align-middle" ><a href="'.$one->url.'" class="btn btn-success col-lg-12" ><i class="fas fa-cloud-download-alt"></i></a></td>';
                                		echo '<td class="text-center align-middle" ><a href="'.$one->webUrl.'" class="btn btn-warning col-lg-12" target="_blank" ><i class="fas fa-external-link-alt"></i></i></a></td>';
                                		echo '<td class="text-center align-middle" ><a href="#" onclick="dosyaSil(\''.$one->id.'\')" class="btn btn-danger col-lg-12" ><i class="fas fa-times"></i></input></td>';
                            			echo '</tr>';
                    				}
                    				?>
                        			</tbody>
                        		</table>
                    		</div>
                		</div>
            		</div>
            	</div>
            </div>
        </div>
    </div>
</div>

<div class="modal hide" id="pleaseWaitDialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-body h-100 d-flex align-items-center">
        <i class='fas fa-sync fa-spin fa-3x mx-auto' style="color: #F8E6E0;"></i>
        
    </div>
</div>
<script type="text/javascript">
tableSirala("#tablebot");
tableArama("#tablebot","#search");

//Tablo sırala fonksiyonu
function tableSirala(tbl){ 
    $(tbl+' th').each(function (col) {
        $(this).click(function () {
    		$(tbl+" th i").remove();
            if ($(this).is('.asc')) {
                $(this).removeClass('asc');
                $(this).addClass('desc');
                $(this).append('<i class="fa fa-sort-desc" aria-hidden="true"/>');
                sortOrder = -1;
            } else {
                $(this).addClass('asc');
                $(this).removeClass('desc');
                $(this).append('<i class="fa fa-sort-asc" aria-hidden="true"/>');
                sortOrder = 1;
            }
            $(this).siblings().removeClass('asc');
            $(this).siblings().removeClass('desc');
            var arrData = $(tbl).find('tbody >tr:has(td)').get();
            arrData.sort(function (a, b) {
                var val1 = $(a).children('td').eq(col).text().toUpperCase();
                var val2 = $(b).children('td').eq(col).text().toUpperCase();
                if ($.isNumeric(val1) && $.isNumeric(val2))
                    return sortOrder == 1 ? val1 - val2 : val2 - val1;
                else
                    return (val1 < val2) ? -sortOrder : (val1 > val2) ? sortOrder : 0;
            });
            $.each(arrData, function (index, row) {
                $(tbl+' tbody').append(row);
            });
        });
    });
}

//List arama fonksiyonu
function tableArama(tbl, edt){
	$(edt).on("keyup",function(){
		var value = $(this).val().toLowerCase();
		$(tbl+" tbody tr").filter(function(){
			if ($(this).text().toLowerCase().indexOf(value)>-1) {
			    $(this).toggle(true);    
			}else{
			    $(this).toggle(false);
			}
			
		});
	});
}

    $("#button-upload").click(function () {
        $("#fUpload").click();
    });
    
    $("#fUpload").bind("change", function () {
        $("#fsubmit").click();
    });


    function dosyaSil(driveId){
        var c = confirm("Silmek istediğinize emin misiniz?");
        if (c) {
        	$.post("<?=PREPATH.'post/drivePost.php?tur=delete' ?>",{
        		link 	: <?="'".$buLink."'" ?>,
        		driveId	: driveId
                },function(data,status){
    	    		if(status == "success"){
    	    			location.reload()
    	    		}else if(status == "error"){
    	    		    hataAc("Bir sorun oluştu.");
    	    	    }
        	    }
            );
        }
    }
</script>
<?php include (PREPATH.'footer.php'); ?>