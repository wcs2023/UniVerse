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
            // DEBUG: Check RAW $_POST data
            error_log("===== RAW POST DATA =====");
            error_log("university_name in POST: '" . ($_POST['university_name'] ?? 'NOT SET') . "'");
            error_log("degree_program in POST: '" . ($_POST['degree_program'] ?? 'NOT SET') . "'");
            error_log("===========================");
            
            // Validate and sanitize input data
            $userData = $this->validateUserData($_POST);
            
            // Check if user already exists
            $userModel = new User();
            
            if ($userModel->getUserByEmail($userData['email'])) {
                $this->view('/auth/registration', [
                    'error' => 'This email address is already registered. Please use a different email or try logging in.'
                ]);
                return;
            }
            
            if ($userModel->getUserByUsername($userData['username'])) {
                $this->view('/auth/registration', [
                    'error' => 'This username is already taken. Please choose a different username.'
                ]);
                return;
            }

            // Hash password
            $userData['password_hash'] = password_hash($userData['password'], PASSWORD_DEFAULT);
            unset($userData['password']);
            unset($userData['confirmPassword']);
            
            // Create user account
            $userId = $userModel->createUser($userData);
            
            // // Debug logging
            // error_log("User creation result - User ID: " . ($userId ? $userId : 'FAILED'));
            // error_log("User type: " . $userData['user_type']);
            
            if ($userId) {
                // Handle role-specific profile creation
                try {
                    $this->createRoleProfile($userId, $userData);
                } catch (Exception $profileError) {
                    // Log the error but continue - basic user account is created
                    error_log("Profile creation error for user $userId: " . $profileError->getMessage());
                    // Optionally, you could show this error to the user
                    // For now, we'll still redirect to login
                }
                
                // Redirect to login page with success message
                $_SESSION['registration_success'] = 'Registration successful! Please log in with your credentials.';
                
                // Use ob_clean to clear any output buffers before redirect
                if (ob_get_level()) {
                    ob_clean();
                }
                
                header('Location: ' . BASE_URL . '/login');
                exit();
            } else {
                // User creation failed - show detailed error
                error_log("User creation failed - database returned false");
                $this->view('/auth/registration', [
                    'error' => 'Registration failed. Unable to create user account. Please try again or contact support if the problem persists.'
                ]);
            }
            
        
        } catch (Exception $e) {
            // Validation or other errors
            error_log("Registration exception: " . $e->getMessage());
            
            $this->view('/auth/registration', [
                'error' => 'Registration error: ' . $e->getMessage()
            ]);
        }
    }
    
    private function validateUserData($data) {
        $required = ['username', 'email', 'password', 'confirmPassword', 'first_name', 'last_name', 'user_type'];
        
        foreach ($required as $field) {
            if (empty($data[$field])) {
                $fieldName = ucwords(str_replace('_', ' ', $field));
                throw new Exception("$fieldName is required. Please fill in all required fields.");
            }
        }
        
        if ($data['password'] !== $data['confirmPassword']) {
            throw new Exception("Passwords do not match. Please make sure both password fields are identical.");
        }
        
        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            throw new Exception("Invalid email format. Please enter a valid email address.");
        }
        
        if (strlen($data['password']) < 8) {
            throw new Exception("Password must be at least 8 characters long.");
        }
        
        // Validate phone number if provided (Sri Lankan format only)
        if (!empty($data['phone'])) {
            if (!preg_match('/^\+94\d{9}$/', $data['phone'])) {
                throw new Exception("Phone number must be in format +94xxxxxxxxx (e.g., +94771234567).");
            }
        }
        
        // Validate contact phone for company if provided
        if (!empty($data['contact_phone'])) {
            if (!preg_match('/^\+94\d{9}$/', $data['contact_phone'])) {
                throw new Exception("Contact phone number must be in format +94xxxxxxxxx (e.g., +94771234567).");
            }
        }
        return $data;
    }
    
    private function createRoleProfile($userId, $userData) {
        switch ($userData['user_type']) {
            case 'alumni':
                //  Use AlumniModel instead of Alumni
                $alumniModel = $this->model('AlumniModel');
                $profileData = [
                    'user_id' => $userId,
                    'alumni_university_name' => $userData['alumni_university_name'] ?? null,
                    'alumni_degree_program' => $userData['alumni_degree_program'] ?? null,
                    'graduation_year' => $userData['graduation_year'] ?? null,
                    'additional_degree_1' => $userData['additional_degree_1'] ?? null,
                    'additional_university_1' => $userData['additional_university_1'] ?? null,
                    'additional_grad_year_1' => $userData['additional_grad_year_1'] ?? null,
                    'additional_degree_2' => $userData['additional_degree_2'] ?? null,
                    'additional_university_2' => $userData['additional_university_2'] ?? null,
                    'additional_grad_year_2' => $userData['additional_grad_year_2'] ?? null,
                    'current_job_title' => $userData['current_job_title'] ?? null,
                    'current_company' => $userData['current_company'] ?? null,
    
                ];
                $alumniModel->createProfile($profileData);
                break;
                
            case 'undergraduate':
            case 'student':
                      
                $undergraduateModel = $this->model('UndergraduateProfile');
                $profileData = [
                    'user_id' => $userId,
                    'university' => $userData['university_name'] ?? null,
                    'faculty' => $userData['faculty'] ?? null,
                    'degree_program' => $userData['degree_program'] ?? null,
                    'academic_year' => $userData['academic_year'] ?? null,
                    'expected_graduation_year' => $userData['expected_graduation_year'] ?? null,
                ];
                         
                $undergraduateModel->createProfile($profileData);
                break;
                
            case 'company':
            case 'employer':
                //  Use the model() method to load the model
                $companyModel = $this->model('CompanyProfile');
                $profileData = [
                    'user_id' => $userId,
                    'company_name' => $userData['company_name'] ?? null,
                    'company_size' => $userData['company_size'] ?? null,
                    'company_description' => $userData['description'] ?? null,
                    'contact_person' => $userData['contact_person_name'] ?? null,
                    'contact_email' => $userData['contact_email'] ?? null,
                    'contact_phone' => $userData['contact_phone'] ?? null
                ];
                $companyModel->createProfile($profileData);
                break;
                
            case 'school_leaver':
                // School leavers use basic user profile only
                // No additional profile table needed
                break;
        }
    }
}