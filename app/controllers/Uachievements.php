<?php 

class Uachievements extends Controller {

    private $achievementModel;

    public function __construct()
    {
        $this->achievementModel = new Achievement();
    }

    public function index() {
        // Check if user is logged in
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . BASE_URL . '/login');
            exit();
        }

        $userId = $_SESSION['user_id'];
        
        // Handle POST request for adding/editing achievements
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->handleAchievementSubmission();
            return;
        }
        
        //  Get user data from database
        $userModel = new User();
        $user = $userModel->getUserById($userId);
        
        //  Get undergraduate profile data
        $undergraduateModel = new UndergraduateProfile();
        $profile = $undergraduateModel->getProfileByUserId($userId);
        
        // Get all achievements for the user
        $achievements = $this->achievementModel->getAchievementsByUserId($userId);
        
        // Get achievement counts by type
        $achievementCounts = $this->achievementModel->getAchievementCountsByType($userId);
        
        // Convert counts array to associative array for easier access
        $counts = [];
        if ($achievementCounts) {
            foreach ($achievementCounts as $count) {
                $counts[$count['achievement_type']] = $count['count'];
            }
        }
        
        // Get available achievement types
        $achievementTypes = ['Academic', 'Professional', 'Certification', 'Award', 'Competition', 'Publication', 'Project', 'Other'];
        
        // Prepare data for the view
        $data = [
            'user' => $user ?? [],  //  Pass user data
            'profile' => $profile ?? [],  //  Pass profile data
            'achievements' => $achievements ?? [],
            'counts' => $counts,
            'types' => $achievementTypes,
            'totalCount' => count($achievements ?? []),
            'error' => $_SESSION['achievement_error'] ?? null,
            'success' => $_SESSION['achievement_success'] ?? null,
            'title' => 'My Achievements'
        ];
        
        // Clear session messages
        unset($_SESSION['achievement_error']);
        unset($_SESSION['achievement_success']);
        
        // Load the undergraduate Achievements view with data
        $this->view('actors/undergraduate/UAchievements', $data);
    }

    /**
     * Handle achievement form submission (add or edit)
     */
    private function handleAchievementSubmission() {
        try {
            $userId = $_SESSION['user_id'];
            
            // Check if this is an edit or new achievement
            $achievementId = $_POST['achievement_id'] ?? null;
            
            // Validate required fields
            $requiredFields = ['title', 'description', 'date_achieved', 'achievement_type'];
            
            foreach ($requiredFields as $field) {
                if (empty($_POST[$field])) {
                    throw new Exception(ucfirst(str_replace('_', ' ', $field)) . ' is required');
                }
            }

            $achievementData = [
                'user_id' => $userId,
                'title' => trim($_POST['title']),
                'description' => trim($_POST['description']),
                'date_achieved' => $_POST['date_achieved'],
                'achievement_type' => $_POST['achievement_type'],
                'issuing_organization' => !empty($_POST['issuing_organization']) ? trim($_POST['issuing_organization']) : null,
                'verification_url' => !empty($_POST['verification_url']) ? trim($_POST['verification_url']) : null
            ];

            if ($achievementId) {
                // Update existing achievement
                $this->achievementModel->updateAchievement($achievementId, $achievementData);
                $_SESSION['achievement_success'] = 'Achievement updated successfully!';
            } else {
                // Create new achievement
                $this->achievementModel->createAchievement($achievementData);
                $_SESSION['achievement_success'] = 'Achievement added successfully!';
            }

            header('Location: ' . BASE_URL . '/uachievements');
            exit();

        } catch (Exception $e) {
            error_log("Achievement submission error: " . $e->getMessage());
            $_SESSION['achievement_error'] = $e->getMessage();
            header('Location: ' . BASE_URL . '/uachievements');
            exit();
        }
    }

    /**
     * Show add achievement form
     */
    public function add() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . BASE_URL . '/login');
            exit();
        }

        $data = [
            'title' => 'Add New Achievement',
            'achievement' => null // No existing achievement data
        ];

        $this->view('actors/undergraduate/UAddAchievements', $data);
    }

    /**
     * Show edit achievement form
     */
    public function edit($id = null) {
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . BASE_URL . '/login');
            exit();
        }

        if (!$id) {
            $_SESSION['achievement_error'] = 'Invalid achievement ID';
            header('Location: ' . BASE_URL . '/uachievements');
            exit();
        }

        try {
            $userId = $_SESSION['user_id'];
            $achievement = $this->achievementModel->getAchievementById($id);

            // Verify the achievement belongs to this user
            if (!$achievement || $achievement['user_id'] != $userId) {
                $_SESSION['achievement_error'] = 'Achievement not found or access denied';
                header('Location: ' . BASE_URL . '/uachievements');
                exit();
            }

            $data = [
                'title' => 'Edit Achievement',
                'achievement' => $achievement
            ];

            $this->view('actors/undergraduate/UAddAchievements', $data);

        } catch (Exception $e) {
            error_log("Achievement edit error: " . $e->getMessage());
            $_SESSION['achievement_error'] = 'Error loading achievement';
            header('Location: ' . BASE_URL . '/uachievements');
            exit();
        }
    }

    /**
     * Delete an achievement
     */
    public function delete($id = null) {
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . BASE_URL . '/login');
            exit();
        }

        if (!$id) {
            $_SESSION['achievement_error'] = 'Invalid achievement ID';
            header('Location: ' . BASE_URL . '/uachievements');
            exit();
        }

        try {
            $userId = $_SESSION['user_id'];
            
            // Verify the achievement belongs to this user
            $achievement = $this->achievementModel->getAchievementById($id);
            
            if ($achievement && $achievement['user_id'] == $userId) {
                $this->achievementModel->deleteAchievement($id);
                $_SESSION['achievement_success'] = 'Achievement deleted successfully!';
            } else {
                $_SESSION['achievement_error'] = 'Achievement not found or access denied';
            }
        } catch (Exception $e) {
            error_log("Achievement deletion error: " . $e->getMessage());
            $_SESSION['achievement_error'] = 'Error deleting achievement: ' . $e->getMessage();
        }

        header('Location: ' . BASE_URL . '/uachievements');
        exit();
    }
}
