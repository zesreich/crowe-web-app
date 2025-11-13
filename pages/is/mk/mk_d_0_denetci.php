<?php 

// $lst = Crud::getSqlCok(new MKDenetci(), MKDenetci::GET_TEKLIF_EKIP, array('tklf_id'=>$tklf_id,'ekip'=>'Denetçi'));//$dEkip[1]));
// if ($lst != null){
//     $denetciList = Base::basitList($lst);
// }else{
//     $denetciList = $lst;
// }

// echo '<pre>';
// print_r($denetciList);
// echo '</pre>';

?>

<input type="hidden" id="bos_klnc_ekip">
<input type="hidden" id="dznl_kullanici_id">
<input type="hidden" id="dznl_kullanici_tc_no">
<input type="hidden" id="dznl_kullanici_kgk_sicil_no">
<input type="hidden" id="dznl_kullanici_ad">
<input type="hidden" id="dznl_kullanici_soyad">

<div id="dntclr">

</div>

<div class="card shadow mb-1">
    <div class="card-header">
    	<h6 class="m-0 font-weight-bold text-primary"><?=mkConfig::EKIP_ASIL_EKIP[1] ?> Listesi</h6>
    </div>
    <div class="row">
    	<div class="card-body">
    		<table id="tablebot_<?=mkConfig::EKIP_ASIL_EKIP[0]?>" class="table table-bordered table-striped " >
    			<thead>
    				<tr>
    					<th class="bg-gray-700 text-gray-200 text-center align-middle">TcNo</th>
    					<th class="bg-gray-700 text-gray-200 text-center align-middle">Ad Soyad</th>
    					<th class="bg-gray-700 text-gray-200 text-center align-middle">Sicil No</th>
    					<th class="bg-gray-700 text-gray-200 text-center align-middle">Görev</th>
    					<th class="bg-gray-700 text-gray-200 text-center align-middle">Pozisyon</th>
    					<th class="bg-gray-700 text-gray-200 text-center align-middle">Çalışma Süresi <br> (saat)</th>
    					<th class="bg-gray-700 text-gray-200 text-center align-middle">Ücret <br> (saat başı)</th>
    					<th class="bg-gray-700 text-gray-200 text-center align-middle">Denetim Ücreti</th>
    					<th class="bg-gray-700 text-gray-200 text-center align-middle" style="width: 100px;">
    						<button type="button" class="btn btn-primary col-lg-12" data-toggle="modal" onclick="denetciAra('<?=mkConfig::EKIP_ASIL_EKIP[0]?>')" data-target="#myModal" id="mstrBtn" >Bul</button>
    					</th>
    				</tr>
    			</thead>
    			<tbody  id="<?=mkConfig::EKIP_ASIL_EKIP[0]?>_tableLst" >
    			</tbody>
    		</table>
		</div>
	</div>
</div>
<div class="card shadow mb-1">
    <div class="card-header">
    	<h6 class="m-0 font-weight-bold text-primary"><?=mkConfig::EKIP_YEDEK_EKIP[1] ?> Listesi</h6>
    </div>
    <div class="row">
    	<div class="card-body">
    		<table id="tablebot_<?=mkConfig::EKIP_YEDEK_EKIP[0]?>" class="table table-bordered table-striped " >
    			<thead>
    				<tr>
    					<th class="bg-gray-700 text-gray-200 text-center align-middle">TcNo</th>
    					<th class="bg-gray-700 text-gray-200 text-center align-middle">Ad Soyad</th>
    					<th class="bg-gray-700 text-gray-200 text-center align-middle">Sicil No</th>
    					<th class="bg-gray-700 text-gray-200 text-center align-middle">Görev</th>
    					<th class="bg-gray-700 text-gray-200 text-center align-middle">Ücret <br> (saat başı)</th>
    					<th class="bg-gray-700 text-gray-200 text-center align-middle" style="width: 100px;">
    						<button type="button" class="btn btn-primary col-lg-12" data-toggle="modal" onclick="denetciAra('<?=mkConfig::EKIP_YEDEK_EKIP[0]?>')" data-target="#myModal" id="mstrBtn" >Bul</button>
    					</th>
    				</tr>
    			</thead>
    			<tbody  id="<?=mkConfig::EKIP_YEDEK_EKIP[0]?>_tableLst" >
    			</tbody>
    		</table>
		</div>
	</div>
