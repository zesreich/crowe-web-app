<?php
/**
 * Ana Sayfa - Sayfa Seçimi
 * Butonlardan farklı sayfalara yönlendirme
 */
session_start();
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Crowe HSY - Arayüz Önizleme">
    <meta name="author" content="">
    <title>CROWE HSY - Arayüz Önizleme</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    
    <!-- Custom CSS -->
    <link href="../front/css/sb-admin-2.min.css" rel="stylesheet">
    
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .main-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.2);
            padding: 3rem;
            max-width: 900px;
            width: 100%;
        }
        .page-button {
            transition: all 0.3s ease;
            border-radius: 10px;
            padding: 1.5rem;
            margin-bottom: 1rem;
        }
        .page-button:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 20px rgba(0,0,0,0.2);
        }
        .page-button i {
            font-size: 2rem;
            margin-bottom: 0.5rem;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12">
                <div class="main-card">
                    <div class="text-center mb-4">
                        <h1 class="h3 text-gray-900 mb-2">Crowe HSY Arayüz Önizleme</h1>
                        <p class="text-muted">Aşağıdaki butonlardan farklı sayfa görünümlerini inceleyebilirsiniz:</p>
                    </div>
                    
                    <div class="row">
                        <!-- 1. Login Sayfası -->
                        <div class="col-md-6 mb-3">
                            <a href="genel/login-standalone.php" class="btn btn-primary btn-block page-button text-white">
                                <div class="d-flex flex-column align-items-center">
                                    <i class="fas fa-sign-in-alt"></i>
                                    <span class="font-weight-bold">Login Sayfası</span>
                                    <small class="mt-1">Giriş ekranı</small>
                                </div>
                            </a>
                        </div>
                        
                        <!-- 2. Dashboard -->
                        <div class="col-md-6 mb-3">
                            <a href="genel/dashboard.php" class="btn btn-success btn-block page-button text-white">
                                <div class="d-flex flex-column align-items-center">
                                    <i class="fas fa-tachometer-alt"></i>
                                    <span class="font-weight-bold">Dashboard</span>
                                    <small class="mt-1">Ana panel</small>
                                </div>
                            </a>
                        </div>
                        
                        <!-- 3. Müşteri Listesi -->
                        <div class="col-md-6 mb-3">
                            <a href="is/musteriListesi-standalone.php" class="btn btn-info btn-block page-button text-white">
                                <div class="d-flex flex-column align-items-center">
                                    <i class="fas fa-building"></i>
                                    <span class="font-weight-bold">Müşteri Listesi</span>
                                    <small class="mt-1">Müşteri listeleme</small>
                                </div>
                            </a>
                        </div>
                        
                        <!-- 4. Müşteri Detay -->
                        <div class="col-md-6 mb-3">
                            <a href="is/musteri-standalone.php?id=1" class="btn btn-warning btn-block page-button text-white">
                                <div class="d-flex flex-column align-items-center">
                                    <i class="fas fa-user-tie"></i>
                                    <span class="font-weight-bold">Müşteri Detay</span>
                                    <small class="mt-1">Müşteri detay sayfası</small>
                                </div>
                            </a>
                        </div>
                    </div>
                    
                    <hr class="my-4">
                    
                    <div class="text-center">
                        <p class="text-muted mb-0">
                            <small>Crowe HSY - Mali Müşavir Yönetim Platformu</small>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
