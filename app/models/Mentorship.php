<?php
/**
 * Mentorship Model
 * Updated to work with normalized database schema
 * Uses: mentor_requests, mentor_sessions, mentors, users, alumni_profiles, undergraduate_profiles
 */
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
     * @param int $studentId The student user ID
     * @param int $mentorId The mentor ID (from mentors table)
     * @param string $message The message from the student
     * @return int|bool The new request ID on success, false on failure
     */
    public function createRequest($studentId, $mentorId, $message = '')
    {
        try {
            $query = "INSERT INTO mentor_requests (student_id, mentor_id, message, status, created_at) 
                      VALUES (?, ?, ?, 'pending', NOW())";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$studentId, $mentorId, $message]);
            return $this->db->lastInsertId();
        } catch (PDOException $e) {
            error_log("Error creating mentorship request: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Get all mentorship requests for a student (undergraduate)
     * 
     * @param int $studentId The student user ID
     * @return array The mentorship requests grouped by status
     */
    public function getRequestsByStudent($studentId)
    {
        $result = [
            'pending' => [],
            'upcoming' => [],
            'completed' => []
        ];

        try {
            // Get pending requests (including awaiting_student_selection)
            $query = "SELECT 
                        mr.request_id, mr.request_id as mentorship_id, mr.status, mr.message, mr.created_at,
                        u.user_id, u.first_name, u.last_name, u.profile_picture as profile_picture_url,
                        CONCAT(u.first_name, ' ', u.last_name) as mentor_name,
                        ap.current_job_title as title, ap.current_company as company
                      FROM mentor_requests mr
                      INNER JOIN mentors m ON mr.mentor_id = m.mentor_id
                      INNER JOIN users u ON m.user_id = u.user_id
                      LEFT JOIN alumni_profiles ap ON u.user_id = ap.user_id
                      WHERE mr.student_id = ?
                      AND mr.status IN ('pending', 'accepted', 'rejected', 'awaiting_student_selection')
                      ORDER BY mr.created_at DESC";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$studentId]);
            $result['pending'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Get upcoming sessions
            $query = "SELECT 
                        mr.request_id, mr.request_id as mentorship_id, mr.status as request_status,
                        ms.session_id, ms.session_date, ms.session_time, ms.duration_hours, ms.status,
                        CONCAT(ms.session_date, ' ', ms.session_time) as scheduled_date,
                        u.user_id, u.first_name, u.last_name, u.profile_picture as profile_picture_url,
                        CONCAT(u.first_name, ' ', u.last_name) as mentor_name,
                        ap.current_job_title as title, ap.current_company as company
                      FROM mentor_sessions ms
                      INNER JOIN mentor_requests mr ON ms.request_id = mr.request_id
                      INNER JOIN mentors m ON mr.mentor_id = m.mentor_id
                      INNER JOIN users u ON m.user_id = u.user_id
                      LEFT JOIN alumni_profiles ap ON u.user_id = ap.user_id
                      WHERE mr.student_id = ?
                      AND ms.status = 'scheduled'
                      AND CONCAT(ms.session_date, ' ', ms.session_time) > NOW()
                      ORDER BY ms.session_date ASC, ms.session_time ASC";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$studentId]);
            $result['upcoming'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Get completed sessions
            $query = "SELECT 
                        mr.request_id, mr.request_id as mentorship_id, mr.status as request_status,
                        ms.session_id, ms.session_date, ms.session_time, ms.duration_hours, ms.status, ms.notes,
                        CONCAT(ms.session_date, ' ', ms.session_time) as scheduled_date,
                        u.user_id, u.first_name, u.last_name, u.profile_picture as profile_picture_url,
                        CONCAT(u.first_name, ' ', u.last_name) as mentor_name,
                        ap.current_job_title as title, ap.current_company as company
                      FROM mentor_sessions ms
                      INNER JOIN mentor_requests mr ON ms.request_id = mr.request_id
                      INNER JOIN mentors m ON mr.mentor_id = m.mentor_id
                      INNER JOIN users u ON m.user_id = u.user_id
                      LEFT JOIN alumni_profiles ap ON u.user_id = ap.user_id
                      WHERE mr.student_id = ?
                      AND (ms.status = 'completed' OR CONCAT(ms.session_date, ' ', ms.session_time) < NOW())
                      ORDER BY ms.session_date DESC";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$studentId]);
            $result['completed'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return $result;
        } catch (PDOException $e) {
            error_log("Error getting mentorship requests: " . $e->getMessage());
            return $result;
        }
    }

    // Alias for backward compatibility
    public function getRequestsByUndergraduate($undergraduateId)
    {
        return $this->getRequestsByStudent($undergraduateId);
    }

    /**
     * Get all mentorship requests for a mentor (alumni)
     * 
     * @param int $userId The mentor's user ID
     * @return array The mentorship requests grouped by status
     */
    public function getRequestsByMentor($userId)
    {
        $result = [
            'requests' => [],
            'upcoming' => [],
            'completed' => []
        ];

        try {
            // Get mentor_id from user_id
            $query = "SELECT mentor_id FROM mentors WHERE user_id = ?";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$userId]);
            $mentor = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$mentor) {
                return $result;
            }

            $mentorId = $mentor['mentor_id'];

            // Get pending requests
            $query = "SELECT 
                        mr.request_id, mr.request_id as mentorship_id, mr.status, mr.message, mr.created_at as request_date,
                        u.user_id, u.first_name, u.last_name, u.profile_picture as profile_picture_url,
                        CONCAT(u.first_name, ' ', u.last_name) as student_name,
                        up.degree_program as program, up.academic_year as year, up.faculty as major
                      FROM mentor_requests mr
                      INNER JOIN users u ON mr.student_id = u.user_id
                      LEFT JOIN undergraduate_profiles up ON u.user_id = up.user_id
                      WHERE mr.mentor_id = ?
                      AND mr.status = 'pending'
                      ORDER BY mr.created_at DESC";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$mentorId]);
            $result['requests'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Get upcoming sessions
            $query = "SELECT 
                        mr.request_id, mr.request_id as mentorship_id, mr.status as request_status,
                        ms.session_id, ms.session_date, ms.session_time, ms.duration_hours, ms.status,
                        CONCAT(ms.session_date, ' ', ms.session_time) as scheduled_date,
                        u.user_id, u.first_name, u.last_name, u.profile_picture as profile_picture_url,
                        CONCAT(u.first_name, ' ', u.last_name) as student_name,
                        up.degree_program as program
                      FROM mentor_sessions ms
                      INNER JOIN mentor_requests mr ON ms.request_id = mr.request_id
                      INNER JOIN users u ON mr.student_id = u.user_id
                      LEFT JOIN undergraduate_profiles up ON u.user_id = up.user_id
                      WHERE mr.mentor_id = ?
                      AND ms.status = 'scheduled'
                      AND CONCAT(ms.session_date, ' ', ms.session_time) > NOW()
                      ORDER BY ms.session_date ASC, ms.session_time ASC";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$mentorId]);
            $result['upcoming'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Get completed sessions
            $query = "SELECT 
                        mr.request_id, mr.request_id as mentorship_id, mr.status as request_status,
                        ms.session_id, ms.session_date, ms.session_time, ms.duration_hours, ms.status, ms.notes,
                        CONCAT(ms.session_date, ' ', ms.session_time) as scheduled_date,
                        u.user_id, u.first_name, u.last_name, u.profile_picture as profile_picture_url,
                        CONCAT(u.first_name, ' ', u.last_name) as student_name,
                        up.degree_program as program
                      FROM mentor_sessions ms
                      INNER JOIN mentor_requests mr ON ms.request_id = mr.request_id
                      INNER JOIN users u ON mr.student_id = u.user_id
                      LEFT JOIN undergraduate_profiles up ON u.user_id = up.user_id
                      WHERE mr.mentor_id = ?
                      AND (ms.status = 'completed' OR CONCAT(ms.session_date, ' ', ms.session_time) < NOW())
                      ORDER BY ms.session_date DESC";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$mentorId]);
            $result['completed'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return $result;
        } catch (PDOException $e) {
            error_log("Error getting mentorship requests: " . $e->getMessage());
            return $result;
        }
    }

    // Alias for backward compatibility
    public function getRequestsByAlumni($alumniId)
    {
        return $this->getRequestsByMentor($alumniId);
    }

    /**
     * Get pending mentorship requests for a mentor
     * 
     * @param int $userId The mentor's user ID
     * @return array The pending requests
     */
    public function getPendingRequestsForMentor($userId)
    {
        try {
            // Get mentor_id from user_id
            $query = "SELECT mentor_id FROM mentors WHERE user_id = ?";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$userId]);
            $mentor = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$mentor) {
                return [];
            }

            $query = "SELECT 
                        mr.request_id, mr.message, mr.created_at as request_date,
                        u.user_id, u.first_name, u.last_name, u.profile_picture as profile_picture_url,
                        CONCAT(u.first_name, ' ', u.last_name) as mentee_name,
                        up.degree_program as major, up.academic_year as year_of_study
                      FROM mentor_requests mr
                      INNER JOIN users u ON mr.student_id = u.user_id
                      LEFT JOIN undergraduate_profiles up ON u.user_id = up.user_id
                      WHERE mr.mentor_id = ?
                      AND mr.status = 'pending'
                      ORDER BY mr.created_at DESC";

            $stmt = $this->db->prepare($query);
            $stmt->execute([$mentor['mentor_id']]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error getting pending requests: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Get upcoming sessions for a mentor
     * 
     * @param int $userId The mentor's user ID
     * @return array The upcoming sessions
     */
    public function getUpcomingSessionsForMentor($userId)
    {
        try {
            $query = "SELECT mentor_id FROM mentors WHERE user_id = ?";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$userId]);
            $mentor = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$mentor) {
                return [];
            }

            $query = "SELECT 
                        ms.session_id, ms.session_date, ms.session_time,
                        CONCAT(ms.session_date, ' ', ms.session_time) as scheduled_time,
                        mr.request_id,
                        u.user_id, u.first_name, u.last_name, u.profile_picture as profile_picture_url,
                        CONCAT(u.first_name, ' ', u.last_name) as mentee_name
                      FROM mentor_sessions ms
                      INNER JOIN mentor_requests mr ON ms.request_id = mr.request_id
                      INNER JOIN users u ON mr.student_id = u.user_id
                      WHERE mr.mentor_id = ?
                      AND ms.status = 'scheduled'
                      AND CONCAT(ms.session_date, ' ', ms.session_time) > NOW()
                      ORDER BY ms.session_date ASC, ms.session_time ASC";

            $stmt = $this->db->prepare($query);
            $stmt->execute([$mentor['mentor_id']]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error getting upcoming sessions: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Get mentor statistics by user ID
     * 
     * @param int $userId The mentor's user ID
     * @return array The mentor stats
     */
    public function getMentorStatsByUserId($userId)
    {
        try {
            $query = "SELECT mentor_id FROM mentors WHERE user_id = ?";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$userId]);
            $mentor = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$mentor) {
                return [
                    'total_mentees' => 0,
                    'total_sessions' => 0,
                    'total_hours' => 0,
                    'average_rating' => 0.0
                ];
            }

            $mentorId = $mentor['mentor_id'];

            // Get total unique mentees
            $query = "SELECT COUNT(DISTINCT mr.student_id) as total_mentees
                      FROM mentor_requests mr
                      WHERE mr.mentor_id = ? AND mr.status IN ('accepted', 'completed')";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$mentorId]);
            $menteesResult = $stmt->fetch(PDO::FETCH_ASSOC);

            // Get total completed sessions
            $query = "SELECT COUNT(*) as total_sessions
                      FROM mentor_sessions ms
                      INNER JOIN mentor_requests mr ON ms.request_id = mr.request_id
                      WHERE mr.mentor_id = ? AND ms.status = 'completed'";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$mentorId]);
            $sessionsResult = $stmt->fetch(PDO::FETCH_ASSOC);

            // Calculate total hours
            $query = "SELECT SUM(ms.duration_hours) as total_hours
                      FROM mentor_sessions ms
                      INNER JOIN mentor_requests mr ON ms.request_id = mr.request_id
                      WHERE mr.mentor_id = ? AND ms.status = 'completed'";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$mentorId]);
            $hoursResult = $stmt->fetch(PDO::FETCH_ASSOC);

            return [
                'total_mentees' => (int) ($menteesResult['total_mentees'] ?? 0),
                'total_sessions' => (int) ($sessionsResult['total_sessions'] ?? 0),
                'total_hours' => round((float) ($hoursResult['total_hours'] ?? 0), 1),
                'average_rating' => 0.0 // Rating system not yet implemented
            ];
        } catch (PDOException $e) {
            error_log("Error getting mentor stats: " . $e->getMessage());
            return [
                'total_mentees' => 0,
                'total_sessions' => 0,
                'total_hours' => 0,
                'average_rating' => 0.0
            ];
        }
    }

    /**
     * Update the status of a mentorship request
     * 
     * @param int $requestId The request ID
     * @param string $status The new status
     * @return bool True on success, false on failure
     */
    public function updateStatus($requestId, $status)
    {
        try {
            $query = "UPDATE mentor_requests SET status = ?, updated_at = NOW() WHERE request_id = ?";
            $stmt = $this->db->prepare($query);
            return $stmt->execute([$status, $requestId]);
        } catch (PDOException $e) {
            error_log("Error updating request status: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Accept a mentorship request
     * 
     * @param int $requestId The request ID
     * @return bool True on success, false on failure
     */
    public function acceptRequest($requestId)
    {
        return $this->updateStatus($requestId, 'accepted');
    }

    /**
     * Decline a mentorship request
     * 
     * @param int $requestId The request ID
     * @return bool True on success, false on failure
     */
    public function declineRequest($requestId)
    {
        return $this->updateStatus($requestId, 'rejected');
    }

    /**
     * Schedule a mentorship session
     * 
     * @param int $requestId The request ID
     * @param string $sessionDate The session date (Y-m-d)
     * @param string $sessionTime The session time (H:i:s)
     * @param float $durationHours The duration in hours
     * @return int|bool The session ID on success, false on failure
     */
    public function scheduleSession($requestId, $sessionDate, $sessionTime, $durationHours = 1.0)
    {
        try {
            $this->db->beginTransaction();

            // Create the session
            $query = "INSERT INTO mentor_sessions (request_id, session_date, session_time, duration_hours, status)
                      VALUES (?, ?, ?, ?, 'scheduled')";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$requestId, $sessionDate, $sessionTime, $durationHours]);
            $sessionId = $this->db->lastInsertId();

            // Update request status
            $query = "UPDATE mentor_requests SET status = 'accepted', updated_at = NOW() WHERE request_id = ?";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$requestId]);

            $this->db->commit();
            return $sessionId;
        } catch (PDOException $e) {
            $this->db->rollBack();
            error_log("Error scheduling session: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Complete a mentorship session
     * 
     * @param int $sessionId The session ID
     * @param string $notes Optional notes
     * @return bool True on success, false on failure
     */
    public function completeSession($sessionId, $notes = '')
    {
        try {
            $query = "UPDATE mentor_sessions SET status = 'completed', notes = ?, updated_at = NOW() WHERE session_id = ?";
            $stmt = $this->db->prepare($query);
            return $stmt->execute([$notes, $sessionId]);
        } catch (PDOException $e) {
            error_log("Error completing session: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Cancel a mentorship session
     * 
     * @param int $sessionId The session ID
     * @return bool True on success, false on failure
     */
    public function cancelSession($sessionId)
    {
        try {
            $query = "UPDATE mentor_sessions SET status = 'cancelled', updated_at = NOW() WHERE session_id = ?";
            $stmt = $this->db->prepare($query);
            return $stmt->execute([$sessionId]);
        } catch (PDOException $e) {
            error_log("Error cancelling session: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Get all available mentors
     * 
     * @param string $searchTerm Search term for filtering
     * @param string $expertise Expertise filter
     * @return array Array of mentor profiles
     */
    public function getAllMentors($searchTerm = '', $expertise = '')
    {
        try {
            $query = "SELECT 
                        u.user_id, u.first_name, u.last_name, u.email, u.profile_picture as profile_picture_url,
                        CONCAT(u.first_name, ' ', u.last_name) as full_name,
                        m.mentor_id, m.expertise_areas, m.max_mentees, m.is_active,
                        ap.current_job_title as current_position, ap.current_company,
                        ap.skills_experience as bio,
                        (SELECT COUNT(*) FROM mentor_sessions ms 
                         INNER JOIN mentor_requests mr ON ms.request_id = mr.request_id
                         WHERE mr.mentor_id = m.mentor_id AND ms.status = 'completed') as total_sessions
                      FROM users u
                      INNER JOIN mentors m ON u.user_id = m.user_id
                      LEFT JOIN alumni_profiles ap ON u.user_id = ap.user_id
                      WHERE u.user_type = 'alumni'
                      AND u.account_status = 'active'
                      AND m.is_active = 1";

            $params = [];

            // Add search filter
            if (!empty($searchTerm)) {
                $query .= " AND (u.first_name LIKE ? OR u.last_name LIKE ? OR ap.current_company LIKE ? OR ap.current_job_title LIKE ?)";
                $searchParam = "%$searchTerm%";
                $params = array_merge($params, [$searchParam, $searchParam, $searchParam, $searchParam]);
            }

            // Add expertise filter
            if (!empty($expertise)) {
                $query .= " AND m.expertise_areas LIKE ?";
                $params[] = "%$expertise%";
            }

            $query .= " ORDER BY total_sessions DESC, u.first_name ASC";

            $stmt = $this->db->prepare($query);
            $stmt->execute($params);
            $mentors = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Process expertise areas to array
            foreach ($mentors as &$mentor) {
                if (!empty($mentor['expertise_areas'])) {
                    $decoded = json_decode($mentor['expertise_areas'], true);
                    $mentor['expertise_array'] = is_array($decoded) ? $decoded : array_map('trim', explode(',', $mentor['expertise_areas']));
                } else {
                    $mentor['expertise_array'] = [];
                }
            }

            return $mentors;
        } catch (PDOException $e) {
            error_log("Error getting all mentors: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Get mentor profile by user ID
     * 
     * @param int $userId The user ID
     * @return array|null The mentor profile or null
     */
    public function getMentorByUserId($userId)
    {
        try {
            $query = "SELECT 
                        m.mentor_id, m.is_active, m.expertise_areas, m.max_mentees,
                        u.user_id, u.first_name, u.last_name, u.email, u.profile_picture,
                        ap.current_job_title, ap.current_company, ap.skills_experience
                      FROM mentors m
                      INNER JOIN users u ON m.user_id = u.user_id
                      LEFT JOIN alumni_profiles ap ON u.user_id = ap.user_id
                      WHERE m.user_id = ?";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$userId]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error getting mentor by user ID: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Get a single mentorship request by ID
     * 
     * @param int $requestId The request ID
     * @return array|bool The request data or false on failure
     */
    public function getRequestById($requestId)
    {
        try {
            $query = "SELECT 
                        mr.*,
                        su.first_name as student_first_name, su.last_name as student_last_name,
                        su.profile_picture as student_picture,
                        mu.first_name as mentor_first_name, mu.last_name as mentor_last_name,
                        mu.profile_picture as mentor_picture,
                        ap.current_job_title, ap.current_company
                      FROM mentor_requests mr
                      INNER JOIN users su ON mr.student_id = su.user_id
                      INNER JOIN mentors m ON mr.mentor_id = m.mentor_id
                      INNER JOIN users mu ON m.user_id = mu.user_id
                      LEFT JOIN alumni_profiles ap ON mu.user_id = ap.user_id
                      WHERE mr.request_id = ?";

            $stmt = $this->db->prepare($query);
            $stmt->execute([$requestId]);
            $request = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($request) {
                $request['student_name'] = $request['student_first_name'] . ' ' . $request['student_last_name'];
                $request['mentor_name'] = $request['mentor_first_name'] . ' ' . $request['mentor_last_name'];
            }

            return $request;
        } catch (PDOException $e) {
            error_log("Error getting mentorship request: " . $e->getMessage());
            return false;
        }
    }

    // Alias for backward compatibility
    public function getMentorshipById($mentorshipId)
    {
        return $this->getRequestById($mentorshipId);
    }

    // =====================================================
    // TIME SLOT MANAGEMENT METHODS
    // =====================================================

    /**
     * Accept a mentorship request and propose time slots
     * 
     * @param int $requestId The request ID
     * @param array $timeSlots Array of datetime strings for proposed slots
     * @return bool True on success, false on failure
     */
    public function acceptRequestWithTimeSlots($requestId, $timeSlots)
    {
        try {
            $this->db->beginTransaction();

            // Get request details for notification
            $request = $this->getRequestById($requestId);
            if (!$request) {
                throw new Exception('Request not found');
            }

            // Insert proposed time slots (exactly 2 slots)
            $slotQuery = "INSERT INTO mentor_proposed_slots (request_id, proposed_datetime, duration_minutes, is_available) 
                          VALUES (?, ?, 60, 1)";
            $slotStmt = $this->db->prepare($slotQuery);

            foreach ($timeSlots as $slot) {
                $slotStmt->execute([$requestId, $slot]);
            }

            // Update request status to awaiting student selection
            $updateQuery = "UPDATE mentor_requests SET status = 'awaiting_student_selection', updated_at = NOW() WHERE request_id = ?";
            $updateStmt = $this->db->prepare($updateQuery);
            $updateStmt->execute([$requestId]);

            // Create notification for the student
            $this->createNotification(
                $request['student_id'],
                $requestId,
                null,
                'time_slots_offered',
                'Time Slots Available!',
                $request['mentor_name'] . ' has accepted your mentorship request and offered time slots. Please select your preferred time.',
                'high'
            );

            $this->db->commit();
            return true;
        } catch (Exception $e) {
            $this->db->rollBack();
            error_log("Error accepting request with time slots: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Get proposed time slots for a mentorship request
     * 
     * @param int $requestId The request ID
     * @return array Array of time slots
     */
    public function getProposedTimeSlots($requestId)
    {
        try {
            $query = "SELECT slot_id, proposed_datetime, duration_minutes, is_selected, is_available
                      FROM mentor_proposed_slots
                      WHERE request_id = ?
                      AND is_available = 1
                      ORDER BY proposed_datetime ASC";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$requestId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error getting proposed time slots: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Check if a time slot is available (no double booking)
     * Uses FOR UPDATE lock when called within a transaction to prevent race conditions
     * 
     * @param int $mentorId The mentor ID
     * @param string $datetime The proposed datetime
     * @param int $duration Duration in minutes
     * @param bool $useLock Whether to use FOR UPDATE lock (use true within transactions)
     * @return bool True if available, false if conflict exists
     */
    public function isSlotAvailable($mentorId, $datetime, $duration = 60, $useLock = false)
    {
        try {
            $endTime = date('Y-m-d H:i:s', strtotime($datetime . " +{$duration} minutes"));

            // Use FOR UPDATE to lock rows and prevent race conditions when within a transaction
            $lockClause = $useLock ? "FOR UPDATE" : "";

            $query = "SELECT COUNT(*) as conflict_count
                      FROM finalized_sessions fs
                      WHERE fs.mentor_id = ?
                      AND fs.status = 'scheduled'
                      AND (
                          (? BETWEEN fs.session_datetime AND DATE_ADD(fs.session_datetime, INTERVAL fs.duration_minutes MINUTE))
                          OR
                          (? BETWEEN fs.session_datetime AND DATE_ADD(fs.session_datetime, INTERVAL fs.duration_minutes MINUTE))
                          OR
                          (fs.session_datetime BETWEEN ? AND ?)
                      )
                      $lockClause";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$mentorId, $datetime, $endTime, $datetime, $endTime]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            return ($result['conflict_count'] == 0);
        } catch (PDOException $e) {
            error_log("Error checking slot availability: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Student selects a time slot and finalizes the session
     * Uses database transaction with row-level locking to prevent double-booking race conditions
     * 
     * @param int $requestId The request ID
     * @param int $slotId The selected slot ID
     * @param int $studentId The student user ID (for verification)
     * @return array Result with success status and message
     */
    public function selectTimeSlotAndFinalize($requestId, $slotId, $studentId)
    {
        try {
            $this->db->beginTransaction();

            // Verify the request belongs to this student
            $request = $this->getRequestById($requestId);
            if (!$request || $request['student_id'] != $studentId) {
                throw new Exception('Unauthorized access to this request');
            }

            // Verify request status
            if ($request['status'] !== 'awaiting_student_selection') {
                throw new Exception('This request is not awaiting time slot selection');
            }

            // Get the selected slot WITH LOCK to prevent race conditions
            // FOR UPDATE ensures no other transaction can read/modify this row until we commit
            $slotQuery = "SELECT * FROM mentor_proposed_slots WHERE slot_id = ? AND request_id = ? FOR UPDATE";
            $slotStmt = $this->db->prepare($slotQuery);
            $slotStmt->execute([$slotId, $requestId]);
            $slot = $slotStmt->fetch(PDO::FETCH_ASSOC);

            if (!$slot) {
                throw new Exception('Invalid time slot selected');
            }

            if (!$slot['is_available']) {
                throw new Exception('This time slot is no longer available');
            }

            // Get mentor_id from request
            $mentorId = $request['mentor_id'];

            // ========================================================
            // CRITICAL: Final double-booking check with row locking
            // This is the last line of defense against race conditions
            // ========================================================
            // Check if this exact time slot is already booked by ANOTHER student
            // Using FOR UPDATE lock to prevent two students from confirming simultaneously
            if (!$this->isSlotAvailable($mentorId, $slot['proposed_datetime'], $slot['duration_minutes'], true)) {
                $this->db->rollBack();
                return [
                    'success' => false,
                    'message' => 'Sorry! This time slot was just booked by another student. Please select a different time or request new slots from your mentor.'
                ];
            }

            // Mark slot as selected and no longer available
            $updateSlotQuery = "UPDATE mentor_proposed_slots SET is_selected = 1, is_available = 0 WHERE slot_id = ?";
            $updateSlotStmt = $this->db->prepare($updateSlotQuery);
            $updateSlotStmt->execute([$slotId]);

            // Mark other slots for this request as unavailable
            $markUnavailableQuery = "UPDATE mentor_proposed_slots SET is_available = 0 WHERE request_id = ? AND slot_id != ?";
            $markUnavailableStmt = $this->db->prepare($markUnavailableQuery);
            $markUnavailableStmt->execute([$requestId, $slotId]);

            // Also mark slots from OTHER requests that have the same datetime as unavailable
            // This prevents the same time from being offered to multiple students
            $markOtherSlotsQuery = "UPDATE mentor_proposed_slots mps
                                    INNER JOIN mentor_requests mr ON mps.request_id = mr.request_id
                                    SET mps.is_available = 0
                                    WHERE mr.mentor_id = ?
                                    AND mps.proposed_datetime = ?
                                    AND mps.slot_id != ?";
            $markOtherSlotsStmt = $this->db->prepare($markOtherSlotsQuery);
            $markOtherSlotsStmt->execute([$mentorId, $slot['proposed_datetime'], $slotId]);

            // Create finalized session
            $sessionQuery = "INSERT INTO finalized_sessions 
                             (request_id, slot_id, mentor_id, student_id, session_datetime, duration_minutes, status)
                             VALUES (?, ?, ?, ?, ?, ?, 'scheduled')";
            $sessionStmt = $this->db->prepare($sessionQuery);
            $sessionStmt->execute([
                $requestId,
                $slotId,
                $mentorId,
                $studentId,
                $slot['proposed_datetime'],
                $slot['duration_minutes']
            ]);
            $sessionId = $this->db->lastInsertId();

            // Update request status to scheduled
            $updateRequestQuery = "UPDATE mentor_requests SET status = 'scheduled', updated_at = NOW() WHERE request_id = ?";
            $updateRequestStmt = $this->db->prepare($updateRequestQuery);
            $updateRequestStmt->execute([$requestId]);

            // Format datetime for notifications
            $formattedDate = date('l, F j, Y \a\t g:i A', strtotime($slot['proposed_datetime']));

            // Notification for the student
            $this->createNotification(
                $studentId,
                $requestId,
                $sessionId,
                'session_confirmed',
                'Session Confirmed! 🎉',
                'Your mentorship session with ' . $request['mentor_name'] . ' is locked for ' . $formattedDate . '.',
                'high'
            );

            // Get mentor's user_id for notification
            $mentorUserQuery = "SELECT user_id FROM mentors WHERE mentor_id = ?";
            $mentorUserStmt = $this->db->prepare($mentorUserQuery);
            $mentorUserStmt->execute([$mentorId]);
            $mentorUser = $mentorUserStmt->fetch(PDO::FETCH_ASSOC);

            if ($mentorUser) {
                $this->createNotification(
                    $mentorUser['user_id'],
                    $requestId,
                    $sessionId,
                    'session_confirmed',
                    'Session Confirmed! 🎉',
                    $request['student_name'] . ' has confirmed your mentorship session for ' . $formattedDate . '.',
                    'high'
                );
            }

            $this->db->commit();

            return [
                'success' => true,
                'session_id' => $sessionId,
                'message' => 'Session successfully scheduled for ' . $formattedDate
            ];
        } catch (Exception $e) {
            $this->db->rollBack();
            error_log("Error selecting time slot: " . $e->getMessage());
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    // =====================================================
    // FINALIZED SESSIONS METHODS
    // =====================================================

    /**
     * Get all finalized upcoming sessions for a student
     * 
     * @param int $studentId The student user ID
     * @return array Array of upcoming sessions
     */
    public function getFinalizedSessionsForStudent($studentId)
    {
        try {
            $query = "SELECT 
                        fs.session_id, fs.session_datetime, fs.duration_minutes, fs.meeting_link, fs.status,
                        fs.request_id, fs.mentor_id,
                        u.first_name as mentor_first_name, u.last_name as mentor_last_name,
                        u.profile_picture as mentor_picture,
                        CONCAT(u.first_name, ' ', u.last_name) as mentor_name,
                        ap.current_job_title as mentor_title, ap.current_company as mentor_company
                      FROM finalized_sessions fs
                      INNER JOIN mentors m ON fs.mentor_id = m.mentor_id
                      INNER JOIN users u ON m.user_id = u.user_id
                      LEFT JOIN alumni_profiles ap ON u.user_id = ap.user_id
                      WHERE fs.student_id = ?
                      AND fs.status = 'scheduled'
                      AND fs.session_datetime > NOW()
                      ORDER BY fs.session_datetime ASC";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$studentId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error getting finalized sessions for student: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Get all finalized upcoming sessions for a mentor
     * 
     * @param int $userId The mentor's user ID
     * @return array Array of upcoming sessions
     */
    public function getFinalizedSessionsForMentor($userId)
    {
        try {
            // Get mentor_id from user_id
            $query = "SELECT mentor_id FROM mentors WHERE user_id = ?";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$userId]);
            $mentor = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$mentor) {
                return [];
            }

            $query = "SELECT 
                        fs.session_id, fs.session_datetime, fs.duration_minutes, fs.meeting_link, fs.status,
                        fs.request_id, fs.student_id,
                        u.first_name as student_first_name, u.last_name as student_last_name,
                        u.profile_picture as student_picture,
                        CONCAT(u.first_name, ' ', u.last_name) as student_name,
                        up.degree_program, up.academic_year
                      FROM finalized_sessions fs
                      INNER JOIN users u ON fs.student_id = u.user_id
                      LEFT JOIN undergraduate_profiles up ON u.user_id = up.user_id
                      WHERE fs.mentor_id = ?
                      AND fs.status = 'scheduled'
                      AND fs.session_datetime > NOW()
                      ORDER BY fs.session_datetime ASC";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$mentor['mentor_id']]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error getting finalized sessions for mentor: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Get session by ID
     * 
     * @param int $sessionId The session ID
     * @return array|null Session data or null
     */
    public function getSessionById($sessionId)
    {
        try {
            $query = "SELECT fs.*, 
                        su.first_name as student_first_name, su.last_name as student_last_name,
                        mu.first_name as mentor_first_name, mu.last_name as mentor_last_name
                      FROM finalized_sessions fs
                      INNER JOIN users su ON fs.student_id = su.user_id
                      INNER JOIN mentors m ON fs.mentor_id = m.mentor_id
                      INNER JOIN users mu ON m.user_id = mu.user_id
                      WHERE fs.session_id = ?";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$sessionId]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error getting session: " . $e->getMessage());
            return null;
        }
    }

    // =====================================================
    // NOTIFICATION METHODS
    // =====================================================

    /**
     * Create a notification
     * 
     * @param int $userId The user ID to notify
     * @param int|null $requestId The related request ID
     * @param int|null $sessionId The related session ID
     * @param string $type The notification type
     * @param string $title The notification title
     * @param string $message The notification message
     * @param string $priority The priority level
     * @return int|bool The notification ID or false on failure
     */
    public function createNotification($userId, $requestId, $sessionId, $type, $title, $message, $priority = 'normal')
    {
        try {
            $query = "INSERT INTO mentorship_notifications 
                      (user_id, request_id, session_id, notification_type, title, message, priority)
                      VALUES (?, ?, ?, ?, ?, ?, ?)";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$userId, $requestId, $sessionId, $type, $title, $message, $priority]);
            return $this->db->lastInsertId();
        } catch (PDOException $e) {
            error_log("Error creating notification: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Get unread notifications for a user
     * 
     * @param int $userId The user ID
     * @param int $limit Maximum notifications to retrieve
     * @return array Array of notifications
     */
    public function getUnreadNotifications($userId, $limit = 10)
    {
        try {
            $query = "SELECT * FROM mentorship_notifications 
                      WHERE user_id = ? AND is_read = 0 
                      ORDER BY priority DESC, created_at DESC 
                      LIMIT ?";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$userId, $limit]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error getting notifications: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Mark a notification as read
     * 
     * @param int $notificationId The notification ID
     * @return bool True on success
     */
    public function markNotificationRead($notificationId)
    {
        try {
            $query = "UPDATE mentorship_notifications SET is_read = 1, read_at = NOW() WHERE notification_id = ?";
            $stmt = $this->db->prepare($query);
            return $stmt->execute([$notificationId]);
        } catch (PDOException $e) {
            error_log("Error marking notification as read: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Get all notifications for a user
     * 
     * @param int $userId The user ID
     * @param int $limit Maximum notifications to retrieve
     * @return array Array of notifications
     */
    public function getAllNotifications($userId, $limit = 50)
    {
        try {
            $query = "SELECT * FROM mentorship_notifications 
                      WHERE user_id = ? 
                      ORDER BY created_at DESC 
                      LIMIT ?";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$userId, $limit]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error getting all notifications: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Count unread notifications for a user
     * 
     * @param int $userId The user ID
     * @return int Number of unread notifications
     */
    public function countUnreadNotifications($userId)
    {
        try {
            $query = "SELECT COUNT(*) as count FROM mentorship_notifications WHERE user_id = ? AND is_read = 0";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$userId]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return (int) $result['count'];
        } catch (PDOException $e) {
            error_log("Error counting notifications: " . $e->getMessage());
            return 0;
        }
    }

    /**
     * Get requests awaiting student selection (for undergraduates)
     * 
     * @param int $studentId The student user ID
     * @return array Array of requests with proposed time slots
     */
    public function getRequestsAwaitingSelection($studentId)
    {
        try {
            $query = "SELECT 
                        mr.request_id, mr.request_id as mentorship_id, mr.status, mr.message, mr.created_at,
                        u.user_id, u.first_name, u.last_name, u.profile_picture as profile_picture_url,
                        CONCAT(u.first_name, ' ', u.last_name) as mentor_name,
                        ap.current_job_title as title, ap.current_company as company
                      FROM mentor_requests mr
                      INNER JOIN mentors m ON mr.mentor_id = m.mentor_id
                      INNER JOIN users u ON m.user_id = u.user_id
                      LEFT JOIN alumni_profiles ap ON u.user_id = ap.user_id
                      WHERE mr.student_id = ?
                      AND mr.status = 'awaiting_student_selection'
                      ORDER BY mr.created_at DESC";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$studentId]);
            $requests = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Add time slots to each request
            foreach ($requests as &$request) {
                $request['time_slots'] = $this->getProposedTimeSlots($request['request_id']);
            }

            return $requests;
        } catch (PDOException $e) {
            error_log("Error getting requests awaiting selection: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Get requests awaiting student confirmation (alias for backward compatibility)
     */
    public function getTimeSlots($mentorshipId)
    {
        return $this->getProposedTimeSlots($mentorshipId);
    }

    /**
     * Add proposed time slots (used by old API)
     */
    public function addProposedSlot($requestId, $datetime)
    {
        try {
            $query = "INSERT INTO mentor_proposed_slots (request_id, proposed_datetime, duration_minutes, is_available) 
                      VALUES (?, ?, 60, 1)";
            $stmt = $this->db->prepare($query);
            return $stmt->execute([$requestId, $datetime]);
        } catch (PDOException $e) {
            error_log("Error adding proposed slot: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Add time slots (legacy method)
     */
    public function addTimeSlots($mentorshipId, $slots)
    {
        try {
            foreach ($slots as $slot) {
                $datetime = $slot['start'] ?? $slot;
                $this->addProposedSlot($mentorshipId, $datetime);
            }
            return true;
        } catch (Exception $e) {
            error_log("Error adding time slots: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Select a time slot (legacy method, redirects to new implementation)
     */
    public function selectTimeSlot($mentorshipId, $slotId)
    {
        // This method is called from the old controller
        // We need to get the student_id from the session or request
        try {
            $request = $this->getRequestById($mentorshipId);
            if (!$request) {
                return false;
            }
            $result = $this->selectTimeSlotAndFinalize($mentorshipId, $slotId, $request['student_id']);
            return $result['success'];
        } catch (Exception $e) {
            error_log("Error in selectTimeSlot: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Get mentor by mentor_id with user details
     * 
     * @param int $mentorId The mentor ID
     * @return array|bool Mentor data or false on failure
     */
    public function getMentorById($mentorId)
    {
        try {
            $query = "SELECT 
                        m.mentor_id, m.user_id, m.is_active, m.expertise_areas, m.max_mentees,
                        u.first_name, u.last_name, u.email, u.profile_picture,
                        CONCAT(u.first_name, ' ', u.last_name) as full_name,
                        ap.current_job_title, ap.current_company, ap.skills_experience,
                        ap.linkedin_url, ap.github_url, ap.portfolio_url,
                        ap.university_name, ap.degree_program, ap.graduation_year
                      FROM mentors m
                      INNER JOIN users u ON m.user_id = u.user_id
                      LEFT JOIN alumni_profiles ap ON u.user_id = ap.user_id
                      WHERE m.mentor_id = ?";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$mentorId]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error getting mentor by ID: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Get mentor statistics
     * 
     * @param int $mentorId The mentor ID
     * @return array Statistics including total sessions, completed sessions, avg rating
     */
    public function getMentorStats($mentorId)
    {
        try {
            // Get total requests and completed sessions
            $query = "SELECT 
                        COUNT(DISTINCT mr.request_id) as total_requests,
                        COUNT(DISTINCT CASE WHEN fs.status = 'completed' THEN fs.session_id END) as completed_sessions,
                        COUNT(DISTINCT CASE WHEN mr.status = 'pending' THEN mr.request_id END) as pending_requests
                      FROM mentor_requests mr
                      LEFT JOIN finalized_sessions fs ON mr.request_id = fs.request_id
                      WHERE mr.mentor_id = ?";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$mentorId]);
            $stats = $stmt->fetch(PDO::FETCH_ASSOC);

            // Get active mentees count (students with accepted or in-progress sessions)
            $query = "SELECT COUNT(DISTINCT mr.student_id) as active_mentees
                      FROM mentor_requests mr
                      WHERE mr.mentor_id = ? 
                      AND mr.status IN ('accepted', 'awaiting_student_selection', 'confirmed')";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$mentorId]);
            $activeCount = $stmt->fetch(PDO::FETCH_ASSOC);
            $stats['active_mentees'] = $activeCount['active_mentees'] ?? 0;

            return $stats;
        } catch (PDOException $e) {
            error_log("Error getting mentor stats: " . $e->getMessage());
            return [
                'total_requests' => 0,
                'completed_sessions' => 0,
                'pending_requests' => 0,
                'active_mentees' => 0
            ];
        }
    }

    /**
     * Check if student has an active request to a specific mentor
     * 
     * @param int $studentId The student user ID
     * @param int $mentorId The mentor ID
     * @return bool True if there's an active request, false otherwise
     */
    public function hasActiveRequest($studentId, $mentorId)
    {
        try {
            $query = "SELECT COUNT(*) as count 
                      FROM mentor_requests 
                      WHERE student_id = ? 
                      AND mentor_id = ? 
                      AND status IN ('pending', 'accepted', 'awaiting_student_selection', 'confirmed')";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$studentId, $mentorId]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return ($result['count'] > 0);
        } catch (PDOException $e) {
            error_log("Error checking active request: " . $e->getMessage());
            return false;
        }
    }
}

