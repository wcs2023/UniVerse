-- SQL script to create an admin user
-- Run this in phpMyAdmin or MySQL command line
USE universe_db;
-- First, check if the admin exists
SELECT * FROM Users WHERE email = 'admin@universe.edu' OR user_type = 'admin';

-- If not exists, insert a new admin user
INSERT INTO Users (
    username,
    email,
    password_hash,
    first_name,
    last_name,
    date_of_birth,
    gender,
    phone,
    profile_picture,
    user_type,
    account_status,
    created_at,
    updated_at
) VALUES (
    'admin',                                                   -- Username
    'admin@universe.edu',                                      -- Email
    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',  -- Password: "password" (change this!)
    'Admin',                                                   -- First name
    'User',                                                    -- Last name
    NULL,                                                      -- date_of_birth (NULL by default)
    NULL,                                                      -- gender (NULL by default)
    '0771234567',                                              -- Phone (optional)
    NULL,                                                      -- profile_picture (NULL by default)
    'admin',                                                   -- User type (admin/alumni/undergraduate/company)
    'active',                                                  -- Account status
    NOW(),                                                     -- Created at
    NOW()                                                      -- Updated at
);

-- Verify the admin was created
SELECT user_id, username, email, user_type, first_name, last_name, account_status 
FROM Users 
WHERE user_type = 'admin';

-- IMPORTANT: After logging in, change the password immediately!
