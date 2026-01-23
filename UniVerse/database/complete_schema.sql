-- UniVerse Database Complete Schema
-- This file creates all necessary tables for the UniVerse application
-- Based on ER Diagram specifications

-- Create database if it doesn't exist
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
    middle_name VARCHAR(50) NULL,
    last_name VARCHAR(50) NOT NULL,
    date_of_birth DATE NULL,
    gender ENUM('male', 'female', 'other') DEFAULT 'male',
    phone VARCHAR(20) NULL,
    profile_picture VARCHAR(255) NULL,
    bio TEXT NULL,
    
    -- Address Information
    address_line1 VARCHAR(255) NULL,
    address_line2 VARCHAR(255) NULL,
    city VARCHAR(100) NULL,
    province VARCHAR(100) NULL,
    postal_code VARCHAR(20) NULL,
    -- country VARCHAR(100) DEFAULT 'Sri Lanka',
    
    -- User Type and Status
    user_type ENUM('undergraduate', 'company', 'admin', 'school_leaver', 'alumni') NOT NULL,
    account_status ENUM('active', 'inactive', 'suspended') DEFAULT 'active',
    email_verified BOOLEAN DEFAULT FALSE,
    
    -- Timestamps
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    last_login TIMESTAMP NULL,
    
    -- Indexes
    INDEX idx_email (email),
    INDEX idx_username (username),
    INDEX idx_user_type (user_type),
    INDEX idx_created_at (created_at)
);#created 

-- ============================================================================
-- UNDERGRADUATE_PROFILES TABLE (Specific to undergraduate users)
-- ============================================================================
CREATE TABLE IF NOT EXISTS undergraduate_profiles (
    student_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    -- student_id VARCHAR(50) UNIQUE NULL,
    university VARCHAR(255) NULL,
    faculty VARCHAR(255) NULL,
    degree_program VARCHAR(255) NULL,
    academic_year VARCHAR(50) NULL,
    expected_graduation_year YEAR NULL,
    skills TEXT NULL, -- why skills?
    interests TEXT NULL,
    
    -- Timestamps
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    -- Foreign Keys
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE,
    
    -- Indexes
    # INDEX idx_user_id (user_id), 
    -- INDEX idx_university (university),
    INDEX idx_student_id (student_id)
);
-- #created

-- ============================================================================
-- COMPANY_PROFILES TABLE (Specific to company users)
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
    
    -- Contact Information
    contact_person VARCHAR(255) NULL,
    contact_email VARCHAR(100) NULL,
    contact_phone VARCHAR(20) NULL,
    
    -- Verification
    is_verified BOOLEAN DEFAULT FALSE,
    verification_date TIMESTAMP NULL,
    
    -- Timestamps
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    -- Foreign Keys
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE,
    
    -- Indexes
    INDEX idx_user_id (user_id),
    INDEX idx_company_name (company_name),
    INDEX idx_industry (industry),
    INDEX idx_verified (is_verified)
);

-- ============================================================================
-- POSTS/ARTICLES TABLE
-- ============================================================================
CREATE TABLE IF NOT EXISTS posts (
    post_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    title VARCHAR(255) NOT NULL,
    slug VARCHAR(255) UNIQUE NOT NULL,
    content TEXT NOT NULL,
    excerpt TEXT NULL,
    featured_image VARCHAR(255) NULL,
    category ENUM('technology', 'career', 'education', 'research', 'student-life', 'industry-news', 'announcement') DEFAULT 'education',
    status ENUM('draft', 'published', 'archived') DEFAULT 'draft',
    views INT DEFAULT 0,
    likes_count INT DEFAULT 0,
    comments_count INT DEFAULT 0,
    reading_time_minutes INT DEFAULT 5,
    
    -- SEO
    meta_title VARCHAR(255) NULL,
    meta_description TEXT NULL,
    tags JSON NULL,
    
    -- Publishing
    published_at TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    -- Foreign Keys
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE,
    
    -- Indexes
    INDEX idx_user_id (user_id),
    INDEX idx_category (category),
    INDEX idx_status (status),
    INDEX idx_published_at (published_at),
    INDEX idx_slug (slug),
    INDEX idx_views (views)
);

