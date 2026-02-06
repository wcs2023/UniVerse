<?php
/**
 * MentorModel - Mentor Profile & Discovery
 * ==========================================
 * Handles mentor profiles, search, listing, and statistics.
 * 
 * Tables: mentors, users, alumni_profiles
 */
require_once __DIR__ . '/MentorshipBase.php';

class MentorModel extends MentorshipBase
{
    /**
     * Ensure a mentor profile exists for an alumni user
     * Auto-creates one if not exists
     * 
     * @param int $userId The alumni's user ID
     * @return int|null The mentor_id
     */
    public function ensureMentorExists($userId)
    {
        try {
            $mentorId = $this->getMentorIdByUserId($userId);
            if ($mentorId) {
                return $mentorId;
            }
            
            $query = "INSERT INTO mentors (user_id, is_active, expertise_areas) VALUES (?, 1, NULL)";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$userId]);
            
            return $this->db->lastInsertId();
        } catch (PDOException $e) {
            error_log("Error ensuring mentor exists: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Get mentor status (exists and is_active)
     * 
     * @param int $userId The alumni's user ID
     * @return array|null Array with mentor_id and is_active, or null if not found
     */
    public function getMentorStatus($userId)
    {
        try {
            $query = "SELECT mentor_id, is_active FROM mentors WHERE user_id = ?";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$userId]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result ?: null;
        } catch (PDOException $e) {
            error_log("Error getting mentor status: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Get all available mentors
     * 
     * @param string $searchTerm Search term
     * @param string $expertise Expertise filter
     * @return array Mentors
     */
    public function getAllMentors($searchTerm = '', $expertise = '')
    {
        try {
            $query = "SELECT 
                        u.user_id, u.first_name, u.last_name, u.email, u.profile_picture as profile_picture_url,
                        m.mentor_id, m.expertise_areas, m.is_active,
                        ap.current_job_title as title, ap.current_company as company, ap.skills_experience as bio,
                        ap.linkedin_url, ap.github_url,
                        (SELECT COUNT(*) FROM mentorship_bookings mb WHERE mb.mentor_id = m.mentor_id AND mb.status = 'completed') as total_sessions,
                        (SELECT AVG(rating) FROM mentorship_feedback mf WHERE mf.mentor_id = m.mentor_id) as avg_rating,
                        (SELECT COUNT(*) FROM mentor_availability_slots mas WHERE mas.mentor_id = m.mentor_id AND mas.is_booked = 0 AND mas.slot_datetime > NOW()) as available_slots
                      FROM mentors m
                      INNER JOIN users u ON m.user_id = u.user_id
                      LEFT JOIN alumni_profiles ap ON u.user_id = ap.user_id
                      WHERE m.is_active = 1";

            $params = [];

            if (!empty($searchTerm)) {
                $query .= " AND (u.first_name LIKE ? OR u.last_name LIKE ? OR ap.current_job_title LIKE ? OR ap.current_company LIKE ?)";
                $searchParam = "%$searchTerm%";
                $params = array_merge($params, [$searchParam, $searchParam, $searchParam, $searchParam]);
            }

            if (!empty($expertise)) {
                $query .= " AND m.expertise_areas LIKE ?";
                $params[] = "%$expertise%";
            }

            $query .= " ORDER BY available_slots DESC, total_sessions DESC, u.first_name ASC";

            $stmt = $this->db->prepare($query);
            $stmt->execute($params);
            $mentors = $stmt->fetchAll(PDO::FETCH_ASSOC);

            foreach ($mentors as &$mentor) {
                $mentor['expertise_areas'] = $mentor['expertise_areas'] 
                    ? array_map('trim', explode(',', $mentor['expertise_areas'])) 
                    : [];
                $mentor['avg_rating'] = round((float)($mentor['avg_rating'] ?? 0), 1);
            }

            return $mentors;
        } catch (PDOException $e) {
            error_log("Error getting all mentors: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Get mentor profile by mentor ID
     * 
     * @param int $mentorId The mentor ID
     * @return array|null Mentor profile
     */
    public function getMentorById($mentorId)
    {
        try {
            $query = "SELECT 
                        u.user_id, u.first_name, u.last_name, u.email, u.profile_picture as profile_picture_url,
                        m.mentor_id, m.expertise_areas, m.is_active,
                        ap.current_job_title as title, ap.current_company as company, ap.skills_experience as bio,
                        ap.linkedin_url, ap.github_url
                      FROM mentors m
                      INNER JOIN users u ON m.user_id = u.user_id
                      LEFT JOIN alumni_profiles ap ON u.user_id = ap.user_id
                      WHERE m.mentor_id = ?";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$mentorId]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error getting mentor by ID: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Get mentor profile by user ID
     * 
     * @param int $userId The user ID
     * @return array|null Mentor profile
     */
    public function getMentorByUserId($userId)
    {
        try {
            $query = "SELECT 
                        u.user_id, u.first_name, u.last_name, u.email, u.profile_picture,
                        CONCAT(u.first_name, ' ', u.last_name) as full_name,
                        m.mentor_id, m.expertise_areas, m.is_active, m.max_mentees,
                        ap.current_job_title, ap.current_company, ap.skills_experience,
                        ap.linkedin_url, ap.github_url
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
     * Get available mentors with their slot counts
     * For the "Explore Mentors" page
     * 
     * @param string $searchTerm Search term
     * @param string $industry Industry filter
     * @param string $expertise Expertise filter
     * @return array List of mentors with available slots
     */
    public function getAvailableMentorsWithSlots($searchTerm = '', $industry = '', $expertise = '')
    {
        try {
            $query = "SELECT 
                        u.user_id, 
                        CONCAT(u.first_name, ' ', u.last_name) as name, 
                        u.email, 
                        u.profile_picture,
                        m.mentor_id, 
                        m.expertise_areas, 
                        m.is_active,
                        ap.current_job_title, 
                        ap.current_company, 
                        ap.skills_experience,
                        ap.linkedin_url, 
                        ap.github_url,
                        (SELECT COUNT(*) FROM mentorship_bookings mb WHERE mb.mentor_id = m.mentor_id AND mb.status = 'completed') as total_sessions,
                        (SELECT AVG(rating) FROM mentorship_feedback mf WHERE mf.mentor_id = m.mentor_id) as rating,
                        (SELECT COUNT(*) FROM mentorship_feedback mf WHERE mf.mentor_id = m.mentor_id) as review_count,
                        (SELECT COUNT(*) FROM mentor_availability_slots mas 
                         WHERE mas.mentor_id = m.mentor_id AND mas.is_booked = 0 AND mas.slot_datetime > NOW()) as available_slots
                      FROM mentors m
                      INNER JOIN users u ON m.user_id = u.user_id
                      LEFT JOIN alumni_profiles ap ON u.user_id = ap.user_id
                      WHERE m.is_active = 1
                      HAVING available_slots > 0";

            $params = [];

            if (!empty($searchTerm)) {
                $query = str_replace("WHERE m.is_active = 1", 
                    "WHERE m.is_active = 1 
                     AND (u.first_name LIKE ? OR u.last_name LIKE ? OR ap.current_job_title LIKE ? OR ap.current_company LIKE ? OR ap.skills_experience LIKE ?)",
                    $query);
                $searchParam = "%$searchTerm%";
                $params = [$searchParam, $searchParam, $searchParam, $searchParam, $searchParam];
            }

            if (!empty($expertise)) {
                $query .= " AND m.expertise_areas LIKE ?";
                $params[] = "%$expertise%";
            }

            if (!empty($industry)) {
                $query .= " AND ap.current_company LIKE ?";
                $params[] = "%$industry%";
            }

            $query .= " ORDER BY available_slots DESC, rating DESC, total_sessions DESC";

            $stmt = $this->db->prepare($query);
            $stmt->execute($params);
            $mentors = $stmt->fetchAll(PDO::FETCH_ASSOC);

            foreach ($mentors as &$mentor) {
                $mentor['rating'] = round((float)($mentor['rating'] ?? 0), 1);
            }

            return $mentors;
        } catch (PDOException $e) {
            error_log("Error getting available mentors with slots: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Get mentor statistics
     * 
     * @param int $userId The mentor's user ID
     * @return array Stats
     */
    public function getMentorStats($userId)
    {
        try {
            $mentorId = $this->getMentorIdByUserId($userId);
            if (!$mentorId) {
                return [
                    'total_sessions' => 0, 
                    'completed_sessions' => 0,
                    'total_mentees' => 0, 
                    'average_rating' => 0,
                    'review_count' => 0
                ];
            }

            $query = "SELECT COUNT(*) as total FROM mentorship_bookings WHERE mentor_id = ?";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$mentorId]);
            $totalSessions = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

            $query = "SELECT COUNT(*) as total FROM mentorship_bookings WHERE mentor_id = ? AND status = 'completed'";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$mentorId]);
            $completedSessions = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

            $query = "SELECT COUNT(DISTINCT student_id) as total FROM mentorship_bookings WHERE mentor_id = ?";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$mentorId]);
            $mentees = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

            $rating = $this->getMentorRating($mentorId);

            return [
                'total_sessions' => (int)$totalSessions,
                'completed_sessions' => (int)$completedSessions,
                'total_mentees' => (int)$mentees,
                'average_rating' => $rating['average'],
                'review_count' => $rating['count']
            ];
        } catch (PDOException $e) {
            error_log("Error getting mentor stats: " . $e->getMessage());
            return [
                'total_sessions' => 0, 
                'completed_sessions' => 0,
                'total_mentees' => 0, 
                'average_rating' => 0,
                'review_count' => 0
            ];
        }
    }

    /**
     * Get mentor's average rating
     * 
     * @param int $mentorId The mentor ID
     * @return array Average rating and count
     */
    public function getMentorRating($mentorId)
    {
        try {
            $query = "SELECT AVG(rating) as avg_rating, COUNT(*) as review_count 
                      FROM mentorship_feedback WHERE mentor_id = ?";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$mentorId]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return [
                'average' => round((float)($result['avg_rating'] ?? 0), 1),
                'count' => (int)($result['review_count'] ?? 0)
            ];
        } catch (PDOException $e) {
            return ['average' => 0, 'count' => 0];
        }
    }

    /**
     * Check if student has active request with mentor
     * In the new instant-booking system, this always returns false
     * 
     * @param int $studentId The student's user ID
     * @param int $mentorId The mentor ID
     * @return bool Always false in new system
     */
    public function hasActiveRequest($studentId, $mentorId)
    {
        return false;
    }
}
