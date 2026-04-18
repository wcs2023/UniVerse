<?php
/**
 * AlumniModel
 * Updated to work with normalized database schema
 * Uses: users, alumni_profiles, mentors tables
 */
class AlumniModel extends Model
{
    protected $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    private function getCanonicalExpertiseCategories()
    {
        return [
            ['category_name' => 'Software Development', 'description' => 'Full Stack, Backend, Frontend, Mobile'],
            ['category_name' => 'Cloud & DevOps', 'description' => 'AWS, Azure, GCP, CI/CD, Docker, Kubernetes'],
            ['category_name' => 'Cybersecurity', 'description' => 'Ethical Hacking, Network Security, SOC'],
            ['category_name' => 'Data & AI/ML', 'description' => 'Data Science, Machine Learning, LLMs'],
            ['category_name' => 'UI/UX & Product', 'description' => 'UX Research, Product Management, Design Systems'],
            ['category_name' => 'Networking & Infra', 'description' => 'Network Engineering, Sysadmin, Linux'],
            ['category_name' => 'Database Systems', 'description' => 'SQL, NoSQL, System Design, Microservices'],
            ['category_name' => 'Embedded & IoT', 'description' => 'Arduino, FPGA, Firmware, Hardware Design'],
            ['category_name' => 'QA & Testing', 'description' => 'Test Automation, Performance Testing, QA'],
            ['category_name' => 'Computer Architecture', 'description' => 'CPU Design, MIPS, Compilers, OS Internals'],
            ['category_name' => 'Open Source & Tools', 'description' => 'Git, Linux CLI, Contributing to OSS'],
            ['category_name' => 'Tech Career & Interview Prep', 'description' => 'Resume Review, DSA, Placement Guidance'],
        ];
    }