-- ============================================================================
-- ACHIEVEMENTS TABLE
-- ============================================================================
CREATE TABLE IF NOT EXISTS achievements (
    achievement_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    title VARCHAR(255) NOT NULL,
    description TEXT NULL,
    achievement_type ENUM('certificate', 'award', 'project', 'activity', 'leadership', 'internship', 'competition', 'publication', 'volunteer') NOT NULL DEFAULT 'certificate',
    date_achieved DATE NOT NULL,
    certificate_url VARCHAR(500) NULL,
    institution VARCHAR(255) NULL,
    skills_gained TEXT NULL,
    is_featured BOOLEAN DEFAULT FALSE,
    verification_status ENUM('pending', 'verified', 'rejected') DEFAULT 'pending',
    
    -- Timestamps
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    -- Foreign Keys
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE,
    
    -- Indexes
    INDEX idx_user_id (user_id),
    INDEX idx_achievement_type (achievement_type),
    INDEX idx_date_achieved (date_achieved),
    INDEX idx_featured (is_featured),
    INDEX idx_verification (verification_status)
);

-- ============================================================================
-- JOBS TABLE
-- ============================================================================
CREATE TABLE IF NOT EXISTS jobs (
    job_id INT AUTO_INCREMENT PRIMARY KEY,
    company_id INT NOT NULL,
    title VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    requirements TEXT NULL,
    responsibilities TEXT NULL,
    location VARCHAR(255) NULL,
    job_type ENUM('full-time', 'part-time', 'internship', 'contract', 'remote', 'hybrid') DEFAULT 'full-time',
    experience_level ENUM('entry', 'junior', 'mid', 'senior', 'executive') DEFAULT 'entry',
    salary_min DECIMAL(10,2) NULL,
    salary_max DECIMAL(10,2) NULL,
    currency VARCHAR(10) DEFAULT 'LKR',
    application_deadline DATE NULL,
    status ENUM('active', 'closed', 'draft', 'paused') DEFAULT 'active',
    
    -- Job Details
    skills_required JSON NULL,
    benefits TEXT NULL,
    work_arrangement ENUM('onsite', 'remote', 'hybrid') DEFAULT 'onsite',
    
    -- Contact Information
    contact_email VARCHAR(100) NULL,
    contact_phone VARCHAR(20) NULL,
    application_url VARCHAR(500) NULL,
    
    -- Statistics
    applications_count INT DEFAULT 0,
    views_count INT DEFAULT 0,
    
    -- Timestamps
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    -- Foreign Keys
    FOREIGN KEY (company_id) REFERENCES users(user_id) ON DELETE CASCADE,
    
    -- Indexes
    INDEX idx_company_id (company_id),
    INDEX idx_job_type (job_type),
    INDEX idx_status (status),
    INDEX idx_deadline (application_deadline),
    INDEX idx_location (location),
    INDEX idx_experience_level (experience_level)
);

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
    
    -- Application tracking
    reviewed_at TIMESTAMP NULL,
    reviewed_by INT NULL,
    notes TEXT NULL,
    
    -- Timestamps
    applied_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    -- Foreign Keys
    FOREIGN KEY (job_id) REFERENCES jobs(job_id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE,
    FOREIGN KEY (reviewed_by) REFERENCES users(user_id) ON DELETE SET NULL,
    
    -- Prevent duplicate applications
    UNIQUE KEY unique_application (job_id, user_id),
    
    -- Indexes
    INDEX idx_job_id (job_id),
    INDEX idx_user_id (user_id),
    INDEX idx_status (status),
    INDEX idx_applied_at (applied_at)
);

