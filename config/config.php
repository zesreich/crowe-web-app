<?php
/**
 * Config Class
 * UYARI: Bu dosya hassas bilgiler içerir. Production'da environment variables kullanılmalıdır.
 */
Class Config{
    const MAIN_LINK     = '/';
	const GECICI_KLASOR = 'gecici/';
    const BASE_LINK     = 'https://www.crowehsy.com/';
    
    // Debug Mode - Production'da false olmalı
    const DEBUG_MODE = false;

    //DRIVE//
    const DRIVE_BACK_LINK     = 'index.php';
    const DRIVE_CLIENT_ID     = '';
    const DRIVE_CLIENT_SECRET = '';
    const DRIVE_DRIVE_ID      = '';
    const DRIVE_ROOT_ID       = '';
    const DRIVE_REDIRECT_URI  = '';
    const DRIVE_SABLON_ID     = '';
    //DRIVE//
    
    //DB//
    // NOT: Production'da environment variables kullanılmalı:
    // getenv('DB_HOST') ?: 'localhost'
    const DB_HOST       = '';
    const DB_USER_NAME  = '';
	//const DB_USER_NAME  = 'crowehsy';
    const DB_PASSWORD   = '';
    const DB_DATABASE   = '';
    //DB//
    
	//MAIL////
    const MAIL_HOST = '';
    const MAIL_USER = '';
    const MAIL_PASS = '';
    const MAIL_ISIM = 'Crowe HSY';
    
    const MAIL_SABLON_SOZLESME   = 'SOZLESME';
    const MAIL_TEKLIF_SOZLESMESI = 'TEKLIF_SOZLESMESI';
    //MAIL////
	
    /**
     * Environment variable desteği
     * Production'da bu methodlar kullanılmalı
     */
    public static function getDbHost() {
        return getenv('DB_HOST') ?: self::DB_HOST;
    }
    
    public static function getDbUser() {
        return getenv('DB_USER') ?: self::DB_USER_NAME;
    }
    
    public static function getDbPassword() {
        return getenv('DB_PASSWORD') ?: self::DB_PASSWORD;
    }
    
    public static function getDbName() {
        return getenv('DB_NAME') ?: self::DB_DATABASE;
    }
}