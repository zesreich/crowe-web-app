-- =====================================================
-- Supabase Reports Table for Crowe HSY
-- Raporlar tablosu
-- =====================================================

-- Enable UUID extension (if not already enabled)
CREATE EXTENSION IF NOT EXISTS "uuid-ossp";

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

-- Create indexes for faster lookups
CREATE INDEX IF NOT EXISTS idx_reports_company ON reports(company);
CREATE INDEX IF NOT EXISTS idx_reports_start_date ON reports(start_date DESC);
CREATE INDEX IF NOT EXISTS idx_reports_status ON reports(status);
CREATE INDEX IF NOT EXISTS idx_reports_created_at ON reports(created_at DESC);

-- =====================================================
-- Function to Update Updated_At Timestamp
-- =====================================================
CREATE OR REPLACE FUNCTION update_reports_updated_at()
RETURNS TRIGGER AS $$
BEGIN
    NEW.updated_at = CURRENT_TIMESTAMP;
    RETURN NEW;
END;
$$ language 'plpgsql';

-- Create trigger to automatically update updated_at
DROP TRIGGER IF EXISTS update_reports_updated_at ON reports;
CREATE TRIGGER update_reports_updated_at 
    BEFORE UPDATE ON reports
    FOR EACH ROW 
    EXECUTE FUNCTION update_reports_updated_at();

-- =====================================================
-- Row Level Security (RLS) Policies
-- =====================================================

-- Enable RLS
ALTER TABLE reports ENABLE ROW LEVEL SECURITY;

-- Drop existing policies if they exist
DROP POLICY IF EXISTS "Authenticated users can view reports" ON reports;
DROP POLICY IF EXISTS "Authenticated users can insert reports" ON reports;
DROP POLICY IF EXISTS "Authenticated users can update reports" ON reports;
DROP POLICY IF EXISTS "Authenticated users can delete reports" ON reports;

-- Policy: Authenticated users can view all reports
CREATE POLICY "Authenticated users can view reports" ON reports
    FOR SELECT 
    USING (auth.role() = 'authenticated');

-- Policy: Authenticated users can insert reports
CREATE POLICY "Authenticated users can insert reports" ON reports
    FOR INSERT 
    WITH CHECK (auth.role() = 'authenticated');

-- Policy: Authenticated users can update reports
CREATE POLICY "Authenticated users can update reports" ON reports
    FOR UPDATE 
    USING (auth.role() = 'authenticated')
    WITH CHECK (auth.role() = 'authenticated');

-- Policy: Authenticated users can delete reports
CREATE POLICY "Authenticated users can delete reports" ON reports
    FOR DELETE 
    USING (auth.role() = 'authenticated');

-- =====================================================
-- Verification Query
-- =====================================================

-- Check if table was created successfully
SELECT 
    'reports' as table_name,
    COUNT(*) as row_count
FROM reports;