-- ============================================================================
-- MESSAGES TABLE -> we not implementing a messaging system
-- ============================================================================
-- CREATE TABLE IF NOT EXISTS messages (
--     message_id INT AUTO_INCREMENT PRIMARY KEY,
--     sender_id INT NOT NULL,
--     receiver_id INT NOT NULL,
--     subject VARCHAR(255) NULL,
--     message_content TEXT NOT NULL,
--     message_type ENUM('direct', 'system', 'notification', 'application_update') DEFAULT 'direct',
--     is_read BOOLEAN DEFAULT FALSE,
--     is_deleted_by_sender BOOLEAN DEFAULT FALSE,
--     is_deleted_by_receiver BOOLEAN DEFAULT FALSE,
--     parent_message_id INT NULL,
    
--     -- Timestamps
--     sent_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
--     read_at TIMESTAMP NULL,
    
--     -- Foreign Keys
--     FOREIGN KEY (sender_id) REFERENCES users(user_id) ON DELETE CASCADE,
--     FOREIGN KEY (receiver_id) REFERENCES users(user_id) ON DELETE CASCADE,
--     FOREIGN KEY (parent_message_id) REFERENCES messages(message_id) ON DELETE SET NULL,
    
--     -- Indexes
--     INDEX idx_sender_id (sender_id),
--     INDEX idx_receiver_id (receiver_id),
--     INDEX idx_is_read (is_read),
--     INDEX idx_sent_at (sent_at),
--     INDEX idx_message_type (message_type)
-- );

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
    
    -- Timestamps
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    read_at TIMESTAMP NULL,
    
    -- Foreign Keys
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE,
    
    -- Indexes
    INDEX idx_user_id (user_id),
    INDEX idx_is_read (is_read),
    INDEX idx_notification_type (notification_type),
    INDEX idx_created_at (created_at)
);

-- ============================================================================
-- POST_LIKES TABLE (Many-to-many relationship)
-- ============================================================================
CREATE TABLE IF NOT EXISTS post_likes (
    like_id INT AUTO_INCREMENT PRIMARY KEY,
    post_id INT NOT NULL,
    user_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    -- Foreign Keys
    FOREIGN KEY (post_id) REFERENCES posts(post_id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE,
    
    -- Prevent duplicate likes
    UNIQUE KEY unique_like (post_id, user_id),
    
    -- Indexes
    INDEX idx_post_id (post_id),
    INDEX idx_user_id (user_id)
);

-- ============================================================================
-- POST_COMMENTS TABLE
-- ============================================================================
CREATE TABLE IF NOT EXISTS post_comments (
    comment_id INT AUTO_INCREMENT PRIMARY KEY,
    post_id INT NOT NULL,
    user_id INT NOT NULL,
    content TEXT NOT NULL,
    parent_comment_id INT NULL,
    status ENUM('active', 'hidden', 'deleted') DEFAULT 'active',
    likes_count INT DEFAULT 0,
    
    -- Timestamps
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    -- Foreign Keys
    FOREIGN KEY (post_id) REFERENCES posts(post_id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE,
    FOREIGN KEY (parent_comment_id) REFERENCES post_comments(comment_id) ON DELETE CASCADE,
    
    -- Indexes
    INDEX idx_post_id (post_id),
    INDEX idx_user_id (user_id),
    INDEX idx_parent_comment_id (parent_comment_id),
    INDEX idx_status (status),
    INDEX idx_created_at (created_at)
);

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
    
    -- Foreign Keys
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE,
    
    -- Indexes
    INDEX idx_user_id (user_id),
    INDEX idx_session_token (session_token),
    INDEX idx_expires_at (expires_at)
);

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
);

-- ============================================================================
-- USER_SKILLS TABLE (Many-to-many relationship for skills)
-- ============================================================================
CREATE TABLE IF NOT EXISTS skills (
    skill_id INT AUTO_INCREMENT PRIMARY KEY,
    skill_name VARCHAR(100) UNIQUE NOT NULL,
    category VARCHAR(50) NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    INDEX idx_skill_name (skill_name),
    INDEX idx_category (category)
);

CREATE TABLE IF NOT EXISTS user_skills (
    user_skill_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    skill_id INT NOT NULL,
    proficiency_level ENUM('beginner', 'intermediate', 'advanced', 'expert') DEFAULT 'beginner',
    years_of_experience INT DEFAULT 0,
    verified BOOLEAN DEFAULT FALSE,
    
    -- Timestamps
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    -- Foreign Keys
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE,
    FOREIGN KEY (skill_id) REFERENCES skills(skill_id) ON DELETE CASCADE,
    
    -- Prevent duplicate user-skill combinations
    UNIQUE KEY unique_user_skill (user_id, skill_id),
    
    -- Indexes
    INDEX idx_user_id (user_id),
    INDEX idx_skill_id (skill_id),
    INDEX idx_proficiency (proficiency_level)
);

