<?php

class Amentorships extends Controller
{
    private $mentorshipModel;
    
    public function __construct()
    {
        // Start session if not already started
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        // Check if user is logged in and is an alumni
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'alumni') {
            header('Location: ' . BASE_URL . '/login');
            exit;
        }
        
        $this->mentorshipModel = $this->model('Mentorship');
    }
    
    /**
     * Default method - Show mentor dashboard
     */
    public function index()
    {
        $this->dashboard();
    }
    
    /**
     * Mentor Dashboard - Shows pending requests, upcoming sessions, and impact stats
     */
    public function dashboard()
    {
        // Use user_id directly as mentor_id
        $mentorId = $_SESSION['user_id'];
        
        // Store in session for consistency with other parts of the system
        $_SESSION['mentor_id'] = $mentorId;
        
        // Get pending requests - handle case where no data exists yet
        $pendingRequests = $this->mentorshipModel->getPendingRequestsForMentor($mentorId) ?? [];
        
        // Get upcoming sessions
        $upcomingSessions = $this->mentorshipModel->getUpcomingSessionsForMentor($mentorId) ?? [];
        
        // Get impact stats
        $stats = $this->mentorshipModel->getMentorStats($mentorId) ?? [
            'total_sessions' => 0,
            'completed_sessions' => 0,
            'active_mentees' => 0
        ];

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
    
    /**
     * Respond to a mentorship request (legacy method, kept for backward compatibility)
     */
    public function respond()
    {
        // Check if user is logged in and is an alumni
        $alumni_id = $_SESSION['alumni_id'] ?? $_SESSION['mentor_id'] ?? 0;
        
        if (!$alumni_id) {
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
            // Redirect to dashboard
            header("Location: " . BASE_URL . "/amentorships");
        }
    }
    
    /**
     * View mentorship details
     */
    public function viewDetails($mentorship_id = null)
    {
        if (!$mentorship_id) {
            header("Location: " . BASE_URL . "/amentorships");
            exit;
        }
        
        // Get mentorship details
        $mentorship = $this->mentorshipModel->getMentorshipById($mentorship_id);
        
        if (!$mentorship) {
            header("Location: " . BASE_URL . "/amentorships");
            exit;
        }
        
        // Verify this mentorship belongs to the current alumni
        $mentorId = $_SESSION['mentor_id'] ?? null;
        if ($mentorship['alumni_id'] != $mentorId) {
            header("Location: " . BASE_URL . "/amentorships");
            exit;
        }
        
        $this->view('mentorship/mentorship_details', [
            'mentorship' => $mentorship
        ]);
    }
    
    /**
     * Complete a mentorship session
     */
    public function complete($mentorship_id = null)
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

        $result = $this->mentorshipModel->updateStatus($mentorship_id, 'completed');
        
        echo json_encode(['success' => $result]);
    }
}
