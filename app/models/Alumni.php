<?php
/**
 * Alumni Model
 * Updated to work with normalized database schema
 */
class Alumni
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    /**
     * Get alumni profile by user ID
     * 
     * @param int $userId The user ID
     * @return object|bool The alumni data or false on failure
     */
    public function getAlumniByUserId($userId)
    {
        try {
            $query = "SELECT 
                        u.user_id, u.username, u.email, u.first_name, u.last_name,
                        u.date_of_birth, u.gender, u.phone, u.profile_picture,
                        u.user_type, u.account_status, u.created_at, u.last_login,
                        ap.profile_id, ap.university_name, ap.degree_program,
                        ap.graduation_year, ap.current_job_title, ap.current_company,
                        ap.linkedin_url, ap.github_url, ap.portfolio_url,
                        ap.skills_experience, ap.profile_completed,
                        CONCAT(u.first_name, ' ', u.last_name) as full_name
                      FROM users u
                      LEFT JOIN alumni_profiles ap ON u.user_id = ap.user_id
                      WHERE u.user_id = ? AND u.user_type = 'alumni'";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$userId]);
            return $stmt->fetch(PDO::FETCH_OBJ);
        } catch (PDOException $e) {
            error_log("Error getting alumni: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Get alumni by ID (same as getAlumniByUserId for compatibility)
     */
    public function getAlumniById($alumniId)
    {
        return $this->getAlumniByUserId($alumniId);
    }

    /**
     * Get user by ID from users table
     * 
     * @param int $userId The user ID
     * @return object|bool The user data or false on failure
     */
    public function getUserById($userId)
    {
        try {
            $query = "SELECT 
                        u.user_id, u.username, u.email, u.first_name, u.last_name,
                        u.date_of_birth, u.gender, u.phone, u.profile_picture,
                        u.user_type, u.account_status, u.created_at, u.last_login,
                        CONCAT(u.first_name, ' ', u.last_name) as full_name,
                        ap.profile_id, ap.university_name, ap.degree_program,
                        ap.graduation_year, ap.current_job_title as current_role,
                        ap.current_company as company, ap.linkedin_url,
                        ap.github_url, ap.portfolio_url, ap.skills_experience as short_bio,
                        CASE WHEN m.is_active = 1 THEN 1 ELSE 0 END as available_for_mentorship
                      FROM users u
                      LEFT JOIN alumni_profiles ap ON u.user_id = ap.user_id
                      LEFT JOIN mentors m ON u.user_id = m.user_id
                      WHERE u.user_id = ?";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$userId]);
            return $stmt->fetch(PDO::FETCH_OBJ);
        } catch (PDOException $e) {
            error_log("Error getting user: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Get all alumni who are available for mentorship
     * 
     * @return array The list of available alumni mentors
     */
    public function getAvailableAlumni()
    {
        try {
            $query = "SELECT 
                        u.user_id, u.first_name, u.last_name, u.email, u.profile_picture,
                        CONCAT(u.first_name, ' ', u.last_name) as full_name,
                        ap.current_job_title, ap.current_company, ap.skills_experience,
                        m.mentor_id, m.expertise_areas, m.max_mentees,
                        (SELECT COUNT(*) FROM mentor_requests mr 
                         WHERE mr.mentor_id = m.mentor_id AND mr.status = 'completed') as completed_sessions
                      FROM users u
                      INNER JOIN alumni_profiles ap ON u.user_id = ap.user_id
                      INNER JOIN mentors m ON u.user_id = m.user_id
                      WHERE u.user_type = 'alumni' 
                      AND u.account_status = 'active'
                      AND m.is_active = 1
                      ORDER BY completed_sessions DESC, u.first_name ASC";
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            $alumni_list = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Parse expertise areas for each alumni
            foreach ($alumni_list as &$alumni) {
                if (!empty($alumni['expertise_areas'])) {
                    $decoded = json_decode($alumni['expertise_areas'], true);
                    $alumni['skills'] = is_array($decoded) ? $decoded : explode(',', $alumni['expertise_areas']);
                } else {
                    $alumni['skills'] = [];
                }
            }

            return $alumni_list;
        } catch (PDOException $e) {
            error_log("Error getting available alumni: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Update alumni profile
     * 
     * @param int $userId The user ID
     * @param array $data The profile data to update
     * @return bool Success or failure
     */
    public function updateProfile($userId, $data)
    {
        try {
            $this->db->beginTransaction();

            // Update users table (name, email)
            if (isset($data['first_name']) || isset($data['last_name']) || isset($data['email'])) {
                $updateFields = [];
                $params = [];

                if (isset($data['first_name'])) {
                    $updateFields[] = "first_name = ?";
                    $params[] = $data['first_name'];
                }
                if (isset($data['last_name'])) {
                    $updateFields[] = "last_name = ?";
                    $params[] = $data['last_name'];
                }
                if (isset($data['email'])) {
                    $updateFields[] = "email = ?";
                    $params[] = $data['email'];
                }
                if (isset($data['phone'])) {
                    $updateFields[] = "phone = ?";
                    $params[] = $data['phone'];
                }

                if (!empty($updateFields)) {
                    $params[] = $userId;
                    $query = "UPDATE users SET " . implode(', ', $updateFields) . " WHERE user_id = ?";
                    $stmt = $this->db->prepare($query);
                    $stmt->execute($params);
                }
            }

            // Check if alumni_profiles record exists
            $query = "SELECT COUNT(*) as count FROM alumni_profiles WHERE user_id = ?";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$userId]);
            $result = $stmt->fetch(PDO::FETCH_OBJ);

            if ($result->count > 0) {
                // Update existing alumni profile
                $query = "UPDATE alumni_profiles SET
                          university_name = COALESCE(?, university_name),
                          degree_program = COALESCE(?, degree_program),
                          graduation_year = COALESCE(?, graduation_year),
                          current_job_title = COALESCE(?, current_job_title),
                          current_company = COALESCE(?, current_company),
                          linkedin_url = COALESCE(?, linkedin_url),
                          github_url = COALESCE(?, github_url),
                          portfolio_url = COALESCE(?, portfolio_url),
                          skills_experience = COALESCE(?, skills_experience),
                          profile_completed = 1
                          WHERE user_id = ?";
                $stmt = $this->db->prepare($query);
                $stmt->execute([
                    $data['university_name'] ?? null,
                    $data['degree_program'] ?? null,
                    $data['graduation_year'] ?? null,
                    $data['current_role'] ?? $data['current_job_title'] ?? null,
                    $data['company'] ?? $data['current_company'] ?? null,
                    $data['linkedin_url'] ?? null,
                    $data['github_url'] ?? null,
                    $data['portfolio_url'] ?? null,
                    $data['short_bio'] ?? $data['skills_experience'] ?? null,
                    $userId
                ]);
            } else {
                // Insert new alumni profile
                $query = "INSERT INTO alumni_profiles 
                          (user_id, university_name, degree_program, graduation_year,
                           current_job_title, current_company, linkedin_url, github_url,
                           portfolio_url, skills_experience, profile_completed)
                          VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 1)";
                $stmt = $this->db->prepare($query);
                $stmt->execute([
                    $userId,
                    $data['university_name'] ?? '',
                    $data['degree_program'] ?? '',
                    $data['graduation_year'] ?? date('Y'),
                    $data['current_role'] ?? $data['current_job_title'] ?? '',
                    $data['company'] ?? $data['current_company'] ?? '',
                    $data['linkedin_url'] ?? '',
                    $data['github_url'] ?? '',
                    $data['portfolio_url'] ?? '',
                    $data['short_bio'] ?? $data['skills_experience'] ?? ''
                ]);
            }

            // Handle mentor availability
            if (isset($data['available_for_mentorship'])) {
                $query = "SELECT COUNT(*) as count FROM mentors WHERE user_id = ?";
                $stmt = $this->db->prepare($query);
                $stmt->execute([$userId]);
                $result = $stmt->fetch(PDO::FETCH_OBJ);

                if ($result->count > 0) {
                    $query = "UPDATE mentors SET is_active = ? WHERE user_id = ?";
                    $stmt = $this->db->prepare($query);
                    $stmt->execute([$data['available_for_mentorship'] ? 1 : 0, $userId]);
                } else if ($data['available_for_mentorship']) {
                    $query = "INSERT INTO mentors (user_id, is_active) VALUES (?, 1)";
                    $stmt = $this->db->prepare($query);
                    $stmt->execute([$userId]);
                }
            }

            $this->db->commit();
            return true;
        } catch (PDOException $e) {
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
            $query = "UPDATE users SET password_hash = ? WHERE user_id = ?";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$passwordHash, $userId]);
            return true;
        } catch (PDOException $e) {
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
            $query = "UPDATE users SET account_status = 'inactive' WHERE user_id = ?";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$userId]);

            // Also deactivate mentor status
            $query = "UPDATE mentors SET is_active = 0 WHERE user_id = ?";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$userId]);

            return true;
        } catch (PDOException $e) {
            error_log("Error deactivating account: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Delete user account and related data
     * 
     * @param int $userId The user ID
     * @return bool Success or failure
     */
    public function deleteAccount($userId)
    {
        try {
            $this->db->beginTransaction();

            // Delete from mentors
            $query = "DELETE FROM mentors WHERE user_id = ?";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$userId]);

            // Delete from alumni_profiles
            $query = "DELETE FROM alumni_profiles WHERE user_id = ?";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$userId]);

            // Delete articles
            $query = "DELETE FROM articles WHERE user_id = ?";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$userId]);

            // Delete from users table (cascades will handle the rest)
            $query = "DELETE FROM users WHERE user_id = ?";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$userId]);

            $this->db->commit();
            return true;
        } catch (PDOException $e) {
            $this->db->rollBack();
            error_log("Error deleting account: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Update profile picture
     * 
     * @param int $userId The user ID
     * @param string $picturePath The profile picture path
     * @return bool Success or failure
     */
    public function updateProfilePicture($userId, $picturePath)
    {
        try {
            $query = "UPDATE users SET profile_picture = ? WHERE user_id = ?";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$picturePath, $userId]);
            return true;
        } catch (PDOException $e) {
            error_log("Error updating profile picture: " . $e->getMessage());
            return false;
        }
    }
}
