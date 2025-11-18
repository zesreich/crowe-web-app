# Supabase Clients Tablosu Kurulum Rehberi

Bu rehber, Excel'den içe aktarılan müşteri verilerini Supabase'de saklamak için gerekli adımları içerir.

## 1. Supabase Tablosunu Oluşturma

1. Supabase Dashboard'a giriş yapın: https://app.supabase.com
2. Projenizi seçin
3. Sol menüden **SQL Editor**'a tıklayın
4. `supabase-clients-table.sql` dosyasının içeriğini kopyalayın
5. SQL Editor'a yapıştırın ve **Run** butonuna tıklayın

Bu işlem şunları oluşturur:
- `clients` tablosu
- Gerekli index'ler
- Otomatik `updated_at` trigger'ı
- Row Level Security (RLS) politikaları

## 2. Supabase Yapılandırması

`auth.js` dosyasında Supabase URL ve API Key'inizi ayarlayın:

```javascript
const SUPABASE_URL = 'https://your-project.supabase.co';
const SUPABASE_ANON_KEY = 'your-anon-key-here';
```

Veya HTML sayfalarında:

```html
<script>
    window.SUPABASE_URL = 'https://your-project.supabase.co';
    window.SUPABASE_ANON_KEY = 'your-anon-key-here';
</script>
```

## 3. Tablo Yapısı

`clients` tablosu şu kolonlara sahiptir:

| Kolon | Tip | Açıklama |
|-------|-----|----------|
| `id` | UUID | Primary key (otomatik) |
| `vergi_no` | VARCHAR(50) | Vergi numarası (unique) |
| `unvan` | VARCHAR(255) | Şirket unvanı |
| `vergi_dairesi` | VARCHAR(255) | Vergi dairesi |
| `ekip` | VARCHAR(100) | Ekip adı |
| `created_at` | TIMESTAMP | Oluşturulma tarihi (otomatik) |
| `updated_at` | TIMESTAMP | Güncellenme tarihi (otomatik) |
| `created_by` | UUID | Oluşturan kullanıcı (opsiyonel) |

## 4. Row Level Security (RLS)

RLS politikaları şunları sağlar:
- ✅ Authenticated kullanıcılar tüm client'ları görebilir
- ✅ Authenticated kullanıcılar client ekleyebilir
- ✅ Authenticated kullanıcılar client güncelleyebilir
- ✅ Authenticated kullanıcılar client silebilir

## 5. Özellikler

### Otomatik Fallback
Eğer Supabase yapılandırılmamışsa veya bağlantı hatası olursa, sistem otomatik olarak localStorage'a geri döner.

### Excel Import
- Excel'den toplu client import'u
- Batch insert ile hızlı kayıt
- Hata durumunda localStorage'a fallback

### CRUD İşlemleri
- ✅ **Create**: Yeni client ekleme
- ✅ **Read**: Client listesini görüntüleme
- ✅ **Update**: Client güncelleme
- ✅ **Delete**: Client silme

## 6. Test Etme

1. Supabase Dashboard'da **Table Editor**'a gidin
2. `clients` tablosunu seçin
3. `client-list.html` sayfasında yeni bir client ekleyin
4. Supabase'de kaydın oluşturulduğunu kontrol edin

## 7. Sorun Giderme

### "relation 'clients' does not exist" hatası
- SQL dosyasını Supabase'de çalıştırdığınızdan emin olun

### "new row violates row-level security policy" hatası
- RLS politikalarının doğru ayarlandığından emin olun
- Kullanıcının authenticated olduğundan emin olun

### Veriler görünmüyor
- Browser console'u kontrol edin (F12)
- Supabase bağlantısını kontrol edin
- localStorage'a fallback olup olmadığını kontrol edin

## 8. Migration (localStorage'dan Supabase'e)

Mevcut localStorage verilerini Supabase'e taşımak için:

```javascript
// Browser console'da çalıştırın
const clients = JSON.parse(localStorage.getItem('clients') || '[]');
const supabase = getSupabaseClient();

if (supabase && clients.length > 0) {
    const clientsToInsert = clients.map(client => ({
        vergi_no: client.vergiNo,
        unvan: client.unvan,
        vergi_dairesi: client.vergiDairesi,
        ekip: client.ekip
    }));
    
    const { data, error } = await supabase
        .from('clients')
        .insert(clientsToInsert);
    
    if (!error) {
        console.log(`${clients.length} client başarıyla Supabase'e taşındı!`);
    }
}
```

## 9. Destek

Sorun yaşarsanız:
1. Browser console'u kontrol edin
2. Supabase Dashboard'da logları kontrol edin
3. Network tab'ında API isteklerini kontrol edin

