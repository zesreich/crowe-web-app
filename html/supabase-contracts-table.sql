-- =====================================================
-- Supabase Contracts Table for Crowe HSY
-- Sözleşmeler tablosu
-- =====================================================

-- Enable UUID extension (if not already enabled)
CREATE EXTENSION IF NOT EXISTS "uuid-ossp";

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

-- Create indexes for faster lookups
CREATE INDEX IF NOT EXISTS idx_contracts_contract_type ON contracts(contract_type);
CREATE INDEX IF NOT EXISTS idx_contracts_company ON contracts(company);
CREATE INDEX IF NOT EXISTS idx_contracts_created_at ON contracts(created_at DESC);

-- =====================================================
-- Function to Update Updated_At Timestamp
-- =====================================================
CREATE OR REPLACE FUNCTION update_contracts_updated_at()
RETURNS TRIGGER AS $$
BEGIN
    NEW.updated_at = CURRENT_TIMESTAMP;
    RETURN NEW;
END;
$$ language 'plpgsql';

-- Create trigger to automatically update updated_at
DROP TRIGGER IF EXISTS update_contracts_updated_at ON contracts;
CREATE TRIGGER update_contracts_updated_at 
    BEFORE UPDATE ON contracts
    FOR EACH ROW 
    EXECUTE FUNCTION update_contracts_updated_at();

-- =====================================================
-- Row Level Security (RLS) Policies
-- =====================================================

-- Enable RLS
ALTER TABLE contracts ENABLE ROW LEVEL SECURITY;

-- Drop existing policies if they exist
DROP POLICY IF EXISTS "Authenticated users can view contracts" ON contracts;
DROP POLICY IF EXISTS "Authenticated users can insert contracts" ON contracts;
DROP POLICY IF EXISTS "Authenticated users can update contracts" ON contracts;
DROP POLICY IF EXISTS "Authenticated users can delete contracts" ON contracts;

-- Policy: Authenticated users can view all contracts
CREATE POLICY "Authenticated users can view contracts" ON contracts
    FOR SELECT 
    USING (auth.role() = 'authenticated');

-- Policy: Authenticated users can insert contracts
CREATE POLICY "Authenticated users can insert contracts" ON contracts
    FOR INSERT 
    WITH CHECK (auth.role() = 'authenticated');

-- Policy: Authenticated users can update contracts
CREATE POLICY "Authenticated users can update contracts" ON contracts
    FOR UPDATE 
    USING (auth.role() = 'authenticated')
    WITH CHECK (auth.role() = 'authenticated');

-- Policy: Authenticated users can delete contracts
CREATE POLICY "Authenticated users can delete contracts" ON contracts
    FOR DELETE 
    USING (auth.role() = 'authenticated');

-- =====================================================
-- Verification Query
-- =====================================================

-- Check if table was created successfully
SELECT 
    'contracts' as table_name,
    COUNT(*) as row_count
FROM contracts;

