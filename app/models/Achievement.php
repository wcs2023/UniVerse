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
    public function getAchievementsByUserId($userId)
    {
        $sql = "SELECT * FROM achievements WHERE user_id = :user_id ORDER BY date_achieved DESC";
        return $this->fetchAll($sql, ['user_id' => $userId]);
    }

    /**
     * Get a single achievement by ID
     */
    public function getAchievementById($achievementId)
    {
        $sql = "SELECT * FROM achievements WHERE achievement_id = :achievement_id";
        return $this->fetch($sql, ['achievement_id' => $achievementId]);
    }

    /**
     * Get achievement counts grouped by type for a user
     */
    public function getAchievementCountsByType($userId)
    {
        $sql = "SELECT achievement_type, COUNT(*) as count 
                FROM achievements 
                WHERE user_id = :user_id 
                GROUP BY achievement_type";
        return $this->fetchAll($sql, ['user_id' => $userId]);
    }

    /**
     * Create a new achievement
     */
    public function createAchievement($data)
    {
        // ✅ Map to correct database column names
        $sql = "INSERT INTO achievements 
                (user_id, title, description, achievement_type, date_achieved, institution, certificate_url) 
                VALUES 
                (:user_id, :title, :description, :achievement_type, :date_achieved, :institution, :certificate_url)";
        
        // ✅ Map the field names to match database columns
        $params = [
            'user_id' => $data['user_id'],
            'title' => $data['title'],
            'description' => $data['description'],
            'achievement_type' => $data['achievement_type'],
            'date_achieved' => $data['date_achieved'],
            'institution' => $data['issuing_organization'] ?? null,  // ✅ Map issuing_organization -> institution
            'certificate_url' => $data['verification_url'] ?? null   // ✅ Map verification_url -> certificate_url
        ];
        
        return $this->query($sql, $params);
    }

    /**
     * Update an existing achievement
     */
    public function updateAchievement($achievementId, $data)
    {
        // ✅ Map to correct database column names
        $sql = "UPDATE achievements 
                SET title = :title,
                    description = :description,
                    achievement_type = :achievement_type,
                    date_achieved = :date_achieved,
                    institution = :institution,
                    certificate_url = :certificate_url
                WHERE achievement_id = :achievement_id";
        
        // ✅ Map the field names to match database columns
        $params = [
            'achievement_id' => $achievementId,
            'title' => $data['title'],
            'description' => $data['description'],
            'achievement_type' => $data['achievement_type'],
            'date_achieved' => $data['date_achieved'],
            'institution' => $data['issuing_organization'] ?? null,  // ✅ Map issuing_organization -> institution
            'certificate_url' => $data['verification_url'] ?? null   // ✅ Map verification_url -> certificate_url
        ];
        
        return $this->query($sql, $params);
    }

    /**
     * Delete an achievement
     */
    public function deleteAchievement($achievementId)
    {
        $sql = "DELETE FROM achievements WHERE achievement_id = :achievement_id";
        return $this->query($sql, ['achievement_id' => $achievementId]);
    }

    /**
     * Get achievements by type for a user
     */
    public function getAchievementsByType($userId, $type)
    {
        $sql = "SELECT * FROM achievements 
                WHERE user_id = :user_id AND achievement_type = :achievement_type 
                ORDER BY date_achieved DESC";
        return $this->fetchAll($sql, [
            'user_id' => $userId,
            'achievement_type' => $type
        ]);
    }

    /**
     * Count total achievements for a user
     */
    public function countUserAchievements($userId)
    {
        $sql = "SELECT COUNT(*) as total FROM achievements WHERE user_id = :user_id";
        $result = $this->fetch($sql, ['user_id' => $userId]);
        return $result['total'] ?? 0;
    }
}
