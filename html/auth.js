// Authentication System for Crowe HSY — Supabase-only auth
// Şifre / oturum kaynağı: yalnızca Supabase Auth (JWT). Yerel fallback login yok.

const SUPABASE_URL = window.SUPABASE_URL || 'YOUR_SUPABASE_URL';
const SUPABASE_ANON_KEY = window.SUPABASE_ANON_KEY || 'YOUR_SUPABASE_ANON_KEY';
const SUPABASE_PLACEHOLDER = SUPABASE_URL.includes('YOUR_') || SUPABASE_ANON_KEY.includes('YOUR_');
const DEFAULT_ADMIN_PASSWORD = 'Crowe2022!';

let supabaseClient = null;
let currentSupabaseSession = null;

(function () {
    function initSupabaseClient() {
        if (window.__supabaseClientInstance) {
            supabaseClient = window.__supabaseClientInstance;
            return;
        }
        if (typeof window === 'undefined' || typeof window.supabase === 'undefined' || SUPABASE_PLACEHOLDER) {
            return;
        }
        if (typeof window.getSupabaseClient === 'function') {
            supabaseClient = window.getSupabaseClient();
            if (supabaseClient) return;
        }
        if (!window.__supabaseClientInstance) {
            supabaseClient = window.supabase.createClient(SUPABASE_URL, SUPABASE_ANON_KEY, {
                auth: { persistSession: true, storage: window.localStorage, autoRefreshToken: true }
            });
            window.__supabaseClientInstance = supabaseClient;
        } else {
            supabaseClient = window.__supabaseClientInstance;
        }
    }
    initSupabaseClient();
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', function () { setTimeout(initSupabaseClient, 50); });
    } else {
        setTimeout(initSupabaseClient, 50);
    }
})();

function storeSessionLocally(userPayload, token, metadata) {
    if (!token || String(token).indexOf('eyJ') !== 0) {
        console.warn('JWT olmayan token reddedildi');
        return;
    }
    localStorage.setItem('auth_user', JSON.stringify(userPayload));
    localStorage.setItem('auth_token', token);
    const requiresPasswordChange = !!(metadata && metadata.requiresPasswordChange === true);
    if (requiresPasswordChange) {
        localStorage.setItem('pendingPasswordChange', JSON.stringify(userPayload));
    } else {
        localStorage.removeItem('pendingPasswordChange');
    }
}

function clearSessionLocally() {
    localStorage.removeItem('auth_user');
    localStorage.removeItem('auth_token');
    localStorage.removeItem('pendingPasswordChange');
}

function getHintKey(email) { return 'passwordHint_' + String(email || '').toLowerCase(); }
function getDisplayNameKey(email) { return 'userDisplayName_' + String(email || '').toLowerCase(); }
function getAvatarKey(email) { return 'userAvatar_' + String(email || '').toLowerCase(); }

function reverseString(value) {
    return String(value || '').split('').reverse().join('');
}

function validateNewPassword(password, hint, options) {
    options = options || {};
    const pwd = String(password || '');
    const reminder = String(hint || '').trim();
    const defaultPassword = String(options.defaultPassword || DEFAULT_ADMIN_PASSWORD);

    if (pwd.length < 8) return { ok: false, error: 'Şifre en az 8 karakter olmalıdır.' };
    if (!/[A-ZÇĞİÖŞÜ]/.test(pwd)) return { ok: false, error: 'Şifrede en az 1 büyük harf olmalıdır.' };
    if (!/[a-zçğıöşü]/.test(pwd)) return { ok: false, error: 'Şifrede en az 1 küçük harf olmalıdır.' };
    if (!/[0-9]/.test(pwd)) return { ok: false, error: 'Şifrede en az 1 rakam olmalıdır.' };
    if (!/[^A-Za-z0-9ÇĞİÖŞÜçğıöşü]/.test(pwd)) return { ok: false, error: 'Şifrede en az 1 özel işaret olmalıdır (!@# vb.).' };
    if (pwd.replace(/\s+/g, '') === defaultPassword.replace(/\s+/g, '')) {
        return { ok: false, error: 'Yeni şifre varsayılan şifreyle aynı olamaz.' };
    }
    if (!reminder) return { ok: false, error: 'Hatırlatıcı kelime zorunludur.' };
    if (reminder.length < 3) return { ok: false, error: 'Hatırlatıcı kelime en az 3 karakter olmalıdır.' };
    if (reminder.toLocaleLowerCase('tr') === pwd.toLocaleLowerCase('tr')) {
        return { ok: false, error: 'Hatırlatıcı kelime şifre ile aynı olamaz.' };
    }
    if (reminder.toLocaleLowerCase('tr') === reverseString(pwd).toLocaleLowerCase('tr')) {
        return { ok: false, error: 'Hatırlatıcı kelime şifrenin tersten yazılışı olamaz.' };
    }
    return { ok: true };
}

