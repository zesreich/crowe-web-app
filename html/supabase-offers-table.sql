-- =====================================================
-- Supabase Offers Table for Crowe HSY
-- Teklifler tablosu
-- =====================================================

-- Enable UUID extension (if not already enabled)
CREATE EXTENSION IF NOT EXISTS "uuid-ossp";

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

-- Create indexes for faster lookups
CREATE INDEX IF NOT EXISTS idx_offers_offer_no ON offers(offer_no);
CREATE INDEX IF NOT EXISTS idx_offers_client_name ON offers(client_name);
CREATE INDEX IF NOT EXISTS idx_offers_status ON offers(status);
CREATE INDEX IF NOT EXISTS idx_offers_send_date ON offers(send_date DESC);
CREATE INDEX IF NOT EXISTS idx_offers_created_at ON offers(created_at DESC);

-- =====================================================
-- Function to Update Updated_At Timestamp
-- =====================================================
CREATE OR REPLACE FUNCTION update_offers_updated_at()
RETURNS TRIGGER AS $$
BEGIN
    NEW.updated_at = CURRENT_TIMESTAMP;
    RETURN NEW;
END;
$$ language 'plpgsql';

-- Create trigger to automatically update updated_at
DROP TRIGGER IF EXISTS update_offers_updated_at ON offers;
CREATE TRIGGER update_offers_updated_at 
    BEFORE UPDATE ON offers
    FOR EACH ROW 
    EXECUTE FUNCTION update_offers_updated_at();

-- =====================================================
-- Row Level Security (RLS) Policies
-- =====================================================

-- Enable RLS
ALTER TABLE offers ENABLE ROW LEVEL SECURITY;

-- Drop existing policies if they exist
DROP POLICY IF EXISTS "Authenticated users can view offers" ON offers;
DROP POLICY IF EXISTS "Authenticated users can insert offers" ON offers;
DROP POLICY IF EXISTS "Authenticated users can update offers" ON offers;
DROP POLICY IF EXISTS "Authenticated users can delete offers" ON offers;

-- Policy: Authenticated users can view all offers
CREATE POLICY "Authenticated users can view offers" ON offers
    FOR SELECT 
    USING (auth.role() = 'authenticated');

-- Policy: Authenticated users can insert offers
CREATE POLICY "Authenticated users can insert offers" ON offers
    FOR INSERT 
    WITH CHECK (auth.role() = 'authenticated');

-- Policy: Authenticated users can update offers
CREATE POLICY "Authenticated users can update offers" ON offers
    FOR UPDATE 
    USING (auth.role() = 'authenticated')
    WITH CHECK (auth.role() = 'authenticated');

-- Policy: Authenticated users can delete offers
CREATE POLICY "Authenticated users can delete offers" ON offers
    FOR DELETE 
    USING (auth.role() = 'authenticated');

-- =====================================================
-- Verification Query
-- =====================================================

-- Check if table was created successfully
SELECT 
    'offers' as table_name,
    COUNT(*) as row_count
FROM offers;


