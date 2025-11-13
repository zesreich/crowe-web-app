<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
  <meta name="description" content="">
  <meta name="author" content="">
  <title>CROWE</title>
  <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
  <link href="<?=PREPATH?>front/css/sb-admin-2.min.css" rel="stylesheet">
  <link href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css" rel="stylesheet" >
  <link href="<?=PREPATH?>front/css/all.min.css" rel="stylesheet">
  <link href="<?=PREPATH?>front/css/fontawesome.min.css" rel="stylesheet">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
  <style type="text/css">
    
    #overlaylay{	
    	position: fixed;
    	top: 0;
    	z-index: 100;
    	width: 100%;
    	height:100%;
    	display: none;
    	background: rgba(0,0,0,0.5);
    }
    
    .cv-spinner {
    	height: 100%;
    	display: flex;
    	justify-content: center;
    	align-items: center;  
    }
    .spinner {
    	width: 40px;
    	height: 40px;
    	border: 4px #ddd solid;
    	border-top: 4px #2e93e6 solid;
    	border-radius: 50%;
    	animation: sp-anime 0.8s infinite linear;
    }
    @keyframes sp-anime {
    	100% { 
    		transform: rotate(360deg); 
    	}
    }
/*     .is-hide{ */
/*     	display:none; */
/*     } */
  
  </style>
  
</head>
<?php 

function secilimiMenu($id){
    global $menuList;
    foreach ($menuList as $mid){
        if ($mid == $id){
            return TRUE;
        }
    }
    return FALSE;
}

function menuYukle($mns, $ust,$pr,$program,$kat){
    $clr = array("#ffffff","#e9eefb","#d4ddf7","#beccf3","#a9baef");
    foreach ($mns as $mn){
        if ($mn['gorunsunmu'] == 'E' && ( $mn['yetki'] == -1 || yetkiSoa::yetkiVarmi($mn['yetki']))) {
            if ($mn['ust_id'] == $ust) {
                if ($mn['klasor'] == 'E') {
                    if ($mn['ust_id'] == -1) {
                        echo '<li class="nav-item '.( $program['ust_id'] == $mn['id'] ?  'active' : '' ).'">';
                        echo '<a class="nav-link '.( $program['ust_id'] == $mn['id'] ?  '' : 'collapsed' ).' " href="#" data-toggle="collapse" data-target="#acilir'.$mn['id'].'" aria-expanded="true" aria-controls="acilir'.$mn['id'].'">';
                        echo '<i class="'. $mn['icon'] .'" ></i>';
                        echo '<span>'.$mn['program_adi'].'</span>';
                        echo '</a>';
//                         echo '<div id="acilir'.$mn['id'].'" class="collapse '.( $program['ust_id'] == $mn['id'] ?  'show' : '' ).' " aria-labelledby="headingPages" data-parent="#accordionSidebar" style="background-color: '.$clr[$kat].';" >';
                        echo '<div id="acilir'.$mn['id'].'" class="collapse '.( secilimiMenu($mn['id']) ?  'show' : '' ).' " aria-labelledby="headingPages" data-parent="#accordionSidebar" style="background-color: '.$clr[$kat].';" >';
                        echo '<div class=" collapse-inner rounded">';
                        menuYukle($mns,$mn['id'],$pr,$program,$kat+1);
                        echo '<div class="collapse-divider"></div>';
                        echo '</div>';
                        echo '</div>';
                        echo '</li>';
                    }else{
                        echo '<a class="collapse-item nav-link '.( $program['ust_id'] == $mn['id'] ?  '' : 'collapsed' ).' "  href="#" data-toggle="collapse" data-target="#acilir'.$mn['id'].'"  aria-expanded="true" aria-controls="acilir'.$mn['id'].'">';
                        echo '<i class="'. $mn['icon'] .'" style="color:#3a3b45;" ></i>';
                        echo '<span>'.$mn['program_adi'].'</span>';
                        echo '</a>';
                        echo '<div id="acilir'.$mn['id'].'" class="collapse '.( secilimiMenu($mn['id'] ) ?  'show' : '' ).' " style="background-color: '.$clr[$kat].';" >';
//                        echo '<div id="acilir'.$mn['id'].'" class="collapse '.( $program['ust_id'] == $mn['id'] ?  'show' : '' ).' " style="background-color: '.$clr[$kat].';" >';
                        echo '<div class=" py-2">';
                        menuYukle($mns,$mn['id'],$pr,$program,$kat+1);
                        echo '</div>';
                        echo '</div>';
                    }
                }else{
                    if ($mn['ust_id'] == -1) { // TAMAM
                        echo '<li class="nav-item '.( $program['id'] == $mn['id'] ?  'active' : '' ).' ">';
                        echo '<a class="nav-link" href="'.$pr.''.$mn['program_link'].'">';
                        echo '<i class="'. $mn['icon'] .'"></i>';
                        echo '<span>'.$mn['program_adi'].'</span></a>';
                        echo '</li>';
                    }else{
                        //echo '<div class=" my-1">';
                        echo '<a class="collapse-item '.( $program['id'] == $mn['id'] ?  'active' : '' ).' " href="'.$pr.''.$mn['program_link'].'">';
                        echo '<i class="'. $mn['icon'].'" style="color:#3a3b45;"></i>';
                        echo '<span class="pl-2" >'.$mn['program_adi'].'</span>';
                        echo '</a>';
                       // echo '</div>';
                    }
                }
            }
        }
    }
}
?>
<body id="page-top">

    <div id="overlaylay">
    	<div class="cv-spinner">
    		<span class="spinner"></span>
    	</div>
    </div>

  <div id="wrapper">