-- ============================================================================
-- FILE_UPLOADS TABLE (For tracking uploaded files)
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
    
    -- Timestamps
    uploaded_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    -- Foreign Keys
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE,
    
    -- Indexes
    INDEX idx_user_id (user_id),
    INDEX idx_upload_purpose (upload_purpose),
    INDEX idx_uploaded_at (uploaded_at)
);

-- ============================================================================
-- INSERT DEFAULT DATA
-- ============================================================================

-- Default admin user
INSERT INTO users (
    username, email, password_hash, first_name, last_name, user_type, email_verified
) VALUES (
    'admin', 
    'admin@universe.edu', 
    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', -- password: password
    'Admin', 
    'User', 
    'admin', 
    TRUE
) ON DUPLICATE KEY UPDATE user_id=user_id;

-- Sample undergraduate user
INSERT INTO users (
    username, email, password_hash, first_name, middle_name, last_name,
    date_of_birth, gender, phone, user_type, email_verified
) VALUES (
    'johndoe', 
    'john.doe@universe.edu', 
    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', -- password: password
    'John', 
    'Michael', 
    'Doe',
    '2004-07-19',
    'male',
    '+1 (555) 123-4567',
    'undergraduate',
    TRUE
) ON DUPLICATE KEY UPDATE user_id=user_id;

-- Sample company user
INSERT INTO users (
    username, email, password_hash, first_name, last_name, user_type, email_verified
) VALUES (
    'techcorp', 
    'hr@techcorp.com', 
    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', -- password: password
    'TechCorp', 
    'Solutions', 
    'company', 
    TRUE
) ON DUPLICATE KEY UPDATE user_id=user_id;

-- Undergraduate profile for John Doe
INSERT INTO undergraduate_profiles (
    user_id, student_id, university, faculty, degree_program, academic_year, expected_graduation_year, gpa
) VALUES (
    2, 'CS2021001', 'University of Colombo', 'Faculty of Science', 'B.Sc. Computer Science', '3rd Year', 2026, 3.75
) ON DUPLICATE KEY UPDATE profile_id=profile_id;

-- Company profile for TechCorp
INSERT INTO company_profiles (
    user_id, company_name, company_size, industry, website, founded_year, 
    company_description, contact_person, contact_email, is_verified
) VALUES (
    3, 'TechCorp Solutions', 'medium', 'Information Technology', 'https://techcorp.com', 2015,
    'Leading software development company specializing in web and mobile applications.',
    'Jane Smith', 'hr@techcorp.com', TRUE
) ON DUPLICATE KEY UPDATE profile_id=profile_id;

-- Sample skills
INSERT INTO skills (skill_name, category) VALUES 
('JavaScript', 'Programming'),
('Python', 'Programming'),
('React', 'Frontend'),
('Node.js', 'Backend'),
('MySQL', 'Database'),
('Project Management', 'Soft Skills'),
('Communication', 'Soft Skills'),
('Leadership', 'Soft Skills')
ON DUPLICATE KEY UPDATE skill_id=skill_id;

-- User skills for John Doe
INSERT INTO user_skills (user_id, skill_id, proficiency_level, years_of_experience) VALUES 
(2, 1, 'intermediate', 2),  -- JavaScript
(2, 2, 'advanced', 3),      -- Python
(2, 3, 'intermediate', 1),  -- React
(2, 5, 'intermediate', 2)   -- MySQL
ON DUPLICATE KEY UPDATE user_skill_id=user_skill_id;

-- System settings
INSERT INTO system_settings (setting_key, setting_value, description) VALUES 
('site_name', 'UniVerse', 'Website name'),
('site_description', 'Connecting Universities with Opportunities', 'Website description'),
('admin_email', 'admin@universe.edu', 'Main admin email'),
('maintenance_mode', 'false', 'Site maintenance mode'),
('max_file_upload_size', '5242880', 'Maximum file upload size in bytes (5MB)'),
('default_posts_per_page', '10', 'Default number of posts per page'),
('job_application_limit', '50', 'Maximum job applications per user per month')
ON DUPLICATE KEY UPDATE setting_value=VALUES(setting_value);

