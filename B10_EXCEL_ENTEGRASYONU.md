# B10 Excel Entegrasyonu - Kurulum ve Kullanım

## ✅ Yapılan Değişiklikler

### 1. PHP Endpoint Oluşturuldu
**Dosya:** `html/generate_b10_excel.php`

Bu endpoint:
- Form verilerini alır
- Sabit şablon dosyasını yükler (`template/B10_ODDI.xlsx`)
- PhpSpreadsheet kullanarak verileri şablona yazar
- Formatları ve stilleri korur
- Excel dosyasını indirme olarak sunar

### 2. Frontend Güncellendi
**Dosya:** `html/denetim-rehberi.html`

Yeni buton eklendi:
- **"Excel'e Aktar (Şablon Formatıyla)"** - PHP endpoint kullanır (önerilen)
- **"Excel'e Aktar ve OneDrive'a Yükle (Eski Yöntem)"** - JavaScript XLSX.js kullanır

### 3. Composer Güncellendi
**Dosya:** `composer/composer.json`

PhpSpreadsheet eklendi:
```json
"phpoffice/phpspreadsheet": "^1.29"
```

---

## 📋 Kurulum Adımları

### 1. PhpSpreadsheet Kurulumu

```bash
cd composer
composer update phpoffice/phpspreadsheet --no-interaction
```

veya

```bash
composer require phpoffice/phpspreadsheet
```

### 2. Şablon Dosyası Kontrolü

Şablon dosyasının şu konumlardan birinde olduğundan emin olun:
1. `template/B10_ODDI.xlsx` (önerilen)
2. `html/template_B10_ODDI.xlsx`
3. `html/template_B10.xlsx`

---

## 🔧 Kullanım

### Form Verilerini Toplama

Frontend'de form verileri otomatik olarak toplanır ve şu alanlar PHP'ye gönderilir:

- `bagimsizlik` - Soru 2 sonucu
- `kapsam` - Soru 3 sonucu
- `genel_anlayis` - Soru 4 sonucu
- `raporlama_cercevesi` - Soru 5 sonucu
- `onemlilik_kriter` - Soru 6 sonucu
- `onemlilik_tutar` - Soru 7 sonucu
- `olagandisi_islem` - Soru 8 sonucu
- `suistimal` - Soru 9 sonucu
- `yasadisi_odeme` - Soru 10 sonucu
- `politika_degisim` - Soru 11 sonucu
- `anlasmazlik` - Soru 12 sonucu
- `sureklilik` - Soru 13-16 sonuçları
- `yonetim_teyit` - Soru 17-18 sonuçları
- `denetim_ozeti` - Soru 19 sonucu
- `plan_degisim` - Soru 20 sonucu
- `sonuc1`, `sonuc2`, `sonuc3` - Sonuç alanları
- `questions` - Tüm sorular (array)
- `conclusions` - Tüm sonuçlar (array)

### Hücre Referansları

PHP kodunda veriler şu hücrelere yazılıyor:
- C15 - Bağımsızlık değerlendirmesi
- C18 - Denetim kapsamı
- C25 - İşletme hakkında genel anlayış
- C40 - Finansal raporlama çerçevesi
- C45 - Önemlilik kriteri
- C50 - Önemlilik tutarı
- C60 - Olağandışı işlemler
- C70 - Suistimal riski
- C80 - Yasadışı ödemeler
- C90 - Muhasebe politikaları
- C100 - Görüş ayrılıkları
- C110 - Süreklilik değerlendirmeleri
- C130 - Yönetim teyitleri
- C150 - Denetim özeti
- C160 - Plan değişiklikleri
- C180, C185, C190 - Sonuç alanları

**Not:** Bu hücre referansları şablonunuza göre `html/generate_b10_excel.php` dosyasında düzenlenmelidir.

---

## 🎯 Avantajlar

### PHP (PhpSpreadsheet) Yöntemi ✅
- ✅ **Formatlar korunur** - Şablonun tüm formatları, stilleri, hücre birleştirmeleri korunur
- ✅ **Şablon sabit** - `template/B10_ODDI.xlsx` dosyası değişmeden kalır
- ✅ **Tam uyumluluk** - Microsoft Excel ile %100 uyumlu
- ✅ **Profesyonel** - Endüstri standardı kütüphane

### JavaScript (XLSX.js) Yöntemi ⚠️
- ⚠️ **Format kaybı** - Bazı formatlar korunmayabilir
- ⚠️ **Stil kaybı** - Hücre stilleri kaybolabilir
- ✅ **Client-side** - Sunucu gerektirmez

---

## 🔍 Hücre Referanslarını Düzenleme

Şablonunuzdaki hücre referansları farklıysa, `html/generate_b10_excel.php` dosyasını düzenleyin:

```php
// Mevcut:
$sheet->setCellValue('C15', $data['bagimsizlik']);

// Yeni hücre referansı (örnek):
$sheet->setCellValue('D20', $data['bagimsizlik']);
```

---

## 📝 Test Etme

1. `denetim-rehberi.html` sayfasını açın
2. Formu doldurun
3. **"Excel'e Aktar (Şablon Formatıyla)"** butonuna tıklayın
4. Excel dosyası indirilecek
5. İndirilen dosyayı açın ve formatların korunduğunu kontrol edin

---

## ⚠️ Sorun Giderme

### PhpSpreadsheet Bulunamadı Hatası
```bash
cd composer
composer install
```

### Şablon Dosyası Bulunamadı
1. `template/B10_ODDI.xlsx` dosyasının var olduğundan emin olun
2. Veya `html/generate_b10_excel.php` dosyasındaki yol kontrolünü düzenleyin

### CORS Hatası
- PHP endpoint'in CORS header'ları zaten eklenmiş
- Apache/Nginx yapılandırmasını kontrol edin

### Hücre Referansları Yanlış
- Şablonunuzu Excel'de açın
- Her alanın hangi hücrede olduğunu kontrol edin
- `html/generate_b10_excel.php` dosyasını güncelleyin

---

## 🚀 Sonuç

Artık B10 formu doldurulduğunda:
1. Veriler PHP endpoint'ine gönderilir
2. Şablon dosyası yüklenir (değişmeden kalır)
3. Veriler şablona yazılır (formatlar korunur)
4. Excel dosyası indirilir

**Şablon dosyası her zaman sabit kalır ve formatları korunur!**

---

*Son Güncelleme: 2025-01-27*


