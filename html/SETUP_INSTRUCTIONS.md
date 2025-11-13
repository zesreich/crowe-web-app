# Supabase Kurulum Talimatları

## 1. Şifre Hash'lerini Oluşturma

### Node.js ile (Önerilen)

1. **Node.js ve npm'in yüklü olduğundan emin olun**
   ```bash
   node --version
   npm --version
   ```

2. **Bağımlılıkları yükleyin**
   ```bash
   npm install
   ```

3. **Hash'leri oluşturun**
   ```bash
   npm run generate-hashes
   ```
   veya
   ```bash
   node generate-password-hashes.js
   ```

4. **Çıktıdaki SQL INSERT statement'larını kopyalayın**

### Online Tool ile (Alternatif)

1. [bcrypt-generator.com](https://bcrypt-generator.com/) gibi bir siteye gidin
2. Her şifre için hash oluşturun:
   - `Frankfurt2025!` → Eda Meric Sefer için
   - `Munich2025!` → Ozkan Cengiz için
   - `Nuremberg2025!` → Mehmet Ali Sariad için
   - `Berlin2025!` → Mert Cengiz için
3. Oluşturulan hash'leri not edin

## 2. Supabase Kurulumu

### Adım 1: Supabase Projesi Oluşturma

1. [Supabase](https://supabase.com) sitesine gidin
2. Hesap oluşturun veya giriş yapın
3. "New Project" butonuna tıklayın
4. Proje bilgilerini doldurun:
   - Project Name: `crowe-hsy`
   - Database Password: Güçlü bir şifre seçin (not edin!)
   - Region: Size en yakın bölgeyi seçin
5. "Create new project" butonuna tıklayın
6. Projenin oluşturulmasını bekleyin (2-3 dakika)

### Adım 2: SQL Schema'yı Çalıştırma

1. Supabase Dashboard'da **SQL Editor** sekmesine gidin
2. **New Query** butonuna tıklayın
3. `supabase-complete.sql` dosyasının içeriğini kopyalayın
4. SQL Editor'e yapıştırın
5. **Placeholder hash'leri değiştirin:**
   - `$2b$10$YOUR_BCRYPT_HASH_HERE` yerine gerçek hash'leri yapıştırın
   - Her kullanıcı için ayrı ayrı değiştirin
6. **Run** butonuna tıklayın veya `Ctrl+Enter` tuşlarına basın

### Adım 3: Kullanıcıları Kontrol Etme

1. SQL Editor'de şu sorguyu çalıştırın:
   ```sql
   SELECT username, full_name, email, role, is_active 
   FROM users 
   WHERE role = 'admin';
   ```
2. 4 admin kullanıcının listelendiğini kontrol edin

## 3. Supabase Credentials'ları Alma

### API Keys

1. Dashboard'da **Settings** → **API** sekmesine gidin
2. Şu bilgileri not edin:
   - **Project URL**: `https://xxxxx.supabase.co`
   - **anon/public key**: `eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9...`
   - **service_role key**: (Güvenlik için saklayın, production'da kullanmayın)

### Database Connection String

1. **Settings** → **Database** sekmesine gidin
2. **Connection string** bölümünden **URI** formatını kopyalayın
3. Format: `postgresql://postgres:[YOUR-PASSWORD]@db.xxxxx.supabase.co:5432/postgres`

## 4. Auth.js'i Güncelleme (İsteğe Bağlı)

Eğer Supabase Auth kullanmak isterseniz:

1. `auth.js` dosyasını açın
2. Supabase credentials'larınızı ekleyin:
   ```javascript
   const SUPABASE_URL = 'https://xxxxx.supabase.co';
   const SUPABASE_ANON_KEY = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9...';
   ```

## 5. Test Etme

1. Login sayfasına gidin
2. Admin kullanıcılardan biriyle giriş yapın:
   - Username: `eda.meric`
   - Password: `Frankfurt2025!`
3. Başarıyla giriş yapıp dashboard'a yönlendirildiğinizi kontrol edin

## Sorun Giderme

### Hash'ler Çalışmıyor

- Hash oluştururken bcrypt cost factor 10 kullanıldığından emin olun
- Hash formatı `$2b$10$...` ile başlamalı

### SQL Hatası Alıyorum

- Supabase SQL Editor'de hatayı kontrol edin
- Tüm tabloların oluşturulduğundan emin olun
- RLS (Row Level Security) politikalarının doğru çalıştığını kontrol edin

### Kullanıcı Giriş Yapamıyor

- `auth.js` dosyasındaki kullanıcı bilgilerini kontrol edin
- Browser console'da hata mesajlarını kontrol edin
- localStorage'da `auth_user` ve `auth_token` değerlerini kontrol edin

## Önemli Notlar

⚠️ **Güvenlik:**
- Production'da şifreleri asla plain text olarak saklamayın
- Supabase Auth kullanarak gerçek authentication yapın
- `service_role` key'ini asla client-side'da kullanmayın
- HTTPS kullanın

⚠️ **Backup:**
- Database'ı düzenli olarak yedekleyin
- SQL script'lerini versiyon kontrolünde tutun

## Destek

Sorun yaşarsanız:
1. Browser console'u kontrol edin
2. Supabase logs'ları kontrol edin
3. SQL sorgularını test edin







