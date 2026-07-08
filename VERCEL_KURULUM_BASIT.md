# Vercel Kurulum — 2 Adım

GitHub push tamam. Vercel bağlantısı için sadece şunu yapın:

## Adım 1 — Terminalde tek komut

```bash
cd /Users/mertcengiz/Desktop/hsy.com/web/web-app
npm run vercel:setup
```

Tarayıcı açılınca **zesreich** GitHub hesabıyla Vercel'e giriş yapın. Gerisini script halleder:
- Proje bağlantısı
- Supabase ayarları
- Preview site deploy

## Adım 2 — Test edin

Script sonunda verilen URL'yi açın, örneğin:
`https://crowe-web-app-xxxxx.vercel.app/login.html`

Giriş:
- **E-posta:** `admin.test@crowehsy.net`
- **Şifre:** `Crowe2022!`

---

## Alternatif: Tarayıcıdan (komut istemezseniz)

1. https://vercel.com/new adresine gidin
2. **Continue with GitHub** → `zesreich` hesabı
3. **Import** → `crowe-web-app` reposunu seçin
4. **Deploy** butonuna basın (ayarları değiştirmeyin, vercel.json hazır)
5. Deploy bitince **Settings → Environment Variables** bölümüne gidin ve ekleyin:

| İsim | Değer |
|------|-------|
| `SUPABASE_URL` | `https://frktkxxszwlspyziecmc.supabase.co` |
| `SUPABASE_ANON_KEY` | *(config.js dosyanızdaki anon key)* |
| `APP_ENV` | `development` |
| `ALLOW_FALLBACK_ADMINS` | `true` |

6. **Redeploy** yapın

---

## Sorun olursa

Terminalde:
```bash
npm run vercel:setup
```
tekrar çalıştırın.
