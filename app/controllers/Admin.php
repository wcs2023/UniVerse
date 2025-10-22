<?php

class Admin extends Controller
{
    public function __construct()
    {
        // Start session if not already started
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        // Check if user is logged in and is an admin
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
            header('Location: ' . BASE_URL . '/login');
            exit;
        }
    }
    
    /**
     * Admin Dashboard - Default landing page
     */
    public function index()
    {
        $this->dashboard();
    }
    
    /**
     * Dashboard view with statistics
     */
    public function dashboard()
    {
        $userModel = $this->model('User');
        
        // Initialize statistics
        $totalUsers = 0;
        $totalUndergraduates = 0;
        $totalAlumni = 0;
        $totalCompanies = 0;
        $totalArticles = 0;
        $pendingRegistrations = 0;
        
        // Fetch statistics data
        try {
            $totalUsers = $userModel->getTotalUsersCount();
            $totalUndergraduates = $userModel->getUsersCountByType('undergraduate');
            $totalAlumni = $userModel->getUsersCountByType('alumni');
            $totalCompanies = $userModel->getUsersCountByType('company');
        } catch (Exception $e) {
            error_log("Error getting users: " . $e->getMessage());
        }
        
        try {
            $articleModel = $this->model('AarticleModel');
            $totalArticles = $articleModel->getTotalArticlesCount();
        } catch (Exception $e) {
            error_log("Error getting articles count: " . $e->getMessage());
        }
        
        // Prepare data for the view
        $data = [
            'totalUsers' => $totalUsers,
            'totalUndergraduates' => $totalUndergraduates ?? 0,
            'totalAlumni' => $totalAlumni ?? 0,
            'totalCompanies' => $totalCompanies ?? 0,
            'totalArticles' => $totalArticles,
            'pendingRegistrations' => $pendingRegistrations
        ];
        
        // Load the dashboard view
        $this->view('actors/admin/dashboard', $data);
    }
    
    /**
     * Manage users - WITH FILTERS AND SEARCH
     */
    public function users()
    {
        $userModel = $this->model('User');
        
        $roleFilter = $_GET['role'] ?? 'all';
        $searchQuery = $_GET['search'] ?? '';
        
        // Initialize users array
        $users = [];
        
        try {
            // Fetch users based on role filter
            if ($roleFilter !== 'all') {
                $users = $userModel->getUsersByType($roleFilter);
            } else {
                $users = $userModel->getAllUsers();
            }
            
            // Make sure we have an array
            if (!is_array($users)) {
                $users = [];
            }
            
            // Apply search filter
            if (!empty($searchQuery)) {
                $users = array_filter($users, function($user) use ($searchQuery) {
                    $search = strtolower($searchQuery);
                    return strpos(strtolower($user['first_name'] ?? ''), $search) !== false ||
                           strpos(strtolower($user['last_name'] ?? ''), $search) !== false ||
                           strpos(strtolower($user['email'] ?? ''), $search) !== false;
                });
            }
            
        } catch (Exception $e) {
            error_log("Error getting users: " . $e->getMessage());
            $users = []; // Make sure users is an empty array on error
        }
        
        // IMPORTANT: Prepare data array for the view
        $data = [
            'users' => $users,
            'roleFilter' => $roleFilter,
            'searchQuery' => $searchQuery
        ];
        
        // Load the users management view with the data
        $this->view('actors/admin/users', $data);
    }
    
    /**
     * View user details (AJAX)
     */
    public function viewUser($userId = null)
    {
        header('Content-Type: application/json');
        
        if (!$userId) {
            echo json_encode(['success' => false, 'message' => 'User ID is required']);
            exit;
        }
        
        $userModel = $this->model('User');
        
        try {
            $user = $userModel->getUserById($userId);
            
            if ($user) {
                unset($user['password_hash']);
                unset($user['password']);
                
                echo json_encode([
                    'success' => true,
                    'user' => $user
                ]);
            } else {
                echo json_encode([
                    'success' => false,
                    'message' => 'User not found'
                ]);
            }
        } catch (Exception $e) {
            error_log("Error viewing user: " . $e->getMessage());
            echo json_encode([
                'success' => false,
                'message' => 'Error retrieving user data'
            ]);
        }
        exit;
    }
    
    /**
     * Activate user account
     */
    public function activateUser($userId = null)
    {
        if (!$userId) {
            header('Location: ' . BASE_URL . '/admin/users?error=missing_id');
            exit;
        }
        
        $userModel = $this->model('User');
        
        try {
            $result = $userModel->activateUser($userId);
            
            if ($result) {
                header('Location: ' . BASE_URL . '/admin/users?success=activated');
            } else {
                header('Location: ' . BASE_URL . '/admin/users?error=activate_failed');
            }
        } catch (Exception $e) {
            error_log("Error activating user: " . $e->getMessage());
            header('Location: ' . BASE_URL . '/admin/users?error=activate_failed');
        }
        exit;
    }
    
    /**
     * Deactivate user account
     */
    public function deactivateUser($userId = null)
    {
        if (!$userId) {
            header('Location: ' . BASE_URL . '/admin/users?error=missing_id');
            exit;
        }
        
        $userModel = $this->model('User');
        
        try {
            $result = $userModel->deactivateUser($userId);
            
            if ($result) {
                header('Location: ' . BASE_URL . '/admin/users?success=deactivated');
            } else {
                header('Location: ' . BASE_URL . '/admin/users?error=deactivate_failed');
            }
        } catch (Exception $e) {
            error_log("Error deactivating user: " . $e->getMessage());
            header('Location: ' . BASE_URL . '/admin/users?error=deactivate_failed');
        }
        exit;
    }
    
    /**
     * Update user details
     */
    public function updateUser($userId = null)
    {
        // Debug logging
        error_log("=== UPDATE USER DEBUG ===");
        error_log("User ID: " . ($userId ?? 'NULL'));
        error_log("Request Method: " . $_SERVER['REQUEST_METHOD']);
        error_log("POST Data: " . print_r($_POST, true));
        
        if (!$userId || $_SERVER['REQUEST_METHOD'] !== 'POST') {
            error_log("ERROR: Missing user ID or not POST request");
            header('Location: ' . BASE_URL . '/admin/users?error=missing_id');
            exit;
        }
        
        $userModel = $this->model('User');
        
        // Validate input
        $first_name = trim($_POST['first_name'] ?? '');
        $middle_name = trim($_POST['middle_name'] ?? '');
        $last_name = trim($_POST['last_name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $username = trim($_POST['username'] ?? '');
        $phone = trim($_POST['phone'] ?? '');
        $gender = $_POST['gender'] ?? '';
        $date_of_birth = $_POST['date_of_birth'] ?? null;
        $user_type = $_POST['user_type'] ?? '';
        $account_status = $_POST['account_status'] ?? 'active';
        
        error_log("Validating data...");
        error_log("First name: $first_name, Last name: $last_name, Email: $email");
        
        // Validation
        if (empty($first_name) || empty($last_name) || empty($email)) {
            error_log("ERROR: Validation failed - missing required fields");
            header('Location: ' . BASE_URL . '/admin/users?error=validation_failed');
            exit;
        }
        
        // Validate phone number if provided (Sri Lankan format)
        if (!empty($phone)) {
            if (!preg_match('/^\+94\d{9}$/', $phone)) {
                error_log("ERROR: Invalid phone number format");
                header('Location: ' . BASE_URL . '/admin/users?error=invalid_phone');
                exit;
            }
        }
        
        // Check if email already exists for another user
        if ($userModel->emailExists($email, $userId)) {
            error_log("ERROR: Email already exists for another user");
            header('Location: ' . BASE_URL . '/admin/users?error=email_exists');
            exit;
        }
        
        try {
            $updateData = [
                'first_name' => $first_name,
                'last_name' => $last_name,
                'email' => $email,
                'user_type' => $user_type,
                'account_status' => $account_status
            ];
            
            // Only add optional fields if they're not empty
            if (!empty($middle_name)) {
                $updateData['middle_name'] = $middle_name;
            }
            
            if (!empty($username)) {
                $updateData['username'] = $username;
            }
            
            if (!empty($phone)) {
                $updateData['phone'] = $phone;
            }
            
            if (!empty($gender)) {
                $updateData['gender'] = $gender;
            }
            
            if (!empty($date_of_birth)) {
                $updateData['date_of_birth'] = $date_of_birth;
            }
            
            error_log("Attempting to update user with data: " . print_r($updateData, true));
            
            $result = $userModel->updateUser($userId, $updateData);
            
            error_log("Update result: " . ($result ? 'SUCCESS' : 'FAILED'));
            
            if ($result) {
                header('Location: ' . BASE_URL . '/admin/users?success=updated');
            } else {
                header('Location: ' . BASE_URL . '/admin/users?error=update_failed');
            }
        } catch (Exception $e) {
            error_log("EXCEPTION updating user: " . $e->getMessage());
            error_log("Stack trace: " . $e->getTraceAsString());
            header('Location: ' . BASE_URL . '/admin/users?error=update_failed');
        }
        
        error_log("=== END UPDATE USER DEBUG ===");
        exit;
    }
    
    /**
     * Delete user account
     */
    public function deleteUser($userId = null)
    {
        error_log("=== DELETE USER DEBUG ===");
        error_log("User ID: " . ($userId ?? 'NULL'));
        
        if (!$userId) {
            error_log("ERROR: Missing user ID");
            header('Location: ' . BASE_URL . '/admin/users?error=missing_id');
            exit;
        }
        
        $userModel = $this->model('User');
        
        try {
            error_log("Attempting to delete user: " . $userId);
            $result = $userModel->deleteUser($userId);
            error_log("Delete result: " . ($result ? 'SUCCESS' : 'FAILED'));
            
            if ($result) {
                header('Location: ' . BASE_URL . '/admin/users?success=deleted');
            } else {
                header('Location: ' . BASE_URL . '/admin/users?error=delete_failed');
            }
        } catch (Exception $e) {
            error_log("EXCEPTION deleting user: " . $e->getMessage());
            header('Location: ' . BASE_URL . '/admin/users?error=delete_failed');
        }
        
        error_log("=== END DELETE USER DEBUG ===");
        exit;
    }
    
    /**
     * Export users to CSV
     */
    public function exportUsers()
    {
        $userModel = $this->model('User');
        
        try {
            $users = $userModel->getAllUsers();
            
            header('Content-Type: text/csv');
            header('Content-Disposition: attachment; filename="users_export_' . date('Y-m-d') . '.csv"');
            
            $output = fopen('php://output', 'w');
            
            fputcsv($output, ['ID', 'First Name', 'Last Name', 'Email', 'Role', 'Status', 'Registered Date']);
            
            foreach ($users as $user) {
                fputcsv($output, [
                    $user['user_id'],
                    $user['first_name'] ?? '',
                    $user['last_name'] ?? '',
                    $user['email'],
                    ucfirst($user['user_type']),
                    ucfirst($user['account_status'] ?? 'active'),
                    date('Y-m-d H:i:s', strtotime($user['created_at']))
                ]);
            }
            
            fclose($output);
            exit;
            
        } catch (Exception $e) {
            error_log("Error exporting users: " . $e->getMessage());
            header('Location: ' . BASE_URL . '/admin/users?error=export_failed');
            exit;
        }
    }
    
    /**
     * Manage articles
     */
    public function articles()
    {
        $articleModel = $this->model('AarticleModel');
        
        // Get all articles
        $articles = $articleModel->getAllArticles();
        
        $data = [
            'articles' => $articles
        ];
        
        $this->view('actors/admin/articles', $data);
    }
    
    /**
     * Manage registrations
     */
    public function registrations()
    {
        $userModel = $this->model('User');
        
        // Get pending registrations (users with status 'pending' or similar)
        $pendingUsers = [];
        try {
            // Since getPendingUsers doesn't exist, we'll get all users and filter
            // or show all users for now
            $pendingUsers = $userModel->getUsersByType(null);
        } catch (Exception $e) {
            error_log("Error getting pending users: " . $e->getMessage());
        }
        
        $data = [
            'pendingUsers' => $pendingUsers
        ];
        
        $this->view('actors/admin/registrations', $data);
    }
    
    /**
     * Manage forums
     */
    public function forums()
    {
        // TODO: Implement forum management
        $data = [
            'forums' => []
        ];
        
        $this->view('actors/admin/forums', $data);
    }
    
    /**
     * Manage notifications
     */
    public function notifications()
    {
        // TODO: Implement notification management
        $data = [
            'notifications' => []
        ];
        
        $this->view('actors/admin/notifications', $data);
    }
    
    /**
     * Admin settings
     */
    public function settings()
    {
        $data = [];
        
        $this->view('actors/admin/settings', $data);
    }
    
    /**
     * Approve a user registration
     */
    public function approveUser($userId = null)
    {
        if (!$userId) {
            header('Location: ' . BASE_URL . '/admin/registrations');
            return;
        }
        
        $userModel = $this->model('User');
        // Update user status to approved (if such field exists)
        try {
            $result = $userModel->updateUser($userId, ['status' => 'approved']);
            header('Location: ' . BASE_URL . '/admin/registrations?success=approved');
        } catch (Exception $e) {
            error_log("Error approving user: " . $e->getMessage());
            header('Location: ' . BASE_URL . '/admin/registrations?error=approve_failed');
        }
    }
    
    /**
     * Reject a user registration
     */
    public function rejectUser($userId = null)
    {
        if (!$userId) {
            header('Location: ' . BASE_URL . '/admin/registrations');
            return;
        }
        
        $userModel = $this->model('User');
        // Update user status to rejected
        try {
            $result = $userModel->updateUser($userId, ['status' => 'rejected']);
            header('Location: ' . BASE_URL . '/admin/registrations?success=rejected');
        } catch (Exception $e) {
            error_log("Error rejecting user: " . $e->getMessage());
            header('Location: ' . BASE_URL . '/admin/registrations?error=reject_failed');
        }
    }
    
    
    /**
     * Update article status
     */
    public function updateArticleStatus()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . '/admin/articles');
            return;
        }
        
        $articleId = $_POST['article_id'] ?? null;
        $status = $_POST['status'] ?? null;
        
        if (!$articleId || !$status) {
            header('Location: ' . BASE_URL . '/admin/articles?error=missing_data');
            return;
        }
        
        $articleModel = $this->model('AarticleModel');
        $result = $articleModel->updateArticleStatus($articleId, $status);
        
        if ($result) {
            header('Location: ' . BASE_URL . '/admin/articles?success=updated');
        } else {
            header('Location: ' . BASE_URL . '/admin/articles?error=update_failed');
        }
    }
    
    /**
     * Helper method to get articles count
     */
    private function getArticlesCount()
    {
        try {
            $articleModel = $this->model('AarticleModel');
            return $articleModel->getTotalArticlesCount();
        } catch (Exception $e) {
            error_log("Error getting articles count: " . $e->getMessage());
            return 0;
        }
    }
    
    /**
     * Helper method to get pending registrations count
     */
    private function getPendingRegistrationsCount()
    {
        try {
            $userModel = $this->model('User');
            $users = $userModel->getUsersByType(null);
            // Count users with status 'pending' if that field exists
            return is_array($users) ? count($users) : 0;
        } catch (Exception $e) {
            error_log("Error getting pending registrations: " . $e->getMessage());
            return 0;
        }
    }
}
