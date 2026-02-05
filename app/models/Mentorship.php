<?php
/**
 * Mentorship Model - SIMPLIFIED SYSTEM
 * =====================================
 * NEW 2-Week Rolling Availability Workflow:
 * 1. Alumni sets available time slots (updates every 2 weeks)
 * 2. Student books any open slot (INSTANT confirmation)
 * 3. Cancel & Rebook if needed (with required reason)
 * 
 * Tables Used:
 * - mentor_availability_slots (alumni's available times)
 * - mentorship_bookings (confirmed sessions)
 * - mentorship_feedback (reviews after sessions)
 * - mentorship_notifications (alerts for both parties)
 * - mentors, users, alumni_profiles, undergraduate_profiles
 */
class Mentorship
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    /**
     * Ensure a mentor profile exists for an alumni user
     * Auto-creates one if not exists
     * 
     * @param int $userId The alumni's user ID
     * @return int|null The mentor_id
     */
    public function ensureMentorExists($userId)
    {
        try {
            // Check if mentor profile already exists
            $mentorId = $this->getMentorIdByUserId($userId);
            if ($mentorId) {
                return $mentorId;
            }
            
            // Create new mentor profile
            $query = "INSERT INTO mentors (user_id, is_active, expertise_areas) VALUES (?, 1, NULL)";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$userId]);
            
            return $this->db->lastInsertId();
        } catch (PDOException $e) {
            error_log("Error ensuring mentor exists: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Get mentor status (exists and is_active)
     * 
     * @param int $userId The alumni's user ID
     * @return array|null Array with mentor_id and is_active, or null if not found
     */
    public function getMentorStatus($userId)
    {
        try {
            $query = "SELECT mentor_id, is_active FROM mentors WHERE user_id = ?";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$userId]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result ?: null;
        } catch (PDOException $e) {
            error_log("Error getting mentor status: " . $e->getMessage());
            return null;
        }
    }

    // =====================================================
    // SECTION 1: MENTOR AVAILABILITY MANAGEMENT
    // Alumni sets their available slots (2-week rolling)
    // =====================================================

    /**
     * Add availability slots for a mentor
     * Alumni can add multiple time slots at once
     * 
     * @param int $userId The alumni's user ID
     * @param array $slots Array of datetime strings ['2026-01-28 10:00', '2026-01-28 14:00']
     * @param int $duration Duration in minutes (default 60)
     * @return array Result with success count
     */
    public function addAvailabilitySlots($userId, $slots, $duration = 60)
    {
        try {
            // Get mentor_id from user_id
            $mentorId = $this->getMentorIdByUserId($userId);
            if (!$mentorId) {
                return ['success' => false, 'message' => 'Mentor profile not found'];
            }

            $successCount = 0;
            $duplicates = 0;
            
            $query = "INSERT IGNORE INTO mentor_availability_slots 
                      (mentor_id, slot_datetime, duration_minutes) 
                      VALUES (?, ?, ?)";
            $stmt = $this->db->prepare($query);

            foreach ($slots as $slotDatetime) {
                $formattedSlot = date('Y-m-d H:i:s', strtotime($slotDatetime));
                
                // Check if slot is in the future
                if (strtotime($formattedSlot) <= time()) {
                    continue; // Skip past slots
                }
                
                $stmt->execute([$mentorId, $formattedSlot, $duration]);
                
                if ($stmt->rowCount() > 0) {
                    $successCount++;
                } else {
                    $duplicates++;
                }
            }

            return [
                'success' => true,
                'added' => $successCount,
                'duplicates' => $duplicates,
                'message' => "Added $successCount slots" . ($duplicates > 0 ? " ($duplicates already existed)" : "")
            ];
        } catch (PDOException $e) {
            error_log("Error adding availability slots: " . $e->getMessage());
            return ['success' => false, 'message' => 'Database error'];
        }
    }

    /**
     * Remove an availability slot (only if not booked)
     * 
     * @param int $userId The alumni's user ID
     * @param int $slotId The slot ID to remove
     * @return array Result
     */
    public function removeAvailabilitySlot($userId, $slotId)
    {
        try {
            $mentorId = $this->getMentorIdByUserId($userId);
            if (!$mentorId) {
                return ['success' => false, 'message' => 'Mentor profile not found'];
            }

            // Only delete if not booked and belongs to this mentor
            $query = "DELETE FROM mentor_availability_slots 
                      WHERE slot_id = ? AND mentor_id = ? AND is_booked = 0";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$slotId, $mentorId]);

            if ($stmt->rowCount() > 0) {
                return ['success' => true, 'message' => 'Slot removed'];
            } else {
                return ['success' => false, 'message' => 'Cannot remove: slot is booked or not found'];
            }
        } catch (PDOException $e) {
            error_log("Error removing slot: " . $e->getMessage());
            return ['success' => false, 'message' => 'Database error'];
        }
    }

    /**
     * Get mentor's availability slots
     * 
     * @param int $userId The alumni's user ID
     * @param bool $futureOnly Only return future slots
     * @return array Array of slots
     */
    public function getMentorAvailability($userId, $futureOnly = true)
    {
        try {
            $mentorId = $this->getMentorIdByUserId($userId);
            if (!$mentorId) {
                return [];
            }

            $query = "SELECT 
                        mas.slot_id, mas.slot_datetime, mas.duration_minutes, mas.is_booked,
                        mas.booked_by_student_id,
                        CASE WHEN mas.is_booked = 1 THEN
                            CONCAT(u.first_name, ' ', u.last_name)
                        ELSE NULL END as booked_by_name
                      FROM mentor_availability_slots mas
                      LEFT JOIN users u ON mas.booked_by_student_id = u.user_id
                      WHERE mas.mentor_id = ?";
            
            if ($futureOnly) {
                $query .= " AND mas.slot_datetime > NOW()";
            }
            
            $query .= " ORDER BY mas.slot_datetime ASC";

            $stmt = $this->db->prepare($query);
            $stmt->execute([$mentorId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error getting mentor availability: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Clean up expired/past slots
     * 
     * @param int $userId The alumni's user ID (optional, null = all mentors)
     * @return int Number of slots removed
     */
    public function removeExpiredSlots($userId = null)
    {
        try {
            if ($userId) {
                $mentorId = $this->getMentorIdByUserId($userId);
                $query = "DELETE FROM mentor_availability_slots 
                          WHERE mentor_id = ? AND slot_datetime < NOW() AND is_booked = 0";
                $stmt = $this->db->prepare($query);
                $stmt->execute([$mentorId]);
            } else {
                $query = "DELETE FROM mentor_availability_slots 
                          WHERE slot_datetime < NOW() AND is_booked = 0";
                $stmt = $this->db->prepare($query);
                $stmt->execute();
            }
            return $stmt->rowCount();
        } catch (PDOException $e) {
            error_log("Error removing expired slots: " . $e->getMessage());
            return 0;
        }
    }

    // =====================================================
    // SECTION 2: STUDENT BOOKING (INSTANT CONFIRMATION)
    // Student sees available slots and books instantly
    // =====================================================

    /**
     * Get all available slots for a specific mentor
     * Student uses this to see when mentor is free
     * 
     * @param int $mentorId The mentor ID
     * @param int $weeksAhead How many weeks to show (default 2)
     * @return array Available slots
     */
    public function getAvailableSlots($mentorId, $weeksAhead = 2)
    {
        try {
            $endDate = date('Y-m-d H:i:s', strtotime("+$weeksAhead weeks"));
            
            $query = "SELECT 
                        slot_id, slot_datetime, duration_minutes
                      FROM mentor_availability_slots
                      WHERE mentor_id = ?
                      AND is_booked = 0
                      AND slot_datetime > NOW()
                      AND slot_datetime <= ?
                      ORDER BY slot_datetime ASC";
            
            $stmt = $this->db->prepare($query);
            $stmt->execute([$mentorId, $endDate]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error getting available slots: " . $e->getMessage());
            return [];
        }
    }

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

            // Notify student
            $this->createNotification(
                $studentId,
                $bookingId,
                'session_confirmed',
                'Session Booked! 🎉',
                "Your mentorship session with $mentorName is confirmed for $formattedDate.",
                'high'
            );

            // Notify mentor
            $this->createNotification(
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

    // =====================================================
    // SECTION 3: CANCEL & REBOOK
    // Either party can cancel (with required reason)
    // =====================================================

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
        // Reason is required
        if (empty(trim($reason))) {
            return ['success' => false, 'message' => 'Cancellation reason is required'];
        }

        try {
            // Get booking details
            $booking = $this->getBookingById($bookingId);
            if (!$booking) {
                return ['success' => false, 'message' => 'Booking not found'];
            }

            // Verify user is part of this booking
            $mentorUserId = $this->getMentorUserId($booking['mentor_id']);
            if ($booking['student_id'] != $userId && $mentorUserId != $userId) {
                return ['success' => false, 'message' => 'Unauthorized'];
            }

            if ($booking['status'] !== 'scheduled') {
                return ['success' => false, 'message' => 'Cannot cancel: session is not scheduled'];
            }

            $this->db->beginTransaction();

            // Update booking status
            $query = "UPDATE mentorship_bookings 
                      SET status = 'cancelled', 
                          cancellation_reason = ?, 
                          cancelled_by = ?,
                          updated_at = NOW()
                      WHERE booking_id = ?";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$reason, $userId, $bookingId]);

            // Free up the slot so someone else can book it
            $query = "UPDATE mentor_availability_slots 
                      SET is_booked = 0, booked_by_student_id = NULL, booking_id = NULL
                      WHERE slot_id = ?";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$booking['slot_id']]);

            // Determine who cancelled and notify the other party
            $cancelledByStudent = ($booking['student_id'] == $userId);
            $notifyUserId = $cancelledByStudent ? $mentorUserId : $booking['student_id'];
            $cancellerName = $this->getUserName($userId);

            $this->createNotification(
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

    // =====================================================
    // SECTION 4: GET BOOKINGS (UPCOMING & COMPLETED)
    // =====================================================

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
     * Generic method to retrieve bookings for a student filtered by status
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

            // For scheduled, show only future sessions
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
     * Generic method to retrieve bookings for a mentor filtered by status
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

            // For scheduled, show only future sessions
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

    // =====================================================
    // SECTION 5: FEEDBACK / REVIEW SYSTEM
    // =====================================================

    /**
     * Submit feedback for a completed session
     * 
     * @param int $bookingId The booking ID
     * @param int $studentId The student's user ID
     * @param int $rating Rating 1-5
     * @param string $reviewText Optional review text
     * @return array Result
     */
    public function submitFeedback($bookingId, $studentId, $rating, $reviewText = '')
    {
        try {
            // Verify booking belongs to this student
            $booking = $this->getBookingById($bookingId);
            if (!$booking || $booking['student_id'] != $studentId) {
                return ['success' => false, 'message' => 'Unauthorized'];
            }

            // Validate rating
            $rating = (int)$rating;
            if ($rating < 1 || $rating > 5) {
                return ['success' => false, 'message' => 'Rating must be between 1 and 5'];
            }

            $this->db->beginTransaction();

            // Insert or update feedback
            $query = "INSERT INTO mentorship_feedback 
                      (booking_id, mentor_id, student_id, rating, review_text)
                      VALUES (?, ?, ?, ?, ?)
                      ON DUPLICATE KEY UPDATE rating = ?, review_text = ?";
            $stmt = $this->db->prepare($query);
            $stmt->execute([
                $bookingId,
                $booking['mentor_id'],
                $studentId,
                $rating,
                $reviewText,
                $rating,
                $reviewText
            ]);

            // Update booking status to completed
            $query = "UPDATE mentorship_bookings SET status = 'completed' WHERE booking_id = ?";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$bookingId]);

            // Notify mentor
            $mentorUserId = $this->getMentorUserId($booking['mentor_id']);
            $studentName = $this->getUserName($studentId);
            
            $this->createNotification(
                $mentorUserId,
                $bookingId,
                'feedback_received',
                'New Feedback ⭐',
                "$studentName has left a $rating-star review for your mentorship session.",
                'normal'
            );

            $this->db->commit();

            return ['success' => true, 'message' => 'Thank you for your feedback!'];
        } catch (Exception $e) {
            $this->db->rollBack();
            error_log("Error submitting feedback: " . $e->getMessage());
            return ['success' => false, 'message' => 'Failed to submit feedback'];
        }
    }

    /**
     * Get mentor's average rating
     * 
     * @param int $mentorId The mentor ID
     * @return array Average rating and count
     */
    public function getMentorRating($mentorId)
    {
        try {
            $query = "SELECT AVG(rating) as avg_rating, COUNT(*) as review_count 
                      FROM mentorship_feedback WHERE mentor_id = ?";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$mentorId]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return [
                'average' => round((float)($result['avg_rating'] ?? 0), 1),
                'count' => (int)($result['review_count'] ?? 0)
            ];
        } catch (PDOException $e) {
            return ['average' => 0, 'count' => 0];
        }
    }

    // =====================================================
    // SECTION 6: MEETING JOIN & STATUS
    // =====================================================

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
        
        // Can join from 15 minutes before until 2 hours after
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

    // =====================================================
    // SECTION 7: MENTOR LISTING & SEARCH
    // =====================================================

    /**
     * Get all available mentors
     * 
     * @param string $searchTerm Search term
     * @param string $expertise Expertise filter
     * @return array Mentors
     */
    public function getAllMentors($searchTerm = '', $expertise = '')
    {
        try {
            $query = "SELECT 
                        u.user_id, u.first_name, u.last_name, u.email, u.profile_picture as profile_picture_url,
                        m.mentor_id, m.expertise_areas, m.is_active,
                        ap.current_job_title as title, ap.current_company as company, ap.skills_experience as bio,
                        ap.linkedin_url, ap.github_url,
                        (SELECT COUNT(*) FROM mentorship_bookings mb WHERE mb.mentor_id = m.mentor_id AND mb.status = 'completed') as total_sessions,
                        (SELECT AVG(rating) FROM mentorship_feedback mf WHERE mf.mentor_id = m.mentor_id) as avg_rating,
                        (SELECT COUNT(*) FROM mentor_availability_slots mas WHERE mas.mentor_id = m.mentor_id AND mas.is_booked = 0 AND mas.slot_datetime > NOW()) as available_slots
                      FROM mentors m
                      INNER JOIN users u ON m.user_id = u.user_id
                      LEFT JOIN alumni_profiles ap ON u.user_id = ap.user_id
                      WHERE m.is_active = 1";

            $params = [];

            if (!empty($searchTerm)) {
                $query .= " AND (u.first_name LIKE ? OR u.last_name LIKE ? OR ap.current_job_title LIKE ? OR ap.current_company LIKE ?)";
                $searchParam = "%$searchTerm%";
                $params = array_merge($params, [$searchParam, $searchParam, $searchParam, $searchParam]);
            }

            if (!empty($expertise)) {
                $query .= " AND m.expertise_areas LIKE ?";
                $params[] = "%$expertise%";
            }

            $query .= " ORDER BY available_slots DESC, total_sessions DESC, u.first_name ASC";

            $stmt = $this->db->prepare($query);
            $stmt->execute($params);
            $mentors = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Process expertise areas to array
            foreach ($mentors as &$mentor) {
                $mentor['expertise_areas'] = $mentor['expertise_areas'] 
                    ? array_map('trim', explode(',', $mentor['expertise_areas'])) 
                    : [];
                $mentor['avg_rating'] = round((float)($mentor['avg_rating'] ?? 0), 1);
            }

            return $mentors;
        } catch (PDOException $e) {
            error_log("Error getting all mentors: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Get mentor profile by mentor ID
     * 
     * @param int $mentorId The mentor ID
     * @return array|null Mentor profile
     */
    public function getMentorById($mentorId)
    {
        try {
            $query = "SELECT 
                        u.user_id, u.first_name, u.last_name, u.email, u.profile_picture as profile_picture_url,
                        m.mentor_id, m.expertise_areas, m.is_active,
                        ap.current_job_title as title, ap.current_company as company, ap.skills_experience as bio,
                        ap.linkedin_url, ap.github_url
                      FROM mentors m
                      INNER JOIN users u ON m.user_id = u.user_id
                      LEFT JOIN alumni_profiles ap ON u.user_id = ap.user_id
                      WHERE m.mentor_id = ?";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$mentorId]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error getting mentor by ID: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Get mentor profile by user ID
     * 
     * @param int $userId The user ID
     * @return array|null Mentor profile
     */
    public function getMentorByUserId($userId)
    {
        try {
            $query = "SELECT 
                        u.user_id, u.first_name, u.last_name, u.email, u.profile_picture,
                        CONCAT(u.first_name, ' ', u.last_name) as full_name,
                        m.mentor_id, m.expertise_areas, m.is_active, m.max_mentees,
                        ap.current_job_title, ap.current_company, ap.skills_experience,
                        ap.linkedin_url, ap.github_url
                      FROM mentors m
                      INNER JOIN users u ON m.user_id = u.user_id
                      LEFT JOIN alumni_profiles ap ON u.user_id = ap.user_id
                      WHERE m.user_id = ?";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$userId]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error getting mentor by user ID: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Get available mentors with their slot counts
     * For the "Explore Mentors" page
     * 
     * @param string $searchTerm Search term
     * @param string $industry Industry filter
     * @param string $expertise Expertise filter
     * @return array List of mentors with available slots
     */
    public function getAvailableMentorsWithSlots($searchTerm = '', $industry = '', $expertise = '')
    {
        try {
            $query = "SELECT 
                        u.user_id, 
                        CONCAT(u.first_name, ' ', u.last_name) as name, 
                        u.email, 
                        u.profile_picture,
                        m.mentor_id, 
                        m.expertise_areas, 
                        m.is_active,
                        ap.current_job_title, 
                        ap.current_company, 
                        ap.skills_experience,
                        ap.linkedin_url, 
                        ap.github_url,
                        (SELECT COUNT(*) FROM mentorship_bookings mb WHERE mb.mentor_id = m.mentor_id AND mb.status = 'completed') as total_sessions,
                        (SELECT AVG(rating) FROM mentorship_feedback mf WHERE mf.mentor_id = m.mentor_id) as rating,
                        (SELECT COUNT(*) FROM mentorship_feedback mf WHERE mf.mentor_id = m.mentor_id) as review_count,
                        (SELECT COUNT(*) FROM mentor_availability_slots mas 
                         WHERE mas.mentor_id = m.mentor_id AND mas.is_booked = 0 AND mas.slot_datetime > NOW()) as available_slots
                      FROM mentors m
                      INNER JOIN users u ON m.user_id = u.user_id
                      LEFT JOIN alumni_profiles ap ON u.user_id = ap.user_id
                      WHERE m.is_active = 1
                      HAVING available_slots > 0";

            $params = [];

            if (!empty($searchTerm)) {
                $query = str_replace("WHERE m.is_active = 1", 
                    "WHERE m.is_active = 1 
                     AND (u.first_name LIKE ? OR u.last_name LIKE ? OR ap.current_job_title LIKE ? OR ap.current_company LIKE ? OR ap.skills_experience LIKE ?)",
                    $query);
                $searchParam = "%$searchTerm%";
                $params = [$searchParam, $searchParam, $searchParam, $searchParam, $searchParam];
            }

            if (!empty($expertise)) {
                $query .= " AND m.expertise_areas LIKE ?";
                $params[] = "%$expertise%";
            }

            // Industry filter (searches company name)
            if (!empty($industry)) {
                $query .= " AND ap.current_company LIKE ?";
                $params[] = "%$industry%";
            }

            $query .= " ORDER BY available_slots DESC, rating DESC, total_sessions DESC";

            $stmt = $this->db->prepare($query);
            $stmt->execute($params);
            $mentors = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Round ratings
            foreach ($mentors as &$mentor) {
                $mentor['rating'] = round((float)($mentor['rating'] ?? 0), 1);
            }

            return $mentors;
        } catch (PDOException $e) {
            error_log("Error getting available mentors with slots: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Get mentor statistics
     * 
     * @param int $userId The mentor's user ID
     * @return array Stats
     */
    public function getMentorStats($userId)
    {
        try {
            $mentorId = $this->getMentorIdByUserId($userId);
            if (!$mentorId) {
                return [
                    'total_sessions' => 0, 
                    'completed_sessions' => 0,
                    'total_mentees' => 0, 
                    'average_rating' => 0,
                    'review_count' => 0
                ];
            }

            // Total sessions (ALL bookings)
            $query = "SELECT COUNT(*) as total FROM mentorship_bookings WHERE mentor_id = ?";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$mentorId]);
            $totalSessions = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

            // Total completed sessions
            $query = "SELECT COUNT(*) as total FROM mentorship_bookings WHERE mentor_id = ? AND status = 'completed'";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$mentorId]);
            $completedSessions = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

            // Unique mentees
            $query = "SELECT COUNT(DISTINCT student_id) as total FROM mentorship_bookings WHERE mentor_id = ?";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$mentorId]);
            $mentees = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

            // Rating
            $rating = $this->getMentorRating($mentorId);

            return [
                'total_sessions' => (int)$totalSessions,
                'completed_sessions' => (int)$completedSessions,
                'total_mentees' => (int)$mentees,
                'average_rating' => $rating['average'],
                'review_count' => $rating['count']
            ];
        } catch (PDOException $e) {
            error_log("Error getting mentor stats: " . $e->getMessage());
            return [
                'total_sessions' => 0, 
                'completed_sessions' => 0,
                'total_mentees' => 0, 
                'average_rating' => 0,
                'review_count' => 0
            ];
        }
    }

    // =====================================================
    // SECTION 8: NOTIFICATIONS
    // =====================================================

    /**
     * Create a notification
     * 
     * @param int $userId User to notify
     * @param int $bookingId Related booking ID
     * @param string $type Notification type
     * @param string $title Notification title
     * @param string $message Notification message
     * @param string $priority Priority level
     * @return int|bool Notification ID or false
     */
    public function createNotification($userId, $bookingId, $type, $title, $message, $priority = 'normal')
    {
        try {
            $query = "INSERT INTO mentorship_notifications 
                      (user_id, session_id, notification_type, title, message, priority)
                      VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$userId, $bookingId, $type, $title, $message, $priority]);
            return $this->db->lastInsertId();
        } catch (PDOException $e) {
            error_log("Error creating notification: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Get unread notifications for a user
     * 
     * @param int $userId The user ID
     * @param int $limit Max notifications
     * @return array Notifications
     */
    public function getUnreadNotifications($userId, $limit = 10)
    {
        try {
            $query = "SELECT * FROM mentorship_notifications 
                      WHERE user_id = ? AND is_read = 0
                      ORDER BY created_at DESC
                      LIMIT ?";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$userId, $limit]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }

    /**
     * Mark notification as read
     * 
     * @param int $notificationId The notification ID
     * @return bool Success
     */
    public function markNotificationRead($notificationId)
    {
        try {
            $query = "UPDATE mentorship_notifications SET is_read = 1, read_at = NOW() WHERE notification_id = ?";
            $stmt = $this->db->prepare($query);
            return $stmt->execute([$notificationId]);
        } catch (PDOException $e) {
            return false;
        }
    }

    /**
     * Count unread notifications
     * 
     * @param int $userId The user ID
     * @return int Count
     */
    public function countUnreadNotifications($userId)
    {
        try {
            $query = "SELECT COUNT(*) as count FROM mentorship_notifications WHERE user_id = ? AND is_read = 0";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$userId]);
            return (int)$stmt->fetch(PDO::FETCH_ASSOC)['count'];
        } catch (PDOException $e) {
            return 0;
        }
    }

    // =====================================================
    // SECTION 9: HELPER METHODS
    // =====================================================

    /**
     * Get mentor_id from user_id
     */
    private function getMentorIdByUserId($userId)
    {
        try {
            $query = "SELECT mentor_id FROM mentors WHERE user_id = ?";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$userId]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result ? $result['mentor_id'] : null;
        } catch (PDOException $e) {
            return null;
        }
    }

    /**
     * Get user_id from mentor_id
     */
    private function getMentorUserId($mentorId)
    {
        try {
            $query = "SELECT user_id FROM mentors WHERE mentor_id = ?";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$mentorId]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result ? $result['user_id'] : null;
        } catch (PDOException $e) {
            return null;
        }
    }

    /**
     * Get user's full name
     */
    private function getUserName($userId)
    {
        try {
            $query = "SELECT CONCAT(first_name, ' ', last_name) as name FROM users WHERE user_id = ?";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$userId]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result ? $result['name'] : 'Unknown User';
        } catch (PDOException $e) {
            return 'Unknown User';
        }
    }

    /**
     * Generate Jitsi meeting link
     */
    private function generateJitsiLink($mentorId, $slotId)
    {
        $uniqueId = $mentorId . '_' . $slotId . '_' . time();
        $roomName = 'UniVerse_Mentorship_' . $uniqueId;
        return 'https://meet.jit.si/' . $roomName;
    }

    /**
     * Check if student has active request with mentor
     * In the new instant-booking system, this always returns false
     * (kept for backward compatibility with existing views)
     * 
     * @param int $studentId The student's user ID
     * @param int $mentorId The mentor ID
     * @return bool Always false in new system
     */
    public function hasActiveRequest($studentId, $mentorId)
    {
        // In the new system, students don't need to check for active requests
        // They can book any available slot directly
        return false;
    }
}
