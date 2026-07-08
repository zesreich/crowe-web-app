# Guvenlik Sertlestirme Plani

Bu dosya, kod tabaninda tespit edilen hassas bilgi riskleri ve alinacak aksiyonlari listeler.

## Kritik Bulgular

- Frontend fallback admin ve sifre davranisi (`html/config.js`, `html/auth.js`)
- SMTP bilgilerinin kodda sabit gecmesi (`soa/genelSoa.php`)
- Public istemci ayarlari ile production ayarlari arasinda net ayrim eksikligi (`html/supabase-config.js`)

## Uygulanan Duzeltmeler

1. SMTP bilgileri koddan kaldirildi ve `Config` sinifi uzerinden env tabanli okunmaya alindi.
2. Mail fonksiyonlarina runtime config kontrolu eklendi (eksik ayarda guvenli sekilde fail).
3. Frontend fallback davranisinin production ortamina tasinmamasi icin kural seti netlestirildi.

## Zorunlu Ortam Degiskenleri

- `MAIL_HOST`
- `MAIL_USER`
- `MAIL_PASS`
- `MAIL_PORT`
- `MAIL_ENCRYPTION`
- `MAIL_ISIM`

Opsiyonel:

- `APP_ENV` (`production` / `development`)
- `BASE_LINK`

## Operasyonel Kontroller

- Production icin `APP_ENV=production` zorunlu.
- Frontend fallback sifre/admin listesi production'da bos kalmali.
- `.env` dosyasi versiyon kontrolune dahil edilmemeli.
- Mail testi sadece test alicisiyla ve non-production ortamda yapilmali.

## Sonraki Adimlar

- `html/config.js` icin `config.local.js` override kalibi dokumante edilmeli.
- SMTP icin app password rotasyonu ve periyodik anahtar yenileme uygulanmali.
- Login endpointleri icin merkezi audit log mekanizmasi eklenmeli.
