<?php
/**
 * Mentorship Model - FACADE CLASS
 * ================================
 * This class delegates to specialized sub-model classes:
 * - MentorModel          → Mentor profiles, discovery, search, stats
 * - MentorAvailabilitySlot → Slot management (add, remove, get)
 * - MentorshipBooking    → Booking CRUD, cancel, session status
 * - MentorshipFeedback   → Feedback submission and ratings
 * - MentorshipNotification → Notification CRUD and counts
 * 
 * All sub-models extend MentorshipBase which provides shared
 * DB connection and helper methods.
 * 
 * Controllers continue to use: $this->model('Mentorship')
 * No controller changes needed - this facade preserves all
 * original method signatures.
 */

// Load base class and sub-models
require_once __DIR__ . '/MentorshipBase.php';
require_once __DIR__ . '/MentorModel.php';
require_once __DIR__ . '/MentorAvailabilitySlot.php';
require_once __DIR__ . '/MentorshipBooking.php';
require_once __DIR__ . '/MentorshipFeedback.php';
require_once __DIR__ . '/MentorshipNotification.php';

class Mentorship
{
    private $mentorModel;
    private $slotModel;
    private $bookingModel;
    private $feedbackModel;
    private $notificationModel;

    public function __construct()
    {
        $this->mentorModel       = new MentorModel();
        $this->slotModel         = new MentorAvailabilitySlot();
        $this->bookingModel      = new MentorshipBooking();
        $this->feedbackModel     = new MentorshipFeedback();
        $this->notificationModel = new MentorshipNotification();
    }

    // =====================================================
    // MENTOR PROFILE (delegates to MentorModel)
    // =====================================================

    public function ensureMentorExists($userId)
    {
        return $this->mentorModel->ensureMentorExists($userId);
    }

    public function getMentorStatus($userId)
    {
        return $this->mentorModel->getMentorStatus($userId);
    }

    public function getAllMentors($searchTerm = '', $expertise = '')
    {
        return $this->mentorModel->getAllMentors($searchTerm, $expertise);
    }

    public function getMentorById($mentorId)
    {
        return $this->mentorModel->getMentorById($mentorId);
    }

    public function getMentorByUserId($userId)
    {
        return $this->mentorModel->getMentorByUserId($userId);
    }

    public function getAvailableMentorsWithSlots($searchTerm = '', $industry = '', $expertise = '')
    {
        return $this->mentorModel->getAvailableMentorsWithSlots($searchTerm, $industry, $expertise);
    }

    public function getMentorStats($userId)
    {
        return $this->mentorModel->getMentorStats($userId);
    }

    public function getMentorRating($mentorId)
    {
        return $this->mentorModel->getMentorRating($mentorId);
    }

    public function hasActiveRequest($studentId, $mentorId)
    {
        return $this->mentorModel->hasActiveRequest($studentId, $mentorId);
    }

    // =====================================================
    // AVAILABILITY SLOTS (delegates to MentorAvailabilitySlot)
    // =====================================================

    public function addAvailabilitySlots($userId, $slots, $duration = 60)
    {
        return $this->slotModel->addAvailabilitySlots($userId, $slots, $duration);
    }

    public function removeAvailabilitySlot($userId, $slotId)
    {
        return $this->slotModel->removeAvailabilitySlot($userId, $slotId);
    }

    public function getMentorAvailability($userId, $futureOnly = true)
    {
        return $this->slotModel->getMentorAvailability($userId, $futureOnly);
    }

    public function removeExpiredSlots($userId = null)
    {
        return $this->slotModel->removeExpiredSlots($userId);
    }

    public function getAvailableSlots($mentorId, $weeksAhead = 2)
    {
        return $this->slotModel->getAvailableSlots($mentorId, $weeksAhead);
    }

    // =====================================================
    // BOOKINGS & SESSIONS (delegates to MentorshipBooking)
    // =====================================================

    public function bookSlot($slotId, $studentId)
    {
        return $this->bookingModel->bookSlot($slotId, $studentId);
    }

    public function cancelBooking($bookingId, $userId, $reason)
    {
        return $this->bookingModel->cancelBooking($bookingId, $userId, $reason);
    }

    public function getUpcomingBookingsForStudent($studentId)
    {
        return $this->bookingModel->getUpcomingBookingsForStudent($studentId);
    }

    public function getUpcomingBookingsForMentor($userId)
    {
        return $this->bookingModel->getUpcomingBookingsForMentor($userId);
    }

    public function getCompletedBookingsForStudent($studentId)
    {
        return $this->bookingModel->getCompletedBookingsForStudent($studentId);
    }

    public function getCompletedBookingsForMentor($userId)
    {
        return $this->bookingModel->getCompletedBookingsForMentor($userId);
    }

    public function getStudentBookings($studentId, $status = 'all')
    {
        return $this->bookingModel->getStudentBookings($studentId, $status);
    }

    public function getMentorBookings($userId, $status = 'all')
    {
        return $this->bookingModel->getMentorBookings($userId, $status);
    }

    public function getBookingById($bookingId)
    {
        return $this->bookingModel->getBookingById($bookingId);
    }

    public function canJoinSession($sessionDatetime)
    {
        return $this->bookingModel->canJoinSession($sessionDatetime);
    }

    public function markSessionCompleted($bookingId)
    {
        return $this->bookingModel->markSessionCompleted($bookingId);
    }

    public function markSessionNoShow($bookingId, $noShowBy = null)
    {
        return $this->bookingModel->markSessionNoShow($bookingId, $noShowBy);
    }

    public function markBookingCompleted($bookingId, $mentorUserId)
    {
        return $this->bookingModel->markBookingCompleted($bookingId, $mentorUserId);
    }

    public function autoCompletePassedSessions()
    {
        return $this->bookingModel->autoCompletePassedSessions();
    }

    // =====================================================
    // FEEDBACK (delegates to MentorshipFeedback)
    // =====================================================

    public function submitFeedback($bookingId, $studentId, $rating, $reviewText = '')
    {
        return $this->feedbackModel->submitFeedback($bookingId, $studentId, $rating, $reviewText);
    }

    // =====================================================
    // NOTIFICATIONS (delegates to MentorshipNotification)
    // =====================================================

    public function createNotification($userId, $bookingId, $type, $title, $message, $priority = 'normal')
    {
        return $this->notificationModel->createNotification($userId, $bookingId, $type, $title, $message, $priority);
    }

    public function getUnreadNotifications($userId, $limit = 10)
    {
        return $this->notificationModel->getUnreadNotifications($userId, $limit);
    }

    public function markNotificationRead($notificationId)
    {
        return $this->notificationModel->markNotificationRead($notificationId);
    }

    public function countUnreadNotifications($userId)
    {
        return $this->notificationModel->countUnreadNotifications($userId);
    }
}
