<?php 
include_once '../../db/Crud.php';
include_once '../../soa/yetkiSoa.php';
include_once '../../config/yetkiConfig.php';

$list = Crud::getSqlCokTblsiz(new RiskListesi(), RiskListesi::GET_BY_AKTIF, array());
?>
<style>
th.rotate {
  height:140px;
  white-space: nowrap;
  position:relative;
}

th.rotate > div {
  transform: rotate(270deg);
  position:absolute;
  left:0;
  right:0;
  top: 110px;
  margin:auto;
  
}
</style>


<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 mb-12">
	<br>
	<input id="araSearch" type="text" class="form-control form-control-user"  placeholder="Arama">
	<br>
	<table id="araTablebot" class="table table-bordered" >
		<thead>
			<tr>
				<th style="width: 10%" class="bg-gray-700 text-gray-200 text-center align-middle">ID&nbsp;&nbsp;<i id="i1" class="srt fas fa-sort" aria-hidden="true"></i></th>
				<th style="width: 10%" class="bg-gray-700 text-gray-200 text-center align-middle">KOD&nbsp;<i id="i2" class="srt fas fa-sort" aria-hidden="true"></i></th>
				<th style="width: 44%" class="bg-gray-700 text-gray-200 text-center align-middle">ADI&nbsp;<i id="i3" class="srt fas fa-sort" aria-hidden="true"></i></th>
				<th style="width: 10%" class="bg-gray-700 text-gray-200 text-center align-middle">Seç</th>
			</tr>
		</thead>
		<tbody>
			<?php 
    			if ($list!= null){
    			    foreach ($list as $gln){
                    	echo '<tr class="listeEleman" >';
			            echo '<td class="text-center align-middle">'.$gln['id'].'</td>';
			            echo '<td class="text-center align-middle">'.$gln['kod'].'</td>';
			            echo '<td class="text-center align-middle">'.$gln['adi'].'</td>';
			            echo '<td class="text-center align-middle"><button type="button" class="btn btn-outline-primary" data-dismiss="modal" onclick="riskAppend('.$gln['id'].','.$_GET['pId'].',\''.$_GET['grup'].'\',\''.$_GET['kod'].'\')">SEÇ</button></td>';
    			        echo '</tr>';
    			    }
    			}
            ?>
		</tbody>
	</table>
</div>
<script>

    tableSirala("#araTablebot");
    function tableSirala(tbl){
    	$(".srt").each(function (dt) {
    		$(this).click(function () {
    			var id =$(this).attr('id');
        		if ($(this).is('.fa-sort-down')) {
            		$(this).removeClass('fa-sort-down');
                    $(this).addClass('fa-sort-up');
                    sortOrder = -1;
                } else {
                    $(this).removeClass('fa-sort');
                    $(this).removeClass('fa-sort-up');
                    $(this).addClass('fa-sort-down');
                    sortOrder = 1;
                }
    
    			$(".srt").each(function (dt) {
        			if ($(this).attr('id') != id) {
                    $(this).removeClass('fa-sort-down');
                    $(this).removeClass('fa-sort-up');
                    $(this).addClass('fa-sort');
        			}
    			});
    
    			var arrData = $(tbl).find('tbody >tr:has(td)').get();
                arrData.sort(function (a, b) {
                    var val1 = $(a).children('td').text().toUpperCase();
                    var val2 = $(b).children('td').text().toUpperCase();
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

	araTableArama("#araTablebot","#araSearch");
    function araTableArama(tbl, edt){
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