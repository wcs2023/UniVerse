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
    <!-- <link rel="stylesheet" href="<?= BASE_URL ?>/css/alumni.css"> -->
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/styles.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/mentorship.css">
    
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
        .ms-back-btn {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            margin-bottom: 1rem;
            padding: 0.45rem 1rem;
            border: 2px solid #e5e7eb;
            border-radius: 999px;
            background: white;
            color: #4b5563;
            font-size: 0.9rem;
            font-weight: 500;
            text-decoration: none;
            transition: all 0.2s ease;
        }
        .ms-back-btn:hover {
            border-color: #6b46c1;
            color: #6b46c1;
            background: #f5f3ff;
        }
        .ms-mentor-expertise-tags {
            display: flex;
            flex-wrap: wrap;
            gap: 0.35rem;
            margin-bottom: 0.75rem;
        }
        .ms-expertise-tag {
            font-size: 0.72rem;
            font-weight: 600;
            padding: 0.2rem 0.6rem;
            border-radius: 20px;
            background: #ede9fe;
            color: #6d28d9;
            border: 1px solid #c4b5fd;
            white-space: nowrap;
        }
    </style>
</head>

<body style="margin-top: 2rem;">
    <?php
    // Include navigation
    $navFile = APPROOT . '/views/actors/undergraduate/Unavigation.view.php';
    if (file_exists($navFile)) {
        include $navFile;
    }
    ?>

    <div class="ms-container">
        <!-- Page Header -->
        <div class="ms-page-header" style="display: block;">
            <h1 class="ms-page-title">Explore Mentors</h1>
            <p style="color: #4b5563; margin-top: 0.5rem;">Find and book sessions with experienced alumni mentors</p>
        </div>

        <!-- Filter Bar -->
        <div class="ms-filter-bar">
            <label for="searchInput" class="visually-hidden">Search mentors</label>
            <input type="text" class="ms-search-input" id="searchInput" placeholder="Search by name, skills, company..." value="<?= htmlspecialchars($data['searchTerm'] ?? '') ?>" aria-label="Search mentors by name, skills, or company">

            <label for="expertiseFilter" class="visually-hidden">Filter by expertise</label>
            <select class="ms-filter-select" id="expertiseFilter" aria-label="Filter mentors by expertise">
                <option value="">All Expertise</option>
                <option value="Software Development" <?= (($data['expertise'] ?? '') === 'Software Development') ? 'selected' : '' ?>>Software Development</option>
                <option value="Cloud &amp; DevOps" <?= (($data['expertise'] ?? '') === 'Cloud & DevOps') ? 'selected' : '' ?>>Cloud &amp; DevOps</option>
                <option value="Cybersecurity" <?= (($data['expertise'] ?? '') === 'Cybersecurity') ? 'selected' : '' ?>>Cybersecurity</option>
                <option value="Data &amp; AI/ML" <?= (($data['expertise'] ?? '') === 'Data & AI/ML') ? 'selected' : '' ?>>Data &amp; AI/ML</option>
                <option value="UI/UX &amp; Product" <?= (($data['expertise'] ?? '') === 'UI/UX & Product') ? 'selected' : '' ?>>UI/UX &amp; Product</option>
                <option value="Networking &amp; Infra" <?= (($data['expertise'] ?? '') === 'Networking & Infra') ? 'selected' : '' ?>>Networking &amp; Infra</option>
                <option value="Database Systems" <?= (($data['expertise'] ?? '') === 'Database Systems') ? 'selected' : '' ?>>Database Systems</option>
                <option value="Embedded &amp; IoT" <?= (($data['expertise'] ?? '') === 'Embedded & IoT') ? 'selected' : '' ?>>Embedded &amp; IoT</option>
                <option value="QA &amp; Testing" <?= (($data['expertise'] ?? '') === 'QA & Testing') ? 'selected' : '' ?>>QA &amp; Testing</option>
                <option value="Computer Architecture" <?= (($data['expertise'] ?? '') === 'Computer Architecture') ? 'selected' : '' ?>>Computer Architecture</option>
                <option value="Open Source &amp; Tools" <?= (($data['expertise'] ?? '') === 'Open Source & Tools') ? 'selected' : '' ?>>Open Source &amp; Tools</option>
                <option value="Tech Career &amp; Interview Prep" <?= (($data['expertise'] ?? '') === 'Tech Career & Interview Prep') ? 'selected' : '' ?>>Tech Career &amp; Interview Prep</option>
            </select>
        </div>

        <!-- Mentors Grid -->
        <div class="ms-mentors-grid" id="mentorsGrid" style="min-height: 60vh; align-content: start;">
            <?php if (isset($data['mentors']) && count($data['mentors']) > 0): ?>
                <?php foreach ($data['mentors'] as $mentor): ?>
                    <?php
                        $mentorName = trim((string)($mentor['name'] ?? '')) ?: 'Anonymous Mentor';
                        $mentorRole = trim((string)($mentor['current_job_title'] ?? '')) ?: 'Role not provided';
                        $mentorCompany = trim((string)($mentor['current_company'] ?? '')) ?: 'Company not provided';
                        $mentorEmail = trim((string)($mentor['email'] ?? ''));
                        $mentorSlots = (int)($mentor['available_slots'] ?? 0);
                    ?>
                    <?php
                        $expertiseList = $mentor['expertise_array'] ?? [];
                        $expertiseAttr = htmlspecialchars(implode('|', array_map('strtolower', $expertiseList)));
                    ?>
                    <div class="ms-mentor-card" data-mentor-id="<?= $mentor['mentor_id'] ?>" data-expertise="<?= $expertiseAttr ?>">
                        <div class="ms-mentor-header">
                            <img src="<?= !empty($mentor['profile_picture']) ? BASE_URL . htmlspecialchars($mentor['profile_picture']) : BASE_URL . '/assets/images/U.png' ?>"
                                alt="<?= htmlspecialchars($mentorName) ?>"
                                class="ms-mentor-avatar"
                                onerror="this.onerror=null; this.src='<?= BASE_URL ?>/assets/images/U.png'">
                            <h3 class="ms-mentor-name"><?= htmlspecialchars($mentorName) ?></h3>
                        </div>

                        <div class="ms-mentor-body">
                            <?php if (!empty($expertiseList)): ?>
                                <div class="ms-mentor-expertise-tags">
                                    <?php foreach ($expertiseList as $exp): ?>
                                        <span class="ms-expertise-tag"><?= htmlspecialchars($exp) ?></span>
                                    <?php endforeach; ?>
                                </div>
                            <?php elseif (!empty($mentor['skills_experience'])): ?>
                                <div class="ms-mentor-skills ms-mentor-skills-top">
                                    <?php
                                    $skills = array_slice(explode(',', $mentor['skills_experience']), 0, 3);
                                    foreach ($skills as $skill):
                                    ?>
                                        <span class="ms-skill-tag"><?= htmlspecialchars(trim($skill)) ?></span>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>

                            <div class="ms-mentor-details">
                                <div class="ms-mentor-detail-row">
                                    <span class="ms-mentor-detail-label">Role</span>
                                    <span class="ms-mentor-detail-value"><?= htmlspecialchars($mentorRole) ?></span>
                                </div>
                                <div class="ms-mentor-detail-row">
                                    <span class="ms-mentor-detail-label">Company</span>
                                    <span class="ms-mentor-detail-value"><?= htmlspecialchars($mentorCompany) ?></span>
                                </div>
                            </div>

                            <?php if (!empty($mentor['rating'])): ?>
                                <div class="ms-mentor-rating">
                                    <span class="ms-rating-stars"><?= str_repeat('&#9733;', round($mentor['rating'])) . str_repeat('&#9734;', 5 - round($mentor['rating'])) ?></span>
                                    <span class="rating-count">(<?= $mentor['review_count'] ?? 0 ?> reviews)</span>
                                </div>
                            <?php endif; ?>

                            <div class="ms-availability-count" style="<?= $mentorSlots === 0 ? 'color: #999;' : '' ?>">
                                <?php if ($mentorSlots > 0): ?>
                                    <?= $mentorSlots ?> slots available
                                <?php else: ?>
                                    No slots available currently
                                <?php endif; ?>
                            </div>

                            <div style="display: grid; gap: 0.75rem; margin-top: 0.25rem;">
                                <button
                                    class="ms-btn ms-btn-book"
                                    onclick="viewMentor(<?= (int)$mentor['mentor_id'] ?>)"
                                    <?= $mentorSlots > 0 ? '' : 'disabled' ?>
                                >
                                    <?= $mentorSlots > 0 ? 'View Time &amp; Book' : 'No Slots Available' ?>
                                </button>
                                <button class="ms-btn ms-btn-view" onclick="goToMentorProfile(<?= (int)$mentor['user_id'] ?>)">
                                    View Profile
                                </button>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="ms-empty-state">
                    <div class="ms-empty-icon" aria-hidden="true">
                        <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="#a78bfa" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="11" cy="11" r="8" />
                            <line x1="21" y1="21" x2="16.65" y2="16.65" />
                            <path d="M11 8a3 3 0 0 0-3 3" />
                        </svg>
                    </div>
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

        document.getElementById('expertiseFilter').addEventListener('change', function() {
            filterMentors();
        });

        function filterMentors() {
            const searchTerm = document.getElementById('searchInput').value.toLowerCase().trim();
            const expertiseTerm = document.getElementById('expertiseFilter').value.toLowerCase().trim();
            const cards = document.querySelectorAll('.ms-mentor-card');
            let visible = 0;

            cards.forEach(card => {
                const text = card.textContent.toLowerCase();
                const cardExpertise = (card.dataset.expertise || '').toLowerCase(); // pipe-separated

                const matchesSearch = !searchTerm || text.includes(searchTerm);
                // Match expertise against the dedicated data attribute (exact category name)
                const matchesExpertise = !expertiseTerm ||
                    cardExpertise.split('|').some(e => e.trim() === expertiseTerm);

                if (matchesSearch && matchesExpertise) {
                    card.style.display = '';
                    visible++;
                } else {
                    card.style.display = 'none';
                }
            });

            // Show/hide empty state
            const grid = document.getElementById('mentorsGrid');
            let emptyState = grid.querySelector('.ms-filter-empty');
            if (visible === 0 && cards.length > 0) {
                if (!emptyState) {
                    emptyState = document.createElement('div');
                    emptyState.className = 'ms-empty-state ms-filter-empty';
                    emptyState.innerHTML = '<div class="ms-empty-icon" aria-hidden="true"><svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="#a78bfa" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8" /><line x1="21" y1="21" x2="16.65" y2="16.65" /></svg></div><h3>No mentors match your filter</h3><p>Try a different expertise or clear the filter.</p>';
                    grid.appendChild(emptyState);
                }
                emptyState.style.display = '';
            } else if (emptyState) {
                emptyState.style.display = 'none';
            }
        }

        filterMentors();

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
                            document.getElementById('modalMentorName').textContent = data.mentor.full_name || data.mentor.name || 'Mentor';
                            document.getElementById('modalMentorTitle').textContent = data.mentor.current_job_title || data.mentor.title || '';
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

        function goToMentorProfile(mentorUserId) {
            window.location.href = `<?= BASE_URL ?>/umentorships/viewMentor/${mentorUserId}`;
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
                    <label class="ms-slot-option" onclick="selectSlot(${slot.slot_id}, event)" style="cursor: pointer;">
                        <input type="radio" name="slot" class="ms-slot-radio" value="${slot.slot_id}" onchange="selectSlot(${slot.slot_id}, event)">
                        <div class="ms-slot-info">
                            <div class="ms-slot-date">${dateStr}</div>
                            <div class="ms-slot-time">${timeStr}</div>
                        </div>
                    </label>
                `;
            }).join('');
        }

        function selectSlot(slotId, event) {
            slotId = parseInt(slotId);
            event.preventDefault();
            event.stopPropagation();
            
            selectedSlotId = slotId;
            document.getElementById('selectedSlotId').value = slotId;
            
            // Check the radio button
            const radio = event.currentTarget.querySelector('input[type="radio"]');
            if (radio) {
                radio.checked = true;
            }
            
            // Update visual selection
            document.querySelectorAll('.ms-slot-option').forEach(el => {
                el.classList.remove('selected');
            });
            event.currentTarget.classList.add('selected');
            
            // Enable book button
            document.getElementById('bookBtn').disabled = false;
            console.log('Slot selected: ' + slotId);
        }

        function closeBookModal() {
            document.getElementById('bookModal').classList.remove('show');
            selectedSlotId = null;
            document.getElementById('bookBtn').disabled = true;
        }

        function confirmBooking() {
            console.log('confirmBooking called. selectedSlotId:', selectedSlotId);
            
            if (!selectedSlotId) {
                console.warn('No slot selected. selectedSlotId:', selectedSlotId);
                MentorshipSystem.showNotification('Please select a time slot.', 'error');
                return;
            }
            
            const btn = document.getElementById('bookBtn');
            btn.disabled = true;
            btn.textContent = 'Booking...';
            
            const bookingData = { slot_id: selectedSlotId };
            console.log('Sending booking request:', bookingData);
            
            fetch('<?= BASE_URL ?>/umentorships/bookSlot', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(bookingData)
            })
            .then(response => {
                console.log('Response received. Status:', response.status);
                return response.json();
            })
            .then(data => {
                console.log('Booking response:', data);
                if (data.success) {
                    window.location.href = '<?= BASE_URL ?>/umentorships?success=booked';
                } else {
                    MentorshipSystem.showNotification('Booking failed: ' + (data.message || 'Unknown error'), 'error');
                    btn.disabled = false;
                    btn.textContent = 'Book Session';
                }
            })
            .catch(error => {
                console.error('Booking error:', error);
                MentorshipSystem.showNotification('Failed to book session. Please try again.', 'error');
                btn.disabled = false;
                btn.textContent = 'Book Session';
            });
        }

        // Close modal handled globally by mentorship.js (outside click + Escape key)
    </script>
    <script src="<?= BASE_URL ?>/assets/js/mentorship.js"></script>

    <?php include __DIR__ . '/../layout/footer.php'; ?>
</body>
</html>
