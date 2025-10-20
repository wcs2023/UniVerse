<?php
// Define constants if not already defined (for direct access)
if (!defined('APPROOT')) {
    define('APPROOT', dirname(dirname(dirname(__FILE__))));
}
if (!defined('URLROOT')) {
    define('URLROOT', 'http://localhost/UniVerse/public');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mentor Dashboard - UniVerse</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --primary-purple: #7c3aed;
            --purple-hover: #6d28d9;
            --purple-light: #ede9fe;
            --text-dark: #1f2937;
            --text-light: #6b7280;
            --bg-light: #f9fafb;
            --border-color: #e5e7eb;
            --success: #10b981;
            --danger: #ef4444;
        }
        
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            background-color: var(--bg-light);
            color: var(--text-dark);
            line-height: 1.6;
            margin: 0;
        }

        .container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 2rem;
        }

        .page-title {
            font-size: 2rem;
            font-weight: 700;
            color: var(--text-dark);
            margin-bottom: 2rem;
        }

        .dashboard-grid {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 2rem;
        }

        /* Card Styles */
        .card {
            background: white;
            border-radius: 12px;
            padding: 2rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        .card-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--text-dark);
            margin-bottom: 1.5rem;
        }

        /* Pending Requests */
        .request-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 1.25rem;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            margin-bottom: 1rem;
            transition: all 0.3s;
        }

        .request-item:hover {
            border-color: var(--primary-purple);
            box-shadow: 0 2px 8px rgba(124, 58, 237, 0.1);
        }

        .request-info {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .request-avatar {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            object-fit: cover;
        }

        .request-details h3 {
            font-size: 1.125rem;
            font-weight: 600;
            color: var(--text-dark);
            margin-bottom: 0.25rem;
        }

        .request-details p {
            font-size: 0.95rem;
            color: var(--text-light);
        }

        .request-actions {
            display: flex;
            gap: 0.75rem;
        }

        .btn {
            padding: 0.625rem 1.5rem;
            border-radius: 8px;
            font-weight: 600;
            font-size: 0.95rem;
            cursor: pointer;
            transition: all 0.3s;
            border: none;
            text-decoration: none;
            display: inline-block;
        }

        .btn-accept {
            background-color: var(--primary-purple);
            color: white;
        }

        .btn-accept:hover {
            background-color: var(--purple-hover);
            transform: translateY(-2px);
        }

        .btn-decline {
            background-color: white;
            color: var(--text-dark);
            border: 1px solid var(--border-color);
        }

        .btn-decline:hover {
            background-color: var(--bg-light);
            border-color: var(--danger);
            color: var(--danger);
        }

        /* Upcoming Sessions */
        .session-item {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 1rem;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            margin-bottom: 1rem;
            transition: all 0.3s;
        }

        .session-item:hover {
            border-color: var(--primary-purple);
            background-color: var(--purple-light);
        }

        .session-avatar {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            object-fit: cover;
        }

        .session-details h4 {
            font-size: 1rem;
            font-weight: 600;
            color: var(--text-dark);
            margin-bottom: 0.25rem;
        }

        .session-details p {
            font-size: 0.9rem;
            color: var(--text-light);
        }

        /* Impact Stats */
        .stats-card {
            background: white;
            border-radius: 12px;
            padding: 2rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        .stat-item {
            text-align: center;
            padding: 1.5rem 0;
            border-bottom: 1px solid var(--border-color);
        }

        .stat-item:last-child {
            border-bottom: none;
        }

        .stat-label {
            font-size: 0.95rem;
            color: var(--text-light);
            margin-bottom: 0.5rem;
        }

        .stat-value {
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--text-dark);
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 3rem 1rem;
            color: var(--text-light);
        }

        .empty-icon {
            font-size: 3rem;
            margin-bottom: 1rem;
        }

        /* Modal */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            animation: fadeIn 0.3s;
        }

        .modal.show {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .modal-dialog {
            background: white;
            border-radius: 12px;
            max-width: 500px;
            width: 90%;
            max-height: 90vh;
            overflow-y: auto;
        }

        .modal-header {
            padding: 1.5rem;
            border-bottom: 1px solid var(--border-color);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .modal-title {
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--text-dark);
        }

        .modal-close {
            background: none;
            border: none;
            font-size: 1.5rem;
            cursor: pointer;
            color: var(--text-light);
            padding: 0;
            width: 30px;
            height: 30px;
        }

        .modal-close:hover {
            color: var(--text-dark);
        }

        .modal-body {
            padding: 1.5rem;
        }

        .form-group {
            margin-bottom: 1.25rem;
        }

        .form-label {
            display: block;
            font-weight: 600;
            margin-bottom: 0.5rem;
            color: var(--text-dark);
        }

        .form-control {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            font-size: 1rem;
            transition: all 0.3s;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--primary-purple);
            box-shadow: 0 0 0 3px rgba(124, 58, 237, 0.1);
        }

        .time-slots {
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
        }

        .time-slot-item {
            display: flex;
            gap: 0.75rem;
            align-items: center;
        }

        .btn-remove-slot {
            background-color: var(--danger);
            color: white;
            padding: 0.5rem 1rem;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 0.9rem;
        }

        .btn-remove-slot:hover {
            background-color: #dc2626;
        }

        .btn-add-slot {
            background-color: var(--primary-purple);
            color: white;
            padding: 0.625rem 1.25rem;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 600;
            margin-top: 0.5rem;
        }

        .btn-add-slot:hover {
            background-color: var(--purple-hover);
        }

        .modal-footer {
            padding: 1.5rem;
            border-top: 1px solid var(--border-color);
            display: flex;
            justify-content: flex-end;
            gap: 0.75rem;
        }

        /* Responsive */
        @media (max-width: 968px) {
            .dashboard-grid {
                grid-template-columns: 1fr;
            }

            .request-item {
                flex-direction: column;
                align-items: flex-start;
                gap: 1rem;
            }

            .request-actions {
                width: 100%;
            }

            .btn {
                flex: 1;
            }

            .container {
                padding: 1rem;
            }
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
    </style>
</head>
<body>
    <?php 
    // Include navigation
    $navFile = APPROOT . '/views/actors/alumini/Anavbar.php';
    if (file_exists($navFile)) {
        include $navFile;
    }
    ?>
    
    <div class="container">
        <h1 class="page-title">Dashboard</h1>
        
        <div class="dashboard-grid">
            <!-- Left Column -->
            <div>
                <!-- Pending Requests -->
                <div class="card">
                    <h2 class="card-title">Pending Requests</h2>
                    
                    <?php if (isset($data['pending_requests']) && count($data['pending_requests']) > 0): ?>
                        <?php foreach ($data['pending_requests'] as $request): ?>
                            <div class="request-item">
                                <div class="request-info">
                                    <img 
                                        src="<?= !empty($request['profile_picture_url']) ? htmlspecialchars($request['profile_picture_url']) : 'https://i.pravatar.cc/150?img=' . rand(1, 70) ?>" 
                                        alt="<?= htmlspecialchars($request['mentee_name']) ?>" 
                                        class="request-avatar"
                                    >
                                    <div class="request-details">
                                        <h3><?= htmlspecialchars($request['mentee_name']) ?></h3>
                                        <p><?= htmlspecialchars($request['major'] ?? 'Student') ?></p>
                                    </div>
                                </div>
                                <div class="request-actions">
                                    <button class="btn btn-accept" onclick="acceptRequest(<?= $request['request_id'] ?>, '<?= htmlspecialchars($request['mentee_name']) ?>')">
                                        Accept
                                    </button>
                                    <button class="btn btn-decline" onclick="declineRequest(<?= $request['request_id'] ?>)">
                                        Decline
                                    </button>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="empty-state">
                            <div class="empty-icon">📭</div>
                            <p>No pending requests at the moment</p>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Upcoming Sessions -->
                <div class="card" style="margin-top: 2rem;">
                    <h2 class="card-title">Upcoming Sessions</h2>
                    
                    <?php if (isset($data['upcoming_sessions']) && count($data['upcoming_sessions']) > 0): ?>
                        <?php foreach ($data['upcoming_sessions'] as $session): ?>
                            <div class="session-item">
                                <img 
                                    src="<?= !empty($session['profile_picture_url']) ? htmlspecialchars($session['profile_picture_url']) : 'https://i.pravatar.cc/150?img=' . rand(1, 70) ?>" 
                                    alt="<?= htmlspecialchars($session['mentee_name']) ?>" 
                                    class="session-avatar"
                                >
                                <div class="session-details">
                                    <h4><?= htmlspecialchars($session['mentee_name']) ?></h4>
                                    <p><?= date('F j, Y · g:i A', strtotime($session['scheduled_time'])) ?></p>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="empty-state">
                            <div class="empty-icon">📅</div>
                            <p>No upcoming sessions scheduled</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Right Column - Impact Stats -->
            <div class="stats-card">
                <h2 class="card-title">Impact Stats</h2>
                
                <div class="stat-item">
                    <div class="stat-label">Mentees Helped</div>
                    <div class="stat-value"><?= isset($data['stats']['total_mentees']) ? $data['stats']['total_mentees'] : 0 ?></div>
                </div>
                
                <div class="stat-item">
                    <div class="stat-label">Hours Mentored</div>
                    <div class="stat-value"><?= isset($data['stats']['total_hours']) ? $data['stats']['total_hours'] : 0 ?></div>
                </div>
                
                <div class="stat-item">
                    <div class="stat-label">Average Rating</div>
                    <div class="stat-value"><?= isset($data['stats']['average_rating']) ? number_format($data['stats']['average_rating'], 1) : '0.0' ?></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Time Slots Modal -->
    <div id="timeSlotsModal" class="modal">
        <div class="modal-dialog">
            <div class="modal-header">
                <h3 class="modal-title">Propose Time Slots</h3>
                <button class="modal-close" onclick="closeModal()">&times;</button>
            </div>
            <div class="modal-body">
                <p style="color: var(--text-light); margin-bottom: 1.5rem;">
                    Offer 2-4 time slots for <strong id="menteeName"></strong> to choose from.
                </p>
                <form id="timeSlotsForm">
                    <input type="hidden" id="requestId" name="request_id">
                    <div class="time-slots" id="timeSlotsContainer">
                        <div class="time-slot-item">
                            <div class="form-group" style="flex: 1; margin: 0;">
                                <input type="datetime-local" class="form-control" name="time_slots[]" required>
                            </div>
                        </div>
                    </div>
                    <button type="button" class="btn-add-slot" onclick="addTimeSlot()">+ Add Another Slot</button>
                </form>
            </div>
            <div class="modal-footer">
                <button class="btn btn-decline" onclick="closeModal()">Cancel</button>
                <button class="btn btn-accept" onclick="submitTimeSlots()">Send Offer</button>
            </div>
        </div>
    </div>

    <script>
        let slotCount = 1;

        function acceptRequest(requestId, menteeName) {
            document.getElementById('requestId').value = requestId;
            document.getElementById('menteeName').textContent = menteeName;
            document.getElementById('timeSlotsModal').classList.add('show');
        }

        function declineRequest(requestId) {
            if (confirm('Are you sure you want to decline this request?')) {
                fetch('<?= URLROOT ?>/mentorships/declineRequest', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ request_id: requestId })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
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
            document.getElementById('timeSlotsContainer').innerHTML = `
                <div class="time-slot-item">
                    <div class="form-group" style="flex: 1; margin: 0;">
                        <input type="datetime-local" class="form-control" name="time_slots[]" required>
                    </div>
                </div>
            `;
            slotCount = 1;
        }

        function addTimeSlot() {
            if (slotCount >= 4) {
                alert('You can add maximum 4 time slots');
                return;
            }
            
            const container = document.getElementById('timeSlotsContainer');
            const slotHtml = `
                <div class="time-slot-item">
                    <div class="form-group" style="flex: 1; margin: 0;">
                        <input type="datetime-local" class="form-control" name="time_slots[]" required>
                    </div>
                    <button type="button" class="btn-remove-slot" onclick="removeTimeSlot(this)">Remove</button>
                </div>
            `;
            container.insertAdjacentHTML('beforeend', slotHtml);
            slotCount++;
        }

        function removeTimeSlot(button) {
            button.closest('.time-slot-item').remove();
            slotCount--;
        }

        function submitTimeSlots() {
            const form = document.getElementById('timeSlotsForm');
            const formData = new FormData(form);
            const timeSlots = formData.getAll('time_slots[]');
            
            if (timeSlots.length < 2) {
                alert('Please provide at least 2 time slots');
                return;
            }

            // Validate all slots are in the future
            const now = new Date();
            
            for (let slot of timeSlots) {
                const slotDate = new Date(slot);
                if (slotDate <= now) {
                    alert('All time slots must be in the future');
                    return;
                }
            }

            const requestId = document.getElementById('requestId').value;
            
            fetch('<?= URLROOT ?>/mentorships/proposeTimeSlots', {
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
                    location.reload();
                } else {
                    alert('Error proposing time slots. Please try again.');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error proposing time slots. Please try again.');
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
    $footerFile = APPROOT . '/views/actors/alumini/Afooter.php';
    if (file_exists($footerFile)) {
        include $footerFile;
    }
    ?>
</body>
</html>
