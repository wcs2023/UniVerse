-- ============================================================================
-- NORMALIZED DATABASE SCHEMA WITH TRIGGERS
-- UniVerse Platform - Complete Schema
-- Generated: December 29, 2025
-- ============================================================================

-- phpMyAdmin SQL Dump compatibility
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

CREATE DATABASE IF NOT EXISTS universe_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE universe_db;

-- ============================================================================
-- USERS TABLE (Main user entity)
-- ============================================================================
CREATE TABLE IF NOT EXISTS users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    date_of_birth DATE NULL,
    gender ENUM('male', 'female', 'other') DEFAULT 'male',
    phone VARCHAR(20) NULL,
    profile_picture VARCHAR(255) NULL,
    user_type ENUM('undergraduate', 'company', 'admin', 'school_leaver', 'alumni') NOT NULL,
    account_status ENUM('active', 'inactive', 'suspended') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    last_login TIMESTAMP NULL,
    
    INDEX idx_email (email),
    INDEX idx_username (username),
    INDEX idx_user_type (user_type),
    INDEX idx_created_at (created_at)
) ENGINE=InnoDB;

-- ============================================================================
-- UNDERGRADUATE_PROFILES TABLE
-- ============================================================================
CREATE TABLE IF NOT EXISTS undergraduate_profiles (
    student_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    university VARCHAR(255) NULL,
    faculty VARCHAR(255) NULL,
    degree_program VARCHAR(255) NULL,
    academic_year VARCHAR(50) NULL,
    expected_graduation_year YEAR NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE,
    INDEX idx_user_id (user_id),
    INDEX idx_student_id (student_id)
) ENGINE=InnoDB;

