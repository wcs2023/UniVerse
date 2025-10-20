<?php

class Umentorships extends Controller
{
    private $mentorshipModel;
    
    public function __construct()
    {
        // Start session if not already started
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        // Check if user is logged in and is an undergraduate
        $user_role = $_SESSION['user_role'] ?? '';
        if ($user_role !== 'undergraduate' && $user_role !== 'student') {
            header('Location: ' . BASE_URL . '/login');
            exit;
        }
        
        $this->mentorshipModel = $this->model('Mentorship');
    }
    
    /**
     * Default method - Show my mentorships
     */
    public function index()
    {
        // Get undergraduate_id from session or fetch from database
        $user_id = $_SESSION['user_id'] ?? 0;
        $undergraduate_id = $_SESSION['undergraduate_id'] ?? 0;
        
        // If not in session, try to get it from database using user_id
        if (!$undergraduate_id && $user_id) {
            $undergraduateModel = $this->model('UndergraduateProfile');
            $profile = $undergraduateModel->getProfileByUserId($user_id);
            if ($profile && isset($profile['undergraduate_id'])) {
                $undergraduate_id = $profile['undergraduate_id'];
                $_SESSION['undergraduate_id'] = $undergraduate_id;
                error_log("Umentorships: Retrieved undergraduate_id from database: " . $undergraduate_id);
            }
        }
        
        // Initialize empty mentorships array
        $mentorships = [
            'pending' => [],
            'upcoming' => [],
            'completed' => []
        ];
        
        // Get mentorship requests only if undergraduate_id exists
        if ($undergraduate_id) {
            $mentorships = $this->mentorshipModel->getRequestsByUndergraduate($undergraduate_id);
        } else {
            error_log("Umentorships: No undergraduate_id found, showing empty mentorship page");
        }
        
        // Load view with data
        $this->view('mentorship/Umentorship', [
            'mentorships' => $mentorships,
            'undergraduate_id' => $undergraduate_id
        ]);
    }
    
    /**
     * Request mentorship from an alumni
     */
    public function request($alumni_id = null)
    {
        $user_id = $_SESSION['user_id'] ?? 0;
        $undergraduate_id = $_SESSION['undergraduate_id'] ?? 0;
        
        if (!$undergraduate_id) {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                echo json_encode(['success' => false, 'message' => 'Unauthorized access']);
                return;
            } else {
                header("Location: " . BASE_URL . "/login");
                return;
            }
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // For AJAX requests
            if (!isset($_POST['alumni_id'])) {
                echo json_encode(['success' => false, 'message' => 'Missing alumni ID']);
                return;
            }
            
            $alumni_id = $_POST['alumni_id'];
            $topic = $_POST['topic'] ?? '';
            $message = $_POST['message'] ?? '';
            $expectations = $_POST['expectations'] ?? '';
            
            // Create mentorship request
            $mentorship_id = $this->mentorshipModel->createRequest($undergraduate_id, $alumni_id, $topic, $message, $expectations);
            
            if ($mentorship_id) {
                echo json_encode(['success' => true, 'mentorship_id' => $mentorship_id]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Failed to create request']);
            }
        } else {
            // If alumni_id is provided, show the profile
            if ($alumni_id !== null) {
                // Get alumni information
                $alumniModel = $this->model('AlumniModel');
                $alumni = $alumniModel->getAlumniById($alumni_id);
                
                if (!$alumni) {
                    header("Location: " . BASE_URL . "/umentorships/exploreMentors");
                    return;
                }
                
                $this->view('mentorship/mentor_profile', [
                    'alumni' => $alumni
                ]);
            } else {
                // Show request form with list of alumni
                $alumniModel = $this->model('AlumniModel');
                $alumni_list = $alumniModel->getAvailableAlumni();
                
                $this->view('mentorship/request', [
                    'alumni_list' => $alumni_list
                ]);
            }
        }
    }
    
    /**
     * Get available time slots for a mentorship
     */
    public function getTimeSlots($mentorship_id = null)
    {
        header('Content-Type: application/json');
        
        // Check if mentorship_id is provided
        if ($mentorship_id === null) {
            echo json_encode(['success' => false, 'message' => 'Missing mentorship ID']);
            return;
        }
        
        $undergraduate_id = $_SESSION['undergraduate_id'] ?? 0;
        
        if (!$undergraduate_id) {
            echo json_encode(['success' => false, 'message' => 'Unauthorized access']);
            return;
        }
        
        // Get time slots for this mentorship
        $slots = $this->mentorshipModel->getTimeSlots($mentorship_id);
        
        echo json_encode(['success' => true, 'slots' => $slots]);
    }
    
