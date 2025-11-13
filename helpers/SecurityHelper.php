<?php
/**
 * Security Helper Class
 * Güvenlik yardımcı fonksiyonları
 */
class SecurityHelper {
    
    /**
     * XSS koruması için output escaping
     * @param string $string
     * @return string
     */
    public static function escape($string) {
        if ($string === null) {
            return '';
        }
        return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
    }
    
    /**
     * SQL Injection koruması için input temizleme
     * @param string $string
     * @return string
     */
    public static function sanitize($string) {
        if ($string === null) {
            return '';
        }
        // Stripslashes if magic quotes are enabled
        if (get_magic_quotes_gpc()) {
            $string = stripslashes($string);
        }
        // Trim whitespace
        $string = trim($string);
        // Remove null bytes
        $string = str_replace(chr(0), '', $string);
        return $string;
    }
    
    /**
     * Email validation
     * @param string $email
     * @return bool
     */
    public static function validateEmail($email) {
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }
    
    /**
     * Integer validation
     * @param mixed $value
     * @return bool
     */
    public static function validateInt($value) {
        return filter_var($value, FILTER_VALIDATE_INT) !== false;
    }
    
    /**
     * String length validation
     * @param string $string
     * @param int $min
     * @param int $max
     * @return bool
     */
    public static function validateLength($string, $min = 0, $max = 255) {
        $length = mb_strlen($string, 'UTF-8');
        return $length >= $min && $length <= $max;
    }
    
