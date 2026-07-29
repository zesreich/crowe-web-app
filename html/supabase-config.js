// Supabase Configuration for Crowe HSY
// Project URL ve anon key: html/config.js dosyasında window.SUPABASE_URL / window.SUPABASE_ANON_KEY ile set edilir.
// Bu dosyayı yüklerken config.js mutlaka ÖNCE gelmeli (aksi halde istemci oluşmaz).
// Eski sabit proje URL'si kaldırıldı; yanlış projeye istek gitmesini önler.

// Global Supabase Client Instance (Singleton Pattern)
// Bu sayede tüm sayfalarda aynı client instance'ı kullanılır
(function initSupabaseClient() {
    // Wait for Supabase library to load
    function createGlobalInstance() {
        if (typeof window.supabase !== 'undefined' && window.SUPABASE_URL && window.SUPABASE_ANON_KEY) {
            if (!window.SUPABASE_URL.includes('YOUR_') && !window.SUPABASE_ANON_KEY.includes('YOUR_')) {
                // Eğer zaten bir global client varsa, yenisini oluşturma
                if (!window.__supabaseClientInstance) {
                    window.__supabaseClientInstance = window.supabase.createClient(window.SUPABASE_URL, window.SUPABASE_ANON_KEY, {
                        auth: {
                            persistSession: true,
                            autoRefreshToken: true,
                            detectSessionInUrl: true,
                            storage: window.localStorage
                        }
                    });
                    console.log('✅ Global Supabase client instance oluşturuldu');
                } else {
                    console.log('✅ Mevcut global Supabase client instance kullanılıyor');
                }
            }
        } else if (typeof window.supabase === 'undefined') {
            // Supabase library henüz yüklenmedi, kısa bir süre bekle
            setTimeout(createGlobalInstance, 50);
        }
    }
    
    // Try immediately
    createGlobalInstance();
    
    // Also try on DOMContentLoaded
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', createGlobalInstance);
    }
})();

// Global getSupabaseClient function - tüm sayfalarda kullanılabilir
window.getSupabaseClient = function() {
    // Önce global instance'ı kontrol et
    if (window.__supabaseClientInstance) {
        return window.__supabaseClientInstance;
    }
    
    // Fallback: auth.js'deki supabaseClient'ı kontrol et
    if (typeof supabaseClient !== 'undefined' && supabaseClient) {
        return supabaseClient;
    }
    
    // Son çare: Yeni client oluştur (sadece gerekirse)
    if (typeof window.supabase !== 'undefined' && window.SUPABASE_URL && window.SUPABASE_ANON_KEY) {
        if (!window.SUPABASE_URL.includes('YOUR_') && !window.SUPABASE_ANON_KEY.includes('YOUR_')) {
            window.__supabaseClientInstance = window.supabase.createClient(window.SUPABASE_URL, window.SUPABASE_ANON_KEY, {
                auth: {
                    persistSession: true,
                    autoRefreshToken: true,
                    detectSessionInUrl: true,
                    storage: window.localStorage
                }
            });
            return window.__supabaseClientInstance;
        }
    }
    
    return null;
};

// Supabase JavaScript Client Library
// Bu script'i HTML sayfalarınızda yükleyin (auth.js'den önce)
// <script src="https://cdn.jsdelivr.net/npm/@supabase/supabase-js@2"></script>
// <script src="supabase-config.js"></script>
// <script src="auth.js"></script>

// Not: Supabase anon key'i bulmak için:
// 1. Supabase Dashboard'a gidin: https://app.supabase.com
// 2. Projenizi seçin
// 3. Sol menüden "Settings" > "API" seçin
// 4. "Project API keys" bölümünde "anon" veya "public" key'i kopyalayın



