<?php 

class Login extends Controller {

    public function index() {
        // Handle POST request for login submission
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->handleLogin();
        } else {
            // Prepare data for the view
            $data = [];
            
            // Check for registration success message
            if (isset($_SESSION['registration_success'])) {
                $data['success'] = $_SESSION['registration_success'];
                unset($_SESSION['registration_success']);
            }
            
            // Show login form
            $this->view('/auth/login', $data);
        }
    }
    
    private function handleLogin() {
        try {
            $username = $_POST['username'] ?? '';
            $password = $_POST['password'] ?? '';
            
            if (empty($username) || empty($password)) {
                $this->view('/auth/login', ['error' => 'Username and password are required']);
                return;
            }
            
            $userModel = new User();
            $user = $userModel->getUserByUsername($username);
            
            if ($user && password_verify($password, $user['password_hash'])) {
                // Login successful
                $_SESSION['user_id'] = $user['user_id'];
                $_SESSION['user_type'] = $user['user_type'];
                $_SESSION['username'] = $user['username'];
                
                // Redirect based on user type
                switch ($user['user_type']) {
                    case 'alumni':
                        header('Location: ' . BASE_URL . '/uhome');
                        break;
                    case 'undergraduate':
                        header('Location: ' . BASE_URL . '/uhome');
                        break;
                    case 'company':
                        header('Location: ' . BASE_URL . '/');
                        break;
                    case 'admin':
                        header('Location: ' . BASE_URL . '/admin');
                        break;
                    default:
                        header('Location: ' . BASE_URL . '/');
                }
                exit;
            } else {
                $this->view('/auth/login', ['error' => 'Invalid username or password']);
            }
            
        } catch (Exception $e) {
            $this->view('/auth/login', ['error' => 'Login error: ' . $e->getMessage()]);
        }
    }
}
