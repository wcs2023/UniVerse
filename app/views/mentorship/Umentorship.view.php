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
    <script>
        window.BASE_URL = '<?= BASE_URL ?>';
        window.USER_TYPE = 'undergraduate';
    </script>
</head>

<body>
    <?php
    $navFile = __DIR__ . '/../actors/undergraduate/unavigation.view.php';

    if (file_exists($navFile)) {
        include $navFile;
    }
    ?>

    <main id="main-content" role="main">
    <!-- Hero Banner -->
    <div class="mentorship-hero-banner">
        <div class="hero-content">
            <h1 class="hero-title">My Mentorship Journey</h1>
            <!-- Quick Stats -->
            <div class="hero-stats">
                <div class="stat-item">
                    <span class="stat-number"><?= count($data['upcoming_bookings'] ?? []) ?></span>
                    <span class="stat-label">Upcoming</span>
                </div>
                <div class="stat-item">
                    <span class="stat-number"><?= count($data['completed_sessions'] ?? []) ?></span>
                    <span class="stat-label">Completed</span>
                </div>
                <div class="stat-item">
                    <span class="stat-number">24/7</span>
                    <span class="stat-label">Support</span>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <!-- Success Message -->
        <?php if (isset($_GET['success'])): ?>
            <div class="success-message" id="successMessage">
                <span class="status-dot status-dot--active"></span>
                <span>
                    <?php 
                    switch($_GET['success']) {
                        case 'booked':
                            echo 'Session booked successfully! Check your upcoming sessions below.';
                            break;
                        case 'cancelled':
                            echo 'Booking has been cancelled.';
                            break;
                        case 'feedback':
                            echo 'Thank you for your feedback!';
                            break;
                        default:
                            echo 'Action completed successfully.';
                    }
                    ?>
                </span>
            </div>
        <?php endif; ?>

        <!-- Page Header -->
        <div class="page-header">
            <h1 class="page-title">My Mentorships</h1>
            <a href="<?= BASE_URL ?>/umentorships/exploreMentors" class="btn-explore">
                Explore Mentors
            </a>
        </div>

        <!-- Upcoming Bookings Section -->
        <div class="section-card">
            <h2 class="section-title">Upcoming Sessions</h2>

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
                    <div class="booking-card" data-booking-id="<?= $booking['booking_id'] ?>" data-session-id="<?= $booking['booking_id'] ?>" data-session-datetime="<?= $sessionDate->format('Y-m-d\TH:i:s') ?>" data-meeting-link="<?= htmlspecialchars($booking['meeting_link'] ?? '') ?>">
                        <div class="booking-header">
                            <img src="<?= !empty($booking['mentor_picture']) ? BASE_URL . htmlspecialchars($booking['mentor_picture']) : BASE_URL . '/assets/images/default-avatar.svg' ?>"
                                alt="<?= htmlspecialchars($booking['mentor_name'] ?? 'Mentor') ?>" 
                                class="booking-avatar"
                                onerror="this.onerror=null; this.src='<?= BASE_URL ?>/assets/images/default-avatar.svg'">
                            <div class="booking-info">
                                <h4 class="booking-name"><?= htmlspecialchars($booking['mentor_name'] ?? 'Mentor') ?></h4>
                                <p class="booking-subtitle">
                                    <?= htmlspecialchars($booking['mentor_title'] ?? '') ?>
                                    <?php if (!empty($booking['mentor_company'])): ?>
                                        at <?= htmlspecialchars($booking['mentor_company']) ?>
                                    <?php endif; ?>
                                </p>
                            </div>
                            <span class="session-status-badge <?= $isActive ? 'badge-active' : ($canJoin ? 'badge-soon' : 'badge-upcoming') ?>" role="status">
                                <?= $isActive ? '<span class="status-dot status-dot--live" aria-hidden="true"></span> LIVE' : ($canJoin ? 'Starting Soon' : 'Confirmed') ?>
                            </span>
                        </div>
                        
                        <div class="booking-datetime">
                            <span aria-hidden="true"></span> <?= $sessionDate->format('l, F j, Y') ?> at <?= $sessionDate->format('g:i A') ?>
                        </div>
                        
                        <!-- Countdown -->
                        <div class="countdown" aria-live="polite" role="timer">
                            <?php if ($isActive): ?>
                                <span class="countdown--live"><span class="status-dot status-dot--active"></span> Session In Progress - Join Now!</span>
                            <?php elseif ($canJoin): ?>
                                <span class="countdown--soon">Session starting very soon!</span>
                            <?php else: ?>
                                <span class="countdown--waiting">
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
                        <div class="booking-actions">
                            <?php if (!empty($booking['meeting_link'])): ?>
                                <button class="btn join-meeting-btn <?= $canJoin ? 'btn-join-active' : 'btn-join-disabled' ?>" 
                                        <?= !$canJoin ? 'disabled' : '' ?>
                                        onclick="<?= $canJoin ? "window.open('" . htmlspecialchars($booking['meeting_link']) . "', '_blank')" : '' ?>">
                                    <?= $canJoin ? 'Join Meeting Now' : 'Wait for Session Time' ?>
                                </button>
                            <?php else: ?>
                                <span class="btn btn-secondary info-strip--muted">
                                    Meeting link unavailable
                                </span>
                            <?php endif; ?>
                            
                            <button class="btn btn-secondary" onclick="openCancelModal(<?= $booking['booking_id'] ?>)">
                                Cancel Session
                            </button>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="empty-state">
                    <div class="empty-icon">--</div>
                    <h3>No Upcoming Sessions</h3>
                    <p>You don't have any booked mentorship sessions yet. Explore available mentors and book a session!</p>
                    <a href="<?= BASE_URL ?>/umentorships/exploreMentors" class="btn btn-primary" style="margin-top: 1rem; display: inline-block; text-decoration: none;">
                        Find a Mentor
                    </a>
                </div>
            <?php endif; ?>
        </div>

        <!-- Sessions Needing Feedback -->
        <?php if (isset($data['needs_feedback']) && count($data['needs_feedback']) > 0): ?>
            <div class="section-card" style="border: 2px solid #f59e0b;">
                <h2 class="section-title">Leave Feedback</h2>
                <p style="color: #6b7280; margin-bottom: 1rem;">Help other students by sharing your experience!</p>

                <?php foreach ($data['needs_feedback'] as $session): ?>
                    <?php $sessionDate = new DateTime($session['slot_datetime']); ?>
                    <div class="feedback-card">
                            <div class="feedback-layout">
                            <img src="<?= !empty($session['mentor_picture']) ? BASE_URL . htmlspecialchars($session['mentor_picture']) : BASE_URL . '/assets/images/default-avatar.svg' ?>"
                                alt="<?= htmlspecialchars($session['mentor_name'] ?? 'Mentor') ?>"
                                class="feedback-avatar"
                                onerror="this.onerror=null; this.src='<?= BASE_URL ?>/assets/images/default-avatar.svg'">
                            <div class="feedback-info">
                                <h4><?= htmlspecialchars($session['mentor_name'] ?? 'Mentor') ?></h4>
                                <p>
                                    <?= $sessionDate->format('F j, Y') ?>
                                </p>
                            </div>
                            <button class="btn btn-primary" onclick="openFeedbackModal(<?= $session['booking_id'] ?>, '<?= htmlspecialchars(addslashes($session['mentor_name'] ?? 'Mentor')) ?>')">
                                Rate Session
                            </button>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <!-- Completed Sessions -->
        <?php 
        $reviewedSessions = array_filter($data['completed_sessions'] ?? [], function($s) {
            return !empty($s['rating']);
        });
        ?>
        <?php if (count($reviewedSessions) > 0): ?>
            <div class="section-card">
                <h2 class="section-title">Past Sessions</h2>

                <?php foreach (array_slice($reviewedSessions, 0, 5) as $session): ?>
                    <?php $sessionDate = new DateTime($session['slot_datetime']); ?>
                    <div class="completed-item">
                        <img src="<?= !empty($session['mentor_picture']) ? BASE_URL . htmlspecialchars($session['mentor_picture']) : BASE_URL . '/assets/images/default-avatar.svg' ?>"
                            alt="Mentor" class="completed-avatar"
                            onerror="this.onerror=null; this.src='<?= BASE_URL ?>/assets/images/default-avatar.svg'">
                        <div class="completed-info">
                            <div class="completed-name"><?= htmlspecialchars($session['mentor_name'] ?? 'Mentor') ?></div>
                            <div class="completed-date"><?= $sessionDate->format('M j, Y') ?></div>
                        </div>
                        <div class="rating-stars">
                            <?= str_repeat('&#9733;', $session['rating']) . str_repeat('&#9734;', 5 - $session['rating']) ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <!-- CTA Section -->
        <div class="cta-section">
            <h2>Ready to Find Your Guide?</h2>
            <p>Explore our network of experienced mentors who can help you achieve your academic and career goals.</p>
            <a href="<?= BASE_URL ?>/umentorships/exploreMentors" class="btn" style="text-decoration: none;">
                Explore Mentors
            </a>
        </div>
    </div>
    </main>

    <!-- Cancel Modal -->
    <div id="cancelModal" class="modal">
        <div class="modal-dialog">
            <div class="modal-header modal-header--danger">
                <h3 style="margin: 0;">Cancel Session</h3>
                <button class="close-modal" onclick="closeCancelModal()" aria-label="Close cancel session dialog"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="cancelBookingId">
                <p>Are you sure you want to cancel this session? The mentor will be notified.</p>
                <div style="margin-top: 1rem;">
                    <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Reason for cancellation (required):</label>
                    <textarea id="cancelReason" class="cancel-reason-input" placeholder="Please provide a reason..." required></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" onclick="closeCancelModal()">Keep Session</button>
                <button class="btn btn-danger" onclick="confirmCancel()">Cancel Session</button>
            </div>
        </div>
    </div>

    <!-- Feedback Modal -->
    <div id="feedbackModal" class="modal">
        <div class="modal-dialog">
            <div class="modal-header modal-header--warning">
                <h3 style="margin: 0;">Rate Your Session</h3>
                <button class="close-modal" onclick="closeFeedbackModal()" aria-label="Close feedback dialog"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="feedbackBookingId">
                
                <p style="text-align: center;">How was your session with <strong id="feedbackMentorName"></strong>?</p>
                
                <!-- Star Rating -->
                <div class="star-rating" role="group" aria-label="Rate your session from 1 to 5 stars">
                    <button type="button" class="star-btn" onclick="selectRating(1)" aria-label="Rate 1 star">☆</button>
                    <button type="button" class="star-btn" onclick="selectRating(2)" aria-label="Rate 2 stars">☆</button>
                    <button type="button" class="star-btn" onclick="selectRating(3)" aria-label="Rate 3 stars">☆</button>
                    <button type="button" class="star-btn" onclick="selectRating(4)" aria-label="Rate 4 stars">☆</button>
                    <button type="button" class="star-btn" onclick="selectRating(5)" aria-label="Rate 5 stars">☆</button>
                </div>
                <input type="hidden" id="feedbackRating" value="0">
                
                <!-- Written Review -->
                <div style="margin-top: 1rem;">
                    <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Your Review (Optional)</label>
                    <textarea id="feedbackText" class="feedback-textarea" placeholder="Share your experience..." maxlength="500"></textarea>
                    <p style="text-align: right; color: #9ca3af; font-size: 0.75rem; margin-top: 0.25rem;">
                        <span id="charCount">0</span>/500
                    </p>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" onclick="closeFeedbackModal()">Cancel</button>
                <button class="btn btn-warning" onclick="submitFeedback()">
                    Submit Feedback
                </button>
            </div>
        </div>
    </div>

    <script>
        let currentRating = 0;

        // Cancel Modal Functions
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

            fetch('<?= BASE_URL ?>/umentorships/cancelBooking', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ booking_id: bookingId, reason: reason })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.location.href = '<?= BASE_URL ?>/umentorships?success=cancelled';
                } else {
                    MentorshipSystem.showNotification('Error: ' + (data.message || 'Failed to cancel session'), 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                MentorshipSystem.showNotification('An error occurred. Please try again.', 'error');
            });
        }

        // Feedback Modal Functions
        function openFeedbackModal(bookingId, mentorName) {
            document.getElementById('feedbackBookingId').value = bookingId;
            document.getElementById('feedbackMentorName').textContent = mentorName;
            document.getElementById('feedbackRating').value = '0';
            document.getElementById('feedbackText').value = '';
            document.getElementById('charCount').textContent = '0';
            currentRating = 0;
            updateStars();
            document.getElementById('feedbackModal').classList.add('show');
        }

        function closeFeedbackModal() {
            document.getElementById('feedbackModal').classList.remove('show');
        }

        function selectRating(rating) {
            currentRating = rating;
            document.getElementById('feedbackRating').value = rating;
            updateStars();
        }

        function updateStars() {
            const stars = document.querySelectorAll('.star-btn');
            stars.forEach((star, index) => {
                if (index < currentRating) {
                    star.classList.add('active');
                    star.textContent = '★';
                } else {
                    star.classList.remove('active');
                    star.textContent = '☆';
                }
            });
        }

        function submitFeedback() {
            const bookingId = document.getElementById('feedbackBookingId').value;
            const rating = document.getElementById('feedbackRating').value;
            const reviewText = document.getElementById('feedbackText').value.trim();

            if (!rating || rating === '0') {
                MentorshipSystem.showNotification('Please select a rating.', 'error');
                return;
            }

            fetch('<?= BASE_URL ?>/umentorships/submitFeedback', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    booking_id: bookingId,
                    rating: parseInt(rating),
                    review_text: reviewText
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.location.href = '<?= BASE_URL ?>/umentorships?success=feedback';
                } else {
                    MentorshipSystem.showNotification('Error: ' + (data.message || 'Failed to submit feedback'), 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                MentorshipSystem.showNotification('An error occurred. Please try again.', 'error');
            });
        }

        // Character counter for feedback
        document.getElementById('feedbackText').addEventListener('input', function() {
            document.getElementById('charCount').textContent = this.value.length;
        });

        // Auto-hide the success banner after a few seconds
        const successMessage = document.getElementById('successMessage');
        if (successMessage) {
            setTimeout(() => {
                successMessage.style.transition = 'opacity 0.35s ease, transform 0.35s ease';
                successMessage.style.opacity = '0';
                successMessage.style.transform = 'translateY(-8px)';

                setTimeout(() => {
                    successMessage.remove();

                    const url = new URL(window.location.href);
                    url.searchParams.delete('success');
                    window.history.replaceState({}, document.title, url.pathname + url.search);
                }, 350);
            }, 3000);
        }

        // Close modals handled globally by mentorship.js (outside click + Escape key)
    </script>
    <script src="<?= BASE_URL ?>/js/mentorship.js"></script>

    <?php include __DIR__ . '/../layout/footer.php'; ?>
</body>
</html>