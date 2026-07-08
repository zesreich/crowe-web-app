<?php
/**
 * genelConfig Class
 * DEPRECATED: Bu sınıf Config sınıfını kullanacak şekilde güncellenmiştir
 * Hassas bilgiler artık .env dosyasından okunmaktadır
 */
include_once __DIR__ . '/config.php';

Class genelConfig{
    // MAIL ayarları artık Config sınıfından okunuyor
    public static function MAIL_HOST() {
        return Config::getMailHost();
    }
    
    public static function MAIL_USER() {
        return Config::getMailUser();
    }
    
    public static function MAIL_PASS() {
        return Config::getMailPass();
    }
    
    public static function MAIL_ISIM() {
        return Config::getMailIsim();
    }
    
    const MAIL_SABLON_SOZLESME  = 'SOZLESME';
    const TEKLIF_SOZLESMESI     = 'TEKLIF_SOZLESMESI';
    
    const GECICI_KLASOR = 'gecici/';
    
    public static function BASE_LINK() {
        return Config::getBaseLink();
    }
    
    // Backward compatibility için constant'lar (DEPRECATED)
    // Bu değerler kullanılırsa uyarı verilebilir ama çalışmaya devam eder
    public static function __callStatic($name, $arguments) {
        if ($name === 'MAIL_HOST') return self::MAIL_HOST();
        if ($name === 'MAIL_USER') return self::MAIL_USER();
        if ($name === 'MAIL_PASS') return self::MAIL_PASS();
        if ($name === 'MAIL_ISIM') return self::MAIL_ISIM();
        if ($name === 'BASE_LINK') return self::BASE_LINK();
        return null;
    }
}