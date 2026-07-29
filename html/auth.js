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
        'mert.cengiz@crowehsy.net': { fullName: 'Mert Cengiz', role: 'admin' },
        'ozkan.cengiz@crowehsy.net': { fullName: 'Özkan Cengiz', role: 'admin' },
        'mehmetali.sariad@crowehsy.net': { fullName: 'Mehmet Ali Sarıad', role: 'admin' },
        'eda.sefer@crowehsy.net': { fullName: 'Eda Sefer', role: 'admin' },
        'hakan.kilic@crowehsy.net': { fullName: 'Hakan Kılıç', role: 'admin' }
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

function getHintKey(email) {
    return 'passwordHint_' + String(email || '').toLowerCase();
}

function getDisplayNameKey(email) {
    return 'userDisplayName_' + String(email || '').toLowerCase();
}

function getAvatarKey(email) {
    return 'userAvatar_' + String(email || '').toLowerCase();
}

function reverseString(value) {
    return String(value || '').split('').reverse().join('');
}

function validateNewPassword(password, hint, options) {
    options = options || {};
    const pwd = String(password || '');
    const reminder = String(hint || '').trim();
    const defaultPassword = String(options.defaultPassword || authFallbackDefaultPassword || 'Crowe2022!');

    if (pwd.length < 8) {
        return { ok: false, error: 'Şifre en az 8 karakter olmalıdır.' };
    }
    if (!/[A-ZÇĞİÖŞÜ]/.test(pwd)) {
        return { ok: false, error: 'Şifrede en az 1 büyük harf olmalıdır.' };
    }
    if (!/[a-zçğıöşü]/.test(pwd)) {
        return { ok: false, error: 'Şifrede en az 1 küçük harf olmalıdır.' };
    }
    if (!/[0-9]/.test(pwd)) {
        return { ok: false, error: 'Şifrede en az 1 rakam olmalıdır.' };
    }
    if (!/[^A-Za-z0-9ÇĞİÖŞÜçğıöşü]/.test(pwd)) {
        return { ok: false, error: 'Şifrede en az 1 özel işaret olmalıdır (!@# vb.).' };
    }
    if (pwd.replace(/\s+/g, '') === defaultPassword.replace(/\s+/g, '')) {
        return { ok: false, error: 'Yeni şifre varsayılan şifreyle aynı olamaz.' };
    }
    if (!reminder) {
        return { ok: false, error: 'Hatırlatıcı kelime zorunludur.' };
    }
    if (reminder.length < 3) {
        return { ok: false, error: 'Hatırlatıcı kelime en az 3 karakter olmalıdır.' };
    }
    if (reminder.toLocaleLowerCase('tr') === pwd.toLocaleLowerCase('tr')) {
        return { ok: false, error: 'Hatırlatıcı kelime şifre ile aynı olamaz.' };
    }
    if (reminder.toLocaleLowerCase('tr') === reverseString(pwd).toLocaleLowerCase('tr')) {
        return { ok: false, error: 'Hatırlatıcı kelime şifrenin tersten yazılışı olamaz.' };
    }
    return { ok: true };
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
        const token = localStorage.getItem('auth_token') || '';
        const isLocalFallbackToken = token.indexOf('token_') === 0;
        if (isLocalFallbackToken) {
            try {
                const local = JSON.parse(localStorage.getItem('auth_user') || 'null');
                if (local && local.username &&
                    String(local.username).toLowerCase() !== String(currentSupabaseSession.user.email || '').toLowerCase()) {
                    // Yerel fallback oturumu korunur; eski Supabase oturumu temizlenir
                    supabaseClient.auth.signOut().catch(function () {});
                    currentSupabaseSession = null;
                    return;
                }
            } catch (e) { /* ignore */ }
        }
        const supUser = currentSupabaseSession.user;
        const email = String(supUser.email || '').toLowerCase();
        const savedName = localStorage.getItem(getDisplayNameKey(email));
        const savedAvatar = localStorage.getItem(getAvatarKey(email));
        const payload = {
            username: supUser.email,
            fullName: savedName || supUser.user_metadata?.full_name || supUser.email,
            role: supUser.app_metadata?.role || supUser.user_metadata?.role || 'admin',
            avatar: savedAvatar || null,
            loginTime: new Date().toISOString()
        };
        const needsPw = userNeedsPasswordChange(email, {
            metaRequires: supUser.user_metadata?.requires_password_change
        });
        storeSessionLocally(payload, currentSupabaseSession.access_token, {
            requiresPasswordChange: needsPw
        });
    }
    // Supabase oturumu yoksa local fallback oturumunu silme —
    // development login (FALLBACK_ADMINS) localStorage'da tutulur.
}

