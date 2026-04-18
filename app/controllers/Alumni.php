<?php

class Alumni extends Controller
{
    private $alumniModel;
    private $articleModel;
    
    public function __construct()
    {
        // Start session if not already started
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        $this->alumniModel = $this->model('AlumniModel');
        $this->articleModel = $this->model('ArticleModel');
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
        
        // Get recent published articles (limit to 6 for homepage)
        $recentArticles = $this->articleModel->getAllPublishedArticles(6, 0);
        
        $data = [
            'user' => $_SESSION ?? [],
            'articles' => $recentArticles
        ];
        
        $this->view('actors/alumni/Ahome', $data);
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
        // Require authentication
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . BASE_URL . '/login');
            exit;
        }
        
        $userId = $_SESSION['user_id'];
        
        // Get user profile data from database
        $userData = $this->alumniModel->getUserById($userId);
        
        // Get mentorship status
        $mentorshipModel = $this->model('Mentorship');
        $mentorStatus = $mentorshipModel->getMentorStatus($userId);
        
        $data = [
            'userData' => $userData,
            'mentorStatus' => $mentorStatus
        ];
        
        $this->view('actors/alumni/Aprofile', $data);
    }
    
    /**
     * Alumni settings page
     */
    public function settings()
    {
        $userId = $_SESSION['user_id'];
        
        // Get user profile data from database
        $userData = $this->alumniModel->getUserById($userId);
        
        $data = [
            'userData' => $userData,
            'error' => $_SESSION['settings_error'] ?? null,
            'success' => $_SESSION['settings_success'] ?? null
        ];
        
        // Clear session messages
        unset($_SESSION['settings_error']);
        unset($_SESSION['settings_success']);
        
        $this->view('actors/alumni/Asettings', $data);
    }
    
    /**
     * Handle password update from form submission
     */
    public function updatePassword()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . '/alumni/settings');
            exit;
        }
        
        $userId = $_SESSION['user_id'];
        
        try {
            $currentPassword = $_POST['current_password'] ?? '';
            $newPassword = $_POST['new_password'] ?? '';
            $confirmPassword = $_POST['confirm_password'] ?? '';
            
            // Validate inputs
            if (empty($currentPassword) || empty($newPassword) || empty($confirmPassword)) {
                throw new Exception('All fields are required');
            }
            
            if ($newPassword !== $confirmPassword) {
                throw new Exception('New passwords do not match');
            }
            
            if (strlen($newPassword) < 8) {
                throw new Exception('Password must be at least 8 characters long');
            }
            
            // Verify current password
            $user = $this->alumniModel->getUserById($userId);
            if (!password_verify($currentPassword, $user->password)) {
                throw new Exception('Current password is incorrect');
            }
            
            // Update password
            $newPasswordHash = password_hash($newPassword, PASSWORD_DEFAULT);
            $result = $this->alumniModel->updatePassword($userId, $newPasswordHash);
            
            if ($result) {
                $_SESSION['settings_success'] = 'Password updated successfully!';
            } else {
                throw new Exception('Failed to update password');
            }
            
        } catch (Exception $e) {
            error_log("Password update error: " . $e->getMessage());
            $_SESSION['settings_error'] = $e->getMessage();
        }
        
        header('Location: ' . BASE_URL . '/alumni/settings');
        exit;
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
        
        $userId = $_SESSION['user_id'];
        
        // Get JSON data
        $json = file_get_contents('php://input');
        $data = json_decode($json, true);
        
        // Validate required fields
        if (empty($data['full_name']) || empty($data['email'])) {
            echo json_encode(['success' => false, 'message' => 'Name and email are required']);
            exit;
        }

        $requestedMentorshipAvailability = !empty($data['available_for_mentorship']);
        $currentUser = $this->alumniModel->getUserById($userId);
        $currentlyMentorshipAvailable = !empty($currentUser) && !empty($currentUser->available_for_mentorship);

        if ($currentlyMentorshipAvailable && !$requestedMentorshipAvailability && $this->alumniModel->hasActiveUpcomingMentorshipSessions($userId)) {
            echo json_encode([
                'success' => false,
                'message' => 'You have active upcoming mentorship sessions. Please complete or cancel those sessions before changing your mentorship status to unavailable.'
            ]);
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
            'available_for_mentorship' => $requestedMentorshipAvailability ? 1 : 0
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
        
        $userId = $_SESSION['user_id'];
        
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
        
        $userId = $_SESSION['user_id'];
        
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
        
        $userId = $_SESSION['user_id'];
        
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
