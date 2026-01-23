<?php 

class UMyProfile extends Controller {
    
    public function index() {
        // Check if user is logged in
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . BASE_URL . '/login');
            exit();
        }

        // Get user ID from session
        $userId = $_SESSION['user_id'];

        // Load User and UndergraduateProfile models
        $userModel = new User();
        $profileModel = new UndergraduateProfile();  // ✅ Changed from Undergraduate

        // Get user data
        $user = $userModel->getUserById($userId);
        
        // Get undergraduate profile data
        $profile = $profileModel->getProfileByUserId($userId);

        // Prepare data for view
        $data = [
            'user' => $user,
            'profile' => $profile,
            'title' => 'My Profile'
        ];

        // Load the view
        $this->view('actors/undergraduate/UMyProfile', $data);
    }
}
