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
        $user_role = $_SESSION['user_type'] ?? $_SESSION['user_role'] ?? '';
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
        // In the new schema, we use user_id directly as student_id
        $user_id = $_SESSION['user_id'] ?? 0;
        $student_id = $user_id; // Use user_id as student_id

        // Initialize empty mentorships array
        $mentorships = [
            'pending' => [],
            'awaiting_selection' => [],
            'upcoming' => [],
            'completed' => []
        ];

        // Get mentorship requests if user_id exists
        if ($student_id) {
            $mentorships = $this->mentorshipModel->getRequestsByStudent($student_id);
            
            // Get requests awaiting time slot selection
            $mentorships['awaiting_selection'] = $this->mentorshipModel->getRequestsAwaitingSelection($student_id);
            
            // Get finalized upcoming sessions
            $mentorships['finalized_sessions'] = $this->mentorshipModel->getFinalizedSessionsForStudent($student_id);
            
            // Get unread notifications count
            $unreadCount = $this->mentorshipModel->countUnreadNotifications($student_id);
        } else {
            error_log("Umentorships: No user_id found, showing empty mentorship page");
            $unreadCount = 0;
        }

        // Load view with data
        $this->view('mentorship/Umentorship', [
            'mentorships' => $mentorships,
            'undergraduate_id' => $student_id, // Keep for backward compatibility
            'student_id' => $student_id,
            'unread_notifications' => $unreadCount
        ]);
    }

    /**
     * Request mentorship from an alumni/mentor
     */
    public function request($mentor_id = null)
    {
        $user_id = $_SESSION['user_id'] ?? 0;
        $student_id = $user_id;

        if (!$student_id) {
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
            $mentor_id = $_POST['mentor_id'] ?? $_POST['alumni_id'] ?? null;
            if (!$mentor_id) {
                echo json_encode(['success' => false, 'message' => 'Missing mentor ID']);
                return;
            }

            $message = $_POST['message'] ?? '';

            // Create mentorship request
            $request_id = $this->mentorshipModel->createRequest($student_id, $mentor_id, $message);

            if ($request_id) {
                echo json_encode(['success' => true, 'request_id' => $request_id]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Failed to create request']);
            }
        } else {
            // If mentor_id is provided, show the profile
            if ($mentor_id !== null) {
                // Get mentor information
                $mentor = $this->mentorshipModel->getMentorByUserId($mentor_id);

                if (!$mentor) {
                    header("Location: " . BASE_URL . "/umentorships/exploreMentors");
                    return;
                }

                $this->view('mentorship/mentor_profile', [
                    'mentor' => $mentor,
                    'alumni' => $mentor // backward compatibility
                ]);
            } else {
                // Show request form with list of mentors
                $mentors = $this->mentorshipModel->getAllMentors();

                $this->view('mentorship/request', [
                    'mentors' => $mentors,
                    'alumni_list' => $mentors // backward compatibility
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

        $user_id = $_SESSION['user_id'] ?? 0;

        if (!$user_id) {
            echo json_encode(['success' => false, 'message' => 'Unauthorized access']);
            return;
        }

        // Get time slots for this mentorship
        $slots = $this->mentorshipModel->getProposedTimeSlots($mentorship_id);

        echo json_encode(['success' => true, 'slots' => $slots]);
    }

    /**
     * Schedule a mentorship session by selecting a time slot
     */
    public function schedule()
    {
        header('Content-Type: application/json');

        $user_id = $_SESSION['user_id'] ?? 0;

        if (!$user_id) {
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

            // Select the time slot and finalize the session
            $result = $this->mentorshipModel->selectTimeSlotAndFinalize($mentorship_id, $slot_id, $user_id);

            echo json_encode($result);
        } else {
            echo json_encode(['success' => false, 'message' => 'Invalid request method']);
        }
    }

    /**
     * Confirm a time slot selection (alternative endpoint)
     */
    public function confirmTimeSlot()
    {
        header('Content-Type: application/json');

        $user_id = $_SESSION['user_id'] ?? 0;

        if (!$user_id) {
            echo json_encode(['success' => false, 'message' => 'Unauthorized access']);
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Invalid request method']);
            return;
        }

        // Accept both form data and JSON
        $input = json_decode(file_get_contents('php://input'), true);
        if (!$input) {
            $input = $_POST;
        }

        $requestId = $input['request_id'] ?? $input['mentorship_id'] ?? null;
        $slotId = $input['slot_id'] ?? null;

        if (!$requestId || !$slotId) {
            echo json_encode(['success' => false, 'message' => 'Missing required parameters']);
            return;
        }

        // Select the time slot and finalize the session
        $result = $this->mentorshipModel->selectTimeSlotAndFinalize($requestId, $slotId, $user_id);

        echo json_encode($result);
    }

    /**
     * Get notifications for the current user
     */
    public function getNotifications()
    {
        header('Content-Type: application/json');

        $user_id = $_SESSION['user_id'] ?? 0;

        if (!$user_id) {
            echo json_encode(['success' => false, 'message' => 'Unauthorized access']);
            return;
        }

        $notifications = $this->mentorshipModel->getUnreadNotifications($user_id);
        $unreadCount = $this->mentorshipModel->countUnreadNotifications($user_id);

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
