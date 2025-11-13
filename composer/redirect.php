<?php
require_once __DIR__ . '/vendor/autoload.php';
include_once __DIR__ . '/../config/config.php';

use Krizalys\Onedrive\Onedrive;

if (!array_key_exists('code', $_GET)) {
    throw new Exception('code undefined in $_GET');
}

session_start();

// if (!array_key_exists('onedrive.client.state', $_SESSION)) {
//     throw new Exception('onedrive.client.state undefined in $_SESSION');
// }

$client = Onedrive::client(
    Config::DRIVE_CLIENT_ID,
    [
        'state' => $_SESSION['onedrive.client.state'],
    ]
);

$client->obtainAccessToken(Config::DRIVE_CLIENT_SECRET, $_GET['code']);

$_SESSION['onedrive.client.state'] = $client->getState();
$url = '../'.Config::DRIVE_BACK_LINK;
if (isset($_SESSION['onedrive.backUrl'])){
    $url = '../'.$_SESSION['onedrive.backUrl'];
}
header("Location: $url");
exit();
