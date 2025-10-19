<?php

class Achievement extends Model 
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Get all achievements for a specific user
     */
    public function getAchievementsByUser($userId)
    {
        $query = "SELECT * FROM achievements WHERE user_id = :user_id ORDER BY date_achieved DESC";
        return $this->fetchAll($query, ['user_id' => $userId]);
    }

    /**
     * Get a single achievement by ID
     */
    public function getAchievementById($id)
    {
        $query = "SELECT * FROM achievements WHERE achievement_id = :id";
        return $this->fetch($query, ['id' => $id]);
    }

    /**
     * Create a new achievement
     */
    public function createAchievement($data)
    {
        $query = "INSERT INTO achievements (user_id, title, description, achievement_type, date_achieved, certificate_url, institution, created_at) 
                  VALUES (:user_id, :title, :description, :achievement_type, :date_achieved, :certificate_url, :institution, NOW())";
        
        return $this->query($query, [
            'user_id' => $data['user_id'],
            'title' => $data['title'],
            'description' => $data['description'],
            'achievement_type' => $data['achievement_type'],
            'date_achieved' => $data['date_achieved'],
            'certificate_url' => $data['certificate_url'] ?? null,
            'institution' => $data['institution'] ?? null
        ]);
    }

    /**
     * Update an achievement
     */
    public function updateAchievement($id, $data)
    {
        $query = "UPDATE achievements 
                  SET title = :title, description = :description, achievement_type = :achievement_type, 
                      date_achieved = :date_achieved, certificate_url = :certificate_url, 
                      institution = :institution, updated_at = NOW()
                  WHERE achievement_id = :id";
        
        return $this->query($query, [
            'id' => $id,
            'title' => $data['title'],
            'description' => $data['description'],
            'achievement_type' => $data['achievement_type'],
            'date_achieved' => $data['date_achieved'],
            'certificate_url' => $data['certificate_url'] ?? null,
            'institution' => $data['institution'] ?? null
        ]);
    }

    /**
     * Delete an achievement
     */
    public function deleteAchievement($id)
    {
        $query = "DELETE FROM achievements WHERE achievement_id = :id";
        return $this->query($query, ['id' => $id]);
    }

    /**
     * Get achievements count by type for a user
     */
    public function getAchievementCountsByType($userId)
    {
        $query = "SELECT achievement_type, COUNT(*) as count FROM achievements WHERE user_id = :user_id GROUP BY achievement_type";
        return $this->fetchAll($query, ['user_id' => $userId]);
    }

    /**
     * Get all achievement types
     */
    public function getAchievementTypes()
    {
        return [
            'certificate' => 'Certificate',
            'award' => 'Award',
            'project' => 'Project',
            'activity' => 'Activity',
            'leadership' => 'Leadership',
            'internship' => 'Internship',
            'competition' => 'Competition'
        ];
    }
}
