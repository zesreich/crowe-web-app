-- =====================================================
-- Müşteri genişletme: blacklist, rol atamaları
-- Mevcut projede bir kez çalıştırın
-- =====================================================

ALTER TABLE clients ADD COLUMN IF NOT EXISTS is_blacklisted BOOLEAN NOT NULL DEFAULT false;
ALTER TABLE clients ADD COLUMN IF NOT EXISTS blacklist_reason TEXT;
ALTER TABLE clients ADD COLUMN IF NOT EXISTS uygulamaci VARCHAR(255);
ALTER TABLE clients ADD COLUMN IF NOT EXISTS partner VARCHAR(255);
ALTER TABLE clients ADD COLUMN IF NOT EXISTS ekip_lideri VARCHAR(255);
ALTER TABLE clients ADD COLUMN IF NOT EXISTS details JSONB DEFAULT '{}'::jsonb;

CREATE INDEX IF NOT EXISTS idx_clients_blacklisted ON clients(is_blacklisted) WHERE is_blacklisted = true;