function truthyFlag(value) {
    return value === true || value === 'true' || value === 1 || value === '1';
}

function isJwtToken(token) {
    return typeof token === 'string' && token.indexOf('eyJ') === 0;
}

function waitForSupabaseClient(timeoutMs) {
    timeoutMs = timeoutMs || 4000;
    return new Promise(function (resolve) {
        const started = Date.now();
        (function tick() {
            if (window.__supabaseClientInstance) {
                supabaseClient = window.__supabaseClientInstance;
                resolve(supabaseClient);
                return;
            }
            if (supabaseClient) { resolve(supabaseClient); return; }
            if (Date.now() - started >= timeoutMs) { resolve(supabaseClient); return; }
            setTimeout(tick, 40);
        })();
    });
}

function buildUserPayloadFromSupabase(supUser) {
    const email = String(supUser.email || '').toLowerCase();
    const meta = supUser.user_metadata || {};
    const savedName = localStorage.getItem(getDisplayNameKey(email));
    const savedAvatar = localStorage.getItem(getAvatarKey(email));
    return {
        username: email,
        fullName: savedName || meta.full_name || email,
        role: (supUser.app_metadata && supUser.app_metadata.role) || meta.role || 'admin',
        avatar: savedAvatar || null,
        loginTime: new Date().toISOString()
    };
}

function requiresPasswordChangeFromMeta(meta, usingDefaultPassword) {
    meta = meta || {};
    if (meta.password_changed === true || meta.password_changed === 'true') return false;
    if (meta.requires_password_change === false || meta.requires_password_change === 'false') return false;
    if (truthyFlag(meta.requires_password_change)) return true;
    if (usingDefaultPassword) return true;
    return false;
}

async function syncSupabaseSession() {
    if (!supabaseClient) return;
    const { data, error } = await supabaseClient.auth.getSession();
    if (error) {
        console.warn('Supabase session alınamadı:', error.message);
        return;
    }
    currentSupabaseSession = data.session || null;
    if (!currentSupabaseSession) {
        // Sahte yerel oturumu temizle
        const token = localStorage.getItem('auth_token') || '';
        if (!isJwtToken(token)) clearSessionLocally();
        return;
    }
    const payload = buildUserPayloadFromSupabase(currentSupabaseSession.user);
    storeSessionLocally(payload, currentSupabaseSession.access_token, {
        requiresPasswordChange: requiresPasswordChangeFromMeta(currentSupabaseSession.user.user_metadata)
    });
}

