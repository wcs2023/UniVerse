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
        // Check if user is logged in as alumni (check both user_type and user_role for compatibility)
        $userType = $_SESSION['user_type'] ?? $_SESSION['user_role'] ?? '';
        if (!isset($_SESSION['user_id']) || $userType !== 'alumni') {
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
        
        // If user data not found, show form with error instead of redirecting away
        if (!$userData) {
            error_log("Alumni edit profile: getUserById returned false for user_id: " . $userId);
            // Create a minimal user object so the view doesn't crash
            $userData = (object)[
                'user_id' => $userId,
                'first_name' => $_SESSION['first_name'] ?? '',
                'last_name' => $_SESSION['last_name'] ?? '',
                'email' => $_SESSION['email'] ?? '',
                'phone' => '',
                'date_of_birth' => '',
                'gender' => '',
                'profile_picture' => null,
                'current_role' => '',
                'company' => '',
                'university_name' => '',
                'degree_program' => '',
                'graduation_year' => '',
                'linkedin_url' => '',
                'github_url' => '',
                'portfolio_url' => '',
                'short_bio' => '',
                'available_for_mentorship' => false
            ];
        }
        
        $expertiseCategories = $this->alumniModel->getExpertiseCategories();
        $selectedExpertise = $this->alumniModel->getMentorExpertiseByUserId($userId);

        $ScheduledSessionsCount = $this->alumniModel->getScheduledSessionCount($userId);

        // Prepare data for view
        $data = [
            'user' => $userData,
            'expertiseCategories' => $expertiseCategories,
            'selectedExpertise' => $selectedExpertise,
            'error' => $_SESSION['profile_error'] ?? null,
            'success' => $_SESSION['profile_success'] ?? null,
            'title' => 'Edit Profile',
            'bookingCount' => $ScheduledSessionsCount
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
                    header('Location: ' . BASE_URL . '/aeditprofile');
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
                'date_of_birth' => $validatedData['date_of_birth'] ?? null,
                'gender' => $validatedData['gender'] ?? null,
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
            $mentorExpertise = $validatedData['mentor_expertise'] ?? [];

            if ($mentorshipAvailable && empty($mentorExpertise)) {
                throw new Exception("Please select at least one expertise area when enabling mentorship");
            }
            
            // Remove null values to prevent overwriting with nulls
            $updateData = array_filter($updateData, function($value) {
                if (is_array($value)) {
                    return !empty($value);
                }
                return $value !== null && $value !== '';
            });
            
            // Update profile using AlumniModel
            $this->alumniModel->updateProfile($userId, $updateData);
            
            // Update mentorship availability
            $this->alumniModel->updateMentorshipAvailability($userId, $mentorshipAvailable);

            // Save mentor expertise only for mentorship flow
            if ($mentorshipAvailable) {
                $this->alumniModel->updateMentorExpertise($userId, $mentorExpertise);
            }
            
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

        if (isset($data['mentor_expertise'])) {
            $allowedExpertise = $this->alumniModel->getExpertiseCategoryNames();
            $submitted = is_array($data['mentor_expertise']) ? $data['mentor_expertise'] : [$data['mentor_expertise']];

            $data['mentor_expertise'] = array_values(array_unique(array_filter(array_map('trim', $submitted), function ($item) use ($allowedExpertise) {
                return $item !== '' && in_array($item, $allowedExpertise, true);
            })));
        }

        return $data;
    }

    private function handleProfilePictureUpload($userId) {
        $file = $_FILES['profile_picture'];
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        $maxSize = 5 * 1024 * 1024; // 5MB

        // Validate file type
        if (!in_array($file['type'], $allowedTypes)) {
            throw new Exception("Invalid file type. Only JPG, PNG, GIF, and WebP are allowed.");
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

        // Delete old profile picture if exists
        $currentUser = $this->alumniModel->getUserById($userId);
        if ($currentUser && !empty($currentUser->profile_picture)) {
            $oldFile = $publicDir . ltrim($currentUser->profile_picture, '/');
            if (file_exists($oldFile)) {
                unlink($oldFile);
            }
        }

        // Generate unique filename
        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = 'profile_' . $userId . '_' . time() . '.' . $extension;
        $uploadPath = $uploadDir . $filename;

        // Move uploaded file
        if (move_uploaded_file($file['tmp_name'], $uploadPath)) {
            // Update user profile picture in database
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
