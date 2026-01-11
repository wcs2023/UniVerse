-- ============================================================================
-- UniVerse Database - Complete Schema
-- Created: October 18, 2025
-- Description: Complete database schema for all features
-- ============================================================================

-- Create database if it doesn't exist
CREATE DATABASE IF NOT EXISTS my_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE my_db;

-- Drop existing tables if they exist (in correct order to handle foreign keys)
DROP TABLE IF EXISTS Messages;
DROP TABLE IF EXISTS Notifications;
DROP TABLE IF EXISTS Feedback;
DROP TABLE IF EXISTS Mentorship_Sessions;
DROP TABLE IF EXISTS Proposed_Slots;
DROP TABLE IF EXISTS Mentorship_Requests;
DROP TABLE IF EXISTS Mentee_Profiles;
DROP TABLE IF EXISTS Mentor_Profiles;
DROP TABLE IF EXISTS MentorshipTimeSlots;
DROP TABLE IF EXISTS Mentorships;
DROP TABLE IF EXISTS Articles;
DROP TABLE IF EXISTS AlumniSkills;
DROP TABLE IF EXISTS AlumniExperience;
DROP TABLE IF EXISTS Alumni;
DROP TABLE IF EXISTS Undergraduates;
DROP TABLE IF EXISTS Users;

-- ============================================================================
-- 1. USERS TABLE - Central table for all user types
-- ============================================================================
CREATE TABLE Users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('undergraduate', 'alumni', 'admin', 'company') NOT NULL,
    full_name VARCHAR(255) NULL,
    profile_picture_url VARCHAR(500) DEFAULT NULL,
    is_active BOOLEAN DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    last_login TIMESTAMP NULL DEFAULT NULL,
    
    INDEX idx_email (email),
    INDEX idx_role (role),
    INDEX idx_active (is_active)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================================
