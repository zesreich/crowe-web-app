# Fazli Gecis, Test ve Rollback Plani

## Fazlar

1. Faz 1 - Guvenlik:
   - Env tabanli mail ayarlari
   - Hardcoded credential temizligi
2. Faz 2 - API standardizasyonu:
   - `action` parametresi ve geriye uyumluluk
3. Faz 3 - Layout sadeleme:
   - HTML tekrarli bloklarin ortak fragmentlere alinmasi
4. Faz 4 - SEO baseline:
   - robots/sitemap/metadata

## Smoke Test Senaryolari

- Login:
  - `post/kullaniciPost.php?action=login`
  - `post/kullaniciPost.php?tur=login`
  - Basarili ve hatali sifre durumlari
- Session:
  - Giris sonrasi yetkili sayfalara erisim
  - Logout sonrasi korumali sayfa redirection
- Mail:
  - Env ayarlari eksikken `mailGonder` guvenli sekilde false donmeli
  - Env ayarlari tamken iletim basarisi dogrulanmali
- SEO:
  - `robots.txt` erisilebilir
  - `sitemap.xml` XML valid
  - Landing sayfalarda canonical/OG etiketleri gorunur

## Rollback Stratejisi

- Kritik dosyalar:
  - `soa/genelSoa.php`
  - `post/kullaniciPost.php`
  - `pages/index.php`
  - `html/index.html`
- Sorun halinde:
  1. API degisiklikleri gecici olarak sadece `tur` kullanimina alinabilir.
  2. Mail gonderimi env eksikliginde devre disi kalir (guvenli fallback).
  3. SEO dosyalari (`robots.txt`, `sitemap.xml`) geri alinabilir; is akisini etkilemez.

## Canliya Alim Kontrolu

- `.env` production degerleri set edildi mi?
- Login akislari iki parametre modelinde de dogrulandi mi?
- Kiritik ekranlarda PHP hata logu temiz mi?
- Arama motoru dosyalari dogru domainde yayinlandi mi?