-- Sample posts
INSERT INTO posts (user_id, title, slug, content, excerpt, category, status, published_at, tags) VALUES 
(
    1,
    'Getting Started with Computer Science at University', 
    'getting-started-computer-science-university',
    'Computer Science is an exciting field that combines logical thinking, creativity, and problem-solving skills. At the university level, students dive deep into programming languages, algorithms, data structures, and software engineering principles. This comprehensive guide will help new students navigate their journey in computer science education.',
    'A comprehensive guide for new computer science students on what to expect and how to succeed in their studies.',
    'education',
    'published',
    NOW(),
    '["computer-science", "education", "students", "university"]'
),
(
    1,
    'Top 10 Programming Languages to Learn in 2025',
    'top-10-programming-languages-2025',
    'The technology landscape continues to evolve rapidly, and staying current with programming languages is crucial for career success. In 2025, several languages stand out for their versatility, job market demand, and future potential. This article explores the most important programming languages every developer should consider learning.',
    'Discover the most in-demand programming languages that will boost your career prospects in 2025.',
    'technology',
    'published',
    NOW(),
    '["programming", "languages", "career", "technology", "2025"]'
)
ON DUPLICATE KEY UPDATE post_id=post_id;

-- Sample achievements
INSERT INTO achievements (user_id, title, description, achievement_type, date_achieved, institution, verification_status) VALUES 
(
    2,
    'Dean\'s List Recognition',
    'Achieved Dean\'s List recognition for maintaining a GPA above 3.8 for two consecutive semesters, demonstrating consistent academic excellence.',
    'award',
    '2024-05-15',
    'University of Colombo',
    'verified'
),
(
    2,
    'Java Programming Certification',
    'Successfully completed Oracle Java SE 11 Developer certification with a score of 89%, demonstrating proficiency in Java programming fundamentals.',
    'certificate',
    '2024-03-20',
    'Oracle Corporation',
    'verified'
),
(
    2,
    'University Hackathon Winner',
    'First place winner at the Annual University Hackathon 2024, developed an innovative mobile app for student productivity in 48 hours.',
    'competition',
    '2024-02-10',
    'University of Colombo',
    'verified'
)
ON DUPLICATE KEY UPDATE achievement_id=achievement_id;

-- Sample job posting
INSERT INTO jobs (
    company_id, title, description, requirements, responsibilities, location, job_type, 
    experience_level, salary_min, salary_max, application_deadline, skills_required
) VALUES (
    3,
    'Junior Software Developer',
    'We are looking for a motivated Junior Software Developer to join our growing team. You will work on developing web applications using modern technologies and collaborate with senior developers to deliver high-quality software solutions.',
    'Bachelor\'s degree in Computer Science or related field. Knowledge of HTML, CSS, JavaScript. Familiarity with at least one programming language (Java, Python, or C#). Good problem-solving skills and willingness to learn.',
    'Develop and maintain web applications. Collaborate with cross-functional teams. Write clean, maintainable code. Participate in code reviews. Learn new technologies and frameworks.',
    'Colombo, Sri Lanka',
    'full-time',
    'entry',
    80000,
    120000,
    DATE_ADD(NOW(), INTERVAL 30 DAY),
    '["JavaScript", "HTML", "CSS", "Java", "Problem Solving"]'
)
ON DUPLICATE KEY UPDATE job_id=job_id;

-- Sample notifications
INSERT INTO notifications (user_id, title, message, notification_type, action_url) VALUES 
(
    2,
    'Welcome to UniVerse!',
    'Thank you for joining UniVerse. Complete your profile to get better job recommendations.',
    'info',
    '/profile/edit'
),
(
    2,
    'New Job Match Found',
    'A new job posting matches your skills: Junior Software Developer at TechCorp.',
    'job',
    '/jobs/1'
)
ON DUPLICATE KEY UPDATE notification_id=notification_id;

-- Success message
SELECT 'Database setup completed successfully! All tables created with sample data.' as Status;
