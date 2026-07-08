# Vercel Backend Kurulum Rehberi

## 🚀 Vercel'de PHP Backend Kurulumu

Vercel'de PHP serverless function olarak çalışacak şekilde yapılandırıldı.

---

## 📋 Vercel Yapılandırması

### 1. Dosya Yapısı

```
web-app/
├── api/
│   └── generate_b10_excel.php  ← Serverless function
├── template/
│   └── B10_ODDI.xlsx           ← Şablon dosyası
├── composer/
│   ├── composer.json
│   └── vendor/                 ← PhpSpreadsheet burada olmalı
├── vercel.json                 ← Vercel config
└── .vercelignore              ← Vercel ignore
```

### 2. vercel.json Yapılandırması

```json
{
  "functions": {
    "api/*.php": {
      "runtime": "vercel-php@0.6.0"
    }
  },
  "routes": [
    {
      "src": "/api/generate_b10_excel.php",
      "dest": "/api/generate_b10_excel.php"
    }
  ]
}
```

---

## 🔧 Vercel'e Deploy

### Seçenek 1: Vercel CLI ile

```bash
# Vercel CLI kur (eğer yoksa)
npm i -g vercel

# Projeye git
cd web-app

# Deploy
vercel

# Production deploy
vercel --prod
```

### Seçenek 2: GitHub Entegrasyonu

1. Kodu GitHub'a push edin
2. Vercel dashboard'da projeyi import edin
3. Build settings:
   - Framework Preset: Other
   - Build Command: (boş bırakın)
   - Output Directory: (boş bırakın)
   - Install Command: `cd composer && composer install`

---

## 📦 Composer Bağımlılıkları (Vercel)

### Build Command (Vercel Dashboard'da)

```
cd composer && composer install --no-dev --optimize-autoloader
```

### Environment Variables (Vercel Dashboard'da)

Gerekirse environment variables ekleyin (şu an için gerek yok).

---

## 📁 Dosya Yapısı (Vercel için)

### Gerekli Dosyalar:

1. **API Function:**
   - ✅ `api/generate_b10_excel.php` - Serverless function

2. **Şablon:**
   - ✅ `template/B10_ODDI.xlsx` - Public klasöründe veya root'ta olabilir

3. **Composer:**
   - ✅ `composer/composer.json` - PhpSpreadsheet eklendi
   - ✅ `composer/vendor/` - Build sırasında oluşturulacak

4. **Config:**
   - ✅ `vercel.json` - Vercel yapılandırması

---

## 🔍 Vercel PHP Runtime

Vercel PHP runtime (`vercel-php@0.6.0`) kullanılıyor. Bu:
- PHP 8.0+ desteği
- Serverless function olarak çalışır
- `/api/` klasöründeki PHP dosyaları otomatik olarak function olur

---

## ⚙️ Frontend Yapılandırması

### Vercel URL'i için:

`html/config.js` dosyasında:

```javascript
// Vercel deployment URL'i
window.API_BASE_URL = 'https://your-project.vercel.app';

// VEYA custom domain
window.API_BASE_URL = 'https://www.crowehsy.com';
```

### API Endpoint:

```javascript
// Otomatik olarak şu URL'e istek gönderir:
// https://your-project.vercel.app/api/generate_b10_excel
```

---

## 📝 Vercel Build Ayarları

### Build Command:
```bash
cd composer && composer install --no-dev --optimize-autoloader
```

### Output Directory:
(boş - root'tan serve edilir)

### Install Command:
(boş - build command'da composer çalıştırılıyor)

---

## 🗂️ Şablon Dosyası Konumu

Vercel'de şablon dosyası şu konumlarda aranır (sırayla):

1. `/template/B10_ODDI.xlsx` (root'ta - önerilen)
2. `/public/template/B10_ODDI.xlsx`
3. `/api/template/B10_ODDI.xlsx`

**Öneri:** `template/` klasörünü root'ta tutun.

---

## 🔒 Environment Variables (Gerekirse)

Vercel Dashboard > Settings > Environment Variables:

Şu an için gerek yok, ancak ileride eklenebilir:
- Database bağlantıları
- API keys
- vb.

---

## ✅ Test

### 1. Vercel Deploy Sonrası:

```bash
# API endpoint'i test et
curl -X POST https://your-project.vercel.app/api/generate_b10_excel \
  -H "Content-Type: application/json" \
  -d '{"company":"Test","bagimsizlik":"Test"}' \
  --output test.xlsx
```

### 2. Frontend'den Test:

1. `denetim-rehberi.html` sayfasını açın
2. `config.js` dosyasında Vercel URL'ini ayarlayın
3. Formu doldurun
4. **"Excel'e Aktar (Şablon Formatıyla)"** butonuna tıklayın
5. Excel dosyası Vercel'den gelecek

---

## 🚨 Önemli Notlar

### 1. Composer Vendor Boyutu

Vercel'in function size limiti var (50MB). PhpSpreadsheet büyük olabilir.

**Çözüm:**
- `--no-dev` flag kullanın (production dependencies only)
- `--optimize-autoloader` kullanın
- Gereksiz dosyaları `.vercelignore` ile hariç tutun

### 2. Şablon Dosyası Boyutu

Excel şablonu büyükse Vercel'in limitlerini aşabilir.

**Çözüm:**
- Şablonu optimize edin
- Veya external storage (S3, etc.) kullanın

### 3. Execution Timeout

Vercel serverless functions için timeout limiti var (10s-60s).

**Çözüm:**
- Büyük dosyalar için async processing düşünün
- Şu anki kullanım için yeterli olmalı

---

## 📚 Vercel PHP Dokümantasyonu

- https://vercel.com/docs/runtimes/php
- https://github.com/vercel-community/php

---

## 🔄 Deploy Komutları

```bash
# Development deploy
vercel

# Production deploy
vercel --prod

# Preview deploy
vercel --preview
```

---

## ✅ Kontrol Listesi

- [x] `api/generate_b10_excel.php` oluşturuldu
- [x] `vercel.json` yapılandırması eklendi
- [x] `.vercelignore` eklendi
- [x] Frontend API URL'i güncellendi
- [ ] Vercel'de composer build command ayarlanmalı
- [ ] `template/B10_ODDI.xlsx` Vercel'e yüklenmeli
- [ ] İlk deploy yapılmalı
- [ ] Test edilmeli

---

*Hazırlanma Tarihi: 2025-01-27*


