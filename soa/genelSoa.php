<?php 
include_once 'baseSoa.php';

include_once PREPATH.'db/Crud.php';
// require_once("vendor/autoload.php");
// require_once("vendor/setasign/fpdi/src/autoload.php");
require_once PREPATH . 'composer/vendor/autoload.php';
use setasign\Fpdi\Fpdi;
// require_once('vendor/setasign/fpdf/fpdf.php'); 
Class genelSoa extends BaseSoa{
    
//     public static function aaqq(){
//         $pdf2=new PDF_MC_Table();
//         $pdf2->AddPage();
//         $pdf2->SetFont('Arial','',14);
//         $pdf2->SetWidths(array(50,50,50,40));
//         srand(microtime()*1000000);
//         for($i=0;$i<5;$i++)
//             $pdf2->Row(array(genelSoa::GenerateSentence(),genelSoa::GenerateSentence(),genelSoa::GenerateSentence(),genelSoa::GenerateSentence()));
//         //$pdf->Output();
//         return $pdf2;
//     }
    
//     public static function pdfOlustur ($tbl){//$pdfAd, $musteri, $teklifTrh, $donemSonTrh, $tutar, $yil, $dil){
//         $pdf = new Fpdi();
//         $pageCount = $pdf->setSourceFile(BaseSoa::path().'soa/vendor/sablon_yeni.pdf');
//         $pdf->AddFont('arial_tr','','arial_tr.php');
//         $pdf->AddFont('arial_tr','B','arial_tr_bold.php');
        
//         for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
//             $templateId = $pdf->importPage($pageNo);
//             $size = $pdf->getTemplateSize($templateId);
//             if ($size['width'] > $size['height']) {
//                 $pdf->AddPage('L', array($size['width'], $size['height']));
//             } else {
//                 $pdf->AddPage('P', array($size['width'], $size['height']));
//             }
//             $pdf->useTemplate($templateId);
//             if($pageNo == 23 ){
//                 $pdf->SetFont('arial_tr','',12);
//                 $pdf->SetY(60);
//                 $pdf->SetWidths(array(155,155));
//                 foreach ($tbl['tablo'] as $rslt){
//                     $pdf->Row(10,array($rslt[0],$rslt[1]));
//                 }
//             }else if($pageNo == 24){
//                 $pdf->SetFont('arial_tr','',16);
//                 $pdf->SetXY(20,55);
//                 $pdf->SetWidths(array(290));
//                 $pdf->undrawRow(6,array($tbl['ozel']));
//             }else if($pageNo == 1){
//                 $pdf->SetFont('arial_tr','B',20);
//                 $pdf->SetXY(20,120);
//                 $pdf->Write(0, iconv('utf-8','iso-8859-9',$tbl['musteri']));
//                 $pdf->SetFont('arial_tr','B',20);
//                 $pdf->SetXY(20,130);
//                 $pdf->Write(0, iconv('utf-8','iso-8859-9','Yönetim Kurulu\'na'));
//                 $pdf->SetFont('arial_tr','',20);
//                 $pdf->SetXY(20,150);
//                 $pdf->Write(0, iconv('utf-8','iso-8859-9','Denetim Hizmet Teklifi, '.$tbl['teklifTrh'] ));
//             }
//         }
// //         $pdf->Output();
//         $pdf->Output(BaseSoa::path().'teklifpdf/'.$tbl['pdfAd'].'.pdf', 'F');
//     }
    
    
//     const usr = 'deneme@ahmethmo.com';
//     const psw = 'B@OvOGKu^]_-';
    
    public static function deneme (){
        //require 'PHPMailerAutoload.php';
        require("mail/class.phpmailer.php");
        
        $mail = new PHPMailer;
        
        $mail->SMTPDebug = 3;                               // Enable verbose debug output
        
        $mail->isSMTP();                                      // Set mailer to use SMTP
        $mail->Host = 'smtp.gmail.com';  // Specify main and backup SMTP servers
        $mail->SMTPAuth = true;                               // Enable SMTP authentication
        $mail->Username = 'ahmethmo@gmail.com';
        $mail->Password = 'Az4599104';
        $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
        $mail->Port = 587;                                    // TCP port to connect to
        
        $mail->setFrom('ahmeted88@hotmail.com', 'Mailer');
        $mail->addAddress('ahmeted88@hotmail.com', 'Joe User');     // Add a recipient
        
        
        $mail->isHTML(true);                                  // Set email format to HTML
        
        $mail->Subject = 'Here is the subject';
        $mail->Body    = 'This is the HTML message body <b>in bold!</b>';
        $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
        
        if(!$mail->send()) {
            echo 'Message could not be sent.';
            echo 'Mailer Error: ' . $mail->ErrorInfo;
        } else {
            echo 'Message has been sent';
        }
        
    }
    
    public static function mailGonder ($baslik,$mesaj,$aliciAdres,$aliciAdi){
        require("mail/class.phpmailer.php");
        $mail = new PHPMailer();
        $mail->IsSMTP();
        $mail->SMTPAuth = true;
        $mail->SMTPDebug = 1;
        
        
        $mail->Host = "smtp.gmail.com";
        $mail->SMTPSecure = "tls"; 
        $mail->Port = 587;      
        $mail->Username = 'ahmethmo@gmail.com';
        $mail->Password = 'Az4599104';
        
        
//         $mail->Host = 'raw.guzelhosting.com';
//         $mail->Port = 465;
//         $mail->SMTPSecure = 'ssl';
//         $mail->Username = 'mami@ahmethmo.com';
//         $mail->Password = '4599104aa';
//         $mail->Username = genelSoa::usr;
//         $mail->Password = genelSoa::psw;
        $mail->SetFrom($mail->Username, 'Ahmet Hacimurtezaoğlu');
        $mail->AddAddress($aliciAdres, $aliciAdi);
        $mail->CharSet = 'UTF-8';
        $mail->Subject = $baslik;
        $mail->MsgHTML($mesaj);
        $mail->AddAttachment(null);
        if($mail->Send()) {
            return TRUE;
        } else {
            return FALSE;
        }
        
    }
    
    public static function sablolarGetir(){
        $result = array();
        $sblnlar  = Crud::all(new Sablonlar());
        foreach ($sblnlar as $sbln){
            $result[$sbln->anahtar->deger] = $sbln->deger->deger;
        }
        return $result;
    }
    
    public static function sablolarGetirByGrup($grup){
        $result = array();
        $sblnlar  = Crud::getSqlCok(new Sablonlar(), Sablonlar::GEY_BY_GRUP, array('grup'=>$grup));
        if ($sblnlar != null){
            foreach ($sblnlar as $sbln){
                $result[$sbln->anahtar->deger] = $sbln->deger->deger;
            }
        }
        return $result;
    }
   
}
// $tbl = Base::basitS(Crud::getById(new Denetim(),202027));

