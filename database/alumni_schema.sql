-- Alumni Database Schema Updates
-- This file adds alumni support to the existing UniVerse database

USE universe_db;

-- ============================================================================
-- UPDATE USERS TABLE - Add alumni to user_type enum
-- ============================================================================
ALTER TABLE users MODIFY COLUMN user_type ENUM('undergraduate', 'company', 'admin', 'school_leaver', 'alumni') NOT NULL;

-- ============================================================================
-- ALUMNI_PROFILES TABLE (Specific to alumni users)
-- ============================================================================
CREATE TABLE IF NOT EXISTS alumni_profiles (
    profile_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    
    -- Primary Education Details (Required at registration)
    university_name VARCHAR(255) NOT NULL,
    degree_program VARCHAR(255) NOT NULL,
    graduation_year YEAR NOT NULL,
    
    -- Optional Additional Degrees
    additional_degree_1 VARCHAR(255) NULL,
    additional_university_1 VARCHAR(255) NULL,
    additional_grad_year_1 YEAR NULL,
    
    additional_degree_2 VARCHAR(255) NULL,
    additional_university_2 VARCHAR(255) NULL,
    additional_grad_year_2 YEAR NULL,
    
    -- Professional Information (to be completed after login)
    current_job_title VARCHAR(255) NULL,
    current_company VARCHAR(255) NULL,
    linkedin_url VARCHAR(255) NULL,
    github_url VARCHAR(255) NULL,
    portfolio_url VARCHAR(255) NULL,
    
    -- Skills and Experience
    skills_experience TEXT NULL,
    
    -- Profile completion status
    profile_completed BOOLEAN DEFAULT FALSE,
    
    -- Timestamps
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    -- Foreign Keys
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE,
    
    -- Indexes
    INDEX idx_user_id (user_id),
    INDEX idx_university_name (university_name),
    INDEX idx_graduation_year (graduation_year),
    INDEX idx_current_company (current_company),
    INDEX idx_profile_completed (profile_completed)
);

-- ============================================================================
-- ALUMNI_ACHIEVEMENTS TABLE (Optional: Track professional achievements)
-- ============================================================================
CREATE TABLE IF NOT EXISTS alumni_achievements (
    achievement_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    title VARCHAR(255) NOT NULL,
    description TEXT NULL,
    achievement_date DATE NULL,
    organization VARCHAR(255) NULL,
    achievement_type ENUM('award', 'certification', 'promotion', 'project', 'publication', 'other') DEFAULT 'other',
    
    -- Timestamps
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    -- Foreign Keys
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE,
    
    -- Indexes
    INDEX idx_user_id (user_id),
    INDEX idx_achievement_type (achievement_type),
    INDEX idx_achievement_date (achievement_date)
);
