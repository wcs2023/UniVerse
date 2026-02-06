<?php
/**
 * MentorshipBase - Shared Database Connection & Helper Methods
 * =============================================================
 * Base class for all mentorship-related models.
 * Provides shared database connection and common helper methods.
 */
class MentorshipBase
{
    protected $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    /**
     * Get mentor_id from user_id
     * 
     * @param int $userId The user ID
     * @return int|null The mentor_id or null
     */
    public function getMentorIdByUserId($userId)
    {
        try {
            $query = "SELECT mentor_id FROM mentors WHERE user_id = ?";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$userId]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result ? $result['mentor_id'] : null;
        } catch (PDOException $e) {
            return null;
        }
    }

    /**
     * Get user_id from mentor_id
     * 
     * @param int $mentorId The mentor ID
     * @return int|null The user_id or null
     */
    public function getMentorUserId($mentorId)
    {
        try {
            $query = "SELECT user_id FROM mentors WHERE mentor_id = ?";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$mentorId]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result ? $result['user_id'] : null;
        } catch (PDOException $e) {
            return null;
        }
    }

    /**
     * Get user's full name
     * 
     * @param int $userId The user ID
     * @return string The user's full name
     */
    public function getUserName($userId)
    {
        try {
            $query = "SELECT CONCAT(first_name, ' ', last_name) as name FROM users WHERE user_id = ?";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$userId]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result ? $result['name'] : 'Unknown User';
        } catch (PDOException $e) {
            return 'Unknown User';
        }
    }

    /**
     * Generate Jitsi meeting link
     * 
     * @param int $mentorId The mentor ID
     * @param int $slotId The slot ID
     * @return string The Jitsi meeting URL
     */
    protected function generateJitsiLink($mentorId, $slotId)
    {
        $uniqueId = $mentorId . '_' . $slotId . '_' . time();
        $roomName = 'UniVerse_Mentorship_' . $uniqueId;
        return 'https://meet.jit.si/' . $roomName;
    }
}
