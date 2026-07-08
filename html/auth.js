// Authentication System for Crowe HSY
// Supabase destekli olacak şekilde güncellendi, Supabase yapılandırması yapılmazsa eski localStorage mantığına geri döner.

const SUPABASE_URL = window.SUPABASE_URL || 'YOUR_SUPABASE_URL';
const SUPABASE_ANON_KEY = window.SUPABASE_ANON_KEY || 'YOUR_SUPABASE_ANON_KEY';
const SUPABASE_PLACEHOLDER = SUPABASE_URL.includes('YOUR_') || SUPABASE_ANON_KEY.includes('YOUR_');

// Fallback configuration - config.js window.* üzerinden gelir.
// NOT: config.js ile aynı global isimde const tanımlanamaz (Safari duplicate variable hatası).
const authAppEnv = (typeof window !== 'undefined' && window.APP_ENV) || 'development';
const authFallbackDefaultPassword = (typeof window !== 'undefined' && window.FALLBACK_DEFAULT_PASSWORD !== undefined)
    ? window.FALLBACK_DEFAULT_PASSWORD
    : (authAppEnv === 'production' ? '' : 'Crowe2022!');
const authFallbackAdmins = (typeof window !== 'undefined' && window.FALLBACK_ADMINS)
    ? window.FALLBACK_ADMINS
    : (authAppEnv === 'production' ? {} : {
        'mehmetali.sariad@crowehsy.net': { fullName: 'Mehmet Ali Sariad', role: 'admin' },
        'ozkan.cengiz@crowehsy.net': { fullName: 'Özkan Cengiz', role: 'admin' },
        'mert.cengiz@crowehsy.net': { fullName: 'Mert Cengiz', role: 'admin' },
        'hakan.kilic@crowehsy.net': { fullName: 'Hakan Kılıç', role: 'admin' },
        'eda.sefer@crowehsy.net': { fullName: 'Eda Sefer', role: 'admin' },
        'irem.gulmez@crowehsy.net': { fullName: 'İrem Gülmez', role: 'admin' }
    });

if (authAppEnv === 'production' && (authFallbackDefaultPassword || Object.keys(authFallbackAdmins).length > 0)) {
    console.warn('⚠️ GÜVENLİK UYARISI: Production modunda fallback şifreler kullanılıyor! config.js dosyasını kontrol edin.');
}

let supabaseClient = null;
let currentSupabaseSession = null;

// Wait for supabase-config.js to initialize global instance
// Use a small delay to ensure supabase-config.js has run
(function() {
    function initSupabaseClient() {
        // First, check if global instance already exists (from supabase-config.js)
        if (window.__supabaseClientInstance) {
            supabaseClient = window.__supabaseClientInstance;
            console.log('✅ auth.js: Global Supabase client instance kullanılıyor');
            return;
        }
        
        // If global instance doesn't exist yet, wait a bit and check again
        // This handles the case where auth.js loads before supabase-config.js
        if (typeof window !== 'undefined' && typeof window.supabase !== 'undefined' && !SUPABASE_PLACEHOLDER) {
            // Check if global getSupabaseClient function exists
            if (typeof window.getSupabaseClient === 'function') {
                supabaseClient = window.getSupabaseClient();
                if (supabaseClient) {
                    console.log('✅ auth.js: Global getSupabaseClient() kullanıldı');
                    return;
                }
            }
            
            // Last resort: Create instance only if absolutely necessary
            // But mark it as global to prevent duplicates
            if (!window.__supabaseClientInstance) {
                supabaseClient = window.supabase.createClient(SUPABASE_URL, SUPABASE_ANON_KEY, {
                    auth: {
                        persistSession: true,
                        storage: window.localStorage
                    }
                });
                // Store as global instance for reuse
                window.__supabaseClientInstance = supabaseClient;
                console.log('✅ auth.js: Yeni Supabase client oluşturuldu ve global instance olarak kaydedildi');
            } else {
                supabaseClient = window.__supabaseClientInstance;
                console.log('✅ auth.js: Mevcut global instance kullanılıyor');
            }
        }
    }
    
    // Try immediately
    initSupabaseClient();
    
    // Also try after a short delay (in case supabase-config.js loads after auth.js)
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(initSupabaseClient, 100);
        });
    } else {
        setTimeout(initSupabaseClient, 100);
    }
})();

function generateToken() {
    return 'token_' + Date.now() + '_' + Math.random().toString(36).slice(2, 11);
}

