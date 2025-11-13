<?php
$pId = 221;
include_once '../../First.php';

$ortak = $_GET['id'];
if (KullaniciTurPrm::IT != $_SESSION['login']['tur'] && KullaniciTurPrm::DENETCI != $_SESSION['login']['tur'] && $_SESSION['login']['isortagi_id'] != $ortak){
    hata('Bu sayfa için yetkiniz yok.',PREPATH);
}

include_once PREPATH . 'header.php';

$ortk   = Crud::getById(new IsOrtagi(),$ortak) -> basit();
?>

<input type="hidden" id="dznl_isortagi_id">
<input type="hidden" id="dznl_isortagi_unvan">

<div class="row" >
    <div class="col-12">
    	<div class="card-header bg-gradient-primary py-3">
        	<h6 class="m-0 font-weight-bold text-gray-300"><?= $ortk['unvan']?></h6>
        </div>
    	<table id="aa" class="table table-bordered table-striped " >
			<thead>
				<tr>
					<th class="bg-gray-700 text-gray-200 text-center align-middle">İş Ortağı</th>
					<th class="bg-gray-700 text-gray-200 text-center align-middle">Payı</th>
					<th class="bg-gray-700 text-gray-200 text-center align-middle" style="width: 100px;">
						<button type="button" class="btn btn-primary col-lg-12" data-toggle="modal" onclick="payAra()" data-target="#myModal" id="mstrBtn" >Bul</button>
					</th>
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

    function payAra(ekip) {
    	$.get( "<?php echo PREPATH.'pages/genel/tabloAra.php?tablo=IsOrtagi'?>", function(data, status){
    		if(status == "success"){
    			$("#txtHint").empty();
    			$("#txtHint").append(data);
    		}else if(status == "error"){
    		    hataAc("Bilgi çekilemedi.");
    	    }
    	});
    }

    function miniAraDonen() {
    	var id 			= $("#dznl_isortagi_id");
    	var unvan		= $("#dznl_isortagi_unvan");

		var varmi = false;
		
		$('.ortk_secili').each(function() {
			if (id.val() == $(this).val().trim()) {
				varmi = true;
			}
        });
		if (!varmi) {
			var table = $('#tableLst');
			var str = '';
			str = str + ('<tr id="yeni">');
	    	str = str + ('	<input type="hidden" id="id_0"  value="0"  />');
	    	str = str + ('	<input type="hidden" id="pid_0" value="'+id.val()+'" class="ortk_secili" />');
	    	str = str + ('  <td class="text-center align-middle">'+unvan.val()+'</td>');
	    	str = str + ('	<td class="text-right align-middle"><input id="pay_0"  onchange="toplamHesapla()" onkeypress="return isNumberKey(event)" 	type="text" class="form-control form-control-user pay" value="0"></td>');
	    	str = str + ('  <td class="text-center align-middle">');
	    	str = str + ('      <button type="button" class="btn btn-primary col-lg-12"	onclick="pay_Ekle(0)" >Kaydet</button>');
	    	str = str + ('  	<button type="button" class="btn btn-danger  col-lg-12"	onclick="pay_Sil(0)" >Sil</button>');
	    	str = str + ('  </td>');
			str = str + ('</tr>');
			table.append(str);
	    	toplamHesapla();
		}else{
			hataAc("Bu denetçi zaten ekli");
		}
    }
    

	getir();
	

	function getir(){
		var table = $('#tableLst');
		table.empty();
    	$.get( "<?php echo PREPATH.'post/isOrtakPost.php?tur=ortakPaylariGetir&id='.$ortak?>", 
		function(data, status){
			if(status == "success"){
				var objx = JSON.parse(data);
			    if (objx.hata == false) {
	 			    var obj = JSON.parse(objx.icerik);
	    			if (obj != null && obj != ""){
		    			console.log(obj.length);
	    				var str = '';
		    			for (var i = 0; i < obj.length; i++) {
			    			item = obj[i];
		    				str = str + ('<tr >');
		    		    	str = str + ('	<input type="hidden" id="id_'+item.id+'"  value="'+item.id+'" />');
		    		    	str = str + ('	<input type="hidden" id="pid_'+item.id+'" value="'+item.pay_ortak_id.id+'" class="ortk_secili" />');
		    		    	str = str + ('  <td class="text-center align-middle">'+item.pay_ortak_id.unvan+'</td>');
		    		    	str = str + ('	<td class="text-right align-middle"><input id="pay_'+item.id+'"  onchange="toplamHesapla()" onkeypress="return isNumberKey(event)" 	type="text" class="form-control form-control-user pay" value="'+(item.pay == null ? '' : item.pay) +'"></td>');
		    		    	str = str + ('  <td class="text-center align-middle">');
		    		    	str = str + ('      <button type="button" class="btn btn-primary col-lg-12"	onclick="pay_Ekle('+item.id+')" >Kaydet</button>');
		    		    	str = str + ('  	<button type="button" class="btn btn-danger  col-lg-12"	onclick="pay_Sil ('+item.id+')" >Sil</button>');
		    		    	str = str + ('  </td>');
		    				str = str + ('</tr>');
						}
		    	    	table.append(str);
		    	    	toplamHesapla();
	    			}
			    }
    		}else if(status == "error"){	
    		    hataAc("Bir sorun oluştu.");
    	    }
        });
	}

    function pay_Ekle(id){
    	var link;
    	if (id == 0) {
    		link = "<?=PREPATH.'post/isOrtakPost.php?tur=pay&islem=create' ?>";
    	}else{
    		link = "<?=PREPATH.'post/isOrtakPost.php?tur=pay&islem=update' ?>";
    	}
    	$.post(link,
            {
        		id 			: id,
        		ortak_id 	: <?=$ortak?>,
        		pay_ortak_id: $('#pid_'+id).val(),
        		pay			: $('#pay_'+id).val()
    	    },
    	    function(data,status){
        		if(status == "success"){
        		    var obj = JSON.parse(data);
        		    if (obj.hata == true) {
        				hataAc(obj.hataMesaj);
        		    }else{
        		    	onayAc(obj.icerik);
        		    	getir();
        		    }
        		}else if(status == "error"){
        		    hataAc("Bir sorun oluştu.");
        	    }
    	    }
        );
    }

    function pay_Sil(id){
    	if (id == 0) {
    		$('#yeni').remove();
    	}else{
    		$.post( "<?=PREPATH.'post/isOrtakPost.php?tur=pay&islem=sil'?>",
    			{
    				id		: id,
    			}, 
    		    function(data,status){
    	    		if(status == "success"){
    	    		    var obj = JSON.parse(data);
    	    		    if (obj.hata == true) {
    	    				hataAc(obj.hataMesaj);
    	    		    }else{
    	    		    	onayAc(obj.icerik);
    	    		    	getir();
    	    		    }
    	    		}else if(status == "error"){
    	    		    hataAc("Bir sorun oluştu.");
    	    	    }
    		    }
    	    );
    	}
    }
	
	function toplamHesapla(){

		var tpl = document.getElementById("tplPay");//.remove();
		if (tpl != null) {
			tpl.remove();
		}
		
		var table = $('#tableLst');
		table.append(
			'<tr id="tplPay" >'+
			'<td ></td>'+
			'<td class="text-center align-middle"><label id="payToplam" >22</label></td>'+
			'<td colspan="1"></td>'+
	    	'</tr>'
		);

		var toplam = 0;
		var arrData = $('.pay');
		for (i = 0; i < arrData.length; i++) {
			toplam += +arrData[i].value;
		}

		$('#payToplam').text(formatPara(toplam));
		if (toplam == 100) {
			$('#payToplam').removeAttr('style');
			$('#payToplam').css("color", "#00ff00");
		}else{
			$('#payToplam').removeAttr('style');
			$('#payToplam').css("color", "#ff0000");
			$("#payToplam").css("font-size", "20px");
			$("#payToplam").css('font-weight', 'bold');
		}
	}

</script>
<?php include (PREPATH.'footer.php'); ?>