<?php 

$cmbKD  = mkConfig::KARAR_MADDELERI;
$cmbKYK = mkConfig::KAYIK_MADDELERI;
$cmbKYK = mkConfig::KAYIK_MADDELERI;
$cmbEKP = array(); //mkConfig::EKIP_MADDELERI;
foreach (mkConfig::EKIP_MADDELERI as $g){
    array_push($cmbEKP,$g[1]);
}


$cmbGRV =  array();//mkConfig::GOREV_MADDELERI;
foreach (mkConfig::GOREV_MADDELERI as $g){
    array_push($cmbGRV,$g[1]);
}

$cmbPZSYM =  array();//mkConfig::GOREV_MADDELERI;
foreach (mkConfig::POZISYON_MADDELERI as $g){
    array_push($cmbPZSYM,$g[1]);
}

$dntList = mkSoa::getDenetciList($_GET['id']);
$szlsm    = Crud::getSqlTek(new Sozlesme(), Sozlesme::GET_TEKLIF_ID, array('tklf_id'=>$tklf_id));


?>

<style>
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

<div class="card shadow mb-1">
    <div class="card-header py-3">
    	<a href="#" onclick="mk0KisibilgileriKaydet();" class="my-0 btn btn-primary float-right" >Kaydet</a>
        <h6 class="mt-2 font-weight-bold text-primary">Kimlik Bilgileri</h6>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-xl-4">
                <div class="row mb-2">
                	<div class="col-lg-4 font-weight-bold text-gray-800 align-self-center  text-right">Denetime Tabi Olma Nedeni :</div>
                	<div class="col-lg-8" id="dznl_dton_id2"><?=$tklf['dton_id']['aciklama'] ?></div>
                </div>
            	<div class="row mb-2">
                	<div class="col-lg-4 font-weight-bold text-gray-800 align-self-center  text-right">Vergi Kimlik No :</div>
                	<div class="col-lg-8" id="dznl_dton_id2"><?=$tklf['musteri_id']['vergi_no'] ?></div>
                </div>
                <div class="row mb-2">
                	<div class="col-lg-4 font-weight-bold text-gray-800 align-self-center  text-right">Ticaret Sicil No :</div>
                	<div class="col-lg-8" id="dznl_dton_id2"><?=$tklf['musteri_id']['sicil_no'] ?></div>
                </div>
                <div class="row mb-2">
                	<div class="col-lg-4 font-weight-bold text-gray-800 align-self-center  text-right">İl :</div>
                	<div class="col-lg-8" id="dznl_dton_id2"><?=$tklf['musteri_id']['il_id']['adi'] ?></div>
                </div>
                <div class="row mb-2">
                	<div class="col-lg-4 font-weight-bold text-gray-800 align-self-center  text-right">İlçe :</div>
                	<div class="col-lg-8" id="dznl_dton_id2"><?= $tklf['musteri_id']['ilce_id'] != null ? $tklf['musteri_id']['ilce_id']['adi'] : '' ?></div>
                </div>
            </div>
            <div class="col-xl-8">
                <div class="row mb-2">
            		<div class="col-lg-4 font-weight-bold text-gray-800 align-self-center  text-right">Şirketin, Kararın Hangi Maddesi Uyarınca Denetim Kapsamında Olduğunu Seçiniz:</div>
            		<div class="col-lg-8">
                		<select class=" custom-select form-control " id="mk0_kd">
                			<option class="dznl_val" value="" selected="selected">Seçiniz</option>
                			<?php 
                			foreach ($cmbKD as $v){
                			    echo '<option class="dznl_val" value="'.$v.'"'. ((trim($v) === trim($mk0->denetim_maddesi->deger)) ? 'selected="selected"' : '') .'>'.$v.'</option>';
            			     }
                            ?>
                        </select>
            		</div>
            	</div>
            	
            	<div class="row mb-2">
                	<div class="col-lg-4 font-weight-bold text-gray-800 align-self-center  text-right">Müşteri Halka Açık Bir Şirket mi?	</div>
                	<div class="col-lg-2 text-center">
                		<select class=" custom-select form-control " id="chk_halk_Ack" onchange="halk()">
                			<option class="dznl_val" value=""  <?= ($mk0->halka_acik->deger == null      ? 'selected="selected"' : '' ) ?>>Seçiniz</option>
                			<option class="dznl_val" value="E" <?= ($mk0->halka_acik->deger != null && $mk0->halka_acik->deger !='H'  ? 'selected="selected"' : '' ) ?>>Evet</option>
                			<option class="dznl_val" value="H" <?= ($mk0->halka_acik->deger=='H'       ? 'selected="selected"' : '' ) ?>>Hayır</option>
            			</select>
        			</div>
        			<div class="col-lg-2 font-weight-bold text-gray-800 align-self-center  text-right">Oran % :</div>
        			<div class="col-lg-4">
        				<input id="halk_ack" type="text" class="form-control form-control-user" value="<?=$mk0->halka_acik->deger == 'H' ? '' : $mk0->halka_acik->deger ?>">
        			</div>
    			</div>
            	<div class="row mb-2">
                	<div class="col-lg-4 font-weight-bold text-gray-800 align-self-center  text-right">Müşteri KAYİK mi?</div>
                	
                	<div class="col-lg-2 text-center">
                		<select class=" custom-select form-control " id="chk_kayik" onchange="kayik()">
                			<option class="dznl_val" value=""  <?= (($mk0->kayik->deger==null) ? 'selected="selected"' : '' ) ?>>Seçiniz</option>
                			<option class="dznl_val" value="E" <?= (($mk0->kayik->deger!=null && $mk0->kayik->deger !='H') ? 'selected="selected"' : '' ) ?>>Evet</option>
                			<option class="dznl_val" value="H" <?= (($mk0->kayik->deger=='H')  ? 'selected="selected"' : '' ) ?>>Hayır</option>
            			</select>
        			</div>
                	<div class="col-lg-6">
                		<select class=" custom-select form-control " id="kayik"  onchange="kayikDiger()">
                			<option class="dznl_val" value="" selected="selected">Seçiniz</option>
                			<?php 
                			foreach ($cmbKYK as $v){
                			    echo '<option class="dznl_val" value="'.$v.'"'. ((trim($v) === trim($mk0->kayik->deger)) ? 'selected="selected"' : '') .'>'.$v.'</option>';
            			     }
                            ?>
                        </select>
            		</div>
                </div>
                <div class="row mb-2">
                	<div class="col-lg-4 font-weight-bold text-gray-800 align-self-center  text-right">KAYİK Diğer :</div>
            		<div class="col-lg-8">
        				<input id="kayik_ack" type="text" class="form-control form-control-user" value="<?=$mk0->kayik_ack->deger ?>">
        			</div>
            	</div>
            </div>
        </div>
    </div>
