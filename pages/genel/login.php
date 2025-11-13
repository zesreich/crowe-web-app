<?php
$pId = 114;
include_once '../../First.php';
if ($_SESSION['login']['id'] != -1){
    hata('Zaten giriş yaptınız.',PREPATH);
}

include_once PREPATH . 'header.php';

?>
<div class="row justify-content-md-center">
	<div class="col-md-6 col-lg-6 col-xl-4">
	<div class="card shadow">
		<div class="p-5">
			<div class="text-center">
				<h1 class="h4 text-gray-900 mb-4">HOŞGELDİNİZ!</h1>
			</div>
			<form class="user" id="loginForm">
				<input type="hidden" id="csrf_token" value="<?php 
					if (class_exists('SecurityHelper')) {
						echo SecurityHelper::generateCSRFToken();
					}
				?>">
				<div class="form-group">
					<input type="email" class="form-control form-control-user" 		id="loginKul"  	placeholder="Kullanıcı adınız..." required autocomplete="username">
				</div>
				<div class="form-group">
					<input type="password" class="form-control form-control-user" 	id="loginPas"	placeholder="Şifreniz..." required autocomplete="current-password">
				</div>
				<div class="form-group" id="twoFactorGroup" style="display: none;">
					<input type="text" class="form-control form-control-user" id="login2FA" placeholder="2FA Kodu (6 haneli)" maxlength="6" pattern="[0-9]{6}">
					<small class="text-muted">E-posta adresinize gönderilen 6 haneli kodu giriniz.</small>
				</div>
				<a class="btn btn-primary btn-user btn-block text-gray-100"			id="loginBtn"	>Giriş Yap</a>
				<hr>
			</form>
			<div class="text-center">
				<a class="small" href="forgot-password.html">Şifremi unuttum?</a>
			</div>
		</div>
		</div>
	</div>
</div>
<script >
	$(function(){
		var csrfToken = $("#csrf_token").val();
		var twoFactorRequired = false;
		
		$("#loginBtn").click(function(){
			var kul = $("#loginKul");
			var pas = $("#loginPas");
			var twoFactorCode = $("#login2FA").val();
			
			if (kul.val() == null || kul.val() == "" ||
				pas.val() == null || pas.val() == "") {
    			hataAc("'Kullanici Adi' yada 'Şifre' boş olamaz.");
    			return;
			}
			
			// 2FA kontrolü
			if (twoFactorRequired && (!twoFactorCode || twoFactorCode.length !== 6)) {
				hataAc("2FA kodu gereklidir (6 haneli).");
				return;
			}

			// Rate limiting kontrolü
			var url = '<?=PREPATH?>post/kullaniciPost.php?tur=login&kul='+encodeURIComponent(kul.val())+'&pas='+encodeURIComponent(pas.val());
			if (twoFactorCode) {
				url += '&twofa='+encodeURIComponent(twoFactorCode);
			}
			url += '&csrf_token='+encodeURIComponent(csrfToken);
			
			$.get(url, function(data, status){
				if(status == "success"){
					console.log(data);
				    var obj = JSON.parse(data);
				    if (obj.hata == true) {
						// Rate limiting hatası kontrolü
						if (obj.hataMesaj && obj.hataMesaj.indexOf('Çok fazla deneme') !== -1) {
							var resetTime = obj.reset_time ? new Date(obj.reset_time * 1000) : null;
							var message = obj.hataMesaj;
							if (resetTime) {
								message += ' ' + Math.ceil((resetTime - new Date()) / 1000) + ' saniye sonra tekrar deneyebilirsiniz.';
							}
							hataAc(message);
						} else if (obj.twoFactorRequired) {
							// 2FA gerekiyor
							twoFactorRequired = true;
							$("#twoFactorGroup").show();
							$("#login2FA").focus();
							onayAc("2FA kodu e-posta adresinize gönderildi. Lütfen kodu giriniz.");
						} else {
							hataAc(obj.hataMesaj);
						}
				    }else{
						window.location.replace('<?=PREPATH ?>')
				    }
				}else if(status == "error"){
				    hataAc("Bir sorun oluştu.");
			    }
			});

		});
	});
</script>
<?php include (PREPATH.'footer.php'); ?>
