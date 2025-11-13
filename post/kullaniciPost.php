<?php 
include_once 'basePost.php';
include_once __DIR__.'/../helpers/SecurityHelper.php';

if ($_GET['tur']== 'login'){
    try {
        // CSRF token kontrolü
        $csrfToken = isset($_GET['csrf_token']) ? $_GET['csrf_token'] : '';
        if (!SecurityHelper::verifyCSRFToken($csrfToken)) {
            hataDondur("Geçersiz güvenlik token'ı. Lütfen sayfayı yenileyin.");
        }
        
        // Rate limiting kontrolü
        $rateLimit = SecurityHelper::checkRateLimit('login', 5, 300); // 5 deneme, 5 dakika
        if (!$rateLimit['allowed']) {
            $jsonArray["hata"] = TRUE;
            $jsonArray["hataMesaj"] = "Çok fazla başarısız deneme. Lütfen " . ceil($rateLimit['remaining_time'] / 60) . " dakika sonra tekrar deneyin.";
            $jsonArray["reset_time"] = $rateLimit['reset_time'];
            echo json_encode($jsonArray, JSON_UNESCAPED_UNICODE);
            exit();
        }
        
        // Input validation
        $kul = isset($_GET['kul']) ? SecurityHelper::sanitize($_GET['kul']) : '';
        $pas = isset($_GET['pas']) ? $_GET['pas'] : '';
        $twoFactorCode = isset($_GET['twofa']) ? SecurityHelper::sanitize($_GET['twofa']) : '';
        
        if (empty($kul) || empty($pas)) {
            hataDondur("Kullanıcı adı ve şifre boş olamaz.");
        }
        
        // Kullanıcıyı bul (şifre olmadan)
        $sonuc = Crud::getSqlTek(new Kullanici(), 
            array("SELECT * FROM ".Config::DB_DATABASE.".kullanici WHERE kullanici_adi=:_kAdi", array('kAdi'=>Base::KELIME)), 
            array('kAdi'=>$kul)
        );
        
        if ($sonuc==null){
            hataDondur("Kullanıcı Adi veya Şifre yanlış.");
        }else{
            // Şifre doğrulama (bcrypt veya MD5 geriye dönük uyumluluk)
            if (!SecurityHelper::verifyPassword($pas, $sonuc->sifre_md5->deger)) {
                hataDondur("Kullanıcı Adi veya Şifre yanlış.");
            }
            
            // 2FA kontrolü (eğer kullanıcıda 2FA aktifse)
            // Not: 2FA secret alanı entity'de olmalı, şimdilik basit kontrol yapıyoruz
            $twoFactorEnabled = isset($sonuc->twofa_secret) && !empty($sonuc->twofa_secret->deger);
            
            if ($twoFactorEnabled) {
                // 2FA kodu bekleniyor
                if (empty($twoFactorCode)) {
                    // 2FA kodu gönder (e-posta ile)
                    $twoFactorCodeGenerated = SecurityHelper::generateTOTP($sonuc->twofa_secret->deger);
                    // TODO: E-posta ile kod gönder
                    // mailSoa::send2FACode($sonuc->email->deger, $twoFactorCodeGenerated);
                    
                    $jsonArray["hata"] = FALSE;
                    $jsonArray["twoFactorRequired"] = true;
                    $jsonArray["icerik"] = "2FA kodu gereklidir.";
                    echo json_encode($jsonArray, JSON_UNESCAPED_UNICODE);
                    exit();
                }
                
                // 2FA kodu doğrula
                if (!SecurityHelper::verifyTOTP($twoFactorCode, $sonuc->twofa_secret->deger)) {
                    hataDondur("2FA kodu yanlış veya süresi dolmuş.");
                }
            }
            
            // Eğer MD5 hash ise, bcrypt'e güncelle
            if (strlen($sonuc->sifre_md5->deger) === 32 && ctype_xdigit($sonuc->sifre_md5->deger)) {
                $sonuc->sifre_md5->deger = SecurityHelper::hashPassword($pas);
                Crud::update($sonuc);
            }
            
            // Rate limit'i sıfırla (başarılı giriş)
            SecurityHelper::resetRateLimit('login');
            mesajSet('onay','Giriş yapıldı.');
            $_SESSION['login']['id']                = $sonuc->id->deger;
            $_SESSION['login']['kullanici_adi']     = $sonuc->kullanici_adi->deger;
            $_SESSION['login']['ad']                = $sonuc->ad->deger;
            $_SESSION['login']['soyad']             = $sonuc->soyad->deger;
            $_SESSION['login']['grup']              = $sonuc->grup_id->deger;
            $_SESSION['login']['tur']               = $sonuc->grup_id->ref->deger->kullanici_tur_id->deger;
            $_SESSION['login']['grup_adi']          = $sonuc->grup_id->ref->deger->adi->deger;
            $_SESSION['login']['tur_adi']           = $sonuc->grup_id->ref->deger->kullanici_tur_id->ref->deger->adi->deger;
            $_SESSION['login']['yetki']             = yetkiSoa::grupYetkiler($_SESSION['login']['grup']);
            if (KullaniciTurPrm::MUSTERI == $_SESSION['login']['tur']) {
                $mstr  = Crud::getSqlTek(new KullaniciMusteri(), KullaniciMusteri::FIND_KULLANICI, array('id'=>$_SESSION['login']['id']));
                if ($mstr != null){
                    $_SESSION['login']['musteri_id'] = Base::basitS($mstr)['musteri_id']['id'];
                }else{
                    $_SESSION['login']['musteri_id'] = null;
                }
            } else if (KullaniciTurPrm::ISORTAGI == $_SESSION['login']['tur']) {
                $mstr  = Crud::getSqlTek(new KullaniciIsortagi(), KullaniciIsortagi::FIND_KULLANICI, array('id'=>$_SESSION['login']['id']));
                if ($mstr != null){
                    $_SESSION['login']['isortagi_id'] = Base::basitS($mstr)['isortagi_id']['id'];
                }else{
                    $_SESSION['login']['isortagi_id'] = null;
                }
            }else{
                $_SESSION['login']['musteri_id']    = null;
                $_SESSION['login']['isortagi_id']   = null;
            }
            cevapDondur("Tamam");
        }
    } catch (Exception $e) {
        hataDondur($e);
    }
}else if ($_GET['tur']== 'logout'){
    try {
        session_destroy();
        cevapDondur("Tamam");
    } catch (Exception $e) {
        hataDondur($e);
    }
}else if ($_GET['tur']== 'create'){
    if (!yetkiSoa::yetkiVarmi(yetkiConfig::KULLANICILAR_KAYDET_DUZENLE)){
        hataDondur('Kullanıcı kayıt ve düzenleme yetkiniz bulunmamaktadır.',PREPATH);
    }
    // Input validation
    $validation = SecurityHelper::validateInput($_POST, [
        'kullanici_adi' => ['required' => true, 'type' => 'string', 'label' => 'Kullanıcı Adı', 'min' => 3, 'max' => 50],
        'ad' => ['required' => true, 'type' => 'string', 'label' => 'Ad', 'min' => 2, 'max' => 50],
        'soyad' => ['required' => true, 'type' => 'string', 'label' => 'Soyad', 'min' => 2, 'max' => 50],
        'email' => ['required' => false, 'type' => 'email', 'label' => 'E-posta'],
        'telefon' => ['required' => false, 'type' => 'string', 'label' => 'Telefon', 'max' => 20]
    ]);
    
    if (!$validation['valid']) {
        hataDondur(implode(' ', $validation['errors']));
    }
    
    $tbl = new Kullanici();
    foreach ($validation['data'] as $key => $value){
        if (isset($tbl->$key)) {
            $tbl->$key->deger = $value;
        }
    }
    
    // Şifre oluşturma (varsayılan)
    $tbl->sifre->deger = strtolower(substr($tbl->kullanici_adi->deger, 0,1).substr($tbl->ad->deger, 0,1).substr($tbl->soyad->deger, 0,1));
    // Bcrypt hash kullan
    $tbl->sifre_md5->deger = SecurityHelper::hashPassword($tbl->sifre->deger);
    $result = Crud::save($tbl);
    if ($result==1){
        if (isset($_GET['mesaj'])){
            mesajSet('onay', 'Kaydetme işlemi tamamlandı.');
        }
        cevapDondur("Tamam");
    }else{
        hataDondur($result);
    }
}else if ($_GET['tur']== 'update'){
    if (!yetkiSoa::yetkiVarmi(yetkiConfig::KULLANICILAR_KAYDET_DUZENLE)){
        hataDondur('Kullanıcı kayıt ve düzenle yetkiniz bulunmamaktadır.',PREPATH);
    }
    $tbl = Crud::getById(new Kullanici(),$_POST['id']);
    foreach ($_POST as $key => $value){
        $tbl->$key->deger=$value;
    }
    if ($tbl->duyuru_id->deger == null) {
        $tbl->duyuru_id->deger = -1;
    }
    $result = Crud::update($tbl);
    if ($result==1){
        if (isset($_GET['mesaj'])){
            mesajSet('onay', 'Düzenleme işlemi tamamlandı.');
        }
        cevapDondur("Tamam");
    }else{
        hataDondur($result);
    }
}else if ($_GET['tur']== 'musteriCreate'){
    $bg = new Db();
    $ses = $bg->getCon();
    try {
//         if (!yetkiSoa::yetkiVarmi(yetkiConfig::KULLANICILAR_KAYDET_DUZENLE)){
//             hataDondur('Kullanıcı kayıt ve düzenleme yetkiniz bulunmamaktadır.',PREPATH);
//         }
        
        $mid = $_POST['mstr'];
        unset($_POST['mstr']);
        $tbl = new Kullanici();
        foreach ($_POST as $key => $value){
            $tbl->$key->deger=$value;
        }
        // Bcrypt hash kullan
        $tbl->sifre_md5->deger = SecurityHelper::hashPassword($tbl->sifre->deger);
        $result = Crud::save($tbl,$ses);
//         $tbl->sifre->deger      = strtolower (substr($tbl->kullanici_adi->deger, 0,1).substr($tbl->ad->deger, 0,1).substr($tbl->soyad->deger, 0,1));
//         $tbl->sifre_md5->deger  = md5($tbl->sifre->deger);
//         $result = Crud::save($tbl,$ses);

        if ($result!=1){
            hataDondur($result);
        }
        
        $mtbl = new KullaniciMusteri();
        $mtbl->kullanici_id->deger = $ses->insert_id;
        $mtbl->musteri_id->deger   = $mid;
        $mresult = Crud::save($mtbl,$ses);
        if ($mresult!=1){
            hataDondur($mresult);
        }

        $ses->commit();
        if (isset($_GET['mesaj'])){
            mesajSet('onay', 'Kaydetme işlemi tamamlandı.');
        }
        cevapDondur("Tamam");
    } catch (Exception $e) {
        if (isset($ses)){
            $ses->rollback();
        }
        hataDondur($e);
    } finally {
        if (isset($ses)){
            mysqli_close($ses);
        }
    }
}else if ($_GET['tur']== 'musteriUpdate'){
    unset($_POST['mstr']);
    $tbl = Crud::getById(new Kullanici(),$_POST['id']);
    $sfr = null;
    if ($_POST['sifre'] != ''){
        $sfr = $_POST['sifre'];
    }else{
        $sfr = $tbl->sifre->deger;
    }
    foreach ($_POST as $key => $value){
        $tbl->$key->deger=$value;
    }
    $tbl->sifre->deger = $sfr;
    // Bcrypt hash kullan
    $tbl->sifre_md5->deger = SecurityHelper::hashPassword($tbl->sifre->deger);
    $result = Crud::update($tbl);
    if ($result==1){
        if (isset($_GET['mesaj'])){
            mesajSet('onay', 'Düzenleme işlemi tamamlandı.');
        }
        cevapDondur("Tamam");
    }else{
        hataDondur($result);
    }
}else if ($_GET['tur']== 'isortagiCreate'){
    $bg = new Db();
    $ses = $bg->getCon();
    try {
//         if (!yetkiSoa::yetkiVarmi(yetkiConfig::KULLANICILAR_KAYDET_DUZENLE)){
//             hataDondur('Kullanıcı kayıt ve düzenleme yetkiniz bulunmamaktadır.',PREPATH);
//         }
        
        $mid = $_POST['mstr'];
        unset($_POST['mstr']);
        $tbl = new Kullanici();
        foreach ($_POST as $key => $value){
            $tbl->$key->deger=$value;
        }
        // Bcrypt hash kullan
        $tbl->sifre_md5->deger = SecurityHelper::hashPassword($tbl->sifre->deger);
        $result = Crud::save($tbl,$ses);
//         $tbl->sifre->deger      = strtolower (substr($tbl->kullanici_adi->deger, 0,1).substr($tbl->ad->deger, 0,1).substr($tbl->soyad->deger, 0,1));
//         $tbl->sifre_md5->deger  = md5($tbl->sifre->deger);
//         $result = Crud::save($tbl,$ses);

        if ($result!=1){
            hataDondur($result);
        }
        
        $mtbl = new KullaniciIsortagi();
        $mtbl->kullanici_id->deger = $ses->insert_id;
        $mtbl->isortagi_id->deger  = $mid;
        $mresult = Crud::save($mtbl,$ses);
        if ($mresult!=1){
            hataDondur($mresult);
        }

        $ses->commit();
        if (isset($_GET['mesaj'])){
            mesajSet('onay', 'Kaydetme işlemi tamamlandı.');
        }
        cevapDondur("Tamam");
    } catch (Exception $e) {
        if (isset($ses)){
            $ses->rollback();
        }
        hataDondur($e);
    } finally {
        if (isset($ses)){
            mysqli_close($ses);
        }
    }
}else if ($_GET['tur']== 'isortagiUpdate'){
    unset($_POST['mstr']);
    $tbl = Crud::getById(new Kullanici(),$_POST['id']);
    $sfr = null;
    if ($_POST['sifre'] != ''){
        $sfr = $_POST['sifre'];
    }else{
        $sfr = $tbl->sifre->deger;
    }
    foreach ($_POST as $key => $value){
        $tbl->$key->deger=$value;
    }
    $tbl->sifre->deger = $sfr;
    // Bcrypt hash kullan
    $tbl->sifre_md5->deger = SecurityHelper::hashPassword($tbl->sifre->deger);
    $result = Crud::update($tbl);
    if ($result==1){
        if (isset($_GET['mesaj'])){
            mesajSet('onay', 'Düzenleme işlemi tamamlandı.');
        }
        cevapDondur("Tamam");
    }else{
        hataDondur($result);
    }
}else if ($_GET['tur']== 'denetciCreate'){
    $bg = new Db();
    $ses = $bg->getCon();
    try {
        $tbl = new Kullanici();
        foreach ($_POST as $key => $value){
            $tbl->$key->deger=$value;
        }
        //$tbl->sifre->deger      = strtolower (substr($tbl->kullanici_adi->deger, 0,1).substr($tbl->ad->deger, 0,1).substr($tbl->soyad->deger, 0,1));
        // Bcrypt hash kullan
        $tbl->sifre_md5->deger = SecurityHelper::hashPassword($tbl->sifre->deger);
        $result = Crud::save($tbl,$ses);

        if ($result!=1){
            hataDondur($result);
        }

        $ses->commit();
        if (isset($_GET['mesaj'])){
            mesajSet('onay', 'Kaydetme işlemi tamamlandı.');
        }
        cevapDondur("Tamam");
    } catch (Exception $e) {
        if (isset($ses)){
            $ses->rollback();
        }
        hataDondur($e);
    } finally {
        if (isset($ses)){
            mysqli_close($ses);
        }
    }
}else if ($_GET['tur']== 'denetciUpdate'){
    unset($_POST['mstr']);
    $tbl = Crud::getById(new Kullanici(),$_POST['id']);
    $sfr = null;
    if ($_POST['sifre'] != ''){
        $sfr = $_POST['sifre'];
    }else{
        $sfr = $tbl->sifre->deger;
    }
    foreach ($_POST as $key => $value){
        $tbl->$key->deger=$value;
    }
    $tbl->sifre->deger = $sfr;
    // Bcrypt hash kullan
    $tbl->sifre_md5->deger = SecurityHelper::hashPassword($tbl->sifre->deger);
    $result = Crud::update($tbl);
    if ($result==1){
        if (isset($_GET['mesaj'])){
            mesajSet('onay', 'Düzenleme işlemi tamamlandı.');
        }
        cevapDondur("Tamam");
    }else{
        hataDondur($result);
    }
}else if ($_GET['tur']== 'sifreDegistir'){
    $tbl = Crud::getById(new Kullanici(),$_POST['id']);
    if ($_POST['eSifre'] == null || $_POST['ySifre'] == null || $_POST['ySifret'] == null){
        hataDondur("Alanlar eksik");
    }
    // Şifre doğrulama (bcrypt veya MD5 geriye dönük uyumluluk)
    if (!SecurityHelper::verifyPassword($_POST['eSifre'], $tbl->sifre_md5->deger)){
        hataDondur("Eski şifre yanlış.");
    }
    if ($_POST['ySifre'] != $_POST['ySifret']){
        hataDondur("Şifreler Eşleşmiyor.");
    }
    // Şifre uzunluk kontrolü
    if (!SecurityHelper::validateLength($_POST['ySifre'], 6, 255)) {
        hataDondur("Şifre en az 6 karakter olmalıdır.");
    }
    $tbl->sifre->deger = SecurityHelper::sanitize($_POST['ySifre']);
    // Bcrypt hash kullan
    $tbl->sifre_md5->deger = SecurityHelper::hashPassword($tbl->sifre->deger);
    
    $result = Crud::update($tbl);
    if ($result==1){
        mesajSet('onay', 'Düzenleme işlemi tamamlandı.');
        cevapDondur("Tamam");
    }else{
        hataDondur($result);
    }
}else if ($_GET['tur']== 'enable2FA'){
    // 2FA'yı etkinleştir
    if (!isset($_POST['password']) || empty($_POST['password'])) {
        hataDondur("Şifre gereklidir.");
    }
    
    // CSRF token kontrolü
    $csrfToken = isset($_POST['csrf_token']) ? $_POST['csrf_token'] : '';
    if (!SecurityHelper::verifyCSRFToken($csrfToken)) {
        hataDondur("Geçersiz güvenlik token'ı.");
    }
    
    $kullanici = Crud::getById(new Kullanici(), $_SESSION['login']['id']);
    
    // Şifre doğrula
    if (!SecurityHelper::verifyPassword($_POST['password'], $kullanici->sifre_md5->deger)) {
        hataDondur("Şifre yanlış.");
    }
    
    // 2FA secret oluştur
    $secret = SecurityHelper::generate2FASecret();
    
    // Not: Entity'de twofa_secret alanı olmalı, şimdilik basit olarak ekliyoruz
    // $kullanici->twofa_secret->deger = $secret;
    // Crud::update($kullanici);
    
    // Geçici olarak session'da sakla (veritabanına alan eklenene kadar)
    $_SESSION['login']['twofa_secret'] = $secret;
    
    cevapDondur("2FA başarıyla etkinleştirildi.");
}else if ($_GET['tur']== 'disable2FA'){
    // 2FA'yı devre dışı bırak
    // CSRF token kontrolü
    $csrfToken = isset($_POST['csrf_token']) ? $_POST['csrf_token'] : '';
    if (!SecurityHelper::verifyCSRFToken($csrfToken)) {
        hataDondur("Geçersiz güvenlik token'ı.");
    }
    
    $kullanici = Crud::getById(new Kullanici(), $_SESSION['login']['id']);
    
    // Not: Entity'de twofa_secret alanı olmalı
    // $kullanici->twofa_secret->deger = null;
    // Crud::update($kullanici);
    
    // Session'dan temizle
    unset($_SESSION['login']['twofa_secret']);
    
    cevapDondur("2FA devre dışı bırakıldı.");
}
