# Sunucu Kurulum Rehberi - B10 Excel Backend

## 📋 Genel Bakış

Backend PHP kodu sunucuda çalışacak. Frontend yerel veya başka bir sunucuda olabilir, backend'e API çağrısı yapar.

## 🔧 Sunucu Kurulum Adımları

### 1. Dosyaları Sunucuya Yükle

Sunucuya yüklenecek dosyalar:
- `html/generate_b10_excel.php` - Backend endpoint
- `template/B10_ODDI.xlsx` - Şablon dosyası (değişmeyecek)
- `composer/` klasörü (vendor dahil)

### 2. PhpSpreadsheet Kurulumu (Sunucuda)

#### Seçenek A: Composer ile (Önerilen)

Sunucuda SSH ile bağlanın ve:

```bash
cd /path/to/web-app/composer
composer require phpoffice/phpspreadsheet
```

veya mevcut `composer.json` varsa:

```bash
cd /path/to/web-app/composer
composer install
```

#### Seçenek B: Manuel Kurulum

Eğer Composer yoksa:

1. PhpSpreadsheet'i indirin:
   - https://github.com/PHPOffice/PhpSpreadsheet/releases
   - En son sürümü (ZIP) indirin

2. `composer/vendor/phpoffice/` klasörüne çıkartın

3. Autoload'u güncelleyin (gerekirse)

### 3. Şablon Dosyası Kontrolü

Şablon dosyasının doğru konumda olduğundan emin olun:

```php
// generate_b10_excel.php içinde kontrol edilen yollar:
1. ../template/B10_ODDI.xlsx (önerilen)
2. template_B10_ODDI.xlsx (html klasöründe)
3. template_B10.xlsx (html klasöründe)
```

**Önemli:** Şablon dosyası `template/B10_ODDI.xlsx` konumunda olmalı!

### 4. Dosya İzinleri

Sunucuda dosya izinlerini ayarlayın:

```bash
# PHP dosyası
chmod 644 html/generate_b10_excel.php

# Şablon dosyası (okunabilir olmalı)
chmod 644 template/B10_ODDI.xlsx

# Vendor klasörü
chmod -R 755 composer/vendor/
```

### 5. PHP Gereksinimleri

Sunucunuzda şu PHP uzantılarının yüklü olduğundan emin olun:

```bash
php -m | grep -E "(zip|xml|gd|mbstring|zip)"
```

Gerekli uzantılar:
- `zip` - Excel dosyaları için
- `xml` - Excel formatı için
- `gd` - Görsel işleme (opsiyonel)
- `mbstring` - Çok baytlı string desteği
- `openssl` - HTTPS için

### 6. Test

Sunucuda test edin:

```bash
# PHP syntax kontrolü
php -l html/generate_b10_excel.php

# PhpSpreadsheet kontrolü
php -r "require 'composer/vendor/autoload.php'; echo 'PhpSpreadsheet OK';"
```

---

## 🌐 Frontend Yapılandırması

### Yerel Geliştirme için

`html/config.js` dosyasını düzenleyin:

```javascript
// Development
window.API_BASE_URL = 'http://localhost:8000'; // veya geliştirme sunucusu
```

### Production için

`html/config.js` dosyasını düzenleyin:

```javascript
// Production
window.API_BASE_URL = 'https://www.crowehsy.com';
```

---

## 🔒 Güvenlik Ayarları

### CORS Ayarları

`html/generate_b10_excel.php` dosyasında CORS ayarları var. Production'da:

```php
// Sadece izin verilen domain'ler
$allowedOrigins = [
    'https://www.crowehsy.com',
    'https://crowehsy.com'
];
```

### Sunucu Güvenliği

1. **.htaccess ile Korumalar** (Apache için):

```apache
# html/generate_b10_excel.php erişim kontrolü
<Files "generate_b10_excel.php">
    # Gerekirse IP kısıtlaması ekleyin
    # Require ip YOUR_IP
</Files>
```

2. **Rate Limiting** (opsiyonel):

```php
// generate_b10_excel.php içine eklenebilir
// Aynı IP'den çok fazla istek gelirse engelle
```

---

## 📁 Sunucu Klasör Yapısı

```
/var/www/html/ (veya sunucu root)
├── html/
│   ├── generate_b10_excel.php  ← Backend endpoint
│   ├── denetim-rehberi.html    ← Frontend (sunucuda veya başka yerde olabilir)
│   ├── api-config.js           ← API config
│   └── config.js               ← Frontend config
├── template/
│   └── B10_ODDI.xlsx          ← Şablon (değişmeyecek)
└── composer/
    ├── composer.json
    ├── composer.lock
    └── vendor/
        └── phpoffice/
            └── phpspreadsheet/ ← Kurulu olmalı
```

---

## ✅ Kontrol Listesi

Kurulum sonrası kontrol edin:

- [ ] `generate_b10_excel.php` dosyası sunucuda
- [ ] `template/B10_ODDI.xlsx` dosyası sunucuda
- [ ] PhpSpreadsheet kurulu (`composer/vendor/phpoffice/phpspreadsheet`)
- [ ] PHP uzantıları yüklü (zip, xml, mbstring)
- [ ] Dosya izinleri doğru (644/755)
- [ ] CORS ayarları production için düzenlenmiş
- [ ] Frontend'de `API_BASE_URL` doğru ayarlanmış
- [ ] Test isteği başarılı

---

## 🧪 Test

### 1. Backend Testi (Sunucuda)

```bash
# PHP test
cd /path/to/html
php -r "
require '../composer/vendor/autoload.php';
echo 'PhpSpreadsheet: OK\n';
"

# Endpoint test (curl ile)
curl -X POST https://www.crowehsy.com/html/generate_b10_excel.php \
  -H "Content-Type: application/json" \
  -d '{"company":"Test","bagimsizlik":"Test"}' \
  --output test.xlsx
```

### 2. Frontend Testi

1. `denetim-rehberi.html` sayfasını açın
2. Formu doldurun
3. **"Excel'e Aktar (Şablon Formatıyla)"** butonuna tıklayın
4. Excel dosyası indirilmeli
5. Formatlar korunmuş olmalı

---

## 📝 Notlar

1. **Şablon Dosyası:** `template/B10_ODDI.xlsx` dosyası **asla değişmemeli**. Her zaman sabit kalmalı.

2. **Frontend-Backend Ayrımı:**
   - Frontend: Yerel veya başka sunucuda olabilir
   - Backend: Mutlaka PHP sunucusunda olmalı

3. **CORS:** Frontend ve backend farklı domain'lerdeyse CORS ayarları gerekli.

4. **Güvenlik:** Production'da CORS'u sadece izin verilen domain'lere açın.

---

## 🚀 Hızlı Kurulum (Özet)

```bash
# 1. Dosyaları sunucuya yükle
scp -r html/generate_b10_excel.php user@server:/path/to/html/
scp -r template/B10_ODDI.xlsx user@server:/path/to/template/
scp -r composer user@server:/path/to/

# 2. SSH ile bağlan
ssh user@server

# 3. Composer kurulumu
cd /path/to/composer
composer install

# 4. İzinleri ayarla
chmod 644 /path/to/html/generate_b10_excel.php
chmod 644 /path/to/template/B10_ODDI.xlsx

# 5. Test
php -l /path/to/html/generate_b10_excel.php
```

---

*Son Güncelleme: 2025-01-27*


