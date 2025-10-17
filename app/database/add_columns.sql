-- Add columns to Alumni table with proper escaping
ALTER TABLE Alumni ADD COLUMN linkedin_url VARCHAR(500) NULL;
ALTER TABLE Alumni ADD COLUMN short_bio TEXT NULL;
ALTER TABLE Alumni ADD COLUMN available_for_mentorship BOOLEAN DEFAULT 0;

-- Add columns to Users table
ALTER TABLE Users ADD COLUMN full_name VARCHAR(255) NULL;
ALTER TABLE Users ADD COLUMN is_active BOOLEAN DEFAULT 1;
