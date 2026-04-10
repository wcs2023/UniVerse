<?php

/**
 * Base Mentorships Controller
 * Redirects to role-specific mentorship controllers:
 * - Alumni → Amentorships
 * - Undergraduate/Student → Umentorships
 */
class Mentorships extends Controller
{
    public function __construct()
    {
        // Start session if not already started
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }
    
    /**
     * Redirect to appropriate mentorship controller based on user role
     */
    public function index()
    {
        // Get user information from session
        $user_role = $_SESSION['user_type'] ?? $_SESSION['user_role'] ?? '';
        $user_id = $_SESSION['user_id'] ?? 0;
        
        // If no user_role or user_id, redirect to login
        if (empty($user_role) || empty($user_id)) {
            error_log("Mentorships: No user_role or user_id in session, redirecting to login");
            header("Location: " . BASE_URL . "/login");
            exit;
        }
        
        // Redirect to role-specific controller
        if ($user_role === 'undergraduate' || $user_role === 'student') {
            header("Location: " . BASE_URL . "/umentorships");
            exit;
        } else if ($user_role === 'alumni') {
            header("Location: " . BASE_URL . "/amentorships");
            exit;
        } else {
            // Unknown user role, redirect to login
            error_log("Mentorships: Unknown user_role: " . $user_role);
            header("Location: " . BASE_URL . "/login");
            exit;
        }
    }
    
    /**
     * View a single mentor's profile
     * Accessible to all logged-in users
     */
    public function viewProfile($mentor_id = null)
    {
        // Check if user is logged in
        $user_id = $_SESSION['user_id'] ?? 0;
        if (!$user_id) {
            header("Location: " . BASE_URL . "/login");
            exit;
        }
        
        // Validate mentor_id
        if (!$mentor_id || !is_numeric($mentor_id)) {
            header("Location: " . BASE_URL . "/umentorships/exploreMentors");
            exit;
        }
        
        // Load models
        $alumniModel = $this->model('AlumniModel');
        $mentorshipModel = $this->model('Mentorship');
        
        // Get mentor details from mentors table
        $mentor = $mentorshipModel->getMentorById($mentor_id);
        
        if (!$mentor) {
            header("Location: " . BASE_URL . "/umentorships/exploreMentors");
            exit;
        }
        
        // Get full alumni profile data
        $mentorProfile = $alumniModel->getUserById($mentor['user_id']);
        
        // Get mentor statistics
        $stats = $mentorshipModel->getMentorStats($mentor_id);
        
        // Check if current user has already sent a request to this mentor
        $hasActiveRequest = false;
        if (isset($_SESSION['user_type']) && $_SESSION['user_type'] === 'undergraduate' ||
            isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'undergraduate') {
            $hasActiveRequest = $mentorshipModel->hasActiveRequest($user_id, $mentor_id);
        }
        
        // Load the profile view
        $this->view('mentorship/mentor_profile', [
            'mentor' => $mentor,
            'profile' => $mentorProfile,
            'stats' => $stats,
            'hasActiveRequest' => $hasActiveRequest,
            'current_user_id' => $user_id,
            'user_type' => $_SESSION['user_type'] ?? ''
        ]);
    }
}
