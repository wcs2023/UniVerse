<?php
// Define constants if not already defined (for direct access)
if (!defined('APPROOT')) {
    define('APPROOT', dirname(dirname(dirname(__FILE__))));
}
if (!defined('URLROOT')) {
    define('URLROOT', 'http://localhost/UniVerse/public');
}
if (!defined('BASE_URL')) {
    define('BASE_URL', 'http://localhost/UniVerse/public');
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mentor Dashboard - UniVerse</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/css/alumni.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/css/mentorship.css">
    <link rel="icon" type="image/png" href="<?= BASE_URL ?>/assets/images/U.png">
    <script>
        window.BASE_URL = '<?= BASE_URL ?>';
        window.USER_TYPE = 'alumni';
    </script>
    <style>
        body { padding-top: 80px; background-color: #a78bfa45 !important; }
        .visually-hidden {
            position: absolute; width: 1px; height: 1px; padding: 0; margin: -1px;
            overflow: hidden; clip: rect(0,0,0,0); white-space: nowrap; border: 0;
        }
    </style>
</head>

<body>
    <?php
    // Include navigation
    $navFile = APPROOT . '/views/actors/alumni/Anavbar.php';
    if (file_exists($navFile)) {
        include $navFile;
    }
    ?>

    <main id="main-content" role="main">
    <div class="ms-container">
        <!-- Success Message -->
        <?php if (isset($_GET['success'])): ?>
            <div class="ms-success-message">
                <span class="ms-status-dot ms-status-dot--active"></span>
                <span>
                    <?php 
                    switch($_GET['success']) {
                        case 'slots_added':
                            $count = isset($_GET['count']) ? (int)$_GET['count'] : 0;
                            $skipped = isset($_GET['skipped']) ? (int)$_GET['skipped'] : 0;
                            $msg = $count > 0 
                                ? "Added {$count} availability slot" . ($count > 1 ? 's' : '') . " successfully!"
                                : 'Availability slots added successfully!';
                            if ($skipped > 0) {
                                $msg .= " ({$skipped} duplicate" . ($skipped > 1 ? 's' : '') . " skipped)";
                            }
                            echo $msg . ' Students can now book these times.';
                            break;
                        case 'booking_cancelled':
                            echo 'Booking has been cancelled successfully.';
                            break;
                        case 'completed':
                            echo 'Session marked as completed.';
                            break;
                        default:
                            echo 'Action completed successfully.';
                    }
                    ?>
                </span>
            </div>
        <?php endif; ?>

        <h1 class="ms-page-title">Mentor Dashboard</h1>

        <div class="ms-dashboard-grid">
            <!-- Left Column -->
            <div>
                <!-- Availability Management -->
                <div class="ms-card">
                    <div class="ms-availability-header">
                        <h2 class="ms-card-title">My Availability (Next 2 Weeks)</h2>
                        <button class="ms-btn-add-availability" onclick="openAddSlotsModal()" aria-label="Add new availability slots">
                            + Add Slots
                        </button>
                    </div>

                    <div class="ms-slots-grid">
                        <?php if (isset($data['availability_slots']) && count($data['availability_slots']) > 0): ?>
                            <?php foreach ($data['availability_slots'] as $slot): ?>
                                <?php 
                                    $slotDate = new DateTime($slot['slot_datetime']);
                                    $isBooked = !empty($slot['is_booked']) || $slot['is_booked'] == 1;
                                ?>
                                <div class="ms-slot-card <?= $isBooked ? 'booked' : '' ?>" data-slot-id="<?= $slot['slot_id'] ?>">
                                    <div class="ms-slot-date"><?= $slotDate->format('D, M j') ?></div>
                                    <div class="ms-slot-time"><?= $slotDate->format('g:i A') ?></div>
                                    <span class="ms-slot-status <?= $isBooked ? 'booked' : 'available' ?>">
                                        <?= $isBooked ? '✓ Booked' : 'Available' ?>
                                    </span>
                                    <?php if (!$isBooked): ?>
                                        <button class="ms-remove-slot-btn" onclick="removeSlot(<?= $slot['slot_id'] ?>)" aria-label="Remove this time slot"><span aria-hidden="true">&times;</span></button>
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="ms-empty-state" style="grid-column: 1 / -1;">
                                <div class="ms-empty-icon ms-empty-icon--css">--</div>
                                <h3>No Availability Set</h3>
                                <p>Add your available time slots so students can book sessions with you.</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Upcoming Bookings -->
                <div class="ms-card">
                    <h2 class="ms-card-title">Upcoming Bookings</h2>

                    <?php if (isset($data['upcoming_bookings']) && count($data['upcoming_bookings']) > 0): ?>
                        <?php foreach ($data['upcoming_bookings'] as $booking): ?>
                            <?php 
                                $sessionDate = new DateTime($booking['slot_datetime']); 
                                $now = new DateTime();
                                $diff = $now->diff($sessionDate);
                                $minutesUntil = ($sessionDate->getTimestamp() - $now->getTimestamp()) / 60;
                                $canJoin = $minutesUntil <= 15 && $minutesUntil > -120;
                                $isActive = $minutesUntil <= 0 && $minutesUntil > -120;
                            ?>
                            <div class="ms-booking-card" data-booking-id="<?= $booking['booking_id'] ?>" data-session-id="<?= $booking['booking_id'] ?>" data-session-datetime="<?= $sessionDate->format('Y-m-d\TH:i:s') ?>" data-meeting-link="<?= htmlspecialchars($booking['meeting_link'] ?? '') ?>">
                                <div class="ms-booking-header">
                                    <img src="<?= !empty($booking['student_picture']) ? BASE_URL . htmlspecialchars($booking['student_picture']) : BASE_URL . '/assets/images/default-avatar.svg' ?>"
                                        alt="<?= htmlspecialchars($booking['student_name'] ?? 'Student') ?>" 
                                        class="ms-booking-avatar"
                                        onerror="this.src='<?= BASE_URL ?>/assets/images/U.png'">
                                    <div class="ms-booking-info">
                                        <h4 class="ms-booking-name"><?= htmlspecialchars($booking['student_name'] ?? 'Student') ?></h4>
                                        <p class="ms-booking-subtitle">
                                            <?= htmlspecialchars($booking['degree_program'] ?? 'Student') ?>
                                        </p>
                                    </div>
                                    <span class="ms-badge <?= $isActive ? 'ms-badge-active' : ($canJoin ? 'ms-badge-soon' : 'ms-badge-upcoming') ?>" role="status">
                                        <?= $isActive ? '<span class="ms-status-dot ms-status-dot--live" aria-hidden="true"></span> LIVE' : ($canJoin ? 'Starting Soon' : 'Confirmed') ?>
                                    </span>
                                </div>
                                
                                <div class="ms-booking-datetime">
                                    <?= $sessionDate->format('l, F j, Y') ?> at <?= $sessionDate->format('g:i A') ?>
                                </div>
                                
                                <!-- Countdown -->
                                <div class="ms-countdown" aria-live="polite" role="timer">
                                    <?php if ($isActive): ?>
                                        <span class="ms-countdown--live"><span class="ms-status-dot ms-status-dot--active"></span> Session In Progress</span>
                                    <?php elseif ($canJoin): ?>
                                        <span class="ms-countdown--soon">Starting very soon!</span>
                                    <?php else: ?>
                                        <span class="ms-countdown--waiting">
                                            Starts in: <strong>
                                            <?php
                                                if ($diff->d > 0) echo $diff->d . 'd ' . $diff->h . 'h';
                                                elseif ($diff->h > 0) echo $diff->h . 'h ' . $diff->i . 'm';
                                                else echo $diff->i . ' minutes';
                                            ?>
                                            </strong>
                                        </span>
                                    <?php endif; ?>
                                </div>

                                <!-- Actions -->
                                <div class="ms-booking-actions">
                                    <?php if (!empty($booking['meeting_link'])): ?>
                                        <button class="ms-btn join-meeting-btn <?= $canJoin ? 'ms-btn-join-active' : 'ms-btn-join-disabled' ?>" 
                                                <?= !$canJoin ? 'disabled' : '' ?>
                                                onclick="<?= $canJoin ? "window.open('" . htmlspecialchars($booking['meeting_link']) . "', '_blank')" : '' ?>">
                                            <?= $canJoin ? 'Join Meeting' : 'Wait for Session' ?>
                                        </button>
                                    <?php else: ?>
                                        <span class="ms-btn ms-btn-secondary ms-info-strip--muted">
                                            Meeting link unavailable
                                        </span>
                                    <?php endif; ?>
                                    
                                    <button class="ms-btn ms-btn-secondary" onclick="openCancelModal(<?= $booking['booking_id'] ?>)">
                                        Cancel Booking
                                    </button>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="ms-empty-state">
                            <div class="ms-empty-icon ms-empty-icon--css">--</div>
                            <h3>No Upcoming Bookings</h3>
                            <p>When students book your available slots, they'll appear here.</p>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Completed Sessions -->
                <?php if (isset($data['completed_sessions']) && count($data['completed_sessions']) > 0): ?>
                    <div class="ms-card">
                        <h2 class="ms-card-title">Recent Completed Sessions</h2>
                        <?php foreach (array_slice($data['completed_sessions'], 0, 5) as $session): ?>
                            <?php $sessionDate = new DateTime($session['slot_datetime']); ?>
                            <div class="ms-completed-item">
                                <img src="<?= !empty($session['student_picture']) ? BASE_URL . htmlspecialchars($session['student_picture']) : BASE_URL . '/assets/images/default-avatar.svg' ?>"
                                    alt="Student" class="ms-completed-avatar"
                                    onerror="this.src='<?= BASE_URL ?>/assets/images/U.png'">
                                <div class="ms-completed-info">
                                    <div class="ms-completed-name"><?= htmlspecialchars($session['student_name'] ?? 'Student') ?></div>
                                    <div class="ms-completed-date"><?= $sessionDate->format('M j, Y') ?></div>
                                </div>
                                <?php if (!empty($session['rating'])): ?>
                                    <div class="ms-rating-stars">
                                        <?= str_repeat('&#9733;', $session['rating']) . str_repeat('&#9734;', 5 - $session['rating']) ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Right Column - Stats -->
            <div>
                <div class="ms-stats-card">
                    <h2 class="ms-card-title">Impact Stats</h2>

                    <div class="ms-stat-item">
                        <div class="ms-stat-label">Total Sessions</div>
                        <div class="ms-stat-value"><?= $data['stats']['total_sessions'] ?? 0 ?></div>
                    </div>

                    <div class="ms-stat-item">
                        <div class="ms-stat-label">Completed Sessions</div>
                        <div class="ms-stat-value"><?= $data['stats']['completed_sessions'] ?? 0 ?></div>
                    </div>

                    <div class="ms-stat-item">
                        <div class="ms-stat-label">Average Rating</div>
                        <div class="ms-stat-value">
                            <?= isset($data['stats']['average_rating']) ? number_format($data['stats']['average_rating'], 1) : '0.0' ?> / 5
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Slots Modal -->
    <div id="addSlotsModal" class="ms-modal">
        <div class="ms-modal-dialog">
            <div class="ms-modal-header">
                <h3>Add Availability Slots</h3>
                <button class="ms-close-modal" onclick="closeAddSlotsModal()" aria-label="Close add slots dialog"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="ms-modal-body">
                <div class="ms-info-text">
                    Add 1-hour time slots when you're available. Students can instantly book any slot (first-come-first-served).
                </div>

                <div id="slotsContainer">
                    <div class="ms-time-slot-input-group">
                        <label>Slot 1</label>
                        <input type="datetime-local" class="slot-input" 
                               min="<?= date('Y-m-d\TH:i') ?>" 
                               max="<?= date('Y-m-d\TH:i', strtotime('+14 days')) ?>">
                    </div>
                </div>

                <button type="button" class="ms-btn-add-slot" onclick="addSlotInput()">
                    + Add Another Slot
                </button>
            </div>
            <div class="ms-modal-footer">
                <button class="ms-btn ms-btn-secondary" onclick="closeAddSlotsModal()">Cancel</button>
                <button class="ms-btn ms-btn-primary" onclick="submitSlots()">Save Availability</button>
            </div>
        </div>
    </div>

    <!-- Cancel Modal -->
    <div id="cancelModal" class="ms-modal">
        <div class="ms-modal-dialog">
            <div class="ms-modal-header ms-modal-header--danger">
                <h3>Cancel Booking</h3>
                <button class="ms-close-modal" onclick="closeCancelModal()" aria-label="Close cancel booking dialog"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="ms-modal-body">
                <input type="hidden" id="cancelBookingId">
                <p>Are you sure you want to cancel this booking? The student will be notified.</p>
                <div style="margin-top: 1rem;">
                    <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Reason for cancellation (required):</label>
                    <textarea id="cancelReason" class="ms-textarea ms-textarea--danger" placeholder="Please provide a reason..." required></textarea>
                </div>
            </div>
            <div class="ms-modal-footer">
                <button class="ms-btn ms-btn-secondary" onclick="closeCancelModal()">Keep Booking</button>
                <button class="ms-btn ms-btn-danger" onclick="confirmCancel()">Cancel Booking</button>
            </div>
        </div>
    </div>

    <script>
        let slotCount = 1;

        function openAddSlotsModal() {
            document.getElementById('addSlotsModal').classList.add('show');
        }

        function closeAddSlotsModal() {
            document.getElementById('addSlotsModal').classList.remove('show');
            // Reset
            document.getElementById('slotsContainer').innerHTML = `
                <div class="ms-time-slot-input-group">
                    <label>Slot 1</label>
                    <input type="datetime-local" class="slot-input" 
                           min="${new Date().toISOString().slice(0, 16)}" 
                           max="${new Date(Date.now() + 14*24*60*60*1000).toISOString().slice(0, 16)}">
                </div>
            `;
            slotCount = 1;
        }

        function addSlotInput() {
            slotCount++;
            const container = document.getElementById('slotsContainer');
            const html = `
                <div class="ms-time-slot-input-group">
                    <label>Slot ${slotCount}</label>
                    <input type="datetime-local" class="slot-input" 
                           min="${new Date().toISOString().slice(0, 16)}" 
                           max="${new Date(Date.now() + 14*24*60*60*1000).toISOString().slice(0, 16)}">
                </div>
            `;
            container.insertAdjacentHTML('beforeend', html);
        }

        async function submitSlots() {
            const inputs = document.querySelectorAll('.slot-input');
            const slots = [];
            
            inputs.forEach(input => {
                if (input.value) {
                    slots.push(input.value);
                }
            });

            if (slots.length === 0) {
                MentorshipSystem.showNotification('Please add at least one time slot.', 'error');
                return;
            }

            // Show confirmation with count
            const confirmed = await MentorshipSystem.showConfirmationModal(
                'Add Availability',
                `Add ${slots.length} availability slot${slots.length > 1 ? 's' : ''}?`
            );
            if (!confirmed) return;

            fetch('<?= BASE_URL ?>/amentorships/addAvailability', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ slots: slots })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const added = data.added || slots.length;
                    const skipped = data.duplicates || 0;
                    let msg = 'slots_added&count=' + added;
                    if (skipped > 0) msg += '&skipped=' + skipped;
                    window.location.href = '<?= BASE_URL ?>/amentorships?success=' + msg;
                } else {
                    MentorshipSystem.showNotification('Error: ' + (data.message || 'Failed to add slots'), 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                MentorshipSystem.showNotification('An error occurred. Please try again.', 'error');
            });
        }

        async function removeSlot(slotId) {
            const confirmed = await MentorshipSystem.showConfirmationModal(
                'Remove Slot',
                'Remove this availability slot?',
                true
            );
            if (!confirmed) return;

            fetch('<?= BASE_URL ?>/amentorships/removeAvailability', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ slot_id: slotId })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    MentorshipSystem.showNotification('Error: ' + (data.message || 'Failed to remove slot'), 'error');
                }
            });
        }

        function openCancelModal(bookingId) {
            document.getElementById('cancelBookingId').value = bookingId;
            document.getElementById('cancelReason').value = '';
            document.getElementById('cancelModal').classList.add('show');
        }

        function closeCancelModal() {
            document.getElementById('cancelModal').classList.remove('show');
        }

        function confirmCancel() {
            const bookingId = document.getElementById('cancelBookingId').value;
            const reason = document.getElementById('cancelReason').value.trim();

            if (!reason) {
                MentorshipSystem.showNotification('Please provide a reason for cancellation.', 'error');
                return;
            }

            fetch('<?= BASE_URL ?>/amentorships/cancelBooking', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ booking_id: bookingId, reason: reason })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.location.href = '<?= BASE_URL ?>/amentorships?success=booking_cancelled';
                } else {
                    MentorshipSystem.showNotification('Error: ' + (data.message || 'Failed to cancel booking'), 'error');
                }
            });
        }

        // Close modals handled globally by mentorship.js (outside click + Escape key)
    </script>
    <script src="<?= ROOT ?>/js/mentorship.js"></script>
    </main>

    <?php include __DIR__ . '/../layout/footer.php'; ?>
</body>
</html>