    private function ensureCanonicalExpertiseCategories()
    {
        $insert = $this->db->prepare(
            "INSERT IGNORE INTO expertise_categories (category_name, description) VALUES (?, ?)"
        );

        foreach ($this->getCanonicalExpertiseCategories() as $category) {
            $insert->execute([$category['category_name'], $category['description']]);
        }
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
                        CONCAT(u.first_name, ' ', u.last_name) as full_name,
                        ap.profile_id, ap.university_name, ap.degree_program,
                        ap.graduation_year, ap.current_job_title, ap.current_company,
                        ap.linkedin_url, ap.github_url, ap.portfolio_url,
                                ap.skills_experience, ap.profile_completed
                      FROM users u
                      LEFT JOIN alumni_profiles ap ON u.user_id = ap.user_id
                      WHERE u.user_id = ?";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$userId]);
            return $stmt->fetch(PDO::FETCH_OBJ);
        } catch (PDOException $e) {
            error_log("Error getting alumni by user_id: " . $e->getMessage());
            return false;
        }
    }
    
    public function getMentorIdByUserId($userId)
    {
        try {
            $query = "SELECT mentor_id 
                    FROM mentors 
                    WHERE user_id = ?";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$userId]);

            return $stmt->fetch(PDO::FETCH_ASSOC); 
            // or PDO::FETCH_OBJ if you prefer object access
        } catch (PDOException $e) {
            error_log("Error getting mentor ID by user ID: " . $e->getMessage());
            return false;
        }
    }

    public function getScheduledSessionCount($userId)
    {
        try {
            $mentor = $this->getMentorIdByUserId($userId);

            if (!$mentor) {
                return 0;
            }

            $query = "SELECT COUNT(*) AS total
                    FROM mentorship_bookings
                    WHERE mentor_id = ? AND status = ?";
            $stmt = $this->db->prepare($query);
            $stmt->execute([
                $mentor['mentor_id'],
                'scheduled'
            ]);

            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            return $result ? (int)$result['total'] : 0;
        } catch (PDOException $e) {
            error_log("Error getting scheduled session count: " . $e->getMessage());
            return 0;
        }
    }

    /**
     * Get alumni by ID (alias for backward compatibility)
     */
    public function getAlumniById($alumniId)
    {
        return $this->getAlumniByUserId($alumniId);
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
                        (SELECT COUNT(*) FROM mentor_sessions ms 
                         INNER JOIN mentor_requests mr ON ms.request_id = mr.request_id
                         WHERE mr.mentor_id = m.mentor_id AND ms.status = 'completed') as completed_sessions
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
     * Get user by ID from users table with mentor info
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
                        u.password_hash as `password`,
                        CONCAT(u.first_name, ' ', u.last_name) as full_name,
                        ap.profile_id, ap.university_name, ap.degree_program,
                        ap.graduation_year, ap.current_job_title as `current_role`,
                        ap.current_company as `company`, ap.linkedin_url,
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

            // Parse full_name into first_name and last_name if provided
            if (isset($data['full_name']) && !isset($data['first_name'])) {
                $nameParts = explode(' ', $data['full_name'], 2);
                $data['first_name'] = $nameParts[0];
                $data['last_name'] = $nameParts[1] ?? '';
            }

            // Update users table
            $query = "UPDATE users SET 
                      first_name = COALESCE(?, first_name),
                      last_name = COALESCE(?, last_name),
                      email = COALESCE(?, email),
                      phone = COALESCE(?, phone),
                      date_of_birth = COALESCE(?, date_of_birth),
                      gender = COALESCE(?, gender),
                      updated_at = NOW()
                      WHERE user_id = ?";
            $stmt = $this->db->prepare($query);
            $stmt->execute([
                $data['first_name'] ?? null,
                $data['last_name'] ?? null,
                $data['email'] ?? null,
                $data['phone'] ?? null,
                $data['date_of_birth'] ?? null,
                $data['gender'] ?? null,
                $userId
            ]);

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
                          profile_completed = 1,
                          updated_at = NOW()
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
                    $data['university_name'] ?? 'Not specified',
                    $data['degree_program'] ?? 'Not specified',
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
                    $query = "UPDATE mentors SET is_active = ?, updated_at = NOW() WHERE user_id = ?";
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
            $query = "UPDATE users SET password_hash = ?, updated_at = NOW() WHERE user_id = ?";
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
            $query = "UPDATE users SET account_status = 'inactive', updated_at = NOW() WHERE user_id = ?";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$userId]);

            // Also deactivate mentor status
            $query = "UPDATE mentors SET is_active = 0, updated_at = NOW() WHERE user_id = ?";
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
     * CASCADE will handle most deletions
     * 
     * @param int $userId The user ID
     * @return bool Success or failure
     */
    public function deleteAccount($userId)
    {
        try {
            $this->db->beginTransaction();

            // Delete from mentors (may not cascade automatically)
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

            // Delete from users table (FK cascades will handle remaining)
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
     * Create alumni profile linked to a user
     *
     * @param array $data Profile data (must include user_id)
     * @return int|bool Inserted profile_id on success, false on failure
     */
    public function createProfile($data)
    {
        if (empty($data['user_id'])) {
            error_log('AlumniModel::createProfile called without user_id');
            return false;
        }

        try {
            $query = "INSERT INTO alumni_profiles (
                        user_id, 
                        university_name, 
                        degree_program, 
                        graduation_year,
                        current_job_title,
                        current_company,
                        linkedin_url,
                        github_url,
                        portfolio_url,
                        skills_experience,
                        profile_completed
                      ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 0)";
            $stmt = $this->db->prepare($query);
            $stmt->execute([
                $data['user_id'],
                $data['university_name'] ?? $data['alumni_university_name'] ?? 'Not specified',
                $data['degree_program'] ?? $data['alumni_degree_program'] ?? 'Not specified',
                $data['graduation_year'] ?? date('Y'),
                $data['current_job_title'] ?? null,
                $data['current_company'] ?? null,
                $data['linkedin_url'] ?? null,
                $data['github_url'] ?? null,
                $data['portfolio_url'] ?? null,
                $data['skills_experience'] ?? null
            ]);

            $profileId = $this->db->lastInsertId();
            return $profileId ? (int) $profileId : false;
        } catch (PDOException $e) {
            error_log('Error creating alumni profile: ' . $e->getMessage());
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
            $query = "UPDATE users SET profile_picture = ?, updated_at = NOW() WHERE user_id = ?";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$picturePath, $userId]);
            return true;
        } catch (PDOException $e) {
            error_log("Error updating profile picture: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Update mentorship availability status
     * Creates a mentor record if it doesn't exist, or updates existing one
     * 
     * @param int $userId The user ID
     * @param bool $isAvailable Whether the user is available for mentorship
     * @return bool Success or failure
     */
    public function updateMentorshipAvailability($userId, $isAvailable)
    {
        try {
            // Check if mentor record exists
            $query = "SELECT mentor_id FROM mentors WHERE user_id = ?";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$userId]);
            $mentor = $stmt->fetch(PDO::FETCH_OBJ);

            if ($mentor) {
                // Update existing mentor record
                $query = "UPDATE mentors SET is_active = ?, updated_at = NOW() WHERE user_id = ?";
                $stmt = $this->db->prepare($query);
                $stmt->execute([$isAvailable ? 1 : 0, $userId]);
            } else {
                // Create new mentor record if user wants to be available
                if ($isAvailable) {
                    $query = "INSERT INTO mentors (user_id, is_active, max_mentees) 
                              VALUES (?, 1, 5)";
                    $stmt = $this->db->prepare($query);
                    $stmt->execute([$userId]);
                }
            }

            return true;
        } catch (PDOException $e) {
            error_log("Error updating mentorship availability: " . $e->getMessage());
            return false;
        }
    }

    public function getExpertiseCategories()
    {
        try {
            $this->ensureCanonicalExpertiseCategories();
            return $this->getCanonicalExpertiseCategories();
        } catch (PDOException $e) {
            error_log("Error getting expertise categories: " . $e->getMessage());
            return $this->getCanonicalExpertiseCategories();
        }
    }

    public function getExpertiseCategoryNames()
    {
        $rows = $this->getExpertiseCategories();
        return array_values(array_map(function ($row) {
            return $row['category_name'];
        }, $rows));
    }

    public function getMentorExpertiseByUserId($userId)
    {
        try {
            $query = "SELECT ec.category_name
                      FROM user_expertise ue
                      INNER JOIN expertise_categories ec ON ue.category_id = ec.category_id
                      WHERE ue.user_id = ?
                      ORDER BY ec.category_name ASC";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$userId]);
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return array_values(array_map(function ($row) {
                return $row['category_name'];
            }, $rows));
        } catch (PDOException $e) {
            error_log("Error getting mentor expertise: " . $e->getMessage());
            return [];
        }
    }

    public function updateMentorExpertise($userId, array $expertiseAreas)
    {
        try {
            $this->ensureCanonicalExpertiseCategories();
            $this->db->beginTransaction();

            $query = "DELETE FROM user_expertise WHERE user_id = ?";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$userId]);

            if (!empty($expertiseAreas)) {
                $placeholders = implode(', ', array_fill(0, count($expertiseAreas), '?'));
                $query = "SELECT category_id, category_name
                          FROM expertise_categories
                          WHERE category_name IN ($placeholders)";
                $stmt = $this->db->prepare($query);
                $stmt->execute($expertiseAreas);
                $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

                if (!empty($categories)) {
                    $insert = $this->db->prepare("INSERT INTO user_expertise (user_id, category_id) VALUES (?, ?)");
                    foreach ($categories as $category) {
                        $insert->execute([$userId, (int)$category['category_id']]);
                    }
                }
            }

            $this->db->commit();
            return true;
        } catch (PDOException $e) {
            $this->db->rollBack();
            error_log("Error updating mentor expertise: " . $e->getMessage());
            return false;
        }
    }
}
