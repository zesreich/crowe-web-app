-- =====================================================
-- Supabase Complete Tables for Crowe HSY
-- Payments, Reports, Contracts, Offers, Online Sessions
-- =====================================================

-- Enable UUID extension (if not already enabled)
CREATE EXTENSION IF NOT EXISTS "uuid-ossp";

-- =====================================================
-- Payments Table
-- =====================================================
CREATE TABLE IF NOT EXISTS payments (
    id UUID PRIMARY KEY DEFAULT uuid_generate_v4(),
    company VARCHAR(255) NOT NULL,
    report_type VARCHAR(100) NOT NULL,
    report_type_value VARCHAR(100),
    amount DECIMAL(15, 2) NOT NULL,
    currency VARCHAR(10) NOT NULL DEFAULT 'TRY',
    payment_date DATE NOT NULL,
    description TEXT,
    created_at TIMESTAMP WITH TIME ZONE DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP WITH TIME ZONE DEFAULT CURRENT_TIMESTAMP,
    created_by UUID REFERENCES auth.users(id) ON DELETE SET NULL
);

-- Create indexes for payments
CREATE INDEX IF NOT EXISTS idx_payments_company ON payments(company);
CREATE INDEX IF NOT EXISTS idx_payments_payment_date ON payments(payment_date DESC);
CREATE INDEX IF NOT EXISTS idx_payments_created_at ON payments(created_at DESC);

-- =====================================================
-- Reports Table
-- =====================================================
CREATE TABLE IF NOT EXISTS reports (
    id UUID PRIMARY KEY DEFAULT uuid_generate_v4(),
    company VARCHAR(255) NOT NULL,
    service VARCHAR(255) NOT NULL,
    report_type VARCHAR(50) NOT NULL,
    start_date DATE NOT NULL,
    end_date DATE NOT NULL,
    team VARCHAR(100) NOT NULL,
    status VARCHAR(50) NOT NULL DEFAULT 'Devam Ediyor',
    created_at TIMESTAMP WITH TIME ZONE DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP WITH TIME ZONE DEFAULT CURRENT_TIMESTAMP,
    created_by UUID REFERENCES auth.users(id) ON DELETE SET NULL
);

-- Create indexes for reports
CREATE INDEX IF NOT EXISTS idx_reports_company ON reports(company);
CREATE INDEX IF NOT EXISTS idx_reports_start_date ON reports(start_date DESC);
CREATE INDEX IF NOT EXISTS idx_reports_status ON reports(status);
CREATE INDEX IF NOT EXISTS idx_reports_created_at ON reports(created_at DESC);

-- =====================================================
-- Contracts Table
-- =====================================================
CREATE TABLE IF NOT EXISTS contracts (
    id UUID PRIMARY KEY DEFAULT uuid_generate_v4(),
    contract_type VARCHAR(100) NOT NULL, -- 'Bağımsız Denetim', 'Mali Müşavirlik', etc.
    company VARCHAR(255) NOT NULL,
    contract_name VARCHAR(255),
    period VARCHAR(50),
    auditor1 VARCHAR(255),
    auditor2 VARCHAR(255),
    auditor3 VARCHAR(255),
    team VARCHAR(100),
    hours VARCHAR(50),
    contract_received VARCHAR(50),
    kgk_reported VARCHAR(50),
    created_at TIMESTAMP WITH TIME ZONE DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP WITH TIME ZONE DEFAULT CURRENT_TIMESTAMP,
    created_by UUID REFERENCES auth.users(id) ON DELETE SET NULL
);

-- Create indexes for contracts
CREATE INDEX IF NOT EXISTS idx_contracts_contract_type ON contracts(contract_type);
CREATE INDEX IF NOT EXISTS idx_contracts_company ON contracts(company);
CREATE INDEX IF NOT EXISTS idx_contracts_created_at ON contracts(created_at DESC);

-- =====================================================
-- Offers Table
-- =====================================================
CREATE TABLE IF NOT EXISTS offers (
    id UUID PRIMARY KEY DEFAULT uuid_generate_v4(),
    offer_no VARCHAR(100) UNIQUE NOT NULL,
    client_name VARCHAR(255) NOT NULL,
    offer_type VARCHAR(255) NOT NULL,
    send_date DATE NOT NULL,
    status VARCHAR(50) NOT NULL DEFAULT 'Onay Bekliyor',
    ppt_files JSONB DEFAULT '[]'::jsonb, -- Array of file names
    pdf_files JSONB DEFAULT '[]'::jsonb, -- Array of file names
    created_at TIMESTAMP WITH TIME ZONE DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP WITH TIME ZONE DEFAULT CURRENT_TIMESTAMP,
    created_by UUID REFERENCES auth.users(id) ON DELETE SET NULL
);

