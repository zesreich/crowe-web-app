<?php
/**
 * B10 Excel Generator
 * Form verilerini alır ve şablonu kullanarak Excel dosyası oluşturur
 * Formatlar ve stil korunur (PhpSpreadsheet kullanarak)
 */

// CORS headers
// Production'da belirli domain'lere izin verin, development'ta * kullanabilirsiniz
$allowedOrigins = [
    'https://www.crowehsy.com',
    'https://crowehsy.com',
    'http://localhost:8000',
    'http://localhost'
];

$origin = isset($_SERVER['HTTP_ORIGIN']) ? $_SERVER['HTTP_ORIGIN'] : '';
if (in_array($origin, $allowedOrigins) || in_array('*', $allowedOrigins)) {
    header('Access-Control-Allow-Origin: ' . ($origin ?: '*'));
} else {
    // Default origin
    header('Access-Control-Allow-Origin: https://www.crowehsy.com');
}

header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');
// İlk header'ı JSON olarak ayarla, sonra Excel için değiştirilecek

// OPTIONS request için
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

// Sadece POST kabul et
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Sadece POST metodu kabul edilir']);
    exit;
}

// JSON verisi al
$input = file_get_contents('php://input');
$data = json_decode($input, true);

if (!$data) {
    http_response_code(400);
    echo json_encode(['error' => 'Geçersiz JSON verisi']);
    exit;
}

try {
    // Composer autoload
    require_once __DIR__ . '/../composer/vendor/autoload.php';
    
    use PhpOffice\PhpSpreadsheet\IOFactory;
    use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
    
    // Şablon dosyasının yolu - önce template klasöründe, sonra html klasöründe ara
    $templatePath = __DIR__ . '/../template/B10_ODDI.xlsx';
    if (!file_exists($templatePath)) {
        $templatePath = __DIR__ . '/template_B10_ODDI.xlsx';
    }
    if (!file_exists($templatePath)) {
        $templatePath = __DIR__ . '/template_B10.xlsx';
    }
    
    if (!file_exists($templatePath)) {
        throw new Exception('Şablon dosyası bulunamadı. B10_ODDI.xlsx dosyasının template/ veya html/ klasöründe olduğundan emin olun.');
    }
    
    // Şablonu yükle
    $spreadsheet = IOFactory::load($templatePath);
    
    // B10_FileReview sheetini seç (yoksa B_Kapak kullan)
    $sheet = $spreadsheet->getSheetByName('B10_FileReview');
    if (!$sheet) {
        $sheet = $spreadsheet->getSheetByName('B_FileReview');
    }
    if (!$sheet) {
        // İlk sheet'i kullan
        $sheet = $spreadsheet->getActiveSheet();
    }
    
    // Form verilerini hücrelere yaz
    // Not: Hücre referansları şablonunuza göre ayarlanmalıdır
    if (isset($data['bagimsizlik'])) {
        $sheet->setCellValue('C15', $data['bagimsizlik']); // 2) Bağımsızlık değerlendirmesi
    }
    if (isset($data['kapsam'])) {
        $sheet->setCellValue('C18', $data['kapsam']); // 3) Denetim kapsamı
    }
    if (isset($data['genel_anlayis'])) {
        $sheet->setCellValue('C25', $data['genel_anlayis']); // 4) İşletme hakkında genel anlayış
    }
    if (isset($data['raporlama_cercevesi'])) {
        $sheet->setCellValue('C40', $data['raporlama_cercevesi']); // 5) Finansal raporlama çerçevesi
    }
    if (isset($data['onemlilik_kriter'])) {
        $sheet->setCellValue('C45', $data['onemlilik_kriter']); // 6) Önemlilik kriteri
    }
    if (isset($data['onemlilik_tutar'])) {
        $sheet->setCellValue('C50', $data['onemlilik_tutar']); // 7) Önemlilik tutarı
    }
    if (isset($data['olagandisi_islem'])) {
        $sheet->setCellValue('C60', $data['olagandisi_islem']); // 8) Olağandışı işlemler
    }
    if (isset($data['suistimal'])) {
        $sheet->setCellValue('C70', $data['suistimal']); // 9) Suistimal riski
    }
    if (isset($data['yasadisi_odeme'])) {
        $sheet->setCellValue('C80', $data['yasadisi_odeme']); // 10) Yasadışı ödemeler
    }
    if (isset($data['politika_degisim'])) {
        $sheet->setCellValue('C90', $data['politika_degisim']); // 11) Muhasebe politikaları
    }
    if (isset($data['anlasmazlik'])) {
        $sheet->setCellValue('C100', $data['anlasmazlik']); // 12) Görüş ayrılıkları
    }
    if (isset($data['sureklilik'])) {
        $sheet->setCellValue('C110', $data['sureklilik']); // 13-16) Süreklilik değerlendirmeleri
    }
    if (isset($data['yonetim_teyit'])) {
        $sheet->setCellValue('C130', $data['yonetim_teyit']); // 17-18) Yönetim teyitleri
    }
    if (isset($data['denetim_ozeti'])) {
        $sheet->setCellValue('C150', $data['denetim_ozeti']); // 19) Denetim özeti
    }
    if (isset($data['plan_degisim'])) {
        $sheet->setCellValue('C160', $data['plan_degisim']); // 20) Plan değişiklikleri
    }
    
    // Sonuç kısmı
    if (isset($data['sonuc1'])) {
        $sheet->setCellValue('C180', $data['sonuc1']); // Açılış bakiyeleri
    }
    if (isset($data['sonuc2'])) {
        $sheet->setCellValue('C185', $data['sonuc2']); // Kapanış bakiyeleri
    }
    if (isset($data['sonuc3'])) {
        $sheet->setCellValue('C190', $data['sonuc3']); // Politikalar
    }
    
    // Sorular ve sonuçlar için dinamik veri (eğer gönderilmişse)
    if (isset($data['questions']) && is_array($data['questions'])) {
        // Soruları işle - şablonunuza göre ayarlayın
        foreach ($data['questions'] as $index => $question) {
            // Hücre referanslarını şablonunuza göre ayarlayın
            $row = 20 + ($index * 2); // Örnek: Her soru için 2 satır
            if (isset($question['result'])) {
                $sheet->setCellValue('D' . $row, $question['result']);
            }
            if (isset($question['explanation'])) {
                $sheet->setCellValue('C' . $row, $question['explanation']);
            }
        }
    }
    
    // Geçici dosya oluştur
    $tempFile = sys_get_temp_dir() . '/' . uniqid('b10_', true) . '.xlsx';
    
    // Excel dosyasını kaydet
    $writer = new Xlsx($spreadsheet);
    $writer->save($tempFile);
    
    // Dosya adı
    $companyName = isset($data['company']) ? preg_replace('/[^a-zA-Z0-9]/', '_', $data['company']) : 'Denetim';
    $date = date('Y-m-d');
    $filename = 'B10_OD_Dosya_Incelemesi_' . $companyName . '_' . $date . '.xlsx';
    
    // Dosyayı indirme olarak sun (önceden ayarlanan JSON header'ını override et)
    header_remove('Content-Type');
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment; filename="' . $filename . '"');
    header('Cache-Control: max-age=0');
    header('Content-Length: ' . filesize($tempFile));
    
    // Dosyayı oku ve gönder
    readfile($tempFile);
    
    // Geçici dosyayı sil
    unlink($tempFile);
    
    exit;
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'error' => 'Excel oluşturma hatası: ' . $e->getMessage(),
        'file' => $e->getFile(),
        'line' => $e->getLine()
    ]);
    exit;
}