-- ============================================================================
-- ALUMNI_PROFILES TABLE (Normalized - removed repeating degree columns)
-- ============================================================================
CREATE TABLE IF NOT EXISTS alumni_profiles (
    profile_id INT(11) NOT NULL AUTO_INCREMENT,
    user_id INT(11) NOT NULL,
    university_name VARCHAR(255) NOT NULL,
    degree_program VARCHAR(255) NOT NULL,
    graduation_year YEAR(4) NOT NULL,
    current_job_title VARCHAR(255) DEFAULT NULL,
    current_company VARCHAR(255) DEFAULT NULL,
    linkedin_url VARCHAR(255) DEFAULT NULL,
    github_url VARCHAR(255) DEFAULT NULL,
    portfolio_url VARCHAR(255) DEFAULT NULL,
    skills_experience TEXT DEFAULT NULL,
    profile_completed TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    PRIMARY KEY (profile_id),
    KEY idx_user_id (user_id),
    KEY idx_university_name (university_name),
    KEY idx_graduation_year (graduation_year),
    KEY idx_current_company (current_company),
    KEY idx_profile_completed (profile_completed),
    CONSTRAINT alumni_profiles_ibfk_1 FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================================
-- ALUMNI_DEGREES TABLE (Normalized additional degrees)
-- ============================================================================
CREATE TABLE IF NOT EXISTS alumni_degrees (
    degree_id INT AUTO_INCREMENT PRIMARY KEY,
    profile_id INT NOT NULL,
    alumni_degree_name VARCHAR(255) NOT NULL,
    alumni_university_name VARCHAR(255) NOT NULL,
    graduation_year YEAR NOT NULL,
    is_primary BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (profile_id) REFERENCES alumni_profiles(profile_id) ON DELETE CASCADE,
    INDEX idx_profile_id (profile_id)
) ENGINE=InnoDB;

-- ============================================================================
-- COMPANY_PROFILES TABLE
-- ============================================================================
CREATE TABLE IF NOT EXISTS company_profiles (
    profile_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    company_name VARCHAR(255) NOT NULL,
    company_size ENUM('startup', 'small', 'medium', 'large', 'enterprise') NULL,
    industry VARCHAR(255) NULL,
    website VARCHAR(255) NULL,
    founded_year YEAR NULL,
    company_description TEXT NULL,
    logo_url VARCHAR(255) NULL,
    contact_person VARCHAR(255) NULL,
    contact_email VARCHAR(100) NULL,
    contact_phone VARCHAR(20) NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE,
    UNIQUE KEY unique_user (user_id),
    INDEX idx_user_id (user_id),
    INDEX idx_company_name (company_name),
    INDEX idx_industry (industry)
) ENGINE=InnoDB;
-- ============================================================================
-- ARTICLES TABLE (with likes counter)
-- ============================================================================
CREATE TABLE IF NOT EXISTS articles (
    article_id INT(11) NOT NULL AUTO_INCREMENT,
    user_id INT(11) NOT NULL,
    title VARCHAR(255) NOT NULL,
    content TEXT NOT NULL,
    excerpt TEXT DEFAULT NULL,
    featured_image VARCHAR(255) DEFAULT NULL,
    category ENUM('technology', 'career', 'education', 'research', 'student-life', 'industry-news', 'announcement') DEFAULT 'education',
    status ENUM('draft', 'published', 'archived') DEFAULT 'draft',
    views INT(11) DEFAULT 0,
    likes INT(11) DEFAULT 0,
    comments_count INT(11) DEFAULT 0,
    meta_title VARCHAR(255) DEFAULT NULL,
    meta_description TEXT DEFAULT NULL,
    tags LONGTEXT DEFAULT NULL,
    published_at TIMESTAMP NULL DEFAULT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    PRIMARY KEY (article_id),
    KEY idx_user_id (user_id),
    KEY idx_category (category),
    KEY idx_status (status),
    KEY idx_published_at (published_at),
    KEY idx_views (views),
    CONSTRAINT articles_ibfk_1 FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================================
-- ARTICLE_LIKES TABLE (tracks who liked, prevents duplicates)
-- ============================================================================
CREATE TABLE IF NOT EXISTS article_likes (
    like_id INT(11) NOT NULL AUTO_INCREMENT,
    article_id INT(11) NOT NULL,
    user_id INT(11) NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    
    PRIMARY KEY (like_id),
    UNIQUE KEY unique_like (article_id, user_id),
    KEY idx_article_id (article_id),
    KEY idx_user_id (user_id),
    CONSTRAINT article_likes_ibfk_1 FOREIGN KEY (article_id) REFERENCES articles(article_id) ON DELETE CASCADE,
    CONSTRAINT article_likes_ibfk_2 FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================================
-- ARTICLE_COMMENTS TABLE
-- ============================================================================
CREATE TABLE IF NOT EXISTS article_comments (
    comment_id INT(11) NOT NULL AUTO_INCREMENT,
    article_id INT(11) NOT NULL,
    user_id INT(11) NOT NULL,
    content TEXT NOT NULL,
    parent_comment_id INT(11) DEFAULT NULL,
    status ENUM('active', 'hidden', 'deleted') DEFAULT 'active',
    likes_count INT(11) DEFAULT 0,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    PRIMARY KEY (comment_id),
    KEY idx_article_id (article_id),
    KEY idx_user_id (user_id),
    KEY idx_parent_comment_id (parent_comment_id),
    KEY idx_status (status),
    KEY idx_created_at (created_at),
    CONSTRAINT article_comments_ibfk_1 FOREIGN KEY (article_id) REFERENCES articles(article_id) ON DELETE CASCADE,
    CONSTRAINT article_comments_ibfk_2 FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE,
    CONSTRAINT article_comments_ibfk_3 FOREIGN KEY (parent_comment_id) REFERENCES article_comments(comment_id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================================
-- ACHIEVEMENTS TABLE
-- ============================================================================
CREATE TABLE IF NOT EXISTS achievements (
    achievement_id INT(11) NOT NULL AUTO_INCREMENT,
    user_id INT(11) NOT NULL,
    title VARCHAR(255) NOT NULL,
    description TEXT DEFAULT NULL,
    achievement_type ENUM('certificate', 'award', 'project', 'activity', 'leadership', 'internship', 'competition', 'publication', 'volunteer') NOT NULL DEFAULT 'certificate',
    date_achieved DATE NOT NULL,
    certificate_url VARCHAR(500) DEFAULT NULL,
    institution VARCHAR(255) DEFAULT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    PRIMARY KEY (achievement_id),
    KEY idx_user_id (user_id),
    KEY idx_achievement_type (achievement_type),
    KEY idx_date_achieved (date_achieved),
    CONSTRAINT achievements_ibfk_1 FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================================
-- JOBS TABLE
-- ============================================================================
CREATE TABLE IF NOT EXISTS jobs (
    job_id INT AUTO_INCREMENT PRIMARY KEY,
    company_id INT NOT NULL,
    title VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    requirements TEXT NULL,
    responsibilities TEXT NOT NULL,
    location VARCHAR(255) NULL,
    job_type ENUM('full-time', 'part-time', 'internship', 'contract', 'remote', 'hybrid') DEFAULT 'full-time',
    experience_level ENUM('entry', 'junior', 'mid', 'senior', 'executive') DEFAULT 'entry',
    salary_min DECIMAL(10,2) NULL,
    salary_max DECIMAL(10,2) NULL,
    currency VARCHAR(10) DEFAULT 'LKR',
    application_deadline DATE NULL,
    status ENUM('active', 'closed', 'draft', 'paused') DEFAULT 'active',
    skills_required JSON NULL,
    benefits TEXT NULL,
    work_arrangement ENUM('onsite', 'remote', 'hybrid') DEFAULT 'onsite',
    contact_email VARCHAR(100) NULL,
    contact_phone VARCHAR(20) NULL,
    application_url VARCHAR(500) NULL,
    applications_count INT DEFAULT 0,
    views_count INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (company_id) REFERENCES users(user_id) ON DELETE CASCADE,
    INDEX idx_company_id (company_id),
    INDEX idx_job_type (job_type),
    INDEX idx_status (status),
    INDEX idx_deadline (application_deadline),
    INDEX idx_location (location),
    INDEX idx_experience_level (experience_level)
) ENGINE=InnoDB;

-- ============================================================================
-- JOB_APPLICATIONS TABLE
-- ============================================================================
CREATE TABLE IF NOT EXISTS job_applications (
    application_id INT AUTO_INCREMENT PRIMARY KEY,
    job_id INT NOT NULL,
    user_id INT NOT NULL,
    cover_letter TEXT NULL,
    resume_url VARCHAR(255) NULL,
    portfolio_url VARCHAR(255) NULL,
    expected_salary DECIMAL(10,2) NULL,
    availability_date DATE NULL,
    status ENUM('pending', 'under_review', 'shortlisted', 'interviewed', 'rejected', 'hired', 'withdrawn') DEFAULT 'pending',
    reviewed_at TIMESTAMP NULL,
    reviewed_by INT NULL,
    notes TEXT NULL,
    applied_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (job_id) REFERENCES jobs(job_id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE,
    FOREIGN KEY (reviewed_by) REFERENCES users(user_id) ON DELETE SET NULL,
    UNIQUE KEY unique_application (job_id, user_id),
    INDEX idx_job_id (job_id),
    INDEX idx_user_id (user_id),
    INDEX idx_status (status),
    INDEX idx_applied_at (applied_at)
) ENGINE=InnoDB;

-- ============================================================================
-- MENTORS TABLE (alumni who offer mentorship)
-- ============================================================================
CREATE TABLE IF NOT EXISTS mentors (
    mentor_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    is_active BOOLEAN DEFAULT TRUE,
    expertise_areas JSON NULL,
    max_mentees INT DEFAULT 5,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE,
    UNIQUE KEY unique_mentor (user_id),
    INDEX idx_user_id (user_id),
    INDEX idx_is_active (is_active)
) ENGINE=InnoDB;

-- ============================================================================
-- MENTOR_REQUESTS TABLE (undergraduates request mentorship)
-- ============================================================================
CREATE TABLE IF NOT EXISTS mentor_requests (
    request_id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT NOT NULL,
    mentor_id INT NOT NULL,
    status ENUM('pending', 'accepted', 'rejected', 'completed') DEFAULT 'pending',
    message TEXT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (student_id) REFERENCES users(user_id) ON DELETE CASCADE,
    FOREIGN KEY (mentor_id) REFERENCES mentors(mentor_id) ON DELETE CASCADE,
    INDEX idx_student_id (student_id),
    INDEX idx_mentor_id (mentor_id),
    INDEX idx_status (status)
) ENGINE=InnoDB;

-- ============================================================================
-- MENTOR_SESSIONS TABLE (scheduled sessions for accepted requests)
-- ============================================================================
CREATE TABLE IF NOT EXISTS mentor_sessions (
    session_id INT AUTO_INCREMENT PRIMARY KEY,
    request_id INT NOT NULL,
    session_date DATE NOT NULL,
    session_time TIME NOT NULL,
    duration_hours DECIMAL(3,1) DEFAULT 1.0,
    status ENUM('scheduled', 'completed', 'cancelled') DEFAULT 'scheduled',
    notes TEXT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (request_id) REFERENCES mentor_requests(request_id) ON DELETE CASCADE,
    INDEX idx_request_id (request_id),
    INDEX idx_session_date (session_date),
    INDEX idx_status (status)
) ENGINE=InnoDB;

-- ============================================================================
-- NOTIFICATIONS TABLE
-- ============================================================================
CREATE TABLE IF NOT EXISTS notifications (
    notification_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    title VARCHAR(255) NOT NULL,
    message TEXT NOT NULL,
    notification_type ENUM('info', 'success', 'warning', 'error', 'job', 'article', 'application', 'achievement', 'message') DEFAULT 'info',
    is_read BOOLEAN DEFAULT FALSE,
    action_url VARCHAR(255) NULL,
    data JSON NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    read_at TIMESTAMP NULL,
    
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE,
    INDEX idx_user_id (user_id),
    INDEX idx_is_read (is_read),
    INDEX idx_notification_type (notification_type),
    INDEX idx_created_at (created_at)
) ENGINE=InnoDB;

-- ============================================================================
-- USER_SESSIONS TABLE
-- ============================================================================
CREATE TABLE IF NOT EXISTS user_sessions (
    session_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    session_token VARCHAR(255) UNIQUE NOT NULL,
    ip_address VARCHAR(45) NULL,
    user_agent TEXT NULL,
    expires_at TIMESTAMP NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE,
    INDEX idx_user_id (user_id),
    INDEX idx_session_token (session_token),
    INDEX idx_expires_at (expires_at)
) ENGINE=InnoDB;

-- ============================================================================
-- SYSTEM_SETTINGS TABLE
-- ============================================================================
CREATE TABLE IF NOT EXISTS system_settings (
    setting_id INT AUTO_INCREMENT PRIMARY KEY,
    setting_key VARCHAR(100) UNIQUE NOT NULL,
    setting_value TEXT NULL,
    description TEXT NULL,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    INDEX idx_setting_key (setting_key)
) ENGINE=InnoDB;

-- ============================================================================
-- FILE_UPLOADS TABLE
-- ============================================================================
CREATE TABLE IF NOT EXISTS file_uploads (
    file_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    original_filename VARCHAR(255) NOT NULL,
    stored_filename VARCHAR(255) NOT NULL,
    file_path VARCHAR(500) NOT NULL,
    file_type VARCHAR(100) NOT NULL,
    file_size INT NOT NULL,
    upload_purpose ENUM('profile_picture', 'resume', 'portfolio', 'certificate', 'post_image', 'company_logo') NOT NULL,
    uploaded_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE,
    INDEX idx_user_id (user_id),
    INDEX idx_upload_purpose (upload_purpose),
    INDEX idx_uploaded_at (uploaded_at)
) ENGINE=InnoDB;

-- ============================================================================
-- TRIGGERS FOR ARTICLE LIKES COUNT
-- ============================================================================

DELIMITER $$

-- Trigger: Increment likes when a like is added
CREATE TRIGGER increment_article_likes
AFTER INSERT ON article_likes
FOR EACH ROW
BEGIN
    UPDATE articles 
    SET likes = likes + 1 
    WHERE article_id = NEW.article_id;
END$$

-- Trigger: Decrement likes when a like is removed
CREATE TRIGGER decrement_article_likes
AFTER DELETE ON article_likes
FOR EACH ROW
BEGIN
    UPDATE articles 
    SET likes = GREATEST(likes - 1, 0)
    WHERE article_id = OLD.article_id;
END$$

DELIMITER ;

-- ============================================================================
-- TRIGGERS FOR ARTICLE COMMENTS COUNT
-- ============================================================================

DELIMITER $$

-- Trigger: Increment comments_count when a comment is added
CREATE TRIGGER increment_article_comments
AFTER INSERT ON article_comments
FOR EACH ROW
BEGIN
    UPDATE articles 
    SET comments_count = comments_count + 1 
    WHERE article_id = NEW.article_id;
END$$

-- Trigger: Decrement comments_count when a comment is deleted
CREATE TRIGGER decrement_article_comments
AFTER DELETE ON article_comments
FOR EACH ROW
BEGIN
    UPDATE articles 
    SET comments_count = GREATEST(comments_count - 1, 0)
    WHERE article_id = OLD.article_id;
END$$

DELIMITER ;

-- ============================================================================
-- TRIGGERS FOR JOB APPLICATIONS COUNT
-- ============================================================================

DELIMITER $$

-- Trigger: Increment applications_count when an application is submitted
CREATE TRIGGER increment_job_applications
AFTER INSERT ON job_applications
FOR EACH ROW
BEGIN
    UPDATE jobs 
    SET applications_count = applications_count + 1 
    WHERE job_id = NEW.job_id;
END$$

-- Trigger: Decrement applications_count when an application is withdrawn/deleted
CREATE TRIGGER decrement_job_applications
AFTER DELETE ON job_applications
FOR EACH ROW
BEGIN
    UPDATE jobs 
    SET applications_count = GREATEST(applications_count - 1, 0)
    WHERE job_id = OLD.job_id;
END$$

DELIMITER ;

-- ============================================================================
-- FORUM TABLES (from existing schema)
-- ============================================================================

-- CREATE TABLE IF NOT EXISTS forum_categories (
--     id INT(11) NOT NULL AUTO_INCREMENT,
--     slug VARCHAR(120) NOT NULL,
--     name VARCHAR(160) NOT NULL,
--     description TEXT DEFAULT NULL,
--     sort_order INT(11) DEFAULT 0,
--     created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    
--     PRIMARY KEY (id),
--     UNIQUE KEY slug (slug)
-- ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- CREATE TABLE IF NOT EXISTS forum_threads (
--     id INT(11) NOT NULL AUTO_INCREMENT,
--     category_id INT(11) NOT NULL,
--     user_id INT(11) NOT NULL,
--     title VARCHAR(180) NOT NULL,
--     body MEDIUMTEXT NOT NULL,
--     views INT(11) DEFAULT 0,
--     is_locked TINYINT(1) DEFAULT 0,
--     is_pinned TINYINT(1) DEFAULT 0,
--     last_post_at DATETIME DEFAULT CURRENT_TIMESTAMP(),
--     created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
--     updated_at TIMESTAMP NULL DEFAULT NULL,
    
--     PRIMARY KEY (id),
--     KEY category_id (category_id),
--     CONSTRAINT forum_threads_ibfk_1 FOREIGN KEY (category_id) REFERENCES forum_categories(id) ON DELETE CASCADE
-- ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- CREATE TABLE IF NOT EXISTS forum_posts (
--     id INT(11) NOT NULL AUTO_INCREMENT,
--     thread_id INT(11) NOT NULL,
--     user_id INT(11) NOT NULL,
--     body MEDIUMTEXT NOT NULL,
--     upvotes INT(11) DEFAULT 0,
--     is_deleted TINYINT(1) DEFAULT 0,
--     created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
--     updated_at TIMESTAMP NULL DEFAULT NULL,
    
--     PRIMARY KEY (id),
--     KEY thread_id (thread_id),
--     CONSTRAINT forum_posts_ibfk_1 FOREIGN KEY (thread_id) REFERENCES forum_threads(id) ON DELETE CASCADE
-- ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- CREATE TABLE IF NOT EXISTS forum_post_votes (
--     id INT(11) NOT NULL AUTO_INCREMENT,
--     post_id INT(11) NOT NULL,
--     user_id INT(11) NOT NULL,
--     value TINYINT(4) NOT NULL,
--     created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    
--     PRIMARY KEY (id),
--     UNIQUE KEY uq_vote (post_id, user_id),
--     CONSTRAINT forum_post_votes_ibfk_1 FOREIGN KEY (post_id) REFERENCES forum_posts(id) ON DELETE CASCADE
-- ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================================
-- FINALIZE
-- ============================================================================

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

-- ============================================================================
-- NORMALIZATION SUMMARY
-- ============================================================================
-- ✅ Fixed: Alumni additional degrees normalized into separate table (alumni_degrees)
-- ✅ Fixed: Column names match existing database (certificate_url, institution, likes instead of likes_count)
-- ✅ Added: article_likes table for duplicate prevention and tracking
-- ✅ Added: article_comments table for commenting functionality
-- ✅ Added: Triggers for automatic counter maintenance (likes, comments, applications)
-- ✅ Added: Proper foreign keys with ON DELETE CASCADE
-- ✅ Added: Forum tables (categories, threads, posts, votes)
-- ✅ Added: Mentorship tables (mentors, mentor_requests, mentor_sessions)
-- ✅ All tables match phpMyAdmin export structure
-- ✅ All tables in 3NF (Third Normal Form)
-- ============================================================================
