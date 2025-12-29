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
                // Login successful - Set ALL session variables
                $_SESSION['user_id'] = $user['user_id'];
                $_SESSION['user_type'] = $user['user_type'];
                $_SESSION['user_role'] = $user['user_type'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['user_name'] = $user['first_name']; // ✅ Changed to just first name
                $_SESSION['first_name'] = $user['first_name'];
                $_SESSION['last_name'] = $user['last_name'];
                $_SESSION['full_name'] = $user['first_name'] . ' ' . $user['last_name']; // ✅ Added full name separately
                $_SESSION['email'] = $user['email']; // ✅ Email
                
                // Log successful login
                error_log("User logged in: " . $user['username'] . " (ID: " . $user['user_id'] . ")");
                
                // Redirect based on user type
                switch ($user['user_type']) {
                    case 'alumni':
                        header('Location: ' . BASE_URL . '/alumni');
                        break;
                    case 'undergraduate':
                        header('Location: ' . BASE_URL . '/uhome');
                        break;
                    case 'school_leaver':
                        header('Location: ' . BASE_URL . '/schoolleaver');
                        break;
                    case 'company':
                        header('Location: ' . BASE_URL . '/company');
                        break;
                    case 'admin':
                        header('Location: ' . BASE_URL . '/admin');
                        break;
                    default:
                        header('Location: ' . BASE_URL . '/home');
                }
                exit;
            } else {
                $this->view('/auth/login', ['error' => 'Invalid username or password']);
            }
            
        } catch (Exception $e) {
            error_log("Login error: " . $e->getMessage());
            $this->view('/auth/login', ['error' => 'Login error: ' . $e->getMessage()]);
        }
    }
}
