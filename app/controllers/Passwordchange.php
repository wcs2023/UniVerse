<?php 
class PasswordChange extends Controller {
    
    public function index() {
        // Check if we know who the user is (either logged in or verified via reset)
        if (!isset($_SESSION['user_id']) && !isset($_SESSION['reset_user_id'])) {
            // If we don't know who they are, send them back to login
            header('Location: ' . BASE_URL . '/login');
            exit();
        }

        $data = [
            'error'   => $_SESSION['settings_error'] ?? null,
            'success' => $_SESSION['settings_success'] ?? null,
        ];

        unset($_SESSION['settings_error']);
        unset($_SESSION['settings_success']);

        $this->view('auth/passwordchange', $data);
    }

    public function changePassword() {
        $userId = $_SESSION['user_id'] ?? $_SESSION['reset_user_id'] ?? null;

        if (!$userId) {
            header('Location: ' . BASE_URL . '/login');
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . '/Passwordchange');
            exit();
        }

        try {
            $newPassword = $_POST['new_password'] ?? '';
            $confirmPassword = $_POST['confirm_password'] ?? '';

            if (empty($newPassword) || strlen($newPassword) < 8) {
                throw new Exception('Password must be at least 8 characters long');
            }

            if ($newPassword !== $confirmPassword) {
                throw new Exception('Passwords do not match');
            }

            $userModel = new User();
            $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
            
            // This runs the UPDATE query in your User model
            $updated = $userModel->updatePassword($userId, $hashedPassword);

            if ($updated) {
                $_SESSION['settings_success'] = 'Password updated successfully!';
                unset($_SESSION['reset_user_id']); // Clean up
                header('Location: ' . BASE_URL . '/login');
                exit();
            } else {
                throw new Exception('Failed to update database. Is it the same password?');
            }

        } catch (Exception $e) {
            $_SESSION['settings_error'] = $e->getMessage();
            header('Location: ' . BASE_URL . '/Passwordchange');
            exit();
        }
    }
}