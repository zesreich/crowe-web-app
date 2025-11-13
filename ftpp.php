<?php
include_once 'ftpFunction.php';

$ftp = Ff::baglan($_GET['klasor']);

ob_start();
$result = ftp_get($ftp, "php://output", $_GET['adi'], FTP_BINARY);
$data = ob_get_contents();
$datasize = ob_get_length( );
ob_end_clean();
$mapfile = array( 'data' => $data, 'size' => $datasize );
ftp_close($ftp);

if (!$mapfile)
{
    $viewParams['OutContext'] = "Error. File not found." ;
}

header('Content-Description: File Transfer');
header('Content-Type: application/octet-stream');
header('Content-Disposition: attachment; filename='.$_GET['adi']);
header('Content-Transfer-Encoding: binary');
header('Expires: 0');
header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
header('Pragma: public');
header('Content-Length: ' . $mapfile['size']);

echo $mapfile['data'];
exit( );