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
    <link rel="stylesheet" href="<?= BASE_URL ?>/css/alumni.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/css/mentorship.css">
    <link rel="icon" type="image/png" href="<?= BASE_URL ?>/assets/images/U.png">
    <script>
        window.BASE_URL = '<?= BASE_URL ?>';
        window.USER_TYPE = 'undergraduate';
    </script>
    <style>
        body { padding-top: 80px; background-color: #a78bfa45 !important; }
        
        .container {
            max-width: 1000px;
            margin: 0 auto;
            padding: 0 1rem;
        }
        
        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
        }
        
        .page-title {
            font-size: 1.75rem;
            font-weight: 700;
            color: #1f2937;
            margin: 0;
        }
        
        .btn-explore {
            background: linear-gradient(135deg, #7c3aed 0%, #a855f7 100%);
            color: white;
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            font-weight: 600;
            text-decoration: none;
            transition: transform 0.2s, box-shadow 0.2s;
        }
        
        .btn-explore:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(124, 58, 237, 0.4);
        }
        
        .section-card {
            background: white;
            border-radius: 16px;
            padding: 1.5rem;
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
            margin-bottom: 1.5rem;
        }
        
        .section-title {
            font-size: 1.25rem;
            font-weight: 600;
            margin-bottom: 1rem;
            color: #1f2937;
        }
        
        .success-message {
            background: #d1fae5;
            border: 1px solid #10b981;
            color: #065f46;
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        /* Booking Card Styles */
        .booking-card {
            background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);
            border: 2px solid #10b981;
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 1rem;
        }
        
        .booking-header {
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        
        .booking-avatar {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            object-fit: cover;
        }
        
        .booking-datetime {
            font-size: 1.125rem;
            font-weight: 600;
            color: #065f46;
            margin-top: 0.75rem;
        }
        
        .session-status-badge {
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
        }
        
        .badge-upcoming { background: #d1fae5; color: #065f46; }
        .badge-soon { background: #fef3c7; color: #b45309; animation: pulse 1.5s infinite; }
        .badge-active { background: #fee2e2; color: #dc2626; animation: pulse 1s infinite; }
        
        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.7; }
        }
        
        .btn-join-active {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%) !important;
            color: white !important;
            animation: pulseGlow 2s infinite;
        }
        
        .btn-join-disabled {
            background: #e5e7eb !important;
            color: #9ca3af !important;
            cursor: not-allowed !important;
        }
        
        @keyframes pulseGlow {
            0%, 100% { box-shadow: 0 0 5px rgba(16, 185, 129, 0.5); }
            50% { box-shadow: 0 0 20px rgba(16, 185, 129, 0.8); }
        }
        
        .btn {
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            border: none;
            transition: all 0.2s;
        }
        
        .btn-secondary { background: #f3f4f6; color: #374151; }
        .btn-secondary:hover { background: #e5e7eb; }
        
        .btn-primary {
            background: linear-gradient(135deg, #7c3aed 0%, #a855f7 100%);
            color: white;
        }
        
        .btn-primary:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(124, 58, 237, 0.4);
        }
        
        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 3rem 2rem;
            color: #4b5563;
        }
        
        .empty-icon { font-size: 4rem; margin-bottom: 1rem; }
        
        /* CTA Section */
        .cta-section {
            background: linear-gradient(135deg, #7c3aed 0%, #a855f7 100%);
            color: white;
            border-radius: 16px;
            padding: 2rem;
            text-align: center;
            margin-top: 2rem;
        }
        
        .cta-section h2 {
            margin: 0 0 0.5rem 0;
            font-size: 1.5rem;
        }
        
        .cta-section p {
            margin: 0 0 1.5rem 0;
            opacity: 0.9;
        }
        
        .cta-section .btn {
            background: white;
            color: #7c3aed;
        }
        
        /* Modal Styles */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 1000;
            align-items: center;
            justify-content: center;
        }
        
        .modal.show { display: flex; }
        
        .modal-dialog {
            background: white;
            border-radius: 16px;
            max-width: 500px;
            width: 90%;
            max-height: 90vh;
            overflow-y: auto;
        }
        
        .modal-header {
            padding: 1.5rem;
            border-bottom: 1px solid #e5e7eb;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-radius: 16px 16px 0 0;
        }
        
        .modal-body { padding: 1.5rem; }
        
        .modal-footer {
            padding: 1rem 1.5rem;
            border-top: 1px solid #e5e7eb;
            display: flex;
            justify-content: flex-end;
            gap: 0.75rem;
        }
        
        .close-modal {
            background: none;
            border: none;
            font-size: 1.5rem;
            cursor: pointer;
            color: #6b7280;
            min-width: 44px;
            min-height: 44px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 8px;
        }

        .close-modal:hover {
            background: rgba(0, 0, 0, 0.1);
        }

        .close-modal:focus {
            outline: 2px solid #7c3aed;
            outline-offset: 2px;
        }
        
        /* Feedback Section */
        .feedback-card {
            background: #f9fafb;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            padding: 1.25rem;
            margin-bottom: 1rem;
        }
        
        .star-rating {
            display: flex;
            gap: 0.25rem;
            justify-content: center;
            font-size: 2rem;
            margin: 1rem 0;
        }
        
        .star-btn {
            cursor: pointer;
            color: #d1d5db;
            transition: all 0.2s;
            background: none;
            border: none;
            font-size: 2rem;
            min-width: 44px;
            min-height: 44px;
        }
        
        .star-btn:hover { transform: scale(1.2); }
        .star-btn.active { color: #fbbf24 !important; }

        .star-btn:focus {
            outline: 2px solid #fbbf24;
            outline-offset: 2px;
            border-radius: 4px;
        }
        
        .feedback-textarea {
            width: 100%;
            padding: 0.75rem;
            border: 2px solid #e5e7eb;
            border-radius: 8px;
            resize: vertical;
            min-height: 100px;
        }
        
        .feedback-textarea:focus {
            border-color: #7c3aed;
            outline: none;
        }

        /* Cancel Modal */
        .cancel-reason-input {
            width: 100%;
            padding: 0.75rem;
            border: 2px solid #e5e7eb;
            border-radius: 8px;
            resize: vertical;
            min-height: 100px;
        }

        .cancel-reason-input:focus {
            border-color: #ef4444;
            outline: none;
        }
    </style>
</head>

<body>
    <?php
    // Include navigation
    $navFile = APPROOT . '/views/actors/undergraduate/Unavigation.view.php';
    if (file_exists($navFile)) {
        include $navFile;
    }
    ?>

    <main id="main-content" role="main">
    <div class="container">
        <!-- Success Message -->
        <?php if (isset($_GET['success'])): ?>
            <div class="success-message">
                <span>✅</span>
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
                🧭 Explore Mentors
            </a>
        </div>

        <!-- Upcoming Bookings Section -->
        <div class="section-card">
            <h2 class="section-title">🔒 Upcoming Sessions</h2>

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
                    <div class="booking-card" data-booking-id="<?= $booking['booking_id'] ?>">
                        <div class="booking-header">
                            <img src="<?= !empty($booking['mentor_picture']) ? htmlspecialchars($booking['mentor_picture']) : 'https://i.pravatar.cc/150?img=' . rand(1, 70) ?>"
                                alt="<?= htmlspecialchars($booking['mentor_name'] ?? 'Mentor') ?>" 
                                class="booking-avatar">
                            <div style="flex: 1;">
                                <h4 style="margin: 0;"><?= htmlspecialchars($booking['mentor_name'] ?? 'Mentor') ?></h4>
                                <p style="margin: 0; color: #065f46; font-size: 0.875rem;">
                                    <?= htmlspecialchars($booking['mentor_title'] ?? '') ?>
                                    <?php if (!empty($booking['mentor_company'])): ?>
                                        at <?= htmlspecialchars($booking['mentor_company']) ?>
                                    <?php endif; ?>
                                </p>
                            </div>
                            <span class="session-status-badge <?= $isActive ? 'badge-active' : ($canJoin ? 'badge-soon' : 'badge-upcoming') ?>">
                                <?= $isActive ? '🔴 LIVE' : ($canJoin ? 'Starting Soon' : '🔒 Confirmed') ?>
                            </span>
                        </div>
                        
                        <div class="booking-datetime">
                            📅 <?= $sessionDate->format('l, F j, Y') ?> at <?= $sessionDate->format('g:i A') ?>
                        </div>
                        
                        <!-- Countdown -->
                        <div style="margin-top: 0.75rem; padding: 0.5rem; background: #f0fdf4; border-radius: 8px;">
                            <?php if ($isActive): ?>
                                <span style="color: #10b981; font-weight: 700;">🟢 Session In Progress - Join Now!</span>
                            <?php elseif ($canJoin): ?>
                                <span style="color: #f59e0b; font-weight: 600;">⏰ Session starting very soon!</span>
                            <?php else: ?>
                                <span style="color: #6b7280;">
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
                        <div style="margin-top: 1rem; display: flex; gap: 0.75rem; flex-wrap: wrap;">
                            <?php if (!empty($booking['meeting_link'])): ?>
                                <button class="btn <?= $canJoin ? 'btn-join-active' : 'btn-join-disabled' ?>" 
                                        <?= !$canJoin ? 'disabled' : '' ?>
                                        onclick="<?= $canJoin ? "window.open('" . htmlspecialchars($booking['meeting_link']) . "', '_blank')" : '' ?>">
                                    <?= $canJoin ? '🎥 Join Meeting Now' : '⏳ Wait for Session Time' ?>
                                </button>
                            <?php else: ?>
                                <span class="btn btn-secondary" style="cursor: not-allowed; opacity: 0.7;">
                                    ⚠️ Meeting link unavailable
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
                    <div class="empty-icon">📅</div>
                    <h3>No Upcoming Sessions</h3>
                    <p>You don't have any booked mentorship sessions yet. Explore available mentors and book a session!</p>
                    <a href="<?= BASE_URL ?>/umentorships/exploreMentors" class="btn btn-primary" style="margin-top: 1rem; display: inline-block; text-decoration: none;">
                        🧭 Find a Mentor
                    </a>
                </div>
            <?php endif; ?>
        </div>

        <!-- Sessions Needing Feedback -->
        <?php if (isset($data['needs_feedback']) && count($data['needs_feedback']) > 0): ?>
            <div class="section-card" style="border: 2px solid #f59e0b;">
                <h2 class="section-title">⭐ Leave Feedback</h2>
                <p style="color: #6b7280; margin-bottom: 1rem;">Help other students by sharing your experience!</p>

                <?php foreach ($data['needs_feedback'] as $session): ?>
                    <?php $sessionDate = new DateTime($session['slot_datetime']); ?>
                    <div class="feedback-card">
                        <div style="display: flex; align-items: center; gap: 1rem;">
                            <img src="<?= !empty($session['mentor_picture']) ? htmlspecialchars($session['mentor_picture']) : 'https://i.pravatar.cc/150' ?>"
                                alt="<?= htmlspecialchars($session['mentor_name'] ?? 'Mentor') ?>"
                                style="width: 50px; height: 50px; border-radius: 50%;">
                            <div style="flex: 1;">
                                <h4 style="margin: 0;"><?= htmlspecialchars($session['mentor_name'] ?? 'Mentor') ?></h4>
                                <p style="margin: 0; color: #6b7280; font-size: 0.875rem;">
                                    📅 <?= $sessionDate->format('F j, Y') ?>
                                </p>
                            </div>
                            <button class="btn btn-primary" onclick="openFeedbackModal(<?= $session['booking_id'] ?>, '<?= htmlspecialchars(addslashes($session['mentor_name'] ?? 'Mentor')) ?>')">
                                ⭐ Rate Session
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
                <h2 class="section-title">✅ Past Sessions</h2>

                <?php foreach (array_slice($reviewedSessions, 0, 5) as $session): ?>
                    <?php $sessionDate = new DateTime($session['slot_datetime']); ?>
                    <div style="display: flex; align-items: center; gap: 1rem; padding: 0.75rem 0; border-bottom: 1px solid #e5e7eb;">
                        <img src="<?= !empty($session['mentor_picture']) ? htmlspecialchars($session['mentor_picture']) : 'https://i.pravatar.cc/150' ?>"
                            alt="Mentor" style="width: 45px; height: 45px; border-radius: 50%;">
                        <div style="flex: 1;">
                            <div style="font-weight: 500;"><?= htmlspecialchars($session['mentor_name'] ?? 'Mentor') ?></div>
                            <div style="color: #6b7280; font-size: 0.875rem;"><?= $sessionDate->format('M j, Y') ?></div>
                        </div>
                        <div style="color: #fbbf24;">
                            <?= str_repeat('★', $session['rating']) . str_repeat('☆', 5 - $session['rating']) ?>
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
                🧭 Explore Mentors
            </a>
        </div>
    </div>

    <!-- Cancel Modal -->
    <div id="cancelModal" class="modal">
        <div class="modal-dialog">
            <div class="modal-header" style="background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%); color: white;">
                <h3 style="margin: 0;">Cancel Session</h3>
                <button class="close-modal" onclick="closeCancelModal()" style="color: white;" aria-label="Close cancel session dialog"><span aria-hidden="true">&times;</span></button>
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
                <button class="btn" style="background: #ef4444; color: white;" onclick="confirmCancel()">Cancel Session</button>
            </div>
        </div>
    </div>

    <!-- Feedback Modal -->
    <div id="feedbackModal" class="modal">
        <div class="modal-dialog">
            <div class="modal-header" style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); color: white;">
                <h3 style="margin: 0;">⭐ Rate Your Session</h3>
                <button class="close-modal" onclick="closeFeedbackModal()" style="color: white;" aria-label="Close feedback dialog"><span aria-hidden="true">&times;</span></button>
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
                <button class="btn" style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); color: white;" onclick="submitFeedback()">
                    ⭐ Submit Feedback
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
                alert('Please provide a reason for cancellation.');
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
                    alert('Error: ' + (data.message || 'Failed to cancel session'));
                }
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
                alert('Please select a rating.');
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
                    alert('Error: ' + (data.message || 'Failed to submit feedback'));
                }
            });
        }

        // Character counter for feedback
        document.getElementById('feedbackText').addEventListener('input', function() {
            document.getElementById('charCount').textContent = this.value.length;
        });

        // Close modals when clicking outside
        document.querySelectorAll('.modal').forEach(modal => {
            modal.addEventListener('click', function(e) {
                if (e.target === this) {
                    this.classList.remove('show');
                }
            });
        });
    </script>
    <script src="<?= ROOT ?>/js/mentorship.js"></script>
    </main>

    <?php include __DIR__ . '/../layout/footer.php'; ?>
</body>
</html>
