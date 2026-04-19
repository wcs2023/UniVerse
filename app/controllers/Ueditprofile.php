<?php 

class Ueditprofile extends Controller {
    
    public function index() {
        // Check if user is logged in
        if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'undergraduate') {
            header('Location: ' . BASE_URL . '/login');
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->handleProfileUpdate();
        } else {
            $this->showEditForm();
        }
    }

    private function showEditForm() {
        // Get user data from database
        $userModel = new User();
        $user = $userModel->getUserById($_SESSION['user_id']);
        
        // Get undergraduate profile data
        $undergraduateModel = new UndergraduateProfile();
        $profile = $undergraduateModel->getProfileByUserId($_SESSION['user_id']);
        
        // Merge user and profile data
        $data = [
            'user' => $user,
            'profile' => $profile,
            'error' => $_SESSION['profile_error'] ?? null,
            'success' => $_SESSION['profile_success'] ?? null,
            'title' => 'Edit Profile'
        ];
        
        // Clear session messages
        unset($_SESSION['profile_error']);
        unset($_SESSION['profile_success']);
        
        $this->view('actors/undergraduate/UEditProfile', $data);
    }

    private function handleProfileUpdate() {
    try {
        $userId = $_SESSION['user_id'];

        // Profile picture only upload
        if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] === UPLOAD_ERR_OK) {
            if (empty($_POST['first_name']) && empty($_POST['university'])) {
                $this->handleProfilePictureUpload($userId);
                $_SESSION['profile_success'] = 'Profile picture updated successfully!';
                header('Location: ' . BASE_URL . '/ueditprofile');
                exit();
            }
        }

        $userData = $this->validateProfileData($_POST);

        // Only pass user-related fields
        $userUpdateData = [];
        foreach (['first_name', 'last_name', 'date_of_birth', 'gender', 'phone'] as $field) {
            if (array_key_exists($field, $userData)) {
                $userUpdateData[$field] = $userData[$field];
            }
        }

        // Only pass profile-related fields
        $profileUpdateData = [];
        foreach (['university', 'faculty', 'degree_program', 'academic_year', 'expected_graduation_year'] as $field) {
            if (array_key_exists($field, $userData)) {
                $profileUpdateData[$field] = $userData[$field];
            }
        }

        $userModel = new User();
        if (!empty($userUpdateData)) {
            $userModel->updateUser($userId, $userUpdateData);
        }

        $undergraduateModel = new UndergraduateProfile();
        if (!empty($profileUpdateData)) {
            $undergraduateModel->updateProfile($userId, $profileUpdateData);
        }

        $_SESSION['profile_success'] = 'Profile updated successfully!';
        header('Location: ' . BASE_URL . '/umyprofile');
        exit();

    } catch (Exception $e) {
            error_log("Profile update error: " . $e->getMessage());
            $_SESSION['profile_error'] = 'Error updating profile: ' . $e->getMessage();
            header('Location: ' . BASE_URL . '/ueditprofile');
            exit();
        }
    }

    private function validateProfileData($data) 
    {
        if (!empty($data['email']) && !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) 
        {
            throw new Exception("Invalid email format");
        }

        if (!empty($data['phone'])) 
        {
            if (!preg_match('/^\+94\d{9}$/', $data['phone'])) 
            {
                throw new Exception("Phone number must be in format +94xxxxxxxxx");
            }
        }

        if (!empty($data['expected_graduation_year'])) 
        {
            $currentYear = (int)date('Y');
            $year = (int)$data['expected_graduation_year'];
        
            if ($year < $currentYear || $year > $currentYear + 9) 
            {
                throw new Exception("Graduation year must be between {$currentYear} and " . ($currentYear + 9));
            }
        }

        return $data;
    }

    private function handleProfilePictureUpload($userId) {
        $file = $_FILES['profile_picture'];
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        $maxSize = 5 * 1024 * 1024; // 5MB

        // Validate file type
        if (!in_array($file['type'], $allowedTypes)) {
            throw new Exception("Invalid file type. Only JPG, PNG, and GIF are allowed.");
        }

        // Validate file size
        if ($file['size'] > $maxSize) {
            throw new Exception("File size must be less than 5MB");
        }

        // Get the absolute path to public directory
        $publicDir = dirname(dirname(__DIR__)) . '/public/';
        $uploadDir = $publicDir . 'assets/images/profiles/';
        
        // Create upload directory if it doesn't exist
        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        // Generate unique filename
        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = 'profile_' . $userId . '_' . time() . '.' . $extension;
        $uploadPath = $uploadDir . $filename;

        // Move uploaded file
        if (move_uploaded_file($file['tmp_name'], $uploadPath)) {
            // Update user profile picture in database
            $userModel = new User();
            $dbPath = '/assets/images/profiles/' . $filename;
            $userModel->updateProfilePicture($userId, $dbPath);
        } else {
            throw new Exception("Failed to upload profile picture");
        }
    }
}
