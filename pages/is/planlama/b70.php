<div class="row">
    <div class="col-lg-12 col-xl-12">
        <div class="card shadow" >
            <div class="card-header py-3"></div>
            <div class="card-body">
				<table class="table table-bordered mb-0">
                	<thead>
                		<tr >
                			<td class="table-warning bg-gray-700 text-gray-200 text-center align-middle" style="width: 25%" >Hesaplamaya Esas Alınabilecek Kriterler</td>
                			<td class="table-warning bg-gray-700 text-gray-200 text-center align-middle" style="width: 14%" >Hesaplamaya İlişkin Kullanabilecek Oranlar</td>
                			<td class="table-warning bg-gray-700 text-gray-200 text-center align-middle" style="width: 14%" >Hesaplamaya İlişkin Kullanılan Oranlar (%)</td>
                			<td class="table-success bg-gray-700 text-gray-200 text-center align-middle" style="width: 14%" >Hesaplamaya Baz Alınan Tutarlar</td>
                			<td class="table-success bg-gray-700 text-gray-200 text-center align-middle" style="width: 14%" >Genel Önemlilik Düzeyi (GOD)</td>
                			<td class="table-success bg-gray-700 text-gray-200 text-center align-middle" style="width: 14%" >Performans Önemliliği Düzeyi (POD) (%60-%80 arasında) (genellikle %75)<br/>Oran : %<input id="b70_pod" type="number" style="width: 50px" class="text-right" value="75"></td>
                			<td class="table-success bg-gray-700 text-gray-200 text-center align-middle" style="width: 5%" 	>Kaydet</td>
                		</tr>
                	</thead>
                    <tbody>
                    	<?php 
                    	foreach (planRiskProsedurConfig::B70_HESAPLAMA_KRITERLERI as $b70){
                    	    ?>
                    	    <tr>
                    	    <td ><?=$b70[1]?><input id="b70_<?=$b70[0]?>_id" 	type="hidden" value="<?=isset($b70List[$b70[0]]) ? $b70List[$b70[0]]['id'] : 0 ?>"/></td>
                    	    <td ><?='%'.$b70[2].' - %'.$b70[3].' arası'?></td>
                    	    <td><input type="number" 	id="b70_<?=$b70[0]?>_oran" 	class="form-control form-control-user text-right" value="<?=isset($b70List[$b70[0]]) ? $b70List[$b70[0]]['oran'] : '' ?>"></td>
                    	    <td><input type="text" 		id="b70_<?=$b70[0]?>_tutar" class="form-control form-control-user text-right" value="<?=isset($b70List[$b70[0]]) ? $b70List[$b70[0]]['tutar'] : '' ?>"></td>
                    	    <td><input type="text"		id="b70_<?=$b70[0]?>_god" 	class="form-control form-control-user text-right" disabled></td>
                    	    <td><input type="text" 		id="b70_<?=$b70[0]?>_perf" 	class="form-control form-control-user text-right" value="<?=isset($b70List[$b70[0]]) ? $b70List[$b70[0]]['performans'] : '' ?>"></td>
                    	    <td><button type="button" class="btn btn-primary " onclick="b70Ekle(<?=$b70[0]?>)" >Kaydet</button></td>
                    	    </tr>
                    	    <?php 
                    	}
                    	?>
                	</tbody>
            	</table>
			</div>
		</div>
	</div>
	<div class="col-lg-12 col-xl-12 mt-3">
        <div class="card shadow" >
            <div class="card-body">    
            	<div class="row">
    				<div class="col-xl-4">
    					<div class="card shadow h-100">
    						<div class="card-body">
    							<input id="b70_s_id" type="hidden" value=""/>
                            	<table class="table table-bordered mt-4">
                                    <tbody>
                                		<tr>
                                    		<td style="width: 70%" colspan="2" class="table-warning bg-gray-700 text-gray-200 ">
                                    		<a data-toggle="tooltip" title="Crowe HSY denetim ekibi, işletmenin faaliyet konusunu, operasyonlarını, çevresi ile olan ilişkilerini, işletmenin içinde bulunduğu pazarı ve bu pazardaki ekonomik koşulları göz önüne alarak, maddi hataları ortaya çıkaracak şekilde bir önemlilik seviyesi belirler." data-placement="top"  class = "tooltp" ><i class="fas fa-info-circle"></i></a>
                                    		B70.01 - (GOD) - Mali tablolar için genel önemlilik seviyesi</td>
                                    		<td style="width: 30%" ><input id="b70_s_god" type="text" class="form-control form-control-user text-right" value=""></td>
                                		</tr>
                                		<tr>
                                    		<td colspan="2" class="table-warning bg-gray-700 text-gray-200 ">
                                    		<a data-toggle="tooltip" title="Performans önemlilik Düzeyi (POD), hesaplanan önemlilik seviyesinin %60-%80'i olarak hesaplanır ve denetim testlerinde örnek hesaplama sırasında ve çıkan hataların öneminin tespitinde kullanılır." data-placement="top"  class = "tooltp" ><i class="fas fa-info-circle"></i></a>
                                    		B70.02 - (POD) - Performans Önemliliği</td>
                                    		<td ><input id="b70_s_pod" type="text" disabled class="form-control form-control-user text-right" value=""></td>
                                		</tr>
                                		<tr>
                                    		<td class="table-warning bg-gray-700 text-gray-200 " style="width: 55%">
                                    		<a data-toggle="tooltip" title="Düzeltme Farkı Eşiği (DFE), hesaplanan genel önemlilik düzeyinin %0-%10'u olarak hesaplanır ve denetim testlerinde örnek hesaplama sırasında ve çıkan hatalara ilişkin düzeltilmesi gereken mali tablo kalemlerinin hangileri olduğunun tespitinde kullanılır." data-placement="top"  class = "tooltp" ><i class="fas fa-info-circle"></i></a>
                                    		B70.03 - (DFE) - Düzeltme farkı eşiği (%0 ila %10 arasında)</td>
                                    		<td class="table-warning bg-gray-700 text-gray-200 " style="width: 15%"><input id="b70_s_oran" type="number" style="width: 60px" class="text-right" value="10"></td>
                                    		<td ><input id="b70_s_dfe" type="text" disabled class="form-control form-control-user text-right" value=""></td>
                                		</tr>
                                	</tbody>
                            	</table>
                        	</div>
                    	</div>
    				</div>
    				<div class="col-xl-4">
						<div class="card shadow h-100">
							<div class="card-header py-3 d-flex flex-row align-items-center justify-content-between ">
								<span class="text">Cari dönemde seçilen hesaplama bazının neden seçildiği hakkında notunuzu belirtiniz</span>
                            </div>
							<div class="card-body">
								<textarea id="b70_s_nots" rows="5" class="form-control" ><?=$plnm['nots'] ?></textarea>
							</div>
						</div>
    				</div>
    				<div class="col-xl-4">
						<div class="card shadow h-100">
							<div class="card-header py-3 d-flex flex-row align-items-center justify-content-between ">
								<span class="text">Başlangıçta önemliliğe ilişkin belirlemiş olduğumuz tutarlar daha sonra değişmiş ise değişikliğin nedenini ve değişiklikten önceki tutarı (GOD, POD, DFE tutarını ayrı ayrı) aşağıda</span>
                            </div>
							<div class="card-body">
								<textarea id="b70_s_degisik" rows="4" class="form-control" ><?=$plnm['degisik'] ?></textarea>
							</div>
						</div>
    				</div>
    				<div class="col-xl-12 mt-3">
        				<div id="b70_s_btn" class="col-lg-12 text-center">
                        	<a href="#" class="btn btn-primary col-lg-6">
                        		<i class="fa fa-floppy-o"></i>
                                <span  class="text">Kaydet</span>
                            </a>
                        </div>
                    </div>
				</div>
        	</div>
    	</div>
	</div>
