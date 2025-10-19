<?php 

class Uachievements extends Controller{

    private $achievementModel;

    public function __construct()
    {
        $this->achievementModel = new Achievement();
    }

    public function index(){
        // For now, using user ID 1 - in a real app, this would come from session
        $userId = 1;
        
        // Get all achievements for the user
        $achievements = $this->achievementModel->getAchievementsByUser($userId);
        
        // Get achievement counts by type
        $achievementCounts = $this->achievementModel->getAchievementCountsByType($userId);
        
        // Convert counts array to associative array for easier access
        $counts = [];
        foreach ($achievementCounts as $count) {
            $counts[$count['type']] = $count['count'];
        }
        
        // Get available achievement types
        $achievementTypes = $this->achievementModel->getAchievementTypes();
        
        // Prepare data for the view
        $data = [
            'achievements' => $achievements,
            'counts' => $counts,
            'types' => $achievementTypes,
            'totalCount' => count($achievements)
        ];
        
        // Load the undergraduate Achievements view with data
        $this->view('actors/undergraduate/UAchievements', $data);
    }

    /**
     * Add a new achievement
     */
    public function add()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = [
                'user_id' => 1, // In real app, get from session
                'title' => $_POST['title'],
                'description' => $_POST['description'],
                'achievement_type' => $_POST['achievement_type'],
                'date_achieved' => $_POST['date_achieved'],
                'certificate_url' => $_POST['certificate_url'] ?? null,
                'institution' => $_POST['institution'] ?? null
            ];
            
            if ($this->achievementModel->createAchievement($data)) {
                // Redirect back to achievements page
                header('Location: ' . BASE_URL . '/uachievements');
                exit;
            }
        }
        
        // Get achievement types for the dropdown
        $achievementTypes = $this->achievementModel->getAchievementTypes();
        
        $data = [
            'types' => $achievementTypes
        ];
        
        // If GET request or failed POST, show add form
        $this->view('actors/undergraduate/UAddAchievement', $data);
    }

    /**
     * Edit an achievement
     */
    public function edit($id = null)
    {
        if (!$id) {
            header('Location: ' . BASE_URL . '/uachievements');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = [
                'title' => $_POST['title'],
                'description' => $_POST['description'],
                'achievement_type' => $_POST['achievement_type'],
                'date_achieved' => $_POST['date_achieved'],
                'certificate_url' => $_POST['certificate_url'] ?? null,
                'institution' => $_POST['institution'] ?? null
            ];
            
            if ($this->achievementModel->updateAchievement($id, $data)) {
                header('Location: ' . BASE_URL . '/uachievements');
                exit;
            }
        }
        
        // Get achievement data for the form
        $achievement = $this->achievementModel->getAchievementById($id);
        $achievementTypes = $this->achievementModel->getAchievementTypes();
        
        $data = [
            'achievement' => $achievement,
            'types' => $achievementTypes
        ];
        
        $this->view('actors/undergraduate/UEditAchievement', $data);
    }

    /**
     * Delete an achievement
     */
    public function delete($id = null)
    {
        if (!$id) {
            header('Location: ' . BASE_URL . '/uachievements');
            exit;
        }

        if ($this->achievementModel->deleteAchievement($id)) {
            header('Location: ' . BASE_URL . '/uachievements');
            exit;
        }
    }
}
