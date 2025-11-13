<?php
($config = include __DIR__ . '/../config/config.php') or die('Configuration file not found');
require_once __DIR__ . '/vendor/autoload.php';

use Krizalys\Onedrive\Onedrive;

include_once '../soa/driveSoa.php';

session_start();
$driveId = (isset($_GET['id']) ? $_GET['id'] : Config::DRIVE_ROOT_ID );
$client = driveSoa::baglan('http://localhost/crow/composer/deneme.php');
//$client = baglanti();
$drive = $client->getDriveItemById(Config::DRIVE_CLIENT_ID,$driveId);


//klasorIciniGoster($client->getDriveItemById($config['ONEDRIVE_DRIVE_ID'],$driveId)->children);
tiklamali($drive->children);



function tiklamali($liste){
    echo '<form enctype="multipart/form-data" action="upload.php'.(isset($_GET['id']) ? '?id='.$_GET['id'] : '').'" method="POST">';
    echo '<input name="dosya" type="file" />';
    echo '<input type="submit" value="Dosyayi Gonder" />';
    echo '</form>';
    
    echo '</br>';
    echo '</br>';
    
    echo '<a href="deneme.php">Ana Dizine Don</a>';
    
    echo '</br>';
    echo '</br>';
    
    foreach ($liste as $one){
        echo '<label for="fname">ADI : </label>';
        echo '<label for="fname">'.$one->id.'</label></br>';
        echo '<label for="fname">'.$one->name.'</label></br>';
        echo '<a href="'.$one->webUrl.'" target="_blank">Dosyayi Ac (One Drive)</a>';
        if ($one->folder != null){
            echo ' -- <a href="deneme.php?id='.$one->id.'">Klasor Ac</a>';
        }
        echo '</br>';
        echo '</br>';
    }
}

function baglanti(){
    global $config;

    if (!isset($_SESSION['onedrive.client.state']) || $_SESSION['onedrive.client.state']->token == null) {
        $client = Onedrive::client($config['ONEDRIVE_CLIENT_ID']);
        $url = $client->getLogInUrl([
            'files.read',
            'files.read.all',
            'files.readwrite',
            'files.readwrite.all',
            'offline_access',
        ], $config['ONEDRIVE_REDIRECT_URI']);
        $_SESSION['onedrive.client.state'] = $client->getState();
        header('HTTP/1.1 302 Found', true, 302);
        header("Location: $url");
        exit();
    }
    
    $client = Onedrive::client(
        $config['ONEDRIVE_CLIENT_ID'],
        [
            'state' => $_SESSION['onedrive.client.state'],
        ]
    );

    if ($client->getAccessTokenStatus() == -2 || $client->getAccessTokenStatus() == 0){
        $client->renewAccessToken($config['ONEDRIVE_CLIENT_SECRET']);
    }
    
    return $client;
}

function klasorOlustur($id,$adi){
    global $client;
    $client->getDriveItemById($id)->createFolder($adi);
}

function sil($id){
    global $client;
    $client->getDriveItemById($id)->delete();
}


function oneDriveTekListe($gelen){
    global $client;
    $liste = array();
    foreach ($gelen as $deger) {
        $liste[$deger->id] = $deger;
    }
    return $liste;
}

function oneDriveAllListe($gelen){
    global $client;
    $liste = array();
    foreach ($gelen as $deger) {
        $bu = array();
        $bu['deger'] =  $deger;
        if ($deger->folder != null){
            $bu['alt'] = oneDriveListeYap ($client->getDriveItemById($deger->id)->children);
        }
        $liste[$deger->id] = $bu;
    }
    return $liste;
}


function klasorIciniGoster ($gelen,$bslk=0){
    global $client,$config;
    $bs = '';
    for ($i = 0; $i < $bslk; $i++) {
        $bs = $bs.'-->';
    }
    foreach ($gelen as $deger) {
        echo $bs.$deger->parentReference->id .'</br>';
        echo $bs.$deger->id .'</br>';
        echo $bs.$deger->name .'</br>';
        echo $bs.$deger->webUrl.'</br>';
        if ($deger->folder != null){
            klasorIciniGoster($client->getDriveItemById($config['ONEDRIVE_DRIVE_ID'],$deger->id)->children, $bslk+1);
        }
        echo '</br>';
    }
}
