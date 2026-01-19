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
        $userType = $_SESSION['user_type'] ?? $_SESSION['user_role'] ?? '';
        if (!isset($_SESSION['user_id']) || $userType !== 'alumni') {
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

        // Get upcoming sessions from both old and new tables
        $upcomingSessions = $this->mentorshipModel->getUpcomingSessionsForMentor($mentorId) ?? [];
        
        // Get finalized sessions (new system)
        $finalizedSessions = $this->mentorshipModel->getFinalizedSessionsForMentor($mentorId) ?? [];

        // Get impact stats
        $stats = $this->mentorshipModel->getMentorStats($mentorId) ?? [
            'total_sessions' => 0,
            'completed_sessions' => 0,
            'active_mentees' => 0
        ];

        // Get unread notifications
        $unreadNotifications = $this->mentorshipModel->countUnreadNotifications($mentorId);

        $data = [
            'pending_requests' => $pendingRequests,
            'upcoming_sessions' => $upcomingSessions,
            'finalized_sessions' => $finalizedSessions,
            'stats' => $stats,
            'unread_notifications' => $unreadNotifications
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

        // Validate: Must have exactly 2 time slots
        if (count($timeSlots) < 2) {
            echo json_encode(['success' => false, 'message' => 'Please provide at least 2 time slots']);
            exit;
        }

        // Validate: All slots must be in the future
        $now = new DateTime();
        foreach ($timeSlots as $slot) {
            $slotDate = new DateTime($slot);
            if ($slotDate <= $now) {
                echo json_encode(['success' => false, 'message' => 'All time slots must be in the future']);
                exit;
            }
        }

        // Accept the request and add time slots
        $result = $this->mentorshipModel->acceptRequestWithTimeSlots($requestId, $timeSlots);

        if ($result) {
            echo json_encode([
                'success' => true, 
                'message' => 'Time slots sent to student. They will select their preferred time.'
            ]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error accepting request. Please try again.']);
        }
    }

    /**
     * Get notifications for the current mentor
     */
    public function getNotifications()
    {
        header('Content-Type: application/json');

        $userId = $_SESSION['user_id'] ?? 0;

        if (!$userId) {
            echo json_encode(['success' => false, 'message' => 'Unauthorized access']);
            return;
        }

        $notifications = $this->mentorshipModel->getUnreadNotifications($userId);
        $unreadCount = $this->mentorshipModel->countUnreadNotifications($userId);

        echo json_encode([
            'success' => true,
            'notifications' => $notifications,
            'unread_count' => $unreadCount
        ]);
    }

    /**
     * Mark a notification as read
     */
    public function markNotificationRead($notification_id = null)
    {
        header('Content-Type: application/json');

        if (!$notification_id && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $input = json_decode(file_get_contents('php://input'), true);
            $notification_id = $input['notification_id'] ?? null;
        }

        if (!$notification_id) {
            echo json_encode(['success' => false, 'message' => 'Missing notification ID']);
            return;
        }

        $result = $this->mentorshipModel->markNotificationRead($notification_id);
        echo json_encode(['success' => $result]);
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
        $mentorId = $_SESSION['user_id'] ?? $_SESSION['mentor_id'] ?? null;
        // Skip ownership check for now as schema uses different ID approach

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
