<?php 

class Aeditprofile extends Controller {
    
    private $alumniModel;
    private $userModel;
    
    public function __construct() {
        // Start session if not already started
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        $this->alumniModel = $this->model('AlumniModel');
        $this->userModel = $this->model('User');
    }
    
    public function index() {
        // Check if user is logged in as alumni
        if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'alumni') {
            header('Location: ' . BASE_URL . '/login');
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Route to delete action if the hidden field is set
            if (isset($_POST['action']) && $_POST['action'] === 'delete_picture') {
                $this->handleDeleteProfilePicture();
            } else {
                $this->handleProfileUpdate();
            }
        } else {
            $this->showEditForm();
        }
    }

    private function showEditForm() {
        $userId = $_SESSION['user_id'];
        
        // Get user data from database using AlumniModel
        $userData = $this->alumniModel->getUserById($userId);
        
        // Prepare data for view
        $data = [
            'user' => $userData,
            'error' => $_SESSION['profile_error'] ?? null,
            'success' => $_SESSION['profile_success'] ?? null,
            'title' => 'Edit Profile'
        ];
        
        // Clear session messages
        unset($_SESSION['profile_error']);
        unset($_SESSION['profile_success']);
        
        $this->view('actors/alumni/AEditProfile', $data);
    }

    private function handleProfileUpdate() {
        try {
            $userId = $_SESSION['user_id'];
            
            // Check if this is a profile picture upload only
            if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] === UPLOAD_ERR_OK) {
                // Check if other required fields are missing (indicates it's just picture upload)
                if (empty($_POST['first_name']) && empty($_POST['current_role'])) {
                    error_log("Profile picture only upload detected for alumni user: " . $userId);
                    $this->handleProfilePictureUpload($userId);
                    $_SESSION['profile_success'] = 'Profile picture updated successfully!';
                    // Redirect to profile page so user sees the updated picture
                    header('Location: ' . BASE_URL . '/alumni/profile?updated=' . time());
                    exit();
                }
            }
            
            // Validate profile data
            $validatedData = $this->validateProfileData($_POST);
            
            // Prepare data for AlumniModel update
            $updateData = [
                'first_name' => $validatedData['first_name'] ?? null,
                'last_name' => $validatedData['last_name'] ?? null,
                'phone' => $validatedData['phone'] ?? null,
                'current_role' => $validatedData['current_role'] ?? null,
                'company' => $validatedData['company'] ?? null,
                'university_name' => $validatedData['university_name'] ?? null,
                'degree_program' => $validatedData['degree_program'] ?? null,
                'graduation_year' => $validatedData['graduation_year'] ?? null,
                'linkedin_url' => $validatedData['linkedin_url'] ?? null,
                'github_url' => $validatedData['github_url'] ?? null,
                'portfolio_url' => $validatedData['portfolio_url'] ?? null,
                'short_bio' => $validatedData['short_bio'] ?? null
            ];
            
            // Handle mentorship availability
            $mentorshipAvailable = isset($_POST['mentorship_available']) && $_POST['mentorship_available'] == '1';
            
            // Remove null values to prevent overwriting with nulls
            $updateData = array_filter($updateData, function($value) {
                return $value !== null && $value !== '';
            });
            
            // Update profile using AlumniModel
            $this->alumniModel->updateProfile($userId, $updateData);
            
            // Update mentorship availability
            $this->alumniModel->updateMentorshipAvailability($userId, $mentorshipAvailable);
            
            // Handle profile picture if uploaded with form
            if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] === UPLOAD_ERR_OK) {
                $this->handleProfilePictureUpload($userId);
            }
            
            $_SESSION['profile_success'] = 'Profile updated successfully!';
            header('Location: ' . BASE_URL . '/alumni/profile');
            exit();
            
        } catch (Exception $e) {
            error_log("Alumni profile update error: " . $e->getMessage());
            $_SESSION['profile_error'] = 'Error updating profile: ' . $e->getMessage();
            header('Location: ' . BASE_URL . '/aeditprofile');
            exit();
        }
    }

    private function validateProfileData($data) {
        // Validate phone number format if provided (Sri Lankan format)
        if (!empty($data['phone'])) {
            if (!preg_match('/^\+94\d{9}$/', $data['phone'])) {
                throw new Exception("Phone number must be in format +94xxxxxxxxx (e.g., +94771234567)");
            }
        }
        
        // Validate URLs if provided
        if (!empty($data['linkedin_url']) && !filter_var($data['linkedin_url'], FILTER_VALIDATE_URL)) {
            throw new Exception("Invalid LinkedIn URL format");
        }
        
        if (!empty($data['github_url']) && !filter_var($data['github_url'], FILTER_VALIDATE_URL)) {
            throw new Exception("Invalid GitHub URL format");
        }
        
        if (!empty($data['portfolio_url']) && !filter_var($data['portfolio_url'], FILTER_VALIDATE_URL)) {
            throw new Exception("Invalid Portfolio URL format");
        }
        
        // Validate graduation year if provided
        if (!empty($data['graduation_year'])) {
            $year = intval($data['graduation_year']);
            if ($year < 1950 || $year > 2030) {
                throw new Exception("Graduation year must be between 1950 and 2030");
            }
        }
        
        return $data;
    }

    private function handleProfilePictureUpload($userId) {
        $file = $_FILES['profile_picture'];

        // BUG-003 FIX: Use finfo for server-side MIME check (cannot be spoofed by browser)
        $finfo = new finfo(FILEINFO_MIME_TYPE);
        $detectedMime = $finfo->file($file['tmp_name']);
        $allowedMimes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];

        if (!in_array($detectedMime, $allowedMimes)) {
            throw new Exception("Invalid file type. Only JPG, PNG, GIF, and WebP are allowed.");
        }

        // BUG-004 FIX: Derive extension from detected MIME, not from user-supplied filename
        $mimeToExt = [
            'image/jpeg' => 'jpg',
            'image/png'  => 'png',
            'image/gif'  => 'gif',
            'image/webp' => 'webp',
        ];
        $extension = $mimeToExt[$detectedMime];

        $maxSize = 5 * 1024 * 1024; // 5MB
        if ($file['size'] > $maxSize) {
            throw new Exception("File size must be less than 5MB");
        }

        $publicDir = dirname(dirname(__DIR__)) . '/public/';
        $uploadDir = $publicDir . 'assets/images/profiles/';

        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        // BUG-002 FIX: Delete the old profile picture file before saving the new one
        $currentUser = $this->alumniModel->getUserById($userId);
        if ($currentUser && !empty($currentUser->profile_picture)) {
            $oldFile = $publicDir . ltrim($currentUser->profile_picture, '/');
            if (file_exists($oldFile)) {
                unlink($oldFile);
            }
        }

        $filename = 'profile_' . $userId . '_' . time() . '.' . $extension;
        $uploadPath = $uploadDir . $filename;

        if (move_uploaded_file($file['tmp_name'], $uploadPath)) {
            $dbPath = '/assets/images/profiles/' . $filename;
            $this->alumniModel->updateProfilePicture($userId, $dbPath);
        } else {
            throw new Exception("Failed to upload profile picture");
        }
    }

    // BUG-001 FIX: Handle profile picture deletion
    private function handleDeleteProfilePicture() {
        try {
            $userId = $_SESSION['user_id'];
            $publicDir = dirname(dirname(__DIR__)) . '/public/';

            $currentUser = $this->alumniModel->getUserById($userId);
            if ($currentUser && !empty($currentUser->profile_picture)) {
                $oldFile = $publicDir . ltrim($currentUser->profile_picture, '/');
                if (file_exists($oldFile)) {
                    unlink($oldFile);
                }
            }

            $this->alumniModel->updateProfilePicture($userId, null);
            $_SESSION['profile_success'] = 'Profile picture removed.';
        } catch (Exception $e) {
            error_log('Delete profile picture error: ' . $e->getMessage());
            $_SESSION['profile_error'] = 'Failed to remove profile picture.';
        }

        header('Location: ' . BASE_URL . '/aeditprofile');
        exit();
    }
}