function truthyFlag(value) {
    return value === true || value === 'true' || value === 1 || value === '1';
}

function userNeedsPasswordChange(email, options) {
    options = options || {};
    const normalized = String(email || '').toLowerCase();
    // Bir kez başarıyla değiştirildiyse bir daha zorlanmasın
    if (normalized && hasPasswordChanged(normalized)) {
        return false;
    }
    // Sunucu açıkça "değiştirildi" diyorsa
    if (options.metaRequires === false || options.metaRequires === 'false') {
        return false;
    }
    if (options.usingDefaultPassword) return true;
    if (truthyFlag(options.metaRequires)) return true;
    return false;
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

    /** Supabase REST (clients vb.) için geçerli JWT oturumu garanti et */
    ensureSupabaseSession: async function(options) {
        options = options || {};
        if (!supabaseClient) {
            return { ok: false, error: 'Supabase bağlantısı yok.' };
        }
        try {
            const { data: sessionWrap } = await supabaseClient.auth.getSession();
            if (sessionWrap && sessionWrap.session) {
                currentSupabaseSession = sessionWrap.session;
                return { ok: true, session: sessionWrap.session };
            }
        } catch (e) { /* continue */ }

        const user = this.getCurrentUser() || this.getPendingPasswordChange();
        const email = String(
            options.email ||
            (user && (user.username || user.email)) ||
            ''
        ).toLowerCase();

        if (!email) {
            return {
                ok: false,
                error: 'Oturum e-postası bulunamadı. Çıkış yapıp tekrar giriş yapın.'
            };
        }

        const defaultPassword = authFallbackDefaultPassword || 'Crowe2022!';
        const candidates = [];
        if (options.password) candidates.push(String(options.password));
        const stored = getStoredPassword(email);
        if (stored) candidates.push(stored);
        // Son çare: varsayılan (sunucu henüz güncellenmemiş olabilir)
        if (defaultPassword) candidates.push(defaultPassword);

        const tried = {};
        let lastError = null;
        for (let i = 0; i < candidates.length; i++) {
            const pwd = candidates[i];
            const key = String(pwd || '');
            if (!key || tried[key]) continue;
            tried[key] = true;
            try {
                const { data, error } = await supabaseClient.auth.signInWithPassword({
                    email: email,
                    password: pwd
                });
                if (!error && data && data.session) {
                    currentSupabaseSession = data.session;
                    setStoredPassword(email, pwd);
                    if (pwd.replace(/\s+/g, '') !== String(defaultPassword).replace(/\s+/g, '')) {
                        markPasswordChanged(email, true);
                    }
                    const payload = Object.assign({}, user || {}, {
                        username: email,
                        loginTime: new Date().toISOString()
                    });
                    storeSessionLocally(payload, data.session.access_token, {
                        requiresPasswordChange: false
                    });
                    return { ok: true, session: data.session };
                }
                lastError = error;
            } catch (err) {
                lastError = err;
            }
        }

        return {
            ok: false,
            error: 'Supabase oturumu açılamadı. Lütfen şifrenizi girin veya çıkış yapıp yeniden giriş yapın.',
            needsPassword: true,
            detail: lastError && lastError.message ? lastError.message : null
        };
    },

    async login(username, password) {
        const normalizedEmail = username ? username.toLowerCase().trim() : '';
        const normalizedInputPassword = String(password || '').replace(/\s+/g, '');
        const defaultPasswordNorm = String(authFallbackDefaultPassword || 'Crowe2022!').replace(/\s+/g, '');
        const localStoredPassword = getStoredPassword(normalizedEmail);
        const localStoredNorm = localStoredPassword
            ? String(localStoredPassword).replace(/\s+/g, '')
            : '';
        const passwordAlreadyChanged = hasPasswordChanged(normalizedEmail);

        function buildPayload(base) {
            const savedName = localStorage.getItem(getDisplayNameKey(normalizedEmail));
            const savedAvatar = localStorage.getItem(getAvatarKey(normalizedEmail));
            return {
                username: base.username || normalizedEmail,
                fullName: savedName || base.fullName || normalizedEmail.split('@')[0],
                role: base.role || 'admin',
                avatar: savedAvatar || base.avatar || null,
                loginTime: new Date().toISOString()
            };
        }

        // Yerelde şifre değiştiyse Crowe2022! / eski şifre asla kabul edilmez
        if (passwordAlreadyChanged) {
            if (normalizedInputPassword === defaultPasswordNorm) {
                return { success: false, error: 'Bu şifre artık geçerli değil. Yeni şifrenizi kullanın.' };
            }
            if (localStoredNorm && normalizedInputPassword !== localStoredNorm) {
                // Yerel kayıt varsa birebir kontrol; yoksa Supabase'e bırak
                // (farklı cihaz) — yine de varsayılanı yukarıda kestik
            }
        }

        let supabaseAuthError = null;
        let supabaseUserExistsWrongPassword = false;

        // 1) Supabase Auth — production kaynağı
        if (supabaseClient) {
            try {
                const { data, error } = await supabaseClient.auth.signInWithPassword({
                    email: normalizedEmail,
                    password: password
                });

                if (!error && data && data.session) {
                    currentSupabaseSession = data.session;
                    const supUser = data.user;
                    const email = String(supUser.email || '').toLowerCase();
                    const meta = supUser.user_metadata || {};
                    const passwordChangedOnServer = meta.password_changed === true ||
                        meta.requires_password_change === false;
                    const usingDefault = normalizedInputPassword === defaultPasswordNorm;

                    if (passwordChangedOnServer) {
                        markPasswordChanged(email, true);
                    }

                    // Sunucuda şifre değişmişken varsayılan ile gelindiyse (teorik olarak olmamalı)
                    if (usingDefault && (passwordChangedOnServer || hasPasswordChanged(email))) {
                        try { await supabaseClient.auth.signOut(); } catch (e) { /* ignore */ }
                        return { success: false, error: 'Bu şifre artık geçerli değil. Yeni şifrenizi kullanın.' };
                    }

                    // Yerel yeni şifre cache'i — varsayılan değilse güncelle
                    if (!usingDefault) {
                        setStoredPassword(email, password);
                        markPasswordChanged(email, true);
                    }

                    const payload = buildPayload({
                        username: supUser.email,
                        fullName: meta.full_name || supUser.email,
                        role: supUser.app_metadata?.role || meta.role || 'admin'
                    });

                    const requiresPasswordChange = userNeedsPasswordChange(email, {
                        metaRequires: usingDefault ? true : meta.requires_password_change,
                        usingDefaultPassword: usingDefault
                    });

                    storeSessionLocally(payload, data.session.access_token, { requiresPasswordChange });
                    return { success: true, user: payload, requiresPasswordChange };
                }

                supabaseAuthError = error;
                const msg = String((error && error.message) || '').toLowerCase();
                // Kullanıcı var ama şifre yanlış — fallback ile Crowe2022! kabul ETMİYORUZ
                if (
                    msg.includes('invalid login') ||
                    msg.includes('invalid credentials') ||
                    msg.includes('wrong password') ||
                    msg.includes('email not confirmed') ||
                    (error && error.status === 400)
                ) {
                    supabaseUserExistsWrongPassword = true;
                }
                console.log('Supabase login failed:', error && error.message);
            } catch (err) {
                supabaseAuthError = err;
                console.log('Supabase connection error:', err && err.message);
            }
        }

        // Supabase "şifre hatalı" — yerel/sunucu şifresi ayrışmış olabilir; onar
        if (supabaseUserExistsWrongPassword) {
            if (
                passwordAlreadyChanged &&
                localStoredNorm &&
                normalizedInputPassword === localStoredNorm &&
                supabaseClient
            ) {
                try {
                    // Sunucuda hâlâ varsayılan olabilir → giriş + yeni şifreye yükselt
                    const { data: oldLogin, error: oldErr } = await supabaseClient.auth.signInWithPassword({
                        email: normalizedEmail,
                        password: authFallbackDefaultPassword || 'Crowe2022!'
                    });
                    if (!oldErr && oldLogin && oldLogin.session) {
                        const { error: upErr } = await supabaseClient.auth.updateUser({
                            password: password,
                            data: {
                                requires_password_change: false,
                                password_changed: true
                            }
                        });
                        if (!upErr) {
                            try { await supabaseClient.auth.signOut(); } catch (e) { /* ignore */ }
                            const { data: newLogin, error: newErr } = await supabaseClient.auth.signInWithPassword({
                                email: normalizedEmail,
                                password: password
                            });
                            if (!newErr && newLogin && newLogin.session) {
                                currentSupabaseSession = newLogin.session;
                                setStoredPassword(normalizedEmail, password);
                                markPasswordChanged(normalizedEmail, true);
                                const adminInfo = authFallbackAdmins[normalizedEmail] || {
                                    fullName: normalizedEmail.split('@')[0],
                                    role: 'admin'
                                };
                                const payload = buildPayload(adminInfo);
                                storeSessionLocally(payload, newLogin.session.access_token, {
                                    requiresPasswordChange: false
                                });
                                return { success: true, user: payload, requiresPasswordChange: false };
                            }
                        }
                    }
                } catch (migrateErr) {
                    console.warn('Şifre senkron onarımı başarısız:', migrateErr && migrateErr.message);
                }
            }
            return { success: false, error: 'Şifre hatalı.' };
        }

        // 2) Fallback — yalnızca Supabase yoksa veya ağ hatası varsa
        if (supabaseClient && !supabaseAuthError) {
            // Supabase cevap verdi ama kullanıcı yok / beklenmeyen durum
            return { success: false, error: 'Kullanıcı bulunamadı veya şifre hatalı.' };
        }

        if (supabaseClient && supabaseAuthError) {
            // Ağ / servis hatası — kontrollü fallback
            console.warn('Supabase erişilemedi, fallback deneniyor:', supabaseAuthError && supabaseAuthError.message);
        }

        if (authAppEnv === 'production' && supabaseClient && Object.keys(authFallbackAdmins).length === 0) {
            return { success: false, error: 'Kullanıcı bulunamadı veya şifre hatalı.' };
        }

        const adminInfo = authFallbackAdmins[normalizedEmail];
        if (!adminInfo) {
            return { success: false, error: 'Kullanıcı bulunamadı veya şifre hatalı.' };
        }

        if (authAppEnv === 'production' && !authFallbackDefaultPassword) {
            return { success: false, error: 'Şifre değiştirilmelidir. Lütfen yönetici ile iletişime geçin.' };
        }

        // Varsayılan şifre: sadece hiç değiştirilmemiş hesaplarda
        if (normalizedInputPassword === defaultPasswordNorm && passwordAlreadyChanged) {
            return { success: false, error: 'Bu şifre artık geçerli değil. Yeni şifrenizi kullanın.' };
        }

        const storedPassword = localStoredNorm || defaultPasswordNorm;
        if (!storedPassword || normalizedInputPassword !== String(storedPassword).replace(/\s+/g, '')) {
            return { success: false, error: 'Şifre hatalı.' };
        }

        // Supabase varken mümkünse yine de gerçek oturum açmayı dene (yukarıda başarısız oldu)
        // Fallback oturumu yalnız acil durum — DB yazmaları için sonra şifre sorulacak
        const payload = buildPayload(adminInfo);
        const usingDefault = normalizedInputPassword === defaultPasswordNorm;
        const needsPasswordChange = userNeedsPasswordChange(normalizedEmail, {
            usingDefaultPassword: usingDefault
        });

        if (!usingDefault) {
            setStoredPassword(normalizedEmail, password);
        }

        storeSessionLocally(payload, null, { requiresPasswordChange: needsPasswordChange });
        return { success: true, user: payload, requiresPasswordChange: needsPasswordChange };
    },

    validatePasswordRules: function(password, hint) {
        return validateNewPassword(password, hint);
    },

    getPasswordRuleStatus: function(password, hint) {
        const pwd = String(password || '');
        const reminder = String(hint || '').trim();
        const defaultPassword = String(authFallbackDefaultPassword || 'Crowe2022!');
        return {
            minLength: pwd.length >= 8,
            upper: /[A-ZÇĞİÖŞÜ]/.test(pwd),
            lower: /[a-zçğıöşü]/.test(pwd),
            digit: /[0-9]/.test(pwd),
            symbol: /[^A-Za-z0-9ÇĞİÖŞÜçğıöşü]/.test(pwd),
            notDefault: pwd.length > 0 && pwd.replace(/\s+/g, '') !== defaultPassword.replace(/\s+/g, ''),
            hintPresent: reminder.length >= 3,
            hintNotPassword: reminder.length > 0 && reminder.toLocaleLowerCase('tr') !== pwd.toLocaleLowerCase('tr'),
            hintNotReverse: reminder.length > 0 && reminder.toLocaleLowerCase('tr') !== reverseString(pwd).toLocaleLowerCase('tr')
        };
    },

    async changePassword(newPassword, hint) {
        const currentUser = this.getPendingPasswordChange() || this.getCurrentUser();
        if (!currentUser) {
            return { success: false, error: 'Kullanıcı bulunamadı.' };
        }

        const normalizedEmail = String(currentUser.username || currentUser.email || '').toLowerCase();
        const check = validateNewPassword(newPassword, hint);
        if (!check.ok) {
            return { success: false, error: check.error };
        }

        const defaultPassword = authFallbackDefaultPassword || 'Crowe2022!';
        const previousLocal = getStoredPassword(normalizedEmail);

        // Supabase zorunlu: şifre sunucuda kalıcı olmalı
        if (!supabaseClient) {
            return { success: false, error: 'Supabase bağlantısı yok. Şifre sunucuya kaydedilemedi.' };
        }

        try {
            let session = null;
            const { data: sessionWrap } = await supabaseClient.auth.getSession();
            session = sessionWrap && sessionWrap.session;

            // Oturum yoksa bilinen şifrelerle giriş dene (önce yerel eski, sonra varsayılan)
            if (!session) {
                const candidates = [];
                if (previousLocal && previousLocal !== newPassword) candidates.push(previousLocal);
                candidates.push(defaultPassword);
                for (let i = 0; i < candidates.length; i++) {
                    const { data: signData, error: signErr } = await supabaseClient.auth.signInWithPassword({
                        email: normalizedEmail,
                        password: candidates[i]
                    });
                    if (!signErr && signData && signData.session) {
                        session = signData.session;
                        currentSupabaseSession = session;
                        break;
                    }
                }
            }

            if (!session) {
                return {
                    success: false,
                    error: 'Sunucu oturumu açılamadı. Crowe2022! ile tekrar giriş yapıp şifreyi yeniden deneyin.'
                };
            }

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

            // Doğrulama: yeni şifreyle gerçekten giriş yapılabiliyor mu?
            try { await supabaseClient.auth.signOut(); } catch (e) { /* ignore */ }
            const { data: verifyData, error: verifyError } = await supabaseClient.auth.signInWithPassword({
                email: normalizedEmail,
                password: newPassword
            });

            if (verifyError || !verifyData || !verifyData.session) {
                return {
                    success: false,
                    error: 'Şifre kaydı doğrulanamadı. Lütfen tekrar deneyin.'
                };
            }

            currentSupabaseSession = verifyData.session;

            // Yerel cache (cihaz hatırlasın)
            setStoredPassword(normalizedEmail, newPassword);
            markPasswordChanged(normalizedEmail, true);
            localStorage.setItem(getHintKey(normalizedEmail), String(hint || '').trim());
            this.clearPendingPasswordChange();

            const payload = Object.assign({}, currentUser, {
                username: normalizedEmail,
                loginTime: new Date().toISOString()
            });
            storeSessionLocally(payload, verifyData.session.access_token, { requiresPasswordChange: false });

            return { success: true };
        } catch (err) {
            console.error('changePassword error:', err);
            return { success: false, error: 'Şifre güncellenemedi: ' + (err && err.message ? err.message : 'bilinmeyen hata') };
        }
    },

    getPasswordHint: function(email) {
        const user = this.getCurrentUser();
        const key = email || (user && (user.username || user.email));
        if (!key) return '';
        return localStorage.getItem(getHintKey(key)) || '';
    },

    updateProfile: function(updates) {
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
                supabaseClient.auth.updateUser({
                    data: { full_name: name }
                }).catch(function (err) {
                    console.warn('Supabase isim güncellenemedi:', err && err.message);
                });
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

    resetPasswordWithDefault: function() {
        const user = this.getCurrentUser();
        if (!user) return { success: false, error: 'Oturum bulunamadı.' };
        const email = String(user.username || user.email || '').toLowerCase();
        localStorage.setItem(getStoredPasswordKey(email), authFallbackDefaultPassword || 'Crowe2022!');
        markPasswordChanged(email, false);
        localStorage.removeItem(getHintKey(email));
        storeSessionLocally(user, localStorage.getItem('auth_token'), { requiresPasswordChange: true });
        return { success: true };
    },

    logout: async function(options = { redirect: true }) {
        try {
            if (supabaseClient) {
                await Promise.race([
                    supabaseClient.auth.signOut(),
                    new Promise(function (resolve) { setTimeout(resolve, 1200); })
                ]);
            }
        } catch (e) { /* ignore */ }
        clearSessionLocally();
        localStorage.removeItem('loginPortal');
        localStorage.removeItem('auditorUser');
        this.clearPendingPasswordChange();
        if (options.redirect !== false) {
            window.location.replace('login.html');
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
            const token = localStorage.getItem('auth_token') || '';
            const isLocalFallbackToken = token.indexOf('token_') === 0;
            if (isLocalFallbackToken) {
                try {
                    const local = JSON.parse(localStorage.getItem('auth_user') || 'null');
                    if (local && local.username &&
                        String(local.username).toLowerCase() !== String(session.user.email || '').toLowerCase()) {
                        supabaseClient.auth.signOut().catch(function () {});
                        return;
                    }
                } catch (e) { /* ignore */ }
            }
            const supUser = session.user;
            const email = String(supUser.email || '').toLowerCase();
            const savedName = localStorage.getItem(getDisplayNameKey(email));
            const savedAvatar = localStorage.getItem(getAvatarKey(email));
            const payload = {
                username: supUser.email,
                fullName: savedName || supUser.user_metadata?.full_name || supUser.email,
                role: supUser.app_metadata?.role || supUser.user_metadata?.role || 'admin',
                avatar: savedAvatar || null,
                loginTime: new Date().toISOString()
            };
            const requiresPasswordChange = userNeedsPasswordChange(email, {
                metaRequires: supUser.user_metadata?.requires_password_change
            });
            storeSessionLocally(payload, session.access_token, { requiresPasswordChange });
        } else if (event === 'SIGNED_OUT') {
            const token = localStorage.getItem('auth_token') || '';
            if (token.indexOf('token_') === 0) {
                return;
            }
            clearSessionLocally();
        }
    });
}

