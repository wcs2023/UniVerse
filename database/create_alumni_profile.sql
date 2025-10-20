-- Script to create Alumni profile for existing users
-- Run this after replacing the placeholders with actual values

-- STEP 1: Check if user exists
SELECT user_id, email, full_name, user_role 
FROM Users 
WHERE user_role = 'alumni' 
  AND user_id NOT IN (SELECT user_id FROM Alumni);

-- This will show you all alumni users who don't have an Alumni profile yet

-- STEP 2: Insert Alumni profile for a specific user
-- Replace the values below with actual data

-- Example for user_id = 1 (replace with your actual user_id):
INSERT INTO Alumni (
    user_id, 
    first_name, 
    last_name, 
    title, 
    company, 
    bio, 
    mentorship_status
) VALUES (
    1,                          -- Replace with actual user_id
    'John',                     -- Replace with actual first name
    'Doe',                      -- Replace with actual last name
    'Software Engineer',        -- Replace with job title
    'Tech Company Inc',         -- Replace with company name
    'Passionate software engineer with 5 years of experience in web development. Happy to help students navigate their career path.',  -- Bio
    'available'                 -- Set to 'available' for mentorship or 'unavailable'
);

-- STEP 3: Verify the insertion
SELECT a.alumni_id, a.user_id, a.first_name, a.last_name, a.title, a.company, a.mentorship_status
FROM Alumni a
JOIN Users u ON a.user_id = u.user_id
WHERE u.user_role = 'alumni';

-- STEP 4: Add some experience (optional)
-- Replace alumni_id with the one from Step 3
INSERT INTO AlumniExperience (
    alumni_id,
    title,
    company,
    start_date,
    end_date,
    description
) VALUES (
    1,                          -- Replace with actual alumni_id from Step 3
    'Senior Developer',         -- Job title
    'Tech Company',             -- Company name
    '2020-01-01',              -- Start date
    NULL,                       -- NULL for current job, or '2023-12-31' for end date
    'Led development of web applications'  -- Description
);

-- STEP 5: Add some skills (optional)
-- Replace alumni_id with the one from Step 3
INSERT INTO AlumniSkills (alumni_id, skill_name) VALUES
(1, 'JavaScript'),             -- Replace alumni_id
(1, 'Python'),
(1, 'React'),
(1, 'Node.js'),
(1, 'Database Design');

-- COMPLETE EXAMPLE: Insert alumni profile in one go
-- This creates a complete profile for user_id = 2

-- First, insert basic alumni info
INSERT INTO Alumni (user_id, first_name, last_name, title, company, bio, mentorship_status)
SELECT 
    u.user_id,
    'Jane',                     -- First name
    'Smith',                    -- Last name  
    'Senior Data Scientist',    -- Title
    'Data Analytics Co',        -- Company
    'Data scientist with expertise in machine learning and analytics. Love mentoring aspiring data professionals.',
    'available'
FROM Users u
WHERE u.user_id = 2             -- Replace with your user_id
  AND u.user_role = 'alumni'
  AND NOT EXISTS (SELECT 1 FROM Alumni WHERE user_id = u.user_id);

-- Get the alumni_id that was just created
SET @last_alumni_id = LAST_INSERT_ID();

-- Add experience
INSERT INTO AlumniExperience (alumni_id, title, company, start_date, end_date, description)
VALUES (@last_alumni_id, 'Data Scientist', 'Data Analytics Co', '2019-01-01', NULL, 'Leading data science initiatives');

-- Add skills
INSERT INTO AlumniSkills (alumni_id, skill_name) VALUES
(@last_alumni_id, 'Python'),
(@last_alumni_id, 'Machine Learning'),
(@last_alumni_id, 'SQL'),
(@last_alumni_id, 'Data Visualization');

-- Verification query - Check everything was created correctly
SELECT 
    u.user_id,
    u.email,
    a.alumni_id,
    a.first_name,
    a.last_name,
    a.title,
    a.company,
    a.mentorship_status,
    (SELECT COUNT(*) FROM AlumniExperience WHERE alumni_id = a.alumni_id) as experience_count,
    (SELECT COUNT(*) FROM AlumniSkills WHERE alumni_id = a.alumni_id) as skills_count
FROM Users u
LEFT JOIN Alumni a ON u.user_id = a.user_id
WHERE u.user_role = 'alumni'
ORDER BY u.user_id;
