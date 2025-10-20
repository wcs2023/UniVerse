<?php

class Mentorships extends Controller
{
    private $mentorshipModel;
    
    public function __construct()
    {
        // Start session if not already started
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        $this->mentorshipModel = $this->model('Mentorship');
    }
    
    public function index()
    {
        // Get user information from session
        $user_role = $_SESSION['user_role'] ?? '';
        $user_id = $_SESSION['user_id'] ?? 0;
        
        if ($user_role === 'undergraduate') {
            // Get undergraduate_id from session
            $undergraduate_id = $_SESSION['undergraduate_id'] ?? 0;
            
           if (!$undergraduate_id) {
                header("Location: " . BASE_URL . "/login");
                return;
            }
            
            // Get mentorship requests for this undergraduate
            $mentorships = $this->mentorshipModel->getRequestsByUndergraduate($undergraduate_id);
            
            // Load view with data
            $this->view('mentorship/Umentorship', [
                'mentorships' => $mentorships
            ]);
        } else if ($user_role === 'alumni') {
            // Redirect to mentor dashboard
            $this->mentorDashboard();
        } else {
            // Redirect to login
            header("Location: " . BASE_URL . "/login");
        }
    }
    
    /**
     * Mentor Dashboard - Shows pending requests, upcoming sessions, and impact stats
     */
    public function mentorDashboard()
    {
        // For now, use dummy data since authentication is not complete
        // Once auth is complete, uncomment the proper auth checks
        
        // TODO: Uncomment when authentication is ready
        /*
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'alumni') {
            header('Location: ' . URLROOT . '/users/login');
            exit;
        }
        
        $mentorId = $_SESSION['mentor_id'] ?? null;
        
        if (!$mentorId) {
            // Get mentor_id from user_id
            $mentorProfile = $this->mentorshipModel->getMentorByUserId($_SESSION['user_id']);
            $mentorId = $mentorProfile['mentor_id'] ?? null;
        }
        
        if (!$mentorId) {
            die('Mentor profile not found');
        }
        */
        
        // TEMPORARY: Use first mentor ID from database for testing
        $mentorId = 1;
        
        // Get pending requests
        $pendingRequests = $this->mentorshipModel->getPendingRequestsForMentor($mentorId);
        
        // Get upcoming sessions
        $upcomingSessions = $this->mentorshipModel->getUpcomingSessionsForMentor($mentorId);
        
        // Get impact stats
        $stats = $this->mentorshipModel->getMentorStats($mentorId);

        $data = [
            'pending_requests' => $pendingRequests,
            'upcoming_sessions' => $upcomingSessions,
            'stats' => $stats
        ];

        $this->view('mentorship/Amentorship', $data);
    }
    
    /**
     * Accept a mentorship request and propose time slots
     */
    public function proposeTimeSlots()
    {
        header('Content-Type: application/json');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Invalid request method']);
            exit;
        }

        $input = json_decode(file_get_contents('php://input'), true);
        $requestId = $input['request_id'] ?? null;
        $timeSlots = $input['time_slots'] ?? [];

        if (!$requestId || empty($timeSlots)) {
            echo json_encode(['success' => false, 'message' => 'Missing required data']);
            exit;
        }

        // Accept the request
        $accepted = $this->mentorshipModel->acceptRequest($requestId);
        
