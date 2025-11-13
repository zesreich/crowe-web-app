<?php
/**
 * Müşteri Detay Sayfası - Standalone
 * Bağımsız müşteri detay sayfası
 */
session_start();

// Eğer giriş yapılmamışsa login sayfasına yönlendir
if (!isset($_SESSION['login']) || $_SESSION['login']['id'] == -1) {
    header('Location: ../genel/login-standalone.php');
    exit();
}

// Parametre kontrolü
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header('Location: musteriListesi-standalone.php');
    exit();
}

// First.php dosyasını include et
include_once '../../First.php';

// Müşteri bilgilerini al
$musteriId = (int)$_GET['id'];
$mstr = Crud::getById(new Musteri(), $musteriId);

if (!$mstr) {
    header('Location: musteriListesi-standalone.php');
    exit();
}

$mstrData = $mstr->basit();
$tbl = new Kullanici();
$kullanicilar = Crud::getSqlCok($tbl, Kullanici::KULLANICI_MUSTERI, array('musteri'=>$musteriId));

?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Crowe HSY - Müşteri Detay">
    <meta name="author" content="">
    <title>CROWE HSY - Müşteri Detay</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    
    <!-- Custom CSS -->
    <link href="../../front/css/sb-admin-2.min.css" rel="stylesheet">
    
    <style>
        body {
            background-color: #f8f9fc;
        }
        .table-responsive {
            max-height: 40vh;
        }
    </style>
