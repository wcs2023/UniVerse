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
    <title>Explore Mentors - UniVerse</title>
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
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 1rem;
        }
        
        .page-header {
            margin-bottom: 1.5rem;
        }
        
        .breadcrumb {
            margin-bottom: 0.5rem;
            font-size: 0.875rem;
        }
        
        .breadcrumb a { color: #7c3aed; text-decoration: none; }
        .breadcrumb a:hover { text-decoration: underline; }
        
        .page-title {
            font-size: 1.75rem;
            font-weight: 700;
            color: #1f2937;
            margin: 0;
        }
        
        /* Search/Filter Bar */
        .filter-bar {
            background: white;
            border-radius: 12px;
            padding: 1rem;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            margin-bottom: 1.5rem;
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
        }
        
        .search-input {
            flex: 1;
            min-width: 200px;
            padding: 0.75rem 1rem;
            border: 2px solid #e5e7eb;
            border-radius: 8px;
            font-size: 1rem;
        }
        
        .search-input:focus {
            border-color: #7c3aed;
            outline: none;
        }
        
        .filter-select {
            padding: 0.75rem 1rem;
            border: 2px solid #e5e7eb;
            border-radius: 8px;
            min-width: 150px;
        }
        
        /* Mentors Grid */
        .mentors-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
            gap: 1.5rem;
        }
        
        .mentor-card {
            background: white;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
            transition: transform 0.2s, box-shadow 0.2s;
        }
        
        .mentor-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 24px rgba(0,0,0,0.12);
        }
        
        .mentor-header {
            background: linear-gradient(135deg, #7c3aed 0%, #a855f7 100%);
            padding: 1.5rem;
            text-align: center;
            color: white;
            position: relative;
        }
        
        .mentor-avatar {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            border: 3px solid white;
            object-fit: cover;
            margin-bottom: 0.75rem;
        }
        
        .mentor-name {
            font-size: 1.25rem;
            font-weight: 600;
            margin: 0 0 0.25rem 0;
        }
        
        .mentor-title {
            font-size: 0.875rem;
            opacity: 0.9;
            margin: 0;
        }
        
        .mentor-body {
            padding: 1.25rem;
        }
        
        .mentor-company {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: #4b5563;
            font-size: 0.875rem;
            margin-bottom: 1rem;
        }
        
        .mentor-skills {
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
            margin-bottom: 1rem;
        }
        
        .skill-tag {
            background: #f3e8ff;
            color: #7c3aed;
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 500;
        }
        
        .availability-count {
            background: #d1fae5;
            color: #065f46;
            padding: 0.5rem 1rem;
            border-radius: 8px;
            text-align: center;
            margin-bottom: 1rem;
        }
        
        .btn {
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            border: none;
            width: 100%;
            transition: all 0.2s;
        }
        
        .btn-view {
            background: linear-gradient(135deg, #7c3aed 0%, #a855f7 100%);
            color: white;
        }
        
        .btn-view:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(124, 58, 237, 0.4);
        }
        
        /* Rating */
        .mentor-rating {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 1rem;
        }
        
        .stars { color: #fbbf24; }
        .rating-count { color: #6b7280; font-size: 0.875rem; }
        
        /* Empty state */
        .empty-state {
            text-align: center;
            padding: 4rem 2rem;
            color: #4b5563;
            grid-column: 1 / -1;
        }
        
        .empty-icon { font-size: 4rem; margin-bottom: 1rem; }
        
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
            max-width: 600px;
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
        }
        
        /* Slot Selection */
        .slots-container {
            display: grid;
            gap: 0.75rem;
            max-height: 400px;
            overflow-y: auto;
        }
        
        .slot-option {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 1rem;
            border: 2px solid #e5e7eb;
            border-radius: 12px;
            cursor: pointer;
            transition: all 0.2s;
        }
        
        .slot-option:hover {
            border-color: #7c3aed;
            background: #f9fafb;
        }
        
        .slot-option.selected {
            border-color: #10b981;
            background: #d1fae5;
        }
        
        .slot-radio {
            width: 24px;
            height: 24px;
            accent-color: #10b981;
        }
        
        .slot-info {
            flex: 1;
        }
        
        .slot-date {
            font-weight: 600;
            color: #1f2937;
        }
        
        .slot-time {
            color: #6b7280;
            font-size: 0.875rem;
        }
        
        .btn-secondary { background: #f3f4f6; color: #374151; }
        .btn-secondary:hover { background: #e5e7eb; }
        
        .btn-book {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
        }
        
        .btn-book:hover {
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.4);
        }
        
        .btn-book:disabled {
            background: #e5e7eb;
            color: #9ca3af;
            cursor: not-allowed;
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

    <div class="container">
        <!-- Breadcrumb -->
        <div class="breadcrumb">
            <a href="<?= BASE_URL ?>/umentorships">My Mentorships</a> › Explore Mentors
        </div>
        
        <div class="page-header">
            <h1 class="page-title">🧭 Explore Mentors</h1>
            <p style="color: #4b5563; margin-top: 0.5rem;">Find and book sessions with experienced alumni mentors</p>
        </div>

        <!-- Filter Bar -->
        <div class="filter-bar">
            <input type="text" class="search-input" id="searchInput" placeholder="🔍 Search by name, skills, company...">
            <select class="filter-select" id="industryFilter">
                <option value="">All Industries</option>
                <option value="tech">Technology</option>
                <option value="finance">Finance</option>
                <option value="healthcare">Healthcare</option>
                <option value="education">Education</option>
                <option value="other">Other</option>
            </select>
        </div>

        <!-- Mentors Grid -->
        <div class="mentors-grid" id="mentorsGrid">
            <?php if (isset($data['mentors']) && count($data['mentors']) > 0): ?>
                <?php foreach ($data['mentors'] as $mentor): ?>
                    <div class="mentor-card" data-mentor-id="<?= $mentor['mentor_id'] ?>">
                        <div class="mentor-header">
                            <img src="<?= !empty($mentor['profile_picture']) ? htmlspecialchars($mentor['profile_picture']) : 'https://i.pravatar.cc/150?img=' . rand(1, 70) ?>"
                                alt="<?= htmlspecialchars($mentor['name'] ?? 'Mentor') ?>"
                                class="mentor-avatar">
                            <h3 class="mentor-name"><?= htmlspecialchars($mentor['name'] ?? 'Anonymous Mentor') ?></h3>
                            <p class="mentor-title"><?= htmlspecialchars($mentor['current_job_title'] ?? 'Professional') ?></p>
                        </div>
                        
                        <div class="mentor-body">
                            <?php if (!empty($mentor['current_company'])): ?>
                                <div class="mentor-company">
                                    🏢 <?= htmlspecialchars($mentor['current_company']) ?>
                                </div>
                            <?php endif; ?>
                            
                            <?php if (!empty($mentor['skills_experience'])): ?>
                                <div class="mentor-skills">
                                    <?php 
                                    $skills = array_slice(explode(',', $mentor['skills_experience']), 0, 3);
                                    foreach ($skills as $skill): 
                                    ?>
                                        <span class="skill-tag"><?= htmlspecialchars(trim($skill)) ?></span>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>
                            
                            <?php if (!empty($mentor['rating'])): ?>
                                <div class="mentor-rating">
                                    <span class="stars"><?= str_repeat('★', round($mentor['rating'])) . str_repeat('☆', 5 - round($mentor['rating'])) ?></span>
                                    <span class="rating-count">(<?= $mentor['review_count'] ?? 0 ?> reviews)</span>
                                </div>
                            <?php endif; ?>
                            
                            <div class="availability-count">
                                ✅ <?= $mentor['available_slots'] ?? 0 ?> slots available
                            </div>
                            
                            <button class="btn btn-view" onclick="viewMentor(<?= $mentor['mentor_id'] ?>)">
                                View & Book
                            </button>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="empty-state">
                    <div class="empty-icon">😕</div>
                    <h3>No Mentors Available</h3>
                    <p>There are currently no mentors with available slots. Please check back later!</p>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Book Slot Modal -->
    <div id="bookModal" class="modal" role="dialog" aria-modal="true" aria-labelledby="bookModalTitle">
        <div class="modal-dialog">
            <div class="modal-header">
                <h3 id="bookModalTitle" style="margin: 0;"><span aria-hidden="true">📅</span> Book a Session</h3>
                <button class="close-modal" onclick="closeBookModal()" aria-label="Close booking dialog"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <div id="mentorInfo" style="text-align: center; margin-bottom: 1.5rem;">
                    <img id="modalMentorAvatar" src="" alt="Mentor profile picture" style="width: 80px; height: 80px; border-radius: 50%; margin-bottom: 0.5rem;">
                    <h4 id="modalMentorName" style="margin: 0;"></h4>
                    <p id="modalMentorTitle" style="color: #4b5563; margin: 0.25rem 0;"></p>
                </div>
                
                <h4 style="margin-bottom: 1rem;">Select a Time Slot:</h4>
                <div class="slots-container" id="slotsContainer" role="radiogroup" aria-label="Available time slots">
                    <p style="text-align: center; color: #4b5563;">Loading available slots...</p>
                </div>
                
                <input type="hidden" id="selectedSlotId">
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" onclick="closeBookModal()">Cancel</button>
                <button class="btn btn-book" id="bookBtn" onclick="confirmBooking()" disabled aria-label="Confirm and book this session">
                    <span aria-hidden="true">🔒</span> Book Session
                </button>
            </div>
        </div>
    </div>

    <script>
        let currentMentorId = null;
        let selectedSlotId = null;

        // Search functionality
        document.getElementById('searchInput').addEventListener('input', function() {
            filterMentors();
        });
        
        document.getElementById('industryFilter').addEventListener('change', function() {
            filterMentors();
        });

        function filterMentors() {
            const searchTerm = document.getElementById('searchInput').value.toLowerCase();
            const cards = document.querySelectorAll('.mentor-card');
            
            cards.forEach(card => {
                const text = card.textContent.toLowerCase();
                if (text.includes(searchTerm)) {
                    card.style.display = '';
                } else {
                    card.style.display = 'none';
                }
            });
        }

        // View mentor and show booking modal
        function viewMentor(mentorId) {
            currentMentorId = mentorId;
            
            // Fetch mentor details and available slots
            fetch(`<?= BASE_URL ?>/umentorships/getAvailableSlots?mentor_id=${mentorId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Update modal with mentor info
                        if (data.mentor) {
                            document.getElementById('modalMentorAvatar').src = data.mentor.profile_picture || 'https://i.pravatar.cc/150';
                            document.getElementById('modalMentorName').textContent = data.mentor.name || 'Mentor';
                            document.getElementById('modalMentorTitle').textContent = data.mentor.current_job_title || '';
                        }
                        
                        // Render available slots
                        renderSlots(data.slots);
                        
                        // Show modal
                        document.getElementById('bookModal').classList.add('show');
                    } else {
                        alert('Error loading mentor details: ' + (data.message || 'Unknown error'));
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Failed to load mentor details');
                });
        }

        function renderSlots(slots) {
            const container = document.getElementById('slotsContainer');
            
            if (!slots || slots.length === 0) {
                container.innerHTML = `
                    <div style="text-align: center; padding: 2rem; color: #6b7280;">
                        <p>😕 No available slots at the moment.</p>
                        <p style="font-size: 0.875rem;">Check back later or try another mentor.</p>
                    </div>
                `;
                return;
            }
            
            container.innerHTML = slots.map(slot => {
                const date = new Date(slot.slot_datetime);
                const dateStr = date.toLocaleDateString('en-US', { weekday: 'long', month: 'long', day: 'numeric', year: 'numeric' });
                const timeStr = date.toLocaleTimeString('en-US', { hour: 'numeric', minute: '2-digit', hour12: true });
                
                return `
                    <label class="slot-option" onclick="selectSlot(${slot.slot_id})">
                        <input type="radio" name="slot" class="slot-radio" value="${slot.slot_id}">
                        <div class="slot-info">
                            <div class="slot-date">📅 ${dateStr}</div>
                            <div class="slot-time">🕐 ${timeStr}</div>
                        </div>
                    </label>
                `;
            }).join('');
        }

        function selectSlot(slotId) {
            selectedSlotId = slotId;
            document.getElementById('selectedSlotId').value = slotId;
            
            // Update visual selection
            document.querySelectorAll('.slot-option').forEach(el => {
                el.classList.remove('selected');
            });
            event.currentTarget.classList.add('selected');
            
            // Enable book button
            document.getElementById('bookBtn').disabled = false;
        }

        function closeBookModal() {
            document.getElementById('bookModal').classList.remove('show');
            selectedSlotId = null;
            document.getElementById('bookBtn').disabled = true;
        }

        function confirmBooking() {
            if (!selectedSlotId) {
                alert('Please select a time slot.');
                return;
            }
            
            const btn = document.getElementById('bookBtn');
            btn.disabled = true;
            btn.textContent = '⏳ Booking...';
            
            fetch('<?= BASE_URL ?>/umentorships/bookSlot', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ slot_id: selectedSlotId })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.location.href = '<?= BASE_URL ?>/umentorships?success=booked';
                } else {
                    alert('Booking failed: ' + (data.message || 'Unknown error'));
                    btn.disabled = false;
                    btn.textContent = '🔒 Book Session';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Failed to book session. Please try again.');
                btn.disabled = false;
                btn.textContent = '🔒 Book Session';
            });
        }

        // Close modal on outside click
        document.getElementById('bookModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeBookModal();
            }
        });
    </script>
    <script src="<?= ROOT ?>/js/mentorship.js"></script>

    <?php include __DIR__ . '/../layout/footer.php'; ?>
</body>
</html>
