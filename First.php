<?php
include_once 'path.php';
include_once 'db/Crud.php';
include_once 'soa/yetkiSoa.php';
include_once 'config/yetkiConfig.php';

// SecurityHelper'ı yükle (varsa)
if (file_exists(__DIR__.'/helpers/SecurityHelper.php')) {
    include_once __DIR__.'/helpers/SecurityHelper.php';
}
    
    // Session güvenlik ayarları
    ini_set('session.cookie_httponly', 1);
    ini_set('session.cookie_secure', 1); // HTTPS için
    ini_set('session.use_only_cookies', 1);
    ini_set('session.cookie_samesite', 'Strict');
    ini_set('session.cookie_lifetime', 0); // Browser kapanınca oturum sona erer
    ini_set('session.gc_maxlifetime', 3600); // 1 saat
    
    session_start();
    
    // Session fixation koruması
    if (!isset($_SESSION['initiated'])) {
        session_regenerate_id(true);
        $_SESSION['initiated'] = true;
    }
    
    // Session hijacking koruması - IP kontrolü (opsiyonel, proxy kullanımında sorun çıkarabilir)
    // if (isset($_SESSION['ip_address']) && $_SESSION['ip_address'] !== $_SERVER['REMOTE_ADDR']) {
    //     session_destroy();
    //     header('Location: ' . PREPATH . 'pages/genel/login.php');
    //     exit();
    // }
    // $_SESSION['ip_address'] = $_SERVER['REMOTE_ADDR'];
    
    if(!isset($_SESSION['login'])){
        $_SESSION['login']['id']                = -1;
        $_SESSION['login']['kullanici_adi']     = 'Ziyaretçi';
        $_SESSION['login']['ad']                = 'Ziyaretçi';
        $_SESSION['login']['soyad']             = 'Ziyaretçi';
        $_SESSION['login']['grup']              = -1;
        $_SESSION['login']['tur']               = null;
        $_SESSION['login']['grup_adi']          = 'Ziyaretçi';
        $_SESSION['login']['tur_adi']           = 'Ziyaretçi';
        $_SESSION['login']['yetki']             = array();
        $_SESSION['login']['musteri_id']        = null;
        $_SESSION['login']['isortagi_id']       = null;
    }

    global $_ADMIN; 
    $_ADMIN = ($_SESSION['login']['id'] == 1 ? TRUE : FALSE);
    
    function hata($sonuc,$pre){
        $_SESSION['mesaj']['tur']   ='hata';
        $_SESSION['mesaj']['mesaj'] =$sonuc;
        header('Location:'.$pre.'index.php');
        exit();
    }
    
    
    $program    = Crud::getById(new Program(),$pId)->basit();
    if ($program['yetki'] != -1) {
        if (!yetkiSoa::yetkiVarmi($program['yetki'])){
            hata('Bu sayfa için yetkiniz bulunmamaktadır.',PREPATH);
        }
        if ($_SESSION['login']['tur'] == null ){
            hata('Giriş yapmanız gerekmektedir.',PREPATH);
        }
    }
    
    if ($pId == 212 || $pId == 213){
        if ($_SESSION['login']['id'] == -1){
            hata('Bu sayfa için giriş yapmanız gerekmektedir.',PREPATH);
        }
        if (!isset($_GET['id'])){
            hata('Parametre eksik.',PREPATH);
        }else if($_GET['id'] != $_SESSION['login']['id']){
            hata('Sadece kendi kullanıcı bilgilerinizi görebilirsiniz.',PREPATH);
        }
    }
    
    
    $menuList = array();
    $menu = Base::basitList(Crud::getSqlCok(new Program(), Program::MENU_SIRALI, array()));
    menuAgac($program['ust_id']);
    function menuAgac($id){
        global $menuList,$menu;
        array_push($menuList, $id);
        if ($id == -1){return;}
        for ($dn = 0; $dn < count($menu); $dn++) {
            if( $menu[$dn]['id'] ==  $id){
                menuAgac($menu[$dn]['ust_id']);
            }
        }
    }
    
    
    
?>