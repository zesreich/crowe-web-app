// Internationalization (i18n) System
// Default language: English

const i18n = {
    currentLang: 'en',
    
    translations: {
        en: {
            // Common
            'welcome': 'WELCOME!',
            'companyName': 'CROWE HSY',
            'platformDescription': 'Financial Advisor Management Platform',
            'login': 'Login',
            'logout': 'Logout',
            'profile': 'Profile',
            'dashboard': 'Dashboard',
            'clients': 'Clients',
            'select': 'SELECT',
            'back': 'Back',
            'backToHome': 'Back to Home',
            'forgotPassword': 'Forgot Password?',
            
            // Index Page
            'interfacePreview': 'Crowe HSY Interface Preview',
            'interfacePreviewDescription': 'You can review different page views from the buttons below:',
            'loginPage': 'Login Page',
            'loginPageDescription': 'Login screen',
            'dashboardDescription': 'Main panel',
            'clientList': 'Client List',
            'clientListDescription': 'Client listing',
            'clientDetail': 'Client Detail',
            'clientDetailDescription': 'Client detail page',
            
            // Login Page
            'username': 'Username...',
            'password': 'Password...',
            'loginButton': 'Login',
            'twoFactorCode': '2FA Code (6 digits)',
            'twoFactorCodeDescription': 'Enter the 6-digit code sent to your email address.',
            'loginError': 'Username or password cannot be empty.',
            'loginError2FA': '2FA code is required (6 digits).',
            'loginErrorWrong': 'Username or password is incorrect.',
            
            // Dashboard
            'totalClients': 'Total Clients',
            'activeAudits': 'Active Audits',
            'pendingOffers': 'Pending Offers',
            'totalUsers': 'Total Users',
            'monthlyTransactionChart': 'Monthly Transaction Chart',
            'recentActivities': 'Recent Activities',
            'newClientAdded': 'New Client Added',
            'auditCompleted': 'Audit Completed',
            'offerSent': 'Offer Sent',
            'hoursAgo': 'hours ago',
            'daysAgo': 'days ago',
            'quickActions': 'Quick Actions',
            'newClient': 'New Client',
            'offers': 'Offers',
            'calendar': 'Calendar',
            'clientListLink': 'Client List',
            
            // Client List
            'clientListTitle': 'CLIENT LIST',
            'searchClient': 'Search client...',
            'newClientButton': 'New Client',
            'taxNo': 'Tax No',
            'title': 'Title',
            'taxOffice': 'Tax Office',
            'businessPartner': 'Business Partner',
            
            // Client Detail
            'clientDetailTitle': 'Client Detail',
            'backToClientList': 'Back to Client List',
            'titleLabel': 'Title:',
            'businessPartnerLabel': 'Business Partner:',
            'taxOfficeLabel': 'Tax Office:',
            'taxNoLabel': 'Tax No:',
            'addressLabel': 'Address:',
            'phoneLabel': 'Phone:',
            'faxLabel': 'Fax:',
            'emailLabel': 'Email:',
            'webLabel': 'Web:',
            'assignedUsers': 'Assigned Users to Client',
            'fullName': 'Full Name',
            'email': 'Email',
            'phone': 'Phone',
            
            // Months (for chart)
            'january': 'January',
            'february': 'February',
            'march': 'March',
            'april': 'April',
            'may': 'May',
            'june': 'June',
            'july': 'July',
            'august': 'August',
            'september': 'September',
            'october': 'October',
            'november': 'November',
            'december': 'December',
            'monthlyTransactionCount': 'Monthly Transaction Count',
            
            // Dashboard - Active Users & Payments
            'incomingPayments': 'Incoming Payments',
            'activeUsers': 'Active Users',
            'userName': 'User Name',
            'activeFile': 'Active File',
            'fileType': 'File Type',
            'duration': 'Duration',
            'status': 'Status',
            'seeAll': 'See All',
            'overview': 'Overview',
            'performance': 'Performance',
            'totalOrders': 'Total Orders',
            'sentOffers': 'SENT OFFERS',
            'contracts': 'CONTRACTS',
            'userCount': 'USER COUNT',
            'onlineUsers': 'ONLINE USERS',
            'sinceLastMonth': 'Since last month',
            'sinceLastWeek': 'Since last week',
            'sinceYesterday': 'Since yesterday',
            'active': 'Active',
            'onlineNow': 'Online now',
            'away': 'Away',
            'excel': 'Excel',
            'word': 'Word',
            'pdf': 'PDF',
            'powerpoint': 'PowerPoint',
            
            // Payments Page
            'addNewPayment': 'Add New Payment',
            'selectCompany': 'Select Company',
            'reportType': 'Report Type',
            'amount': 'Amount',
            'currency': 'Currency',
            'paymentDate': 'Payment Date',
            'description': 'Description',
            'pleaseSelect': '-- Please Select --',
            'save': 'Save',
            'cancel': 'Cancel',
            'confirmPayment': 'Payment Confirmation',
            'confirmPaymentMessage': 'Please review payment information:',
            'confirm': 'Confirm and Save',
            'paymentSavedSuccess': 'Payment saved successfully!',
            'auditService': 'Audit Service',
            'taxConsulting': 'Tax Consulting',
            'financialAdvisory': 'Financial Advisory',
            'independentAudit': 'Independent Audit',
            'financialAnalysis': 'Financial Analysis',
            'amountPlaceholder': '0.00',
            'descriptionPlaceholder': 'You can add a note about the payment...'
        },
        tr: {
            // Common
            'welcome': 'HOŞGELDİNİZ!',
            'companyName': 'CROWE HSY',
            'platformDescription': 'Mali Müşavir Yönetim Platformu',
            'login': 'Giriş',
            'logout': 'Çıkış',
            'profile': 'Profil',
            'dashboard': 'Dashboard',
            'clients': 'Müşteriler',
            'select': 'SEÇ',
            'back': 'Geri',
            'backToHome': 'Ana Sayfaya Dön',
            'forgotPassword': 'Şifremi unuttum?',
            
            // Index Page
            'interfacePreview': 'Crowe HSY Arayüz Önizleme',
            'interfacePreviewDescription': 'Aşağıdaki butonlardan farklı sayfa görünümlerini inceleyebilirsiniz:',
            'loginPage': 'Login Sayfası',
            'loginPageDescription': 'Giriş ekranı',
            'dashboardDescription': 'Ana panel',
            'clientList': 'Müşteri Listesi',
            'clientListDescription': 'Müşteri listeleme',
            'clientDetail': 'Müşteri Detay',
            'clientDetailDescription': 'Müşteri detay sayfası',
            
            // Login Page
            'username': 'Kullanıcı adınız...',
            'password': 'Şifreniz...',
            'loginButton': 'Giriş Yap',
            'twoFactorCode': '2FA Kodu (6 haneli)',
            'twoFactorCodeDescription': 'E-posta adresinize gönderilen 6 haneli kodu giriniz.',
            'loginError': "'Kullanici Adi' yada 'Şifre' boş olamaz.",
            'loginError2FA': '2FA kodu gereklidir (6 haneli).',
            'loginErrorWrong': 'Kullanıcı adı veya şifre yanlış.',
            
            // Dashboard
            'totalClients': 'Toplam Müşteri',
            'activeAudits': 'Aktif Denetimler',
            'pendingOffers': 'Bekleyen Teklifler',
            'totalUsers': 'Toplam Kullanıcı',
            'monthlyTransactionChart': 'Aylık İşlem Grafiği',
            'recentActivities': 'Son Aktiviteler',
            'newClientAdded': 'Yeni Müşteri Eklendi',
            'auditCompleted': 'Denetim Tamamlandı',
            'offerSent': 'Teklif Gönderildi',
            'hoursAgo': 'saat önce',
            'daysAgo': 'gün önce',
            'quickActions': 'Hızlı İşlemler',
            'newClient': 'Yeni Müşteri',
            'offers': 'Teklifler',
            'calendar': 'Takvim',
            'clientListLink': 'Müşteri Listesi',
            
            // Client List
            'clientListTitle': 'MÜŞTERİ LİSTESİ',
            'searchClient': 'Müşteri ara...',
            'newClientButton': 'Yeni Müşteri',
            'taxNo': 'Vergi No',
            'title': 'Unvan',
            'taxOffice': 'Vergi Dairesi',
            'businessPartner': 'İş Ortağı',
            
            // Client Detail
            'clientDetailTitle': 'Müşteri Detay',
            'backToClientList': 'Müşteri Listesine Dön',
            'titleLabel': 'Unvan:',
            'businessPartnerLabel': 'İş Ortağı:',
            'taxOfficeLabel': 'Vergi Dairesi:',
            'taxNoLabel': 'Vergi No:',
            'addressLabel': 'Adres:',
            'phoneLabel': 'Telefon:',
            'faxLabel': 'Faks:',
            'emailLabel': 'E-posta:',
            'webLabel': 'Web:',
            'assignedUsers': 'Müşteriye Atanmış Kullanıcılar',
            'fullName': 'Ad Soyad',
            'email': 'E-posta',
            'phone': 'Telefon',
            
            // Months (for chart)
            'january': 'Ocak',
            'february': 'Şubat',
            'march': 'Mart',
            'april': 'Nisan',
            'may': 'Mayıs',
            'june': 'Haziran',
            'july': 'Temmuz',
            'august': 'Ağustos',
            'september': 'Eylül',
            'october': 'Ekim',
            'november': 'Kasım',
            'december': 'Aralık',
            'monthlyTransactionCount': 'Aylık İşlem Sayısı',
            
            // Dashboard - Active Users & Payments
            'incomingPayments': 'Gelen Ödemeler',
            'activeUsers': 'Aktif Kullanıcılar',
            'userName': 'Kullanıcı Adı',
            'activeFile': 'Aktif Dosya',
            'fileType': 'Dosya Tipi',
            'duration': 'Süre',
            'status': 'Durum',
            'seeAll': 'Tümünü Gör',
            'overview': 'Genel Bakış',
            'performance': 'Performans',
            'totalOrders': 'Toplam Siparişler',
            'sentOffers': 'GÖNDERİLEN TEKLİF',
            'contracts': 'SÖZLEŞMELER',
            'userCount': 'KULLANICI SAYISI',
            'onlineUsers': 'ÇEVRİMİÇİ KULLANICILAR',
            'sinceLastMonth': 'Geçen aydan beri',
            'sinceLastWeek': 'Geçen haftadan beri',
            'sinceYesterday': 'Dünden beri',
            'active': 'Aktif',
            'onlineNow': 'Şu anda çevrimiçi',
            'away': 'Uzakta',
            'excel': 'Excel',
            'word': 'Word',
            'pdf': 'PDF',
            'powerpoint': 'PowerPoint',
            
            // Payments Page
            'addNewPayment': 'Yeni Ödeme Ekle',
            'selectCompany': 'Şirket Seçiniz',
            'reportType': 'Rapor Türü',
            'amount': 'Tutar',
            'currency': 'Döviz Cinsi',
            'paymentDate': 'Ödeme Tarihi',
            'description': 'Açıklama',
            'pleaseSelect': '-- Lütfen Seçiniz --',
            'save': 'Kaydet',
            'cancel': 'İptal',
            'confirmPayment': 'Ödeme Onayı',
            'confirmPaymentMessage': 'Lütfen ödeme bilgilerini kontrol ediniz:',
            'confirm': 'Onayla ve Kaydet',
            'paymentSavedSuccess': 'Ödeme başarıyla kaydedildi!',
            'auditService': 'Denetim Hizmeti',
            'taxConsulting': 'Vergi Danışmanlığı',
            'financialAdvisory': 'Mali Müşavirlik',
            'independentAudit': 'Bağımsız Denetim',
            'financialAnalysis': 'Finansal Analiz',
            'amountPlaceholder': '0.00',
            'descriptionPlaceholder': 'Ödeme ile ilgili not ekleyebilirsiniz...'
        }
    },
    
    // Initialize language from localStorage or default to English
    init: function() {
        const savedLang = localStorage.getItem('language') || 'en';
        this.setLanguage(savedLang);
    },
    
    // Set language
    setLanguage: function(lang) {
        if (this.translations[lang]) {
            this.currentLang = lang;
            localStorage.setItem('language', lang);
            document.documentElement.lang = lang;
            this.updatePage();
        }
    },
    
    // Get translation for a key
    t: function(key) {
        return this.translations[this.currentLang][key] || key;
    },
    
    // Update all elements with data-i18n attribute
    updatePage: function() {
        // Update regular data-i18n elements
        document.querySelectorAll('[data-i18n]').forEach(element => {
            const key = element.getAttribute('data-i18n');
            const translation = this.t(key);
            
            if (element.tagName === 'TITLE' || element.tagName === 'META') {
                element.textContent = translation;
            } else {
                element.textContent = translation;
            }
        });
        
        // Update placeholder elements with data-i18n-placeholder
        document.querySelectorAll('[data-i18n-placeholder]').forEach(element => {
            const key = element.getAttribute('data-i18n-placeholder');
            const translation = this.t(key);
            element.placeholder = translation;
        });
        
        // Update option elements with data-i18n-option
        document.querySelectorAll('option[data-i18n-option]').forEach(element => {
            const key = element.getAttribute('data-i18n-option');
            const translation = this.t(key);
            element.textContent = translation;
        });
        
        // Update lang attribute
        document.documentElement.lang = this.currentLang;
        
        // Trigger custom event for page-specific updates
        document.dispatchEvent(new CustomEvent('languageChanged', { detail: { lang: this.currentLang } }));
    }
};

// Initialize on load
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', () => i18n.init());
} else {
    i18n.init();
}
