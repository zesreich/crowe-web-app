// Password Hash Generator for Supabase
// Run this script with Node.js to generate bcrypt hashes for passwords
// Usage: node generate-password-hashes.js

const bcrypt = require('bcrypt');

// Admin users and their passwords
const users = [
    {
        username: 'eda.meric',
        password: 'Frankfurt2025!',
        fullName: 'Eda Meric Sefer',
        email: 'eda.meric@crowehsy.com'
    },
    {
        username: 'ozkan.cengiz',
        password: 'Munich2025!',
        fullName: 'Ozkan Cengiz',
        email: 'ozkan.cengiz@crowehsy.com'
    },
    {
        username: 'mehmet.sariad',
        password: 'Nuremberg2025!',
        fullName: 'Mehmet Ali Sariad',
        email: 'mehmet.sariad@crowehsy.com'
    },
    {
        username: 'mert.cengiz',
        password: 'Berlin2025!',
        fullName: 'Mert Cengiz',
        email: 'mert.cengiz@crowehsy.com'
    }
];

console.log('Generating bcrypt hashes for admin users...\n');

users.forEach((user, index) => {
    const saltRounds = 10;
    const hash = bcrypt.hashSync(user.password, saltRounds);
    
    console.log(`User ${index + 1}: ${user.fullName}`);
    console.log(`Username: ${user.username}`);
    console.log(`Password: ${user.password}`);
    console.log(`Hash: ${hash}`);
    console.log(`Email: ${user.email}`);
    console.log('---\n');
    
    // Generate SQL INSERT statement
    const sqlInsert = `INSERT INTO users (username, password_hash, full_name, email, role, is_active) 
VALUES (
    '${user.username}',
    '${hash}',
    '${user.fullName}',
    '${user.email}',
    'admin',
    true
) ON CONFLICT (username) DO NOTHING;`;
    
    console.log('SQL INSERT:');
    console.log(sqlInsert);
    console.log('\n');
});

console.log('\n✅ All password hashes generated successfully!');
console.log('Copy the SQL INSERT statements above and run them in Supabase SQL Editor.');