function storeSessionLocally(userPayload, token, metadata) {
    localStorage.setItem('auth_user', JSON.stringify(userPayload));
    localStorage.setItem('auth_token', token || generateToken());

    const requiresPasswordChange =
        metadata &&
        metadata.requiresPasswordChange === true &&
        userPayload &&
        userPayload.role !== 'admin';

    if (requiresPasswordChange) {
        localStorage.setItem('pendingPasswordChange', JSON.stringify(userPayload));
    } else {
        localStorage.removeItem('pendingPasswordChange');
    }
}

function clearSessionLocally() {
    localStorage.removeItem('auth_user');
    localStorage.removeItem('auth_token');
}

function getStoredPasswordKey(email) {
    return 'userPassword_' + email.toLowerCase();
}

function getStoredPassword(email) {
    return localStorage.getItem(getStoredPasswordKey(email));
}

function setStoredPassword(email, password) {
    localStorage.setItem(getStoredPasswordKey(email), password);
    markPasswordChanged(email, true);
}

function hasPasswordChanged(email) {
    return localStorage.getItem('passwordChanged_' + email.toLowerCase()) === 'true';
}

function markPasswordChanged(email, state) {
    localStorage.setItem('passwordChanged_' + email.toLowerCase(), state ? 'true' : 'false');
}

async function syncSupabaseSession() {
    if (!supabaseClient) {
        return;
    }
    const { data, error } = await supabaseClient.auth.getSession();
    if (error) {
        console.warn('Supabase session alınamadı:', error.message);
        return;
    }
    currentSupabaseSession = data.session || null;
    if (currentSupabaseSession) {
        const supUser = currentSupabaseSession.user;
        const payload = {
            username: supUser.email,
            fullName: supUser.user_metadata?.full_name || supUser.email,
            role: supUser.app_metadata?.role || supUser.user_metadata?.role || 'admin',
            loginTime: new Date().toISOString()
        };
        storeSessionLocally(payload, currentSupabaseSession.access_token, {
            requiresPasswordChange: supUser.user_metadata?.requires_password_change === true
        });
    }
    // Supabase oturumu yoksa local fallback oturumunu silme —
    // development login (FALLBACK_ADMINS) localStorage'da tutulur.
}

