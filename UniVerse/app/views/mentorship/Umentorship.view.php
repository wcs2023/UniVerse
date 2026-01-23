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

// Include navigation if it exists, otherwise we'll use inline navigation
$navFile = APPROOT . '/views/actors/undergraduate/Unavigation.view.php';
if (file_exists($navFile)) {
    require_once $navFile;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Mentorships - UniVerse</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        :root {
            --primary-purple: #7c3aed;
            --purple-hover: #6d28d9;
            --text-dark: #1f2937;
            --text-light: #6b7280;
            --bg-light: #f9fafb;
            --success-green: #10b981;
            --warning-yellow: #f59e0b;
            --danger-red: #ef4444;
            --pending-blue: #3b82f6;
        }
        
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            background-color: var(--bg-light);
            color: var(--text-dark);
            line-height: 1.6;
            margin: 0;
        }

        /* Container */
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 2rem;
        }

        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
            margin-top: 4rem;
            padding: 2rem 0 1rem;
        }

        .page-header h1 {
            font-size: 2rem;
            font-weight: 700;
            color: var(--text-dark);
            margin: 0;
        }

        .btn-explore {
            background-color: white;
            color: var(--primary-purple);
            border: 2px solid var(--primary-purple);
            padding: 0.625rem 1.5rem;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-explore:hover {
            background-color: var(--primary-purple);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(124, 58, 237, 0.3);
        }

        .section-card {
            background: white;
            border-radius: 12px;
            padding: 2rem;
            margin-bottom: 2rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        .section-title {
            font-size: 1.5rem;
            font-weight: 600;
            color: var(--text-dark);
            margin-bottom: 1.5rem;
        }

        .mentorship-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 1.25rem;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            margin-bottom: 1rem;
            transition: all 0.3s ease;
            background: white;
        }

        .mentorship-item:hover {
            border-color: var(--primary-purple);
            box-shadow: 0 4px 12px rgba(124, 58, 237, 0.1);
            transform: translateY(-2px);
        }

        .mentor-info {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .mentor-avatar {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            object-fit: cover;
        }

        .mentor-details h3 {
            font-size: 1.125rem;
            font-weight: 600;
            color: var(--text-dark);
            margin: 0 0 0.25rem 0;
        }

        .status-badge {
            padding: 0.375rem 0.875rem;
            border-radius: 20px;
            font-size: 0.875rem;
            font-weight: 500;
            display: inline-block;
        }

        .status-pending {
            background-color: rgba(59, 130, 246, 0.1);
            color: var(--pending-blue);
        }

        .status-accepted {
            background-color: rgba(16, 185, 129, 0.1);
            color: var(--success-green);
        }

        .status-rejected {
            background-color: rgba(239, 68, 68, 0.1);
            color: var(--danger-red);
        }

        .status-scheduled {
            background-color: rgba(124, 58, 237, 0.1);
            color: var(--primary-purple);
        }
        
        .btn-view-times {
            background-color: var(--primary-purple);
            color: white;
            border: none;
            padding: 0.625rem 1.5rem;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .btn-view-times:hover {
            background-color: var(--purple-hover);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(124, 58, 237, 0.3);
        }

        .session-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 1.25rem;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            margin-bottom: 1rem;
            background: white;
            transition: all 0.3s ease;
        }

        .session-item:hover {
            border-color: var(--primary-purple);
            box-shadow: 0 4px 12px rgba(124, 58, 237, 0.1);
            transform: translateY(-2px);
        }

        .session-info h3 {
            font-size: 1.125rem;
            font-weight: 600;
            color: var(--text-dark);
            margin: 0 0 0.25rem 0;
        }

        .session-date {
            color: var(--text-light);
            font-size: 0.95rem;
        }

        .cta-section {
            text-align: center;
            padding: 3rem 2rem;
            background: white;
            border-radius: 12px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        .cta-section h2 {
            font-size: 1.75rem;
            font-weight: 700;
            color: var(--text-dark);
            margin-bottom: 1rem;
        }

        .cta-section p {
            color: var(--text-light);
            font-size: 1.05rem;
            margin-bottom: 2rem;
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
        }

        .empty-state {
            text-align: center;
            padding: 3rem 1rem;
        }

        .empty-icon {
            font-size: 4rem;
            color: var(--text-light);
            margin-bottom: 1rem;
        }

        .empty-state h4 {
            color: var(--text-dark);
            margin-bottom: 0.5rem;
        }

        .empty-state p {
            color: var(--text-light);
        }

        /* Modal Styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
        }

        .modal.show {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .modal-dialog {
            background: white;
            border-radius: 12px;
            max-width: 600px;
            width: 90%;
            max-height: 90vh;
            overflow-y: auto;
        }

        .modal-header {
            background-color: var(--primary-purple);
            color: white;
            padding: 1.5rem;
            border-radius: 12px 12px 0 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .modal-title {
            font-size: 1.25rem;
            font-weight: 600;
            margin: 0;
        }

        .btn-close {
            background: none;
            border: none;
            color: white;
            font-size: 1.5rem;
            cursor: pointer;
            padding: 0;
            width: 30px;
            height: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .modal-body {
            padding: 1.5rem;
        }

        .modal-footer {
            padding: 1rem 1.5rem;
            border-top: 1px solid #e5e7eb;
            display: flex;
            justify-content: flex-end;
            gap: 0.5rem;
        }

        .btn-secondary {
            background-color: #6b7280;
            color: white;
            border: none;
            padding: 0.625rem 1.5rem;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
        }

        .time-slot-card {
            padding: 1rem;
            border: 2px solid #e5e7eb;
            border-radius: 8px;
            margin-bottom: 0.75rem;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .time-slot-card:hover {
            border-color: var(--primary-purple);
            background-color: rgba(124, 58, 237, 0.05);
        }

        .time-slot-card.selected {
            border-color: var(--primary-purple);
            background-color: rgba(124, 58, 237, 0.1);
        }

        .time-slot-card.disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        .spinner {
            border: 3px solid #f3f3f3;
            border-top: 3px solid var(--primary-purple);
            border-radius: 50%;
            width: 40px;
            height: 40px;
            animation: spin 1s linear infinite;
            margin: 2rem auto;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .text-center {
            text-align: center;
        }

        .py-3 {
            padding: 1.5rem 0;
        }

        .d-flex {
            display: flex;
        }

        .justify-content-between {
            justify-content: space-between;
        }

        .align-items-center {
            align-items: center;
        }

        .text-muted {
            color: var(--text-light);
        }

        .badge {
            padding: 0.25rem 0.5rem;
            border-radius: 4px;
            font-size: 0.875rem;
        }

        .bg-secondary {
            background-color: #6b7280;
            color: white;
        }
        
        .text-success {
            color: var(--success-green);
        }

        @media (max-width: 768px) {
            .page-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 1rem;
            }

            .mentorship-item,
            .session-item {
                flex-direction: column;
                align-items: flex-start;
                gap: 1rem;
            }

            .btn-view-times {
                width: 100%;
            }
            
            .container {
                padding: 0 1rem;
            }

            .modal-dialog {
                width: 95%;
            }
        }
    </style>
    <link rel="stylesheet" href="<?= BASE_URL ?>/css/styles.css">
</head>
<body>
    <div class="container">
        <!-- Page Header -->
        <div class="page-header">
            <h1>My Mentorships</h1>
            <a href="<?= BASE_URL ?>/umentorships/exploreMentors" class="btn-explore">
                <span>Explore Mentors</span>
            </a>
        </div>

        <!-- Mentorship Requests Section -->
        <div class="section-card">
            <h2 class="section-title">Mentorship Requests</h2>
            
            <?php if (isset($data['mentorships']['pending']) && count($data['mentorships']['pending']) > 0): ?>
                <?php foreach ($data['mentorships']['pending'] as $request): ?>
                    <div class="mentorship-item">
                        <div class="mentor-info">
                            <img src="<?= $request['profile_picture_url'] ?? 'https://i.pravatar.cc/150' ?>" 
                                 alt="<?= htmlspecialchars($request['first_name'] . ' ' . $request['last_name']) ?>" 
                                 class="mentor-avatar">
                            <div class="mentor-details">
                                <h3><?= htmlspecialchars($request['first_name'] . ' ' . $request['last_name']) ?></h3>
                                <span class="status-badge status-<?= strtolower($request['status']) ?>">
                                    <?= ucfirst($request['status']) ?>
                                </span>
                            </div>
                        </div>
                        
                        <?php if ($request['status'] === 'accepted' || $request['status'] === 'awaiting_student_confirmation'): ?>
                            <button class="btn-view-times" onclick="viewOfferedTimes('<?= htmlspecialchars($request['first_name'] . ' ' . $request['last_name']) ?>', <?= $request['mentorship_id'] ?>)">
                                View Offered Times
                            </button>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="empty-state">
                    <div class="empty-icon">📥</div>
                    <h4>No Pending Requests</h4>
                    <p>You don't have any mentorship requests at the moment.</p>
                </div>
            <?php endif; ?>
        </div>

        <!-- Upcoming Sessions Section -->
        <div class="section-card">
            <h2 class="section-title">Upcoming Sessions</h2>
            
            <?php if (isset($data['mentorships']['upcoming']) && count($data['mentorships']['upcoming']) > 0): ?>
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
            <?php else: ?>
                <div class="empty-state">
                    <div class="empty-icon">📅</div>
                    <h4>No Upcoming Sessions</h4>
                    <p>You don't have any scheduled sessions.</p>
                </div>
            <?php endif; ?>
        </div>

        <!-- Call to Action Section -->
        <div class="cta-section">
            <h2>Ready to Find Your Guide?</h2>
            <p>Explore our network of experienced mentors who can help you achieve your academic and career goals.</p>
            <a href="<?= BASE_URL ?>/umentorships/exploreMentors" class="btn-view-times" style="text-decoration: none;">
                <span>🧭 Explore Mentors</span>
            </a>
        </div>
    </div>

    <!-- Time Selection Modal -->
    <div class="modal" id="timeModal">
        <div class="modal-dialog">
            <div class="modal-header">
                <h5 class="modal-title" id="timeModalLabel">Select a Time Slot</h5>
                <button type="button" class="btn-close" onclick="closeModal()" aria-label="Close">×</button>
            </div>
            <div class="modal-body">
                <div style="margin-bottom: 1rem;">
                    <strong>Mentor: </strong><span id="mentorName"></span>
                </div>
                <div id="timeSlotsContainer">
                    <!-- Time slots will be loaded here via AJAX -->
                    <div class="text-center py-3">
                        <div class="spinner"></div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-secondary" onclick="closeModal()">Cancel</button>
                <button type="button" class="btn-view-times" id="scheduleBtn" onclick="scheduleSession()" disabled>
                    Schedule Session
                </button>
            </div>
        </div>
    </div>

    <script>
        let selectedSlotId = null;
        let currentMentorshipId = null;

        // Function to view offered times
        function viewOfferedTimes(mentorName, mentorshipId) {
            document.getElementById('mentorName').textContent = mentorName;
            currentMentorshipId = mentorshipId;
            
            // Show the modal
            document.getElementById('timeModal').classList.add('show');
            
            // Load time slots via AJAX
            loadTimeSlots(mentorshipId);
        }

        // Function to close modal
        function closeModal() {
            document.getElementById('timeModal').classList.remove('show');
        }

        // Function to load time slots
        function loadTimeSlots(mentorshipId) {
            const container = document.getElementById('timeSlotsContainer');
            
            fetch(`<?= BASE_URL ?>/umentorships/getTimeSlots/${mentorshipId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success && data.slots.length > 0) {
                        let slotsHTML = '';
                        data.slots.forEach(slot => {
                            const startDate = new Date(slot.start_datetime);
                            const endDate = new Date(slot.end_datetime);
                            const isBooked = slot.is_booked == 1;
                            
                            slotsHTML += `
                                <div class="time-slot-card ${isBooked ? 'disabled' : ''}" 
                                     data-slot-id="${slot.slot_id}"
                                     onclick="${isBooked ? '' : `selectTimeSlot(${slot.slot_id})`}">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <strong>${startDate.toLocaleDateString('en-US', { weekday: 'long', month: 'long', day: 'numeric', year: 'numeric' })}</strong>
                                            <br>
                                            <span class="text-muted">${startDate.toLocaleTimeString('en-US', { hour: 'numeric', minute: '2-digit' })} - ${endDate.toLocaleTimeString('en-US', { hour: 'numeric', minute: '2-digit' })}</span>
                                        </div>
                                        ${isBooked ? '<span class="badge bg-secondary">Booked</span>' : '<span class="text-success" style="display: none; font-size: 1.5rem;">✓</span>'}
                                    </div>
                                </div>
                            `;
                        });
                        container.innerHTML = slotsHTML;
                    } else {
                        container.innerHTML = '<div style="padding: 1rem; background: #dbeafe; border-radius: 8px; color: #1e40af;">No time slots available yet. Please wait for your mentor to provide available times.</div>';
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    container.innerHTML = '<div style="padding: 1rem; background: #fee2e2; border-radius: 8px; color: #991b1b;">Failed to load time slots. Please try again.</div>';
                });
        }

        // Function to select a time slot
        function selectTimeSlot(slotId) {
            // Remove selected class from all slots
            document.querySelectorAll('.time-slot-card').forEach(card => {
                card.classList.remove('selected');
                const checkmark = card.querySelector('.text-success');
                if (checkmark) checkmark.style.display = 'none';
            });
            
            // Add selected class to clicked slot
            const selectedCard = document.querySelector(`[data-slot-id="${slotId}"]`);
            selectedCard.classList.add('selected');
            const checkmark = selectedCard.querySelector('.text-success');
            if (checkmark) checkmark.style.display = 'block';
            
            // Store selected slot ID
            selectedSlotId = slotId;
            
            // Enable schedule button
            document.getElementById('scheduleBtn').disabled = false;
        }

        // Function to schedule a session
        function scheduleSession() {
            if (!selectedSlotId || !currentMentorshipId) {
                alert('Please select a time slot.');
                return;
            }
            
            // Disable button to prevent double submission
            const scheduleBtn = document.getElementById('scheduleBtn');
            scheduleBtn.disabled = true;
            scheduleBtn.textContent = 'Scheduling...';
            
            // Send AJAX request to schedule the session
            const formData = new FormData();
            formData.append('mentorship_id', currentMentorshipId);
            formData.append('slot_id', selectedSlotId);
            
            fetch('<?= BASE_URL ?>/umentorships/schedule', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Close modal and reload page
                    closeModal();
                    window.location.href = '<?= URLROOT ?>/mentorships?success=scheduled';
                } else {
                    alert('Error: ' + (data.message || 'Failed to schedule session'));
                    scheduleBtn.disabled = false;
                    scheduleBtn.textContent = 'Schedule Session';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred. Please try again.');
                scheduleBtn.disabled = false;
                scheduleBtn.textContent = 'Schedule Session';
            });
        }

        // Close modal when clicking outside
        document.getElementById('timeModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeModal();
            }
        });

        // Add fade-in animation on page load
        document.addEventListener('DOMContentLoaded', function() {
            const sections = document.querySelectorAll('.section-card, .cta-section');
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
