<?php 
foreach (mkConfig::B55_02_LIST as $pln){
    $prosedurKod = $pln[0];
    $prosedurs   = $pros[$prosedurKod];
    ?>
	<div class="card-body">
		<table id="tablebot_<?=str_replace('.','_',$prosedurKod) ?>" class="table table-bordered table-striped " >
			<thead>
				<tr>
					<th class="bg-gray-700 text-gray-200 text-center align-middle col-5">Görüşülen kişilerin</th>
					<th class="bg-gray-700 text-gray-200 text-center align-middle col-5">Görev</th>
					<th class="bg-gray-700 text-gray-200 text-center align-middle col-2">
						<button type="button" class="btn btn-primary col-11" data-toggle="modal" onclick="bosEkle('<?=str_replace('.','_',$prosedurKod) ?>')" data-target="#myModal" id="mstrBtn" >BOŞ EKLE</button>
					</th>
				</tr>
			</thead>
			<tbody  id="<?=str_replace('.','_',$prosedurKod)?>_tableLst" >
			</tbody>
		</table>
	</div>
    <?php 
    foreach ($prosedurs as $p){
        include 'plan_prosedur.php';
    }
}
?>