<?php 

class USettings extends Controller {
    
    public function index() {
        // Check if user is logged in
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . BASE_URL . '/login');
            exit();
        }

        $userId = $_SESSION['user_id'];
        
        // ✅ Get user data from database
        $userModel = new User();
        $user = $userModel->getUserById($userId);
        
        // ✅ Get undergraduate profile data
        $undergraduateModel = new UndergraduateProfile();
        $profile = $undergraduateModel->getProfileByUserId($userId);
        
        // Prepare data for the view
        $data = [
            'user' => $user ?? [],
            'profile' => $profile ?? [],
            'error' => $_SESSION['settings_error'] ?? null,
            'success' => $_SESSION['settings_success'] ?? null,
            'title' => 'Settings'
        ];
        
        // Clear session messages
        unset($_SESSION['settings_error']);
        unset($_SESSION['settings_success']);
        
        // Load the undergraduate Settings view with data
        $this->view('actors/undergraduate/USettings', $data);
    }

    /**
     * Handle password change
     */
    public function changePassword() {
        // Check if user is logged in
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . BASE_URL . '/login');
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . '/usettings');
            exit();
        }

        try {
            $userId = $_SESSION['user_id'];
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
            $userModel = new User();
            $user = $userModel->getUserById($userId);

            if (!password_verify($currentPassword, $user['password'])) {
                throw new Exception('Current password is incorrect');
            }

            // Update password
            $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
            $updated = $userModel->updatePassword($userId, $hashedPassword);

            if ($updated) {
                $_SESSION['settings_success'] = 'Password updated successfully!';
            } else {
                throw new Exception('Failed to update password');
            }

        } catch (Exception $e) {
            error_log("Password change error: " . $e->getMessage());
            $_SESSION['settings_error'] = $e->getMessage();
        }

        header('Location: ' . BASE_URL . '/usettings');
        exit();
    }
}
