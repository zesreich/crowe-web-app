<?php
$pId = 143;
include_once '../../First.php';
include_once PREPATH . 'header.php';

$tbl    = new IsOrtagi();
$gelen  = Crud::all($tbl);

$yni    = Crud::getById(new Program() , 139 ) -> basit();
$mstr   = Crud::getById(new Program() , 153 ) -> basit();

?>

<div class="row">
    <div class="col-lg-12 col-xl-12 pb-3">
        <div class="card shadow">
            <div class="card-header bg-gradient-primary py-3">
            	<h6 class="m-0 font-weight-bold text-gray-300">İŞ ORTAĞI LİSTESİ</h6>
            </div>
            <div class="card-body">
            	<div class="table-responsive">
            		<div class="container col-12">
                		<div class="row">
                    		<div class="col-10 mb-2" >
                    			<input id="search" type="text" class="form-control form-control-user "  placeholder="Arama">
                    		</div>
                    		<div class="col-2 mb-2">
                              	<a href="<?=PREPATH.$yni['program_link'] ?>" id="dznl_sil" class="btn btn-success col-lg-12" >
                              		<i class="fa fa-plus"></i><span class="text"> Yeni İş Ortağı Ekle/Düzenle</span>
                          		</a>
                    		</div>
                    		<div  class="col-12">
                        		<table id="tablebot" class="table table-bordered table-striped" >
                        			<thead>
                        				<tr>
                        					<th class="bg-gray-700 text-gray-200 text-center align-middle">Id</th>
                        					<th class="bg-gray-700 text-gray-200 text-center align-middle">Unvan</th>
                        					<th class="bg-gray-700 text-gray-200 text-center align-middle">Vergi Dairesi</th>
                        					<th class="bg-gray-700 text-gray-200 text-center align-middle">Vergi No</th>
                        					<th class="bg-gray-700 text-gray-200 text-center align-middle">Seç</th>
                        				</tr>
                        			</thead>
                        			<tbody>
                            			<?php 
                            			if ($gelen!= null){
                                			foreach ($gelen as $gln){
                                            ?>
                                        	<tr class="listeEleman" onclick="detayAc('<?= $gln->id ?>')">
                                        		<td class="text-center align-middle" id="list_id"><?= $gln->id ?> </td>
                                        		<td class="text-center align-middle"><?= $gln->unvan ?> </td>
                                        		<td class="text-center align-middle"><?= ($gln->vergi_daire_id->deger != null ? $gln->vergi_daire_id->ref->deger->adi : '' )?> </td>
                                        		<td class="text-center align-middle"><?= $gln->vergi_no ?> </td>
                                        		<td class="text-center align-middle">
                                                	<a href="<?=PREPATH.$mstr['program_link'].'?id='. $gln->id ?>"  class="btn btn-warning col-lg-8" >
                                                  		<i class="fa fa-hand-pointer-o"></i><span class="text"> SEÇ</span>
                                              		</a>
                                        		</td>
                                        	</tr>
                                            <?php
                                			}
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



<script >
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
</script>
<?php include (PREPATH.'footer.php'); ?>