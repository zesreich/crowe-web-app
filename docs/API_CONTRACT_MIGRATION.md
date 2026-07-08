# API Sozlesmesi ve Gecis Plani

Bu dosya, mevcut `?tur=` modelinden daha okunabilir bir API sozlesmesine gecis yolunu tanimlar.

## Mevcut Durum

- Endpointler agirlikla `post/*.php?tur=<aksiyon>` seklinde calisiyor.
- Aksiyon secimi query param ile yapildigi icin dokumantasyon ve test zorlasiyor.

## Hedef Sozlesme

Yeni modelde iki yol desteklenecek:

1. Eski uyumluluk:
   - `post/kullaniciPost.php?tur=login`
2. Yeni model:
   - `post/kullaniciPost.php?action=login`

Not: Ilk fazda sadece aksiyon anahtarini standardize ediyoruz. Dosya bazli endpoint ayrimi bir sonraki faz.

## Uygulanan Gecis

- `post/kullaniciPost.php` icinde `action` parametresi birincil hale getirildi.
- `tur` parametresi geriye uyumluluk icin korunuyor.

## Sonraki Faz (Onerilen)

1. Kritik endpointler icin sabit route aliaslari olustur:
   - `/api/auth/login` -> `post/kullaniciPost.php?action=login`
   - `/api/auth/logout` -> `post/kullaniciPost.php?action=logout`
2. Tuketici kodlari kademeli olarak `action` modeline tasinmali.
3. Son fazda `tur` kullanimi deprecated edilip log ile takip edilmeli.

## Test Matrisi

- `action=login` ve `tur=login` davranislari esit olmali.
- Hata durumlarinda JSON semasi korunmali.
- CSRF/rate limit kontrolleri yeni anahtarda da ayni sekilde calismali.
