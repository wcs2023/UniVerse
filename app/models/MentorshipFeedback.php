<?php
/**
 * MentorshipFeedback - Feedback & Review System
 * ================================================
 * Handles feedback submission and rating queries
 * for completed mentorship sessions.
 * 
 * Table: mentorship_feedback
 */
require_once __DIR__ . '/MentorshipBase.php';

class MentorshipFeedback extends MentorshipBase
{
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
            // Get booking details to verify ownership
            $bookingModel = new MentorshipBooking();
            $booking = $bookingModel->getBookingById($bookingId);
            if (!$booking || $booking['student_id'] != $studentId) {
                return ['success' => false, 'message' => 'Unauthorized'];
            }

            $rating = (int)$rating;
            if ($rating < 1 || $rating > 5) {
                return ['success' => false, 'message' => 'Rating must be between 1 and 5'];
            }

            $this->db->beginTransaction();

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

            $query = "UPDATE mentorship_bookings SET status = 'completed' WHERE booking_id = ?";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$bookingId]);

            // Notify mentor
            $mentorUserId = $this->getMentorUserId($booking['mentor_id']);
            $studentName = $this->getUserName($studentId);
            
            $notificationModel = new MentorshipNotification();
            $notificationModel->createNotification(
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
}