<!--     <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion li-sil" id="accordionSidebar"> -->
    <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion li-sil" id="accordionSidebar">
      <a class="sidebar-brand d-flex align-items-center justify-content-center" href="<?=PREPATH.'index.php' ?>">
        Crowe HSY
      </a>
      <hr class="sidebar-divider my-0">
      <?= menuYukle($menu,-1,PREPATH,$program,0);?>
      <hr class="sidebar-divider d-none d-md-block">
      <div class="text-center d-none d-md-inline">
      	<button class="rounded-circle border-0" id="sidebarToggle"></button>
  	  </div>
    </ul>
    <div id="content-wrapper" class="d-flex flex-column">
      <div id="content">
        <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">
          <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
            <i class="fa fa-bars"></i>
          </button>
			<h3  class="text-dark ml-5" ><?= $program['program_adi'] ?></h3>
          <ul class="navbar-nav ml-auto">
            <li class="nav-item dropdown no-arrow d-sm-none">
              <a class="nav-link dropdown-toggle" href="#" id="searchDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fas fa-search fa-fw"></i>
              </a>
              <div class="dropdown-menu dropdown-menu-right p-3 shadow animated--grow-in" aria-labelledby="searchDropdown">
                <form class="form-inline mr-auto w-100 navbar-search">
                  <div class="input-group">
                    <input type="text" class="form-control bg-light border-0 small" placeholder="Search for..." aria-label="Search" aria-describedby="basic-addon2">
                    <div class="input-group-append">
                      <button class="btn btn-primary" type="button">
                        <i class="fas fa-search fa-sm"></i>
                      </button>
                    </div>
                  </div>
                </form>
              </div>
            </li>


            <div class="topbar-divider d-none d-sm-block"></div>
            <li class="nav-item dropdown no-arrow">
              <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          		<?php if($_SESSION['login']['id']==-1){ ?>
                    <span class="mr-2 d-none d-lg-inline text-gray-600 small">ZİYARETÇİ</span>
                    <img class="img-profile rounded-circle" src="https://source.unsplash.com/QAB-WJcbgJk/60x60">
                <?php }else{ ?>
				<table align="center">
					<tbody>
						<tr>
							<td style="text-align:center">
                				<span class="mr-2 text-primary" style="font-size:15px;"><?= $_SESSION['login']['tur_adi'] ?></span>
							</td>
							<td style="text-align:center">
                				<span class="mr-2 text-info" style="font-size:15px;"><?= $_SESSION['login']['grup_adi'] ?></span>
							</td>
							<td rowspan="2">
            					<img class="img-profile rounded-circle" src="https://encrypted-tbn0.gstatic.com/images?q=tbn%3AANd9GcSM6KKVa0Kjm1vMYXV-9IOXtcwxbWC2LhA7UUE2MQCV9zgIAww1">
							</td>
						</tr>
						<tr>
							<td colspan="2">
            					<span class="mr-2 text-secondary" style="font-size:15px;"><?= $_SESSION['login']['ad'].' '.$_SESSION['login']['soyad'] ?></span>
							</td>
						</tr>
            		</tbody>
				</table>                	
                <?php } ?>
              </a>
              <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
                <?php 
                if($_SESSION['login']['id']==-1){?>
                <a class="dropdown-item" href="<?=PREPATH.'pages/genel/login.php' ?>" >
                  <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                  Login
                </a>
                <?php }else{ ?>
                <a class="dropdown-item" href="<?=PREPATH.'pages/kullanici/kullaniciProfil.php?id='.$_SESSION['login']['id'] ?>">
                  <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                  Profil
                </a>
                <a class="dropdown-item" href="<?=PREPATH.'pages/genel/takvim/takvim.php' ?>">
                  <i class="far fa-calendar-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                  Takvim
                </a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="#" id="logout" >
                  <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                  Logout
                </a>
                <?php }?>
              </div>
            </li>
          </ul>
        </nav>

        <div class="container-fluid">
            <div id="hata" class="alert alert-danger" style="display: none">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                      <div class="h5 font-weight-bold mb-1">HATA!</div>
                      <div id="hataMesaji" class="font-weight-bold"></div>
                    </div>
                    <div id="hataKapat" class="col-auto">
                      <i class="fa fa-times fa-2x"></i>
                    </div>
                </div>
            </div>
            
            <div id="onay" class="alert alert-success" style="display: none">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                      <div class="h5 font-weight-bold mb-1">BAŞARILI!</div>
                      <div id="onayMesaji" class="font-weight-bold">asdwqe</div>
                    </div>
                    <div id="onayKapat" class="col-auto">
                      <i class="fa fa-times fa-2x"></i>
                    </div>
                </div>
            </div>
            
