<style>
    .d10Table  td {
        vertical-align: middle;
    }
</style>
<div class="card shadow mb-1">
    <div class="card-body">
    	<nav >
          <div class="nav nav-tabs "	id="nav-tab" role="tablist">
            <a class="nav-item nav-link mk mr-1 text-center"	data-toggle="tab" id="D101_btn"	role="tab" href="#nav-D101" onclick="d101init()" >D10.01</a>
            <a class="nav-item nav-link mk mr-1 text-center" 	data-toggle="tab" id="D102_btn"	role="tab" href="#nav-D102" >D10.02</a>
            <a class="nav-item nav-link mk mr-1 text-center" 	data-toggle="tab" id="D103_btn"	role="tab" href="#nav-D103" >D10.03</a>
          </div>
        </nav>
        <div class="border">
            <div class="tab-content m-3" id="nav-tabContent">
            	<div class="tab-pane fade show active"	id="nav-D101" 	role="tabpanel" ></div>
              	<div class="tab-pane fade" 				id="nav-D102" 	role="tabpanel" ></div>
              	<div class="tab-pane fade" 				id="nav-D103" 	role="tabpanel" ></div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    function d101init(){
        //$('#nav-D101 table').remove();
		var table = $('#nav-D101');
        table.find("table").remove();
    	$.get( "<?= PREPATH.'post/denetimSonucPost.php?tur=riskler&tklf_id='.$tklf_id?>", function(data, status){
    		if(status == "success"){
    			var objx 	= JSON.parse(data);
    			const obj 	= JSON.parse(objx.icerik);
    			
    			var str = '<table class="table table-bordered mb-0 d10Table">'+
        			'<thead>'+
        				'<tr >'+
            				'<td colspan="3"></td>'+
            				'<td class="table-warning" >Yeterli ve Uygun Denetim Kanıtı Elde Edildi mi?</td>'+
            				'<td class="table-warning" >Bulgu Var mı?</td>'+
            				'<td class="table-warning" >Bulgunun Tutarı</td>'+
            				'<td class="table-success" >Yönetimden Düzeltme Talep Edildi mi?</td>'+
            				'<td class="table-success" >Yönetim Finansal Tabloları Düzeltti mi?</td>'+
            				'<td class="table-success" >Düzeltilmemiş Denetim Bulguları</td>'+
            				'<td class="table-success" >Finansal Tablolara Muhtemel Etkisi?</td>'+
            				'<td class="table-success" >Kaydet</td>'+
            			'</tr>'+
            		'</thead>'
    			'<tbody>';
    			
    			for (const [key, value] of Object.entries(obj)) {
        			var item = value; 
        			str += '<tr class="text-primary "><td colspan="11" ><b>'+item.rlKod + ' ) ' + item.rlAdi+'</b></td></tr>';
					for (var x = 0; x < item.kods.length; x++) {
						var kod = item.kods[x];
						var tutar = '0';
						var bulgu = 'HAYIR';
						if (kod.tutar != null && kod.tutar > 0) {
							tutar = kod.tutar;
							bulgu = 'EVET'; 
						}
						str +=
							'<tr>'+
                        		'<td style="width: 20%"><b>'+kod.rpKod+'</b> '+kod.rpAdi+'</td>'+
                        		'<td style="width: 8%" >'+kod.kaynak+'</td>'+
                        		'<td style="width: 8%" >'+kod.duzey+'</td>'+
                        		'<td style="width: 8%" >'+(kod.kanit == 'E' ? 'EVET' : 'HAYIR' )+'</td>'+
                        		'<td style="width: 8%" >'+bulgu+'</td>'+
                        		'<td style="width: 8%" >'+tutar+'</td>'+
                        		'<td style="width: 9%" >'+
                            		'<select class=" custom-select form-control " id="chk_talep_'+kod.id+'" >'+
                            			'<option value=""  >Seçiniz</option>'+
                            			'<option value="E" '+(kod.talep_edildi == 'E' ? 'selected' : '' )+' >Evet</option>'+
                            			'<option value="H" '+(kod.talep_edildi == 'H' ? 'selected' : '' )+' >Hayır</option>'+
                        			'</select>'+
                				'</td>'+
                        		'<td style="width: 9%" >'+
                            		'<select class=" custom-select form-control " id="chk_tablo_'+kod.id+'" >'+
                            			'<option value=""  >Seçiniz</option>'+
                            			'<option value="E" '+(kod.tablo_duzelt == 'E' ? 'selected' : '' )+' >Evet</option>'+
                            			'<option value="H" '+(kod.tablo_duzelt == 'H' ? 'selected' : '' )+' >Hayır</option>'+
                        			'</select>'+
                        		'</td>'+
                        		'<td style="width: 9%" ><input id="bulgu_'+kod.id+'" type="number" class="form-control form-control-user" value="'+kod.denetim_bulgu+'"></td>'+
                        		'<td style="width: 9%" >'+
                            		'<select class=" custom-select form-control " id="chk_muhtemel_'+kod.id+'" >'+
                            			'<option value=""  >Seçiniz</option>'+
                            			'<option value="E" '+(kod.muhtemel_etki == 'E' ? 'selected' : '' )+' >Yaygın</option>'+
                            			'<option value="H" '+(kod.muhtemel_etki == 'H' ? 'selected' : '' )+' >Yaygın Değil</option>'+
                        			'</select>'+
                        		'</td>'+
                        		'<td style="width: 4%" >'+
                        			'<a id="asd" href="#" onclick="riskPlanKaydet('+kod.id+');" class="my-0 btn btn-primary " >Kaydet</a>'
                        		'</td>'+
                          	'</tr>';
					}
    			}
				str += '</tbody></table>';
    			table.append(str);
    		}
    	});
    }

    function riskPlanKaydet(id){
    	$.post( "<?php echo PREPATH.'post/denetimSonucPost.php?tur=planRiskKaydet'?>",
    		{
    			id		: id,
    			talep 	: $('#chk_talep_'+id).val(),
    			tablo 	: $('#chk_tablo_'+id).val(),
    			denetim	: $('#bulgu_'+id).val(),
    			muhtemel: $('#chk_muhtemel_'+id).val()
        	},
    	    function(data, status){
    			if(status == "success"){
    				var objx = JSON.parse(data);
    			    if (objx.hata == false) {
    			        onayAc('Düzenlendi.');
    			    }else{
    			    	hataAc(objx.hataMesaj);
    			    }
    			}else if(status == "error"){
        		    hataAc("Bir sorun oluştu.");
        	    }
    	    }
        );
    }
</script>