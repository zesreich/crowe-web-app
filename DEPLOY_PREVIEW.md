# Deploy & Preview Rehberi (GitHub + Vercel)

Eski Vercel hesabını kaybettiyseniz sorun değil. Aşağıdaki akışla **önce test/preview**, sonra **production** yapabilirsiniz.

---

## 3 katmanlı test akışı

| Katman | Ne test eder? | Komut / URL |
|--------|---------------|-------------|
| **1. Yerel** | UI, login, Atlas shell | `npm run dev` → http://localhost:8000/login.html |
| **2. Vercel Preview** | Gerçek Vercel ortamı, HTTPS, PHP API | `npm run vercel:preview` veya GitHub PR |
| **3. Production** | Canlı site | `npm run vercel:prod` veya main branch merge |

**Öneri:** Production'a geçmeden önce en az Katman 1 + Katman 2'yi tamamlayın.

---

## Adım 1 — Yerel preview (Vercel'e dokunmadan)

```bash
cd web/web-app
npm run dev
```

Tarayıcıda açın:
- http://localhost:8000/login.html
- http://localhost:8000/dashboard.html
- http://localhost:8000/ecosystem-mockup/

Yerel `html/config.js` dosyanız zaten var; git'e commit edilmez.

Test kullanıcısı (development):
- **E-posta:** `admin.test@crowehsy.net`
- **Şifre:** `Crowe2022!`

---

## Adım 2 — Yeni Vercel hesabı + GitHub bağlantısı

### 2a. Eski Vercel bağlantısını temizleyin

Proje klasöründe eski hesaba bağlı `.vercel` varsa silin:

```bash
cd web/web-app
rm -rf .vercel
```

### 2b. GitHub repo

Repo: `https://github.com/zesreich/crowe-web-app`

Değişiklikleri push edin:

```bash
git add .
git commit -m "Vercel preview deploy yapılandırması"
git push origin main
```

### 2c. Yeni Vercel hesabı

1. https://vercel.com → **Sign Up** → **Continue with GitHub**
2. **Add New → Project**
3. `zesreich/crowe-web-app` reposunu seçin
4. **Root Directory:** `web/web-app` değil — repo kökü zaten `web-app` ise boş bırakın  
   *(Repo doğrudan web-app içeriğiyse root boş kalır)*
5. **Framework Preset:** Other
6. **Build Command:** `node scripts/build-config.js` *(vercel.json'dan otomatik gelir)*
7. **Install Command:** `cd composer && composer install --no-dev --optimize-autoloader`
8. **Output Directory:** boş bırakın

> **Önemli:** İlk deploy'da **Production** yerine önce preview branch kullanın (Adım 3).

### 2d. Ortam değişkenleri (Vercel Dashboard → Settings → Environment Variables)

| Değişken | Preview | Production | Açıklama |
|----------|---------|------------|----------|
| `SUPABASE_URL` | ✓ | ✓ | Supabase proje URL |
| `SUPABASE_ANON_KEY` | ✓ | ✓ | Supabase anon key |
| `APP_ENV` | `development` | `production` | Fallback admin kontrolü |
| `ALLOW_FALLBACK_ADMINS` | `true` | `false` | Preview'da test login |
| `API_BASE_URL` | *(boş bırakın)* | custom domain | Boşsa Vercel URL kullanılır |

`API_BASE_URL` boş bırakılırsa build script otomatik `https://<vercel-url>` yazar.

---

## Adım 3 — Preview deploy (production olmadan)

### Seçenek A: Branch + Pull Request (önerilen)

```bash
git checkout -b preview/atlas-ui
git push -u origin preview/atlas-ui
```

GitHub'da PR açın → Vercel otomatik **Preview Deployment** URL'i yorum olarak ekler:
`https://crowe-web-app-xxxxx.vercel.app`

Bu URL **production değildir**. Test edin, onaylayın, sonra merge edin.

### Seçenek B: Vercel CLI ile preview

```bash
npm i -g vercel
cd web/web-app
vercel login          # yeni hesap
vercel link           # yeni projeye bağla
vercel                # preview deploy (--prod YOK!)
```

CLI size geçici preview URL verir. `--prod` kullanmayın.

### Preview'da test checklist

- [ ] `/login.html` açılıyor
- [ ] Login → dashboard yönlendirmesi çalışıyor
- [ ] Tema (açık/koyu) çalışıyor
- [ ] Supabase auth (varsa) çalışıyor
- [ ] `/api/generate_b10_excel` endpoint'i yanıt veriyor (Excel özelliği kullanılıyorsa)

---

## Adım 4 — Production'a alma

Preview'dan memnun kaldıktan sonra:

**GitHub ile:** PR'ı `main`'e merge edin → Vercel production deploy tetiklenir.

**CLI ile:**
```bash
vercel --prod
```

Production'da `APP_ENV=production` ve `ALLOW_FALLBACK_ADMINS=false` olduğundan emin olun.

---

## Sık sorulan sorular

### Eski Vercel projesi ne olacak?
Erişiminiz yoksa yeni hesapta sıfırdan proje oluşturursunuz. GitHub repo aynı kalabilir.

### config.js neden git'te yok?
Hassas bilgiler içerir. Vercel build sırasında `scripts/build-config.js` ortam değişkenlerinden üretir.

### Yerel config.js ile Vercel config.js farklı mı?
Evet. Yerelde kendi `html/config.js` dosyanız; Vercel'de build script üretir.

### Production deploy'u nasıl engellerim?
Vercel Dashboard → Project → Settings → Git → **Production Branch** = `main`  
Preview branch'ler (`preview/*`) otomatik preview URL alır, production'a gitmez.

---

## Hızlı komut özeti

```bash
# Yerel test
npm run dev

# Vercel preview (production değil)
npm run vercel:preview

# Production (dikkatli!)
npm run vercel:prod
```
