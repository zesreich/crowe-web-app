-- =====================================================
-- Supabase Payments Table for Crowe HSY
-- Ödemeler tablosu
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

-- Create indexes for faster lookups
CREATE INDEX IF NOT EXISTS idx_payments_company ON payments(company);
CREATE INDEX IF NOT EXISTS idx_payments_payment_date ON payments(payment_date DESC);
CREATE INDEX IF NOT EXISTS idx_payments_created_at ON payments(created_at DESC);

-- =====================================================
-- Function to Update Updated_At Timestamp
-- =====================================================
CREATE OR REPLACE FUNCTION update_payments_updated_at()
RETURNS TRIGGER AS $$
BEGIN
    NEW.updated_at = CURRENT_TIMESTAMP;
    RETURN NEW;
END;
$$ language 'plpgsql';

-- Create trigger to automatically update updated_at
DROP TRIGGER IF EXISTS update_payments_updated_at ON payments;
CREATE TRIGGER update_payments_updated_at 
    BEFORE UPDATE ON payments
    FOR EACH ROW 
    EXECUTE FUNCTION update_payments_updated_at();

-- =====================================================
-- Row Level Security (RLS) Policies
-- =====================================================

-- Enable RLS
ALTER TABLE payments ENABLE ROW LEVEL SECURITY;

-- Drop existing policies if they exist
DROP POLICY IF EXISTS "Authenticated users can view payments" ON payments;
DROP POLICY IF EXISTS "Authenticated users can insert payments" ON payments;
DROP POLICY IF EXISTS "Authenticated users can update payments" ON payments;
DROP POLICY IF EXISTS "Authenticated users can delete payments" ON payments;

-- Policy: Authenticated users can view all payments
CREATE POLICY "Authenticated users can view payments" ON payments
    FOR SELECT 
    USING (auth.role() = 'authenticated');

-- Policy: Authenticated users can insert payments
CREATE POLICY "Authenticated users can insert payments" ON payments
    FOR INSERT 
    WITH CHECK (auth.role() = 'authenticated');

-- Policy: Authenticated users can update payments
CREATE POLICY "Authenticated users can update payments" ON payments
    FOR UPDATE 
    USING (auth.role() = 'authenticated')
    WITH CHECK (auth.role() = 'authenticated');

-- Policy: Authenticated users can delete payments
CREATE POLICY "Authenticated users can delete payments" ON payments
    FOR DELETE 
    USING (auth.role() = 'authenticated');

-- =====================================================
-- Verification Query
-- =====================================================

-- Check if table was created successfully
SELECT 
    'payments' as table_name,
    COUNT(*) as row_count
FROM payments;