// const/let window'a yazılmaz; shell ve sayfalar window.Auth bekliyor
window.Auth = Auth;

// Global authentication kontrolü - DOMContentLoaded ile geciktir
if (typeof window !== 'undefined' && window.location.pathname !== '/login.html' && !window.location.pathname.includes('login.html')) {
    const protectedPages = ['dashboard.html', 'client-list.html', 'client-detail.html', 'contracts.html', 'reports.html', 'payments.html', 'offers.html', 'users.html', 'online-users.html'];
    const currentPage = window.location.pathname.split('/').pop();

    if (protectedPages.includes(currentPage)) {
        // Denetçi portalından girenler yönetim paneline erişemez
        function checkAdminAccess() {
            if (localStorage.getItem('loginPortal') === 'auditor') {
                window.location.href = 'auditor-dashboard.html';
                return;
            }
            if (typeof Auth !== 'undefined' && !Auth.isAuthenticated()) {
                window.location.href = 'login.html';
            }
        }
        // DOMContentLoaded ile kontrol et, script'lerin yüklenmesi için bekle
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', function() {
                setTimeout(checkAdminAccess, 100);
            });
        } else {
            // Sayfa zaten yüklendiyse direkt kontrol et
            setTimeout(checkAdminAccess, 100);
        }
    }
}