</div>
<div class="card shadow mb-1">
    <div class="card-header">
    	<h6 class="m-0 font-weight-bold text-primary">Dosyayı Paylaş</h6>
    </div>
    <div class="row">
    	<div class="card-body">
    		<table id="tablebot_<?=mkConfig::EKIP_YARDIMCI_EKIP[0]?>" class="table table-bordered table-striped " >
    			<thead>
    				<tr>
    					<th class="bg-gray-700 text-gray-200 text-center align-middle">TcNo</th>
    					<th class="bg-gray-700 text-gray-200 text-center align-middle">Ad Soyad</th>
    					<th class="bg-gray-700 text-gray-200 text-center align-middle">Sicil No</th>
    					<th class="bg-gray-700 text-gray-200 text-center align-middle" style="width: 100px;">
    						<button type="button" class="btn btn-primary col-lg-12" data-toggle="modal" onclick="denetciAra('<?=mkConfig::EKIP_YARDIMCI_EKIP[0]?>')" data-target="#myModal" id="mstrBtn" >Bul</button>
    					</th>
    				</tr>
    			</thead>
    			<tbody  id="<?=mkConfig::EKIP_YARDIMCI_EKIP[0]?>_tableLst" >
    			</tbody>
    		</table>
		</div>
	</div>
</div>

