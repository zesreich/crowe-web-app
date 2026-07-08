# 🚀 Sunucu Kurulumu - Hızlı Başlangıç

## ⚠️ ÖNEMLİ: Yerel Kurulum Gerekmiyor!

Backend **sunucuda** çalışacak. Yerelde PHP/Composer kurmanıza **GEREK YOK**.

---

## 📋 Sunucuda Yapılacaklar (5 Dakika)

### 1. Dosyaları Sunucuya Yükle

FTP/SFTP veya Git ile şu dosyaları yükle:

```
html/generate_b10_excel.php  → Sunucu: /path/to/html/
template/B10_ODDI.xlsx       → Sunucu: /path/to/template/
composer/composer.json       → Sunucu: /path/to/composer/
```

### 2. SSH ile Bağlan ve PhpSpreadsheet Kur

```bash
# Sunucuya bağlan
ssh user@your-server.com

# Composer klasörüne git
cd /var/www/html/composer  # (veya sunucunuzdaki yol)

# PhpSpreadsheet kur
composer require phpoffice/phpspreadsheet
```

### 3. Kontrol Et

```bash
# PhpSpreadsheet kurulu mu?
ls vendor/phpoffice/phpspreadsheet

# PHP syntax kontrolü
php -l ../html/generate_b10_excel.php
```

**Hepsi bu kadar!** ✅

---

## ⚙️ Frontend Yapılandırması

`html/config.js` dosyasında backend URL'ini ayarla:

```javascript
// Backend sunucu URL'i
window.API_BASE_URL = 'https://www.crowehsy.com';
```

---

## 🧪 Test

1. `denetim-rehberi.html` sayfasını aç
2. Formu doldur
3. **"Excel'e Aktar (Şablon Formatıyla)"** butonuna tıkla
4. Excel dosyası indirilecek (sunucudan gelecek)

---

## 📚 Detaylı Rehber

Tüm detaylar için: `SUNUCU_KURULUM_REHBERI.md`

---

**Not:** Yerel kurulum scripti (`install_phpspreadsheet.ps1`) sadece yerel geliştirme için. Sunucuda çalıştırmanıza gerek yok!


