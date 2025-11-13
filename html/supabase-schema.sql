-- Supabase Database Schema for Crowe HSY
-- Create users table for authentication

-- Enable UUID extension
CREATE EXTENSION IF NOT EXISTS "uuid-ossp";

-- Users table
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

-- Create index on username for faster lookups
CREATE INDEX IF NOT EXISTS idx_users_username ON users(username);
CREATE INDEX IF NOT EXISTS idx_users_email ON users(email);

-- Insert admin users
-- Note: Passwords should be hashed using bcrypt before inserting
-- Password hash format: $2b$10$... (bcrypt hash)
-- For production, use proper password hashing. Here we'll use bcrypt with cost factor 10

-- Eda Meric Sefer - Password: Frankfurt2025!
INSERT INTO users (username, password_hash, full_name, email, role, is_active) 
VALUES (
    'eda.meric',
    '$2b$10$rK9Q8XJ7Z5YxVnM2PqL3.eO8K9J7Z5YxVnM2PqL3.eO8K9J7Z5YxV', -- Placeholder - replace with actual bcrypt hash
    'Eda Meric Sefer',
    'eda.meric@crowehsy.com',
    'admin',
    true
) ON CONFLICT (username) DO NOTHING;

-- Ozkan Cengiz - Password: Munich2025!
INSERT INTO users (username, password_hash, full_name, email, role, is_active) 
VALUES (
    'ozkan.cengiz',
    '$2b$10$rK9Q8XJ7Z5YxVnM2PqL3.eO8K9J7Z5YxVnM2PqL3.eO8K9J7Z5YxV', -- Placeholder - replace with actual bcrypt hash
    'Ozkan Cengiz',
    'ozkan.cengiz@crowehsy.com',
    'admin',
    true
) ON CONFLICT (username) DO NOTHING;

-- Mehmet Ali Sariad - Password: Nuremberg2025!
INSERT INTO users (username, password_hash, full_name, email, role, is_active) 
VALUES (
    'mehmet.sariad',
    '$2b$10$rK9Q8XJ7Z5YxVnM2PqL3.eO8K9J7Z5YxVnM2PqL3.eO8K9J7Z5YxV', -- Placeholder - replace with actual bcrypt hash
    'Mehmet Ali Sariad',
    'mehmet.sariad@crowehsy.com',
    'admin',
    true
) ON CONFLICT (username) DO NOTHING;

-- Mert Cengiz - Password: Berlin2025!
INSERT INTO users (username, password_hash, full_name, email, role, is_active) 
VALUES (
    'mert.cengiz',
    '$2b$10$rK9Q8XJ7Z5YxVnM2PqL3.eO8K9J7Z5YxVnM2PqL3.eO8K9J7Z5YxV', -- Placeholder - replace with actual bcrypt hash
    'Mert Cengiz',
    'mert.cengiz@crowehsy.com',
    'admin',
    true
) ON CONFLICT (username) DO NOTHING;

-- Create function to update updated_at timestamp
CREATE OR REPLACE FUNCTION update_updated_at_column()
RETURNS TRIGGER AS $$
BEGIN
    NEW.updated_at = CURRENT_TIMESTAMP;
    RETURN NEW;
END;
$$ language 'plpgsql';

-- Create trigger to automatically update updated_at
CREATE TRIGGER update_users_updated_at BEFORE UPDATE ON users
    FOR EACH ROW EXECUTE FUNCTION update_updated_at_column();

-- Create sessions table for tracking user sessions
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

-- Enable Row Level Security (RLS)
ALTER TABLE users ENABLE ROW LEVEL SECURITY;
ALTER TABLE user_sessions ENABLE ROW LEVEL SECURITY;

-- Create policies for users table
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

-- Create policies for user_sessions table
CREATE POLICY "Users can manage own sessions" ON user_sessions
    FOR ALL USING (user_id = auth.uid());







