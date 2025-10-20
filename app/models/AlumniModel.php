<?php
class AlumniModel extends Model
{
    protected $db;
    
    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }
    
    /**
     * Get an alumni by ID
     * 
     * @param int $alumni_id The alumni ID
     * @return array|bool The alumni data or false on failure
     */
    public function getAlumniById($alumni_id)
    {
        try {
            // Get alumni basic information
            $query = "SELECT * FROM Alumni WHERE alumni_id = ?";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$alumni_id]);
            $alumni = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$alumni) {
                return false;
            }
            
            // Get alumni experience
            $query = "SELECT * FROM AlumniExperience WHERE alumni_id = ? ORDER BY start_date DESC";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$alumni_id]);
            $alumni['experience'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // Get alumni skills
            $query = "SELECT skill_name FROM AlumniSkills WHERE alumni_id = ? ORDER BY skill_name";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$alumni_id]);
            $skills = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            $alumni['skills'] = array_map(function($skill) {
                return $skill['skill_name'];
            }, $skills);
            
            return $alumni;
        } catch(PDOException $e) {
            error_log("Error getting alumni: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Get an alumni by user ID
     * 
     * @param int $user_id The user ID
     * @return array|bool The alumni data or false on failure
     */
    public function getAlumniByUserId($user_id)
    {
        try {
            // Get alumni basic information
            $query = "SELECT * FROM Alumni WHERE user_id = ?";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$user_id]);
            $alumni = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$alumni) {
                return false;
            }
            
            return $alumni;
        } catch(PDOException $e) {
            error_log("Error getting alumni by user_id: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Get all alumni who are available for mentorship
     * 
     * @return array The list of available alumni
     */
    public function getAvailableAlumni()
    {
        try {
            $query = "SELECT a.*, 
                     (SELECT COUNT(*) FROM Mentorships m WHERE m.alumni_id = a.alumni_id AND m.status = 'completed') as completed_sessions
                     FROM Alumni a 
                     WHERE a.mentorship_status = 'available'
                     ORDER BY completed_sessions DESC, a.first_name ASC";
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            $alumni_list = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // Get skills for each alumni
            foreach ($alumni_list as &$alumni) {
                $query = "SELECT skill_name FROM AlumniSkills WHERE alumni_id = ? ORDER BY skill_name LIMIT 5";
                $stmt = $this->db->prepare($query);
                $stmt->execute([$alumni['alumni_id']]);
                $skills = $stmt->fetchAll(PDO::FETCH_ASSOC);
                
                $alumni['skills'] = array_map(function($skill) {
                    return $skill['skill_name'];
                }, $skills);
            }
            
            return $alumni_list;
        } catch(PDOException $e) {
            error_log("Error getting available alumni: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Get user by ID from Users table
     * 
     * @param int $userId The user ID
     * @return object|bool The user data or false on failure
     */
    public function getUserById($userId)
    {
        try {
            $query = "SELECT u.*, mp.current_role, mp.company, mp.linkedin_url, mp.short_bio, mp.available_for_mentorship
                      FROM Users u
                      LEFT JOIN Mentor_Profiles mp ON u.user_id = mp.user_id
                      WHERE u.user_id = ?";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$userId]);
            return $stmt->fetch(PDO::FETCH_OBJ);
        } catch(PDOException $e) {
            error_log("Error getting user: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Update user profile
     * 
     * @param int $userId The user ID
     * @param array $data The profile data to update
     * @return bool Success or failure
     */
    public function updateProfile($userId, $data)
    {
        try {
            $this->db->beginTransaction();
            
            // Update Users table
            $query = "UPDATE Users SET 
                      full_name = ?,
                      email = ?
                      WHERE user_id = ?";
            $stmt = $this->db->prepare($query);
            $stmt->execute([
                $data['full_name'],
                $data['email'],
                $userId
            ]);
            
            // Check if Mentor_Profiles record exists
            $query = "SELECT COUNT(*) as count FROM Mentor_Profiles WHERE user_id = ?";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$userId]);
            $result = $stmt->fetch(PDO::FETCH_OBJ);
            
            if ($result->count > 0) {
                // Update existing record
                $query = "UPDATE Mentor_Profiles SET
                          current_role = ?,
                          company = ?,
                          linkedin_url = ?,
                          short_bio = ?,
                          available_for_mentorship = ?
                          WHERE user_id = ?";
                $stmt = $this->db->prepare($query);
                $stmt->execute([
                    $data['current_role'],
                    $data['company'],
                    $data['linkedin_url'],
                    $data['short_bio'],
                    $data['available_for_mentorship'],
                    $userId
                ]);
            } else {
                // Insert new record
                $query = "INSERT INTO Mentor_Profiles 
                          (user_id, current_role, company, linkedin_url, short_bio, available_for_mentorship)
                          VALUES (?, ?, ?, ?, ?, ?)";
                $stmt = $this->db->prepare($query);
                $stmt->execute([
                    $userId,
                    $data['current_role'],
                    $data['company'],
                    $data['linkedin_url'],
                    $data['short_bio'],
                    $data['available_for_mentorship']
                ]);
            }
            
            $this->db->commit();
            return true;
        } catch(PDOException $e) {
            $this->db->rollBack();
            error_log("Error updating profile: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Update user password
     * 
     * @param int $userId The user ID
     * @param string $passwordHash The new password hash
     * @return bool Success or failure
     */
    public function updatePassword($userId, $passwordHash)
    {
        try {
            $query = "UPDATE Users SET password = ? WHERE user_id = ?";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$passwordHash, $userId]);
            return true;
        } catch(PDOException $e) {
            error_log("Error updating password: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Deactivate user account
     * 
     * @param int $userId The user ID
     * @return bool Success or failure
     */
    public function deactivateAccount($userId)
    {
        try {
            $query = "UPDATE Users SET is_active = 0 WHERE user_id = ?";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$userId]);
            
            // Also update mentor availability
            $query = "UPDATE Mentor_Profiles SET available_for_mentorship = 0 WHERE user_id = ?";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$userId]);
            
            return true;
        } catch(PDOException $e) {
            error_log("Error deactivating account: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Delete user account
     * 
     * @param int $userId The user ID
     * @return bool Success or failure
     */
    public function deleteAccount($userId)
    {
        try {
            $this->db->beginTransaction();
            
            // Delete from Mentor_Profiles
            $query = "DELETE FROM Mentor_Profiles WHERE user_id = ?";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$userId]);
            
            // Delete from Mentee_Profiles
            $query = "DELETE FROM Mentee_Profiles WHERE user_id = ?";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$userId]);
            
            // Delete user articles
            $query = "DELETE FROM Articles WHERE author_id = ?";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$userId]);
            
            // Delete from Users table
            $query = "DELETE FROM Users WHERE user_id = ?";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$userId]);
            
            $this->db->commit();
            return true;
        } catch(PDOException $e) {
            $this->db->rollBack();
            error_log("Error deleting account: " . $e->getMessage());
            return false;
        }
    }
}
