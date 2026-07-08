# Yeni Supabase Projesi — Kurulum Rehberi

Sıfırdan açtığınız Supabase projesini Crowe HSY paneli ile bağlamak için adım adım rehber.

**Tahmini süre:** 15–20 dakika

---

## Adım 1 — API bilgilerini al

1. https://supabase.com/dashboard → yeni projenizi açın
2. Sol menü **Project Settings** (dişli) → **API**
3. Şunları not edin:

| Alan | Nerede | Örnek |
|------|--------|-------|
| **Project URL** | Project URL | `https://abcdefgh.supabase.co` |
| **anon public key** | Project API keys → `anon` `public` | `eyJhbGci...` |

> `service_role` key'i **asla** frontend'e koymayın.

---

## Adım 2 — Veritabanı tablolarını oluştur

1. Dashboard → **SQL Editor**
2. **New query**
3. Projedeki `supabase-yeni-proje-kurulum.sql` dosyasının **tüm içeriğini** yapıştırın
4. **Run** (veya Ctrl+Enter)

Başarılı olursa sonuçta 6 tablo görürsünüz:
- `clients`
- `payments`
- `reports`
- `contracts`
- `offers`
- `online_sessions`

**Table Editor**'dan da kontrol edebilirsiniz.

> Eski SQL dosyalarını (`supabase-complete.sql`, `SUPABASE_READY.sql` vb.) **çalıştırmayın** — bunlar artık kullanılmıyor.

---

## Adım 3 — Auth kullanıcıları oluştur

Panel **Supabase Auth** kullanır (custom `users` tablosu değil).

1. Dashboard → **Authentication** → **Users**
2. **Add user** → **Create new user**
3. İlk test kullanıcısı:

| Alan | Değer |
|------|-------|
| Email | `admin.test@crowehsy.net` |
| Password | `Crowe2022!` |
| Auto Confirm User | ✅ Açık |

4. İsteğe bağlı — gerçek adminler için aynı adımla ekleyin:
   - `mert.cengiz@crowehsy.net`
   - `ozkan.cengiz@crowehsy.net`
   - vb.

### Kullanıcı metadata (opsiyonel ama önerilir)

Kullanıcı satırına tıklayın → **Raw User Meta Data**:

```json
{
  "full_name": "Admin Test",
  "role": "admin"
}
```

---

## Adım 4 — Email auth ayarları

1. **Authentication** → **Providers** → **Email**
2. **Enable Email provider** açık olsun
3. Geliştirme için **Confirm email** kapalı olabilir (Auto Confirm ile birlikte)

---

## Adım 5 — Yerel config.js güncelle

`web/web-app/html/config.js` dosyasını açın ve Supabase satırlarını yeni projenizle değiştirin:

```javascript
window.SUPABASE_URL = window.SUPABASE_URL || 'https://SIZIN-PROJE-ID.supabase.co';
window.SUPABASE_ANON_KEY = window.SUPABASE_ANON_KEY || 'SIZIN-ANON-KEY';
```

Aynı dosyada development modu kalsın:

```javascript
const CONFIG_APP_ENV = 'development';
```

> `config.js` git'e commit edilmez (.gitignore'da).

---

## Adım 6 — Yerel sunucu ile test

```bash
cd web/web-app
npm run dev
```

Tarayıcı: http://localhost:8000/login.html

### Login testi

- Email: `admin.test@crowehsy.net`
- Password: `Crowe2022!`
- Beklenen: `dashboard.html` açılır

### Supabase bağlantı testi (F12 → Console)

Başarılı girişte şunları görmelisiniz:
- `✅ Global Supabase client instance oluşturuldu`
- Supabase login failed **görmemelisiniz** (fallback'e düşmemeli)

### Veritabanı testi

1. http://localhost:8000/client-list.html
2. Yeni müşteri ekleyin
3. Supabase → **Table Editor** → `clients` → kayıt görünmeli

"Demo Musteri A.S." zaten SQL ile eklenmiş olmalı.

---

## Adım 7 — Vercel için env (deploy öncesi)

Vercel Dashboard → Project → Settings → Environment Variables:

| Değişken | Preview | Production |
|----------|---------|------------|
| `SUPABASE_URL` | ✅ | ✅ |
| `SUPABASE_ANON_KEY` | ✅ | ✅ |
| `APP_ENV` | `development` | `production` |
| `ALLOW_FALLBACK_ADMINS` | `true` | `false` |

Vercel build sırasında `scripts/build-config.js` bu değerlerden `config.js` üretir.

---

## Sorun giderme

### "Invalid login credentials"
- Kullanıcı Authentication → Users'da var mı?
- Auto Confirm açık mı?
- Email küçük harfle yazıldı mı?

### "new row violates row-level security policy"
- Supabase Auth ile giriş yapıldı mı? (Fallback login DB'ye yazamaz)
- Console'da session var mı: `localStorage.getItem('auth_token')`

### Veriler localStorage'a kaydediliyor
- `config.js` URL/key doğru mu?
- Console'da Supabase hata mesajına bakın
- Network sekmesinde `supabase.co` istekleri 401/403 mü?

### Eski projeye bağlanıyor
- `config.js` içindeki URL'yi kontrol edin
- Tarayıcı cache: hard refresh (Cmd+Shift+R)

---

## Kontrol listesi

- [ ] SQL çalıştırıldı (6 tablo)
- [ ] Auth kullanıcısı oluşturuldu
- [ ] `config.js` güncellendi
- [ ] Login → dashboard çalışıyor
- [ ] client-list → Supabase'e kayıt yazıyor
- [ ] Vercel env değişkenleri hazır (deploy için)

---

## Sonraki adım

Kurulum tamamlandıktan sonra GitHub + Vercel preview deploy için `DEPLOY_PREVIEW.md` dosyasına geçin.
