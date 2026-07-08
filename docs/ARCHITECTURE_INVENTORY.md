# Crowe HSY Mimari Envanteri

Bu dokuman, PHP tabanli ana uygulama ile HTML panelinin sorumluluk sinirlarini tek yerde toplar.

## 1) Uygulama Katmanlari

- `pages/`: Sunum katmani (ekranlar)
- `post/`: Islem endpointleri (AJAX/form)
- `soa/`: Is kurallari, servis fonksiyonlari
- `entity/`: Veri modelleri/tablo nesneleri
- `db/`: Baglanti ve CRUD altyapisi
- `config/`: Ortam ve uygulama ayarlari
- `helpers/`: Guvenlik ve ortak yardimci fonksiyonlar
- `api/`: Vercel serverless endpointleri
- `html/`: Ayrik statik panel (Supabase destekli)

## 2) Giris Noktalari

- Ana uygulama bootstrap: `index.php`, `First.php`
- PHP ekranlari: `pages/**`
- Islem endpointleri: `post/*.php` (genelde `?tur=` ile aksiyon secimi)
- HTML panel girisleri: `html/index.html`, `html/home.html`, `html/login.html`

## 3) Sorumluluk Haritasi

### PHP Uygulamasi (Primary)

- Kimlik dogrulama/session: `post/kullaniciPost.php`, `First.php`
- Yetki/menu uretilmesi: `header.php`, `config/`, `soa/`
- Is surecleri:
  - Musteri: `pages/is/musteri*.php`
  - Denetim/Planlama: `pages/is/denetim/**`, `pages/is/planlama/**`
  - Sozlesme/Teklif: `pages/is/sozlesme/**`, `pages/is/denetim/teklif*.php`
- Veri akis modeli:
  1. `pages/*` UI tetikler
  2. `post/*` istegi alir
  3. `soa/*` is kuralini calistirir
  4. `db/Crud.php` uzerinden DB erisimi saglanir

### HTML Panel (Secondary)

- Ekranlar: `html/*.html`
- Auth katmani: `html/auth.js`
- Supabase konfigrasyonu: `html/supabase-config.js`, `html/config.js`
- API base ayarlari: `html/api-config.js`
- Not: Bu panelde navigation/layout yapisi sayfa bazinda tekrarli.

## 4) Ortak Bilesenler ve Layout

- PHP tarafi:
  - Header/nav/sidebar: `header.php`
  - Footer: `footer.php`
- HTML tarafi:
  - Ortak partial mekanizmasi yok, menu bloklari sayfalarda tekrar ediyor.

## 5) Cevrilmesi Gereken Teknik Borclar

- `post/*.php?tur=...` endpoint modeli standardize edilmeli.
- HTML panelde tekrarli layout tek kaynaga indirilmeli.
- SEO metadata ve arama motoru dosyalari (`robots.txt`, `sitemap.xml`) tumlenmeli.
- Credential ve fallback login davranislari production-safe hale getirilmeli.
