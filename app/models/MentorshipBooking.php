<?php
/**
 * MentorshipBooking - Booking & Session Management
 * ==================================================
 * Handles booking slots, cancellations, session status,
 * and retrieving bookings for students and mentors.
 * 
 * Table: mentorship_bookings, mentor_availability_slots
 */
require_once __DIR__ . '/MentorshipBase.php';

class MentorshipBooking extends MentorshipBase
{
    /**
     * Book an available slot - INSTANT CONFIRMATION
     * Uses transaction with row locking to prevent double-booking
     * 
     * @param int $slotId The slot ID to book
     * @param int $studentId The student's user ID
     * @return array Result with booking details
     */
    public function bookSlot($slotId, $studentId)
    {
        try {
            $this->db->beginTransaction();

            // Lock the slot row to prevent race conditions
            $query = "SELECT * FROM mentor_availability_slots 
                      WHERE slot_id = ? FOR UPDATE";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$slotId]);
            $slot = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$slot) {
                $this->db->rollBack();
                return ['success' => false, 'message' => 'Slot not found'];
            }

            if ($slot['is_booked']) {
                $this->db->rollBack();
                return ['success' => false, 'message' => 'This slot was just booked by someone else. Please choose another time.'];
            }

            if (strtotime($slot['slot_datetime']) <= time()) {
                $this->db->rollBack();
                return ['success' => false, 'message' => 'This slot has already passed'];
            }

            // Generate meeting link
            $meetingLink = $this->generateJitsiLink($slot['mentor_id'], $slotId);

            // Create the booking
            $bookingQuery = "INSERT INTO mentorship_bookings 
                             (slot_id, mentor_id, student_id, session_datetime, duration_minutes, meeting_link, status)
                             VALUES (?, ?, ?, ?, ?, ?, 'scheduled')";
            $bookingStmt = $this->db->prepare($bookingQuery);
            $bookingStmt->execute([
                $slotId,
                $slot['mentor_id'],
                $studentId,
                $slot['slot_datetime'],
                $slot['duration_minutes'],
                $meetingLink
            ]);
            $bookingId = $this->db->lastInsertId();

            // Mark slot as booked
            $updateQuery = "UPDATE mentor_availability_slots 
                           SET is_booked = 1, booked_by_student_id = ?, booking_id = ?
                           WHERE slot_id = ?";
            $updateStmt = $this->db->prepare($updateQuery);
            $updateStmt->execute([$studentId, $bookingId, $slotId]);

            // Get mentor and student names for notifications
            $mentorUserId = $this->getMentorUserId($slot['mentor_id']);
            $studentName = $this->getUserName($studentId);
            $mentorName = $this->getUserName($mentorUserId);
            $formattedDate = date('l, F j, Y \a\t g:i A', strtotime($slot['slot_datetime']));

            // Create notifications using MentorshipNotification
            $notificationModel = new MentorshipNotification();

            $notificationModel->createNotification(
                $studentId,
                $bookingId,
                'session_confirmed',
                'Session Booked! 🎉',
                "Your mentorship session with $mentorName is confirmed for $formattedDate.",
                'high'
            );

            $notificationModel->createNotification(
                $mentorUserId,
                $bookingId,
                'session_confirmed',
                'New Booking! 📅',
                "$studentName has booked a mentorship session with you for $formattedDate.",
                'high'
            );

            $this->db->commit();

            return [
                'success' => true,
                'booking_id' => $bookingId,
                'meeting_link' => $meetingLink,
                'session_datetime' => $slot['slot_datetime'],
                'message' => "Session booked for $formattedDate"
            ];
        } catch (Exception $e) {
            $this->db->rollBack();
            error_log("Error booking slot: " . $e->getMessage());
            return ['success' => false, 'message' => 'Booking failed. Please try again.'];
        }
    }

    /**
     * Cancel a booking (either party)
     * Reason is REQUIRED - cannot cancel without explaining why
     * 
     * @param int $bookingId The booking ID
     * @param int $userId The user ID cancelling
     * @param string $reason The cancellation reason (REQUIRED)
     * @return array Result
     */
    public function cancelBooking($bookingId, $userId, $reason)
    {
        if (empty(trim($reason))) {
            return ['success' => false, 'message' => 'Cancellation reason is required'];
        }

        try {
            $booking = $this->getBookingById($bookingId);
            if (!$booking) {
                return ['success' => false, 'message' => 'Booking not found'];
            }

            $mentorUserId = $this->getMentorUserId($booking['mentor_id']);
            if ($booking['student_id'] != $userId && $mentorUserId != $userId) {
                return ['success' => false, 'message' => 'Unauthorized'];
            }

            if ($booking['status'] !== 'scheduled') {
                return ['success' => false, 'message' => 'Cannot cancel: session is not scheduled'];
            }

            $this->db->beginTransaction();

            $query = "UPDATE mentorship_bookings 
                      SET status = 'cancelled', 
                          cancellation_reason = ?, 
                          cancelled_by = ?,
                          updated_at = NOW()
                      WHERE booking_id = ?";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$reason, $userId, $bookingId]);

            $query = "UPDATE mentor_availability_slots 
                      SET is_booked = 0, booked_by_student_id = NULL, booking_id = NULL
                      WHERE slot_id = ?";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$booking['slot_id']]);

            $cancelledByStudent = ($booking['student_id'] == $userId);
            $notifyUserId = $cancelledByStudent ? $mentorUserId : $booking['student_id'];
            $cancellerName = $this->getUserName($userId);

            $notificationModel = new MentorshipNotification();
            $notificationModel->createNotification(
                $notifyUserId,
                $bookingId,
                'session_cancelled',
                'Session Cancelled ❌',
                "$cancellerName has cancelled the mentorship session. Reason: $reason",
                'high'
            );

            $this->db->commit();

            return ['success' => true, 'message' => 'Session cancelled. The time slot is now available for rebooking.'];
        } catch (Exception $e) {
            $this->db->rollBack();
            error_log("Error cancelling booking: " . $e->getMessage());
            return ['success' => false, 'message' => 'Failed to cancel'];
        }
    }

    /**
     * Get upcoming bookings for a student
     * 
     * @param int $studentId The student's user ID
     * @return array Upcoming sessions
     */
    public function getUpcomingBookingsForStudent($studentId)
    {
        try {
            $query = "SELECT 
                        mb.booking_id, mb.session_datetime, mb.duration_minutes, 
                        mb.meeting_link, mb.status, mb.mentor_id,
                        u.first_name as mentor_first_name, u.last_name as mentor_last_name,
                        u.profile_picture as mentor_picture,
                        ap.current_job_title as mentor_title, ap.current_company as mentor_company
                      FROM mentorship_bookings mb
                      INNER JOIN mentors m ON mb.mentor_id = m.mentor_id
                      INNER JOIN users u ON m.user_id = u.user_id
                      LEFT JOIN alumni_profiles ap ON u.user_id = ap.user_id
                      WHERE mb.student_id = ?
                      AND mb.status = 'scheduled'
                      AND mb.session_datetime > NOW()
                      ORDER BY mb.session_datetime ASC";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$studentId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error getting student bookings: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Get upcoming bookings for a mentor (alumni)
     * 
     * @param int $userId The alumni's user ID
     * @return array Upcoming sessions
     */
    public function getUpcomingBookingsForMentor($userId)
    {
        try {
            $mentorId = $this->getMentorIdByUserId($userId);
            if (!$mentorId) {
                return [];
            }

            $query = "SELECT 
                        mb.booking_id, mb.session_datetime, mb.duration_minutes,
                        mb.meeting_link, mb.status, mb.student_id,
                        u.first_name as student_first_name, u.last_name as student_last_name,
                        u.profile_picture as student_picture,
                        up.degree_program, up.academic_year
                      FROM mentorship_bookings mb
                      INNER JOIN users u ON mb.student_id = u.user_id
                      LEFT JOIN undergraduate_profiles up ON u.user_id = up.user_id
                      WHERE mb.mentor_id = ?
                      AND mb.status = 'scheduled'
                      AND mb.session_datetime > NOW()
                      ORDER BY mb.session_datetime ASC";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$mentorId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error getting mentor bookings: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Get completed sessions for a student (for feedback)
     * 
     * @param int $studentId The student's user ID
     * @return array Completed sessions
     */
    public function getCompletedBookingsForStudent($studentId)
    {
        try {
            $query = "SELECT 
                        mb.booking_id, mb.session_datetime, mb.duration_minutes, mb.status,
                        mb.mentor_id,
                        u.first_name as mentor_first_name, u.last_name as mentor_last_name,
                        u.profile_picture as mentor_picture,
                        ap.current_job_title as mentor_title,
                        mf.rating, mf.review_text
                      FROM mentorship_bookings mb
                      INNER JOIN mentors m ON mb.mentor_id = m.mentor_id
                      INNER JOIN users u ON m.user_id = u.user_id
                      LEFT JOIN alumni_profiles ap ON u.user_id = ap.user_id
                      LEFT JOIN mentorship_feedback mf ON mb.booking_id = mf.booking_id
                      WHERE mb.student_id = ?
                      AND (mb.status = 'completed' OR mb.session_datetime < NOW())
                      ORDER BY mb.session_datetime DESC";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$studentId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error getting completed bookings: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Get completed sessions for a mentor
     * 
     * @param int $userId The alumni's user ID
     * @return array Completed sessions
     */
    public function getCompletedBookingsForMentor($userId)
    {
        try {
            $mentorId = $this->getMentorIdByUserId($userId);
            if (!$mentorId) {
                return [];
            }

            $query = "SELECT 
                        mb.booking_id, mb.session_datetime, mb.duration_minutes, mb.status,
                        mb.student_id,
                        u.first_name as student_first_name, u.last_name as student_last_name,
                        u.profile_picture as student_picture,
                        up.degree_program,
                        mf.rating, mf.review_text
                      FROM mentorship_bookings mb
                      INNER JOIN users u ON mb.student_id = u.user_id
                      LEFT JOIN undergraduate_profiles up ON u.user_id = up.user_id
                      LEFT JOIN mentorship_feedback mf ON mb.booking_id = mf.booking_id
                      WHERE mb.mentor_id = ?
                      AND (mb.status = 'completed' OR mb.session_datetime < NOW())
                      ORDER BY mb.session_datetime DESC";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$mentorId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error getting completed bookings for mentor: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Get student bookings by status
     * 
     * @param int $studentId The student's user ID
     * @param string $status Status filter: scheduled, completed, cancelled, no_show, or 'all'
     * @return array Bookings list
     */
    public function getStudentBookings($studentId, $status = 'all')
    {
        try {
            $query = "SELECT 
                        mb.booking_id, mb.session_datetime as slot_datetime, mb.duration_minutes, 
                        mb.meeting_link, mb.status, mb.mentor_id, mb.cancellation_reason,
                        CONCAT(u.first_name, ' ', u.last_name) as mentor_name,
                        u.profile_picture as mentor_picture,
                        ap.current_job_title as mentor_title, ap.current_company as mentor_company,
                        mf.rating, mf.review_text
                      FROM mentorship_bookings mb
                      INNER JOIN mentors m ON mb.mentor_id = m.mentor_id
                      INNER JOIN users u ON m.user_id = u.user_id
                      LEFT JOIN alumni_profiles ap ON u.user_id = ap.user_id
                      LEFT JOIN mentorship_feedback mf ON mb.booking_id = mf.booking_id
                      WHERE mb.student_id = ?";

            $params = [$studentId];

            if ($status !== 'all') {
                $query .= " AND mb.status = ?";
                $params[] = $status;
            }

            if ($status === 'scheduled') {
                $query .= " AND mb.session_datetime > NOW()";
            }

            $query .= " ORDER BY mb.session_datetime " . ($status === 'scheduled' ? 'ASC' : 'DESC');

            $stmt = $this->db->prepare($query);
            $stmt->execute($params);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error getting student bookings: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Get mentor bookings by status
     * 
     * @param int $userId The mentor's user ID
     * @param string $status Status filter: scheduled, completed, cancelled, no_show, or 'all'
     * @return array Bookings list
     */
    public function getMentorBookings($userId, $status = 'all')
    {
        try {
            $mentorId = $this->getMentorIdByUserId($userId);
            if (!$mentorId) {
                return [];
            }

            $query = "SELECT 
                        mb.booking_id, mb.session_datetime as slot_datetime, mb.duration_minutes,
                        mb.meeting_link, mb.status, mb.student_id, mb.cancellation_reason,
                        CONCAT(u.first_name, ' ', u.last_name) as student_name,
                        u.profile_picture as student_picture,
                        up.degree_program, up.academic_year,
                        mf.rating, mf.review_text
                      FROM mentorship_bookings mb
                      INNER JOIN users u ON mb.student_id = u.user_id
                      LEFT JOIN undergraduate_profiles up ON u.user_id = up.user_id
                      LEFT JOIN mentorship_feedback mf ON mb.booking_id = mf.booking_id
                      WHERE mb.mentor_id = ?";

            $params = [$mentorId];

            if ($status !== 'all') {
                $query .= " AND mb.status = ?";
                $params[] = $status;
            }

            if ($status === 'scheduled') {
                $query .= " AND mb.session_datetime > NOW()";
            }

            $query .= " ORDER BY mb.session_datetime " . ($status === 'scheduled' ? 'ASC' : 'DESC');

            $stmt = $this->db->prepare($query);
            $stmt->execute($params);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error getting mentor bookings: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Get a single booking by ID
     * 
     * @param int $bookingId The booking ID
     * @return array|null Booking details
     */
    public function getBookingById($bookingId)
    {
        try {
            $query = "SELECT 
                        mb.*,
                        su.first_name as student_first_name, su.last_name as student_last_name,
                        su.profile_picture as student_picture, su.email as student_email,
                        mu.first_name as mentor_first_name, mu.last_name as mentor_last_name,
                        mu.profile_picture as mentor_picture, mu.email as mentor_email,
                        ap.current_job_title as mentor_title, ap.current_company as mentor_company,
                        up.degree_program, up.academic_year
                      FROM mentorship_bookings mb
                      INNER JOIN users su ON mb.student_id = su.user_id
                      INNER JOIN mentors m ON mb.mentor_id = m.mentor_id
                      INNER JOIN users mu ON m.user_id = mu.user_id
                      LEFT JOIN alumni_profiles ap ON mu.user_id = ap.user_id
                      LEFT JOIN undergraduate_profiles up ON su.user_id = up.user_id
                      WHERE mb.booking_id = ?";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$bookingId]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error getting booking: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Check if a session can be joined (within time window)
     * Can join from 15 minutes before until 2 hours after start time
     * 
     * @param string $sessionDatetime The session datetime
     * @return array Join status information
     */
    public function canJoinSession($sessionDatetime)
    {
        $now = new DateTime();
        $sessionTime = new DateTime($sessionDatetime);
        
        $minutesUntil = ($sessionTime->getTimestamp() - $now->getTimestamp()) / 60;
        
        $canJoin = $minutesUntil <= 15 && $minutesUntil > -120;
        $isActive = $minutesUntil <= 0 && $minutesUntil > -120;
        $hasEnded = $minutesUntil <= -120;
        
        return [
            'can_join' => $canJoin,
            'is_active' => $isActive,
            'has_ended' => $hasEnded,
            'minutes_until' => round($minutesUntil),
            'status' => $hasEnded ? 'ended' : ($isActive ? 'active' : ($canJoin ? 'joinable' : 'upcoming'))
        ];
    }

    /**
     * Mark a session as completed
     * 
     * @param int $bookingId The booking ID
     * @return bool Success
     */
    public function markSessionCompleted($bookingId)
    {
        try {
            $query = "UPDATE mentorship_bookings SET status = 'completed', updated_at = NOW() WHERE booking_id = ?";
            $stmt = $this->db->prepare($query);
            return $stmt->execute([$bookingId]);
        } catch (PDOException $e) {
            error_log("Error marking session completed: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Mark a session as no-show
     * 
     * @param int $bookingId The booking ID
     * @param string $noShowBy Who didn't show
     * @return bool Success
     */
    public function markSessionNoShow($bookingId, $noShowBy = null)
    {
        try {
            $notes = $noShowBy ? "No-show by: $noShowBy" : "No-show";
            $query = "UPDATE mentorship_bookings SET status = 'no_show', notes = ?, updated_at = NOW() WHERE booking_id = ?";
            $stmt = $this->db->prepare($query);
            return $stmt->execute([$notes, $bookingId]);
        } catch (PDOException $e) {
            error_log("Error marking session no-show: " . $e->getMessage());
            return false;
        }
    }
}