<script >

	var overSayi = 0;

	function loadEkranAc(){
		overSayi++;　
		$("#overlaylay").fadeIn(300);
		$("#overlaylay").css({ opacity: 0.5 });
	}

	function loadEkranKapat(){
		overSayi--;
		if (overSayi <= 0) {
			$("#overlaylay").fadeOut(300);
			overSayi = 0;
		}
	}

	function isNumberKey(evt)
	{
		var charCode = (evt.which) ? evt.which : evt.keyCode;
		if (charCode != 46 && charCode > 31 
		&& (charCode < 48 || charCode > 57))
		return false;
		return true;
	}
 

// jQuery(function($){
// 	$(document).ajaxSend(function() {
// 		$("#overlaylay").fadeIn(300);　
// 	});
		
// 	$('#button').click(function(){
// 		$.ajax({
// 			type: 'GET',
// 			success: function(data){
// 				console.log(data);
// 			}
// 		}).done(function() {
// 			setTimeout(function(){
// 				$("#overlaylay").fadeOut(300);
// 			},1000);
// 		});
// 	});	
// });

	$("#logout").click(function(){
		$.get( "<?=PREPATH.'post/kullaniciPost.php?tur=logout' ?>", function(data, status){
    		if(status == "success"){
        		console.log(data);
    		    var obj = JSON.parse(data);
    		    if (obj.hata == true) {
    				hataAc(obj.hataMesaj);
    		    }else{
    				location.reload();
    		    }
    		}else if(status == "error"){
    		    hataAc("Bir sorun oluştu.");
    	    }
	    });
	});
	
	$(function(){
	    $("#hataKapat").click(function(){
			$("#hataMesaji").text("");
			$("#hata").hide();
	    });
	    
	    $("#onayKapat").click(function(){
			$("#onayMesaji").text("");
			$("#onay").hide();
	    });

	});
    function hataAc(icerik){
		$("#hataMesaji").text(icerik);
		$("#hata").show();
    }

    function onayAc(icerik){
		$("#onayMesaji").text(icerik);
		$("#onay").show(); 
    }

    function formatPara(amount, decimalCount = 2, decimal = ",", thousands = ".") {
	  try {
	    decimalCount = Math.abs(decimalCount);
	    decimalCount = isNaN(decimalCount) ? 2 : decimalCount;

	    const negativeSign = amount < 0 ? "-" : "";

	    let i = parseInt(amount = Math.abs(Number(amount) || 0).toFixed(decimalCount)).toString();
	    let j = (i.length > 3) ? i.length % 3 : 0;

	    return negativeSign + (j ? i.substr(0, j) + thousands : '') + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + thousands) + (decimalCount ? decimal + Math.abs(amount - i).toFixed(decimalCount).slice(2) : "");
	  } catch (e) {
	    console.log(e)
	  }
	}

	function formatTarih(trh) {
		if (trh != null){
    	    date = (typeof trh) == 'string' ? new Date(trh) : trh;
    	    let year = date.getFullYear();
    	    let month = (1 + date.getMonth()).toString().padStart(2, '0');
    	    let day = date.getDate().toString().padStart(2, '0');
//     	    return month + '/' + day + '/' + year;
    	    return day + '/' + month + '/' + year;
		}
	}

	function formatTarihNoktali(trh) {
		if (trh != null){
    	    date = (typeof trh) == 'string' ? new Date(trh.replace(' ', 'T')) : trh;
    	    let year = date.getFullYear();
    	    let month = (1 + date.getMonth()).toString().padStart(2, '0');
    	    let day = date.getDate().toString().padStart(2, '0');
    	    return day + '.' + month + '.' + year;
		}
	}

	function formatTarihforForm(trh) {
		if (trh != null){
    	    date = (typeof trh) == 'string' ? new Date(trh) : trh;
    	    let year = date.getFullYear();
    	    let month = (1 + date.getMonth()).toString().padStart(2, '0');
    	    let day = date.getDate().toString().padStart(2, '0');
//     	    return month + '/' + day + '/' + year;
    	    return year + '-' + month + '-' + day;
		}
	}

	function formatTarihSaatforForm(trh) {
		if (trh != null){
    	    date = (typeof trh) == 'string' ? new Date(trh) : trh;
    	    let year = date.getFullYear();
    	    let month = (1 + date.getMonth()).toString().padStart(2, '0');
    	    let day = date.getDate().toString().padStart(2, '0');
			let hour = date.getHours().toString().padStart(2, '0');
			let min = date.getMinutes().toString().padStart(2, '0');
			let sec = date.getSeconds().toString().padStart(2, '0');
			return year + '-' + month + '-' + day + ' ' + hour + ':' + min + ':' + sec;
		}
	}

	function formatSaatTarihforForm(trh) {
		if (trh != null){
    	    date = (typeof trh) == 'string' ? new Date(trh) : trh;
    	    let year = date.getFullYear();
    	    let month = (1 + date.getMonth()).toString().padStart(2, '0');
    	    let day = date.getDate().toString().padStart(2, '0');
			let hour = date.getHours().toString().padStart(2, '0');
			let min = date.getMinutes().toString().padStart(2, '0');
			let sec = date.getSeconds().toString().padStart(2, '0');
			return hour + ':' + min + '  ' + day + '-' + month + '-' + year ;
		}
	}
	    
    <?php
    if (isset($_SESSION['mesaj'])){
        if ($_SESSION['mesaj']['tur']=='hata'){ 
            echo 'hataAc("'.$_SESSION['mesaj']['mesaj'].'")';
        }else if ($_SESSION['mesaj']['tur']=='onay'){
            echo 'onayAc("'.$_SESSION['mesaj']['mesaj'].'")';
        }
        unset($_SESSION['mesaj']);
    }
    ?>
</script>