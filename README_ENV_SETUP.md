# Environment Variables Kurulum Rehberi

Bu rehber, Crowe HSY web uygulaması için güvenli environment variables kurulumunu açıklar.

## 🔐 Güvenlik Öncelikli Kurulum

### 1. .env Dosyası Oluşturma

Proje root dizininde `.env` dosyası oluşturun:

```bash
# .env.example dosyasını kopyalayın
cp .env.example .env
```

### 2. .env Dosyasını Düzenleme

`.env` dosyasını açın ve tüm değerleri doldurun:

```env
# Veritabanı Ayarları
DB_HOST=localhost
DB_USER=crowehsy
DB_PASSWORD=your_secure_password_here
DB_NAME=crowehsy_db

# E-posta Ayarları
MAIL_HOST=smtp.gmail.com
MAIL_USER=noreply@crowehsy.net
MAIL_PASS=your_email_password_here

# OneDrive Ayarları
DRIVE_CLIENT_ID=your_client_id
DRIVE_CLIENT_SECRET=your_client_secret

# Uygulama Ayarları
APP_ENV=production
APP_DEBUG=false

# Supabase (Modern Frontend)
SUPABASE_URL=https://your-project.supabase.co
SUPABASE_ANON_KEY=your_anon_key
```

### 3. Frontend Config Dosyası

`html/` klasöründe `config.js` dosyası oluşturun:

```bash
cd html
cp config.js.example config.js
```

`config.js` dosyasını düzenleyin:

```javascript
// Production modu
const APP_ENV = 'production';

// Production'da fallback şifreler boş olmalı
const FALLBACK_DEFAULT_PASSWORD = '';
const FALLBACK_ADMINS = {};
```

### 4. Git Güvenliği

`.env` ve `config.js` dosyalarının git'e commit edilmediğinden emin olun:

```bash
# .gitignore dosyasına eklenmeli:
.env
.env.local
html/config.js
```

## 📋 Değişiklik Özeti

### ✅ Yapılan İyileştirmeler:

1. **Environment Variables Desteği**
   - `.env` dosyası sistemi eklendi
   - `helpers/EnvLoader.php` oluşturuldu
   - `Config` sınıfı `.env` desteği ile güncellendi

2. **Güvenlik İyileştirmeleri**
   - Hardcoded şifreler kaldırıldı (`config/_genelConfig.php`)
   - Veritabanı şifreleri artık `.env`'den okunuyor
   - E-posta şifreleri artık `.env`'den okunuyor
   - Frontend fallback şifreleri production'da devre dışı

3. **Kod İyileştirmeleri**
   - `Db.php` güncellendi (environment variables kullanıyor)
   - `mailSoa.php` güncellendi
   - `Crud.php` debug mode güncellendi
   - `.gitignore` güncellendi (.env koruması eklendi)

4. **Dokümantasyon**
   - `.env.example` template dosyası oluşturuldu
   - `config.js.example` template dosyası oluşturuldu
   - Bu rehber oluşturuldu

## 🚀 Migration Adımları

### Mevcut Projeye Uygulama:

1. **Backup Alın**
   ```bash
   cp config/_genelConfig.php config/_genelConfig.php.backup
   ```

2. **.env Dosyası Oluşturun**
   ```bash
   cp .env.example .env
   # .env dosyasını düzenleyin
   ```

3. **Mevcut Değerleri .env'e Taşıyın**
   - `config/_genelConfig.php`'deki değerleri `.env`'e kopyalayın
   - Veritabanı bilgilerini `.env`'e ekleyin

4. **Test Edin**
   - Geliştirme ortamında test edin
   - Tüm fonksiyonların çalıştığından emin olun

5. **Production'a Deploy**
   - `.env` dosyasını production sunucusuna güvenli şekilde yükleyin
   - `html/config.js` dosyasını oluşturun (production ayarlarıyla)
   - Test edin

## ⚠️ Önemli Notlar

1. **.env Dosyası Asla Git'e Commit Edilmemeli**
   - `.gitignore` dosyası kontrol edildi
   - Hassas bilgiler korunuyor

2. **Production Ayarları**
   - `APP_ENV=production` olmalı
   - `APP_DEBUG=false` olmalı
   - Frontend'de `FALLBACK_DEFAULT_PASSWORD` boş olmalı

3. **Backward Compatibility**
   - Eski kodlar hala çalışır (deprecated uyarıları ile)
   - Kademeli geçiş yapılabilir

## 🔍 Sorun Giderme

### .env Dosyası Okunmuyor:

```php
// helpers/EnvLoader.php dosyasının yüklendiğinden emin olun
// First.php dosyasında EnvLoader::load() çağrılıyor
```

### Config Değerleri Boş:

- `.env` dosyasının root dizinde olduğundan emin olun
- Dosya izinlerini kontrol edin
- Environment variable'ları manuel kontrol edin: `echo getenv('DB_HOST');`

### Frontend Fallback Çalışmıyor:

- `html/config.js` dosyasının yüklendiğinden emin olun
- HTML sayfalarında `config.js` script'i `auth.js`'den önce yüklenmeli

## 📞 Destek

Sorun yaşarsanız:
1. Log dosyalarını kontrol edin
2. `.env` dosyası formatını kontrol edin
3. PHP error log'larını inceleyin


