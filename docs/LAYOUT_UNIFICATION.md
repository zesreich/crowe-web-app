# Layout Birlesimi Yaklasimi

Bu dokuman, PHP ve HTML tarafinda tekrar eden navigation/layout kodunu azaltmak icin uygulanacak modeli tanimlar.

## PHP Tarafi

- `header.php` ve `footer.php` zaten ortak kaynaktir.
- Hedef: tum `pages/**` ekranlarinin bu ortak dosyalari ayni sirada kullanmasini zorunlu kilmak.
- Kontrol listesi:
  - Sayfa `First.php` bootstrap kullaniyor mu?
  - Header ve footer include zinciri tutarli mi?
  - Sayfa bazli inline menu varyasyonlari kaldirildi mi?

## HTML Tarafi

- Mevcut durumda her sayfa kendi sidebar/topbar bloklarini tekrar ediyor.
- Hedef: ortak layout fragment'leri tek dosyadan yuklemek.

Onerilen yapı:

- `html/layout/sidebar.html`
- `html/layout/topbar.html`
- `html/layout/footer.html`
- `html/layout-loader.js` (fetch + insert)

## Gecis Stratejisi

1. Once dashboard ve client-list gibi yuksek trafik sayfalarda ortak fragment kullan.
2. Sonra tum HTML sayfalarina yay.
3. En sonda tekrar eden HTML bloklarini tamamen kaldir.

## Kabul Kriterleri

- Navigation degisikligi tek dosyada yapildiginda tum sayfalara yansimali.
- Role-based menu gorunurlugu tek yerde kontrol edilmeli.
- Mevcut auth yonlendirmeleri bozulmamali.
