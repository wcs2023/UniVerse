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
    <title>My Mentorships - UniVerse</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/css/umentorship.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/css/styles.css">
    <link rel="icon" type="image/png" href="<?= BASE_URL ?>/assets/images/U.png">
   
</head>

<body>
    <?php

    $navFile = __DIR__ . '/../actors/undergraduate/unavigation.view.php';

    if (file_exists($navFile)) {
        include $navFile;
    }
    ?>

    <div class="container">
        <!-- Success Message -->
        <?php if (isset($_GET['success']) && $_GET['success'] === 'scheduled'): ?>
            <div class="success-message">
                <span>✅</span>
                <span>Your mentorship session has been successfully scheduled! Check your upcoming sessions below.</span>
            </div>
        <?php endif; ?>

        <!-- Page Header -->
        <div class="page-header">
            <h1 class="page-title">My Mentorships</h1>
            <a href="<?= BASE_URL ?>/umentorships/exploreMentors" class="btn-explore">
                <span>Explore Mentors</span>
            </a>
        </div>

        <!-- High Priority Alert Banner for Pending Time Selections -->
        <?php if (isset($data['mentorships']['awaiting_selection']) && count($data['mentorships']['awaiting_selection']) > 0): ?>
            <div class="alert-banner">
                <span class="alert-icon">🎉</span>
                <div class="alert-content">
                    <div class="alert-title">Action Required!</div>
                    <div class="alert-message">You have <?= count($data['mentorships']['awaiting_selection']) ?> mentorship request(s) with time slots waiting for your selection.</div>
                </div>
                <a href="#awaiting-section" class="btn-view">View Now</a>
            </div>
        <?php endif; ?>

        <!-- Awaiting Time Slot Selection Section -->
        <?php if (isset($data['mentorships']['awaiting_selection']) && count($data['mentorships']['awaiting_selection']) > 0): ?>
            <div class="section-card" id="awaiting-section">
                <h2 class="section-title">⏰ Select Your Session Time</h2>
                <p style="color: var(--text-light); margin-bottom: 1.5rem;">Your mentorship request has been accepted! Please select your preferred time slot below.</p>

                <?php foreach ($data['mentorships']['awaiting_selection'] as $request): ?>
                    <div class="awaiting-selection-card">
                        <div class="card-header">
                            <img src="<?= $request['profile_picture_url'] ?? 'https://i.pravatar.cc/150' ?>"
                                alt="<?= htmlspecialchars($request['first_name'] . ' ' . $request['last_name']) ?>"
                                class="mentor-avatar" style="width: 60px; height: 60px; border-radius: 50%;">
                            <div>
                                <h3 style="margin: 0;"><?= htmlspecialchars($request['first_name'] . ' ' . $request['last_name']) ?></h3>
                                <p style="margin: 0; color: #6b7280; font-size: 0.875rem;">
                                    <?= htmlspecialchars($request['title'] ?? '') ?>
                                    <?php if (!empty($request['company'])): ?>
                                        at <?= htmlspecialchars($request['company']) ?>
                                    <?php endif; ?>
                                </p>
                            </div>
                            <span class="priority-badge">Action Required</span>
                        </div>

                        <p style="font-weight: 500; color: #92400e;">Choose one of the time slots offered by your mentor:</p>

                        <div class="time-slot-options">
                            <?php if (isset($request['time_slots']) && count($request['time_slots']) > 0): ?>
                                <?php foreach ($request['time_slots'] as $slot): ?>
                                    <?php 
                                        $slotDate = new DateTime($slot['proposed_datetime']);
                                        $now = new DateTime();
                                        $isExpired = $slotDate < $now;
                                    ?>
                                    <div class="time-slot-option <?= $isExpired ? 'disabled' : '' ?>" 
                                         data-slot-id="<?= $slot['slot_id'] ?>"
                                         data-request-id="<?= $request['request_id'] ?>">
                                        <div class="slot-date">
                                            📅 <?= $slotDate->format('l, F j, Y') ?>
                                        </div>
                                        <div class="slot-time">
                                            🕐 <?= $slotDate->format('g:i A') ?> (<?= $slot['duration_minutes'] ?> min)
                                        </div>
                                        <?php if (!$isExpired): ?>
                                            <button class="confirm-btn" 
                                                    onclick="confirmSlot(<?= $request['request_id'] ?>, <?= $slot['slot_id'] ?>, this)">
                                                ✓ Confirm This Time
                                            </button>
                                        <?php else: ?>
                                            <button class="confirm-btn" disabled>Expired</button>
                                        <?php endif; ?>
                                    </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <p>No time slots available. Please contact your mentor.</p>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <!-- Finalized/Upcoming Sessions Section -->
        <?php if (isset($data['mentorships']['finalized_sessions']) && count($data['mentorships']['finalized_sessions']) > 0): ?>
            <div class="section-card">
                <h2 class="section-title">🔒 Confirmed Sessions</h2>

                <?php foreach ($data['mentorships']['finalized_sessions'] as $session): ?>
                    <?php $sessionDate = new DateTime($session['session_datetime']); ?>
                    <div class="finalized-session">
                        <div class="session-header">
                            <img src="<?= $session['mentor_picture'] ?? 'https://i.pravatar.cc/150' ?>"
                                alt="<?= htmlspecialchars($session['mentor_name']) ?>"
                                class="mentor-avatar" style="width: 60px; height: 60px; border-radius: 50%;">
                            <div style="flex: 1;">
                                <h3 style="margin: 0;"><?= htmlspecialchars($session['mentor_name']) ?></h3>
                                <p style="margin: 0; color: #065f46; font-size: 0.875rem;">
                                    <?= htmlspecialchars($session['mentor_title'] ?? '') ?>
                                    <?php if (!empty($session['mentor_company'])): ?>
                                        at <?= htmlspecialchars($session['mentor_company']) ?>
                                    <?php endif; ?>
                                </p>
                            </div>
                            <span class="locked-badge">🔒 Confirmed</span>
                        </div>
                        <div class="session-datetime">
                            📅 <?= $sessionDate->format('l, F j, Y') ?> at <?= $sessionDate->format('g:i A') ?>
                        </div>
                        <?php if (!empty($session['meeting_link'])): ?>
                            <a href="<?= htmlspecialchars($session['meeting_link']) ?>" target="_blank" 
                               class="btn-view-times" style="margin-top: 1rem; display: inline-block; text-decoration: none;">
                                🎥 Join Meeting
                            </a>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <!-- Pending Requests Section -->
        <div class="section-card">
            <h2 class="section-title">Mentorship Requests</h2>

            <?php 
            // Filter out requests that are awaiting selection (shown above)
            $pendingRequests = array_filter($data['mentorships']['pending'] ?? [], function($req) {
                return $req['status'] === 'pending';
            });
            ?>

            <?php if (count($pendingRequests) > 0): ?>
                <?php foreach ($pendingRequests as $request): ?>
                    <div class="mentorship-item">
                        <div class="mentor-info">
                            <img src="<?= $request['profile_picture_url'] ?? 'https://i.pravatar.cc/150' ?>"
                                alt="<?= htmlspecialchars($request['first_name'] . ' ' . $request['last_name']) ?>"
                                class="mentor-avatar">
                            <div class="mentor-details">
                                <h3><?= htmlspecialchars($request['first_name'] . ' ' . $request['last_name']) ?></h3>
                                <span class="status-badge status-<?= strtolower($request['status']) ?>">
                                    <?= ucfirst(str_replace('_', ' ', $request['status'])) ?>
                                </span>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="empty-state">
                    <div class="empty-icon">📥</div>
                    <h3>No Pending Requests</h3>
                    <p>You don't have any pending mentorship requests at the moment.</p>
                </div>
            <?php endif; ?>
        </div>

        <!-- Upcoming Sessions Section (Legacy - from old system) -->
        <?php if (isset($data['mentorships']['upcoming']) && count($data['mentorships']['upcoming']) > 0): ?>
            <div class="section-card">
                <h2 class="section-title">Upcoming Sessions</h2>

                <?php foreach ($data['mentorships']['upcoming'] as $session): ?>
                    <div class="session-item">
                        <div class="mentor-info">
                            <img src="<?= $session['profile_picture_url'] ?? 'https://i.pravatar.cc/150' ?>"
                                alt="<?= htmlspecialchars($session['first_name'] . ' ' . $session['last_name']) ?>"
                                class="mentor-avatar">
                            <div class="session-info">
                                <h3><?= htmlspecialchars($session['first_name'] . ' ' . $session['last_name']) ?></h3>
                                <div class="session-date">
                                    <?php if (isset($session['scheduled_date'])): ?>
                                        <span>📅</span>
                                        <?= date('l, F j, Y \a\t g:i A', strtotime($session['scheduled_date'])) ?>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>

                        <?php if (isset($session['meeting_link'])): ?>
                            <a href="<?= htmlspecialchars($session['meeting_link']) ?>" target="_blank" class="btn-view-times">
                                <span>🎥 Join Meeting</span>
                            </a>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <!-- Call to Action Section -->
        <div class="cta-section">
            <h2>Ready to Find Your Guide?</h2>
            <p>Explore our network of experienced mentors who can help you achieve your academic and career goals.</p>
            <a href="<?= BASE_URL ?>/umentorships/exploreMentors" class="btn-view-times" style="text-decoration: none;">
                <span>🧭 Explore Mentors</span>
            </a>
        </div>
    </div>

    <!-- Confirmation Modal -->
    <div class="modal" id="confirmModal">
        <div class="modal-dialog">
            <div class="modal-header" style="background: linear-gradient(135deg, #7c3aed 0%, #a855f7 100%); color: white; border-radius: 12px 12px 0 0;">
                <h5 class="modal-title">Confirm Session Time</h5>
                <button type="button" class="close-modal" onclick="closeConfirmModal()" style="color: white;">×</button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to confirm this time slot?</p>
                <div id="selectedSlotInfo" style="background: #f5f3ff; padding: 1rem; border-radius: 8px; margin: 1rem 0;">
                    <!-- Slot info will be inserted here -->
                </div>
                <p style="color: #6b7280; font-size: 0.875rem;">
                    ⚠️ Once confirmed, this session will be locked and both you and your mentor will receive a confirmation notification.
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeConfirmModal()">Cancel</button>
                <button type="button" class="btn btn-primary" id="finalConfirmBtn" onclick="finalizeConfirmation()">
                    ✓ Confirm Session
                </button>
            </div>
        </div>
    </div>

    <script>
        let pendingRequestId = null;
        let pendingSlotId = null;
        let pendingButton = null;

        // Function to confirm a time slot
        function confirmSlot(requestId, slotId, button) {
            pendingRequestId = requestId;
            pendingSlotId = slotId;
            pendingButton = button;

            // Get slot info from the clicked card
            const card = button.closest('.time-slot-option');
            const dateText = card.querySelector('.slot-date').textContent;
            const timeText = card.querySelector('.slot-time').textContent;

            // Update modal with slot info
            document.getElementById('selectedSlotInfo').innerHTML = `
                <strong>${dateText}</strong><br>
                <span style="color: #6b7280;">${timeText}</span>
            `;

            // Show confirmation modal
            document.getElementById('confirmModal').classList.add('show');
        }

        // Close confirmation modal
        function closeConfirmModal() {
            document.getElementById('confirmModal').classList.remove('show');
            pendingRequestId = null;
            pendingSlotId = null;
            pendingButton = null;
        }

        // Finalize the confirmation
        function finalizeConfirmation() {
            if (!pendingRequestId || !pendingSlotId) {
                alert('Error: Missing information. Please try again.');
                return;
            }

            const confirmBtn = document.getElementById('finalConfirmBtn');
            confirmBtn.disabled = true;
            confirmBtn.textContent = 'Confirming...';

            // Disable the original button too
            if (pendingButton) {
                pendingButton.disabled = true;
                pendingButton.textContent = 'Confirming...';
            }

            // Send request to confirm the slot
            fetch('<?= BASE_URL ?>/umentorships/confirmTimeSlot', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    request_id: pendingRequestId,
                    slot_id: pendingSlotId
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Success! Redirect with success message
                    window.location.href = '<?= BASE_URL ?>/umentorships?success=scheduled';
                } else {
                    // Check if it's a double-booking error
                    const isDoubleBooking = data.message && (
                        data.message.includes('just booked') || 
                        data.message.includes('another student') ||
                        data.message.includes('no longer available')
                    );
                    
                    if (isDoubleBooking) {
                        // Show a more user-friendly double-booking message
                        showDoubleBookingError(data.message);
                    } else {
                        alert('Error: ' + (data.message || 'Failed to confirm session. Please try again.'));
                    }
                    
                    confirmBtn.disabled = false;
                    confirmBtn.textContent = '✓ Confirm Session';
                    if (pendingButton) {
                        pendingButton.disabled = false;
                        pendingButton.textContent = '✓ Confirm This Time';
                    }
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred. Please try again.');
                confirmBtn.disabled = false;
                confirmBtn.textContent = '✓ Confirm Session';
                if (pendingButton) {
                    pendingButton.disabled = false;
                    pendingButton.textContent = '✓ Confirm This Time';
                }
            });
        }

        // Show double-booking error with option to refresh
        function showDoubleBookingError(message) {
            closeConfirmModal();
            
            // Create error modal
            const errorModal = document.createElement('div');
            errorModal.className = 'modal show';
            errorModal.id = 'doubleBookingModal';
            errorModal.innerHTML = `
                <div class="modal-dialog">
                    <div class="modal-header" style="background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%); color: white; border-radius: 12px 12px 0 0;">
                        <h5 class="modal-title">⚠️ Time Slot No Longer Available</h5>
                        <button type="button" class="close-modal" onclick="closeDoubleBookingModal()" style="color: white;">×</button>
                    </div>
                    <div class="modal-body" style="text-align: center; padding: 2rem;">
                        <div style="font-size: 3rem; margin-bottom: 1rem;">😔</div>
                        <p style="font-size: 1.1rem; color: #374151; margin-bottom: 1rem;">
                            ${message}
                        </p>
                        <p style="color: #6b7280; font-size: 0.875rem;">
                            Another student confirmed this time slot just before you did. 
                            Please select a different available time or contact your mentor for new options.
                        </p>
                    </div>
                    <div class="modal-footer" style="justify-content: center;">
                        <button type="button" class="btn btn-primary" onclick="refreshPage()">
                            🔄 Refresh & See Available Slots
                        </button>
                    </div>
                </div>
            `;
            document.body.appendChild(errorModal);
            
            // Mark the conflicting slot as unavailable visually
            if (pendingButton) {
                const card = pendingButton.closest('.time-slot-option');
                if (card) {
                    card.style.opacity = '0.5';
                    card.style.pointerEvents = 'none';
                    pendingButton.textContent = 'Already Booked';
                    pendingButton.disabled = true;
                    pendingButton.style.background = '#9ca3af';
                }
            }
        }

        function closeDoubleBookingModal() {
            const modal = document.getElementById('doubleBookingModal');
            if (modal) {
                modal.remove();
            }
        }

        function refreshPage() {
            window.location.reload();
        }

        // Close modal when clicking outside
        document.getElementById('confirmModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeConfirmModal();
            }
        });

        // Smooth scroll to awaiting section if hash is present
        if (window.location.hash === '#awaiting-section') {
            setTimeout(() => {
                document.getElementById('awaiting-section')?.scrollIntoView({ behavior: 'smooth' });
            }, 100);
        }

        // Add fade-in animation on page load
        document.addEventListener('DOMContentLoaded', function() {
            const sections = document.querySelectorAll('.section-card, .cta-section, .alert-banner');
            sections.forEach((section, index) => {
                section.style.opacity = '0';
                section.style.transform = 'translateY(20px)';
                section.style.transition = 'opacity 0.5s ease, transform 0.5s ease';

                setTimeout(() => {
                    section.style.opacity = '1';
                    section.style.transform = 'translateY(0)';
                }, index * 100);
            });
        });
    </script>
    <?php include __DIR__ . '/../layout/footer.php'; ?>
</body>

</html>
