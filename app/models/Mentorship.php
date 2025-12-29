<?php

class Mentorship
{
    private $db;
    
    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }
    
    /**
     * Create a new mentorship request
     * 
     * @param int $undergraduate_id The undergraduate's ID
     * @param int $alumni_id The alumni's ID
     * @param string $topic The topic of the mentorship
     * @param string $message The message from the student
     * @param string $expectations The student's expectations
     * @return int|bool The new mentorship ID on success, false on failure
     */
    public function createRequest($undergraduate_id, $alumni_id, $topic = '', $message = '', $expectations = '')
    {
        try {
            $query = "INSERT INTO Mentorships (undergraduate_id, alumni_id, topic, message, expectations, status, request_date) 
                      VALUES (?, ?, ?, ?, ?, 'pending', NOW())";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$undergraduate_id, $alumni_id, $topic, $message, $expectations]);
            return $this->db->lastInsertId();
        } catch(PDOException $e) {
            error_log("Error creating mentorship request: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Get all mentorship requests for an undergraduate
     * 
     * @param int $undergraduate_id The undergraduate's ID
     * @return array The mentorship requests grouped by status
     */
    public function getRequestsByUndergraduate($undergraduate_id)
    {
        $result = [
            'pending' => [],
            'upcoming' => [],
            'completed' => []
        ];
        
        try {
            // Get pending requests
            $query = "SELECT m.*, a.first_name, a.last_name, a.title, a.company 
                      FROM Mentorships m
                      JOIN Alumni a ON m.alumni_id = a.alumni_id
                      WHERE m.undergraduate_id = ?
                      AND (m.status = 'pending' OR m.status = 'awaiting_student_confirmation' OR m.status = 'rejected')
                      ORDER BY m.request_date DESC";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$undergraduate_id]);
            $result['pending'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // Get upcoming sessions
            $query = "SELECT m.*, a.first_name, a.last_name, a.title, a.company,
                      DATE_ADD(m.scheduled_date, INTERVAL 60 MINUTE) as end_datetime
                      FROM Mentorships m
                      JOIN Alumni a ON m.alumni_id = a.alumni_id
                      WHERE m.undergraduate_id = ?
                      AND m.status = 'scheduled'
                      AND m.scheduled_date > NOW()
                      ORDER BY m.scheduled_date ASC";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$undergraduate_id]);
            $result['upcoming'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // Get completed sessions
            $query = "SELECT m.*, a.first_name, a.last_name, a.title, a.company,
                      CASE WHEN m.feedback IS NOT NULL THEN 1 ELSE 0 END as feedback_provided
                      FROM Mentorships m
                      JOIN Alumni a ON m.alumni_id = a.alumni_id
                      WHERE m.undergraduate_id = ?
                      AND (m.status = 'completed' OR (m.status = 'scheduled' AND m.scheduled_date < NOW()))
                      ORDER BY m.scheduled_date DESC";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$undergraduate_id]);
            $result['completed'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            return $result;
        } catch(PDOException $e) {
            error_log("Error getting mentorship requests: " . $e->getMessage());
            return $result;
        }
    }
    
    /**
     * Get all mentorship requests for an alumni
     * 
     * @param int $alumni_id The alumni's ID
     * @return array The mentorship requests grouped by status
     */
    public function getRequestsByAlumni($alumni_id)
    {
        $result = [
            'requests' => [],
            'upcoming' => [],
            'completed' => []
        ];
        
        try {
            // Get pending requests
            $query = "SELECT m.*, u.first_name, u.last_name, u.program, 
                      u.year, u.major, u.interests
                      FROM Mentorships m
                      JOIN Undergraduates u ON m.undergraduate_id = u.undergraduate_id
                      WHERE m.alumni_id = ?
                      AND m.status = 'pending'
                      ORDER BY m.request_date DESC";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$alumni_id]);
            $result['requests'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // Format the names and other fields for display
            foreach ($result['requests'] as &$request) {
                $request['student_name'] = $request['first_name'] . ' ' . $request['last_name'];
                $request['student_program'] = $request['program'];
                $request['student_year'] = $request['year'];
                $request['student_major'] = $request['major'];
                $request['student_interests'] = $request['interests'];
            }
            
            // Get upcoming sessions
            $query = "SELECT m.*, u.first_name, u.last_name, u.program, 
                      DATE_ADD(m.scheduled_date, INTERVAL 60 MINUTE) as end_datetime
                      FROM Mentorships m
                      JOIN Undergraduates u ON m.undergraduate_id = u.undergraduate_id
                      WHERE m.alumni_id = ?
                      AND (m.status = 'scheduled' OR m.status = 'awaiting_student_confirmation')
                      AND (m.scheduled_date > NOW() OR m.status = 'awaiting_student_confirmation')
                      ORDER BY m.scheduled_date ASC";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$alumni_id]);
            $result['upcoming'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // Format the names and other fields for display
            foreach ($result['upcoming'] as &$session) {
                $session['student_name'] = $session['first_name'] . ' ' . $session['last_name'];
                $session['student_program'] = $session['program'];
            }
            
            // Get completed sessions
            $query = "SELECT m.*, u.first_name, u.last_name, u.program,
                      CASE WHEN m.feedback IS NOT NULL THEN 1 ELSE 0 END as feedback_provided,
                      CASE WHEN m.mentor_feedback IS NOT NULL THEN 1 ELSE 0 END as mentor_feedback_provided
                      FROM Mentorships m
                      JOIN Undergraduates u ON m.undergraduate_id = u.undergraduate_id
                      WHERE m.alumni_id = ?
                      AND (m.status = 'completed' OR (m.status = 'scheduled' AND m.scheduled_date < NOW()))
                      ORDER BY m.scheduled_date DESC";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$alumni_id]);
            $result['completed'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // Format the names and other fields for display
            foreach ($result['completed'] as &$session) {
                $session['student_name'] = $session['first_name'] . ' ' . $session['last_name'];
                $session['student_program'] = $session['program'];
            }
            
            return $result;
        } catch(PDOException $e) {
            error_log("Error getting mentorship requests: " . $e->getMessage());
            return $result;
        }
    }
    
    /**
     * Update the status of a mentorship request
     * 
     * @param int $mentorship_id The mentorship ID
     * @param string $status The new status
     * @return bool True on success, false on failure
     */
    public function updateStatus($mentorship_id, $status)
    {
        try {
            $query = "UPDATE Mentorships SET status = ? WHERE mentorship_id = ?";
            $stmt = $this->db->prepare($query);
            return $stmt->execute([$status, $mentorship_id]);
        } catch(PDOException $e) {
            error_log("Error updating mentorship status: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Schedule a mentorship session
     * 
     * @param int $mentorship_id The mentorship ID
     * @param string $scheduled_date The scheduled date/time
     * @return bool True on success, false on failure
     */
    public function scheduleSession($mentorship_id, $scheduled_date)
    {
        try {
            $this->db->beginTransaction();
            
            // Get the duration from the selected time slot
            $query = "SELECT slot_id, TIMESTAMPDIFF(MINUTE, start_datetime, end_datetime) as duration
                      FROM MentorshipTimeSlots 
                      WHERE mentorship_id = ? AND start_datetime = ?";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$mentorship_id, $scheduled_date]);
            $slot = $stmt->fetch(PDO::FETCH_ASSOC);
            $duration = $slot ? $slot['duration'] : 60; // Default to 60 minutes if not found
            
            // Update the mentorship with scheduled time and duration
            $query = "UPDATE Mentorships 
                      SET status = 'scheduled', 
                          scheduled_date = ?,
                          duration = ?
                      WHERE mentorship_id = ?";
            $stmt = $this->db->prepare($query);
            $result = $stmt->execute([$scheduled_date, $duration, $mentorship_id]);
            
            // Mark the selected slot as booked
            if ($slot && isset($slot['slot_id'])) {
                $query = "UPDATE MentorshipTimeSlots SET is_booked = 1 WHERE slot_id = ?";
                $stmt = $this->db->prepare($query);
                $stmt->execute([$slot['slot_id']]);
            }
            
            $this->db->commit();
            return $result;
        } catch(PDOException $e) {
            $this->db->rollBack();
            error_log("Error scheduling mentorship session: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Select a time slot for a mentorship
     * 
     * @param int $mentorship_id The mentorship ID
     * @param int $slot_id The selected slot ID
     * @return bool True on success, false on failure
     */
    public function selectTimeSlot($mentorship_id, $slot_id)
    {
        try {
            $this->db->beginTransaction();
            
            // Get the slot details
            $query = "SELECT * FROM MentorshipTimeSlots WHERE slot_id = ? AND mentorship_id = ?";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$slot_id, $mentorship_id]);
            $slot = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$slot) {
                $this->db->rollBack();
                return false;
            }
            
            // Mark the slot as booked
            $query = "UPDATE MentorshipTimeSlots SET is_booked = 1 WHERE slot_id = ?";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$slot_id]);
            
            // Mark other slots for this mentorship as not booked
            $query = "UPDATE MentorshipTimeSlots SET is_booked = 0 WHERE mentorship_id = ? AND slot_id != ?";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$mentorship_id, $slot_id]);
            
            // Schedule the session
            $duration = round((strtotime($slot['end_datetime']) - strtotime($slot['start_datetime'])) / 60);
            $query = "UPDATE Mentorships 
                      SET status = 'scheduled', 
                          scheduled_date = ?,
                          duration = ?
                      WHERE mentorship_id = ?";
            $stmt = $this->db->prepare($query);
            $result = $stmt->execute([$slot['start_datetime'], $duration, $mentorship_id]);
            
            $this->db->commit();
            return $result;
        } catch(PDOException $e) {
            $this->db->rollBack();
            error_log("Error selecting time slot: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Add time slots for a mentorship
     * 
     * @param int $mentorship_id The mentorship ID
     * @param array $slots Array of start and end datetimes
     * @return bool True on success, false on failure
     */
    public function addTimeSlots($mentorship_id, $slots)
    {
        try {
            $this->db->beginTransaction();
            
            $query = "INSERT INTO MentorshipTimeSlots (mentorship_id, start_datetime, end_datetime) VALUES (?, ?, ?)";
            $stmt = $this->db->prepare($query);
            
            foreach ($slots as $slot) {
                $stmt->execute([$mentorship_id, $slot['start'], $slot['end']]);
            }
            
            $this->db->commit();
            return true;
        } catch(PDOException $e) {
            $this->db->rollBack();
            error_log("Error adding time slots: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Get time slots for a mentorship
     * 
     * @param int $mentorship_id The mentorship ID
     * @return array The time slots
     */
    public function getTimeSlots($mentorship_id)
    {
        try {
            $query = "SELECT * FROM MentorshipTimeSlots WHERE mentorship_id = ? ORDER BY start_datetime";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$mentorship_id]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            error_log("Error getting time slots: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Add feedback to a mentorship
     * 
     * @param int $mentorship_id The mentorship ID
     * @param string $feedback The feedback text
     * @param string $type The type of feedback (student or mentor)
     * @return bool True on success, false on failure
     */
    public function addFeedback($mentorship_id, $feedback, $type = 'student')
    {
        try {
            $field = ($type === 'student') ? 'feedback' : 'mentor_feedback';
            
            $query = "UPDATE Mentorships SET $field = ? WHERE mentorship_id = ?";
            $stmt = $this->db->prepare($query);
            return $stmt->execute([$feedback, $mentorship_id]);
        } catch(PDOException $e) {
            error_log("Error adding feedback: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Get a single mentorship by ID
     * 
     * @param int $mentorship_id The mentorship ID
     * @return array|bool The mentorship data or false on failure
     */
    public function getMentorshipById($mentorship_id)
    {
        try {
            $query = "SELECT m.*, 
                      a.first_name as alumni_first_name, a.last_name as alumni_last_name, 
                      a.title as mentor_title, a.company,
                      u.first_name as student_first_name, u.last_name as student_last_name, 
                      u.program as student_program,
                      DATE_ADD(m.scheduled_date, INTERVAL COALESCE(m.duration, 60) MINUTE) as end_datetime
                      FROM Mentorships m 
                      JOIN Alumni a ON m.alumni_id = a.alumni_id
                      JOIN Undergraduates u ON m.undergraduate_id = u.undergraduate_id
                      WHERE m.mentorship_id = ?";
            
            $stmt = $this->db->prepare($query);
            $stmt->execute([$mentorship_id]);
            $mentorship = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($mentorship) {
                // Format the names for display
                $mentorship['mentor_name'] = $mentorship['alumni_first_name'] . ' ' . $mentorship['alumni_last_name'];
                $mentorship['student_name'] = $mentorship['student_first_name'] . ' ' . $mentorship['student_last_name'];
            }
            
            return $mentorship;
        } catch(PDOException $e) {
            error_log("Error getting mentorship: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Get all available mentors from the database
     * 
     * @param string $searchTerm Search term for filtering mentors
     * @param string $industry Industry filter
     * @param string $expertise Expertise filter
     * @return array Array of mentor profiles
     */
    public function getAllMentors($searchTerm = '', $industry = '', $expertise = '')
    {
        try {
            $query = "SELECT 
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
                        mp.bio,
                        mp.average_rating,
                        mp.total_sessions,
                        mp.is_available
                      FROM Users u
                      INNER JOIN Mentor_Profiles mp ON u.user_id = mp.user_id
                      WHERE u.role = 'alumni'
                      AND mp.is_available = 1";
            
            $params = [];
            
            // Add search filter
            if (!empty($searchTerm)) {
                $query .= " AND (u.first_name LIKE ? OR u.last_name LIKE ? OR mp.current_company LIKE ? OR mp.current_position LIKE ?)";
                $searchParam = "%$searchTerm%";
                $params[] = $searchParam;
                $params[] = $searchParam;
                $params[] = $searchParam;
                $params[] = $searchParam;
            }
            
            // Add industry filter
            if (!empty($industry)) {
                $query .= " AND mp.current_company LIKE ?";
                $params[] = "%$industry%";
            }
            
            // Add expertise filter
            if (!empty($expertise)) {
                $query .= " AND mp.expertise_areas LIKE ?";
                $params[] = "%$expertise%";
            }
            
            $query .= " ORDER BY mp.average_rating DESC, mp.total_sessions DESC";
            
            $stmt = $this->db->prepare($query);
            $stmt->execute($params);
            $mentors = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // Process expertise areas to array
            foreach ($mentors as &$mentor) {
                if (!empty($mentor['expertise_areas'])) {
                    $mentor['expertise_array'] = array_map('trim', explode(',', $mentor['expertise_areas']));
                } else {
                    $mentor['expertise_array'] = [];
                }
            }
            
            return $mentors;
        } catch(PDOException $e) {
            error_log("Error getting all mentors: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Get mentor profile by user ID
     */
    public function getMentorByUserId($userId)
    {
        try {
            $query = "SELECT * FROM Mentor_Profiles WHERE user_id = ?";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$userId]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            error_log("Error getting mentor by user ID: " . $e->getMessage());
            return null;
        }
    }
    
    /**
     * Get pending mentorship requests for a mentor
     * Updated to use Users table directly instead of Alumni table
     */
    public function getPendingRequestsForMentor($mentorId)
    {
        try {
            // Query uses alumni_id but we pass user_id (they should be same in simplified approach)
            $query = "SELECT 
                        m.mentorship_id as request_id,
                        m.request_date,
                        m.message,
                        m.topic,
                        m.expectations,
                        u.user_id,
                        u.first_name,
                        u.last_name,
                        CONCAT(u.first_name, ' ', u.last_name) as mentee_name,
                        u.profile_picture as profile_picture_url,
                        up.degree_program as major,
                        up.current_year as year_of_study
                      FROM Mentorships m
                      JOIN undergraduate_profiles up ON m.undergraduate_id = up.student_id
                      JOIN users u ON up.user_id = u.user_id
                      WHERE m.alumni_id = ?
                      AND m.status = 'pending'
                      ORDER BY m.request_date DESC";
            
            $stmt = $this->db->prepare($query);
            $stmt->execute([$mentorId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            error_log("Error getting pending requests: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Get upcoming sessions for a mentor
     * Updated to use Users table directly
     */
    public function getUpcomingSessionsForMentor($mentorId)
    {
        try {
            $query = "SELECT 
                        m.mentorship_id as session_id,
                        m.scheduled_date as scheduled_time,
                        m.topic as meeting_link,
                        u.first_name,
                        u.last_name,
                        CONCAT(u.first_name, ' ', u.last_name) as mentee_name,
                        u.profile_picture as profile_picture_url
                      FROM Mentorships m
                      JOIN undergraduate_profiles up ON m.undergraduate_id = up.student_id
                      JOIN users u ON up.user_id = u.user_id
                      WHERE m.alumni_id = ?
                      AND m.status IN ('scheduled', 'awaiting_student_confirmation')
                      AND (m.scheduled_date > NOW() OR m.status = 'awaiting_student_confirmation')
                      ORDER BY m.scheduled_date ASC";
            
            $stmt = $this->db->prepare($query);
            $stmt->execute([$mentorId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            error_log("Error getting upcoming sessions: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Get impact statistics for a mentor
     * Updated to use Users table directly
     */
    public function getMentorStats($mentorId)
    {
        try {
            // Get total unique mentees from completed sessions
            $query = "SELECT COUNT(DISTINCT m.undergraduate_id) as total_mentees
                      FROM Mentorships m
                      WHERE m.alumni_id = ? AND m.status = 'completed'";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$mentorId]);
            $menteesResult = $stmt->fetch(PDO::FETCH_ASSOC);
            
            // Get total sessions (completed)
            $query = "SELECT COUNT(*) as total_sessions
                      FROM Mentorships m
                      WHERE m.alumni_id = ? AND m.status = 'completed'";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$mentorId]);
            $sessionsResult = $stmt->fetch(PDO::FETCH_ASSOC);
            
            // Calculate total hours (assuming 1 hour per session based on duration field)
            $query = "SELECT SUM(duration) / 60 as total_hours
                      FROM Mentorships m
                      WHERE m.alumni_id = ? AND m.status = 'completed'";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$mentorId]);
            $hoursResult = $stmt->fetch(PDO::FETCH_ASSOC);
            
            return [
                'total_mentees' => (int)($menteesResult['total_mentees'] ?? 0),
                'total_sessions' => (int)($sessionsResult['total_sessions'] ?? 0),
                'total_hours' => (int)($hoursResult['total_hours'] ?? 0),
                'completed_sessions' => (int)($sessionsResult['total_sessions'] ?? 0),
                'active_mentees' => (int)($menteesResult['total_mentees'] ?? 0),
                'average_rating' => 0.0  // Will need to implement rating system
            ];
        } catch(PDOException $e) {
            error_log("Error getting mentor stats: " . $e->getMessage());
            return [
                'total_mentees' => 0,
                'total_sessions' => 0,
                'total_hours' => 0,
                'completed_sessions' => 0,
                'active_mentees' => 0,
                'average_rating' => 0.0
            ];
        }
    }
    
    /**
     * Accept a mentorship request
     */
    public function acceptRequest($requestId)
    {
        try {
            $query = "UPDATE Mentorship_Requests 
                      SET status = 'accepted', response_date = NOW()
                      WHERE request_id = ?";
            $stmt = $this->db->prepare($query);
            return $stmt->execute([$requestId]);
        } catch(PDOException $e) {
            error_log("Error accepting request: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Decline a mentorship request
     */
    public function declineRequest($requestId)
    {
        try {
            $query = "UPDATE Mentorship_Requests 
                      SET status = 'rejected', response_date = NOW()
                      WHERE request_id = ?";
            $stmt = $this->db->prepare($query);
            return $stmt->execute([$requestId]);
        } catch(PDOException $e) {
            error_log("Error declining request: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Add a proposed time slot for a mentorship request
     */
    public function addProposedSlot($requestId, $datetime)
    {
        try {
            // Calculate end time (1 hour after start)
            $endDatetime = date('Y-m-d H:i:s', strtotime($datetime . ' +1 hour'));
            
            $query = "INSERT INTO Proposed_Time_Slots (request_id, start_time, end_time, is_booked)
                      VALUES (?, ?, ?, 0)";
            $stmt = $this->db->prepare($query);
            return $stmt->execute([$requestId, $datetime, $endDatetime]);
        } catch(PDOException $e) {
            error_log("Error adding proposed slot: " . $e->getMessage());
            return false;
        }
    }
}
