<?php
Class Ff{
    
    public  static $ftp_host = 'ftp.crowehsy.com';
    public  static $ftp_user_name = 'crowehsy';
    public  static $ftp_user_pass = 'C5r6O7w8CRW';

    static function baglan($path = null){
        $ftp = ftp_connect( Ff::$ftp_host ) or die( "Couldn't connect to server!!!" );
        $login = ftp_login($ftp, Ff::$ftp_user_name, Ff::$ftp_user_pass);
        ftp_pasv($ftp, true);
        if (!$login) {
            return false;
        }
        Ff::dosyaDegistir($ftp,$path);
        return $ftp;
    }
    
    static function baglantiyiKes($ftp){
        ftp_close($ftp);
    }
    
    static function dosyaDegistir($ftp,$path = null){
        if (ftp_chdir($ftp, "./public_html/ftp".($path == null ? "" : "/".$path))) {
           // echo "Yeni çalışma dizini: " . ftp_pwd($ftp) . "\n";
        } else {
            echo "Yeni dizine geçilemedi\n";
        }
    }
    
    static function belgeSil($ftp,$dosya){
        if (ftp_delete($ftp, $dosya)) {
            //echo "$dosya sorunsuzca silindi\n";
        } else {
            echo "$dosya silinemedi\n";
        }
    }
    
    static function belgeGetir($ftp, $path = null){
        return ftp_nlist($ftp, $path == null ? "" : $path);
    }
    
    static function cek($ftp){
        if (Ff::baglan()){
            Ff::dosyaDegistir();
            $l = ftp_nlist($ftp, "");
            Ff::baglantiyiKes();
            return $l;
        }
    }
}