<?php
include_once  __DIR__.'/../config/config.php';
/**
 * Database Connection Class
 * Güvenli veritabanı bağlantı yönetimi
 */
class Db 
{    
    private $_host;
    private $_username;
    private $_password;
    private $_database;
    
    public function __construct() {
        // Environment variable'lardan oku (güvenli yöntem)
        $this->_host = Config::getDbHost();
        $this->_username = Config::getDbUser();
        $this->_password = Config::getDbPassword();
        $this->_database = Config::getDbName();
    }
    
    protected $connection;
    
    public function  getSes(){
        $ses = $this->getCon();
        $ses->autocommit(FALSE);
        return $ses;
    }
    
    public function getCon()
    {
        if (!isset($this->connection)) {
            try {
                $this->connection = new mysqli($this->_host, $this->_username, $this->_password, $this->_database);
                
                // Bağlantı hatası kontrolü
                if ($this->connection->connect_error) {
                    error_log("Database connection error: " . $this->connection->connect_error);
                    if (Config::getDebugMode()) {
                        throw new Exception("Veritabanı bağlantı hatası: " . $this->connection->connect_error);
                    } else {
                        throw new Exception("Veritabanı bağlantı hatası oluştu.");
                    }
                }
                
                // UTF-8 karakter seti
                if (!$this->connection->set_charset("utf8")) {
                    error_log("Error setting charset: " . $this->connection->error);
                }
                
                // SQL mode ayarları (güvenlik için)
                $this->connection->query("SET sql_mode = 'STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION'");
                
            } catch (Exception $e) {
                error_log("Database connection exception: " . $e->getMessage());
                if (Config::getDebugMode()) {
                    throw $e;
                } else {
                    throw new Exception("Veritabanı bağlantı hatası oluştu.");
                }
            }
        }    
        
        return $this->connection;
    }
    
    /**
     * Bağlantıyı kapat
     */
    public function close() {
        if (isset($this->connection)) {
            $this->connection->close();
            $this->connection = null;
        }
    }
    
    /**
     * Destructor - bağlantıyı kapat
     */
    public function __destruct() {
        // Not: Connection pooling için kapatmayabiliriz
        // $this->close();
    }
}
?>