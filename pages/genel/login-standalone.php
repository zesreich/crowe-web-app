<?php
/**
 * Login Sayfası - Standalone
 * Bağımsız login sayfası
 */
session_start();

// Eğer zaten giriş yapılmışsa ana sayfaya yönlendir
if (isset($_SESSION['login']) && $_SESSION['login']['id'] != -1) {
    header('Location: ../index.php');
    exit();
}

?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Crowe HSY - Giriş Sayfası">
    <meta name="author" content="">
    <title>CROWE HSY - Giriş</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    
    <!-- Custom CSS -->
    <link href="../../front/css/sb-admin-2.min.css" rel="stylesheet">
    
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .login-card {
            background: white;
            border-radius: 10px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.2);
            padding: 3rem;
            max-width: 450px;
            width: 100%;
        }
        .login-icon {
            width: 80px;
            height: 80px;
            margin-bottom: 20px;
            color: #4e73df;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5">
                <div class="login-card">
                    <div class="text-center mb-4">
                        <svg class="login-icon" fill="currentColor" viewBox="0 0 16 16">
                            <path d="M12.75 3a.75.75 0 0 0-.75-.75h-2.5a.75.75 0 0 0 0 1.5h2.5a.75.75 0 0 0 .75-.75ZM15 3.75a.75.75 0 0 0-.75-.75h-.5a.75.75 0 0 0 0 1.5h.5a.75.75 0 0 0 .75-.75ZM.75 3a.75.75 0 0 0-.75.75v8.5a.75.75 0 0 0 .75.75h14.5a.75.75 0 0 0 .75-.75v-8.5a.75.75 0 0 0-.75-.75H.75ZM1.5 4.5h13v7H1.5v-7ZM8.75 7.5a.75.75 0 0 0 0 1.5h2.5a.75.75 0 0 0 0-1.5h-2.5ZM5 7.5a.75.75 0 0 0 0 1.5h.75a.75.75 0 0 0 0-1.5H5Z"/>
                        </svg>
                        <h1 class="h4 text-gray-900 mb-2">HOŞGELDİNİZ!</h1>
                        <h2 class="text-primary mb-4">CROWE HSY</h2>
                        <p class="text-muted">Mali Müşavir Yönetim Platformu</p>
                    </div>
                    
                    <form id="loginForm">
                        <input type="hidden" id="csrf_token" value="<?php 
                            if (file_exists('../../helpers/SecurityHelper.php')) {
                                include_once '../../helpers/SecurityHelper.php';
                                if (class_exists('SecurityHelper')) {
                                    echo SecurityHelper::generateCSRFToken();
                                }
                            }
                        ?>">
                        
                        <div class="form-group">
                            <input type="text" class="form-control form-control-user" id="loginKul" placeholder="Kullanıcı adınız..." required autocomplete="username">
                        </div>
                        
                        <div class="form-group">
                            <input type="password" class="form-control form-control-user" id="loginPas" placeholder="Şifreniz..." required autocomplete="current-password">
                        </div>
                        
                        <div class="form-group" id="twoFactorGroup" style="display: none;">
                            <input type="text" class="form-control form-control-user" id="login2FA" placeholder="2FA Kodu (6 haneli)" maxlength="6" pattern="[0-9]{6}">
                            <small class="text-muted">E-posta adresinize gönderilen 6 haneli kodu giriniz.</small>
                        </div>
                        
                        <a class="btn btn-primary btn-user btn-block" href="#" id="loginBtn">
                            <i class="fas fa-sign-in-alt"></i> Giriş Yap
                        </a>
                        
                        <hr>
                    </form>
                    
                    <div class="text-center">
                        <a class="small" href="#">Şifremi unuttum?</a>
                    </div>
                    
                    <div class="text-center mt-3">
                        <a href="../../index.php" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left"></i> Ana Sayfaya Dön
                        </a>
                    </div>
                    
                    <!-- Hata/Başarı Mesajları -->
                    <div id="hata" class="alert alert-danger mt-3" style="display: none;">
                        <div id="hataMesaji"></div>
                    </div>
                    <div id="onay" class="alert alert-success mt-3" style="display: none;">
                        <div id="onayMesaji"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        $(function(){
            var csrfToken = $("#csrf_token").val();
            var twoFactorRequired = false;
            
            function hataAc(icerik){
                $("#hataMesaji").text(icerik);
                $("#hata").show();
                $("#onay").hide();
            }
            
            function onayAc(icerik){
                $("#onayMesaji").text(icerik);
                $("#onay").show();
                $("#hata").hide();
            }
            
            $("#loginBtn").click(function(e){
                e.preventDefault();
                
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

                var url = '../../post/kullaniciPost.php?tur=login&kul='+encodeURIComponent(kul.val())+'&pas='+encodeURIComponent(pas.val());
                if (twoFactorCode) {
                    url += '&twofa='+encodeURIComponent(twoFactorCode);
                }
                url += '&csrf_token='+encodeURIComponent(csrfToken);
                
                $.get(url, function(data, status){
                    if(status == "success"){
                        var obj = JSON.parse(data);
                        if (obj.hata == true) {
                            if (obj.hataMesaj && obj.hataMesaj.indexOf('Çok fazla deneme') !== -1) {
                                var resetTime = obj.reset_time ? new Date(obj.reset_time * 1000) : null;
                                var message = obj.hataMesaj;
                                if (resetTime) {
                                    message += ' ' + Math.ceil((resetTime - new Date()) / 1000) + ' saniye sonra tekrar deneyebilirsiniz.';
                                }
                                hataAc(message);
                            } else if (obj.twoFactorRequired) {
                                twoFactorRequired = true;
                                $("#twoFactorGroup").show();
                                $("#login2FA").focus();
                                onayAc("2FA kodu e-posta adresinize gönderildi. Lütfen kodu giriniz.");
                            } else {
                                hataAc(obj.hataMesaj);
                            }
                        }else{
                            window.location.replace('../../index.php');
                        }
                    }else if(status == "error"){
                        hataAc("Bir sorun oluştu.");
                    }
                });
            });
        });
    </script>
</body>
</html>
