-- Compatibility patches for existing tables

-- Add missing columns to Users table if not exists
ALTER TABLE Users 
ADD COLUMN IF NOT EXISTS full_name VARCHAR(255) NULL AFTER email,
ADD COLUMN IF NOT EXISTS is_active BOOLEAN DEFAULT 1 AFTER role;

-- Add missing columns to Alumni table if needed
ALTER TABLE Alumni
ADD COLUMN IF NOT EXISTS current_role VARCHAR(255) NULL AFTER company,
ADD COLUMN IF NOT EXISTS linkedin_url VARCHAR(500) NULL AFTER current_role,
ADD COLUMN IF NOT EXISTS short_bio TEXT NULL AFTER linkedin_url,
ADD COLUMN IF NOT EXISTS available_for_mentorship BOOLEAN DEFAULT 0 AFTER short_bio;

-- Update existing Users to have full_name from first/last name if applicable
UPDATE Users u
LEFT JOIN Alumni a ON u.user_id = a.user_id
SET u.full_name = CONCAT(COALESCE(a.first_name, ''), ' ', COALESCE(a.last_name, ''))
WHERE u.full_name IS NULL AND a.first_name IS NOT NULL;

UPDATE Users u
LEFT JOIN Undergraduates ug ON u.user_id = ug.user_id
SET u.full_name = CONCAT(COALESCE(ug.first_name, ''), ' ', COALESCE(ug.last_name, ''))
WHERE u.full_name IS NULL AND ug.first_name IS NOT NULL;