const Auth = {
    supabaseEnabled: !SUPABASE_PLACEHOLDER,

    isAuthenticated: function () {
        try {
            const user = localStorage.getItem('auth_user');
            const token = localStorage.getItem('auth_token') || '';
            if (!user || !isJwtToken(token)) return false;
            JSON.parse(user);
            return true;
        } catch (e) {
            return false;
        }
    },

    getCurrentUser: function () {
        const user = localStorage.getItem('auth_user');
        return user ? JSON.parse(user) : null;
    },

    isAdmin: function () {
        const user = this.getCurrentUser();
        return user && user.role === 'admin';
    },

    getPendingPasswordChange: function () {
        const pending = localStorage.getItem('pendingPasswordChange');
        return pending ? JSON.parse(pending) : null;
    },

    clearPendingPasswordChange: function () {
        localStorage.removeItem('pendingPasswordChange');
    },

    ensureSupabaseSession: async function () {
        const client = await waitForSupabaseClient();
        if (!client) {
            return { ok: false, error: 'Supabase bağlantısı yok.', redirectLogin: true };
        }
        supabaseClient = client;
        try {
            const { data: sessionWrap } = await supabaseClient.auth.getSession();
            if (sessionWrap && sessionWrap.session) {
                currentSupabaseSession = sessionWrap.session;
                const payload = buildUserPayloadFromSupabase(sessionWrap.session.user);
                storeSessionLocally(payload, sessionWrap.session.access_token, {
                    requiresPasswordChange: requiresPasswordChangeFromMeta(sessionWrap.session.user.user_metadata)
                });
                return { ok: true, session: sessionWrap.session };
            }
            const { data: refreshed, error: refreshError } = await supabaseClient.auth.refreshSession();
            if (!refreshError && refreshed && refreshed.session) {
                currentSupabaseSession = refreshed.session;
                const payload = buildUserPayloadFromSupabase(refreshed.session.user);
                storeSessionLocally(payload, refreshed.session.access_token, {
                    requiresPasswordChange: requiresPasswordChangeFromMeta(refreshed.session.user.user_metadata)
                });
                return { ok: true, session: refreshed.session };
            }
        } catch (err) {
            console.warn('ensureSupabaseSession:', err && err.message);
        }
        clearSessionLocally();
        return { ok: false, error: 'Supabase oturumu yok. Lütfen tekrar giriş yapın.', redirectLogin: true };
    },

    async login(username, password) {
        const client = await waitForSupabaseClient();
        if (!client) {
            return { success: false, error: 'Supabase bağlantısı kurulamadı. Sayfayı yenileyip tekrar deneyin.' };
        }
        supabaseClient = client;

        const normalizedEmail = username ? username.toLowerCase().trim() : '';
        const normalizedInputPassword = String(password || '').replace(/\s+/g, '');
        const usingDefault = normalizedInputPassword === DEFAULT_ADMIN_PASSWORD.replace(/\s+/g, '');

        if (!normalizedEmail || !normalizedInputPassword) {
            return { success: false, error: 'E-posta ve şifre zorunludur.' };
        }

        try {
            const { data, error } = await supabaseClient.auth.signInWithPassword({
                email: normalizedEmail,
                password: password
            });
            if (error || !data || !data.session) {
                console.warn('Supabase login failed:', error && (error.message || error.code || error));
                const code = String((error && (error.code || error.error_code)) || '');
                if (code === 'email_not_confirmed') {
                    return { success: false, error: 'E-posta henüz onaylanmamış. Supabase’de kullanıcıyı onaylayın.' };
                }
                if (code === 'invalid_credentials' || (error && error.status === 400)) {
                    return {
                        success: false,
                        error: 'E-posta veya şifre hatalı (Supabase). Şifreyi bilmiyorsanız SQL Editor’da supabase-reset-admin-passwords.sql çalıştırıp Crowe2022! ile girin.'
                    };
                }
                return { success: false, error: (error && error.message) || 'E-posta veya şifre hatalı.' };
            }

            currentSupabaseSession = data.session;
            const meta = (data.user && data.user.user_metadata) || {};
            const passwordChangedOnServer = meta.password_changed === true || meta.password_changed === 'true';

            if (usingDefault && passwordChangedOnServer) {
                try { await supabaseClient.auth.signOut(); } catch (e) {}
                clearSessionLocally();
                return { success: false, error: 'Bu şifre artık geçerli değil. Yeni şifrenizi kullanın.' };
            }

            const requiresPasswordChange = requiresPasswordChangeFromMeta(meta, usingDefault);
            const payload = buildUserPayloadFromSupabase(data.user);
            storeSessionLocally(payload, data.session.access_token, { requiresPasswordChange });
            // Eski local şifre / sahte token kalıntılarını temizle
            try {
                Object.keys(localStorage).forEach(function (key) {
                    if (key.indexOf('userPassword_') === 0 || key.indexOf('passwordChanged_') === 0) {
                        localStorage.removeItem(key);
                    }
                });
                localStorage.removeItem('clients');
            } catch (cleanupErr) {}
            return { success: true, user: payload, requiresPasswordChange };
        } catch (err) {
            console.error('login error:', err);
            return { success: false, error: 'Giriş sırasında bir hata oluştu.' };
        }
    },

    validatePasswordRules: function (password, hint) {
        return validateNewPassword(password, hint, { defaultPassword: DEFAULT_ADMIN_PASSWORD });
    },

    getPasswordRuleStatus: function (password, hint) {
        const pwd = String(password || '');
        const reminder = String(hint || '').trim();
        return {
            minLength: pwd.length >= 8,
            upper: /[A-ZÇĞİÖŞÜ]/.test(pwd),
            lower: /[a-zçğıöşü]/.test(pwd),
            digit: /[0-9]/.test(pwd),
            symbol: /[^A-Za-z0-9ÇĞİÖŞÜçğıöşü]/.test(pwd),
            notDefault: pwd.length > 0 && pwd.replace(/\s+/g, '') !== DEFAULT_ADMIN_PASSWORD.replace(/\s+/g, ''),
            hintPresent: reminder.length >= 3,
            hintNotPassword: reminder.length > 0 && reminder.toLocaleLowerCase('tr') !== pwd.toLocaleLowerCase('tr'),
            hintNotReverse: reminder.length > 0 && reminder.toLocaleLowerCase('tr') !== reverseString(pwd).toLocaleLowerCase('tr')
        };
    },

    async changePassword(newPassword, hint) {
        const currentUser = this.getPendingPasswordChange() || this.getCurrentUser();
        if (!currentUser) return { success: false, error: 'Kullanıcı bulunamadı.' };
        const normalizedEmail = String(currentUser.username || currentUser.email || '').toLowerCase();
        const check = validateNewPassword(newPassword, hint, { defaultPassword: DEFAULT_ADMIN_PASSWORD });
        if (!check.ok) return { success: false, error: check.error };

        const sessionCheck = await this.ensureSupabaseSession();
        if (!sessionCheck.ok) {
            return { success: false, error: 'Supabase oturumu gerekli. Tekrar giriş yapın.' };
        }

        try {
            const { error: updateError } = await supabaseClient.auth.updateUser({
                password: newPassword,
                data: {
                    requires_password_change: false,
                    password_changed: true,
                    password_hint: String(hint || '').trim()
                }
            });
            if (updateError) {
                return { success: false, error: 'Şifre sunucuya kaydedilemedi: ' + updateError.message };
            }

            try { await supabaseClient.auth.signOut(); } catch (e) {}
            const { data: verifyData, error: verifyError } = await supabaseClient.auth.signInWithPassword({
                email: normalizedEmail,
                password: newPassword
            });
            if (verifyError || !verifyData || !verifyData.session) {
                return { success: false, error: 'Şifre kaydı doğrulanamadı. Tekrar giriş yapıp deneyin.' };
            }

            currentSupabaseSession = verifyData.session;
            this.clearPendingPasswordChange();
            const payload = buildUserPayloadFromSupabase(verifyData.user);
            storeSessionLocally(payload, verifyData.session.access_token, { requiresPasswordChange: false });
            return { success: true };
        } catch (err) {
            console.error('changePassword error:', err);
            return { success: false, error: 'Şifre güncellenemedi.' };
        }
    },

    getPasswordHint: function () {
        try {
            if (currentSupabaseSession && currentSupabaseSession.user &&
                currentSupabaseSession.user.user_metadata &&
                currentSupabaseSession.user.user_metadata.password_hint) {
                return currentSupabaseSession.user.user_metadata.password_hint;
            }
        } catch (e) {}
        return '';
    },

    updateProfile: function (updates) {
        const user = this.getCurrentUser() || this.getPendingPasswordChange();
        if (!user) return { success: false, error: 'Oturum bulunamadı.' };
        const email = String(user.username || user.email || '').toLowerCase();
        if (!email) return { success: false, error: 'E-posta bulunamadı.' };
        const next = Object.assign({}, user);

        if (updates && typeof updates.fullName === 'string') {
            const name = updates.fullName.trim();
            if (name.length < 2) return { success: false, error: 'İsim en az 2 karakter olmalıdır.' };
            next.fullName = name;
            localStorage.setItem(getDisplayNameKey(email), name);
            if (supabaseClient) {
                supabaseClient.auth.updateUser({ data: { full_name: name } }).catch(function () {});
            }
        }
        if (updates && Object.prototype.hasOwnProperty.call(updates, 'avatar')) {
            next.avatar = updates.avatar || null;
            if (updates.avatar) localStorage.setItem(getAvatarKey(email), updates.avatar);
            else localStorage.removeItem(getAvatarKey(email));
        }
        localStorage.setItem('auth_user', JSON.stringify(next));
        if (localStorage.getItem('pendingPasswordChange')) {
            localStorage.setItem('pendingPasswordChange', JSON.stringify(next));
        }
        return { success: true, user: next };
    },

    resetPasswordWithDefault: async function () {
        return { success: false, error: 'Şifre sıfırlama Supabase üzerinden yapılmalıdır.' };
    },

    logout: async function (options) {
        options = options || { redirect: true };
        try {
            const client = await waitForSupabaseClient(1500);
            if (client) {
                await Promise.race([
                    client.auth.signOut(),
                    new Promise(function (resolve) { setTimeout(resolve, 1200); })
                ]);
            }
        } catch (e) {}
        clearSessionLocally();
        localStorage.removeItem('loginPortal');
        localStorage.removeItem('auditorUser');
        if (options.redirect !== false) window.location.replace('login.html');
    },

    requireAuth: function () {
        if (!this.isAuthenticated()) {
            window.location.href = 'login.html';
            return false;
        }
        return true;
    },

    requireAdmin: function () {
        if (!this.requireAuth()) return false;
        if (!this.isAdmin()) {
            alert('Bu sayfaya erişmek için yönetici yetkisi gerekir.');
            window.location.href = 'dashboard.html';
            return false;
        }
        return true;
    }
};

