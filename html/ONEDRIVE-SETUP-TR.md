# Microsoft OneDrive Entegrasyonu Kurulum Rehberi

## Gereksinimler
- Microsoft 365 veya OneDrive hesabı
- Azure Active Directory (Azure AD) erişimi

## Adım 1: Azure AD'de Uygulama Kaydı Oluşturma

1. **Azure Portal**'a gidin: https://portal.azure.com
2. Sol menüden **Azure Active Directory** seçin
3. **App registrations** (Uygulama kayıtları) tıklayın
4. **New registration** (Yeni kayıt) butonuna tıklayın

### Uygulama Kayıt Bilgileri:
- **Name (Ad):** `CROWE HSY Auditor OneDrive`
- **Supported account types:** 
  - Seçenek 1: `Accounts in any organizational directory and personal Microsoft accounts` (Herkes için)
  - Seçenek 2: `Accounts in this organizational directory only` (Sadece şirketiniz için)
- **Redirect URI:** 
  - Platform: `Single-page application (SPA)`
  - URI: Uygulamanızın URL'si, örnek:
    - Yerel geliştirme: `http://localhost:3000/backup-7.27.2023_16-13-20_crowehsy/backup-7.27.2023_16-13-20_crowehsy/homedir/public_html/html/auditor-dashboard.html`
    - Canlı site: `https://yourdomain.com/html/auditor-dashboard.html`

5. **Register** (Kaydet) butonuna tıklayın

## Adım 2: API İzinleri Ekleme

1. Oluşturulan uygulama sayfasında sol menüden **API permissions** seçin
2. **Add a permission** tıklayın
3. **Microsoft Graph** seçin
4. **Delegated permissions** seçin
5. Şu izinleri ekleyin:
   - `User.Read` (Kullanıcı bilgisi okuma)
   - `Files.ReadWrite.All` (OneDrive dosyalarını okuma ve yazma)
6. **Add permissions** butonuna tıklayın
7. **Grant admin consent** (Yönetici onayı ver) butonuna tıklayın ve onaylayın

## Adım 3: Client ID'yi Kopyalama

1. Sol menüden **Overview** seçin
2. **Application (client) ID** değerini kopyalayın
3. Bu ID'yi `auditor-dashboard.html` dosyasındaki `clientId` değerine yapıştırın

### auditor-dashboard.html dosyasında güncelleme:

```javascript
const msalConfig = {
    auth: {
        clientId: "BURAYA_KOPYALADIĞINIZ_CLIENT_ID_YAPIŞTIRIN",
        authority: "https://login.microsoftonline.com/common",
        redirectUri: window.location.origin + window.location.pathname
    },
    cache: {
        cacheLocation: "localStorage",
        storeAuthStateInCookie: false
    }
};
```

## Adım 4: Redirect URI Güncelleme (Gerekirse)

Eğer farklı bir URL kullanıyorsanız:

1. Azure Portal'da uygulamanıza gidin
2. **Authentication** seçin
3. **Single-page application** bölümünde **Add URI** tıklayın
4. Yeni URL'nizi ekleyin (örnek: `https://yourdomain.com/auditor-dashboard.html`)
5. **Save** butonuna tıklayın

## Adım 5: Test Etme

1. `auditor-dashboard.html` dosyasını kaydedin
2. Tarayıcıda sayfayı açın
3. "Microsoft ile Giriş Yap" butonuna tıklayın
4. Microsoft hesabınızla giriş yapın
5. İzinleri onaylayın
6. OneDrive dosyalarınız görüntülenecektir

## Özellikler

### ✅ Çalışan Özellikler:
- Microsoft hesabıyla OAuth2 girişi
- OneDrive dosya ve klasör listeleme
- Dosya indirme
- Dosya yükleme
- Klasör oluşturma
- Dosya silme
- Dosya paylaşım linki oluşturma
- Depolama alanı bilgisi görüntüleme
- Dosya önizleme (Microsoft Office Online)

### 📝 Notlar:
- **Güvenlik:** Client ID'niz gizli bilgi değildir, public client olarak kullanılabilir
- **Token Yönetimi:** Access token'lar tarayıcı localStorage'da saklanır
- **Refresh Token:** MSAL.js otomatik olarak token yenileme işlemini yapar
- **Dosya Boyutu:** Büyük dosyalar için chunked upload kullanılmalıdır (şu an max 4MB)

## Sorun Giderme

### Problem: "AADSTS50011: The reply URL specified in the request does not match..."
**Çözüm:** Azure AD'de kayıtlı Redirect URI ile mevcut URL'nin eşleştiğinden emin olun.

### Problem: "AADSTS65001: The user or administrator has not consented..."
**Çözüm:** Azure AD'de API izinlerini ekledikten sonra "Grant admin consent" butonuna tıklayın.

### Problem: "Access token alınamıyor"
**Çözüm:** 
- Tarayıcı konsolunu kontrol edin
- Pop-up blocker'ı devre dışı bırakın
- Gizli mod/InPrivate modda test edin

### Problem: "CORS hatası"
**Çözüm:** Microsoft Graph API CORS destekler, ancak redirect URI'nin doğru yapılandırıldığından emin olun.

## Gelişmiş Yapılandırma (Opsiyonel)

### Sadece Şirket Hesapları İçin:

```javascript
const msalConfig = {
    auth: {
        clientId: "YOUR_CLIENT_ID",
        authority: "https://login.microsoftonline.com/YOUR_TENANT_ID",
        redirectUri: window.location.origin + window.location.pathname
    }
};
```

### Büyük Dosya Yükleme (>4MB):

Büyük dosyalar için Microsoft Graph'ın upload session API'sini kullanmanız gerekir:
https://docs.microsoft.com/en-us/graph/api/driveitem-createuploadsession

## Kaynaklar

- Microsoft Graph API Dokümantasyonu: https://docs.microsoft.com/en-us/graph/
- MSAL.js Dokümantasyonu: https://github.com/AzureAD/microsoft-authentication-library-for-js
- OneDrive API: https://docs.microsoft.com/en-us/graph/api/resources/onedrive

## Destek

Sorularınız için: https://docs.microsoft.com/en-us/answers/topics/microsoft-graph.html

