-- ============================================================================
-- UniVerse Mentorship System - Complete Database Schema
-- Created: October 16, 2025
-- Description: Production-ready schema for mentorship feature
-- ============================================================================

-- Drop existing tables if they exist (in correct order to handle foreign keys)
DROP TABLE IF EXISTS Notifications;
DROP TABLE IF EXISTS Messages;
DROP TABLE IF EXISTS Feedback;
DROP TABLE IF EXISTS Mentorship_Sessions;
DROP TABLE IF EXISTS Proposed_Slots;
DROP TABLE IF EXISTS Mentorship_Requests;
DROP TABLE IF EXISTS Mentee_Profiles;
DROP TABLE IF EXISTS Mentor_Profiles;
DROP TABLE IF EXISTS Users;

-- ============================================================================
-- 1. USERS TABLE
-- Purpose: Central table for all user types (Undergraduates, Alumni, Admin)
-- ============================================================================
CREATE TABLE Users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(100) NOT NULL,
    last_name VARCHAR(100) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('undergraduate', 'alumni', 'admin') NOT NULL,
    profile_picture_url VARCHAR(500) DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    last_login TIMESTAMP NULL DEFAULT NULL,
    account_status ENUM('active', 'suspended', 'deleted') DEFAULT 'active',
    
    INDEX idx_email (email),
    INDEX idx_role (role),
    INDEX idx_status (account_status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================================
-- 2. MENTOR PROFILES TABLE
-- Purpose: Extended information for alumni who are mentors
-- ============================================================================
CREATE TABLE Mentor_Profiles (
    mentor_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT UNIQUE NOT NULL,
    expertise_areas TEXT NOT NULL COMMENT 'Comma-separated areas of expertise',
    professional_experience TEXT,
    current_company VARCHAR(255),
    current_position VARCHAR(255),
    years_of_experience INT DEFAULT 0,
    graduation_year YEAR,
    degree VARCHAR(255),
    major VARCHAR(255),
    is_available BOOLEAN DEFAULT FALSE COMMENT 'Whether accepting new mentorship requests',
    average_rating DECIMAL(3,2) DEFAULT 0.00 CHECK (average_rating >= 0 AND average_rating <= 5),
    total_sessions INT DEFAULT 0,
    total_ratings INT DEFAULT 0,
    bio TEXT,
    linkedin_url VARCHAR(500),
    max_concurrent_mentees INT DEFAULT 5 COMMENT 'Maximum number of active mentees',
    response_time_hours INT DEFAULT 48 COMMENT 'Expected response time in hours',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (user_id) REFERENCES Users(user_id) ON DELETE CASCADE,
    INDEX idx_availability (is_available),
    INDEX idx_rating (average_rating),
    INDEX idx_expertise (expertise_areas(100))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================================
-- 3. MENTEE PROFILES TABLE
-- Purpose: Extended information for undergraduate students seeking mentorship
-- ============================================================================
CREATE TABLE Mentee_Profiles (
    mentee_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT UNIQUE NOT NULL,
    current_year INT COMMENT 'Year of study (1, 2, 3, 4)',
    major VARCHAR(255),
    interests TEXT COMMENT 'Areas of interest for mentorship',
    career_goals TEXT,
    gpa DECIMAL(3,2) CHECK (gpa >= 0 AND gpa <= 4),
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
-- 4. MENTORSHIP REQUESTS TABLE
-- Purpose: Tracks all mentorship requests and their lifecycle
-- ============================================================================
CREATE TABLE Mentorship_Requests (
    request_id INT AUTO_INCREMENT PRIMARY KEY,
    mentee_id INT NOT NULL,
    mentor_id INT NOT NULL,
    request_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    status ENUM('pending', 'accepted', 'rejected', 'scheduled', 'completed', 'cancelled') DEFAULT 'pending',
    mentee_message TEXT COMMENT 'Why the mentee is requesting mentorship',
    topic VARCHAR(500) COMMENT 'What they want to discuss',
    expectations TEXT COMMENT 'What the mentee hopes to gain',
    rejection_reason TEXT,
    accepted_at TIMESTAMP NULL DEFAULT NULL,
    scheduled_at TIMESTAMP NULL DEFAULT NULL,
    completed_at TIMESTAMP NULL DEFAULT NULL,
    cancelled_at TIMESTAMP NULL DEFAULT NULL,
    cancelled_by INT NULL DEFAULT NULL COMMENT 'user_id of who cancelled',
    cancellation_reason TEXT,
    last_updated TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (mentee_id) REFERENCES Users(user_id) ON DELETE CASCADE,
    FOREIGN KEY (mentor_id) REFERENCES Mentor_Profiles(mentor_id) ON DELETE CASCADE,
    FOREIGN KEY (cancelled_by) REFERENCES Users(user_id) ON DELETE SET NULL,
    INDEX idx_mentee (mentee_id),
    INDEX idx_mentor (mentor_id),
    INDEX idx_status (status),
    INDEX idx_request_date (request_date)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================================
-- 5. PROPOSED SLOTS TABLE
-- Purpose: Stores time slots proposed by mentor after accepting request
-- ============================================================================
CREATE TABLE Proposed_Slots (
    slot_id INT AUTO_INCREMENT PRIMARY KEY,
    request_id INT NOT NULL,
    start_time DATETIME NOT NULL,
    end_time DATETIME NOT NULL,
    is_selected BOOLEAN DEFAULT FALSE,
    is_available BOOLEAN DEFAULT TRUE COMMENT 'Can be disabled if no longer available',
    proposed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    meeting_link VARCHAR(500) COMMENT 'Zoom/Google Meet/Teams link',
    meeting_location VARCHAR(500) COMMENT 'Physical location if in-person',
    meeting_type ENUM('virtual', 'in-person', 'phone') DEFAULT 'virtual',
    notes TEXT COMMENT 'Additional information about the slot',
    
    FOREIGN KEY (request_id) REFERENCES Mentorship_Requests(request_id) ON DELETE CASCADE,
    INDEX idx_request (request_id),
    INDEX idx_start_time (start_time),
    INDEX idx_selected (is_selected),
    CHECK (end_time > start_time),
    CHECK (start_time >= NOW())
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================================
-- 6. MENTORSHIP SESSIONS TABLE
-- Purpose: Stores confirmed and scheduled mentorship sessions
-- ============================================================================
CREATE TABLE Mentorship_Sessions (
    session_id INT AUTO_INCREMENT PRIMARY KEY,
    request_id INT NOT NULL,
    mentee_id INT NOT NULL,
    mentor_id INT NOT NULL,
    slot_id INT NOT NULL COMMENT 'The selected time slot',
    scheduled_time DATETIME NOT NULL,
    end_time DATETIME NOT NULL,
    status ENUM('scheduled', 'in_progress', 'completed', 'cancelled', 'no_show_mentee', 'no_show_mentor') DEFAULT 'scheduled',
    meeting_link VARCHAR(500),
    meeting_location VARCHAR(500),
    meeting_type ENUM('virtual', 'in-person', 'phone') DEFAULT 'virtual',
    actual_start_time DATETIME NULL DEFAULT NULL,
    actual_end_time DATETIME NULL DEFAULT NULL,
    duration_minutes INT COMMENT 'Actual duration after completion',
    session_notes TEXT COMMENT 'Notes taken during or after session',
    completed_at TIMESTAMP NULL DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    FOREIGN KEY (request_id) REFERENCES Mentorship_Requests(request_id) ON DELETE CASCADE,
    FOREIGN KEY (mentee_id) REFERENCES Users(user_id) ON DELETE CASCADE,
    FOREIGN KEY (mentor_id) REFERENCES Mentor_Profiles(mentor_id) ON DELETE CASCADE,
    FOREIGN KEY (slot_id) REFERENCES Proposed_Slots(slot_id) ON DELETE RESTRICT,
    INDEX idx_mentee (mentee_id),
    INDEX idx_mentor (mentor_id),
    INDEX idx_scheduled_time (scheduled_time),
    INDEX idx_status (status),
    CHECK (end_time > scheduled_time)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================================
-- 7. FEEDBACK TABLE
-- Purpose: Stores ratings and feedback from both mentors and mentees
-- ============================================================================
CREATE TABLE Feedback (
    feedback_id INT AUTO_INCREMENT PRIMARY KEY,
    session_id INT NOT NULL,
    reviewer_id INT NOT NULL COMMENT 'Who is giving the feedback',
    reviewee_id INT NOT NULL COMMENT 'Who is receiving the feedback',
    reviewer_role ENUM('mentee', 'mentor') NOT NULL,
    rating INT NOT NULL CHECK (rating >= 1 AND rating <= 5),
    comment TEXT,
    would_recommend BOOLEAN DEFAULT TRUE,
    
    -- Mentee-specific fields
    skills_gained TEXT COMMENT 'What skills/knowledge the mentee gained',
    goals_met BOOLEAN COMMENT 'Did the session meet mentee expectations',
    
    -- Mentor-specific fields
    mentee_prepared BOOLEAN COMMENT 'Was the mentee prepared for the session',
    mentee_engaged BOOLEAN COMMENT 'Was the mentee engaged during the session',
    
    submitted_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    FOREIGN KEY (session_id) REFERENCES Mentorship_Sessions(session_id) ON DELETE CASCADE,
    FOREIGN KEY (reviewer_id) REFERENCES Users(user_id) ON DELETE CASCADE,
    FOREIGN KEY (reviewee_id) REFERENCES Users(user_id) ON DELETE CASCADE,
    UNIQUE KEY unique_feedback (session_id, reviewer_id),
    INDEX idx_session (session_id),
    INDEX idx_reviewee (reviewee_id),
    INDEX idx_rating (rating)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================================
-- 8. NOTIFICATIONS TABLE
-- Purpose: Tracks all system notifications for users
-- ============================================================================
CREATE TABLE Notifications (
    notification_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    type ENUM(
        'request_received',
        'request_accepted', 
        'request_rejected',
        'slots_proposed',
        'session_scheduled',
        'session_reminder_24h',
        'session_reminder_1h',
        'session_started',
        'session_completed',
        'feedback_requested',
        'feedback_received',
        'session_cancelled'
    ) NOT NULL,
    title VARCHAR(255) NOT NULL,
    message TEXT NOT NULL,
    related_id INT COMMENT 'ID of related entity (request_id, session_id, etc)',
    related_type ENUM('request', 'session', 'feedback', 'user'),
    action_url VARCHAR(500) COMMENT 'URL to navigate when notification is clicked',
    is_read BOOLEAN DEFAULT FALSE,
    is_sent BOOLEAN DEFAULT FALSE COMMENT 'Whether notification was sent via email/SMS',
    priority ENUM('low', 'normal', 'high') DEFAULT 'normal',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    read_at TIMESTAMP NULL DEFAULT NULL,
    
    FOREIGN KEY (user_id) REFERENCES Users(user_id) ON DELETE CASCADE,
    INDEX idx_user_unread (user_id, is_read),
    INDEX idx_created (created_at),
    INDEX idx_type (type)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================================
-- 9. MESSAGES TABLE
-- Purpose: Direct messaging between mentors and mentees
-- ============================================================================
CREATE TABLE Messages (
    message_id INT AUTO_INCREMENT PRIMARY KEY,
    request_id INT NOT NULL COMMENT 'Related mentorship request',
    sender_id INT NOT NULL,
    receiver_id INT NOT NULL,
    message TEXT NOT NULL,
    is_read BOOLEAN DEFAULT FALSE,
    sent_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    read_at TIMESTAMP NULL DEFAULT NULL,
    attachment_url VARCHAR(500),
    attachment_name VARCHAR(255),
    
    FOREIGN KEY (request_id) REFERENCES Mentorship_Requests(request_id) ON DELETE CASCADE,
    FOREIGN KEY (sender_id) REFERENCES Users(user_id) ON DELETE CASCADE,
    FOREIGN KEY (receiver_id) REFERENCES Users(user_id) ON DELETE CASCADE,
    INDEX idx_request (request_id),
    INDEX idx_receiver_unread (receiver_id, is_read),
    INDEX idx_sent_at (sent_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================================
-- TRIGGERS
-- Purpose: Automatically update related data when certain actions occur
-- ============================================================================

-- Trigger: Update mentor's average rating when new feedback is added
DELIMITER $$
CREATE TRIGGER update_mentor_rating_after_feedback
AFTER INSERT ON Feedback
FOR EACH ROW
BEGIN
    DECLARE mentor_user_id INT;
    
    -- Get the mentor's user_id from the session
    SELECT mentor_id INTO mentor_user_id
    FROM Mentorship_Sessions
    WHERE session_id = NEW.session_id;
    
    -- Only update if feedback is for a mentor (reviewer is mentee)
    IF NEW.reviewer_role = 'mentee' THEN
        UPDATE Mentor_Profiles
        SET 
            total_ratings = total_ratings + 1,
            average_rating = (
                SELECT AVG(rating)
                FROM Feedback f
                JOIN Mentorship_Sessions ms ON f.session_id = ms.session_id
                WHERE ms.mentor_id = mentor_user_id
                AND f.reviewer_role = 'mentee'
            )
        WHERE user_id = mentor_user_id;
    END IF;
END$$

-- Trigger: Update mentor's total sessions when session is completed
CREATE TRIGGER update_mentor_sessions_after_completion
AFTER UPDATE ON Mentorship_Sessions
FOR EACH ROW
BEGIN
    IF NEW.status = 'completed' AND OLD.status != 'completed' THEN
        UPDATE Mentor_Profiles
        SET total_sessions = total_sessions + 1
        WHERE user_id = NEW.mentor_id;
    END IF;
END$$

-- Trigger: Mark only selected slot and update others
CREATE TRIGGER update_slots_after_selection
AFTER UPDATE ON Proposed_Slots
FOR EACH ROW
BEGIN
    IF NEW.is_selected = TRUE AND OLD.is_selected = FALSE THEN
        -- Mark all other slots for this request as not selected
        UPDATE Proposed_Slots
        SET is_selected = FALSE
        WHERE request_id = NEW.request_id
        AND slot_id != NEW.slot_id;
    END IF;
END$$

DELIMITER ;

-- ============================================================================
-- VIEWS
-- Purpose: Simplified queries for common data retrieval patterns
-- ============================================================================

-- View: Active Mentors with their details
CREATE VIEW Active_Mentors AS
SELECT 
    u.user_id,
    u.first_name,
    u.last_name,
    u.email,
    u.profile_picture_url,
    mp.mentor_id,
    mp.expertise_areas,
    mp.current_company,
    mp.current_position,
    mp.years_of_experience,
    mp.average_rating,
    mp.total_sessions,
    mp.bio,
    mp.is_available
FROM Users u
JOIN Mentor_Profiles mp ON u.user_id = mp.user_id
WHERE u.account_status = 'active'
AND mp.is_available = TRUE;

-- View: Pending Mentorship Requests with details
CREATE VIEW Pending_Requests_Details AS
SELECT 
    mr.request_id,
    mr.request_date,
    mr.topic,
    mr.mentee_message,
    mentee.user_id AS mentee_user_id,
    mentee.first_name AS mentee_first_name,
    mentee.last_name AS mentee_last_name,
    mentee.email AS mentee_email,
    mp_mentee.major AS mentee_major,
    mp_mentee.current_year AS mentee_year,
    mentor.user_id AS mentor_user_id,
    mentor.first_name AS mentor_first_name,
    mentor.last_name AS mentor_last_name,
    mentor.email AS mentor_email,
    mp_mentor.current_company AS mentor_company,
    mp_mentor.current_position AS mentor_position
FROM Mentorship_Requests mr
JOIN Users mentee ON mr.mentee_id = mentee.user_id
JOIN Users mentor ON mr.mentor_id = (
    SELECT user_id FROM Mentor_Profiles WHERE mentor_id = mr.mentor_id
)
LEFT JOIN Mentee_Profiles mp_mentee ON mentee.user_id = mp_mentee.user_id
LEFT JOIN Mentor_Profiles mp_mentor ON mentor.user_id = mp_mentor.user_id
WHERE mr.status = 'pending';

-- View: Upcoming Sessions
CREATE VIEW Upcoming_Sessions_Details AS
SELECT 
    ms.session_id,
    ms.scheduled_time,
    ms.end_time,
    ms.meeting_link,
    ms.meeting_type,
    mentee.user_id AS mentee_user_id,
    mentee.first_name AS mentee_first_name,
    mentee.last_name AS mentee_last_name,
    mentor.user_id AS mentor_user_id,
    mentor.first_name AS mentor_first_name,
    mentor.last_name AS mentor_last_name,
    mr.topic
FROM Mentorship_Sessions ms
JOIN Users mentee ON ms.mentee_id = mentee.user_id
JOIN Mentor_Profiles mp ON ms.mentor_id = mp.mentor_id
JOIN Users mentor ON mp.user_id = mentor.user_id
JOIN Mentorship_Requests mr ON ms.request_id = mr.request_id
WHERE ms.status = 'scheduled'
AND ms.scheduled_time > NOW()
ORDER BY ms.scheduled_time ASC;

-- ============================================================================
-- INDEXES FOR PERFORMANCE
-- Additional composite indexes for common queries
-- ============================================================================
CREATE INDEX idx_mentor_available_rating ON Mentor_Profiles(is_available, average_rating DESC);
CREATE INDEX idx_request_status_date ON Mentorship_Requests(status, request_date DESC);
CREATE INDEX idx_session_status_time ON Mentorship_Sessions(status, scheduled_time);
CREATE INDEX idx_notification_user_type ON Notifications(user_id, type, is_read);

-- ============================================================================
-- SAMPLE DATA INSERTION (Optional - Uncomment if needed)
-- ============================================================================
/*
-- Insert sample users
INSERT INTO Users (first_name, last_name, email, password, role) VALUES
('John', 'Doe', 'john.doe@university.edu', '$2y$10$encrypted_password_here', 'undergraduate'),
('Jane', 'Smith', 'jane.smith@alumni.edu', '$2y$10$encrypted_password_here', 'alumni'),
('Bob', 'Johnson', 'bob.johnson@university.edu', '$2y$10$encrypted_password_here', 'undergraduate'),
('Alice', 'Williams', 'alice.williams@alumni.edu', '$2y$10$encrypted_password_here', 'alumni');

-- Insert mentor profiles
INSERT INTO Mentor_Profiles (user_id, expertise_areas, current_company, current_position, is_available, bio) VALUES
(2, 'Software Engineering, Web Development, Career Guidance', 'Tech Corp', 'Senior Software Engineer', TRUE, 'Passionate about helping students transition into tech careers.'),
(4, 'Data Science, Machine Learning, Research', 'Data Analytics Inc', 'Lead Data Scientist', TRUE, 'Former academic researcher, now in industry. Happy to guide on research and career paths.');

-- Insert mentee profiles
INSERT INTO Mentee_Profiles (user_id, current_year, major, interests, career_goals) VALUES
(1, 3, 'Computer Science', 'Web Development, AI', 'Software Engineer at a top tech company'),
(3, 2, 'Data Science', 'Machine Learning, Analytics', 'Data Scientist in healthcare');
*/

-- ============================================================================
-- END OF SCHEMA
-- ============================================================================
