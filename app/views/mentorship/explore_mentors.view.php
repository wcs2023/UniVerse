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
        .visually-hidden {
            position: absolute; width: 1px; height: 1px; padding: 0; margin: -1px;
            overflow: hidden; clip: rect(0,0,0,0); white-space: nowrap; border: 0;
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

    <div class="ms-container">
        <!-- Breadcrumb -->
        <nav class="ms-breadcrumb" aria-label="Breadcrumb">
            <a href="<?= BASE_URL ?>/umentorships">My Mentorships</a> › Explore Mentors
        </nav>
        
        <div class="ms-page-header" style="display: block;">
            <h1 class="ms-page-title">Explore Mentors</h1>
            <p style="color: #4b5563; margin-top: 0.5rem;">Find and book sessions with experienced alumni mentors</p>
        </div>

        <!-- Filter Bar -->
        <div class="ms-filter-bar">
            <label for="searchInput" class="visually-hidden">Search mentors</label>
            <input type="text" class="ms-search-input" id="searchInput" placeholder="Search by name, skills, company..." aria-label="Search mentors by name, skills, or company">
            <label for="industryFilter" class="visually-hidden">Filter by industry</label>
            <select class="ms-filter-select" id="industryFilter" aria-label="Filter mentors by industry">
                <option value="">All Industries</option>
                <option value="tech">Technology</option>
                <option value="finance">Finance</option>
                <option value="healthcare">Healthcare</option>
                <option value="education">Education</option>
                <option value="other">Other</option>
            </select>
        </div>

        <!-- Mentors Grid -->
        <div class="ms-mentors-grid" id="mentorsGrid">
            <?php if (isset($data['mentors']) && count($data['mentors']) > 0): ?>
                <?php foreach ($data['mentors'] as $mentor): ?>
                    <div class="ms-mentor-card" data-mentor-id="<?= $mentor['mentor_id'] ?>">
                        <div class="ms-mentor-header">
                            <img src="<?= !empty($mentor['profile_picture']) ? BASE_URL . htmlspecialchars($mentor['profile_picture']) : BASE_URL . '/assets/images/default-avatar.svg' ?>"
                                alt="<?= htmlspecialchars($mentor['name'] ?? 'Mentor') ?>"
                                class="ms-mentor-avatar"
                                onerror="this.src='<?= BASE_URL ?>/assets/images/U.png'">
                            <h3 class="ms-mentor-name"><?= htmlspecialchars($mentor['name'] ?? 'Anonymous Mentor') ?></h3>
                            <p class="ms-mentor-title"><?= htmlspecialchars($mentor['current_job_title'] ?? 'Professional') ?></p>
                        </div>
                        
                        <div class="ms-mentor-body">
                            <?php if (!empty($mentor['current_company'])): ?>
                                <div class="ms-mentor-company">
                                    <?= htmlspecialchars($mentor['current_company']) ?>
                                </div>
                            <?php endif; ?>
                            
                            <?php if (!empty($mentor['skills_experience'])): ?>
                                <div class="ms-mentor-skills">
                                    <?php 
                                    $skills = array_slice(explode(',', $mentor['skills_experience']), 0, 3);
                                    foreach ($skills as $skill): 
                                    ?>
                                        <span class="ms-skill-tag"><?= htmlspecialchars(trim($skill)) ?></span>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>
                            
                            <?php if (!empty($mentor['rating'])): ?>
                                <div class="ms-mentor-rating">
                                    <span class="ms-rating-stars"><?= str_repeat('&#9733;', round($mentor['rating'])) . str_repeat('&#9734;', 5 - round($mentor['rating'])) ?></span>
                                    <span class="rating-count">(<?= $mentor['review_count'] ?? 0 ?> reviews)</span>
                                </div>
                            <?php endif; ?>
                            
                            <div class="ms-availability-count" style="<?= ($mentor['available_slots'] ?? 0) == 0 ? 'color: #999;' : '' ?>">
                                <?php if (($mentor['available_slots'] ?? 0) > 0): ?>
                                    <?= $mentor['available_slots'] ?> slots available
                                <?php else: ?>
                                    No slots available currently
                                <?php endif; ?>
                            </div>
                            
                            <button class="ms-btn ms-btn-view" onclick="viewMentor(<?= $mentor['mentor_id'] ?>)">
                                <?= ($mentor['available_slots'] ?? 0) > 0 ? 'View & Book' : 'View Profile' ?>
                            </button>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="ms-empty-state">
                    <div class="ms-empty-icon ms-empty-icon--css">?</div>
                    <h3>No Mentors Available</h3>
                    <p>There are currently no active mentors. Please check back later!</p>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Book Slot Modal -->
    <div id="bookModal" class="ms-modal" role="dialog" aria-modal="true" aria-labelledby="bookModalTitle">
        <div class="ms-modal-dialog" style="max-width: 600px;">
            <div class="ms-modal-header">
                <h3 id="bookModalTitle" style="margin: 0;">Book a Session</h3>
                <button class="ms-close-modal" onclick="closeBookModal()" aria-label="Close booking dialog">&times;</button>
            </div>
            <div class="ms-modal-body">
                <div id="mentorInfo" style="text-align: center; margin-bottom: 1.5rem;">
                    <img id="modalMentorAvatar" src="" alt="Mentor profile picture" style="width: 80px; height: 80px; border-radius: 50%; margin-bottom: 0.5rem;" onerror="this.src='<?= BASE_URL ?>/assets/images/U.png'">
                    <h4 id="modalMentorName" style="margin: 0;"></h4>
                    <p id="modalMentorTitle" style="color: #4b5563; margin: 0.25rem 0;"></p>
                </div>
                
                <h4 style="margin-bottom: 1rem;">Select a Time Slot:</h4>
                <div class="ms-slots-container" id="slotsContainer" role="radiogroup" aria-label="Available time slots">
                    <p style="text-align: center; color: #4b5563;">Loading available slots...</p>
                </div>
                
                <input type="hidden" id="selectedSlotId">
            </div>
            <div class="ms-modal-footer">
                <button class="ms-btn ms-btn-secondary" onclick="closeBookModal()">Cancel</button>
                <button class="ms-btn ms-btn-book" id="bookBtn" onclick="confirmBooking()" disabled aria-label="Confirm and book this session">
                    Book Session
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
            const cards = document.querySelectorAll('.ms-mentor-card');
            
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
                            document.getElementById('modalMentorAvatar').src = data.mentor.profile_picture ? '<?= BASE_URL ?>' + data.mentor.profile_picture : '<?= BASE_URL ?>/assets/images/default-avatar.svg';
                            document.getElementById('modalMentorName').textContent = data.mentor.name || 'Mentor';
                            document.getElementById('modalMentorTitle').textContent = data.mentor.current_job_title || '';
                        }
                        
                        // Render available slots
                        renderSlots(data.slots);
                        
                        // Show modal
                        document.getElementById('bookModal').classList.add('show');
                    } else {
                        MentorshipSystem.showNotification('Error loading mentor details: ' + (data.message || 'Unknown error'), 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    MentorshipSystem.showNotification('Failed to load mentor details', 'error');
                });
        }

        function renderSlots(slots) {
            const container = document.getElementById('slotsContainer');
            
            if (!slots || slots.length === 0) {
                container.innerHTML = `
                    <div style="text-align: center; padding: 2rem; color: #6b7280;">
                        <p>No available slots at the moment.</p>
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
                    <label class="ms-slot-option" onclick="selectSlot(${slot.slot_id}, event)">
                        <input type="radio" name="slot" class="ms-slot-radio" value="${slot.slot_id}">
                        <div class="ms-slot-info">
                            <div class="ms-slot-date">${dateStr}</div>
                            <div class="ms-slot-time">${timeStr}</div>
                        </div>
                    </label>
                `;
            }).join('');
        }

        function selectSlot(slotId, event) {
            selectedSlotId = slotId;
            document.getElementById('selectedSlotId').value = slotId;
            
            // Update visual selection
            document.querySelectorAll('.ms-slot-option').forEach(el => {
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
                MentorshipSystem.showNotification('Please select a time slot.', 'error');
                return;
            }
            
            const btn = document.getElementById('bookBtn');
            btn.disabled = true;
            btn.textContent = 'Booking...';
            
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
                    MentorshipSystem.showNotification('Booking failed: ' + (data.message || 'Unknown error'), 'error');
                    btn.disabled = false;
                    btn.textContent = 'Book Session';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                MentorshipSystem.showNotification('Failed to book session. Please try again.', 'error');
                btn.disabled = false;
                btn.textContent = 'Book Session';
            });
        }

        // Close modal handled globally by mentorship.js (outside click + Escape key)
    </script>
    <script src="<?= ROOT ?>/js/mentorship.js"></script>

    <?php include __DIR__ . '/../layout/footer.php'; ?>
</body>
</html>