// $result = array(
//     array('Denetime Tabi Olma Nedeni'   ,$tbl['dton_id']['aciklama']),
//     array('Dönem '                      ,BaseSoa::strDateToStr($tbl['donem_bas_trh']).'-'.BaseSoa::strDateToStr($tbl['donem_bts_trh'])),
//     array('FRÇ'                         ,$tbl['frc_id']['adi']),
//     array('Tutar (KDV Hariç)'           ,number_format($tbl['tutar'], 2, ',', '.')),
//     array('Param Birimi'                ,$tbl['para_birimi_id']['sembol']),
//     array('Raporlama Dil'               ,$tbl['dil_id']['adi'])
// );

// $pdf = array();
// $pdf['musteri']     = $tbl['musteri_id']['unvan'];
// $pdf['teklifTrh']   = BaseSoa::strDateToStr($tbl['teklif_tarihi']);
// $pdf['donemSonTrh'] = BaseSoa::strDateToStr($tbl['donem_bts_trh']);
// $pdf['tutar']       = number_format($tbl['tutar'], 2, ',', '.');
// $pdf['yil']         = $tbl['para_birimi_id']['sembol'];

// //print_r($tbl['teklif_tarihi']);
// //print_r($tbl);

// genelSoa::pdfOlustur($pdf , $result);

// genelSoa::mailGonder("asd", "qwe", "ahmeted88@hotmail.com", "ahmet haci");
// genelSoa::pdfOlustur('musteri', 'teklifTrh', 'donemSonTr', 'tutar', 'yil');