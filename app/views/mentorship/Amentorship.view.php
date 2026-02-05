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
        
        .dashboard-grid {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 2rem;
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 1rem;
        }
        
        @media (max-width: 900px) {
            .dashboard-grid { grid-template-columns: 1fr; }
        }
        
        .dashboard-card {
            background: white;
            border-radius: 16px;
            padding: 1.5rem;
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
            margin-bottom: 1.5rem;
        }
        
        .card-title {
            font-size: 1.25rem;
            font-weight: 600;
            margin-bottom: 1rem;
            color: #1f2937;
        }
        
        .page-title {
            font-size: 1.75rem;
            font-weight: 700;
            margin-bottom: 1.5rem;
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
        
        /* Availability Calendar Styles */
        .availability-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
        }
        
        .btn-add-availability {
            background: linear-gradient(135deg, #7c3aed 0%, #a855f7 100%);
            color: white;
            border: none;
            padding: 0.75rem 1.25rem;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: transform 0.2s, box-shadow 0.2s;
        }
        
        .btn-add-availability:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(124, 58, 237, 0.4);
        }
        
        .slots-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 1rem;
        }
        
        .slot-card {
            background: #f5f3ff;
            border: 2px solid #e9d5ff;
            border-radius: 12px;
            padding: 1rem;
            position: relative;
            transition: all 0.2s;
        }
        
        .slot-card:hover {
            border-color: #7c3aed;
            box-shadow: 0 2px 8px rgba(124, 58, 237, 0.2);
        }
        
        .slot-card.booked {
            background: #d1fae5;
            border-color: #10b981;
        }
        
        .slot-date {
            font-weight: 600;
            color: #1f2937;
            font-size: 0.95rem;
        }
        
        .slot-time {
            color: #4b5563;
            font-size: 0.875rem;
            margin-top: 0.25rem;
        }
        
        .slot-status {
            font-size: 0.75rem;
            padding: 0.25rem 0.5rem;
            border-radius: 20px;
            margin-top: 0.5rem;
            display: inline-block;
        }
        
        .slot-status.available {
            background: #eff6ff;
            color: #1e40af;
        }
        
        .slot-status.booked {
            background: #d1fae5;
            color: #065f46;
        }
        
        .remove-slot-btn {
            position: absolute;
            top: 0.5rem;
            right: 0.5rem;
            background: #fee2e2;
            color: #dc2626;
            border: none;
            border-radius: 50%;
            width: 44px;
            height: 44px;
            min-width: 44px;
            min-height: 44px;
            font-size: 1.25rem;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            transition: opacity 0.2s;
        }

        .remove-slot-btn:focus {
            opacity: 1;
            outline: 2px solid #dc2626;
            outline-offset: 2px;
        }
        
        .slot-card:hover .remove-slot-btn {
            opacity: 1;
        }
        
        .slot-card.booked .remove-slot-btn {
            display: none;
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
            width: 50px;
            height: 50px;
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
        
        /* Stats Card */
        .stats-card {
            background: white;
            border-radius: 16px;
            padding: 1.5rem;
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
        }
        
        .stat-item {
            padding: 1rem 0;
            border-bottom: 1px solid #e5e7eb;
        }
        
        .stat-item:last-child { border-bottom: none; }
        
        .stat-label { color: #4b5563; font-size: 0.875rem; }
        .stat-value { font-size: 1.5rem; font-weight: 700; color: #1f2937; }
        
        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 2rem;
            color: #4b5563;
        }
        
        .empty-icon { font-size: 3rem; margin-bottom: 1rem; }
        
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
            background: linear-gradient(135deg, #7c3aed 0%, #a855f7 100%);
            color: white;
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
            color: white;
            min-width: 44px;
            min-height: 44px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 8px;
        }

        .close-modal:hover {
            background: rgba(255, 255, 255, 0.2);
        }

        .close-modal:focus {
            outline: 2px solid white;
            outline-offset: 2px;
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
        
        .btn-primary:disabled {
            background: #9ca3af;
            cursor: not-allowed;
            transform: none;
            box-shadow: none;
        }
        
        .time-slot-input-group {
            background: #f9fafb;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            padding: 1rem;
            margin-bottom: 0.75rem;
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
        }
        
        .time-slot-input-group input:focus {
            border-color: #7c3aed;
            outline: none;
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
            margin-top: 0.5rem;
        }
        
        .add-slot-btn:hover { background: #f5f3ff; }
        
        .info-text {
            background: #eff6ff;
            border: 1px solid #bfdbfe;
            border-radius: 8px;
            padding: 0.75rem;
            color: #1e40af;
            font-size: 0.875rem;
            margin-bottom: 1rem;
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
    $navFile = APPROOT . '/views/actors/alumni/Anavbar.php';
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
                        case 'slots_added':
                            echo 'Availability slots added successfully! Students can now book these times.';
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

        <h1 class="page-title">Mentor Dashboard</h1>

        <div class="dashboard-grid">
            <!-- Left Column -->
            <div>
                <!-- Availability Management -->
                <div class="dashboard-card">
                    <div class="availability-header">
                        <h2 class="card-title"><span aria-hidden="true">📅</span> My Availability (Next 2 Weeks)</h2>
                        <button class="btn-add-availability" onclick="openAddSlotsModal()" aria-label="Add new availability slots">
                            + Add Slots
                        </button>
                    </div>

                    <div class="slots-grid">
                        <?php if (isset($data['availability_slots']) && count($data['availability_slots']) > 0): ?>
                            <?php foreach ($data['availability_slots'] as $slot): ?>
                                <?php 
                                    $slotDate = new DateTime($slot['slot_datetime']);
                                    $isBooked = !empty($slot['is_booked']) || $slot['is_booked'] == 1;
                                ?>
                                <div class="slot-card <?= $isBooked ? 'booked' : '' ?>" data-slot-id="<?= $slot['slot_id'] ?>">
                                    <div class="slot-date">📅 <?= $slotDate->format('D, M j') ?></div>
                                    <div class="slot-time">🕐 <?= $slotDate->format('g:i A') ?></div>
                                    <span class="slot-status <?= $isBooked ? 'booked' : 'available' ?>">
                                        <?= $isBooked ? '✓ Booked' : 'Available' ?>
                                    </span>
                                    <?php if (!$isBooked): ?>
                                        <button class="remove-slot-btn" onclick="removeSlot(<?= $slot['slot_id'] ?>)" aria-label="Remove this time slot"><span aria-hidden="true">&times;</span></button>
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="empty-state" style="grid-column: 1 / -1;">
                                <div class="empty-icon">📭</div>
                                <h3>No Availability Set</h3>
                                <p>Add your available time slots so students can book sessions with you.</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Upcoming Bookings -->
                <div class="dashboard-card">
                    <h2 class="card-title">🔒 Upcoming Bookings</h2>

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
                                    <img src="<?= !empty($booking['student_picture']) ? htmlspecialchars($booking['student_picture']) : 'https://i.pravatar.cc/150?img=' . rand(1, 70) ?>"
                                        alt="<?= htmlspecialchars($booking['student_name'] ?? 'Student') ?>" 
                                        class="booking-avatar">
                                    <div style="flex: 1;">
                                        <h4 style="margin: 0;"><?= htmlspecialchars($booking['student_name'] ?? 'Student') ?></h4>
                                        <p style="margin: 0; color: #065f46; font-size: 0.875rem;">
                                            <?= htmlspecialchars($booking['degree_program'] ?? 'Student') ?>
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
                                        <span style="color: #10b981; font-weight: 700;">🟢 Session In Progress</span>
                                    <?php elseif ($canJoin): ?>
                                        <span style="color: #f59e0b; font-weight: 600;">⏰ Starting very soon!</span>
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
                                            <?= $canJoin ? '🎥 Join Meeting' : '⏳ Wait for Session' ?>
                                        </button>
                                    <?php else: ?>
                                        <span class="btn btn-secondary" style="cursor: not-allowed; opacity: 0.7;">
                                            ⚠️ Meeting link unavailable
                                        </span>
                                    <?php endif; ?>
                                    
                                    <button class="btn btn-secondary" onclick="openCancelModal(<?= $booking['booking_id'] ?>)">
                                        Cancel Booking
                                    </button>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="empty-state">
                            <div class="empty-icon">📅</div>
                            <h3>No Upcoming Bookings</h3>
                            <p>When students book your available slots, they'll appear here.</p>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Completed Sessions -->
                <?php if (isset($data['completed_sessions']) && count($data['completed_sessions']) > 0): ?>
                    <div class="dashboard-card">
                        <h2 class="card-title">✅ Recent Completed Sessions</h2>
                        <?php foreach (array_slice($data['completed_sessions'], 0, 5) as $session): ?>
                            <?php $sessionDate = new DateTime($session['slot_datetime']); ?>
                            <div style="display: flex; align-items: center; gap: 1rem; padding: 0.75rem 0; border-bottom: 1px solid #e5e7eb;">
                                <img src="<?= !empty($session['student_picture']) ? htmlspecialchars($session['student_picture']) : 'https://i.pravatar.cc/150?img=' . rand(1, 70) ?>"
                                    alt="Student" style="width: 40px; height: 40px; border-radius: 50%;">
                                <div style="flex: 1;">
                                    <div style="font-weight: 500;"><?= htmlspecialchars($session['student_name'] ?? 'Student') ?></div>
                                    <div style="color: #6b7280; font-size: 0.875rem;"><?= $sessionDate->format('M j, Y') ?></div>
                                </div>
                                <?php if (!empty($session['rating'])): ?>
                                    <div style="color: #fbbf24;">
                                        <?= str_repeat('★', $session['rating']) . str_repeat('☆', 5 - $session['rating']) ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Right Column - Stats -->
            <div>
                <div class="stats-card">
                    <h2 class="card-title">📊 Impact Stats</h2>

                    <div class="stat-item">
                        <div class="stat-label">Total Sessions</div>
                        <div class="stat-value"><?= $data['stats']['total_sessions'] ?? 0 ?></div>
                    </div>

                    <div class="stat-item">
                        <div class="stat-label">Completed Sessions</div>
                        <div class="stat-value"><?= $data['stats']['completed_sessions'] ?? 0 ?></div>
                    </div>

                    <div class="stat-item">
                        <div class="stat-label">Average Rating</div>
                        <div class="stat-value">
                            <?= isset($data['stats']['average_rating']) ? number_format($data['stats']['average_rating'], 1) : '0.0' ?> ⭐
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Slots Modal -->
    <div id="addSlotsModal" class="modal">
        <div class="modal-dialog">
            <div class="modal-header">
                <h3>📅 Add Availability Slots</h3>
                <button class="close-modal" onclick="closeAddSlotsModal()" aria-label="Close add slots dialog"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <div class="info-text">
                    ℹ️ Add 1-hour time slots when you're available. Students can instantly book any slot (first-come-first-served).
                </div>

                <div id="slotsContainer">
                    <div class="time-slot-input-group">
                        <label>Slot 1</label>
                        <input type="datetime-local" class="slot-input" 
                               min="<?= date('Y-m-d\TH:i') ?>" 
                               max="<?= date('Y-m-d\TH:i', strtotime('+14 days')) ?>">
                    </div>
                </div>

                <button type="button" class="add-slot-btn" onclick="addSlotInput()">
                    + Add Another Slot
                </button>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" onclick="closeAddSlotsModal()">Cancel</button>
                <button class="btn btn-primary" onclick="submitSlots()">Save Availability</button>
            </div>
        </div>
    </div>

    <!-- Cancel Modal -->
    <div id="cancelModal" class="modal">
        <div class="modal-dialog">
            <div class="modal-header" style="background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);">
                <h3>Cancel Booking</h3>
                <button class="close-modal" onclick="closeCancelModal()" aria-label="Close cancel booking dialog"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="cancelBookingId">
                <p>Are you sure you want to cancel this booking? The student will be notified.</p>
                <div style="margin-top: 1rem;">
                    <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Reason for cancellation (required):</label>
                    <textarea id="cancelReason" class="cancel-reason-input" placeholder="Please provide a reason..." required></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" onclick="closeCancelModal()">Keep Booking</button>
                <button class="btn" style="background: #ef4444; color: white;" onclick="confirmCancel()">Cancel Booking</button>
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
                <div class="time-slot-input-group">
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
                <div class="time-slot-input-group">
                    <label>Slot ${slotCount}</label>
                    <input type="datetime-local" class="slot-input" 
                           min="${new Date().toISOString().slice(0, 16)}" 
                           max="${new Date(Date.now() + 14*24*60*60*1000).toISOString().slice(0, 16)}">
                </div>
            `;
            container.insertAdjacentHTML('beforeend', html);
        }

        function submitSlots() {
            const inputs = document.querySelectorAll('.slot-input');
            const slots = [];
            
            inputs.forEach(input => {
                if (input.value) {
                    slots.push(input.value);
                }
            });

            if (slots.length === 0) {
                alert('Please add at least one time slot.');
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
                    window.location.href = '<?= BASE_URL ?>/amentorships?success=slots_added';
                } else {
                    alert('Error: ' + (data.message || 'Failed to add slots'));
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred. Please try again.');
            });
        }

        function removeSlot(slotId) {
            if (!confirm('Remove this availability slot?')) return;

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
                    alert('Error: ' + (data.message || 'Failed to remove slot'));
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
                alert('Please provide a reason for cancellation.');
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
                    alert('Error: ' + (data.message || 'Failed to cancel booking'));
                }
            });
        }

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
