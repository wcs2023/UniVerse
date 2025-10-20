<?php

class Alumni extends Controller
{
    private $alumniModel;
    
    public function __construct()
    {
        // Start session if not already started
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        $this->alumniModel = $this->model('AlumniModel');
    }
    
    /**
     * Alumni home page
     */
    public function index()
    {
        // TODO: Add authentication check
        /*
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'alumni') {
            header('Location: ' . URLROOT . '/users/login');
            exit;
        }
        */
        
        $data = [
            'user' => $_SESSION ?? []
        ];
        
        $this->view('actors/alumini/Ahome', $data);
    }
    
    public function directory()
    {
        // Get user information from session
        $user_role = $_SESSION['user_role'] ?? '';
        
        // Get list of alumni who are available for mentorship
        $alumni_list = $this->alumniModel->getAvailableAlumni();
        
        // Load view with data
        $this->view('mentorship/alumni_directory', [
            'alumni_list' => $alumni_list,
            'user_role' => $user_role
        ]);
    }
    
    /**
     * Display profile management page
     */
    public function profile()
    {
        // TODO: Add authentication check
        /*
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'alumni') {
            header('Location: ' . URLROOT . '/users/login');
            exit;
        }
        */
        
        $userId = $_SESSION['user_id'] ?? 1; // Hardcoded for now
        
        // Get user profile data from database
        $userData = $this->alumniModel->getUserById($userId);
        
        $data = [
            'userData' => $userData
        ];
        
        $this->view('actors/alumini/Aprofile', $data);
    }
    
    /**
     * Update profile information
     */
    public function updateProfile()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Invalid request method']);
            exit;
        }
        
        $userId = $_SESSION['user_id'] ?? 1;
        
        // Get JSON data
        $json = file_get_contents('php://input');
        $data = json_decode($json, true);
        
        // Validate required fields
        if (empty($data['full_name']) || empty($data['email'])) {
            echo json_encode(['success' => false, 'message' => 'Name and email are required']);
            exit;
        }
        
        // Update profile data
        $result = $this->alumniModel->updateProfile($userId, [
            'full_name' => $data['full_name'],
            'email' => $data['email'],
            'current_role' => $data['current_role'] ?? '',
            'company' => $data['company'] ?? '',
            'linkedin_url' => $data['linkedin_url'] ?? '',
            'short_bio' => $data['short_bio'] ?? '',
            'available_for_mentorship' => $data['available_for_mentorship'] ? 1 : 0
        ]);
        
        if ($result) {
            echo json_encode(['success' => true, 'message' => 'Profile updated successfully']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error updating profile']);
        }
        exit;
    }
    
    /**
     * Change password
     */
    public function changePassword()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Invalid request method']);
            exit;
        }
        
        $userId = $_SESSION['user_id'] ?? 1;
        
        $json = file_get_contents('php://input');
        $data = json_decode($json, true);
        
        // Validate inputs
        if (empty($data['current_password']) || empty($data['new_password'])) {
            echo json_encode(['success' => false, 'message' => 'All fields are required']);
            exit;
        }
        
        // Verify current password
        $user = $this->alumniModel->getUserById($userId);
        if (!password_verify($data['current_password'], $user->password)) {
            echo json_encode(['success' => false, 'message' => 'Current password is incorrect']);
            exit;
        }
        
        // Update password
        $newPasswordHash = password_hash($data['new_password'], PASSWORD_DEFAULT);
        $result = $this->alumniModel->updatePassword($userId, $newPasswordHash);
        
        if ($result) {
            echo json_encode(['success' => true, 'message' => 'Password changed successfully']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error changing password']);
        }
        exit;
    }
    
    /**
     * Deactivate account
     */
    public function deactivateAccount()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Invalid request method']);
            exit;
        }
        
        $userId = $_SESSION['user_id'] ?? 1;
        
        $result = $this->alumniModel->deactivateAccount($userId);
        
        if ($result) {
            echo json_encode(['success' => true, 'message' => 'Account deactivated']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error deactivating account']);
        }
        exit;
    }
    
    /**
     * Delete account
     */
    public function deleteAccount()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Invalid request method']);
            exit;
        }
        
        $userId = $_SESSION['user_id'] ?? 1;
        
        $result = $this->alumniModel->deleteAccount($userId);
        
        if ($result) {
            // Destroy session
            session_destroy();
            echo json_encode(['success' => true, 'message' => 'Account deleted']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error deleting account']);
        }
        exit;
    }
}
