<?php
$pId = 999; // Dashboard sayfa ID'si
include_once '../../First.php';
include_once PREPATH . 'header.php';

// Giriş kontrolü
if ($_SESSION['login']['id'] == -1){
    header('Location:'.PREPATH.'pages/genel/login.php');
    exit();
}

// Kullanıcı bilgilerini al
$kullanici = Crud::getById(new Kullanici(), $_SESSION['login']['id']);

// İstatistikler (örnek veriler)
$toplamMusteri = 0;
$aktifDenetim = 0;
$bekleyenTeklif = 0;
$toplamKullanici = 0;

// Veritabanından gerçek veriler çekilebilir
// $toplamMusteri = Crud::all(new Musteri()) ? count(Crud::all(new Musteri())) : 0;

?>
<div class="row">
    <!-- İstatistik Kartları -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                            Toplam Müşteri
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $toplamMusteri > 0 ? $toplamMusteri : '24' ?></div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-building fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-success shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                            Aktif Denetimler
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $aktifDenetim > 0 ? $aktifDenetim : '12' ?></div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-clipboard-check fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-info shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                            Bekleyen Teklifler
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $bekleyenTeklif > 0 ? $bekleyenTeklif : '8' ?></div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-file-contract fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-warning shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                            Toplam Kullanıcı
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $toplamKullanici > 0 ? $toplamKullanici : '18' ?></div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-users fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Grafikler ve Aktiviteler -->
<div class="row">
    <!-- Grafik Alanı -->
    <div class="col-lg-6 mb-4">
        <div class="card shadow">
            <div class="card-header bg-gradient-primary py-3">
                <h6 class="m-0 font-weight-bold text-white">Aylık İşlem Grafiği</h6>
            </div>
            <div class="card-body">
                <canvas id="myChart" style="height: 300px;"></canvas>
            </div>
        </div>
    </div>

    <!-- Son Aktiviteler -->
    <div class="col-lg-6 mb-4">
        <div class="card shadow">
            <div class="card-header bg-gradient-primary py-3">
                <h6 class="m-0 font-weight-bold text-white">Son Aktiviteler</h6>
            </div>
            <div class="card-body" style="max-height: 350px; overflow-y: auto;">
                <div class="list-group">
                    <a href="#" class="list-group-item list-group-item-action">
                        <div class="d-flex w-100 justify-content-between">
                            <h6 class="mb-1"><i class="fas fa-user-plus text-success"></i> Yeni Müşteri Eklendi</h6>
                            <small class="text-muted">2 saat önce</small>
                        </div>
                        <p class="mb-1">ABC İnşaat A.Ş. sisteme kaydedildi.</p>
                        <small class="text-muted"><?= $_SESSION['login']['ad'] . ' ' . $_SESSION['login']['soyad'] ?></small>
                    </a>
                    <a href="#" class="list-group-item list-group-item-action">
                        <div class="d-flex w-100 justify-content-between">
                            <h6 class="mb-1"><i class="fas fa-check-circle text-info"></i> Denetim Tamamlandı</h6>
                            <small class="text-muted">5 saat önce</small>
                        </div>
                        <p class="mb-1">DEF Gıda Ltd. için denetim raporu hazırlandı.</p>
                        <small class="text-muted">Denetçi: Mehmet Öztürk</small>
                    </a>
                    <a href="#" class="list-group-item list-group-item-action">
                        <div class="d-flex w-100 justify-content-between">
                            <h6 class="mb-1"><i class="fas fa-paper-plane text-warning"></i> Teklif Gönderildi</h6>
                            <small class="text-muted">1 gün önce</small>
                        </div>
                        <p class="mb-1">GHI Teknoloji için teklif onaya gönderildi.</p>
                        <small class="text-muted">İş Ortağı: İş Ortağı 1</small>
                    </a>
                    <a href="#" class="list-group-item list-group-item-action">
                        <div class="d-flex w-100 justify-content-between">
                            <h6 class="mb-1"><i class="fas fa-file-alt text-primary"></i> Yeni Belge Yüklendi</h6>
                            <small class="text-muted">2 gün önce</small>
                        </div>
                        <p class="mb-1">ABC İnşaat için vergi levhası yüklendi.</p>
                        <small class="text-muted">Kullanıcı: Ayşe Yılmaz</small>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Hızlı İşlemler -->
<div class="row">
    <div class="col-lg-12 mb-4">
        <div class="card shadow">
            <div class="card-header bg-gradient-primary py-3">
                <h6 class="m-0 font-weight-bold text-white">Hızlı İşlemler</h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <a href="<?=PREPATH?>pages/is/musteriListesi.php" class="btn btn-primary btn-block">
                            <i class="fas fa-building"></i> Müşteri Listesi
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="<?=PREPATH?>pages/is/musteriTanim.php" class="btn btn-success btn-block">
                            <i class="fas fa-plus"></i> Yeni Müşteri
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="<?=PREPATH?>pages/is/denetim/teklifListesi.php" class="btn btn-info btn-block">
                            <i class="fas fa-file-contract"></i> Teklifler
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="<?=PREPATH?>pages/genel/takvim/takvim.php" class="btn btn-warning btn-block">
                            <i class="fas fa-calendar-alt"></i> Takvim
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Chart.js grafiği
$(document).ready(function() {
    if (typeof Chart !== 'undefined') {
        var ctx = document.getElementById('myChart');
        if (ctx) {
            var myChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: ['Ocak', 'Şubat', 'Mart', 'Nisan', 'Mayıs', 'Haziran', 'Temmuz', 'Ağustos', 'Eylül'],
                    datasets: [{
                        label: 'Aylık İşlem Sayısı',
                        data: [12, 19, 15, 25, 22, 18, 30, 28, 24],
                        backgroundColor: 'rgba(54, 162, 235, 0.2)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 2,
                        tension: 0.4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        }
    }
});
</script>

<?php include (PREPATH.'footer.php'); ?>
