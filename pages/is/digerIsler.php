<?php
$pId = 226;
include_once '../../First.php';

$id = $_GET['id'];
$ortak   = Crud::getById(new IsOrtagi() ,$id)->basit();
if (KullaniciTurPrm::IT != $_SESSION['login']['tur'] && KullaniciTurPrm::DENETCI != $_SESSION['login']['tur'] && $_SESSION['login']['isortagi_id'] != $id){
   hata('Bu sayfa için yetkiniz yok.',PREPATH);
}

include_once PREPATH . 'soa/isOrtakSoa.php';
include_once PREPATH . 'header.php';

?>

<input type="hidden" id="dznl_isortagi_id">
<input type="hidden" id="dznl_isortagi_unvan">

<div class="row" >
    <div class="col-12">
    	<div class="card-header bg-gradient-primary py-3">
        	<h6 class="m-0 font-weight-bold text-gray-300"><?= $id .' - '. $ortak['unvan']?></h6>
        </div>
    	<table id="aa" class="table table-bordered table-striped " >
			<thead>
				<tr>
					<th class="bg-gray-700 text-gray-200 text-center align-middle">Id</th>
					<th class="bg-gray-700 text-gray-200 text-center align-middle">Asıl İş Ortağı</th>
					<th class="bg-gray-700 text-gray-200 text-center align-middle">Payı</th>
					<th class="bg-gray-700 text-gray-200 text-center align-middle">Payı Tutar</th>
					<th class="bg-gray-700 text-gray-200 text-center align-middle">Fatura</th>
					<th class="bg-gray-700 text-gray-200 text-center align-middle">Ödeme</th>
				</tr>
			</thead>
        	<tbody id="tableLst" >
    		</tbody>
		</table>
	</div>
</div>
<div class="modal fade" id="myModal" role="dialog">
	<div class="modal-dialog modal-xl">
		<div class="modal-content">
			<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 mb-12">
				<div class="card" style="min-height:395px;">
					<div class="card-block">
						<div id="txtHint"></div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">

	getir();
	function getir(){
		var table = $('#tableLst');
		table.empty();
    	$.get( "<?= PREPATH.'post/isOrtakPost.php?tur=digerIsGetir&id='.$id?>", 
		function(data, status){
			if(status == "success"){
				var objx = JSON.parse(data);
			    if (objx.hata == false) {
	 			    var obj = JSON.parse(objx.icerik);
	 			    console.log(obj);
	    			if (obj != null && obj != ""){
	    				var str = '';
		    			for (var i = 0; i < obj.length; i++) {
			    			item = obj[i];
		    				str = str + ('<tr >');
		    		    	str = str + ('	<input type="hidden" id="id_'+item.id+'" class="ids"  value="'+item.id+'" />');
		    		    	str = str + ('	<input type="hidden" id="pid_'+item.id+'" value="'+item.pay_ortak_id.id+'" class="ortk_secili" />');
		    		    	str = str + ('  <td class="text-center align-middle">'+item.tklf_id+'</td>');
		    		    	str = str + ('  <td class="text-center align-middle">'+item.ortak_id.unvan+'</td>');
	    		    		str = str + ('  <td class="text-center align-middle">'+(item.pay == null ? 0 : item.pay)+'</td>');
		    		    	str = str + ('	<td class="text-right align-middle"><label id="pay_tutar_'+item.id+'" >'+formatPara(item.tutar)+' </label></td>');
	    		    		str = str + ('  <td class="text-center align-middle">'+(item.fatura == 'H' ? 'HAYIR' : 'EVET')+'</td>');
	    		    		str = str + ('  <td class="text-center align-middle">'+(item.odeme == 'H' ? 'HAYIR' : 'EVET')+'</td>');
		    				str = str + ('</tr>');
						}
		    	    	table.append(str);
	    			}
			    }
    		}else if(status == "error"){	
    		    hataAc("Bir sorun oluştu.");
    	    }
        });
	}


</script>
<?php include (PREPATH.'footer.php'); ?>