-- Create indexes for offers
CREATE INDEX IF NOT EXISTS idx_offers_offer_no ON offers(offer_no);
CREATE INDEX IF NOT EXISTS idx_offers_client_name ON offers(client_name);
CREATE INDEX IF NOT EXISTS idx_offers_status ON offers(status);
CREATE INDEX IF NOT EXISTS idx_offers_send_date ON offers(send_date DESC);
CREATE INDEX IF NOT EXISTS idx_offers_created_at ON offers(created_at DESC);

-- =====================================================
-- Online Sessions Table (for tracking active users)
-- =====================================================
CREATE TABLE IF NOT EXISTS online_sessions (
    id UUID PRIMARY KEY DEFAULT uuid_generate_v4(),
    user_id UUID REFERENCES auth.users(id) ON DELETE CASCADE,
    user_email VARCHAR(255) NOT NULL,
    full_name VARCHAR(255),
    session_start TIMESTAMP WITH TIME ZONE DEFAULT CURRENT_TIMESTAMP,
    last_activity TIMESTAMP WITH TIME ZONE DEFAULT CURRENT_TIMESTAMP,
    active_files JSONB DEFAULT '[]'::jsonb, -- Array of active files
    ip_address VARCHAR(45),
    user_agent TEXT,
    is_active BOOLEAN DEFAULT true,
    created_at TIMESTAMP WITH TIME ZONE DEFAULT CURRENT_TIMESTAMP
);

-- Create indexes for online_sessions
CREATE INDEX IF NOT EXISTS idx_online_sessions_user_id ON online_sessions(user_id);
CREATE INDEX IF NOT EXISTS idx_online_sessions_is_active ON online_sessions(is_active);
CREATE INDEX IF NOT EXISTS idx_online_sessions_last_activity ON online_sessions(last_activity DESC);

-- =====================================================
-- Functions to Update Updated_At Timestamp
-- =====================================================

-- Payments update trigger
CREATE OR REPLACE FUNCTION update_payments_updated_at()
RETURNS TRIGGER AS $$
BEGIN
    NEW.updated_at = CURRENT_TIMESTAMP;
    RETURN NEW;
END;
$$ language 'plpgsql';

DROP TRIGGER IF EXISTS update_payments_updated_at ON payments;
CREATE TRIGGER update_payments_updated_at 
    BEFORE UPDATE ON payments
    FOR EACH ROW 
    EXECUTE FUNCTION update_payments_updated_at();

-- Reports update trigger
CREATE OR REPLACE FUNCTION update_reports_updated_at()
RETURNS TRIGGER AS $$
BEGIN
    NEW.updated_at = CURRENT_TIMESTAMP;
    RETURN NEW;
END;
$$ language 'plpgsql';

DROP TRIGGER IF EXISTS update_reports_updated_at ON reports;
CREATE TRIGGER update_reports_updated_at 
    BEFORE UPDATE ON reports
    FOR EACH ROW 
    EXECUTE FUNCTION update_reports_updated_at();

-- Contracts update trigger
CREATE OR REPLACE FUNCTION update_contracts_updated_at()
RETURNS TRIGGER AS $$
BEGIN
    NEW.updated_at = CURRENT_TIMESTAMP;
    RETURN NEW;
END;
$$ language 'plpgsql';

DROP TRIGGER IF EXISTS update_contracts_updated_at ON contracts;
CREATE TRIGGER update_contracts_updated_at 
    BEFORE UPDATE ON contracts
    FOR EACH ROW 
    EXECUTE FUNCTION update_contracts_updated_at();

-- Offers update trigger
CREATE OR REPLACE FUNCTION update_offers_updated_at()
RETURNS TRIGGER AS $$
BEGIN
    NEW.updated_at = CURRENT_TIMESTAMP;
    RETURN NEW;
END;
$$ language 'plpgsql';

DROP TRIGGER IF EXISTS update_offers_updated_at ON offers;
CREATE TRIGGER update_offers_updated_at 
    BEFORE UPDATE ON offers
    FOR EACH ROW 
    EXECUTE FUNCTION update_offers_updated_at();

-- =====================================================
-- Row Level Security (RLS) Policies
-- =====================================================

-- Enable RLS on all tables
ALTER TABLE payments ENABLE ROW LEVEL SECURITY;
ALTER TABLE reports ENABLE ROW LEVEL SECURITY;
ALTER TABLE contracts ENABLE ROW LEVEL SECURITY;
ALTER TABLE offers ENABLE ROW LEVEL SECURITY;
ALTER TABLE online_sessions ENABLE ROW LEVEL SECURITY;

-- Drop existing policies if they exist
DROP POLICY IF EXISTS "Authenticated users can view payments" ON payments;
DROP POLICY IF EXISTS "Authenticated users can insert payments" ON payments;
DROP POLICY IF EXISTS "Authenticated users can update payments" ON payments;
DROP POLICY IF EXISTS "Authenticated users can delete payments" ON payments;

DROP POLICY IF EXISTS "Authenticated users can view reports" ON reports;
DROP POLICY IF EXISTS "Authenticated users can insert reports" ON reports;
DROP POLICY IF EXISTS "Authenticated users can update reports" ON reports;
DROP POLICY IF EXISTS "Authenticated users can delete reports" ON reports;