</div>

<div class="card shadow mb-1">
    <div class="card-header">
    	<h6 class="m-0 font-weight-bold text-primary">Denetim Ekibine İlişkin Bilgiler</h6>
    </div>
    <div class="card-body">
        <div class="row">
        	<div class="col-xl-4">
        		<div class="font-weight-bold text-gray-800 align-self-center  text-center">Denetlenecek Finansal Tabloların Ait Olduğu Hesap Döneminin Başlangıç ve Bitiş Tarihini</div>
            </div>
        	<div class="col-xl-4">
        		<div class="font-weight-bold text-gray-800 align-self-center  text-center">Sözleşme Tarihi</div>
            </div>
        	<div class="col-xl-4">
        		<div class="font-weight-bold text-gray-800 align-self-center  text-center">Toplam Denetim Ücreti (KDV Hariç) </div>
            </div>
        </div>
        <div class="row">
        	<div class="col-xl-4">
        		<div class="align-self-center  text-center"><?=BaseSoa::strDateToStr($tklf['donem_bas_trh']).' - '.BaseSoa::strDateToStr($tklf['donem_bts_trh']) ?></div>
            </div>
        	<div class="col-xl-4">
        		<div class="align-self-center  text-center"><?= $szlsm->denetim_imza_trh->deger != '' ? BaseSoa::strDateToStr($szlsm->denetim_imza_trh->deger) : '' ?></div>
            </div>
        	<div class="col-xl-4">
        		<div class="align-self-center  text-center"><?=number_format($tklf['tutar']).' '.$tklf['para_birimi_id']['sembol']?></div>
            </div>
        </div>
    </div>
</div>

<!-- 
-Sorumlu Denetçi
-Denetçi
-Yardımcı Denetçi
 -->
<?php 
include 'mk_d_0_denetci.php';
?>


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




<script>

function mk0KisibilgileriKaydet() {

	if ($('#chk_kayik').val() == 'E' && $('#kayik').val() == '') {
		hataAc("'Müşteri KAYİK mi?' sorusuna 'Evet' yanıtı seçilmişse diğer alan boş olamaz.");
		return;
	}
	if ($('#chk_kayik').val() == 'E' && $('#kayik').val() == 'Diğer' && $('#kayik_ack').val() == '') {
		hataAc("'Müşteri KAYİK mi?' sorusuna 'Evet' ve 'Diğer' seçilmişse 'KAYİK Diğer' alanı boş olmaz.");
		return;
	}
	
	if ($('#chk_halk_Ack').val() == 'E' && $('#halk_ack').val() == '') {
		hataAc("'Müşteri Halka Açık Bir Şirket mi?' sorusuna 'Evet' yanıtı seçilmişse oran girmek zorundasınız.");
		return;
	}
	
	$.post("<?=PREPATH.'post/mk0Post.php?tur=kimlikBilgiler&islem=kaydet'?>",
        {
    		id			: <?= $mk0->id->deger?>,
    		kd   		: $('#mk0_kd').val(),
    		halk  		: ($('#chk_halk_Ack').val() == 'H' 	? 'H' : $('#halk_ack').val()),
    		kayik 		: ($('#chk_kayik').val() == 'H' 	? 'H' : $('#kayik').val()),
    		kayik_ack	: $('#kayik_ack').val()
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


function kayik(){
	var val = document.getElementById("chk_kayik").value;
	if (val == 'E') {
		$("#kayik").prop( "disabled", false );
	}else{
		$("#kayik").prop( "disabled", true );
    	$("#kayik").val(null);
    	kayikDiger();
	}
}

function kayikDiger(){
	if ($( "#kayik" ).val() == '<?=mkConfig::KAYIK_DIGER ?>') {
  		$("#kayik_ack").prop( "disabled", false );
	}else{
  		$("#kayik_ack").prop( "disabled", true ); 
  		$("#kayik_ack").val(null); 
	}
}

function halk(){
	var val = document.getElementById("chk_halk_Ack").value;
	$("#halk_ack").val(null);
	if (val == 'E') {
		$("#halk_ack").prop( "disabled", false );
	}else{
    	$("#halk_ack").prop( "disabled", true );
	}
}

<?php if ($mk0->halka_acik->deger==null) {?>
	halk();
<?php }?>
kayik();
kayikDiger();
</script>