</div>
<script type="text/javascript">

<?php 
    $arr = '';
    foreach (planRiskProsedurConfig::B70_HESAPLAMA_KRITERLERI as $b70){
        $arr .= ($arr != '') ? ','.$b70[0] : $b70[0];
    
    ?>
	$("#b70_<?=$b70[0]?>_tutar").change(function() {
		$("#b70_<?=$b70[0]?>_tutar").val(formatPara($("#b70_<?=$b70[0]?>_tutar").val().replaceAll(',','.')));
		godHesaplama(<?=$b70[0]?>);
	});
	
    $("#b70_<?=$b70[0]?>_oran").change(function() {
    	let v = this.value;
    	if (v < <?=$b70[2]?>) {
      		this.value = <?=$b70[2]?>;
    	}else if (v > <?=$b70[3]?>) {
      		this.value = <?=$b70[3]?>;
    	}
    	godHesaplama(<?=$b70[0]?>);
    });

    $("#b70_<?=$b70[0]?>_perf").change(function() {
    	var tutar = $('#b70_<?=$b70[0]?>_perf').val().replaceAll('.','').replaceAll(',','.');
    	$('#b70_<?=$b70[0]?>_perf').val(formatPara(tutar));
    });
<?php } 
    echo 'var b70List = ['.$arr.'];';