        if ($accepted) {
            // Add proposed time slots
            foreach ($timeSlots as $slot) {
                $this->mentorshipModel->addProposedSlot($requestId, $slot);
            }
            
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error accepting request']);
        }
    }
    
    /**
     * Decline a mentorship request
     */
    public function declineRequest()
    {
        header('Content-Type: application/json');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Invalid request method']);
            exit;
        }

        $input = json_decode(file_get_contents('php://input'), true);
        $requestId = $input['request_id'] ?? null;

        if (!$requestId) {
            echo json_encode(['success' => false, 'message' => 'Missing request ID']);
            exit;
        }

        $declined = $this->mentorshipModel->declineRequest($requestId);
        
        echo json_encode(['success' => $declined]);
    }
    
    public function request($alumni_id = null)
    {
        // Check if user is logged in and is an undergraduate
        $user_role = $_SESSION['user_role'] ?? '';
        $user_id = $_SESSION['user_id'] ?? 0;
        $undergraduate_id = $_SESSION['undergraduate_id'] ?? 0;
        
        if ($user_role !== 'undergraduate' || !$undergraduate_id) {
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
                    header("Location: " . BASE_URL . "/alumniDirectory");
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
    
    public function respond()
    {
        // Check if user is logged in and is an alumni
        $user_role = $_SESSION['user_role'] ?? '';
        $alumni_id = $_SESSION['alumni_id'] ?? 0;
        
        if ($user_role !== 'alumni' || !$alumni_id) {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                echo json_encode(['success' => false, 'message' => 'Unauthorized access']);
                return;
            } else {
                header("Location: " . BASE_URL . "/login");
                return;
            }
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Get mentorship ID from POST data
            if (!isset($_POST['mentorship_id'])) {
                echo json_encode(['success' => false, 'message' => 'Missing mentorship ID']);
                return;
            }
            
            $mentorship_id = $_POST['mentorship_id'];
            
            // Check if slots are provided
            if (isset($_POST['slots']) && is_array($_POST['slots'])) {
                $slots = [];
                
                foreach ($_POST['slots'] as $slot) {
                    if (isset($slot['start']) && isset($slot['end'])) {
                        $slots[] = [
                            'start' => $slot['start'],
                            'end' => $slot['end']
                        ];
                    }
                }
                
                if (count($slots) > 0) {
                    // Add time slots
                    $result = $this->mentorshipModel->addTimeSlots($mentorship_id, $slots);
                    
                    if ($result) {
                        // Update status to awaiting_student_confirmation
                        $this->mentorshipModel->updateStatus($mentorship_id, 'awaiting_student_confirmation');
                        
                        echo json_encode(['success' => true]);
                    } else {
                        echo json_encode(['success' => false, 'message' => 'Failed to add time slots']);
                    }
                } else {
                    echo json_encode(['success' => false, 'message' => 'No valid time slots provided']);
                }
            } else if (isset($_POST['action']) && $_POST['action'] === 'reject') {
                // Update status to rejected
                $result = $this->mentorshipModel->updateStatus($mentorship_id, 'rejected');
                
                if ($result) {
                    echo json_encode(['success' => true]);
                } else {
                    echo json_encode(['success' => false, 'message' => 'Failed to reject request']);
                }
            } else {
                echo json_encode(['success' => false, 'message' => 'Invalid request']);
            }
        } else {
            // Not needed as we're handling this with AJAX
            header("Location: " . BASE_URL . "/mentorships");
        }
    }
    
    public function getTimeSlots($mentorship_id = null)
    {
        // Check if mentorship_id is provided
        if ($mentorship_id === null) {
            echo json_encode(['success' => false, 'message' => 'Missing mentorship ID']);
            return;
        }
        
        // Check if user is logged in and is an undergraduate
        $user_role = $_SESSION['user_role'] ?? '';
        $undergraduate_id = $_SESSION['undergraduate_id'] ?? 0;
        
        if ($user_role !== 'undergraduate' || !$undergraduate_id) {
            echo json_encode(['success' => false, 'message' => 'Unauthorized access']);
            return;
        }
        
        // Get time slots for this mentorship
        $slots = $this->mentorshipModel->getTimeSlots($mentorship_id);
        
        echo json_encode(['success' => true, 'slots' => $slots]);
    }
    
    public function schedule()
    {
        // Check if user is logged in and is an undergraduate
        $user_role = $_SESSION['user_role'] ?? '';
        $undergraduate_id = $_SESSION['undergraduate_id'] ?? 0;
        
        if ($user_role !== 'undergraduate' || !$undergraduate_id) {
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
    
    public function feedback($mentorship_id = null)
    {
        // Check if mentorship_id is provided
        if ($mentorship_id === null) {
            header("Location: " . BASE_URL . "/mentorships");
            return;
        }
        
        // This form can be accessed by both undergraduate and alumni
        // This is placeholder code - implement proper user authentication
        $user_role = 'undergraduate'; // Example: get from session
        
        if ($user_role !== 'undergraduate' && $user_role !== 'alumni') {
            header("Location: " . BASE_URL);
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
                header("Location: " . BASE_URL . "/mentorships?success=feedback");
            } else {
                // Redirect with error message
                header("Location: " . BASE_URL . "/mentorships/feedback/$mentorship_id?error=empty");
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
}
