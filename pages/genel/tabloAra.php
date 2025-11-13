<?php 
include_once '../../db/Crud.php';
include_once '../../soa/yetkiSoa.php';
include_once '../../config/yetkiConfig.php';
$tbl = $_GET['tablo'];

$sql = $tbl::tabloAra['sql'];
if (isset($_GET['sql'])){
    $sql = $_GET['sql'];
    $sql = $tbl::$sql();
}

$alan   = $tbl::tabloAra['alan'];
$donen  = $tbl::tabloAra['donen'];
$list = Crud::getSqlCokTblsiz(new $tbl(), $sql, array());

?>

<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 mb-12">
	<br>
	<input id="araSearch" type="text" class="form-control form-control-user"  placeholder="Arama">
	<br>
	<table id="araTablebot" class="table table-hover table-striped" >
		<thead>
			<tr>
				<?php 
				    foreach ($alan['adi'] as $ad){
				        echo '<th class="bg-gray-700 text-gray-200 text-center align-middle">'.$ad.'</th>';
				    }
				?>
				<th class="bg-gray-700 text-gray-200 text-center align-middle">Seç</th>
			</tr>
		</thead>
		<tbody>
    			<?php 
    			if ($list!= null){
    			    foreach ($list as $gln){
                    	echo '<tr class="listeEleman" >';
    			        foreach ($alan['db'] as $db){
    			            echo '<td class="text-center align-middle">'.$gln[$db].'</td>';
    			        }
                    ?>
            		<td class="text-center align-middle">
                        <button type="button" class="btn btn-outline-primary" data-dismiss="modal" onclick="miniSetle(
                        <?php  
                            echo '\'';
                            foreach ($donen as $dn){
                                echo $gln[$dn].',';
                            }
                            echo '\''; 
                        ?>
                        )">SEÇ</button>
            		</td>
            	</tr>
                <?php
    			}
			}
			?>
		</tbody>
	</table>
</div>
<script>
	araTableArama("#araTablebot","#araSearch");
	
    function miniSetle(data){
		var res = data.split(",");
		var arr = [<?php echo '"'.implode('","', $donen).'"' ?>];
		for (var i = 0; i < arr.length; i++) {
            $('#dznl_<?= strtolower($tbl).'_'?>'+ arr[i]).val(res[i]);
		}
		
		var fnk = "miniAraDonen"
		if(eval("typeof " + fnk) == 'function'){
    		miniAraDonen();
		}
    }

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