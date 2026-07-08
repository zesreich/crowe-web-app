# Composer ve PhpSpreadsheet Kurulum Rehberi

## 🔧 Durum
PHP ve Composer sistem PATH'inde bulunamadı. PhpSpreadsheet'i kurmak için önce bunları kurmamız gerekiyor.

## 📋 Seçenek 1: Composer'ı İndir ve Kullan (Önerilen)

### 1. Composer İndir

Windows için Composer Setup dosyasını indirin:
```
https://getcomposer.org/Composer-Setup.exe
```

Veya manuel olarak `composer.phar` dosyasını indirin:
```powershell
# PowerShell'de çalıştırın
Invoke-WebRequest -Uri "https://getcomposer.org/composer.phar" -OutFile "composer.phar"
```

### 2. Composer'ı Kur

**Composer Setup kullanıyorsanız:**
- İndirilen `Composer-Setup.exe` dosyasını çalıştırın
- Kurulum sihirbazı PHP'yi otomatik bulacaktır (XAMPP, WAMP, vs. kuruluysa)
- Kurulum tamamlandığında PATH'e otomatik eklenir

**composer.phar kullanıyorsanız:**
```powershell
# composer.phar'ı proje root'una kopyalayın
# Sonra şu şekilde kullanın:
php composer.phar install
```

### 3. PhpSpreadsheet Kur

Composer kurulduktan sonra:

```bash
cd composer
composer require phpoffice/phpspreadsheet
```

veya

```bash
cd composer
composer install
```

---

## 📋 Seçenek 2: PHP Kurulumu (Eğer PHP yoksa)

### XAMPP Kullanarak (Önerilen - Windows için)

1. **XAMPP İndir:**
   - https://www.apachefriends.org/download.html
   - Windows için XAMPP'i indirin

2. **XAMPP Kur:**
   - İndirilen dosyayı çalıştırın
   - Kurulum yapın (genellikle `C:\xampp`)

3. **PHP PATH'e Ekle:**
   ```powershell
   # Sistem değişkenlerini düzenleyin:
   # 1. Windows + R tuşlarına basın
   # 2. sysdm.cpl yazın ve Enter'a basın
   # 3. "Gelişmiş" sekmesine gidin
   # 4. "Ortam Değişkenleri" butonuna tıklayın
   # 5. "Path" değişkenini seçin ve "Düzenle" butonuna tıklayın
   # 6. "Yeni" butonuna tıklayın ve şunu ekleyin:
   #    C:\xampp\php
   ```

4. **PowerShell'i Yeniden Başlat**

5. **PHP Kontrol:**
   ```powershell
   php --version
   ```

---

## 📋 Seçenek 3: Manuel PhpSpreadsheet Kurulumu (Gelişmiş)

Composer kullanmak istemiyorsanız, PhpSpreadsheet'i manuel olarak kurabilirsiniz:

### Adımlar:

1. **PhpSpreadsheet İndir:**
   - https://github.com/PHPOffice/PhpSpreadsheet/releases
   - En son sürümü indirin (ZIP)

2. **Vendor Klasörüne Kopyala:**
   ```powershell
   # İndirilen ZIP'i açın
   # phpoffice/phpspreadsheet klasörünü şuraya kopyalayın:
   composer/vendor/phpoffice/
   ```

3. **Autoload Güncelle:**
   - `composer/vendor/composer/autoload_psr4.php` dosyasını düzenleyin
   - PhpSpreadsheet namespace'ini ekleyin

**NOT:** Bu yöntem önerilmez, çünkü bağımlılıkları manuel yönetmeniz gerekir.

---

## 📋 Seçenek 4: Web Sunucusu Üzerinden (Geçici Çözüm)

Eğer web sunucunuz (Apache/Nginx) zaten çalışıyorsa ve PHP yüklüyse:

1. **Composer.phar İndir:**
   ```powershell
   cd C:\Users\Mert\Desktop\web\web-app
   Invoke-WebRequest -Uri "https://getcomposer.org/composer.phar" -OutFile "composer.phar"
   ```

2. **PHP ile Çalıştır:**
   ```powershell
   # Web sunucusunun PHP'sini kullan
   # Örnek: XAMPP için
   C:\xampp\php\php.exe composer.phar install
   
   # Veya WAMP için
   C:\wamp64\bin\php\php8.1.0\php.exe composer.phar install
   ```

3. **PhpSpreadsheet Kur:**
   ```powershell
   cd composer
   C:\xampp\php\php.exe ..\composer.phar require phpoffice/phpspreadsheet
   ```

---

## ✅ Kurulum Sonrası Kontrol

PhpSpreadsheet başarıyla kurulduktan sonra:

```powershell
# composer/vendor/phpoffice klasörünün var olduğunu kontrol edin
Test-Path composer\vendor\phpoffice\phpspreadsheet
```

Sonuç `True` olmalı.

---

## 🚀 Hızlı Başlangıç (XAMPP Kullanıcıları için)

```powershell
# 1. XAMPP'i kurun (https://www.apachefriends.org)

# 2. PHP PATH'e ekleyin (yukarıdaki Seçenek 2'ye bakın)

# 3. Composer Setup'ı indirin ve kurun
#    https://getcomposer.org/Composer-Setup.exe

# 4. PowerShell'i yeniden başlatın

# 5. PhpSpreadsheet'i kurun
cd C:\Users\Mert\Desktop\web\web-app\composer
composer require phpoffice/phpspreadsheet
```

---

## 📞 Yardım

Kurulum sırasında sorun yaşarsanız:

1. PHP'nin kurulu olduğundan emin olun
2. Composer'ın PATH'te olduğundan emin olun
3. Web sunucusunun çalıştığından emin olun

**Not:** `html/generate_b10_excel.php` dosyası hazır, sadece PhpSpreadsheet kurulması gerekiyor.

---

*Son Güncelleme: 2025-01-27*


