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
    <link rel="icon" type="image/png" href="<?= BASE_URL ?>/assets/images/U.png">
    <style>
        body {
            padding-top: 90px;
        }

        .notification-bell {
            position: relative;
            cursor: pointer;
        }

        .notification-badge {
            position: absolute;
            top: -5px;
            right: -5px;
            background: #ef4444;
            color: white;
            border-radius: 50%;
            min-width: 20px;
            height: 20px;
            font-size: 0.75rem;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 0 4px;
        }

        .finalized-session-card {
            background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);
            border: 2px solid #10b981;
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 1rem;
        }

        .finalized-session-card .session-header {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .finalized-session-card .locked-badge {
            background: #10b981;
            color: white;
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .finalized-session-card .session-datetime {
            font-size: 1.125rem;
            font-weight: 600;
            color: #065f46;
            margin-top: 0.5rem;
        }

        .time-slot-input-group {
            background: #f9fafb;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            padding: 1rem;
            margin-bottom: 0.75rem;
            position: relative;
        }

        .time-slot-input-group label {
            display: block;
            font-weight: 600;
            color: #374151;
            margin-bottom: 0.5rem;
            font-size: 0.875rem;
        }

        .time-slot-input-group input[type="datetime-local"] {
            width: 100%;
            padding: 0.75rem;
            border: 2px solid #e5e7eb;
            border-radius: 8px;
            font-size: 1rem;
            transition: border-color 0.2s;
        }

        .time-slot-input-group input[type="datetime-local"]:focus {
            border-color: #7c3aed;
            outline: none;
        }

        .time-slot-input-group .remove-slot-btn {
            position: absolute;
            top: 0.5rem;
            right: 0.5rem;
            background: #ef4444;
            color: white;
            border: none;
            border-radius: 50%;
            width: 24px;
            height: 24px;
            font-size: 1rem;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: background 0.2s;
        }

        .time-slot-input-group .remove-slot-btn:hover {
            background: #dc2626;
        }

        .add-slot-btn {
            background: transparent;
            border: 2px dashed #7c3aed;
            color: #7c3aed;
            padding: 0.75rem 1rem;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 600;
            width: 100%;
            transition: all 0.2s;
            margin-top: 0.5rem;
        }

        .add-slot-btn:hover {
            background: #f5f3ff;
        }

        .add-slot-btn:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        .info-text {
            background: #eff6ff;
            border: 1px solid #bfdbfe;
            border-radius: 8px;
            padding: 0.75rem;
            color: #1e40af;
            font-size: 0.875rem;
            margin-bottom: 1rem;
        }

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

        .modal.show {
            display: flex;
        }

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
            background: linear-gradient(135deg, #7c3aed 0%, #a855f7 100%);
            color: white;
            border-radius: 16px 16px 0 0;
        }

        .modal-body {
            padding: 1.5rem;
        }

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
            color: white;
        }

        .btn {
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            border: none;
            transition: all 0.2s;
        }

        .btn-secondary {
            background: #f3f4f6;
            color: #374151;
        }

        .btn-secondary:hover {
            background: #e5e7eb;
        }

        .btn-primary {
            background: linear-gradient(135deg, #7c3aed 0%, #a855f7 100%);
            color: white;
        }

        .btn-primary:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(124, 58, 237, 0.4);
        }

        .btn-primary:disabled {
            background: #9ca3af;
            cursor: not-allowed;
            transform: none;
            box-shadow: none;
        }

        .request-item {
            background: white;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            padding: 1rem;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            transition: box-shadow 0.2s;
        }

        .request-item:hover {
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .request-info {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .request-avatar {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            object-fit: cover;
        }

        .request-details h3 {
            margin: 0;
            font-size: 1rem;
            color: #1f2937;
        }

        .request-details p {
            margin: 0.25rem 0 0;
            color: #6b7280;
            font-size: 0.875rem;
        }

        .request-actions {
            display: flex;
            gap: 0.5rem;
        }

        .btn-accept {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 8px;
            border: none;
            font-weight: 600;
            cursor: pointer;
            transition: transform 0.2s;
        }

        .btn-accept:hover {
            transform: scale(1.05);
        }

        .btn-decline {
            background: #f3f4f6;
            color: #6b7280;
            padding: 0.5rem 1rem;
            border-radius: 8px;
            border: none;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
        }

        .btn-decline:hover {
            background: #fee2e2;
            color: #dc2626;
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

    <div class="container">
        <!-- Success Message -->
        <?php if (isset($_GET['success'])): ?>
            <div class="success-message">
                <span>✅</span>
                <span>
                    <?php 
                    switch($_GET['success']) {
                        case 'time_slots_sent':
                            echo 'Time slots have been sent to the student. They will select their preferred time.';
                            break;
                        case 'declined':
                            echo 'Mentorship request has been declined.';
                            break;
                        default:
                            echo 'Action completed successfully.';
                    }
                    ?>
                </span>
            </div>
        <?php endif; ?>

        <h1 class="page-title">Mentor Dashboard</h1>

        <div class="dashboard-grid">
            <!-- Left Column -->
            <div>
                <!-- Pending Requests -->
                <div class="dashboard-card">
                    <h2 class="card-title">📥 Pending Requests</h2>

                    <?php if (isset($data['pending_requests']) && count($data['pending_requests']) > 0): ?>
                        <?php foreach ($data['pending_requests'] as $request): ?>
                            <div class="request-item">
                                <div class="request-info">
                                    <img src="<?= !empty($request['profile_picture_url']) ? htmlspecialchars($request['profile_picture_url']) : 'https://i.pravatar.cc/150?img=' . rand(1, 70) ?>"
                                        alt="<?= htmlspecialchars($request['mentee_name']) ?>" class="request-avatar">
                                    <div class="request-details">
                                        <h3><?= htmlspecialchars($request['mentee_name']) ?></h3>
                                        <p><?= htmlspecialchars($request['major'] ?? 'Student') ?></p>
                                        <?php if (!empty($request['message'])): ?>
                                            <p style="font-style: italic; color: #9ca3af; margin-top: 0.5rem;">
                                                "<?= htmlspecialchars(substr($request['message'], 0, 100)) ?>..."
                                            </p>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <div class="request-actions">
                                    <button class="btn-accept"
                                        onclick="acceptRequest(<?= $request['request_id'] ?>, '<?= htmlspecialchars(addslashes($request['mentee_name'])) ?>')">
                                        ✓ Accept
                                    </button>
                                    <button class="btn-decline" onclick="declineRequest(<?= $request['request_id'] ?>)">
                                        ✗ Decline
                                    </button>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="empty-state">
                            <div class="empty-icon">📭</div>
                            <h3>No Pending Requests</h3>
                            <p>No pending requests at the moment</p>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Confirmed/Finalized Sessions -->
                <?php if (isset($data['finalized_sessions']) && count($data['finalized_sessions']) > 0): ?>
                    <div class="dashboard-card" style="margin-top: 2rem;">
                        <h2 class="card-title">🔒 Confirmed Sessions</h2>

                        <?php foreach ($data['finalized_sessions'] as $session): ?>
                            <?php $sessionDate = new DateTime($session['session_datetime']); ?>
                            <div class="finalized-session-card">
                                <div class="session-header">
                                    <img src="<?= !empty($session['student_picture']) ? htmlspecialchars($session['student_picture']) : 'https://i.pravatar.cc/150?img=' . rand(1, 70) ?>"
                                        alt="<?= htmlspecialchars($session['student_name']) ?>" 
                                        style="width: 50px; height: 50px; border-radius: 50%; object-fit: cover;">
                                    <div style="flex: 1;">
                                        <h4 style="margin: 0;"><?= htmlspecialchars($session['student_name']) ?></h4>
                                        <p style="margin: 0; color: #065f46; font-size: 0.875rem;">
                                            <?= htmlspecialchars($session['degree_program'] ?? 'Student') ?>
                                        </p>
                                    </div>
                                    <span class="locked-badge">🔒 Confirmed</span>
                                </div>
                                <div class="session-datetime">
                                    📅 <?= $sessionDate->format('l, F j, Y') ?> at <?= $sessionDate->format('g:i A') ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <!-- Upcoming Sessions (Legacy) -->
                <?php if (isset($data['upcoming_sessions']) && count($data['upcoming_sessions']) > 0): ?>
                    <div class="dashboard-card" style="margin-top: 2rem;">
                        <h2 class="card-title">📅 Upcoming Sessions</h2>

                        <?php foreach ($data['upcoming_sessions'] as $session): ?>
                            <div class="session-item">
                                <img src="<?= !empty($session['profile_picture_url']) ? htmlspecialchars($session['profile_picture_url']) : 'https://i.pravatar.cc/150?img=' . rand(1, 70) ?>"
                                    alt="<?= htmlspecialchars($session['mentee_name']) ?>" class="session-avatar">
                                <div class="session-details">
                                    <h4><?= htmlspecialchars($session['mentee_name']) ?></h4>
                                    <p><?= date('F j, Y · g:i A', strtotime($session['scheduled_time'])) ?></p>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Right Column - Impact Stats -->
            <div class="stats-card">
                <h2 class="card-title">📊 Impact Stats</h2>

                <div class="stat-item">
                    <div class="stat-label">Mentees Helped</div>
                    <div class="stat-value">
                        <?= isset($data['stats']['total_mentees']) ? $data['stats']['total_mentees'] : 0 ?></div>
                </div>

                <div class="stat-item">
                    <div class="stat-label">Hours Mentored</div>
                    <div class="stat-value">
                        <?= isset($data['stats']['total_hours']) ? $data['stats']['total_hours'] : 0 ?></div>
                </div>

                <div class="stat-item">
                    <div class="stat-label">Average Rating</div>
                    <div class="stat-value">
                        <?= isset($data['stats']['average_rating']) ? number_format($data['stats']['average_rating'], 1) : '0.0' ?>
                    </div>
                </div>

                <?php if (isset($data['unread_notifications']) && $data['unread_notifications'] > 0): ?>
                    <div style="margin-top: 1.5rem; padding-top: 1rem; border-top: 1px solid #e5e7eb;">
                        <div class="stat-item">
                            <div class="stat-label">🔔 Unread Notifications</div>
                            <div class="stat-value" style="color: #7c3aed;">
                                <?= $data['unread_notifications'] ?>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Time Slots Modal -->
    <div id="timeSlotsModal" class="modal">
        <div class="modal-dialog">
            <div class="modal-header">
                <h3 class="modal-title">📅 Propose Time Slots</h3>
                <button class="close-modal" onclick="closeModal()">&times;</button>
            </div>
            <div class="modal-body">
                <div class="info-text">
                    ℹ️ Please provide <strong>2 time slots</strong> for <strong id="menteeName"></strong> to choose from.
                    The student will receive a notification and select their preferred time.
                </div>

                <form id="timeSlotsForm">
                    <input type="hidden" id="requestId" name="request_id">
                    
                    <div id="timeSlotsContainer">
                        <div class="time-slot-input-group" data-slot="1">
                            <label>Option 1 (Required)</label>
                            <input type="datetime-local" name="time_slots[]" required 
                                   min="<?= date('Y-m-d\TH:i', strtotime('+1 day')) ?>">
                        </div>
                        <div class="time-slot-input-group" data-slot="2">
                            <label>Option 2 (Required)</label>
                            <input type="datetime-local" name="time_slots[]" required
                                   min="<?= date('Y-m-d\TH:i', strtotime('+1 day')) ?>">
                        </div>
                    </div>

                    <button type="button" class="add-slot-btn" id="addSlotBtn" onclick="addTimeSlot()">
                        + Add Another Option (Optional)
                    </button>
                </form>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" onclick="closeModal()">Cancel</button>
                <button class="btn btn-primary" id="submitSlotsBtn" onclick="submitTimeSlots()">
                    📤 Send to Student
                </button>
            </div>
        </div>
    </div>

    <script>
        let slotCount = 2;
        const maxSlots = 4;

        function acceptRequest(requestId, menteeName) {
            document.getElementById('requestId').value = requestId;
            document.getElementById('menteeName').textContent = menteeName;
            document.getElementById('timeSlotsModal').classList.add('show');
        }

        function declineRequest(requestId) {
            if (confirm('Are you sure you want to decline this mentorship request?')) {
                fetch('<?= BASE_URL ?>/amentorships/declineRequest', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ request_id: requestId })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        window.location.href = '<?= BASE_URL ?>/amentorships?success=declined';
                    } else {
                        alert('Error declining request. Please try again.');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error declining request. Please try again.');
                });
            }
        }

        function closeModal() {
            document.getElementById('timeSlotsModal').classList.remove('show');
            // Reset form
            document.getElementById('timeSlotsForm').reset();
            // Reset to 2 slots
            const container = document.getElementById('timeSlotsContainer');
            container.innerHTML = `
                <div class="time-slot-input-group" data-slot="1">
                    <label>Option 1 (Required)</label>
                    <input type="datetime-local" name="time_slots[]" required 
                           min="${new Date(Date.now() + 86400000).toISOString().slice(0, 16)}">
                </div>
                <div class="time-slot-input-group" data-slot="2">
                    <label>Option 2 (Required)</label>
                    <input type="datetime-local" name="time_slots[]" required
                           min="${new Date(Date.now() + 86400000).toISOString().slice(0, 16)}">
                </div>
            `;
            slotCount = 2;
            document.getElementById('addSlotBtn').disabled = false;
        }

        function addTimeSlot() {
            if (slotCount >= maxSlots) {
                document.getElementById('addSlotBtn').disabled = true;
                return;
            }

            slotCount++;
            const container = document.getElementById('timeSlotsContainer');
            const minDate = new Date(Date.now() + 86400000).toISOString().slice(0, 16);
            
            const slotHtml = `
                <div class="time-slot-input-group" data-slot="${slotCount}">
                    <label>Option ${slotCount} (Optional)</label>
                    <input type="datetime-local" name="time_slots[]" 
                           min="${minDate}">
                    <button type="button" class="remove-slot-btn" onclick="removeTimeSlot(this)">&times;</button>
                </div>
            `;
            container.insertAdjacentHTML('beforeend', slotHtml);

            if (slotCount >= maxSlots) {
                document.getElementById('addSlotBtn').disabled = true;
            }
        }

        function removeTimeSlot(button) {
            button.closest('.time-slot-input-group').remove();
            slotCount--;
            document.getElementById('addSlotBtn').disabled = false;
            
            // Re-number remaining slots
            const slots = document.querySelectorAll('.time-slot-input-group');
            slots.forEach((slot, index) => {
                const label = slot.querySelector('label');
                const isRequired = index < 2;
                label.textContent = `Option ${index + 1} (${isRequired ? 'Required' : 'Optional'})`;
                slot.setAttribute('data-slot', index + 1);
            });
        }

        function submitTimeSlots() {
            const form = document.getElementById('timeSlotsForm');
            const inputs = form.querySelectorAll('input[type="datetime-local"]');
            const timeSlots = [];
            const now = new Date();

            // Collect and validate time slots
            let hasError = false;
            inputs.forEach((input, index) => {
                if (input.value) {
                    const slotDate = new Date(input.value);
                    if (slotDate <= now) {
                        alert(`Time slot ${index + 1} must be in the future.`);
                        hasError = true;
                        return;
                    }
                    timeSlots.push(input.value);
                } else if (index < 2) {
                    // First two are required
                    alert(`Please fill in time slot ${index + 1}. At least 2 options are required.`);
                    hasError = true;
                    return;
                }
            });

            if (hasError) return;

            if (timeSlots.length < 2) {
                alert('Please provide at least 2 time slots for the student to choose from.');
                return;
            }

            const requestId = document.getElementById('requestId').value;
            const submitBtn = document.getElementById('submitSlotsBtn');
            
            // Disable button and show loading
            submitBtn.disabled = true;
            submitBtn.textContent = 'Sending...';

            fetch('<?= BASE_URL ?>/amentorships/proposeTimeSlots', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    request_id: requestId,
                    time_slots: timeSlots
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    closeModal();
                    window.location.href = '<?= BASE_URL ?>/amentorships?success=time_slots_sent';
                } else {
                    alert('Error: ' + (data.message || 'Failed to send time slots. Please try again.'));
                    submitBtn.disabled = false;
                    submitBtn.textContent = '📤 Send to Student';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error sending time slots. Please try again.');
                submitBtn.disabled = false;
                submitBtn.textContent = '📤 Send to Student';
            });
        }

        // Close modal when clicking outside
        document.getElementById('timeSlotsModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeModal();
            }
        });
    </script>

    <?php
    // Include footer
    include __DIR__ . '/../layout/footer.php';
    ?>
</body>

</html>
