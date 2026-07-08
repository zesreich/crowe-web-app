# Supabase Test Users (Auth)

Bu projede login önce Supabase Auth dener; kullanıcı yoksa veya hata olursa
development fallback (`config.js` FALLBACK_ADMINS) devreye girer.

## Local test hesabı (anında çalışır)

- **Email:** `admin.test@crowehsy.net`
- **Password:** `Crowe2022!`
- **URL:** http://localhost:8000/login.html

Alternatif (aynı şifre):

- `cursor.demo@crowehsy.net` / `Crowe2022!`

## Supabase Dashboard'da kullanıcı oluşturma (opsiyonel)

1. Supabase Dashboard'a girin.
2. Sol menü `Authentication` -> `Users`.
3. `Add user` butonuna basın.
4. Email ve password girin.
5. `Auto Confirm User` aktif olsun.
6. Kaydedin.

Örnek:

1. `cursor.demo@crowehsy.net` / `Crowe2022!`
2. `admin.test@crowehsy.net` / `Crowe2022!`

## Login testi

- URL: `http://localhost:8000/login.html`
- Email: `admin.test@crowehsy.net`
- Password: `Crowe2022!`
- Beklenen: `dashboard.html` yönlendirmesi
