<?php
$pId = 224;
include_once '../../First.php';

$id = $_GET['id'];
$tklf   = Crud::getById(new Denetim(),$id)->basit();
$ortakMi = false;
if ($_SESSION['login']['isortagi_id'] == $tklf['musteri_id']['isortagi_id']['id']){
    $ortakMi = true;
}

if ( KullaniciTurPrm::IT != $_SESSION['login']['tur'] &&  KullaniciTurPrm::DENETCI != $_SESSION['login']['tur'] && $tklf['musteri_id']['isortagi_id']['id'] != $_SESSION['login']['isortagi_id']){
    hata('Bu sayfa için yetkiniz yok.',PREPATH);
}

include_once PREPATH . 'soa/isOrtakSoa.php';
include_once PREPATH . 'header.php';

?>
<style>
.table-responsive{
    max-height: 40vh;
}
.switch {
  position: relative;
  display: inline-block;
  width: 60px;
  height: 34px;
}

.switch input { 
  opacity: 0;
  width: 0;
  height: 0;
}

.slider {
  position: absolute;
  cursor: pointer;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: #ccc;
  -webkit-transition: .4s;
  transition: .4s;
}

.slider:before {
  position: absolute;
  content: "";
  height: 26px;
  width: 26px;
  left: 4px;
  bottom: 4px;
  background-color: white;
  -webkit-transition: .4s;
  transition: .4s;
}

input:checked + .slider {
  background-color: #0275d8;
}

input:focus + .slider {
  box-shadow: 0 0 1px #0275d8;
}

input:checked + .slider:before {
  -webkit-transform: translateX(26px);
  -ms-transform: translateX(26px);
  transform: translateX(26px);
}

/* Rounded sliders */
.slider.round {
  border-radius: 34px;
}

.slider.round:before {
  border-radius: 50%;
}
</style>
<input type="hidden" id="dznl_isortagi_id">
<input type="hidden" id="dznl_isortagi_unvan">

<div class="row" >
    <div class="col-12">
    	<div class="card-header bg-gradient-primary py-3">
        	<h6 class="m-0 font-weight-bold text-gray-300"><?= $id .' - '. $tklf['musteri_id']['unvan'] ?></h6>
        </div>
        <div class="row m-4">
        	<div class="col-lg-4 font-weight-bold text-gray-800 align-self-center  text-right">Sözleşme Tutarı	 :</div>
        	<div class="col-lg-8" id="tutar"><?=$tklf['tutar']?></div>
        </div>
        <div class="row m-4">
        	<div class="col-lg-4 font-weight-bold text-gray-800 align-self-center  text-right">Para Birimi	 :</div>
        	<div class="col-lg-8" id="dznl_dton_id2"><?=$tklf['para_birimi_id']['adi']?></div>
        </div>
    	<table id="aa" class="table table-bordered table-striped " >
			<thead>
				<tr>
					<th class="bg-gray-700 text-gray-200 text-center align-middle">Id</th>
					<th class="bg-gray-700 text-gray-200 text-center align-middle">İş Ortağı</th>
					<th class="bg-gray-700 text-gray-200 text-center align-middle">Payı</th>
					<th class="bg-gray-700 text-gray-200 text-center align-middle">Payı Tutar</th>
					<th class="bg-gray-700 text-gray-200 text-center align-middle">Fatura</th>
					<th class="bg-gray-700 text-gray-200 text-center align-middle">Ödeme</th>
					<?php if (KullaniciTurPrm::ISORTAGI != $_SESSION['login']['tur']) {?>
					<th class="bg-gray-700 text-gray-200 text-center align-middle"></th>
					<?php }?>
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
	var topTutar = $('#tutar').text();

	getir();
	
	function getir(){
		var table = $('#tableLst');
		table.empty();
    	$.get( "<?= PREPATH.'post/isOrtakPost.php?tur=ortakPayDegerGetir&id='.$id.($ortakMi ? '&ortak_id='.$tklf['musteri_id']['isortagi_id']['id'] : '' )?>", 
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
		    		    	str = str + ('  <td class="text-center align-middle">'+item.pay_ortak_id.unvan+'</td>');
	                        
                            <?php if (KullaniciTurPrm::ISORTAGI == $_SESSION['login']['tur']) {?>
		    		    		str = str + ('  <td class="text-center align-middle">'+(item.pay == null ? 0 : item.pay)+'</td>');
		    		    	<?php }else{ ?>
		    		    		str = str + ('	<td class="text-right align-middle"><input id="pay_'+item.id+'"  		onchange="toplamHesapla()" onkeypress="return isNumberKey(event)" 	type="text" class="form-control form-control-user pay" value="'+(item.pay == null ? 0 : item.pay) +'"></td>');
		    		    	<?php }?>
		    		    	
		    		    	str = str + ('	<td class="text-right align-middle"><label id="pay_tutar_'+item.id+'" class="payTutar"></label></td>');
	                        <?php if (KullaniciTurPrm::ISORTAGI == $_SESSION['login']['tur']) {?>
		    		    		str = str + ('  <td class="text-center align-middle">'+(item.fatura == 'H' ? 'HAYIR' : 'EVET')+'</td>');
		    		    		str = str + ('  <td class="text-center align-middle">'+(item.odeme == 'H' ? 'HAYIR' : 'EVET')+'</td>');
		    		    	<?php }else{ ?>
		    		    		str = str + ('	<td class="text-center align-middle"><label class="switch"><input id="chk_fatura_'+item.id+'" type="checkbox" '+(item.fatura == 'H' ? '' : 'checked' )+'><span class="slider round"></span></label></td>');
		    		    		str = str + ('	<td class="text-center align-middle"><label class="switch"><input id="chk_odeme_'+item.id+'"  type="checkbox" '+(item.odeme == 'H' ? '' : 'checked' )+'><span class="slider round"></span></label></td>');
    		    		    	str = str + ('  <td class="text-center align-middle">');
    		    		    	str = str + ('      <button type="button" class="btn btn-primary col-lg-12"	onclick="pay_duzenle('+item.id+')" >Kaydet</button>');
    		    		    	str = str + ('  </td>');
		    		    	<?php }?>
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

	function pay_duzenle(id){
    	$.post("<?=PREPATH.'post/isOrtakPost.php?tur=payDeger&islem=update' ?>",
            {
        		id 		: id,
        		pay		: $('#pay_'+id).val(),
        		tutar 	: $('#pay_tutar_'+id).val(),
        		fatura	: $('#chk_fatura_'+id)[0].checked,
        		odeme 	: $('#chk_odeme_'+id)[0].checked
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
			'<td colspan="5"></td>'+
	    	'</tr>'
		);

		var toplam = 0;
		var arrData = $('.pay');
		for (i = 0; i < arrData.length; i++) {
			toplam += +arrData[i].value;
		}
		
		var arrData = $('.ids');
		for (i = 0; i < arrData.length; i++) {
			var pay = $('#pay_'+arrData[i].value);
			var snc = Math.floor(((topTutar * pay.val()) / toplam)*100)/100;  	
			$('#pay_tutar_'+arrData[i].value).text(formatPara(snc));
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