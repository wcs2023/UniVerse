-- Mentorship Scheduling System Update
-- This migration adds the complete time slot and session scheduling workflow

-- Drop existing tables if they exist (for clean installation)
-- CAUTION: Only run these DROP statements on development/test environments
-- DROP TABLE IF EXISTS finalized_sessions;
-- DROP TABLE IF EXISTS mentor_proposed_slots;

-- =====================================================
-- MENTOR PROPOSED TIME SLOTS TABLE
-- Stores the 2 time slots offered by the mentor when accepting a request
-- =====================================================
CREATE TABLE IF NOT EXISTS mentor_proposed_slots (
    slot_id INT AUTO_INCREMENT PRIMARY KEY,
    request_id INT NOT NULL,
    proposed_datetime DATETIME NOT NULL,
    duration_minutes INT NOT NULL DEFAULT 60,
    is_selected BOOLEAN NOT NULL DEFAULT 0,
    is_available BOOLEAN NOT NULL DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (request_id) REFERENCES mentor_requests(request_id) ON DELETE CASCADE,
    INDEX idx_request_id (request_id),
    INDEX idx_proposed_datetime (proposed_datetime)
);

-- =====================================================
-- FINALIZED SESSIONS TABLE
-- Stores confirmed/locked mentorship sessions
-- =====================================================
CREATE TABLE IF NOT EXISTS finalized_sessions (
    session_id INT AUTO_INCREMENT PRIMARY KEY,
    request_id INT NOT NULL,
    slot_id INT NOT NULL,
    mentor_id INT NOT NULL,
    student_id INT NOT NULL,
    session_datetime DATETIME NOT NULL,
    duration_minutes INT NOT NULL DEFAULT 60,
    meeting_link VARCHAR(500) NULL,
    status ENUM('scheduled', 'completed', 'cancelled', 'no_show') DEFAULT 'scheduled',
    notes TEXT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (request_id) REFERENCES mentor_requests(request_id) ON DELETE CASCADE,
    FOREIGN KEY (slot_id) REFERENCES mentor_proposed_slots(slot_id) ON DELETE CASCADE,
    INDEX idx_mentor_id (mentor_id),
    INDEX idx_student_id (student_id),
    INDEX idx_session_datetime (session_datetime),
    INDEX idx_status (status)
);

-- =====================================================
-- MENTORSHIP NOTIFICATIONS TABLE
-- Enhanced notification system for mentorship events
-- =====================================================
CREATE TABLE IF NOT EXISTS mentorship_notifications (
    notification_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    request_id INT NULL,
    session_id INT NULL,
    notification_type ENUM(
        'request_received',           -- Alumni: New mentorship request
        'request_accepted',           -- Student: Request accepted, time slots available
        'request_declined',           -- Student: Request was declined
        'time_slots_offered',         -- Student: Mentor offered time slots
        'session_confirmed',          -- Both: Session date/time confirmed
        'session_reminder',           -- Both: Reminder before session
        'session_completed',          -- Both: Session completed
        'session_cancelled'           -- Both: Session was cancelled
    ) NOT NULL,
    title VARCHAR(255) NOT NULL,
    message TEXT NOT NULL,
    priority ENUM('low', 'normal', 'high') DEFAULT 'normal',
    is_read BOOLEAN NOT NULL DEFAULT 0,
    read_at DATETIME NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE,
    INDEX idx_user_id (user_id),
    INDEX idx_is_read (is_read),
    INDEX idx_notification_type (notification_type),
    INDEX idx_created_at (created_at)
);

-- =====================================================
-- UPDATE MENTOR_REQUESTS TABLE
-- Add new status for the scheduling workflow
-- =====================================================
ALTER TABLE mentor_requests 
MODIFY COLUMN status ENUM(
    'pending',                      -- Initial state: waiting for mentor response
    'awaiting_student_selection',   -- Mentor accepted, student needs to pick time
    'scheduled',                    -- Session is scheduled
    'completed',                    -- Session completed
    'rejected',                     -- Mentor declined the request
    'cancelled',                    -- Request was cancelled
    'expired'                       -- Request or time slots expired
) DEFAULT 'pending';

