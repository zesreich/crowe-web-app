<?php
$pId = 999; // Yeni sayfa ID'si
include_once '../../First.php';

// Kullanıcı sadece kendi 2FA ayarlarını görebilir
if ($_SESSION['login']['id'] == -1) {
    hata('Giriş yapmanız gerekmektedir.', PREPATH);
}

include_once PREPATH . 'header.php';

// 2FA durumunu kontrol et
$kullanici = Crud::getById(new Kullanici(), $_SESSION['login']['id']);
$twoFactorEnabled = isset($kullanici->twofa_secret) && !empty($kullanici->twofa_secret->deger);

?>
<div class="row justify-content-md-center">
    <div class="col-md-8 col-lg-6">
        <div class="card shadow">
            <div class="card-header">
                <h6 class="m-0 font-weight-bold text-white">İki Faktörlü Kimlik Doğrulama (2FA)</h6>
            </div>
            <div class="card-body">
                <?php if ($twoFactorEnabled): ?>
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle"></i> 2FA aktif.
                    </div>
                    <div class="form-group">
                        <button class="btn btn-danger" onclick="disable2FA()">
                            <i class="fas fa-times-circle"></i> 2FA'yı Devre Dışı Bırak
                        </button>
                    </div>
                <?php else: ?>
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle"></i> 2FA devre dışı.
                    </div>
                    <div class="form-group">
                        <label>2FA'yı etkinleştirmek için şifrenizi girin:</label>
                        <input type="password" class="form-control" id="verifyPassword" placeholder="Şifreniz">
                    </div>
                    <div class="form-group">
                        <button class="btn btn-primary" onclick="enable2FA()">
                            <i class="fas fa-shield-alt"></i> 2FA'yı Etkinleştir
                        </button>
                    </div>
                <?php endif; ?>
                
                <hr>
                <h6>2FA Nasıl Çalışır?</h6>
                <p class="text-muted">
                    2FA aktif olduğunda, giriş yaparken şifrenize ek olarak e-posta adresinize gönderilen 6 haneli bir kod girmeniz gerekecektir.
                    Bu, hesabınızın güvenliğini artırır.
                </p>
            </div>
        </div>
    </div>
</div>

<script>
function enable2FA() {
    var password = $("#verifyPassword").val();
    if (!password) {
        hataAc("Lütfen şifrenizi girin.");
        return;
    }
    
    $.post('<?=PREPATH?>post/kullaniciPost.php?tur=enable2FA', {
        password: password,
        csrf_token: '<?=SecurityHelper::generateCSRFToken()?>'
    }, function(data) {
        var obj = JSON.parse(data);
        if (obj.hata) {
            hataAc(obj.hataMesaj);
        } else {
            onayAc("2FA başarıyla etkinleştirildi.");
            setTimeout(function() {
                location.reload();
            }, 2000);
        }
    });
}

function disable2FA() {
    if (!confirm("2FA'yı devre dışı bırakmak istediğinizden emin misiniz?")) {
        return;
    }
    
    $.post('<?=PREPATH?>post/kullaniciPost.php?tur=disable2FA', {
        csrf_token: '<?=SecurityHelper::generateCSRFToken()?>'
    }, function(data) {
        var obj = JSON.parse(data);
        if (obj.hata) {
            hataAc(obj.hataMesaj);
        } else {
            onayAc("2FA devre dışı bırakıldı.");
            setTimeout(function() {
                location.reload();
            }, 2000);
        }
    });
}
</script>

<?php include (PREPATH.'footer.php'); ?>
