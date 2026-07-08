<?php
/**
 * Config Class
 * Environment variables desteği ile güvenli konfigürasyon yönetimi
 * 
 * ÖNEMLİ: Hassas bilgiler .env dosyasında saklanmalıdır.
 * .env dosyası git'e commit edilmemelidir!
 */
Class Config{
    // EnvLoader'ı yükle
    private static $envLoaded = false;
    
    /**
     * Environment loader'ı başlat
     */
    private static function initEnv() {
        if (!self::$envLoaded) {
            $envLoaderPath = __DIR__ . '/../helpers/EnvLoader.php';
            if (file_exists($envLoaderPath)) {
                require_once $envLoaderPath;
                EnvLoader::load();
            }
            self::$envLoaded = true;
        }
    }
    
    // Uygulama Ayarları
    const MAIN_LINK     = '/';
	const GECICI_KLASOR = 'gecici/';
    
    // Debug Mode - Production'da false olmalı
    public static function getDebugMode() {
        self::initEnv();
        $debug = self::getEnv('APP_DEBUG', 'false');
        return strtolower($debug) === 'true';
    }
    
    public static function getBaseLink() {
        self::initEnv();
        return self::getEnv('BASE_LINK', 'https://www.crowehsy.com/');
    }

    //DRIVE//
    const DRIVE_BACK_LINK = 'index.php';
    
    public static function getDriveClientId() {
        self::initEnv();
        return self::getEnv('DRIVE_CLIENT_ID', '');
    }
    
    public static function getDriveClientSecret() {
        self::initEnv();
        return self::getEnv('DRIVE_CLIENT_SECRET', '');
    }
    
    public static function getDriveDriveId() {
        self::initEnv();
        return self::getEnv('DRIVE_DRIVE_ID', '');
    }
    
    public static function getDriveRootId() {
        self::initEnv();
        return self::getEnv('DRIVE_ROOT_ID', '');
    }
    
    public static function getDriveRedirectUri() {
        self::initEnv();
        return self::getEnv('DRIVE_REDIRECT_URI', self::getBaseLink() . 'composer/redirect.php');
    }
    
    public static function getDriveSablonId() {
        self::initEnv();
        return self::getEnv('DRIVE_SABLON_ID', '');
    }
    
    //DB//
    public static function getDbHost() {
        self::initEnv();
        return self::getEnv('DB_HOST', 'localhost');
    }
    
    public static function getDbUser() {
        self::initEnv();
        return self::getEnv('DB_USER', '');
    }
    
    public static function getDbPassword() {
        self::initEnv();
        return self::getEnv('DB_PASSWORD', '');
    }
    
    public static function getDbName() {
        self::initEnv();
        return self::getEnv('DB_NAME', '');
    }
    
    // Eski constant'lar için backward compatibility (DEPRECATED - getDbHost() kullanın)
    public static function DB_HOST() {
        return self::getDbHost();
    }
    
    public static function DB_USER_NAME() {
        return self::getDbUser();
    }
    
    public static function DB_PASSWORD() {
        return self::getDbPassword();
    }
    
    public static function DB_DATABASE() {
        return self::getDbName();
    }
    
	//MAIL//
    public static function getMailHost() {
        self::initEnv();
        return self::getEnv('MAIL_HOST', '');
    }
    
    public static function getMailUser() {
        self::initEnv();
        return self::getEnv('MAIL_USER', '');
    }
    
    public static function getMailPass() {
        self::initEnv();
        return self::getEnv('MAIL_PASS', '');
    }
    
    public static function getMailIsim() {
        self::initEnv();
        return self::getEnv('MAIL_ISIM', 'Crowe HSY');
    }
    
    public static function getMailPort() {
        self::initEnv();
        return (int)self::getEnv('MAIL_PORT', '587');
    }
    
    public static function getMailEncryption() {
        self::initEnv();
        return self::getEnv('MAIL_ENCRYPTION', 'tls');
    }
    
    const MAIL_SABLON_SOZLESME   = 'SOZLESME';
    const MAIL_TEKLIF_SOZLESMESI = 'TEKLIF_SOZLESMESI';
    
    // Eski constant'lar için backward compatibility (DEPRECATED)
    public static function MAIL_HOST() {
        return self::getMailHost();
    }
    
    public static function MAIL_USER() {
        return self::getMailUser();
    }
    
    public static function MAIL_PASS() {
        return self::getMailPass();
    }
    
    public static function MAIL_ISIM() {
        return self::getMailIsim();
    }
    
    // Environment variable helper
    private static function getEnv($key, $default = null) {
        // Önce sistem environment variable'ından oku
        $value = getenv($key);
        
        if ($value !== false && $value !== '') {
            return $value;
        }
        
        // EnvLoader'dan oku (eğer yüklüyse)
        if (class_exists('EnvLoader')) {
            $value = EnvLoader::get($key);
            if ($value !== null) {
                return $value;
            }
        }
        
        return $default;
    }
    
    // Debug mode için constant (backward compatibility)
    public static function DEBUG_MODE() {
        return self::getDebugMode();
    }
}