(async function bindSupabaseAuthListener() {
    const client = await waitForSupabaseClient();
    if (!client) return;
    supabaseClient = client;
    await syncSupabaseSession();
    supabaseClient.auth.onAuthStateChange(function (event, session) {
        currentSupabaseSession = session;
        if (session && session.user) {
            const payload = buildUserPayloadFromSupabase(session.user);
            storeSessionLocally(payload, session.access_token, {
                requiresPasswordChange: requiresPasswordChangeFromMeta(session.user.user_metadata)
            });
        } else if (event === 'SIGNED_OUT') {
            clearSessionLocally();
        }
    });
})();

window.Auth = Auth;

if (typeof window !== 'undefined' && !String(window.location.pathname || '').includes('login.html')) {
    const protectedPages = ['dashboard.html', 'client-list.html', 'client-detail.html', 'contracts.html', 'reports.html', 'payments.html', 'offers.html', 'users.html', 'online-users.html'];
    const currentPage = window.location.pathname.split('/').pop();
    if (protectedPages.indexOf(currentPage) !== -1) {
        function checkAdminAccess() {
            if (localStorage.getItem('loginPortal') === 'auditor') {
                window.location.href = 'auditor-dashboard.html';
                return;
            }
            if (typeof Auth !== 'undefined' && !Auth.isAuthenticated()) {
                window.location.href = 'login.html';
            }
        }
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', function () { setTimeout(checkAdminAccess, 100); });
        } else {
            setTimeout(checkAdminAccess, 100);
        }
    }
}