</head>
<body id="page-top">
    <div id="wrapper">
        <!-- Sidebar -->
        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="../../index.php">
                <i class="fas fa-building"></i>
                <span class="ml-2">Crowe HSY</span>
            </a>
            <hr class="sidebar-divider my-0">
            <li class="nav-item">
                <a class="nav-link" href="../genel/dashboard.php">
                    <i class="fas fa-tachometer-alt"></i>
                    <span>Dashboard</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="musteriListesi-standalone.php">
                    <i class="fas fa-building"></i>
                    <span>Müşteriler</span>
                </a>
            </li>
        </ul>

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">
            <!-- Topbar -->
            <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">
                <button class="btn btn-link d-md-none rounded-circle mr-3" id="sidebarToggleTop">
                    <i class="fa fa-bars"></i>
                </button>
                <h3 class="text-dark ml-5"><?= isset($mstrData['unvan']) ? SecurityHelper::escape($mstrData['unvan']) : 'Müşteri Detay' ?></h3>
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item dropdown no-arrow">
                        <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown">
                            <span class="mr-2 d-none d-lg-inline text-gray-600 small">
                                <?= $_SESSION['login']['ad'] . ' ' . $_SESSION['login']['soyad'] ?>
                            </span>
                            <img class="img-profile rounded-circle" src="https://via.placeholder.com/40/4e73df/ffffff?text=<?= substr($_SESSION['login']['ad'], 0, 1) ?>" width="40" height="40">
                        </a>
                        <div class="dropdown-menu dropdown-menu-right shadow">
                            <a class="dropdown-item" href="#">
                                <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                                Profil
                            </a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="#" onclick="logout()">
                                <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                Logout
                            </a>
                        </div>
                    </li>
                </ul>
            </nav>

            <!-- Main Content -->
            <div class="container-fluid">
                <div class="row mb-3">
                    <div class="col-12">
                        <a href="musteriListesi-standalone.php" class="btn btn-light">
                            <i class="fas fa-arrow-left"></i> Müşteri Listesine Dön
                        </a>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card shadow">
                            <div class="card-header bg-gradient-primary py-3" data-toggle="collapse" data-target="#demo">
                                <h6 class="m-0 font-weight-bold text-white">
                                    <i class="fas fa-building"></i> <?= isset($mstrData['unvan']) ? SecurityHelper::escape($mstrData['unvan']) : 'Müşteri' ?>
                                </h6>
                            </div>
                            <div class="row">
                                <div class="col-xl-6">
                                    <div class="card-body collapse show" id="demo">
                                        <?php if (KullaniciTurPrm::DENETCI == $_SESSION['login']['tur']): ?>
                                        <div class="row mb-2">
                                            <div class="col-lg-4 font-weight-bold text-gray-800 align-self-center text-right">Id:</div>
                                            <div class="col-lg-4"><?= $mstrData['id'] ?></div>
                                        </div>
                                        <?php endif; ?>
                                        
                                        <div class="row mb-2">
                                            <div class="col-lg-4 font-weight-bold text-gray-800 align-self-center text-right">Unvan:</div>
                                            <div class="col-lg-8"><?= isset($mstrData['unvan']) ? SecurityHelper::escape($mstrData['unvan']) : '' ?></div>
                                        </div>
                                        
                                        <div class="row mb-2">
                                            <div class="col-lg-4 font-weight-bold text-gray-800 align-self-center text-right">İş Ortağı:</div>
                                            <div class="col-lg-8">
                                                <?= isset($mstrData['isortagi_id']) && isset($mstrData['isortagi_id']['unvan']) ? SecurityHelper::escape($mstrData['isortagi_id']['unvan']) : '' ?>
                                            </div>
                                        </div>
                                        
                                        <div class="row mb-2">
                                            <div class="col-lg-4 font-weight-bold text-gray-800 align-self-center text-right">Vergi Dairesi:</div>
                                            <div class="col-lg-8">
                                                <?= isset($mstrData['vergi_daire_id']) && isset($mstrData['vergi_daire_id']['adi']) ? SecurityHelper::escape($mstrData['vergi_daire_id']['adi']) : '' ?>
                                            </div>
                                        </div>
                                        
                                        <div class="row mb-2">
                                            <div class="col-lg-4 font-weight-bold text-gray-800 align-self-center text-right">Vergi No:</div>
                                            <div class="col-lg-8"><?= isset($mstrData['vergi_no']) ? SecurityHelper::escape($mstrData['vergi_no']) : '' ?></div>
                                        </div>
                                        
                                        <div class="row mb-2">
                                            <div class="col-lg-4 font-weight-bold text-gray-800 align-self-center text-right">Adres:</div>
                                            <div class="col-lg-8"><?= isset($mstrData['adres']) ? SecurityHelper::escape($mstrData['adres']) : '' ?></div>
                                        </div>
                                        
                                        <div class="row mb-2">
                                            <div class="col-lg-4 font-weight-bold text-gray-800 align-self-center text-right">Telefon:</div>
                                            <div class="col-lg-8"><?= isset($mstrData['telefon']) ? SecurityHelper::escape($mstrData['telefon']) : '' ?></div>
                                        </div>
                                        
                                        <div class="row mb-2">
                                            <div class="col-lg-4 font-weight-bold text-gray-800 align-self-center text-right">Faks:</div>
                                            <div class="col-lg-8"><?= isset($mstrData['faks']) ? SecurityHelper::escape($mstrData['faks']) : '' ?></div>
                                        </div>
                                        
                                        <div class="row mb-2">
                                            <div class="col-lg-4 font-weight-bold text-gray-800 align-self-center text-right">E-posta:</div>
                                            <div class="col-lg-8"><?= isset($mstrData['email']) ? SecurityHelper::escape($mstrData['email']) : '' ?></div>
                                        </div>
                                        
                                        <div class="row mb-2">
                                            <div class="col-lg-4 font-weight-bold text-gray-800 align-self-center text-right">Web:</div>
                                            <div class="col-lg-8"><?= isset($mstrData['web']) ? SecurityHelper::escape($mstrData['web']) : '' ?></div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-xl-6">
                                    <div class="card-body">
                                        <h6 class="font-weight-bold text-gray-800 mb-3">Müşteriye Atanmış Kullanıcılar</h6>
                                        <div class="table-responsive">
                                            <table class="table table-bordered table-striped">
                                                <thead>
                                                    <tr>
                                                        <th class="bg-gray-700 text-gray-200 text-center">Ad Soyad</th>
                                                        <th class="bg-gray-700 text-gray-200 text-center">E-posta</th>
                                                        <th class="bg-gray-700 text-gray-200 text-center">Telefon</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    if ($kullanicilar != null && count($kullanicilar) > 0) {
                                                        foreach ($kullanicilar as $kullanici) {
                                                            ?>
                                                            <tr>
                                                                <td><?= SecurityHelper::escape($kullanici->ad->deger . ' ' . $kullanici->soyad->deger) ?></td>
                                                                <td><?= SecurityHelper::escape($kullanici->email->deger ?? '') ?></td>
                                                                <td><?= SecurityHelper::escape($kullanici->telefon->deger ?? '') ?></td>
                                                            </tr>
                                                            <?php
                                                        }
                                                    } else {
                                                        ?>
                                                        <tr>
                                                            <td colspan="3" class="text-center">Bu müşteriye atanmış kullanıcı bulunmamaktadır.</td>
                                                        </tr>
                                                        <?php
                                                    }
                                                    ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Custom JS -->
    <script src="../../front/js/sb-admin-2.min.js"></script>
    
    <script>
        function logout() {
            if (confirm('Çıkış yapmak istediğinizden emin misiniz?')) {
                window.location.href = '../../post/kullaniciPost.php?tur=logout';
            }
        }
    </script>
</body>
</html>