?>

    $("#b70_pod").change(function() {
    	let v = this.value;
    	if (v < 60) {
      		this.value = 60;
    	}else if (v > 80) {
      		this.value = 80;
    	}
    	for (var i = 0; i < b70List.length; i++) {
			godHesaplama(b70List[i]);
		}
    });

    function godHesaplama(tur){
		var oran  = $('#b70_'+tur+'_oran').val();
		var tutar = $('#b70_'+tur+'_tutar').val().replaceAll('.','').replaceAll(',','.');
		var oran  = $('#b70_'+tur+'_oran').val();
		var tutar = $('#b70_'+tur+'_tutar').val().replaceAll('.','').replaceAll(',','.');
		if (oran != '' && tutar != '') {
    		var genel = (oran/100)*tutar;
            $('#b70_'+tur+'_god').val(formatPara(Math.floor(genel*100)/100));
            var uoran =$('#b70_pod').val();
            $('#b70_'+tur+'_perf').val( formatPara(Math.floor(genel*uoran)/100) );
		}
    }

    $("#b70_s_god").change(function() {
    	altGodHesaplama();
    });

    $("#b70_s_oran").change(function() {
    	let v = this.value;
    	if (v < 0) {
      		this.value = 0;
    	}else if (v > 10) {
      		this.value = 10;
    	}
    	altGodHesaplama();
    });

    function altGodHesaplama(){
        var ttr = $("#b70_s_god").val().replaceAll('.','').replaceAll(',','.');
    	var uoran =$('#b70_pod').val();
    	$('#b70_s_god').val(formatPara(ttr));
    	var tutar = Math.floor((uoran*ttr)/100);
    	$('#b70_s_pod').val(formatPara(tutar));
    	var dfeoran =$('#b70_s_oran').val();
    	$('#b70_s_dfe').val(formatPara(Math.floor(ttr*dfeoran)/100));
    }
    
    b70Yukle();
    function b70Yukle(){
    	$('.tooltp').tooltip();
    	$.post("<?=PREPATH.'post/planlama/b70Post.php?tur=b70Getir' ?>",
            {
        		tklf_id	: <?=$tklf_id?>,
    	    },
    	    function(data,status){
        		if(status == "success"){
        		    var obj = JSON.parse(data);
        		    if (obj.hata == true) {
        				hataAc(obj.hataMesaj);
        		    }else{
            		    var items = JSON.parse(obj.icerik);
            		    var genelOran = 0;
            	    	for (var i = 0; i < items.length; i++) {
                	    	var itm = items[i];
                	    	var god = (itm.oran*itm.tutar)/100;
							if (genelOran == 0) {
								genelOran = (100*itm.performans)/god;
							}
            				$('#b70_'+itm.tur+'_id').val(itm.id);
            				$('#b70_'+itm.tur+'_oran').val(itm.oran);
            				$('#b70_'+itm.tur+'_tutar').val(formatPara(itm.tutar));
            				$('#b70_'+itm.tur+'_god').val(formatPara(Math.floor(god)));
            				$('#b70_'+itm.tur+'_perf').val(formatPara(itm.performans));
            				$("#b70_pod").val(genelOran);
            			}
        		    }
        		}else if(status == "error"){
        		    hataAc("Bir sorun oluştu.");
        	    }
    	    }
        );
    	$.post("<?=PREPATH.'post/planlama/planlamaPost.php?tur=getPlan' ?>",
            {
        		tklf_id	: <?=$tklf_id?>,
    	    },
    	    function(data,status){
        		if(status == "success"){
        		    var obj = JSON.parse(data);
        		    if (obj.hata == true) {
        				hataAc(obj.hataMesaj);
        		    }else{
            		    var item = JSON.parse(obj.icerik);
        				$('#b70_s_god').val(formatPara(item.god));
        				$('#b70_s_pod').val(formatPara(item.pod));
        				$('#b70_s_oran').val(Math.floor(((100*item.dfe)/item.pod)*100)/100);
        				$('#b70_s_dfe').val(formatPara(item.dfe));
        		    }
        		}else if(status == "error"){
        		    hataAc("Bir sorun oluştu.");
        	    }
    	    }
        );
    };

    function b70Ekle(tur){
		var oran  = $('#b70_'+tur+'_oran').val();
		var tutar = $('#b70_'+tur+'_tutar').val().replaceAll('.','').replaceAll(',','.');
		if (oran != '' && tutar != '') {
        	var link;
        	link = "<?=PREPATH.'post/planlama/b70Post.php?tur=b70Ekle' ?>";
        	$.post(link,
                {
            		id 			: $('#b70_'+tur+'_id').val(),
            		tklf_id		: <?=$tklf_id?>,
    				tur			: tur,
    				oran		: oran,
    				tutar		: tutar,
    				performans	: $('#b70_'+tur+'_perf').val().replaceAll('.','').replaceAll(',','.'),
        	    },
        	    function(data,status){
            		if(status == "success"){
            		    var obj = JSON.parse(data);
            		    if (obj.hata == true) {
            				hataAc(obj.hataMesaj);
            		    }else{
            		    	onayAc(obj.icerik);
            		    }
            		}else if(status == "error"){
            		    hataAc("Bir sorun oluştu.");
            	    }
        	    }
            );
		}else{
    		hataAc('Alanlar boş olamaz.');
		}
    }

    $("#b70_s_btn").click(function(){
    	var link = "<?=PREPATH.'post/planlama/b70Post.php?tur=b70sEkle' ?>";
    	$.post(link,
            {
        		id 		: <?=$plnm['id']?>,
        		tklf_id	: <?=$tklf_id?>,
				god		: $('#b70_s_god').val().replaceAll('.','').replaceAll(',','.'),
				pod		: $('#b70_s_pod').val().replaceAll('.','').replaceAll(',','.'),
				dfe		: $('#b70_s_dfe').val().replaceAll('.','').replaceAll(',','.'),
				nots	: $('#b70_s_nots').val(),
				degisik	: $('#b70_s_degisik').val(),
    	    },
    	    function(data,status){
        		if(status == "success"){
        		    var obj = JSON.parse(data);
        		    if (obj.hata == true) {
        				hataAc(obj.hataMesaj);
        		    }else{
        		    	onayAc(obj.icerik);
        		    }
        		}else if(status == "error"){
        		    hataAc("Bir sorun oluştu.");
        	    }
    	    }
        );
    });

    
</script>


