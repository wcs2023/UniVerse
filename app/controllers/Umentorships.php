<?php

/**
 * Umentorships Controller - Student Mentorship Management
 * 
 * NEW SIMPLIFIED WORKFLOW:
 * - Students browse available mentor slots
 * - Instant booking - no approval required
 * - First-come-first-served with double-booking prevention
 * - Cancel & rebook with required reason
 */
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
     * Default method - Show my mentorships dashboard
     */
    public function index()
    {
        $studentId = $_SESSION['user_id'];

        // Get student's upcoming scheduled bookings
        $upcomingBookings = $this->mentorshipModel->getStudentBookings($studentId, 'scheduled') ?? [];

        // Get completed sessions (for feedback)
        $completedSessions = $this->mentorshipModel->getStudentBookings($studentId, 'completed') ?? [];

        // Get sessions needing feedback (completed but no feedback given)
        $needsFeedback = array_filter($completedSessions, function($session) {
            return empty($session['rating']);
        });

        // Get cancelled bookings (recent)
        $cancelledBookings = $this->mentorshipModel->getStudentBookings($studentId, 'cancelled') ?? [];

        // Get unread notifications count
        $unreadCount = $this->mentorshipModel->countUnreadNotifications($studentId);

        // Load view with data
        $this->view('mentorship/Umentorship', [
            'upcoming_bookings' => $upcomingBookings,
            'completed_sessions' => $completedSessions,
            'needs_feedback' => $needsFeedback,
            'cancelled_bookings' => $cancelledBookings,
            'student_id' => $studentId,
            'unread_notifications' => $unreadCount
        ]);
    }

    /**
     * Browse available mentor slots
     */
    public function exploreMentors()
    {
        // Verify user is an undergraduate (additional check)
        $userRole = $_SESSION['user_type'] ?? $_SESSION['user_role'] ?? '';
        if ($userRole !== 'undergraduate' && $userRole !== 'student') {
            header('Location: ' . BASE_URL . '/login');
            exit;
        }

        // Get search/filter parameters
        $searchTerm = $_GET['search'] ?? '';
        $industry = $_GET['industry'] ?? '';
        $expertise = $_GET['expertise'] ?? '';

        // Get all available mentors with their available slots
        $mentors = $this->mentorshipModel->getAvailableMentorsWithSlots($searchTerm, $industry, $expertise);

        // Load the explore mentors view
        $this->view('mentorship/explore_mentors', [
            'mentors' => $mentors ?? [],
            'searchTerm' => $searchTerm,
            'industry' => $industry,
            'expertise' => $expertise
        ]);
    }

    /**
     * View a specific mentor's profile and available slots
     */
    public function viewMentor($mentorUserId = null)
    {
        if (!$mentorUserId) {
            header("Location: " . BASE_URL . "/umentorships/exploreMentors");
            return;
        }

        // Get mentor information
        $mentor = $this->mentorshipModel->getMentorByUserId($mentorUserId);

        if (!$mentor) {
            header("Location: " . BASE_URL . "/umentorships/exploreMentors");
            return;
        }

        // Get mentor's available slots
        $availableSlots = $this->mentorshipModel->getAvailableSlots($mentorUserId);

        // Get mentor stats
        $stats = $this->mentorshipModel->getMentorStats($mentorUserId);

        // Check if student already has active request/booking with this mentor
        $studentId = $_SESSION['user_id'];
        $hasActiveRequest = $this->mentorshipModel->hasActiveRequest($studentId, $mentor['mentor_id']);

        // Get user type for navigation
        $userType = $_SESSION['user_type'] ?? $_SESSION['user_role'] ?? 'undergraduate';

        $this->view('mentorship/mentor_profile', [
            'mentor' => $mentor,
            'available_slots' => $availableSlots ?? [],
            'stats' => $stats ?? ['completed_sessions' => 0, 'active_mentees' => 0],
            'hasActiveRequest' => $hasActiveRequest,
            'user_type' => $userType
        ]);
    }

    /**
     * Get available slots for a mentor (AJAX endpoint)
     */
    public function getAvailableSlots($mentorUserId = null)
    {
        header('Content-Type: application/json');

        // Accept mentor_id from query parameters or URL parameter
        if (!$mentorUserId && isset($_GET['mentor_id'])) {
            $mentorUserId = $_GET['mentor_id'];
        }

        if (!$mentorUserId) {
            echo json_encode(['success' => false, 'message' => 'Missing mentor ID']);
            return;
        }

        // Get mentor info for the modal
        $mentor = $this->mentorshipModel->getMentorByUserId($mentorUserId);
        
        // Get available slots
        $slots = $this->mentorshipModel->getAvailableSlots($mentorUserId);

        echo json_encode([
            'success' => true,
            'mentor' => $mentor,
            'slots' => $slots ?? []
        ]);
    }

    /**
     * Book a slot instantly (AJAX endpoint)
     * This is the main booking action - no approval required
     */
    public function bookSlot()
    {
        header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Invalid request method']);
            return;
        }

        $studentId = $_SESSION['user_id'];

        // Accept both form data and JSON
        $input = json_decode(file_get_contents('php://input'), true);
        if (!$input) {
            $input = $_POST;
        }

        $slotId = $input['slot_id'] ?? null;

        if (!$slotId) {
            echo json_encode(['success' => false, 'message' => 'Missing slot ID']);
            return;
        }

        // Book the slot using the model (handles double-booking prevention)
        $result = $this->mentorshipModel->bookSlot($slotId, $studentId);

        echo json_encode($result);
    }

    /**
     * Cancel a booking (AJAX endpoint)
     * Requires a reason for cancellation
     */
    public function cancelBooking()
    {
        header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Invalid request method']);
            return;
        }

        $studentId = $_SESSION['user_id'];

        $input = json_decode(file_get_contents('php://input'), true);
        if (!$input) {
            $input = $_POST;
        }

        $bookingId = $input['booking_id'] ?? null;
        $reason = trim($input['reason'] ?? '');

        if (!$bookingId) {
            echo json_encode(['success' => false, 'message' => 'Missing booking ID']);
            return;
        }

        if (empty($reason)) {
            echo json_encode(['success' => false, 'message' => 'Cancellation reason is required']);
            return;
        }

        $result = $this->mentorshipModel->cancelBooking($bookingId, $studentId, $reason);

        echo json_encode($result);
    }

    /**
     * Submit feedback for a completed session (AJAX endpoint)
     */
    public function submitFeedback()
    {
        header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Invalid request method']);
            return;
        }

        $studentId = $_SESSION['user_id'];

        $input = json_decode(file_get_contents('php://input'), true);
        if (!$input) {
            $input = $_POST;
        }

        $bookingId = $input['booking_id'] ?? null;
        $rating = $input['rating'] ?? null;
        $reviewText = $input['review_text'] ?? $input['feedback'] ?? '';

        if (!$bookingId || !$rating) {
            echo json_encode(['success' => false, 'message' => 'Missing required fields (booking_id and rating)']);
            return;
        }

        // Validate rating
        $rating = (int) $rating;
        if ($rating < 1 || $rating > 5) {
            echo json_encode(['success' => false, 'message' => 'Rating must be between 1 and 5']);
            return;
        }

        // Submit feedback
        $result = $this->mentorshipModel->submitFeedback($bookingId, $studentId, $rating, $reviewText);

        echo json_encode($result);
    }

    /**
     * Get booking details (AJAX endpoint)
     */
    public function getBookingDetails($bookingId = null)
    {
        header('Content-Type: application/json');

        $studentId = $_SESSION['user_id'];

        if (!$bookingId) {
            echo json_encode(['success' => false, 'message' => 'Missing booking ID']);
            return;
        }

        $booking = $this->mentorshipModel->getBookingById($bookingId);

        if (!$booking) {
            echo json_encode(['success' => false, 'message' => 'Booking not found']);
            return;
        }

        // Verify this booking belongs to this student
        if ($booking['student_user_id'] != $studentId) {
            echo json_encode(['success' => false, 'message' => 'Unauthorized']);
            return;
        }

        // Add join status
        $joinStatus = $this->mentorshipModel->canJoinSession($booking['slot_datetime']);
        $booking['join_status'] = $joinStatus;

        echo json_encode(['success' => true, 'booking' => $booking]);
    }

    /**
     * Check session join status (AJAX endpoint)
     */
    public function checkJoinStatus($bookingId = null)
    {
        header('Content-Type: application/json');

        $studentId = $_SESSION['user_id'];

        if (!$bookingId) {
            echo json_encode(['success' => false, 'message' => 'Missing booking ID']);
            return;
        }

        $booking = $this->mentorshipModel->getBookingById($bookingId);

        if (!$booking || $booking['student_user_id'] != $studentId) {
            echo json_encode(['success' => false, 'message' => 'Unauthorized']);
            return;
        }

        $joinStatus = $this->mentorshipModel->canJoinSession($booking['slot_datetime']);

        echo json_encode([
            'success' => true,
            'booking_id' => $bookingId,
            'slot_datetime' => $booking['slot_datetime'],
            'meeting_link' => $booking['meeting_link'],
            'join_status' => $joinStatus
        ]);
    }

    /**
     * Get notifications (AJAX endpoint)
     */
    public function getNotifications()
    {
        header('Content-Type: application/json');

        $userId = $_SESSION['user_id'];

        $notifications = $this->mentorshipModel->getUnreadNotifications($userId);
        $unreadCount = $this->mentorshipModel->countUnreadNotifications($userId);

        echo json_encode([
            'success' => true,
            'notifications' => $notifications ?? [],
            'unread_count' => $unreadCount
        ]);
    }

    /**
     * Mark notification as read (AJAX endpoint)
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
     * Get my bookings (AJAX endpoint)
     */
    public function getMyBookings()
    {
        header('Content-Type: application/json');

        $studentId = $_SESSION['user_id'];
        $status = $_GET['status'] ?? 'confirmed';

        $bookings = $this->mentorshipModel->getStudentBookings($studentId, $status);

        echo json_encode([
            'success' => true,
            'bookings' => $bookings ?? []
        ]);
    }
}
