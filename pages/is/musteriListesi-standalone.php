<?php
/**
 * Müşteri Listesi Sayfası - Standalone
 * Bağımsız müşteri listesi sayfası
 */
session_start();

// Eğer giriş yapılmamışsa login sayfasına yönlendir
if (!isset($_SESSION['login']) || $_SESSION['login']['id'] == -1) {
    header('Location: ../genel/login-standalone.php');
    exit();
}

// First.php dosyasını include et
include_once '../../First.php';
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Crowe HSY - Müşteri Listesi">
    <meta name="author" content="">
    <title>CROWE HSY - Müşteri Listesi</title>
    
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
            <li class="nav-item active">
                <a class="nav-link" href="#">
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
                <h3 class="text-dark ml-5">MÜŞTERİ LİSTESİ</h3>
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
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card shadow">
                            <div class="card-header bg-gradient-primary py-3">
                                <h6 class="m-0 font-weight-bold text-white">MÜŞTERİ LİSTESİ</h6>
                            </div>
                            <div class="card-body">
                                <div class="row mb-3">
                                    <div class="col-10">
                                        <input type="text" class="form-control" id="search" placeholder="Müşteri ara...">
                                    </div>
                                    <div class="col-2">
                                        <a href="musteriTanim.php" class="btn btn-success btn-block">
                                            <i class="fa fa-plus"></i> Yeni Müşteri
                                        </a>
                                    </div>
                                </div>
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped" id="tablebot">
                                        <thead>
                                            <tr>
                                                <th class="bg-gray-700 text-gray-200 text-center align-middle">Vergi No</th>
                                                <th class="bg-gray-700 text-gray-200 text-center align-middle">Unvan</th>
                                                <th class="bg-gray-700 text-gray-200 text-center align-middle">Vergi Dairesi</th>
                                                <th class="bg-gray-700 text-gray-200 text-center align-middle">İş Ortağı</th>
                                                <th class="bg-gray-700 text-gray-200 text-center align-middle">Seç</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $tbl = new Musteri();
                                            $gelen = Crud::all($tbl);
                                            if ($gelen != null) {
                                                foreach ($gelen as $gln) {
                                                    ?>
                                                    <tr class="listeEleman">
                                                        <td class="text-center align-middle"><?= isset($gln->vergi_no) ? $gln->vergi_no->deger : '' ?></td>
                                                        <td class="text-left align-middle"><?= isset($gln->unvan) ? $gln->unvan->deger : '' ?></td>
                                                        <td class="text-center align-middle">
                                                            <?= isset($gln->vergi_daire_id) && isset($gln->vergi_daire_id->ref) && isset($gln->vergi_daire_id->ref->deger) ? $gln->vergi_daire_id->ref->deger->adi->deger : '' ?>
                                                        </td>
                                                        <td class="text-center align-middle">
                                                            <?= isset($gln->isortagi_id) && isset($gln->isortagi_id->ref) && isset($gln->isortagi_id->ref->deger) ? $gln->isortagi_id->ref->deger->unvan->deger : '' ?>
                                                        </td>
                                                        <td class="text-center align-middle">
                                                            <a href="musteri-standalone.php?id=<?= $gln->id ?>" class="btn btn-warning btn-sm">
                                                                <i class="fa fa-hand-pointer-o"></i> SEÇ
                                                            </a>
                                                        </td>
                                                    </tr>
                                                    <?php
                                                }
                                            } else {
                                                ?>
                                                <tr>
                                                    <td colspan="5" class="text-center">Henüz müşteri kaydı bulunmamaktadır.</td>
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

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Custom JS -->
    <script src="../../front/js/sb-admin-2.min.js"></script>
    
    <script>
        // Tablo arama fonksiyonu
        function tableArama(tbl, edt){
            $(edt).on("keyup", function(){
                var value = $(this).val().toLowerCase();
                $(tbl+" tbody tr").filter(function(){
                    if ($(this).text().toLowerCase().indexOf(value) > -1) {
                        $(this).toggle(true);
                    } else {
                        $(this).toggle(false);
                    }
                });
            });
        }
        
        // Tablo sırala fonksiyonu
        function tableSirala(tbl){
            $(tbl+' th').each(function (col) {
                $(this).click(function () {
                    $(tbl+" th i").remove();
                    if ($(this).is('.asc')) {
                        $(this).removeClass('asc');
                        $(this).addClass('desc');
                        $(this).append('<i class="fa fa-sort-desc" aria-hidden="true"/>');
                        sortOrder = -1;
                    } else {
                        $(this).addClass('asc');
                        $(this).removeClass('desc');
                        $(this).append('<i class="fa fa-sort-asc" aria-hidden="true"/>');
                        sortOrder = 1;
                    }
                    $(this).siblings().removeClass('asc');
                    $(this).siblings().removeClass('desc');
                    var arrData = $(tbl).find('tbody >tr:has(td)').get();
                    arrData.sort(function (a, b) {
                        var val1 = $(a).children('td').eq(col).text().toUpperCase();
                        var val2 = $(b).children('td').eq(col).text().toUpperCase();
                        if ($.isNumeric(val1) && $.isNumeric(val2))
                            return sortOrder == 1 ? val1 - val2 : val2 - val1;
                        else
                            return (val1 < val2) ? -sortOrder : (val1 > val2) ? sortOrder : 0;
                    });
                    $.each(arrData, function (index, row) {
                        $(tbl+' tbody').append(row);
                    });
                });
            });
        }
        
        tableSirala("#tablebot");
        tableArama("#tablebot", "#search");
        
        function logout() {
            if (confirm('Çıkış yapmak istediğinizden emin misiniz?')) {
                window.location.href = '../../post/kullaniciPost.php?tur=logout';
            }
        }
    </script>
</body>
</html>