DROP POLICY IF EXISTS "Authenticated users can view contracts" ON contracts;
DROP POLICY IF EXISTS "Authenticated users can insert contracts" ON contracts;
DROP POLICY IF EXISTS "Authenticated users can update contracts" ON contracts;
DROP POLICY IF EXISTS "Authenticated users can delete contracts" ON contracts;

DROP POLICY IF EXISTS "Authenticated users can view offers" ON offers;
DROP POLICY IF EXISTS "Authenticated users can insert offers" ON offers;
DROP POLICY IF EXISTS "Authenticated users can update offers" ON offers;
DROP POLICY IF EXISTS "Authenticated users can delete offers" ON offers;

DROP POLICY IF EXISTS "Authenticated users can view online_sessions" ON online_sessions;
DROP POLICY IF EXISTS "Authenticated users can insert online_sessions" ON online_sessions;
DROP POLICY IF EXISTS "Authenticated users can update online_sessions" ON online_sessions;
DROP POLICY IF EXISTS "Authenticated users can delete online_sessions" ON online_sessions;

-- Payments Policies
CREATE POLICY "Authenticated users can view payments" ON payments
    FOR SELECT 
    USING (auth.role() = 'authenticated');

CREATE POLICY "Authenticated users can insert payments" ON payments
    FOR INSERT 
    WITH CHECK (auth.role() = 'authenticated');

CREATE POLICY "Authenticated users can update payments" ON payments
    FOR UPDATE 
    USING (auth.role() = 'authenticated')
    WITH CHECK (auth.role() = 'authenticated');

CREATE POLICY "Authenticated users can delete payments" ON payments
    FOR DELETE 
    USING (auth.role() = 'authenticated');

-- Reports Policies
CREATE POLICY "Authenticated users can view reports" ON reports
    FOR SELECT 
    USING (auth.role() = 'authenticated');

CREATE POLICY "Authenticated users can insert reports" ON reports
    FOR INSERT 
    WITH CHECK (auth.role() = 'authenticated');

CREATE POLICY "Authenticated users can update reports" ON reports
    FOR UPDATE 
    USING (auth.role() = 'authenticated')
    WITH CHECK (auth.role() = 'authenticated');

CREATE POLICY "Authenticated users can delete reports" ON reports
    FOR DELETE 
    USING (auth.role() = 'authenticated');

-- Contracts Policies
CREATE POLICY "Authenticated users can view contracts" ON contracts
    FOR SELECT 
    USING (auth.role() = 'authenticated');

CREATE POLICY "Authenticated users can insert contracts" ON contracts
    FOR INSERT 
    WITH CHECK (auth.role() = 'authenticated');

CREATE POLICY "Authenticated users can update contracts" ON contracts
    FOR UPDATE 
    USING (auth.role() = 'authenticated')
    WITH CHECK (auth.role() = 'authenticated');

CREATE POLICY "Authenticated users can delete contracts" ON contracts
    FOR DELETE 
    USING (auth.role() = 'authenticated');

-- Offers Policies
CREATE POLICY "Authenticated users can view offers" ON offers
    FOR SELECT 
    USING (auth.role() = 'authenticated');

CREATE POLICY "Authenticated users can insert offers" ON offers
    FOR INSERT 
    WITH CHECK (auth.role() = 'authenticated');

CREATE POLICY "Authenticated users can update offers" ON offers
    FOR UPDATE 
    USING (auth.role() = 'authenticated')
    WITH CHECK (auth.role() = 'authenticated');

CREATE POLICY "Authenticated users can delete offers" ON offers
    FOR DELETE 
    USING (auth.role() = 'authenticated');

-- Online Sessions Policies
CREATE POLICY "Authenticated users can view online_sessions" ON online_sessions
    FOR SELECT 
    USING (auth.role() = 'authenticated');

CREATE POLICY "Authenticated users can insert online_sessions" ON online_sessions
    FOR INSERT 
    WITH CHECK (auth.role() = 'authenticated');

CREATE POLICY "Authenticated users can update online_sessions" ON online_sessions
    FOR UPDATE 
    USING (auth.role() = 'authenticated')
    WITH CHECK (auth.role() = 'authenticated');

CREATE POLICY "Authenticated users can delete online_sessions" ON online_sessions
    FOR DELETE 
    USING (auth.role() = 'authenticated');

-- =====================================================
-- Verification Queries
-- =====================================================

-- Check if tables were created successfully
SELECT 
    'payments' as table_name,
    COUNT(*) as row_count
FROM payments
UNION ALL
SELECT 
    'reports' as table_name,
    COUNT(*) as row_count
FROM reports
UNION ALL
SELECT 
    'contracts' as table_name,
    COUNT(*) as row_count
FROM contracts
UNION ALL
SELECT 
    'offers' as table_name,
    COUNT(*) as row_count
FROM offers
UNION ALL
SELECT 
    'online_sessions' as table_name,
    COUNT(*) as row_count
FROM online_sessions;

