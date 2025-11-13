<?php
$pId = 206;
include_once '../../First.php';
include_once PREPATH.'soa/driveSoa.php';
include_once PREPATH.'config/config.php';

$buLink = PREPATH.'pages/genel/deneme.php';
$client = driveSoa::baglan($buLink);

/*
echo $client->getRoot()->id.'</br>';
echo $client->getRoot()->parentReference->driveId.'</br>';
 echo '<pre>';
 print_r($client->getRoot());
 echo '</pre>';
 */

//$driveId = (isset($_GET['id']) ? $_GET['id'] : $client->getRoot()->id);
$driveId = (isset($_GET['id']) ? $_GET['id'] : Config::DRIVE_ROOT_ID);
$drive = driveSoa::dosyaListesi($client, $driveId);

//echo $client->getRoot()->id.'</br>';

include_once PREPATH . 'header.php';
?>
<div class="row">
	<div class="col-6">
		<div class="card shadow mb-4">
			<div class="card-body">
    			<h6 class="m-0 font-weight-bold text-primary">DRIVE_DRIVE_ID:</h6>
    			<?=$client->getRoot()->id ?>
    		</div>
    	</div>
	</div>
	<div class="col-6">
		<div class="card shadow mb-4">
			<div class="card-body">
				<h6 class="m-0 font-weight-bold text-primary">DRIVE_ROOT_ID:</h6>
    			<?=strtoupper($client->getRoot()->parentReference->driveId) ?>
			</div>
		</div>
	</div>
    <?php
    foreach ($drive as $one){
//         echo '<pre>';
//         print_r($one);
//         echo '</pre>';
    ?>
        <div class="col-lg-6">
            <div class="card shadow mb-4">
            	<div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            		<h6 class="m-0 font-weight-bold text-primary"><?=$one->name ?></h6>
            		<a id="myLink" href="#" onclick="dosyaSil('<?=$one->id?>');return false;">dosya Sil</a>
            	</div>
            	<div class="card-body">
            		<a href="<?=$one->webUrl ?>" target="_blank">Dosyayi Ac (One Drive)</a>
            		
            		<br>
	            	<?=$one->id ?>
	            	<br>
	            	<?php 
	            	if ($one->folder != null){
	            	    echo ' <a href="deneme.php?id='.$one->id.'">Klasor Ac</a>';
	            	}
	            	?>
            	</div>
            </div>
    	</div>
    <?php
    }
    ?>
</div>

<?php 
function tiklamali($liste)
{
//     echo '<form enctype="multipart/form-data" action="upload.php'.(isset($_GET['id']) ? '?id='.$_GET['id'] : '').'" method="POST">';
//     echo '<input name="dosya" type="file" />';
//     echo '<input type="submit" value="Dosyayi Gonder" />';
//     echo '</form>';
    
//     echo '</br>';
//     echo '</br>';
    
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
?>
<script type="text/javascript">

function dosyaSil(drvId){
	$.post('<?= PREPATH.'post/drivePost.php?tur=sil' ?>',
        {
			link 	: '<?=$buLink ?>">',
			driveId	: drvId,
	    },
	    function(data,status){
    		if(status == "success"){
    		    var objIc = JSON.parse(data);
    		    if (objIc.hata == true) {
    				hataAc(objIc.hataMesaj);
    		    }else{
	    		    console.log(objIc);
    		    }
    		}else if(status == "error"){
    		    hataAc("Bir sorun oluştu.");
    	    }
	    }
    );
}

</script>
<?php 
include (PREPATH.'footer.php');
?>
