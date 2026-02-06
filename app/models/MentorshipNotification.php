<?php
/**
 * MentorshipNotification - Notification Management
 * ==================================================
 * Handles creating, reading, and managing notifications
 * for mentorship-related events.
 * 
 * Table: mentorship_notifications
 */
require_once __DIR__ . '/MentorshipBase.php';

class MentorshipNotification extends MentorshipBase
{
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
}