-- 2. ALUMNI TABLE - Extended profile for alumni users
-- ============================================================================
CREATE TABLE Alumni (
    alumni_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT UNIQUE NOT NULL,
    first_name VARCHAR(100),
    last_name VARCHAR(100),
    title VARCHAR(255) NULL,
    company VARCHAR(255) NULL,
    bio TEXT NULL,
    mentorship_status ENUM('available', 'unavailable') DEFAULT 'unavailable',
    linkedin_url VARCHAR(500) NULL,
    short_bio TEXT NULL,
    available_for_mentorship BOOLEAN DEFAULT 0,
    
    FOREIGN KEY (user_id) REFERENCES Users(user_id) ON DELETE CASCADE,
    INDEX idx_mentorship (available_for_mentorship),
    INDEX idx_status (mentorship_status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================================
-- 3. ALUMNI EXPERIENCE TABLE - Work experience for alumni
-- ============================================================================
CREATE TABLE AlumniExperience (
    experience_id INT AUTO_INCREMENT PRIMARY KEY,
    alumni_id INT NOT NULL,
    title VARCHAR(255) NOT NULL,
    company VARCHAR(255) NOT NULL,
    start_date VARCHAR(50) NOT NULL,
    end_date VARCHAR(50) NOT NULL,
    description TEXT NULL,
    
    FOREIGN KEY (alumni_id) REFERENCES Alumni(alumni_id) ON DELETE CASCADE,
    INDEX idx_alumni (alumni_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================================
-- 4. ALUMNI SKILLS TABLE - Skills and expertise for alumni
-- ============================================================================
CREATE TABLE AlumniSkills (
    skill_id INT AUTO_INCREMENT PRIMARY KEY,
    alumni_id INT NOT NULL,
    skill_name VARCHAR(100) NOT NULL,
    
    FOREIGN KEY (alumni_id) REFERENCES Alumni(alumni_id) ON DELETE CASCADE,
    INDEX idx_alumni (alumni_id),
    INDEX idx_skill (skill_name)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================================
-- 5. UNDERGRADUATES TABLE - Extended profile for student users
-- ============================================================================
CREATE TABLE Undergraduates (
    undergraduate_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT UNIQUE NOT NULL,
    first_name VARCHAR(100),
    last_name VARCHAR(100),
    program VARCHAR(255) NULL,
    year VARCHAR(50) NULL,
    major VARCHAR(255) NULL,
    interests TEXT NULL,
    
    FOREIGN KEY (user_id) REFERENCES Users(user_id) ON DELETE CASCADE,
    INDEX idx_major (major),
    INDEX idx_year (year)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================================
-- 6. ARTICLES TABLE - Alumni can create and manage articles
-- ============================================================================
CREATE TABLE Articles (
    article_id INT AUTO_INCREMENT PRIMARY KEY,
    author_id INT NOT NULL,
    title VARCHAR(255) NOT NULL,
    content TEXT NOT NULL,
    status ENUM('draft', 'published', 'archived') DEFAULT 'draft',
    category VARCHAR(100) NULL,
    tags TEXT NULL,
    views INT DEFAULT 0,
    likes INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    published_at TIMESTAMP NULL DEFAULT NULL,
    
    FOREIGN KEY (author_id) REFERENCES Users(user_id) ON DELETE CASCADE,
    INDEX idx_author (author_id),
    INDEX idx_status (status),
    INDEX idx_published (published_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================================
-- 7. MENTORSHIPS TABLE - Main table to track mentorship relationships
-- ============================================================================
CREATE TABLE Mentorships (
    mentorship_id INT AUTO_INCREMENT PRIMARY KEY,
    undergraduate_id INT NOT NULL,
    alumni_id INT NOT NULL,
    topic VARCHAR(255) NULL,
    message TEXT NULL,
    expectations TEXT NULL,
    status ENUM('pending', 'awaiting_student_confirmation', 'scheduled', 'completed', 'rejected', 'canceled') DEFAULT 'pending',
    request_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    scheduled_date DATETIME NULL,
    duration INT DEFAULT 60,
    feedback TEXT NULL,
    mentor_feedback TEXT NULL,
    
    FOREIGN KEY (undergraduate_id) REFERENCES Undergraduates(undergraduate_id) ON DELETE CASCADE,
    FOREIGN KEY (alumni_id) REFERENCES Alumni(alumni_id) ON DELETE CASCADE,
    INDEX idx_undergraduate (undergraduate_id),
    INDEX idx_alumni (alumni_id),
    INDEX idx_status (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================================
-- 8. MENTORSHIP TIME SLOTS TABLE - Specific time slots offered by mentors
-- ============================================================================
CREATE TABLE MentorshipTimeSlots (
    slot_id INT AUTO_INCREMENT PRIMARY KEY,
    mentorship_id INT NOT NULL,
    start_datetime DATETIME NOT NULL,
    end_datetime DATETIME NOT NULL,
    is_booked BOOLEAN NOT NULL DEFAULT 0,
    
    FOREIGN KEY (mentorship_id) REFERENCES Mentorships(mentorship_id) ON DELETE CASCADE,
    INDEX idx_mentorship (mentorship_id),
    INDEX idx_booked (is_booked)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================================
-- 9. MENTOR PROFILES TABLE - Extended info for alumni mentors
-- ============================================================================
CREATE TABLE Mentor_Profiles (
    mentor_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT UNIQUE NOT NULL,
    expertise_areas TEXT NOT NULL COMMENT 'Comma-separated areas',
    professional_experience TEXT,
    current_company VARCHAR(255),
    current_position VARCHAR(255),
    years_of_experience INT DEFAULT 0,
    is_available BOOLEAN DEFAULT FALSE,
    average_rating DECIMAL(3,2) DEFAULT 0.00,
    total_sessions INT DEFAULT 0,
    total_ratings INT DEFAULT 0,
    bio TEXT,
    linkedin_url VARCHAR(500),
    max_concurrent_mentees INT DEFAULT 5,
    response_time_hours INT DEFAULT 48,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (user_id) REFERENCES Users(user_id) ON DELETE CASCADE,
    INDEX idx_availability (is_available),
    INDEX idx_rating (average_rating)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================================
-- 10. MENTEE PROFILES TABLE - Extended info for students seeking mentorship
-- ============================================================================
CREATE TABLE Mentee_Profiles (
    mentee_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT UNIQUE NOT NULL,
    current_year INT,
    major VARCHAR(255),
    interests TEXT,
    career_goals TEXT,
    gpa DECIMAL(3,2),
    university VARCHAR(255),
    expected_graduation YEAR,
    resume_url VARCHAR(500),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (user_id) REFERENCES Users(user_id) ON DELETE CASCADE,
    INDEX idx_major (major),
    INDEX idx_graduation (expected_graduation)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================================
-- 11. MENTORSHIP REQUESTS TABLE - Tracks mentorship request lifecycle
-- ============================================================================
CREATE TABLE Mentorship_Requests (
    request_id INT AUTO_INCREMENT PRIMARY KEY,
    mentee_id INT NOT NULL,
    mentor_id INT NOT NULL,
    request_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    status ENUM('pending', 'accepted', 'rejected', 'scheduled', 'completed', 'cancelled') DEFAULT 'pending',
    mentee_message TEXT,
    topic VARCHAR(500),
    expectations TEXT,
    rejection_reason TEXT,
    accepted_at TIMESTAMP NULL,
    scheduled_at TIMESTAMP NULL,
    completed_at TIMESTAMP NULL,
    cancelled_at TIMESTAMP NULL,
    cancelled_by INT NULL,
    cancellation_reason TEXT,
    last_updated TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (mentee_id) REFERENCES Users(user_id) ON DELETE CASCADE,
    FOREIGN KEY (mentor_id) REFERENCES Mentor_Profiles(mentor_id) ON DELETE CASCADE,
    FOREIGN KEY (cancelled_by) REFERENCES Users(user_id) ON DELETE SET NULL,
    INDEX idx_mentee (mentee_id),
    INDEX idx_mentor (mentor_id),
    INDEX idx_status (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================================
-- 12. PROPOSED SLOTS TABLE - Time slots proposed by mentor
-- ============================================================================
CREATE TABLE Proposed_Slots (
    slot_id INT AUTO_INCREMENT PRIMARY KEY,
    request_id INT NOT NULL,
    start_time DATETIME NOT NULL,
    end_time DATETIME NOT NULL,
    is_selected BOOLEAN DEFAULT FALSE,
    is_available BOOLEAN DEFAULT TRUE,
    proposed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    meeting_link VARCHAR(500),
    meeting_location VARCHAR(500),
    meeting_type ENUM('virtual', 'in-person', 'phone') DEFAULT 'virtual',
    notes TEXT,
    
    FOREIGN KEY (request_id) REFERENCES Mentorship_Requests(request_id) ON DELETE CASCADE,
    INDEX idx_request (request_id),
    INDEX idx_selected (is_selected)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================================
-- 13. MENTORSHIP SESSIONS TABLE - Confirmed sessions
-- ============================================================================
CREATE TABLE Mentorship_Sessions (
    session_id INT AUTO_INCREMENT PRIMARY KEY,
    request_id INT NOT NULL,
    mentee_id INT NOT NULL,
    mentor_id INT NOT NULL,
    slot_id INT NOT NULL,
    scheduled_time DATETIME NOT NULL,
    end_time DATETIME NOT NULL,
    status ENUM('scheduled', 'in_progress', 'completed', 'cancelled', 'no_show_mentee', 'no_show_mentor') DEFAULT 'scheduled',
    meeting_link VARCHAR(500),
    meeting_location VARCHAR(500),
    meeting_type ENUM('virtual', 'in-person', 'phone') DEFAULT 'virtual',
    actual_start_time DATETIME NULL,
    actual_end_time DATETIME NULL,
    duration_minutes INT,
    session_notes TEXT,
    completed_at TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    FOREIGN KEY (request_id) REFERENCES Mentorship_Requests(request_id) ON DELETE CASCADE,
    FOREIGN KEY (mentee_id) REFERENCES Users(user_id) ON DELETE CASCADE,
    FOREIGN KEY (mentor_id) REFERENCES Mentor_Profiles(mentor_id) ON DELETE CASCADE,
    FOREIGN KEY (slot_id) REFERENCES Proposed_Slots(slot_id) ON DELETE RESTRICT,
    INDEX idx_status (status),
    INDEX idx_scheduled_time (scheduled_time)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================================
-- 14. FEEDBACK TABLE - Ratings and feedback
-- ============================================================================
CREATE TABLE Feedback (
    feedback_id INT AUTO_INCREMENT PRIMARY KEY,
    session_id INT NOT NULL,
    reviewer_id INT NOT NULL,
    reviewee_id INT NOT NULL,
    reviewer_role ENUM('mentee', 'mentor') NOT NULL,
    rating INT NOT NULL,
    comment TEXT,
    would_recommend BOOLEAN DEFAULT TRUE,
    skills_gained TEXT,
    goals_met BOOLEAN,
    mentee_prepared BOOLEAN,
    mentee_engaged BOOLEAN,
    submitted_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    FOREIGN KEY (session_id) REFERENCES Mentorship_Sessions(session_id) ON DELETE CASCADE,
    FOREIGN KEY (reviewer_id) REFERENCES Users(user_id) ON DELETE CASCADE,
    FOREIGN KEY (reviewee_id) REFERENCES Users(user_id) ON DELETE CASCADE,
    UNIQUE KEY unique_feedback (session_id, reviewer_id),
    INDEX idx_rating (rating)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================================
-- 15. NOTIFICATIONS TABLE - System notifications
-- ============================================================================
CREATE TABLE Notifications (
    notification_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    user_type ENUM('undergraduate', 'alumni', 'admin', 'company') NOT NULL,
    related_id INT NULL,
    type ENUM(
        'mentorship_request', 'mentorship_accepted', 'mentorship_rejected',
        'time_slots_offered', 'session_scheduled', 'session_reminder',
        'session_feedback', 'request_received', 'request_accepted',
        'slots_proposed', 'session_reminder_24h', 'session_reminder_1h',
        'session_started', 'session_completed', 'feedback_requested',
        'feedback_received', 'session_cancelled'
    ) NOT NULL,
    message TEXT NOT NULL,
    is_read BOOLEAN NOT NULL DEFAULT 0,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    
    FOREIGN KEY (user_id) REFERENCES Users(user_id) ON DELETE CASCADE,
    INDEX idx_user_type (user_id, user_type),
    INDEX idx_read (is_read)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================================
-- 16. MESSAGES TABLE - Direct messaging
-- ============================================================================
CREATE TABLE Messages (
    message_id INT AUTO_INCREMENT PRIMARY KEY,
    request_id INT NOT NULL,
    sender_id INT NOT NULL,
    receiver_id INT NOT NULL,
    message TEXT NOT NULL,
    is_read BOOLEAN DEFAULT FALSE,
    sent_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    read_at TIMESTAMP NULL,
    attachment_url VARCHAR(500),
    attachment_name VARCHAR(255),
    
    FOREIGN KEY (request_id) REFERENCES Mentorship_Requests(request_id) ON DELETE CASCADE,
    FOREIGN KEY (sender_id) REFERENCES Users(user_id) ON DELETE CASCADE,
    FOREIGN KEY (receiver_id) REFERENCES Users(user_id) ON DELETE CASCADE,
    INDEX idx_request (request_id),
    INDEX idx_receiver_unread (receiver_id, is_read)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================================
-- END OF SCHEMA
-- ============================================================================
