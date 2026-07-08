# ✅ Sunucu Backend Entegrasyonu Tamamlandı

## 🎯 Yapılan Değişiklikler

### 1. ✅ Backend URL Yapılandırması

**Yeni Dosyalar:**
- `html/api-config.js` - Backend API URL yapılandırması
- `SUNUCU_KURULUM_REHBERI.md` - Detaylı kurulum rehberi

**Güncellenen Dosyalar:**
- `html/config.js` - API_BASE_URL eklendi
- `html/config.js.example` - API_BASE_URL eklendi
- `html/denetim-rehberi.html` - Backend URL'ini kullanacak şekilde güncellendi
- `html/generate_b10_excel.php` - CORS ayarları production için düzenlendi

### 2. ✅ Frontend-Backend Ayrımı

Artık:
- **Frontend:** Yerel veya herhangi bir sunucuda olabilir
- **Backend:** PHP sunucusunda çalışacak (`https://www.crowehsy.com`)

Frontend, backend'e API çağrısı yapar.

---

## 📋 Sunucuya Yüklenecek Dosyalar

### Zorunlu Dosyalar:

1. **Backend Endpoint:**
   - `html/generate_b10_excel.php`

2. **Şablon Dosyası:**
   - `template/B10_ODDI.xlsx` (değişmeyecek)

3. **Composer Vendor:**
   - `composer/vendor/phpoffice/phpspreadsheet/` (PhpSpreadsheet kurulu olmalı)

### Opsiyonel (Frontend farklı sunucudaysa):

- `html/denetim-rehberi.html`
- `html/api-config.js`
- `html/config.js`

---

## 🚀 Sunucuda Yapılacaklar

### 1. PhpSpreadsheet Kurulumu

```bash
# SSH ile sunucuya bağlan
ssh user@server

# Composer klasörüne git
cd /path/to/web-app/composer

# PhpSpreadsheet kur
composer require phpoffice/phpspreadsheet
```

### 2. Dosya Kontrolleri

```bash
# Şablon dosyası kontrolü
ls -la /path/to/template/B10_ODDI.xlsx

# PHP endpoint kontrolü
ls -la /path/to/html/generate_b10_excel.php

# PhpSpreadsheet kontrolü
ls -la /path/to/composer/vendor/phpoffice/phpspreadsheet
```

### 3. İzinler

```bash
chmod 644 html/generate_b10_excel.php
chmod 644 template/B10_ODDI.xlsx
```

---

## ⚙️ Frontend Yapılandırması

### Yerel Geliştirme için:

`html/config.js` dosyasında:

```javascript
window.API_BASE_URL = 'http://localhost:8000'; // veya dev sunucu
```

### Production için:

`html/config.js` dosyasında:

```javascript
window.API_BASE_URL = 'https://www.crowehsy.com';
```

---

## 🔒 CORS Ayarları

`generate_b10_excel.php` dosyasında production için CORS ayarları yapıldı:

```php
$allowedOrigins = [
    'https://www.crowehsy.com',
    'https://crowehsy.com'
];
```

Gerekirse domain ekleyebilirsiniz.

---

## ✅ Test

### Backend Testi (Sunucuda):

```bash
# PHP syntax test
php -l html/generate_b10_excel.php

# PhpSpreadsheet test
php -r "require 'composer/vendor/autoload.php'; echo 'OK';"
```

### Frontend Testi:

1. `denetim-rehberi.html` sayfasını açın
2. Formu doldurun
3. **"Excel'e Aktar (Şablon Formatıyla)"** butonuna tıklayın
4. Backend'den Excel dosyası indirilecek

---

## 📝 Önemli Notlar

1. **Şablon Sabit:** `template/B10_ODDI.xlsx` dosyası **asla değişmemeli**

2. **Backend URL:** Frontend'de `API_BASE_URL` doğru ayarlanmalı

3. **CORS:** Farklı domain'lerdeyse CORS ayarları gerekli (zaten yapıldı)

4. **PhpSpreadsheet:** Sunucuda mutlaka kurulu olmalı

---

## 📚 Detaylı Rehber

Tüm detaylar için: `SUNUCU_KURULUM_REHBERI.md` dosyasına bakın.

---

*Tamamlanma Tarihi: 2025-01-27*


