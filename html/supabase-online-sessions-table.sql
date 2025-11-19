-- =====================================================
-- Supabase Online Sessions Table for Crowe HSY
-- Online kullanıcı oturumları tablosu
-- =====================================================

-- Enable UUID extension (if not already enabled)
CREATE EXTENSION IF NOT EXISTS "uuid-ossp";

-- =====================================================
-- Online Sessions Table
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

-- Create indexes for faster lookups
CREATE INDEX IF NOT EXISTS idx_online_sessions_user_id ON online_sessions(user_id);
CREATE INDEX IF NOT EXISTS idx_online_sessions_is_active ON online_sessions(is_active);
CREATE INDEX IF NOT EXISTS idx_online_sessions_last_activity ON online_sessions(last_activity DESC);

-- =====================================================
-- Row Level Security (RLS) Policies
-- =====================================================

-- Enable RLS
ALTER TABLE online_sessions ENABLE ROW LEVEL SECURITY;

-- Drop existing policies if they exist
DROP POLICY IF EXISTS "Authenticated users can view online_sessions" ON online_sessions;
DROP POLICY IF EXISTS "Authenticated users can insert online_sessions" ON online_sessions;
DROP POLICY IF EXISTS "Authenticated users can update online_sessions" ON online_sessions;
DROP POLICY IF EXISTS "Authenticated users can delete online_sessions" ON online_sessions;

-- Policy: Authenticated users can view all online sessions
CREATE POLICY "Authenticated users can view online_sessions" ON online_sessions
    FOR SELECT 
    USING (auth.role() = 'authenticated');

-- Policy: Authenticated users can insert online sessions
CREATE POLICY "Authenticated users can insert online_sessions" ON online_sessions
    FOR INSERT 
    WITH CHECK (auth.role() = 'authenticated');

-- Policy: Authenticated users can update online sessions
CREATE POLICY "Authenticated users can update online_sessions" ON online_sessions
    FOR UPDATE 
    USING (auth.role() = 'authenticated')
    WITH CHECK (auth.role() = 'authenticated');

-- Policy: Authenticated users can delete online sessions
CREATE POLICY "Authenticated users can delete online_sessions" ON online_sessions
    FOR DELETE 
    USING (auth.role() = 'authenticated');

-- =====================================================
-- Verification Query
-- =====================================================

-- Check if table was created successfully
SELECT 
    'online_sessions' as table_name,
    COUNT(*) as row_count
FROM online_sessions;

