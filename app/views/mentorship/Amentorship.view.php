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
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/alumni.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/mentorship.css">
    <link rel="icon" type="image/png" href="<?= BASE_URL ?>/assets/images/U.png">
    <script>
        window.BASE_URL = '<?= BASE_URL ?>';
        window.USER_TYPE = 'alumni';
        window.MENTOR_ACTIVE = <?= empty($data['mentor_inactive']) ? 'true' : 'false' ?>;
    </script>
    <style>
        body { padding-top: 80px; background-color: #a78bfa45 !important; }
        .visually-hidden {
            position: absolute; width: 1px; height: 1px; padding: 0; margin: -1px;
            overflow: hidden; clip: rect(0,0,0,0); white-space: nowrap; border: 0;
        }
        .slot-row { display: flex; gap: 8px; align-items: center; }
        .slot-row .slot-input { flex: 1; }
        .slot-done-btn {
            padding: 6px 14px;
            background: #7c3aed;
            color: #fff;
            border: none;
            border-radius: 7px;
            font-weight: 600;
            font-size: 0.85rem;
            cursor: pointer;
            white-space: nowrap;
            transition: background 0.2s;
        }
        .slot-done-btn.done { background: #10b981; }
        .slot-done-btn:hover { opacity: 0.88; }
        .slot-time-label { font-size: 0.82rem; color: #059669; margin-top: 4px; }
        .ms-btn-add-availability:disabled {
            opacity: 0.55;
            cursor: not-allowed;
            pointer-events: none;
        }
    </style>
</head>

<body>
    <?php $canManageSlots = empty($data['mentor_inactive']); ?>
    <?php
    // Include navigation
    $navFile = APPROOT . '/views/actors/alumni/Anavbar.php';
    if (file_exists($navFile)) {
        include $navFile;
    }
    ?>

    <main id="main-content" role="main">
    <div class="ms-container">
        <!-- Mentor Inactive Warning -->
        <?php if (!empty($data['mentor_inactive'])): ?>
            <div style="background: #fef3c7; color: #92400e; border: 2px solid #fbbf24; padding: 2rem; border-radius: 12px; margin-bottom: 1.5rem; text-align: center;">
                <h2 style="margin: 0 0 0.75rem 0; font-size: 1.3rem;">Mentorship Not Enabled</h2>
                <p style="margin: 0 0 1rem 0;">You need to enable mentorship availability in your profile settings to manage slots and receive bookings.</p>
                <a href="<?= BASE_URL ?>/aeditprofile#mentorship-settings" style="display: inline-block; background: #6c63ff; color: white; padding: 0.6rem 1.5rem; border-radius: 8px; text-decoration: none; font-weight: 600;">Enable in Profile Settings</a>
            </div>
        <?php endif; ?>

        <!-- Success Message -->
        <?php if (isset($_GET['success']) && $_GET['success'] !== 'slots_added'): ?>
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
                        <button
                            class="ms-btn-add-availability"
                            onclick="<?= $canManageSlots ? 'openAddSlotsModal()' : '' ?>"
                            aria-label="Add new availability slots"
                            <?= $canManageSlots ? '' : 'disabled title="Enable mentorship in profile settings to add slots"' ?>>
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
                                <div class="ms-empty-icon" aria-hidden="true">
                                    <svg width="56" height="56" viewBox="0 0 24 24" fill="none" stroke="#a78bfa" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                        <rect x="3" y="4" width="18" height="18" rx="2" />
                                        <line x1="16" y1="2" x2="16" y2="6" />
                                        <line x1="8" y1="2" x2="8" y2="6" />
                                        <line x1="3" y1="10" x2="21" y2="10" />
                                        <line x1="8" y1="14" x2="8" y2="18" />
                                        <line x1="6" y1="16" x2="10" y2="16" />
                                    </svg>
                                </div>
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
                                    <img src="<?= !empty($booking['student_picture']) ? BASE_URL . htmlspecialchars($booking['student_picture']) : BASE_URL . '/assets/images/U.png' ?>"
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
                            <div class="ms-empty-icon" aria-hidden="true">
                                <svg width="56" height="56" viewBox="0 0 24 24" fill="none" stroke="#a78bfa" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                    <rect x="3" y="4" width="18" height="18" rx="2" />
                                    <line x1="16" y1="2" x2="16" y2="6" />
                                    <line x1="8" y1="2" x2="8" y2="6" />
                                    <line x1="3" y1="10" x2="21" y2="10" />
                                    <circle cx="12" cy="16" r="2" fill="#a78bfa" stroke="none" />
                                </svg>
                            </div>
                            <h3>No Upcoming Bookings</h3>
                            <p>When students book your available slots, they'll appear here.</p>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Cancelled Sessions -->
                <?php if (!empty($data['cancelled_bookings'])): ?>
                    <div class="ms-card" style="border: 2px solid #fca5a5;">
                        <h2 class="ms-card-title" style="color: #dc2626;">Cancelled Sessions</h2>
                        <?php foreach ($data['cancelled_bookings'] as $booking): ?>
                            <?php $sessionDate = new DateTime($booking['slot_datetime']); ?>
                            <?php $cancelledAt = !empty($booking['cancelled_at']) ? new DateTime($booking['cancelled_at']) : null; ?>
                            <div class="ms-completed-item" style="border-left: 4px solid #ef4444; padding-left: 1rem; margin-bottom: 1rem; background: #fff5f5; border-radius: 8px; padding: 1rem;">
                                <img src="<?= !empty($booking['student_picture']) ? BASE_URL . htmlspecialchars($booking['student_picture']) : BASE_URL . '/assets/images/U.png' ?>"
                                    alt="Student" class="ms-completed-avatar"
                                    onerror="this.src='<?= BASE_URL ?>/assets/images/U.png'">
                                <div class="ms-completed-info" style="flex: 1;">
                                    <div class="ms-completed-name"><?= htmlspecialchars($booking['student_name'] ?? 'Student') ?></div>
                                    <div class="ms-completed-date"><?= $sessionDate->format('M j, Y') ?> at <?= $sessionDate->format('g:i A') ?></div>
                                    <?php if (!empty($booking['cancelled_by_name'])): ?>
                                        <div style="margin-top: 0.35rem; font-size: 0.85rem; color: #7f1d1d;">
                                            <strong>Cancelled by:</strong> <?= htmlspecialchars($booking['cancelled_by_name']) ?>
                                            <?php if ($cancelledAt): ?>
                                                on <?= $cancelledAt->format('M j, Y \a\t g:i A') ?>
                                            <?php endif; ?>
                                        </div>
                                    <?php endif; ?>
                                    <?php if (!empty($booking['cancellation_reason'])): ?>
                                        <div style="margin-top: 0.5rem; font-size: 0.875rem; color: #b91c1c; background: #fee2e2; padding: 0.4rem 0.75rem; border-radius: 6px; display: inline-block;">
                                            <strong>Reason:</strong> <?= htmlspecialchars($booking['cancellation_reason']) ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <span style="font-size: 0.75rem; font-weight: 700; background: #fee2e2; color: #dc2626; padding: 0.25rem 0.75rem; border-radius: 20px; white-space: nowrap;">Cancelled</span>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <!-- Completed Sessions -->
                <?php if (isset($data['completed_sessions']) && count($data['completed_sessions']) > 0): ?>
                    <div class="ms-card">
                        <h2 class="ms-card-title">Recent Completed Sessions</h2>
                        <?php foreach (array_slice($data['completed_sessions'], 0, 5) as $session): ?>
                            <?php $sessionDate = new DateTime($session['slot_datetime']); ?>
                            <div class="ms-completed-item">
                                <img src="<?= !empty($session['student_picture']) ? BASE_URL . htmlspecialchars($session['student_picture']) : BASE_URL . '/assets/images/U.png' ?>"
                                    alt="Student" class="ms-completed-avatar"
                                    onerror="this.src='<?= BASE_URL ?>/assets/images/U.png'">
                                <div class="ms-completed-info">
                                    <div class="ms-completed-name"><?= htmlspecialchars($session['student_name'] ?? 'Student') ?></div>
                                    <div class="ms-completed-date"><?= $sessionDate->format('M j, Y') ?></div>
                                </div>
                                <?php if (!empty($session['review_text'])): ?>
                                <div class="completed-review" style="margin-top: 6px; color: #6b7280;">
                                    "<?= htmlspecialchars($session['review_text']) ?>"
                                </div>
                                <?php endif; ?>
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
                        <div class="slot-row">
                            <input type="datetime-local" class="slot-input"
                                   min="<?= date('Y-m-d\TH:i') ?>"
                                   max="<?= date('Y-m-d\TH:i', strtotime('+14 days')) ?>">
                            <button type="button" class="slot-done-btn" onclick="doneSlot(this)">Done</button>
                        </div>
                        <div class="slot-time-label"></div>
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
            if (!window.MENTOR_ACTIVE) {
                MentorshipSystem.showNotification('Enable mentorship in your profile settings before adding slots.', 'error');
                return;
            }
            document.getElementById('addSlotsModal').classList.add('show');
        }

        function closeAddSlotsModal() {
            document.getElementById('addSlotsModal').classList.remove('show');
            // Reset
            document.getElementById('slotsContainer').innerHTML = `
                <div class="ms-time-slot-input-group">
                    <label>Slot 1</label>
                    <div class="slot-row">
                        <input type="datetime-local" class="slot-input"
                               min="${new Date().toISOString().slice(0, 16)}"
                               max="${new Date(Date.now() + 14*24*60*60*1000).toISOString().slice(0, 16)}">
                        <button type="button" class="slot-done-btn" onclick="doneSlot(this)">Done</button>
                    </div>
                    <div class="slot-time-label"></div>
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
                    <div class="slot-row">
                        <input type="datetime-local" class="slot-input"
                               min="${new Date().toISOString().slice(0, 16)}"
                               max="${new Date(Date.now() + 14*24*60*60*1000).toISOString().slice(0, 16)}">
                        <button type="button" class="slot-done-btn" onclick="doneSlot(this)">Done</button>
                    </div>
                    <div class="slot-time-label"></div>
                </div>
            `;
            container.insertAdjacentHTML('beforeend', html);
        }

        function doneSlot(btn) {
            const group = btn.closest('.ms-time-slot-input-group');
            const input = group.querySelector('.slot-input');
            const label = group.querySelector('.slot-time-label');
            input.blur(); // close the calendar popup
            if (input.value) {
                const d = new Date(input.value);
                label.textContent = '✅ ' + d.toLocaleString('en-US', { weekday:'short', month:'short', day:'numeric', hour:'numeric', minute:'2-digit', hour12:true });
                btn.textContent = '✓ Done';
                btn.classList.add('done');
            } else {
                label.textContent = '';
                MentorshipSystem.showNotification('Please select a date and time first.', 'error');
            }
        }

        async function submitSlots() {
            if (!window.MENTOR_ACTIVE) {
                MentorshipSystem.showNotification('Mentorship is not enabled for your profile.', 'error');
                return;
            }

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
            if (!window.MENTOR_ACTIVE) {
                MentorshipSystem.showNotification('Mentorship is not enabled for your profile.', 'error');
                return;
            }

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
            })
            .catch(error => {
                console.error('Error:', error);
                MentorshipSystem.showNotification('An error occurred. Please try again.', 'error');
            });
        }

        // Close modals handled globally by mentorship.js (outside click + Escape key)
    </script>
    <script src="<?= BASE_URL ?>/assets/js/mentorship/core.js"></script>
    <script src="<?= BASE_URL ?>/assets/js/mentorship/actions.js"></script>
    <script src="<?= BASE_URL ?>/assets/js/mentorship/ui.js"></script>
    <script src="<?= BASE_URL ?>/assets/js/mentorship/styles-init.js"></script>
    </main>

    <?php include __DIR__ . '/../layout/footer.php'; ?>
</body>
</html>
