<?php
/**
 * Environment Variables Loader
 * .env dosyasından environment variable'ları yükler
 * 
 * Kullanım:
 * EnvLoader::load();
 * $value = EnvLoader::get('DB_HOST');
 */
class EnvLoader
{
    private static $loaded = false;
    private static $vars = [];
    
    /**
     * .env dosyasını yükle
     * 
     * @param string $path .env dosyasının yolu (root dizine göre)
     * @return bool Başarılı ise true
     */
    public static function load($path = __DIR__ . '/../.env')
    {
        if (self::$loaded) {
            return true;
        }
        
        // .env dosyası var mı kontrol et
        if (!file_exists($path)) {
            // Production'da environment variables sistemden okunabilir
            // .env dosyası olmasa da devam et
            error_log("Warning: .env file not found at: $path");
            self::$loaded = true;
            return false;
        }
        
        // Dosyayı oku
        $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        
        foreach ($lines as $line) {
            // Yorum satırlarını atla
            if (strpos(trim($line), '#') === 0) {
                continue;
            }
            
            // KEY=VALUE formatını parse et
            if (strpos($line, '=') !== false) {
                list($key, $value) = explode('=', $line, 2);
                
                // Boşlukları temizle
                $key = trim($key);
                $value = trim($value);
                
                // Değeri temizle (tırnak işaretlerini kaldır)
                $value = trim($value, '"\'');
                
                // Environment variable olarak set et (önce sistemden oku)
                if (!getenv($key)) {
                    putenv("$key=$value");
                }
                
                // Local array'e de ekle
                self::$vars[$key] = $value;
            }
        }
        
        self::$loaded = true;
        return true;
    }
    
    /**
     * Environment variable değerini al
     * 
     * @param string $key Variable adı
     * @param mixed $default Varsayılan değer (bulunamazsa)
     * @return mixed
     */
    public static function get($key, $default = null)
    {
        // Önce sistem environment variable'ından oku
        $value = getenv($key);
        
        if ($value !== false) {
            return $value;
        }
        
        // Sonra local array'den oku
        if (isset(self::$vars[$key])) {
            return self::$vars[$key];
        }
        
        // Varsayılan değeri döndür
        return $default;
    }
    
    /**
     * Tüm environment variable'ları al
     * 
     * @return array
     */
    public static function all()
    {
        return self::$vars;
    }
    
    /**
     * Environment variable'ın mevcut olup olmadığını kontrol et
     * 
     * @param string $key
     * @return bool
     */
    public static function has($key)
    {
        return getenv($key) !== false || isset(self::$vars[$key]);
    }
    
    /**
     * Reset (test için)
     */
    public static function reset()
    {
        self::$loaded = false;
        self::$vars = [];
    }
}


