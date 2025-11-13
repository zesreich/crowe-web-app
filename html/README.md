# Crowe HSY - Financial Advisor Management Platform

## Overview
This is a multi-language financial advisor management platform with authentication, dark/light theme support, and admin access control.

## Features
- ✅ Multi-language support (English/Turkish) - Default: English
- ✅ User authentication system
- ✅ Dark/Light theme switcher (on all pages except login)
- ✅ Admin access control
- ✅ Responsive design
- ✅ Galaxy animation on login page

## Admin Users

The following users have admin access to all pages:

1. **Eda Meric Sefer**
   - Username: `eda.meric`
   - Password: `Frankfurt2025!`

2. **Ozkan Cengiz**
   - Username: `ozkan.cengiz`
   - Password: `Munich2025!`

3. **Mehmet Ali Sariad**
   - Username: `mehmet.sariad`
   - Password: `Nuremberg2025!`

4. **Mert Cengiz**
   - Username: `mert.cengiz`
   - Password: `Berlin2025!`

## Database Setup (Supabase)

### 1. Create Supabase Project
1. Go to [Supabase](https://supabase.com)
2. Create a new project
3. Note your project URL and anon key

### 2. Run SQL Schema
1. Open the SQL Editor in Supabase Dashboard
2. Copy and paste the contents of `supabase-schema.sql`
3. Execute the SQL script

### 3. Update Password Hashes
The password hashes in the SQL file are placeholders. You need to generate actual bcrypt hashes:

**Option 1: Using Node.js**
```javascript
const bcrypt = require('bcrypt');
const password = 'Frankfurt2025!';
const hash = bcrypt.hashSync(password, 10);
console.log(hash);
```

**Option 2: Using Online Tool**
- Use a bcrypt hash generator online
- Generate hashes for all 4 passwords
- Update the SQL file with actual hashes

### 4. Insert Users
After updating the password hashes, run the INSERT statements in the SQL file to create the admin users.

## File Structure

```
html/
├── index.html              # Main entry point
├── login.html              # Login page (no dark mode)
├── dashboard.html          # Dashboard (with dark mode)
├── client-list.html        # Client list (with dark mode)
├── client-detail.html      # Client detail (with dark mode)
├── i18n.js                # Internationalization system
├── auth.js                 # Authentication system
├── darkmode.js             # Dark mode toggle
├── darkmode.css            # Dark mode styles
├── supabase-schema.sql     # Database schema
└── README.md              # This file
```

## Usage

### Starting the Application
1. Open `index.html` in a web browser
2. Click on "Login Page" button
3. Enter one of the admin credentials
4. You'll be redirected to the dashboard

### Language Switching
- Click the language switcher button (🌐) in the top-right corner
- Default language: English
- Can switch to Turkish

### Dark Mode
- Click the theme switcher button (🌙/☀️) in the top-right corner
- Available on all pages except login
- Preference is saved in localStorage

### Logout
- Click on your name in the top-right corner
- Select "Logout" from the dropdown menu

## Authentication Flow

1. User enters username and password on login page
2. System checks credentials against admin users
3. If valid, user is stored in localStorage
4. User is redirected to dashboard
5. All protected pages check authentication on load
6. If not authenticated, user is redirected to login

## Protected Pages

The following pages require authentication:
- `dashboard.html`
- `client-list.html`
- `client-detail.html`

## Integration with Supabase (Future)

To integrate with Supabase:
1. Update `auth.js` with your Supabase credentials
2. Replace the local authentication with Supabase Auth
3. Use Supabase client for database operations

Example:
```javascript
import { createClient } from '@supabase/supabase-js'

const supabaseUrl = 'YOUR_SUPABASE_URL'
const supabaseKey = 'YOUR_SUPABASE_ANON_KEY'
const supabase = createClient(supabaseUrl, supabaseKey)
```

## Notes

- Passwords are currently stored in plain text in `auth.js` for development
- In production, use Supabase Auth with proper password hashing
- Dark mode preference is stored in localStorage
- Language preference is stored in localStorage
- Authentication state is stored in localStorage

## Browser Compatibility

- Chrome (recommended)
- Firefox
- Safari
- Edge

## License

Internal use only - Crowe HSY







