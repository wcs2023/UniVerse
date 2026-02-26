-- =============================================================
-- SIMPLIFIED MENTORSHIP SYSTEM - 2-Week Rolling Availability
-- =============================================================
-- NEW WORKFLOW:
-- 1. Alumni sets availability slots (every 2 weeks)
-- 2. Student books any open slot (INSTANT confirmation)
-- 3. Cancel & Rebook if needed (with required reason)
-- =============================================================

-- =====================================================
-- NEW TABLE: mentor_availability_slots
-- Alumni's available time slots for 2-week periods
-- =====================================================
CREATE TABLE IF NOT EXISTS mentor_availability_slots (
    slot_id INT AUTO_INCREMENT PRIMARY KEY,
    mentor_id INT NOT NULL,
    slot_datetime DATETIME NOT NULL,
    duration_minutes INT NOT NULL DEFAULT 60,
    is_booked BOOLEAN NOT NULL DEFAULT 0,
    booked_by_student_id INT NULL,
    booking_id INT NULL,  -- Links to mentorship_bookings
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    -- Foreign keys
    FOREIGN KEY (mentor_id) REFERENCES mentors(mentor_id) ON DELETE CASCADE,
    FOREIGN KEY (booked_by_student_id) REFERENCES users(user_id) ON DELETE SET NULL,
    
    -- Prevent duplicate slots for same mentor at same time
    UNIQUE KEY unique_mentor_slot (mentor_id, slot_datetime),
    
    -- Indexes for fast queries
    INDEX idx_mentor_id (mentor_id),
    INDEX idx_slot_datetime (slot_datetime),
    INDEX idx_is_booked (is_booked),
    INDEX idx_booked_by (booked_by_student_id)
);

-- =====================================================
-- NEW TABLE: mentorship_bookings
-- Simplified booking records (replaces complex request flow)
-- =====================================================
CREATE TABLE IF NOT EXISTS mentorship_bookings (
    booking_id INT AUTO_INCREMENT PRIMARY KEY,
    slot_id INT NOT NULL,
    mentor_id INT NOT NULL,
    student_id INT NOT NULL,
    session_datetime DATETIME NOT NULL,
    duration_minutes INT NOT NULL DEFAULT 60,
    meeting_link VARCHAR(500) NULL,
    status ENUM('scheduled', 'completed', 'cancelled', 'no_show') DEFAULT 'scheduled',
    cancellation_reason TEXT NULL,
    cancelled_by INT NULL,
    notes TEXT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    -- Foreign keys
    FOREIGN KEY (slot_id) REFERENCES mentor_availability_slots(slot_id) ON DELETE CASCADE,
    FOREIGN KEY (mentor_id) REFERENCES mentors(mentor_id) ON DELETE CASCADE,
    FOREIGN KEY (student_id) REFERENCES users(user_id) ON DELETE CASCADE,
    FOREIGN KEY (cancelled_by) REFERENCES users(user_id) ON DELETE SET NULL,
    
    -- Indexes
    INDEX idx_mentor_id (mentor_id),
    INDEX idx_student_id (student_id),
    INDEX idx_session_datetime (session_datetime),
    INDEX idx_status (status)
);

-- =====================================================
-- NEW TABLE: mentorship_feedback
-- Simple feedback/review system
-- =====================================================
CREATE TABLE IF NOT EXISTS mentorship_feedback (
    feedback_id INT AUTO_INCREMENT PRIMARY KEY,
    booking_id INT NOT NULL,
    mentor_id INT NOT NULL,
    student_id INT NOT NULL,
    rating TINYINT NOT NULL CHECK (rating >= 1 AND rating <= 5),
    review_text TEXT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    -- Foreign keys
    FOREIGN KEY (booking_id) REFERENCES mentorship_bookings(booking_id) ON DELETE CASCADE,
    FOREIGN KEY (mentor_id) REFERENCES mentors(mentor_id) ON DELETE CASCADE,
    FOREIGN KEY (student_id) REFERENCES users(user_id) ON DELETE CASCADE,
    
    -- One review per booking
    UNIQUE KEY unique_booking_feedback (booking_id),
    
    -- Indexes
    INDEX idx_mentor_id (mentor_id),
    INDEX idx_student_id (student_id)
);

-- =====================================================
-- SIMPLIFIED NOTIFICATIONS TABLE (if not exists)
-- Reuses existing mentorship_notifications table
-- =====================================================
-- No changes needed - existing table works fine

-- =====================================================
-- VIEW: Available Slots for Students
-- Shows all unbooked future slots
-- =====================================================
CREATE OR REPLACE VIEW view_available_mentor_slots AS
SELECT 
    mas.slot_id,
    mas.mentor_id,
    mas.slot_datetime,
    mas.duration_minutes,
    m.user_id as mentor_user_id,
    u.first_name as mentor_first_name,
    u.last_name as mentor_last_name,
    u.profile_picture as mentor_picture,
    ap.current_job_title as mentor_title,
    ap.current_company as mentor_company,
    m.expertise_areas
FROM mentor_availability_slots mas
INNER JOIN mentors m ON mas.mentor_id = m.mentor_id
INNER JOIN users u ON m.user_id = u.user_id
LEFT JOIN alumni_profiles ap ON u.user_id = ap.user_id
WHERE mas.is_booked = 0
AND mas.slot_datetime > NOW()
ORDER BY mas.slot_datetime ASC;

-- =====================================================
-- VIEW: Student's Upcoming Bookings
-- =====================================================
CREATE OR REPLACE VIEW view_student_upcoming_bookings AS
SELECT 
    mb.booking_id,
    mb.session_datetime,
    mb.duration_minutes,
    mb.meeting_link,
    mb.status,
    mb.student_id,
    mb.mentor_id,
    u.first_name as mentor_first_name,
    u.last_name as mentor_last_name,
    u.profile_picture as mentor_picture,
    ap.current_job_title as mentor_title,
    ap.current_company as mentor_company
FROM mentorship_bookings mb
INNER JOIN mentors m ON mb.mentor_id = m.mentor_id
INNER JOIN users u ON m.user_id = u.user_id
LEFT JOIN alumni_profiles ap ON u.user_id = ap.user_id
WHERE mb.status = 'scheduled'
AND mb.session_datetime > NOW()
ORDER BY mb.session_datetime ASC;

-- =====================================================
-- VIEW: Mentor's Upcoming Bookings
-- =====================================================
CREATE OR REPLACE VIEW view_mentor_upcoming_bookings AS
SELECT 
    mb.booking_id,
    mb.session_datetime,
    mb.duration_minutes,
    mb.meeting_link,
    mb.status,
    mb.student_id,
    mb.mentor_id,
    u.first_name as student_first_name,
    u.last_name as student_last_name,
    u.profile_picture as student_picture,
    up.degree_program,
    up.academic_year
FROM mentorship_bookings mb
INNER JOIN users u ON mb.student_id = u.user_id
LEFT JOIN undergraduate_profiles up ON u.user_id = up.user_id
WHERE mb.status = 'scheduled'
AND mb.session_datetime > NOW()
ORDER BY mb.session_datetime ASC;
