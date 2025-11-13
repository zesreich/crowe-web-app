<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
require 'vendor/autoload.php';
$mail = new PHPMailer(true);
try {
    $mail = new PHPMailer();
    $mail->SetLanguage("tr", "G:\wamp64\www\crow\composer\vendor\phpmailer\phpmailer\language");
    $mail->CharSet  ="utf-8";
    $mail->Encoding="base64";
    $mail->SMTPDebug = SMTP::DEBUG_OFF;                      // Enable verbose debug output
    $mail->isSMTP();                                            // Send using SMTP
    $mail->Host       = genelConfig::MAIL_HOST;                    // Set the SMTP server to send through
    $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
    $mail->Username   = genelConfig::MAIL_USER;                     // SMTP username
    $mail->Password   = genelConfig::MAIL_PASS;                               // SMTP password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;         // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
    $mail->Port       = 465;                                    // TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above
    $mail->setFrom($mail->Username, genelConfig::MAIL_ISIM);
    $mail->addAddress('ahmeted88@hotmail.com', 'Joe User');     // Add a recipient
    $mail->isHTML(true);                                  // Set email format to HTML
    $mail->Subject = 'Başlık';
    $mail->Body    = 'Denemeler Neler</b> çaöaiaşağapdeneme alt kısımlar';
    echo $mail->send();
    echo 'Message has been sent';
} catch (Exception $e) {
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}