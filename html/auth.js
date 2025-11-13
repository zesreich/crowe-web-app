// Authentication System for Crowe HSY
// Supabase destekli olacak şekilde güncellendi, Supabase yapılandırması yapılmazsa eski localStorage mantığına geri döner.

const SUPABASE_URL = window.SUPABASE_URL || 'YOUR_SUPABASE_URL';
const SUPABASE_ANON_KEY = window.SUPABASE_ANON_KEY || 'YOUR_SUPABASE_ANON_KEY';
const SUPABASE_PLACEHOLDER = SUPABASE_URL.includes('YOUR_') || SUPABASE_ANON_KEY.includes('YOUR_');

const FALLBACK_DEFAULT_PASSWORD = 'Crowe2022!';
const FALLBACK_ADMINS = {
    'mehmetali.sariad@crowehsy.net': { fullName: 'Mehmet Ali Sariad', role: 'admin' },
    'ozkan.cengiz@crowehsy.net': { fullName: 'Özkan Cengiz', role: 'admin' },
    'mert.cengiz@crowehsy.net': { fullName: 'Mert Cengiz', role: 'admin' },
    'hakan.kilic@crowehsy.net': { fullName: 'Hakan Kılıç', role: 'admin' },
    'eda.sefer@crowehsy.net': { fullName: 'Eda Sefer', role: 'admin' }
};

let supabaseClient = null;
let currentSupabaseSession = null;

if (typeof window !== 'undefined' && typeof window.supabase !== 'undefined' && !SUPABASE_PLACEHOLDER) {
    supabaseClient = window.supabase.createClient(SUPABASE_URL, SUPABASE_ANON_KEY, {
        auth: {
            persistSession: true,
            storage: window.localStorage
        }
    });
}

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
    } else {
        clearSessionLocally();
    }
}

const Auth = {
    supabaseEnabled: Boolean(supabaseClient),

    isAuthenticated: function() {
        const user = localStorage.getItem('auth_user');
        const token = localStorage.getItem('auth_token');
        return Boolean(user && token);
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

        if (supabaseClient) {
            const { data, error } = await supabaseClient.auth.signInWithPassword({
                email: normalizedEmail,
                password: password
            });

            if (error) {
                return { success: false, error: error.message };
            }

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

        // Fallback: local listed admins
        const adminInfo = FALLBACK_ADMINS[normalizedEmail];
        if (!adminInfo) {
            return { success: false, error: 'Kullanıcı bulunamadı.' };
        }

        const storedPassword = getStoredPassword(normalizedEmail) || FALLBACK_DEFAULT_PASSWORD;
        if (password !== storedPassword) {
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
    supabaseClient.auth.onAuthStateChange((_event, session) => {
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
        } else {
            clearSessionLocally();
        }
    });
}

if (typeof window !== 'undefined' && window.location.pathname !== '/login.html' && !window.location.pathname.includes('login.html')) {
    const protectedPages = ['dashboard.html', 'client-list.html', 'client-detail.html', 'contracts.html', 'reports.html', 'payments.html', 'offers.html', 'users.html', 'online-users.html'];
    const currentPage = window.location.pathname.split('/').pop();

    if (protectedPages.includes(currentPage)) {
        if (!Auth.isAuthenticated()) {
            window.location.href = 'login.html';
        }
    }
}





