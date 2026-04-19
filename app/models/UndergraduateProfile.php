<?php

class UndergraduateProfile extends Model 
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Get undergraduate profile by user ID
     */
    public function getProfileByUserId($userId)
    {
        $query = "SELECT * FROM undergraduate_profiles WHERE user_id = :user_id";
        return $this->fetch($query, ['user_id' => $userId]);
    }

    /**
     * Create undergraduate profile
     */
    public function createProfile($data)
    {
        $query = "INSERT INTO undergraduate_profiles 
                  (user_id, university, faculty, degree_program, academic_year, expected_graduation_year)
                  VALUES (:user_id, :university, :faculty, :degree_program, :academic_year, :expected_graduation_year)";
        
        return $this->query($query, $data);
    }

    /**
     * Update undergraduate profile
     */
    public function updateProfile($userId, $data)
    {
        try {
            $allowedFields = [
                'university',
                'faculty', 
                'degree_program',
                'academic_year',
                'expected_graduation_year',
                'school'
            ];

            $setParts = [];
            $params   = ['user_id' => $userId];

            foreach ($allowedFields as $field) {
                if (array_key_exists($field, $data)) {
                    $setParts[]     = "{$field} = :{$field}";
                    $params[$field] = $data[$field] !== '' ? $data[$field] : null;
                }
            }

            if (empty($setParts)) return false;

            $query = "UPDATE undergraduate_profiles SET " . implode(', ', $setParts) . " WHERE user_id = :user_id";

            $stmt = $this->query($query, $params);
            return $stmt->rowCount() > 0;

        } catch (Exception $e) {
            error_log("Error in updateProfile(): " . $e->getMessage());
            return false;
        }
    }
    // public function updateProfile($userId, $data)
    // {
    //     $query = "UPDATE undergraduate_profiles 
    //               SET university = :university,
    //                   faculty = :faculty,
    //                   degree_program = :degree_program,
    //                   academic_year = :academic_year,
    //                   expected_graduation_year = :expected_graduation_year
    //               WHERE user_id = :user_id";
        
    //     $data['user_id'] = $userId;
    //     return $this->query($query, $data);
    // }
}