const Auth = {
    supabaseEnabled: Boolean(supabaseClient),

    isAuthenticated: function() {
        try {
            const user = localStorage.getItem('auth_user');
            const token = localStorage.getItem('auth_token');
            // Hem user hem token olmalı ve user geçerli bir JSON olmalı
            if (!user || !token) {
                return false;
            }
            // User'ın geçerli bir JSON olduğunu kontrol et
            try {
                JSON.parse(user);
            } catch (e) {
                return false;
            }
            return true;
        } catch (error) {
            console.error('isAuthenticated error:', error);
            return false;
        }
    },

    getCurrentUser: function() {
        const user = localStorage.getItem('auth_user');
        return user ? JSON.parse(user) : null;
    },

    isAdmin: function() {
        const user = this.getCurrentUser();
        return user && user.role === 'admin';
    },

    getPendingPasswordChange: function() {
        const pending = localStorage.getItem('pendingPasswordChange');
        return pending ? JSON.parse(pending) : null;
    },

    clearPendingPasswordChange: function() {
        localStorage.removeItem('pendingPasswordChange');
    },

    async login(username, password) {
        const normalizedEmail = username ? username.toLowerCase().trim() : '';

        // Önce Supabase'i dene, hata olursa fallback'e geç
        if (supabaseClient) {
            try {
                const { data, error } = await supabaseClient.auth.signInWithPassword({
                    email: normalizedEmail,
                    password: password
                });

                if (!error && data && data.session) {
                    currentSupabaseSession = data.session;
                    const supUser = data.user;
                    const payload = {
                        username: supUser.email,
                        fullName: supUser.user_metadata?.full_name || supUser.email,
                        role: supUser.app_metadata?.role || supUser.user_metadata?.role || 'admin',
                        loginTime: new Date().toISOString()
                    };

                    const requiresPasswordChange = supUser.user_metadata?.requires_password_change === true;
                    storeSessionLocally(payload, data.session?.access_token, { requiresPasswordChange });

                    return { success: true, user: payload, requiresPasswordChange };
                }
                
                // Supabase'de hata varsa fallback'e geç (kullanıcı yoksa veya şifre yanlışsa)
                console.log('Supabase login failed, falling back to local auth:', error?.message);
            } catch (err) {
                // Supabase bağlantı hatası varsa fallback'e geç
                console.log('Supabase connection error, falling back to local auth:', err.message);
            }
        }

        // Fallback: local listed admins (sadece development veya Supabase yoksa)
        // Production'da bu bölüm çalışmamalı
        if (authAppEnv === 'production' && supabaseClient) {
            return { success: false, error: 'Kullanıcı bulunamadı veya şifre hatalı.' };
        }
        
        const adminInfo = authFallbackAdmins[normalizedEmail];
        if (!adminInfo) {
            return { success: false, error: 'Kullanıcı bulunamadı.' };
        }

        // Production'da fallback şifre kullanılmamalı
        if (authAppEnv === 'production' && !authFallbackDefaultPassword) {
            return { success: false, error: 'Şifre değiştirilmelidir. Lütfen yönetici ile iletişime geçin.' };
        }

        const storedPassword = getStoredPassword(normalizedEmail) || authFallbackDefaultPassword;
        if (!storedPassword || password !== storedPassword) {
            return { success: false, error: 'Şifre hatalı.' };
        }

        const payload = {
            username: normalizedEmail,
            fullName: adminInfo.fullName,
            role: adminInfo.role,
            loginTime: new Date().toISOString()
        };

        markPasswordChanged(normalizedEmail, true);
        storeSessionLocally(payload, null, { requiresPasswordChange: false });

        return { success: true, user: payload, requiresPasswordChange: false };
    },

    async changePassword(newPassword) {
        const currentUser = this.getCurrentUser();
        if (!currentUser) {
            return { success: false, error: 'Kullanıcı bulunamadı.' };
        }

        const normalizedEmail = currentUser.username.toLowerCase();

        if (supabaseClient) {
            const { error } = await supabaseClient.auth.updateUser({
                password: newPassword,
                data: { requires_password_change: false }
            });

            if (error) {
                return { success: false, error: error.message };
            }

            markPasswordChanged(normalizedEmail, true);
            this.clearPendingPasswordChange();
            await syncSupabaseSession();
            return { success: true };
        }

        setStoredPassword(normalizedEmail, newPassword);
        this.clearPendingPasswordChange();
        return { success: true };
    },

    logout: async function(options = { redirect: true }) {
        if (supabaseClient) {
            await supabaseClient.auth.signOut();
        }
        clearSessionLocally();
        this.clearPendingPasswordChange();
        if (options.redirect !== false) {
            window.location.href = 'login.html';
        }
    },

    requireAuth: function() {
        if (!this.isAuthenticated()) {
            window.location.href = 'login.html';
            return false;
        }
        return true;
    },

    requireAdmin: function() {
        if (!this.isAuthenticated()) {
            window.location.href = 'login.html';
            return false;
        }
        if (!this.isAdmin()) {
            alert('Bu sayfaya erişmek için yönetici yetkisi gerekir.');
            window.location.href = 'dashboard.html';
            return false;
        }
        return true;
    }
};

if (supabaseClient) {
    syncSupabaseSession();
    supabaseClient.auth.onAuthStateChange((event, session) => {
        currentSupabaseSession = session;
        if (session) {
            const supUser = session.user;
            const payload = {
                username: supUser.email,
                fullName: supUser.user_metadata?.full_name || supUser.email,
                role: supUser.app_metadata?.role || supUser.user_metadata?.role || 'admin',
                loginTime: new Date().toISOString()
            };
            const requiresPasswordChange = supUser.user_metadata?.requires_password_change === true;
            storeSessionLocally(payload, session.access_token, { requiresPasswordChange });
        } else if (event === 'SIGNED_OUT') {
            // Yalnızca gerçek çıkışta temizle; INITIAL_SESSION(null) fallback login'i silmesin
            clearSessionLocally();
        }
    });
}

// Global authentication kontrolü - DOMContentLoaded ile geciktir
if (typeof window !== 'undefined' && window.location.pathname !== '/login.html' && !window.location.pathname.includes('login.html')) {
    const protectedPages = ['dashboard.html', 'client-list.html', 'client-detail.html', 'contracts.html', 'reports.html', 'payments.html', 'offers.html', 'users.html', 'online-users.html'];
    const currentPage = window.location.pathname.split('/').pop();

    if (protectedPages.includes(currentPage)) {
        // DOMContentLoaded ile kontrol et, script'lerin yüklenmesi için bekle
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', function() {
                setTimeout(function() {
                    if (typeof Auth !== 'undefined' && !Auth.isAuthenticated()) {
                        window.location.href = 'login.html';
                    }
                }, 100);
            });
        } else {
            // Sayfa zaten yüklendiyse direkt kontrol et
            setTimeout(function() {
                if (typeof Auth !== 'undefined' && !Auth.isAuthenticated()) {
                    window.location.href = 'login.html';
                }
            }, 100);
        }
    }
}





