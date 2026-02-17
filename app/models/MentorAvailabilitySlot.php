<?php
/**
 * MentorAvailabilitySlot - Slot Management
 * ==========================================
 * Handles mentor availability slot operations:
 * add, remove, get, and cleanup expired slots.
 * 
 * Table: mentor_availability_slots
 */
require_once __DIR__ . '/MentorshipBase.php';

class MentorAvailabilitySlot extends MentorshipBase
{
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
                
                if (strtotime($formattedSlot) <= time()) {
                    continue;
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
}
