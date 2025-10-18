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
            
            // Debug logging
            error_log("User creation result - User ID: " . ($userId ? $userId : 'FAILED'));
            error_log("User type: " . $userData['user_type']);
            
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
        
        return $data;
    }
    
    private function createRoleProfile($userId, $userData) {
        switch ($userData['user_type']) {
            case 'alumni':
                $alumniModel = new Alumni();
                $profileData = [
                    'user_id' => $userId,
                    'university' => $userData['university_name'] ?? null,
                    'faculty' => $userData['faculty'] ?? null,
                    'graduation_year' => $userData['graduation_year'] ?? null,
                    'field_of_study' => $userData['degree_program'] ?? null
                ];
                $alumniModel->createProfile($profileData);
                break;
                
            case 'undergraduate':
            case 'student':
                // Handle undergraduate profile creation
                $undergraduateModel = new UndergraduateProfile();  // ✅ Changed from Undergraduate
                $profileData = [
                    'user_id' => $userId,
                    'university' => $userData['university_name'] ?? null,
                    'faculty' => $userData['faculty'] ?? null,
                    'degree_program' => $userData['degree_program'] ?? null,
                    'academic_year' => $userData['academic_year'] ?? null,
                    'expected_graduation_year' => $userData['expected_graduation_year'] ?? null,
                    'interests' => $userData['skills_interests'] ?? null  // ✅ Removed 'skills' field
                ];
                $undergraduateModel->createProfile($profileData);
                break;
                
            case 'company':
            case 'employer':
                // Handle company profile creation
                $companyModel = new CompanyProfile();
                $profileData = [
                    'user_id' => $userId,
                    'company_name' => $userData['company_name'] ?? null,
                    'company_size' => $userData['company_size'] ?? null,
                    'industry' => $userData['industry'] ?? null,
                    'website' => $userData['website'] ?? null,
                    'founded_year' => $userData['founded_year'] ?? null,
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
