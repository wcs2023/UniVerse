<?php

class Alumni extends Model
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Create a new alumni profile
     */
    public function createProfile($data)
    {
        // Prepare alumni profile data
        $query = "INSERT INTO alumni_profiles (
                    user_id, university_name, degree_program, graduation_year,
                    additional_degree_1, additional_university_1, additional_grad_year_1,
                    additional_degree_2, additional_university_2, additional_grad_year_2,
                    skills_experience
                  ) VALUES (
                    :user_id, :university_name, :degree_program, :graduation_year,
                    :additional_degree_1, :additional_university_1, :additional_grad_year_1,
                    :additional_degree_2, :additional_university_2, :additional_grad_year_2,
                    :skills_experience
                  )";

        $params = [
            'user_id' => $data['user_id'],
            'university_name' => $data['university_name'],
            'degree_program' => $data['degree_program'],
            'graduation_year' => $data['graduation_year'],
            'additional_degree_1' => $data['additional_degree_1'] ?? null,
            'additional_university_1' => $data['additional_university_1'] ?? null,
            'additional_grad_year_1' => $data['additional_grad_year_1'] ?? null,
            'additional_degree_2' => $data['additional_degree_2'] ?? null,
            'additional_university_2' => $data['additional_university_2'] ?? null,
            'additional_grad_year_2' => $data['additional_grad_year_2'] ?? null,
            'skills_experience' => $data['skills_experience'] ?? null,
        ];

        return $this->query($query, $params);
    }

    /**
     * Get alumni profile by user ID
     */
    public function getProfileByUserId($userId)
    {
        $query = "SELECT * FROM alumni_profiles WHERE user_id = :user_id";
        return $this->fetch($query, ['user_id' => $userId]);
    }

    /**
     * Update alumni profile
     */
    public function updateProfile($userId, $data)
    {
        $allowedFields = [
            'university_name', 'degree_program', 'graduation_year',
            'additional_degree_1', 'additional_university_1', 'additional_grad_year_1',
            'additional_degree_2', 'additional_university_2', 'additional_grad_year_2',
            'current_job_title', 'current_company', 'linkedin_url', 'github_url',
            'portfolio_url', 'skills_experience', 'profile_completed'
        ];

        $updateFields = [];
        $params = ['user_id' => $userId];
        
        foreach ($allowedFields as $field) {
            if (isset($data[$field])) {
                $updateFields[] = "$field = :$field";
                $params[$field] = $data[$field];
            }
        }

        if (empty($updateFields)) {
            return false;
        }

        $query = "UPDATE alumni_profiles SET " . implode(', ', $updateFields) . ", updated_at = NOW() WHERE user_id = :user_id";

        return $this->query($query, $params);
    }

    /**
     * Complete profile after login (job title, company, social links)
     */
    public function completeProfile($userId, $professionalData)
    {
        $query = "UPDATE alumni_profiles SET 
                    current_job_title = :job_title,
                    current_company = :company,
                    linkedin_url = :linkedin_url,
                    github_url = :github_url,
                    portfolio_url = :portfolio_url,
                    profile_completed = true,
                    updated_at = NOW()
                  WHERE user_id = :user_id";

        $params = [
            'user_id' => $userId,
            'job_title' => $professionalData['job_title'] ?? null,
            'company' => $professionalData['company'] ?? null,
            'linkedin_url' => $professionalData['linkedin_url'] ?? null,
            'github_url' => $professionalData['github_url'] ?? null,
            'portfolio_url' => $professionalData['portfolio_url'] ?? null
        ];

        return $this->query($query, $params);
    }

    /**
     * Check if alumni profile is completed
     */
    public function isProfileCompleted($userId)
    {
        $query = "SELECT profile_completed FROM alumni_profiles WHERE user_id = :user_id";
        $result = $this->fetch($query, ['user_id' => $userId]);
        
        return !empty($result) && $result['profile_completed'];
    }

    /**
     * Get all alumni profiles with basic info for networking
     */
    public function getAllAlumni($limit = 20, $offset = 0)
    {
        $query = "
            SELECT 
                u.user_id, u.first_name, u.last_name, u.profile_picture,
                ap.university_name, ap.degree_program, ap.graduation_year,
                ap.current_job_title, ap.current_company, ap.linkedin_url
            FROM alumni_profiles ap
            JOIN users u ON ap.user_id = u.user_id
            WHERE u.account_status = 'active'
            ORDER BY ap.graduation_year DESC, u.last_name ASC
            LIMIT :limit OFFSET :offset
        ";

        return $this->fetchAll($query, [
            'limit' => $limit,
            'offset' => $offset
        ]);
    }

    /**
     * Search alumni by university, degree, or company
     */
    public function searchAlumni($searchTerm, $filters = [])
    {
        $query = "
            SELECT 
                u.user_id, u.first_name, u.last_name, u.profile_picture,
                ap.university_name, ap.degree_program, ap.graduation_year,
                ap.current_job_title, ap.current_company, ap.linkedin_url
            FROM alumni_profiles ap
            JOIN users u ON ap.user_id = u.user_id
            WHERE u.account_status = 'active'
        ";

        $params = [];

        // Add search term condition
        if (!empty($searchTerm)) {
            $query .= " AND (
                ap.university_name LIKE :search 
                OR ap.degree_program LIKE :search 
                OR ap.current_company LIKE :search
                OR CONCAT(u.first_name, ' ', u.last_name) LIKE :search
            )";
            $params['search'] = "%{$searchTerm}%";
        }

        // Add filters
        if (!empty($filters['university'])) {
            $query .= " AND ap.university_name = :university";
            $params['university'] = $filters['university'];
        }

        if (!empty($filters['graduation_year'])) {
            $query .= " AND ap.graduation_year = :graduation_year";
            $params['graduation_year'] = $filters['graduation_year'];
        }

        if (!empty($filters['degree_program'])) {
            $query .= " AND ap.degree_program LIKE :degree_program";
            $params['degree_program'] = "%{$filters['degree_program']}%";
        }

        $query .= " ORDER BY ap.graduation_year DESC, u.last_name ASC";

        return $this->fetchAll($query, $params);
    }

    /**
     * Get alumni achievements
     */
    public function getAchievements($userId)
    {
        $query = "
            SELECT * FROM alumni_achievements 
            WHERE user_id = :user_id 
            ORDER BY achievement_date DESC
        ";
        
        return $this->fetchAll($query, ['user_id' => $userId]);
    }

    /**
     * Add achievement
     */
    public function addAchievement($userId, $achievementData)
    {
        $query = "INSERT INTO alumni_achievements (
                    user_id, title, description, achievement_date, organization, achievement_type
                  ) VALUES (
                    :user_id, :title, :description, :achievement_date, :organization, :achievement_type
                  )";

        $params = [
            'user_id' => $userId,
            'title' => $achievementData['title'],
            'description' => $achievementData['description'] ?? null,
            'achievement_date' => $achievementData['achievement_date'] ?? null,
            'organization' => $achievementData['organization'] ?? null,
            'achievement_type' => $achievementData['achievement_type'] ?? 'other'
        ];

        return $this->query($query, $params);
    }

    /**
     * Get statistics for alumni dashboard
     */
    public function getAlumniStats()
    {
        $stats = [];

        // Total alumni count
        $query = "SELECT COUNT(*) as total FROM alumni_profiles ap JOIN users u ON ap.user_id = u.user_id WHERE u.account_status = 'active'";
        $result = $this->fetch($query);
        $stats['total_alumni'] = $result['total'] ?? 0;

        // Alumni by graduation year (last 10 years)
        $query = "
            SELECT graduation_year, COUNT(*) as count 
            FROM alumni_profiles ap 
            JOIN users u ON ap.user_id = u.user_id 
            WHERE u.account_status = 'active' 
            AND graduation_year >= YEAR(CURDATE()) - 10
            GROUP BY graduation_year 
            ORDER BY graduation_year DESC
        ";
        $stats['by_year'] = $this->fetchAll($query);

        // Top universities
        $query = "
            SELECT university_name, COUNT(*) as count 
            FROM alumni_profiles ap 
            JOIN users u ON ap.user_id = u.user_id 
            WHERE u.account_status = 'active' 
            GROUP BY university_name 
            ORDER BY count DESC 
            LIMIT 10
        ";
        $stats['top_universities'] = $this->fetchAll($query);

        return $stats;
    }
}
