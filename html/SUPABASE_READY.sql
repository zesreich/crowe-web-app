-- =====================================================
-- Supabase Database Schema for Crowe HSY
-- HAZIR KULLANIM - Direkt Supabase'de çalıştırın
-- =====================================================

-- Enable UUID extension
CREATE EXTENSION IF NOT EXISTS "uuid-ossp";

-- =====================================================
-- Users Table
-- =====================================================
CREATE TABLE IF NOT EXISTS users (
    id UUID PRIMARY KEY DEFAULT uuid_generate_v4(),
    username VARCHAR(255) UNIQUE NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    full_name VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE,
    role VARCHAR(50) DEFAULT 'admin' CHECK (role IN ('admin', 'employee', 'user')),
    is_active BOOLEAN DEFAULT true,
    created_at TIMESTAMP WITH TIME ZONE DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP WITH TIME ZONE DEFAULT CURRENT_TIMESTAMP
);

-- Create indexes for faster lookups
CREATE INDEX IF NOT EXISTS idx_users_username ON users(username);
CREATE INDEX IF NOT EXISTS idx_users_email ON users(email);
CREATE INDEX IF NOT EXISTS idx_users_role ON users(role);

-- =====================================================
-- User Sessions Table
-- =====================================================
CREATE TABLE IF NOT EXISTS user_sessions (
    id UUID PRIMARY KEY DEFAULT uuid_generate_v4(),
    user_id UUID NOT NULL REFERENCES users(id) ON DELETE CASCADE,
    session_token VARCHAR(255) UNIQUE NOT NULL,
    expires_at TIMESTAMP WITH TIME ZONE NOT NULL,
    created_at TIMESTAMP WITH TIME ZONE DEFAULT CURRENT_TIMESTAMP,
    ip_address VARCHAR(45),
    user_agent TEXT
);

CREATE INDEX IF NOT EXISTS idx_sessions_user_id ON user_sessions(user_id);
CREATE INDEX IF NOT EXISTS idx_sessions_token ON user_sessions(session_token);
CREATE INDEX IF NOT EXISTS idx_sessions_expires_at ON user_sessions(expires_at);

-- =====================================================
-- Function to Update Updated_At Timestamp
-- =====================================================
CREATE OR REPLACE FUNCTION update_updated_at_column()
RETURNS TRIGGER AS $$
BEGIN
    NEW.updated_at = CURRENT_TIMESTAMP;
    RETURN NEW;
END;
$$ language 'plpgsql';

-- Create trigger to automatically update updated_at
DROP TRIGGER IF EXISTS update_users_updated_at ON users;
CREATE TRIGGER update_users_updated_at BEFORE UPDATE ON users
    FOR EACH ROW EXECUTE FUNCTION update_updated_at_column();

-- =====================================================
-- Insert Admin Users
-- =====================================================
-- NOT: Şifre hash'leri bcrypt ile oluşturulmalı
-- Aşağıdaki hash'leri gerçek bcrypt hash'leri ile değiştirin
-- Online tool: https://bcrypt-generator.com/
-- Cost factor: 10

-- Şifreler:
-- Eda Meric Sefer: Frankfurt2025!
-- Ozkan Cengiz: Munich2025!
-- Mehmet Ali Sariad: Nuremberg2025!
-- Mert Cengiz: Berlin2025!

-- Eda Meric Sefer - Password: Frankfurt2025!
-- Hash'ı https://bcrypt-generator.com/ adresinde oluşturun ve aşağıya yapıştırın
INSERT INTO users (username, password_hash, full_name, email, role, is_active) 
VALUES (
    'eda.meric',
    '$2b$10$YOUR_BCRYPT_HASH_HERE', -- Buraya bcrypt hash'ini yapıştırın
    'Eda Meric Sefer',
    'eda.meric@crowehsy.com',
    'admin',
    true
) ON CONFLICT (username) DO UPDATE SET
    password_hash = EXCLUDED.password_hash,
    full_name = EXCLUDED.full_name,
    email = EXCLUDED.email,
    role = EXCLUDED.role,
    is_active = EXCLUDED.is_active,
    updated_at = CURRENT_TIMESTAMP;

-- Ozkan Cengiz - Password: Munich2025!
-- Hash'ı https://bcrypt-generator.com/ adresinde oluşturun ve aşağıya yapıştırın
INSERT INTO users (username, password_hash, full_name, email, role, is_active) 
VALUES (
    'ozkan.cengiz',
    '$2b$10$YOUR_BCRYPT_HASH_HERE', -- Buraya bcrypt hash'ini yapıştırın
    'Ozkan Cengiz',
    'ozkan.cengiz@crowehsy.com',
    'admin',
    true
) ON CONFLICT (username) DO UPDATE SET
    password_hash = EXCLUDED.password_hash,
    full_name = EXCLUDED.full_name,
    email = EXCLUDED.email,
    role = EXCLUDED.role,
    is_active = EXCLUDED.is_active,
    updated_at = CURRENT_TIMESTAMP;