-- =====================================================
-- STORED PROCEDURE: Check for double bookings
-- =====================================================
DELIMITER //

CREATE PROCEDURE IF NOT EXISTS CheckSlotAvailability(
    IN p_mentor_id INT,
    IN p_proposed_datetime DATETIME,
    IN p_duration_minutes INT,
    OUT p_is_available BOOLEAN
)
BEGIN
    DECLARE conflict_count INT DEFAULT 0;
    
    -- Calculate end time
    SET @end_time = DATE_ADD(p_proposed_datetime, INTERVAL p_duration_minutes MINUTE);
    
    -- Check for overlapping sessions
    SELECT COUNT(*) INTO conflict_count
    FROM finalized_sessions fs
    WHERE fs.mentor_id = p_mentor_id
    AND fs.status = 'scheduled'
    AND (
        (p_proposed_datetime BETWEEN fs.session_datetime AND DATE_ADD(fs.session_datetime, INTERVAL fs.duration_minutes MINUTE))
        OR
        (@end_time BETWEEN fs.session_datetime AND DATE_ADD(fs.session_datetime, INTERVAL fs.duration_minutes MINUTE))
        OR
        (fs.session_datetime BETWEEN p_proposed_datetime AND @end_time)
    );
    
    SET p_is_available = (conflict_count = 0);
END //

DELIMITER ;

-- =====================================================
-- VIEW: Pending Time Slot Selections for Students
-- =====================================================
CREATE OR REPLACE VIEW view_pending_slot_selections AS
SELECT 
    mr.request_id,
    mr.student_id,
    mr.mentor_id,
    mr.status,
    mr.created_at as request_date,
    u_student.first_name as student_first_name,
    u_student.last_name as student_last_name,
    u_mentor.first_name as mentor_first_name,
    u_mentor.last_name as mentor_last_name,
    u_mentor.profile_picture as mentor_picture,
    ap.current_job_title as mentor_title,
    ap.current_company as mentor_company,
    mps.slot_id,
    mps.proposed_datetime,
    mps.duration_minutes,
    mps.is_selected,
    mps.is_available
FROM mentor_requests mr
JOIN users u_student ON mr.student_id = u_student.user_id
JOIN mentors m ON mr.mentor_id = m.mentor_id
JOIN users u_mentor ON m.user_id = u_mentor.user_id
LEFT JOIN alumni_profiles ap ON u_mentor.user_id = ap.user_id
LEFT JOIN mentor_proposed_slots mps ON mr.request_id = mps.request_id
WHERE mr.status = 'awaiting_student_selection';

-- =====================================================
-- VIEW: Upcoming Finalized Sessions
-- =====================================================
CREATE OR REPLACE VIEW view_upcoming_sessions AS
SELECT 
    fs.session_id,
    fs.request_id,
    fs.session_datetime,
    fs.duration_minutes,
    fs.meeting_link,
    fs.status,
    fs.mentor_id,
    fs.student_id,
    u_student.first_name as student_first_name,
    u_student.last_name as student_last_name,
    u_student.profile_picture as student_picture,
    u_mentor.first_name as mentor_first_name,
    u_mentor.last_name as mentor_last_name,
    u_mentor.profile_picture as mentor_picture,
    ap.current_job_title as mentor_title,
    ap.current_company as mentor_company
FROM finalized_sessions fs
JOIN users u_student ON fs.student_id = u_student.user_id
JOIN mentors m ON fs.mentor_id = m.mentor_id
JOIN users u_mentor ON m.user_id = u_mentor.user_id
LEFT JOIN alumni_profiles ap ON u_mentor.user_id = ap.user_id
WHERE fs.status = 'scheduled'
AND fs.session_datetime > NOW()
ORDER BY fs.session_datetime ASC;