    /**
     * Password hash (bcrypt)
     * @param string $password
     * @return string
     */
    public static function hashPassword($password) {
        return password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);
    }
    
    /**
     * Password verify
     * @param string $password
     * @param string $hash
     * @return bool
     */
    public static function verifyPassword($password, $hash) {
        // MD5 hash kontrolü (eski şifreler için geriye dönük uyumluluk)
        if (strlen($hash) === 32 && ctype_xdigit($hash)) {
            // MD5 hash olduğunu kontrol et
            if (md5($password) === $hash) {
                return true;
            }
        }
        // Modern bcrypt hash kontrolü
        return password_verify($password, $hash);
    }
    
    /**
     * CSRF token oluştur
     * @return string
     */
    public static function generateCSRFToken() {
        if (!isset($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }
    
    /**
     * CSRF token doğrula
     * @param string $token
     * @return bool
     */
    public static function verifyCSRFToken($token) {
        return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
    }
    
    /**
     * Input validation ve sanitization
     * @param array $data
     * @param array $rules
     * @return array ['valid' => bool, 'errors' => array, 'data' => array]
     */
    public static function validateInput($data, $rules) {
        $errors = [];
        $validated = [];
        
        foreach ($rules as $field => $rule) {
            $value = isset($data[$field]) ? $data[$field] : null;
            
            // Required check
            if (isset($rule['required']) && $rule['required'] && ($value === null || $value === '')) {
                $errors[$field] = $rule['label'] . ' zorunludur.';
                continue;
            }
            
            // Skip validation if value is empty and not required
            if ($value === null || $value === '') {
                $validated[$field] = null;
                continue;
            }
            
            // Type validation
            if (isset($rule['type'])) {
                switch ($rule['type']) {
                    case 'email':
                        if (!self::validateEmail($value)) {
                            $errors[$field] = $rule['label'] . ' geçerli bir email adresi olmalıdır.';
                        }
                        break;
                    case 'int':
                        if (!self::validateInt($value)) {
                            $errors[$field] = $rule['label'] . ' bir sayı olmalıdır.';
                        } else {
                            $value = (int)$value;
                        }
                        break;
                    case 'string':
                        $value = self::sanitize($value);
                        if (isset($rule['min']) || isset($rule['max'])) {
                            if (!self::validateLength($value, $rule['min'] ?? 0, $rule['max'] ?? 255)) {
                                $errors[$field] = $rule['label'] . ' uzunluğu ' . ($rule['min'] ?? 0) . '-' . ($rule['max'] ?? 255) . ' karakter arasında olmalıdır.';
                            }
                        }
                        break;
                }
            }
            
            $validated[$field] = $value;
        }
        
        return [
            'valid' => empty($errors),
            'errors' => $errors,
            'data' => $validated
        ];
    }
    
    /**
     * Safe error message (production'da detaylı hata gösterme)
     * @param Exception $e
     * @return string
     */
    public static function getSafeErrorMessage($e) {
        // Production modunda detaylı hata mesajları gösterilmez
        if (defined('DEBUG_MODE') && DEBUG_MODE === true) {
            return $e->getMessage();
        }
        return 'Bir hata oluştu. Lütfen daha sonra tekrar deneyin.';
    }
    
    /**
     * Rate Limiting - İstek sınırlama
     * @param string $key - Rate limit key (örn: 'login', 'api')
     * @param int $maxAttempts - Maksimum deneme sayısı
     * @param int $timeWindow - Zaman penceresi (saniye)
     * @return array ['allowed' => bool, 'remaining' => int, 'reset_time' => int]
     */
    public static function checkRateLimit($key, $maxAttempts = 5, $timeWindow = 300) {
        if (!isset($_SESSION['rate_limit'])) {
            $_SESSION['rate_limit'] = [];
        }
        
        $now = time();
        $rateKey = 'rate_' . $key;
        
        // İlk deneme veya zaman penceresi dolmuş
        if (!isset($_SESSION['rate_limit'][$rateKey]) || 
            ($now - $_SESSION['rate_limit'][$rateKey]['start_time']) > $timeWindow) {
            $_SESSION['rate_limit'][$rateKey] = [
                'attempts' => 1,
                'start_time' => $now,
                'reset_time' => $now + $timeWindow
            ];
            return [
                'allowed' => true,
                'remaining' => $maxAttempts - 1,
                'reset_time' => $now + $timeWindow
            ];
        }
        
        // Deneme sayısını artır
        $_SESSION['rate_limit'][$rateKey]['attempts']++;
        
        // Limit aşıldı mı?
        if ($_SESSION['rate_limit'][$rateKey]['attempts'] > $maxAttempts) {
            $resetTime = $_SESSION['rate_limit'][$rateKey]['reset_time'];
            $remainingTime = $resetTime - $now;
            return [
                'allowed' => false,
                'remaining' => 0,
                'reset_time' => $resetTime,
                'remaining_time' => $remainingTime
            ];
        }
        
        return [
            'allowed' => true,
            'remaining' => $maxAttempts - $_SESSION['rate_limit'][$rateKey]['attempts'],
            'reset_time' => $_SESSION['rate_limit'][$rateKey]['reset_time']
        ];
    }
    
    /**
     * Rate limit'i sıfırla
     * @param string $key
     */
    public static function resetRateLimit($key) {
        if (isset($_SESSION['rate_limit'])) {
            $rateKey = 'rate_' . $key;
            unset($_SESSION['rate_limit'][$rateKey]);
        }
    }
    
    /**
     * 2FA - TOTP (Time-based One-Time Password) oluştur
     * @param string $secret - Gizli anahtar
     * @return string - 6 haneli kod
     */
    public static function generateTOTP($secret) {
        $time = floor(time() / 30); // 30 saniyelik zaman penceresi
        $hash = hash_hmac('sha1', pack('N*', 0) . pack('N*', $time), $secret, true);
        $offset = ord($hash[19]) & 0xf;
        $code = (
            ((ord($hash[$offset+0]) & 0x7f) << 24) |
            ((ord($hash[$offset+1]) & 0xff) << 16) |
            ((ord($hash[$offset+2]) & 0xff) << 8) |
            (ord($hash[$offset+3]) & 0xff)
        ) % 1000000;
        return str_pad($code, 6, '0', STR_PAD_LEFT);
    }
    
    /**
     * 2FA - TOTP doğrula
     * @param string $code - Kullanıcının girdiği kod
     * @param string $secret - Gizli anahtar
     * @param int $tolerance - Tolerans (kaç zaman penceresi)
     * @return bool
     */
    public static function verifyTOTP($code, $secret, $tolerance = 1) {
        $time = floor(time() / 30);
        
        // Mevcut ve önceki/sonraki zaman pencerelerini kontrol et
        for ($i = -$tolerance; $i <= $tolerance; $i++) {
            $checkTime = $time + $i;
            $hash = hash_hmac('sha1', pack('N*', 0) . pack('N*', $checkTime), $secret, true);
            $offset = ord($hash[19]) & 0xf;
            $checkCode = (
                ((ord($hash[$offset+0]) & 0x7f) << 24) |
                ((ord($hash[$offset+1]) & 0xff) << 16) |
                ((ord($hash[$offset+2]) & 0xff) << 8) |
                (ord($hash[$offset+3]) & 0xff)
            ) % 1000000;
            $checkCode = str_pad($checkCode, 6, '0', STR_PAD_LEFT);
            
            if (hash_equals($checkCode, $code)) {
                return true;
            }
        }
        
        return false;
    }
    
    /**
     * 2FA - Gizli anahtar oluştur
     * @return string
     */
    public static function generate2FASecret() {
        return self::base32_encode(random_bytes(20));
    }
    
    /**
     * Base32 encode (2FA için)
     * @param string $data
     * @return string
     */
    private static function base32_encode($data) {
        $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ234567';
        $result = '';
        $bits = '';
        
        for ($i = 0; $i < strlen($data); $i++) {
            $bits .= str_pad(decbin(ord($data[$i])), 8, '0', STR_PAD_LEFT);
        }
        
        for ($i = 0; $i < strlen($bits); $i += 5) {
            $chunk = substr($bits, $i, 5);
            if (strlen($chunk) < 5) {
                $chunk = str_pad($chunk, 5, '0');
            }
            $result .= $chars[bindec($chunk)];
        }
        
        return $result;
    }
}
