<?php 

class Registration extends Controller {

    public function index() {
        // Handle POST request for form submission
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->handleRegistration();
        } else {
            // Show registration form
            $this->view('/auth/registration');
        }
    }

    private function handleRegistration() {
        try {
            // Validate and sanitize input data
            $userData = $this->validateUserData($_POST);
            
            // Check if user already exists
            $userModel = new User();
            
            if ($userModel->getUserByEmail($userData['email'])) {
                $this->view('/auth/registration', ['error' => 'Email already exists']);
                return;
            }
            
            if ($userModel->getUserByUsername($userData['username'])) {
                $this->view('/auth/registration', ['error' => 'Username already exists']);
                return;
            }

            // Hash password
            $userData['password_hash'] = password_hash($userData['password'], PASSWORD_DEFAULT);
            unset($userData['password']);
            unset($userData['confirmPassword']);
            
            // Create user account
            $userId = $userModel->createUser($userData);
            
            if ($userId) {
                // Handle role-specific profile creation
                $this->createRoleProfile($userId, $userData);
                
                // Redirect to login page with success message
                $_SESSION['registration_success'] = 'Registration successful! Please log in with your credentials.';
                header('Location: ' . BASE_URL . '/login');
                exit;
            } else {
                $this->view('/auth/registration', ['error' => 'Registration failed. Please try again.']);
            }
            
        
        } catch (Exception $e) {
            $this->view('/auth/registration', ['error' => 'Registration error: ' . $e->getMessage()]);
        }
    }
    
    private function validateUserData($data) {
        $required = ['username', 'email', 'password', 'confirmPassword', 'first_name', 'last_name', 'user_type'];
        
        foreach ($required as $field) {
            if (empty($data[$field])) {
                throw new Exception("$field is required");
            }
        }
        
        if ($data['password'] !== $data['confirmPassword']) {
            throw new Exception("Passwords do not match");
        }
        
        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            throw new Exception("Invalid email format");
        }
        
        return $data;
    }
    
    private function createRoleProfile($userId, $userData) {
        switch ($userData['user_type']) {
            case 'alumni':
                $alumniModel = new Alumni();
                $profileData = [
                    'user_id' => $userId,
                    'university' => $userData['university'] ?? null,
                    'faculty' => $userData['faculty'] ?? null,
                    'graduation_year' => $userData['graduation_year'] ?? null,
                    'field_of_study' => $userData['field_of_study'] ?? null
                ];
                $alumniModel->createProfile($profileData);
                break;
                
            // Add other role profile creation here as needed
            case 'undergraduate':
                // Handle undergraduate profile creation
                break;
                
            case 'company':
                // Handle company profile creation
                break;
        }
    }
}
