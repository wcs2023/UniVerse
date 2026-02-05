<?php

/**
 * Amentorships Controller - Alumni Mentorship Management
 * 
 * NEW SIMPLIFIED WORKFLOW:
 * - Alumni set 2-week rolling availability slots
 * - Students can instantly book available slots
 * - No approval required - slots are first-come-first-served
 * - Cancel & rebook with required reason
 */
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
     * Mentor Dashboard - Shows availability, upcoming bookings, and impact stats
     */
    public function dashboard()
    {
        $mentorUserId = $_SESSION['user_id'];
        $_SESSION['mentor_id'] = $mentorUserId;

        // Check if user is registered as mentor and is active
        $mentorStatus = $this->mentorshipModel->getMentorStatus($mentorUserId);
        
        if (!$mentorStatus || !$mentorStatus['is_active']) {
            // Redirect to edit profile with message
            $_SESSION['mentorship_warning'] = 'Please enable mentorship availability in your profile settings to access this section.';
            header('Location: ' . BASE_URL . '/aeditprofile');
            exit;
        }

        // Get mentor's availability slots (next 2 weeks)
        $availabilitySlots = $this->mentorshipModel->getMentorAvailability($mentorUserId) ?? [];

        // Get upcoming scheduled bookings
        $upcomingBookings = $this->mentorshipModel->getMentorBookings($mentorUserId, 'scheduled') ?? [];

        // Get past/completed sessions
        $completedSessions = $this->mentorshipModel->getMentorBookings($mentorUserId, 'completed') ?? [];

        // Get impact stats
        $stats = $this->mentorshipModel->getMentorStats($mentorUserId) ?? [
            'total_sessions' => 0,
            'completed_sessions' => 0,
            'active_mentees' => 0,
            'average_rating' => 0
        ];

        // Get unread notifications
        $unreadNotifications = $this->mentorshipModel->countUnreadNotifications($mentorUserId);

        $data = [
            'availability_slots' => $availabilitySlots,
            'upcoming_bookings' => $upcomingBookings,
            'completed_sessions' => $completedSessions,
            'stats' => $stats,
            'unread_notifications' => $unreadNotifications
        ];

        $this->view('mentorship/Amentorship', $data);
    }

    /**
     * Add availability slots (AJAX endpoint)
     * Alumni can add multiple 1-hour slots for the next 2 weeks
     */
    public function addAvailability()
    {
        header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Invalid request method']);
            exit;
        }

        $mentorUserId = $_SESSION['user_id'];

        // Accept both form data and JSON
        $input = json_decode(file_get_contents('php://input'), true);
        if (!$input) {
            $input = $_POST;
        }

        $slots = $input['slots'] ?? [];

        if (empty($slots)) {
            echo json_encode(['success' => false, 'message' => 'No time slots provided']);
            exit;
        }

        // Validate slots are within 2 weeks and in the future
        $now = new DateTime();
        $maxDate = new DateTime('+14 days');
        $validSlots = [];

        foreach ($slots as $slot) {
            $slotDate = new DateTime($slot);
            
            if ($slotDate <= $now) {
                continue; // Skip past slots
            }
            
            if ($slotDate > $maxDate) {
                continue; // Skip slots beyond 2 weeks
            }

            $validSlots[] = $slot;
        }

        if (empty($validSlots)) {
            echo json_encode(['success' => false, 'message' => 'All slots must be within the next 2 weeks and in the future']);
            exit;
        }

        // Add slots using the model
        $result = $this->mentorshipModel->addAvailabilitySlots($mentorUserId, $validSlots);

        if ($result['success']) {
            echo json_encode([
                'success' => true,
                'message' => 'Availability slots added successfully',
                'added_count' => $result['added_count'] ?? count($validSlots)
            ]);
        } else {
            echo json_encode(['success' => false, 'message' => $result['message'] ?? 'Error adding slots']);
        }
    }

    /**
     * Remove an availability slot (AJAX endpoint)
     * Only allowed if slot is not booked
     */
    public function removeAvailability()
    {
        header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Invalid request method']);
            exit;
        }

        $mentorUserId = $_SESSION['user_id'];

        $input = json_decode(file_get_contents('php://input'), true);
        $slotId = $input['slot_id'] ?? null;

        if (!$slotId) {
            echo json_encode(['success' => false, 'message' => 'Missing slot ID']);
            exit;
        }

        $result = $this->mentorshipModel->removeAvailabilitySlot($mentorUserId, $slotId);

        echo json_encode($result);
    }

    /**
     * Get mentor's current availability (AJAX endpoint)
     */
    public function getAvailability()
    {
        header('Content-Type: application/json');

        $mentorUserId = $_SESSION['user_id'];

        $slots = $this->mentorshipModel->getMentorAvailability($mentorUserId);

        echo json_encode([
            'success' => true,
            'slots' => $slots ?? []
        ]);
    }

    /**
     * Get upcoming bookings (AJAX endpoint)
     */
    public function getBookings()
    {
        header('Content-Type: application/json');

        $mentorUserId = $_SESSION['user_id'];
        $status = $_GET['status'] ?? 'confirmed';

        $bookings = $this->mentorshipModel->getMentorBookings($mentorUserId, $status);

        echo json_encode([
            'success' => true,
            'bookings' => $bookings ?? []
        ]);
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
            exit;
        }

        $mentorUserId = $_SESSION['user_id'];

        $input = json_decode(file_get_contents('php://input'), true);
        if (!$input) {
            $input = $_POST;
        }

        $bookingId = $input['booking_id'] ?? null;
        $reason = trim($input['reason'] ?? '');

        if (!$bookingId) {
            echo json_encode(['success' => false, 'message' => 'Missing booking ID']);
            exit;
        }

        if (empty($reason)) {
            echo json_encode(['success' => false, 'message' => 'Cancellation reason is required']);
            exit;
        }

        $result = $this->mentorshipModel->cancelBooking($bookingId, $mentorUserId, $reason);

        echo json_encode($result);
    }

    /**
     * Mark a session as completed (AJAX endpoint)
     */
    public function markCompleted()
    {
        header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Invalid request method']);
            exit;
        }

        $mentorUserId = $_SESSION['user_id'];

        $input = json_decode(file_get_contents('php://input'), true);
        if (!$input) {
            $input = $_POST;
        }

        $bookingId = $input['booking_id'] ?? null;

        if (!$bookingId) {
            echo json_encode(['success' => false, 'message' => 'Missing booking ID']);
            exit;
        }

        $result = $this->mentorshipModel->markBookingCompleted($bookingId, $mentorUserId);

        echo json_encode($result);
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
     * Get booking details (AJAX endpoint)
     */
    public function getBookingDetails($bookingId = null)
    {
        header('Content-Type: application/json');

        $mentorUserId = $_SESSION['user_id'];

        if (!$bookingId) {
            echo json_encode(['success' => false, 'message' => 'Missing booking ID']);
            return;
        }

        $booking = $this->mentorshipModel->getBookingById($bookingId);

        if (!$booking) {
            echo json_encode(['success' => false, 'message' => 'Booking not found']);
            return;
        }

        // Verify this booking belongs to this mentor
        if ($booking['mentor_user_id'] != $mentorUserId) {
            echo json_encode(['success' => false, 'message' => 'Unauthorized']);
            return;
        }

        // Add join status
        $joinStatus = $this->mentorshipModel->canJoinSession($booking['slot_datetime']);
        $booking['join_status'] = $joinStatus;

        echo json_encode(['success' => true, 'booking' => $booking]);
    }

    /**
     * Bulk add availability for a week (convenience method)
     */
    public function addWeeklyAvailability()
    {
        header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Invalid request method']);
            exit;
        }

        $mentorUserId = $_SESSION['user_id'];

        $input = json_decode(file_get_contents('php://input'), true);
        
        // Expected format: array of day/time combinations
        // e.g., [{ day: 'monday', times: ['09:00', '10:00', '14:00'] }]
        $weeklySchedule = $input['weekly_schedule'] ?? [];

        if (empty($weeklySchedule)) {
            echo json_encode(['success' => false, 'message' => 'No schedule provided']);
            exit;
        }

        $slots = [];
        $now = new DateTime();
        
        // Generate slots for next 2 weeks based on the weekly pattern
        for ($week = 0; $week < 2; $week++) {
            foreach ($weeklySchedule as $daySchedule) {
                $dayName = strtolower($daySchedule['day']);
                $times = $daySchedule['times'] ?? [];

                foreach ($times as $time) {
                    // Find next occurrence of this day
                    $slotDate = clone $now;
                    $slotDate->modify("next {$dayName}");
                    if ($week > 0) {
                        $slotDate->modify("+{$week} week");
                    }
                    
                    // Set the time
                    list($hour, $minute) = explode(':', $time);
                    $slotDate->setTime((int)$hour, (int)$minute);

                    // Only add if in the future
                    if ($slotDate > $now) {
                        $slots[] = $slotDate->format('Y-m-d H:i:s');
                    }
                }
            }
        }

        if (empty($slots)) {
            echo json_encode(['success' => false, 'message' => 'No valid slots generated']);
            exit;
        }

        $result = $this->mentorshipModel->addAvailabilitySlots($mentorUserId, $slots);

        echo json_encode($result);
    }

    /**
     * Check if user can join a session
     */
    public function checkJoinStatus($bookingId = null)
    {
        header('Content-Type: application/json');

        $mentorUserId = $_SESSION['user_id'];

        if (!$bookingId) {
            echo json_encode(['success' => false, 'message' => 'Missing booking ID']);
            return;
        }

        $booking = $this->mentorshipModel->getBookingById($bookingId);

        if (!$booking || $booking['mentor_user_id'] != $mentorUserId) {
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
}
