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
        $user_role = $_SESSION['user_role'] ?? '';
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
}