<script type="text/javascript">

	var js_gorev 	=<?='["' . implode('", "', $cmbGRV) . '"]'?>;
	var js_pozisyon =<?='["' . implode('", "', $cmbPZSYM) . '"]'?>;

    var ekiplerVK = new Map();
    ekiplerVK.set("<?=mkConfig::EKIP_ASIL_EKIP[1].'","'.mkConfig::EKIP_ASIL_EKIP[0]?>");
    ekiplerVK.set("<?=mkConfig::EKIP_YEDEK_EKIP[1].'","'.mkConfig::EKIP_YEDEK_EKIP[0]?>");
    ekiplerVK.set("<?=mkConfig::EKIP_YARDIMCI_EKIP[1].'","'.mkConfig::EKIP_YARDIMCI_EKIP[0]?>");
    var ekiplerKV = new Map();
    ekiplerKV.set("<?=mkConfig::EKIP_ASIL_EKIP[0].'","'.mkConfig::EKIP_ASIL_EKIP[1]?>");
    ekiplerKV.set("<?=mkConfig::EKIP_YEDEK_EKIP[0].'","'.mkConfig::EKIP_YEDEK_EKIP[1]?>");
    ekiplerKV.set("<?=mkConfig::EKIP_YARDIMCI_EKIP[0].'","'.mkConfig::EKIP_YARDIMCI_EKIP[1]?>");

    function denetciAra(ekip) {
    	$("#bos_klnc_ekip").val(ekip);
        var link = '';
        if(ekip != '<?=mkConfig::EKIP_YARDIMCI_EKIP[0] ?>'){
            link = 'denetciSqlGetir';
        }else{
        	link = 'yardimciSqlGetir';
        }
    	$.get( "<?php echo PREPATH.'pages/genel/tabloAra.php?tablo=Kullanici&sql='?>"+link, function(data, status){
    		if(status == "success"){
    			$("#txtHint").empty();
    			$("#txtHint").append(data);
    		}else if(status == "error"){
    		    hataAc("Bilgi çekilemedi.");
    	    }
    	});
    	
    }

    function miniAraDonen() {
    	var id 			= $("#dznl_kullanici_id");
    	var tc_no		= $("#dznl_kullanici_tc_no");
    	var kgk_sicil_no= $("#dznl_kullanici_kgk_sicil_no");
    	var ad 			= $("#dznl_kullanici_ad");
    	var soyad 		= $("#dznl_kullanici_soyad");
    	var ekip 		= $("#bos_klnc_ekip");

    	if (id.val() != null && id.val() != "" && ekip != '' && ekip != null) {
    		var varmi = false;
    		if (ekip.val() == '<?=mkConfig::EKIP_YARDIMCI_EKIP[0]?>') {
    			$('.yrd_class').each(function() {
        			if (id.val() == $(this).val().trim()) {
        				varmi = true;
        			}
                });
			}else{
        		$('.kid_class').each(function() {
        			if (id.val() == $(this).val().trim()) {
        				varmi = true;
        			}
                });
			}
    		if (!varmi) {
    			var item =
    			{
					id			: 0,
					ekip		: ekiplerKV.get(ekip.val()),
					saat		: null,
					saat_ucret	: null,
					denetci_id	: {
    					id			: id.val(),
    					tc_no		: tc_no.val(),
    					ad			: ad.val(),
    					soyad		: soyad.val(),
    					kgk_sicil_no: kgk_sicil_no.val(),	
					}	
    			};
    			denetciSatirEkle(item);
    			ucretHesapla();
    		}else{
    			hataAc("Bu denetçi zaten ekli");
    		}
    	}
    	
    }


	function denetcileriListesi(){
		var table1 = $('#<?=mkConfig::EKIP_ASIL_EKIP[0] ?>_tableLst');
		var table2 = $('#<?=mkConfig::EKIP_YARDIMCI_EKIP[0] ?>_tableLst');
		var table3 = $('#<?=mkConfig::EKIP_YEDEK_EKIP[0] ?>_tableLst');
		table1.find("tr").remove();
		table2.find("tr").remove();
		table3.find("tr").remove();
		
		$.post( "<?=PREPATH.'post/mk0Post.php?tur=denetci&islem=liste'?>",{
			tklf_id	: <?=$tklf_id?>
		},function(data, status){
			if(status == "success"){
				var objx = JSON.parse(data);
			    if (objx.hata == false) {
	 			    var obj = JSON.parse(objx.icerik);
	    			if (obj != null && obj != ""){
		    			for (var i = 0; i < obj.length; i++) {
		    				denetciSatirEkle(obj[i]);
						}
	    			}
	    			ucretHesapla();
			    }
    		}else if(status == "error"){
    		    hataAc("Bir sorun oluştu.");
    	    }
        });
	}

	function denetciSatirEkle(item){
		var gorev ='';
		var pozisyon ='';
		js_gorev.map(function(vl){
			gorev = gorev+'<option class="dznl_val" value="'+vl+'"'+ ((vl == item.gorev) ? 'selected="selected"' : '') +'>'+vl+'</option>';
		});
		js_pozisyon.map(function(vl){
			pozisyon = pozisyon+'<option class="dznl_val" value="'+vl+'"'+ ((vl == item.pozisyon) ? 'selected="selected"' : '') +'>'+vl+'</option>';
		});
		var ekip = ekiplerVK.get(item.ekip);
		var str = '';
		str = str + ('<tr id="'+ekip+'_eleman_'+item.denetci_id.id+'" '+( item.id == 0 ? 'style="background-color:#CEFFCE"' : '' )+' >');
    	str = str + ('	<input type="hidden" id="'+ekip+'_id_'+item.denetci_id.id+'" value="'+item.id+'"/>');
    	str = str + ('	<input type="hidden" id="'+ekip+'_kid_'+item.denetci_id.id+'" class="'+ (ekip == '<?=mkConfig::EKIP_ASIL_EKIP[0]?>' ? 'mkDntcId' : '')+' '+(ekip != '<?=mkConfig::EKIP_YARDIMCI_EKIP[0]?>' ? 'kid_class' : 'yrd_class')+'" value="'+item.denetci_id.id+'">');
    	str = str + ('	<input type="hidden" id="'+ekip+'_ekip_'+item.denetci_id.id+'" value="'+item.ekip+'">');
    	str = str + ('  <td class="text-center align-middle">'+(item.denetci_id.tc_no == null ? '' : item.denetci_id.tc_no )+'</td>');
    	str = str + ('  <td class="text-center align-middle">'+item.denetci_id.ad+' '+item.denetci_id.soyad+'</td>');
    	str = str + ('  <td class="text-center align-middle">'+(item.denetci_id.kgk_sicil_no == null ? '' : item.denetci_id.kgk_sicil_no )+'</td>');
    	if (ekip != '<?=mkConfig::EKIP_YARDIMCI_EKIP[0]?>') {
        	str = str + ('  <td class="text-center align-middle">');
        	str = str + ('		<select class="custom-select form-control" id="'+ekip+'_gorev_'+item.denetci_id.id+'" >');
        	str = str + (' 			<option class="dznl_val" value="" selected="selected">Seçiniz</option>');
    		str = str + (gorev);
    		str = str + ('		</select>');
        	str = str + ('	</td>');
    	}
    	if (ekip == '<?=mkConfig::EKIP_ASIL_EKIP[0]?>') {
    		str = str + ('  <td class="text-center align-middle">');
        	str = str + ('		<select class="custom-select form-control" id="'+ekip+'_pozisyon_'+item.denetci_id.id+'" >');
        	str = str + (' 			<option class="dznl_val" value="" selected="selected">Seçiniz</option>');
    		str = str + (pozisyon);
    		str = str + ('		</select>');
        	str = str + ('	</td>');
    		str = str + ('<td class="text-center align-middle"><input id="'+ekip+'_saat_'+item.denetci_id.id+'" onchange="ucretHesapla()" onkeypress="return isNumberKey(event)"	type="text" class="form-control form-control-user" value="'+(item.saat == null 		? '' : item.saat)		+'"></td>');
    	}
    	if (ekip != '<?=mkConfig::EKIP_YARDIMCI_EKIP[0]?>') {
    		str = str + ('<td class="text-center align-middle"><input id="'+ekip+'_saat_ucret_'+item.denetci_id.id+'" onchange="ucretHesapla()" onkeypress="return isNumberKey(event)" 	type="text" class="form-control form-control-user" value="'+(item.saat_ucret == null ? '' : item.saat_ucret) +'"></td>');
    	}
    	if (ekip == '<?=mkConfig::EKIP_ASIL_EKIP[0]?>') {
    		str = str + ('<td class="text-center align-middle"><label id="'+ekip+'_ucret_'+item.denetci_id.id+'"></label></td>');
    	}
    	str = str + ('      <td class="text-center align-middle">');
    	str = str + ('      	<button type="button" class="btn btn-primary col-lg-12"	onclick="denetci_Ekle(\''+ekip+'\','+item.denetci_id.id+')" >Kaydet</button>');
    	str = str + ('      	<button type="button" class="btn btn-danger  col-lg-12"	onclick="denetci_Sil (\''+ekip+'\','+item.id+','+item.denetci_id.id+')" >Sil</button>');
    	str = str + ('  	</td>');
    	str = str + ('</tr>');
    	
    	var table = $('#'+ekip+'_tableLst');
    	table.append(str);
	}

	function ucretHesapla(){

		var tpl = document.getElementById("tplmUcret");//.remove();
		if (tpl != null) {
			tpl.remove();
		}
		
		var table = $('#tablebot_<?=mkConfig::EKIP_ASIL_EKIP[0]?>');
		table.append(
			'<tr id="tplmUcret" >'+
			'<td colspan="7"></td>'+
			'<td class="text-center align-middle"><label id="denetimUcretToplam" >22</label></td>'+
			'<td colspan="1"></td>'+
	    	'</tr>'
		);

		var toplam = 0;
		var idler = new Array();
		var arrData = $('.mkDntcId');
		for (i = 0; i < arrData.length; i++) {
			var v = arrData[i].value;
			if ($('#<?=mkConfig::EKIP_ASIL_EKIP[0]?>_saat_'+v).val() != '' && $('#<?=mkConfig::EKIP_ASIL_EKIP[0]?>_saat_ucret_'+v).val() != '' ) {
				var saat = $('#<?=mkConfig::EKIP_ASIL_EKIP[0]?>_saat_'+v).val();
				var saatU = $('#<?=mkConfig::EKIP_ASIL_EKIP[0]?>_saat_ucret_'+v).val();
				var ar = [v , saat,saatU];
				idler.push(ar);
			}else{
				$('#<?=mkConfig::EKIP_ASIL_EKIP[0]?>_ucret_'+v).text('');
			}
		}

		for (i = 0; i < idler.length; i++) {
			$('#<?=mkConfig::EKIP_ASIL_EKIP[0]?>_ucret_'+idler[i][0]).text(formatPara((idler[i][1] * idler[i][2]).toFixed(2)));
			toplam = (+toplam + +(idler[i][1] * idler[i][2]).toFixed(2)).toFixed(2);
		}

		$('#denetimUcretToplam').text(formatPara(toplam));
		if (toplam == <?= $tklf['tutar']?>) {
			$('#denetimUcretToplam').removeAttr('style');
			$('#denetimUcretToplam').css("color", "#00ff00");
		}else{
			$('#denetimUcretToplam').removeAttr('style');
			$('#denetimUcretToplam').css("color", "#ff0000");
			$("#denetimUcretToplam").css("font-size", "20px");
			$("#denetimUcretToplam").css('font-weight', 'bold');
		}
	}
  	
    function denetci_Ekle(ekip,id){
    	var link;
    	if (id == 0) {
    		link = "<?=PREPATH.'post/mk0Post.php?tur=denetci&islem=create&mesaj=true' ?>";
    	}else{
    		link = "<?=PREPATH.'post/mk0Post.php?tur=denetci&islem=update&mesaj=true' ?>";
    	}
    	$.post(link,
            {
        		id 			: $('#'+ekip+'_id_'+id).val(),
        		tklf_id		: <?=$tklf_id?>,
    			denetci_id 	: $('#'+ekip+'_kid_'+id).val(),
        		ekip		: $('#'+ekip+'_ekip_'+id).val(),
        		gorev		: $('#'+ekip+'_gorev_'+id).val(),
        		pozisyon	: $('#'+ekip+'_pozisyon_'+id).val(),
        		saat		: $('#'+ekip+'_saat_'+id).val(),
        		saat_ucret	: $('#'+ekip+'_saat_ucret_'+id).val(),
        		uygun		: "E"
    	    },
    	    function(data,status){
        		if(status == "success"){
        		    var obj = JSON.parse(data);
        		    if (obj.hata == true) {
        				hataAc(obj.hataMesaj);
        		    }else{
        		    	onayAc(obj.icerik);
        		    	denetcileriListesi();
        		    }
        		}else if(status == "error"){
        		    hataAc("Bir sorun oluştu.");
        	    }
    	    }
        );
    }

    function denetci_Sil(ekip,id,kid){
    	if (id == 0) {
    		$('#'+ekip+'_eleman_'+kid).remove();
    	}else{
    		$.post( "<?=PREPATH.'post/mk0Post.php?tur=denetci&islem=sil'?>",
    			{
    				tklf_id	: <?=$tklf_id?>,
    				id		: $('#'+ekip+'_id_'+kid).val(),
    				link	: <?="'".$link."'" ?>
    			}, 
    		    function(data,status){
    	    		if(status == "success"){
    	    		    var obj = JSON.parse(data);
    	    		    if (obj.hata == true) {
    	    				hataAc(obj.hataMesaj);
    	    		    }else{
    	    		    	onayAc(obj.icerik);
    	    		    	denetcileriListesi();
    	    		    }
    	    		}else if(status == "error"){
    	    		    hataAc("Bir sorun oluştu.");
    	    	    }
    		    }
    	    );
    	}
    }

    denetcileriListesi();
</script>