    /**
     * Schedule a mentorship session by selecting a time slot
     */
    public function schedule()
    {
        header('Content-Type: application/json');
        
        $undergraduate_id = $_SESSION['undergraduate_id'] ?? 0;
        
        if (!$undergraduate_id) {
            echo json_encode(['success' => false, 'message' => 'Unauthorized access']);
            return;
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Check if mentorship_id and slot_id are provided
            if (!isset($_POST['mentorship_id']) || !isset($_POST['slot_id'])) {
                echo json_encode(['success' => false, 'message' => 'Missing required parameters']);
                return;
            }
            
            $mentorship_id = $_POST['mentorship_id'];
            $slot_id = $_POST['slot_id'];
            
            // Get the mentorship to verify it belongs to this undergraduate
            $mentorship = $this->mentorshipModel->getMentorshipById($mentorship_id);
            
            if (!$mentorship || $mentorship['undergraduate_id'] != $undergraduate_id) {
                echo json_encode(['success' => false, 'message' => 'Unauthorized access to this mentorship']);
                return;
            }
            
            // Select the time slot and schedule the session
            $result = $this->mentorshipModel->selectTimeSlot($mentorship_id, $slot_id);
            
            if ($result) {
                echo json_encode(['success' => true]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Failed to schedule session']);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Invalid request method']);
        }
    }
    
    /**
     * Submit feedback for a completed mentorship session
     */
    public function feedback($mentorship_id = null)
    {
        // Check if mentorship_id is provided
        if ($mentorship_id === null) {
            header("Location: " . BASE_URL . "/umentorships");
            return;
        }
        
        $undergraduate_id = $_SESSION['undergraduate_id'] ?? 0;
        
        if (!$undergraduate_id) {
            header("Location: " . BASE_URL . "/login");
            return;
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Get feedback from POST data
            $feedback = $_POST['feedback'] ?? '';
            
            if ($feedback) {
                // Add feedback
                $this->mentorshipModel->addFeedback($mentorship_id, $feedback);
                
                // Update status to completed
                $this->mentorshipModel->updateStatus($mentorship_id, 'completed');
                
                // Redirect with success message
                header("Location: " . BASE_URL . "/umentorships?success=feedback");
            } else {
                // Redirect with error message
                header("Location: " . BASE_URL . "/umentorships/feedback/$mentorship_id?error=empty");
            }
        } else {
            // Show feedback form
            $this->view('mentorship/feedback', [
                'mentorship_id' => $mentorship_id
            ]);
        }
    }
    
    /**
     * Display all available mentors for students to explore
     */
    public function exploreMentors()
    {
        // Get search parameters
        $searchTerm = $_GET['search'] ?? '';
        $industry = $_GET['industry'] ?? '';
        $expertise = $_GET['expertise'] ?? '';
        
        // Get all available mentors
        $mentors = $this->mentorshipModel->getAllMentors($searchTerm, $industry, $expertise);
        
        // Load the explore mentors view
        $this->view('mentorship/Uprofview', [
            'mentors' => $mentors,
            'searchTerm' => $searchTerm,
            'industry' => $industry,
            'expertise' => $expertise
        ]);
    }
    
    /**
     * View mentorship details
     */
    public function viewDetails($mentorship_id = null)
    {
        if (!$mentorship_id) {
            header("Location: " . BASE_URL . "/umentorships");
            exit;
        }
        
        $undergraduate_id = $_SESSION['undergraduate_id'] ?? 0;
        
        // Get mentorship details
        $mentorship = $this->mentorshipModel->getMentorshipById($mentorship_id);
        
        if (!$mentorship) {
            header("Location: " . BASE_URL . "/umentorships");
            exit;
        }
        
        // Verify this mentorship belongs to the current undergraduate
        if ($mentorship['undergraduate_id'] != $undergraduate_id) {
            header("Location: " . BASE_URL . "/umentorships");
            exit;
        }
        
        $this->view('mentorship/mentorship_details', [
            'mentorship' => $mentorship
        ]);
    }
    
    /**
     * Cancel a mentorship request
     */
    public function cancel($mentorship_id = null)
    {
        header('Content-Type: application/json');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Invalid request method']);
            exit;
        }

        if (!$mentorship_id) {
            echo json_encode(['success' => false, 'message' => 'Missing mentorship ID']);
            exit;
        }

        $undergraduate_id = $_SESSION['undergraduate_id'] ?? 0;
        
        // Get mentorship to verify ownership
        $mentorship = $this->mentorshipModel->getMentorshipById($mentorship_id);
        
        if (!$mentorship || $mentorship['undergraduate_id'] != $undergraduate_id) {
            echo json_encode(['success' => false, 'message' => 'Unauthorized']);
            exit;
        }

        $result = $this->mentorshipModel->updateStatus($mentorship_id, 'cancelled');
        
        echo json_encode(['success' => $result]);
    }
}
