# Microsoft OneDrive Entegrasyonu Kurulum Rehberi

## Gereksinimler
- Microsoft 365 veya OneDrive hesabı
- Azure Active Directory (Azure AD) erişimi

## Adım 1: Azure AD'de Uygulama Kaydı Oluşturma

1. **Azure Portal**'a gidin: https://portal.azure.com
2. Sol menüden **Microsoft Entra ID** seçin (eski adı: Azure Active Directory)
   - Alternatif: Portal'ın üst kısmındaki arama çubuğuna "App registrations" yazın
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
   - `Files.Read.All` (OneDrive dosyalarını okuma - **Paylaşılan dosyalar için gerekli**)
   - `Files.ReadWrite.All` (OneDrive dosyalarını okuma ve yazma - Opsiyonel, sadece yazma gerekiyorsa)
   - `Sites.Read.All` (SharePoint sitelerindeki dosyaları okuma)
   - `Files.Read.Selected` (Seçili dosyaları okuma - **Paylaşılan dosyalar için downloadUrl almak için eklendi**)
   - `Files.ReadWrite.AppFolder` (Uygulama klasörüne okuma/yazma - **Paylaşılan dosyalar için downloadUrl almak için eklendi**)
6. **Add permissions** butonuna tıklayın
7. **Grant admin consent** (Yönetici onayı ver) butonuna tıklayın ve onaylayın

### ⚠️ Önemli Notlar:
- **Paylaşılan dosyaları görmek için** `Files.Read.All` izni **mutlaka** eklenmelidir
- **Dosya izinlerini görmek için** `Files.Read.All` yeterlidir
- **Paylaşılan dosyalar için downloadUrl almak için** `Files.Read.Selected` ve `Files.ReadWrite.AppFolder` izinleri eklendi
- Eğer sadece okuma yapılacaksa `Files.Read.All` yeterli, yazma gerekiyorsa `Files.ReadWrite.All` ekleyin

## Adım 3: Client ID'yi Kopyalama

1. Sol menüden **Overview** seçin
2. **Application (client) ID** değerini kopyalayın
3. Bu ID'yi `auditor-dashboard.html` dosyasındaki `clientId` değerine yapıştırın

## Adım 3.5: Certificates & Secrets Ekleme (Opsiyonel - Server-Side Authentication İçin)

Eğer server-side (backend) authentication kullanacaksanız, client secret oluşturmanız gerekir:

1. Azure Portal'da uygulamanızın sayfasında sol menüden **Certificates & secrets** seçin
2. **Client secrets** sekmesine gidin
3. **New client secret** butonuna tıklayın
4. **Description (Açıklama)** alanına bir açıklama girin (örn: "Production Secret" veya "Development Secret")
5. **Expires (Süre dolma)** seçeneğini belirleyin:
   - **6 months** (6 ay)
   - **12 months** (12 ay)
   - **24 months** (24 ay)
   - **Never** (Asla - Önerilmez, güvenlik riski)
6. **Add** butonuna tıklayın
7. **⚠️ ÖNEMLİ:** Secret değeri (Value) sadece bir kez gösterilir! Hemen kopyalayın ve güvenli bir yere kaydedin
8. Secret değerini kopyaladıktan sonra sayfayı yenilediğinizde artık göremezsiniz

### Client Secret Kullanımı:

Client secret, backend (server-side) uygulamalarda kullanılır. Frontend (browser) uygulamalarında **ASLA** kullanılmamalıdır çünkü güvenlik riski oluşturur.

**Güvenlik Notları:**
- Client secret'ı asla frontend koduna eklemeyin
- Client secret'ı asla Git repository'ye commit etmeyin
- Client secret'ı environment variable olarak saklayın
- Secret'ın süresi dolmadan önce yeni bir secret oluşturup eski olanı silin

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

1. Azure Portal'da **Microsoft Entra ID** → **App registrations** → uygulamanıza gidin
2. Sol menüden **Authentication** seçin
3. **Single-page application** bölümünde **Add URI** tıklayın
4. Yeni URL'nizi ekleyin (örnek: `https://www.crowehsy.com/auditor-dashboard.html`)
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

