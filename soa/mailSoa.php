<?php 
include_once 'baseSoa.php';
require_once PREPATH . 'composer/vendor/autoload.php';
include_once PREPATH . 'config/config.php';
include_once PREPATH . 'db/Crud.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

Class mailSoa extends BaseSoa{
    
    public static function sablonKetSetle($sablon, $keyler){
        foreach ($keyler as $key => $value){
            $sablon['mesaj'] = str_replace($key, $value,$sablon['mesaj']);
        }
        return $sablon;
    }
    
    public static function mailAt($to,$toIsim,$sablon,$dosyalar,$htmlMi = false){
        $tolar = explode(";", $to);
        $result = true;
        $mail = new PHPMailer(true);
        try {
            $mail = new PHPMailer();
            $mail->isSMTP();
            $mail->SetLanguage("tr", PREPATH."composer/vendor/phpmailer/phpmailer/language");
            $mail->CharSet      ="utf-8";
            $mail->Encoding     ="base64";
            $mail->SMTPDebug    = SMTP::DEBUG_OFF;
            $mail->Host         = config::MAIL_HOST;
            $mail->SMTPAuth     = true;
            $mail->Username     = config::MAIL_USER;
            $mail->Password     = config::MAIL_PASS;
            $mail->SMTPSecure   = PHPMailer::ENCRYPTION_SMTPS;
            $mail->Port         = 465;
            $mail->setFrom($mail->Username, config::MAIL_ISIM);
//             $mail->addAddress($to, $toIsim);
            foreach ($tolar as $t){
                $mail->addAddress($t, $t);
            }
            $mail->isHTML($htmlMi);
            $mail->Subject      = $sablon['baslik'];
            $mail->Body         = $sablon['mesaj'];
            if ($dosyalar != null){
                foreach ($dosyalar as $dsy){
                    $mail->addStringAttachment(file_get_contents(PREPATH.config::GECICI_KLASOR.$dsy), $dsy);
                }
            }
            $mail->send();
        } catch (Exception $e) {
            $result = $mail->ErrorInfo;
        }
        return $result;
    }
    
}

// session_start();
// $sablon  = Crud::getSqlTek(new mailSablon(), mailSablon::GET_KEY, array('skey'=>genelConfig::MAIL_SABLON_SOZLESME))->basit();
// $keyler = array (
//     '#musteriAd#' => 'Ahmet',
//     '#deneme#' => 'asd'
// );
// $sablon = mailSoa::sablonKetSetle($sablon, $keyler);
// mailSoa::mailAt('ahmeted88@hotmail.com', 'Ahmet', $sablon,array('asd.pdf'));

