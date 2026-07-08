-- =====================================================
-- Supabase Clients Table for Crowe HSY
-- Excel'den içe aktarılan müşteri verilerini saklar
-- =====================================================

-- Enable UUID extension (if not already enabled)
CREATE EXTENSION IF NOT EXISTS "uuid-ossp";

-- =====================================================
-- Clients Table
-- =====================================================
CREATE TABLE IF NOT EXISTS clients (
    id UUID PRIMARY KEY DEFAULT uuid_generate_v4(),
    vergi_no VARCHAR(50) NOT NULL,
    unvan VARCHAR(255) NOT NULL,
    vergi_dairesi VARCHAR(255) NOT NULL,
    ekip VARCHAR(100) NOT NULL,
    created_at TIMESTAMP WITH TIME ZONE DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP WITH TIME ZONE DEFAULT CURRENT_TIMESTAMP,
    created_by UUID REFERENCES auth.users(id) ON DELETE SET NULL,
    UNIQUE(vergi_no)
);

-- Create indexes for faster lookups
CREATE INDEX IF NOT EXISTS idx_clients_vergi_no ON clients(vergi_no);
CREATE INDEX IF NOT EXISTS idx_clients_unvan ON clients(unvan);
CREATE INDEX IF NOT EXISTS idx_clients_ekip ON clients(ekip);
CREATE INDEX IF NOT EXISTS idx_clients_created_at ON clients(created_at DESC);

-- =====================================================
-- Function to Update Updated_At Timestamp
-- =====================================================
CREATE OR REPLACE FUNCTION update_clients_updated_at()
RETURNS TRIGGER AS $$
BEGIN
    NEW.updated_at = CURRENT_TIMESTAMP;
    RETURN NEW;
END;
$$ language 'plpgsql';

-- Create trigger to automatically update updated_at
DROP TRIGGER IF EXISTS update_clients_updated_at ON clients;
CREATE TRIGGER update_clients_updated_at 
    BEFORE UPDATE ON clients
    FOR EACH ROW 
    EXECUTE FUNCTION update_clients_updated_at();

-- =====================================================
-- Row Level Security (RLS) Policies
-- =====================================================

-- Enable RLS
ALTER TABLE clients ENABLE ROW LEVEL SECURITY;

-- Drop existing policies if they exist
DROP POLICY IF EXISTS "Authenticated users can view clients" ON clients;
DROP POLICY IF EXISTS "Authenticated users can insert clients" ON clients;
DROP POLICY IF EXISTS "Authenticated users can update clients" ON clients;
DROP POLICY IF EXISTS "Authenticated users can delete clients" ON clients;

-- Policy: Authenticated users can view all clients
CREATE POLICY "Authenticated users can view clients" ON clients
    FOR SELECT 
    USING (auth.role() = 'authenticated');

-- Policy: Authenticated users can insert clients
CREATE POLICY "Authenticated users can insert clients" ON clients
    FOR INSERT 
    WITH CHECK (auth.role() = 'authenticated');

-- Policy: Authenticated users can update clients
CREATE POLICY "Authenticated users can update clients" ON clients
    FOR UPDATE 
    USING (auth.role() = 'authenticated')
    WITH CHECK (auth.role() = 'authenticated');

-- Policy: Authenticated users can delete clients
CREATE POLICY "Authenticated users can delete clients" ON clients
    FOR DELETE 
    USING (auth.role() = 'authenticated');

-- =====================================================
-- Verification Query
-- =====================================================

-- Check if table was created successfully
SELECT 
    'clients' as table_name,
    COUNT(*) as row_count
FROM clients;