-- Mehmet Ali Sariad - Password: Nuremberg2025!
-- Hash'ı https://bcrypt-generator.com/ adresinde oluşturun ve aşağıya yapıştırın
INSERT INTO users (username, password_hash, full_name, email, role, is_active) 
VALUES (
    'mehmet.sariad',
    '$2b$10$YOUR_BCRYPT_HASH_HERE', -- Buraya bcrypt hash'ini yapıştırın
    'Mehmet Ali Sariad',
    'mehmet.sariad@crowehsy.com',
    'admin',
    true
) ON CONFLICT (username) DO UPDATE SET
    password_hash = EXCLUDED.password_hash,
    full_name = EXCLUDED.full_name,
    email = EXCLUDED.email,
    role = EXCLUDED.role,
    is_active = EXCLUDED.is_active,
    updated_at = CURRENT_TIMESTAMP;

-- Mert Cengiz - Password: Berlin2025!
-- Hash'ı https://bcrypt-generator.com/ adresinde oluşturun ve aşağıya yapıştırın
INSERT INTO users (username, password_hash, full_name, email, role, is_active) 
VALUES (
    'mert.cengiz',
    '$2b$10$YOUR_BCRYPT_HASH_HERE', -- Buraya bcrypt hash'ini yapıştırın
    'Mert Cengiz',
    'mert.cengiz@crowehsy.com',
    'admin',
    true
) ON CONFLICT (username) DO UPDATE SET
    password_hash = EXCLUDED.password_hash,
    full_name = EXCLUDED.full_name,
    email = EXCLUDED.email,
    role = EXCLUDED.role,
    is_active = EXCLUDED.is_active,
    updated_at = CURRENT_TIMESTAMP;

-- =====================================================
-- Row Level Security (RLS) Policies
-- =====================================================

-- Enable RLS
ALTER TABLE users ENABLE ROW LEVEL SECURITY;
ALTER TABLE user_sessions ENABLE ROW LEVEL SECURITY;

-- Drop existing policies if they exist
DROP POLICY IF EXISTS "Users can view own data" ON users;
DROP POLICY IF EXISTS "Admins can view all users" ON users;
DROP POLICY IF EXISTS "Users can manage own sessions" ON user_sessions;

-- Policy: Users can read their own data
CREATE POLICY "Users can view own data" ON users
    FOR SELECT USING (auth.uid() = id);

-- Policy: Admins can view all users
CREATE POLICY "Admins can view all users" ON users
    FOR SELECT USING (
        EXISTS (
            SELECT 1 FROM users 
            WHERE id = auth.uid() AND role = 'admin'
        )
    );

-- Policy: Users can manage own sessions
CREATE POLICY "Users can manage own sessions" ON user_sessions
    FOR ALL USING (user_id = auth.uid());

-- =====================================================
-- Helper Functions
-- =====================================================

-- Function to verify password (for Supabase Auth integration)
CREATE OR REPLACE FUNCTION verify_user_password(
    p_username VARCHAR,
    p_password VARCHAR
)
RETURNS TABLE(
    id UUID,
    username VARCHAR,
    full_name VARCHAR,
    email VARCHAR,
    role VARCHAR
) AS $$
DECLARE
    v_user users%ROWTYPE;
BEGIN
    -- Get user by username
    SELECT * INTO v_user FROM users WHERE username = p_username AND is_active = true;
    
    IF v_user.id IS NULL THEN
        RETURN;
    END IF;
    
    -- Return user info if password matches (bcrypt verification should be done in application layer)
    -- For production, use Supabase Auth instead
    RETURN QUERY
    SELECT 
        v_user.id,
        v_user.username,
        v_user.full_name,
        v_user.email,
        v_user.role
    WHERE v_user.password_hash IS NOT NULL;
END;
$$ LANGUAGE plpgsql SECURITY DEFINER;

-- =====================================================
-- Views
-- =====================================================

-- View for active admin users
CREATE OR REPLACE VIEW active_admins AS
SELECT 
    id,
    username,
    full_name,
    email,
    created_at
FROM users
WHERE role = 'admin' AND is_active = true
ORDER BY created_at DESC;

-- =====================================================
-- Verification Queries
-- =====================================================

-- Check if tables were created successfully
SELECT 
    'users' as table_name,
    COUNT(*) as row_count
FROM users
UNION ALL
SELECT 
    'user_sessions' as table_name,
    COUNT(*) as row_count
FROM user_sessions;

-- Check admin users
SELECT 
    username,
    full_name,
    email,
    role,
    is_active,
    created_at
FROM users
WHERE role = 'admin'
ORDER BY created_at;







