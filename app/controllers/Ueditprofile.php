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

    // private function handleProfileUpdate() {
    //     try {
    //         $userId = $_SESSION['user_id'];
            
    //         // Check if this is a profile picture upload (only file, no other fields)
    //         if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] === UPLOAD_ERR_OK) {
    //             // Check if other required fields are missing (indicates it's just picture upload)
    //             if (empty($_POST['first_name']) && empty($_POST['university'])) {
    //                 error_log("Profile picture only upload detected for user: " . $userId);
    //                 $this->handleProfilePictureUpload($userId);
    //                 $_SESSION['profile_success'] = 'Profile picture updated successfully!';
    //                 header('Location: ' . BASE_URL . '/ueditprofile');
    //                 exit();
    //             }
    //         }
            
    //         // Otherwise, handle full profile update
    //         $userData = $this->validateProfileData($_POST);
            
    //         // Update user table - only include non-empty fields
    //         $userModel = new User();
    //         $userUpdateData = [];
            
    //         if (!empty($userData['first_name'])) {
    //             $userUpdateData['first_name'] = $userData['first_name'];
    //         }
    //         if (!empty($userData['last_name'])) {
    //             $userUpdateData['last_name'] = $userData['last_name'];
    //         }
    //         if (!empty($userData['date_of_birth'])) {
    //             $userUpdateData['date_of_birth'] = $userData['date_of_birth'];
    //         }
    //         if (!empty($userData['gender'])) {
    //             $userUpdateData['gender'] = $userData['gender'];
    //         }
    //         if (!empty($userData['phone'])) {
    //             $userUpdateData['phone'] = $userData['phone'];
    //         }
            
    //         if (!empty($userUpdateData)) {
    //             $userModel->updateUser($userId, $userUpdateData);
    //         }
            
    //         // Update undergraduate profile table - only include non-empty fields
    //         $undergraduateModel = new UndergraduateProfile();
    //         $profileUpdateData = [];
            
    //         if (!empty($userData['university'])) {
    //             $profileUpdateData['university'] = $userData['university'];
    //         }
    //         if (!empty($userData['faculty'])) {
    //             $profileUpdateData['faculty'] = $userData['faculty'];
    //         }
    //         if (!empty($userData['degree_program'])) {
    //             $profileUpdateData['degree_program'] = $userData['degree_program'];
    //         }
    //         if (!empty($userData['academic_year'])) {
    //             $profileUpdateData['academic_year'] = $userData['academic_year'];
    //         }
    //         if (!empty($userData['expected_graduation_year'])) {
    //             $profileUpdateData['expected_graduation_year'] = $userData['expected_graduation_year'];
    //         }
            
    //         if (!empty($profileUpdateData)) {
    //             $undergraduateModel->updateProfile($userId, $profileUpdateData);
    //         }
            
    //         $_SESSION['profile_success'] = 'Profile updated successfully!';
    //         header('Location: ' . BASE_URL . '/umyprofile');
    //         exit();
            
    //     } catch (Exception $e) {
    //         error_log("Profile update error: " . $e->getMessage());
    //         $_SESSION['profile_error'] = 'Error updating profile: ' . $e->getMessage();
    //         header('Location: ' . BASE_URL . '/ueditprofile');
    //         exit();
    //     }
    // }

    // private function validateProfileData($data) {
    //     // No required fields - all fields are optional during update
    //     // Validate email format if provided
    //     if (!empty($data['email']) && !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
    //         throw new Exception("Invalid email format");
    //     }
        
    //     // Validate phone number format if provided (Sri Lankan format)
    //     if (!empty($data['phone'])) {
    //         if (!preg_match('/^\+94\d{9}$/', $data['phone'])) {
    //             throw new Exception("Phone number must be in format +94xxxxxxxxx (e.g., +94771234567)");
    //         }
    //     }
    //     return $data;
    // }
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
