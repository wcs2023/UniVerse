<?php
// Define constants if not already defined
if (!defined('APPROOT')) {
    define('APPROOT', dirname(dirname(dirname(__FILE__))));
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($data['mentor']['full_name']) ?> - Mentor Profile</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/css/alumni.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/css/mentorship.css">
    <link rel="icon" type="image/png" href="<?= BASE_URL ?>/assets/images/U.png">
    <style>
        /* Page-specific styles using CSS variables from alumni.css */
        body { background-color: #a78bfa45; padding-top: 80px; }
        .visually-hidden {
            position: absolute; width: 1px; height: 1px; padding: 0; margin: -1px;
            overflow: hidden; clip: rect(0,0,0,0); white-space: nowrap; border: 0;
        }

        /* Back Button */
        .back-button {
            display: inline-flex; align-items: center; gap: 0.5rem;
            color: var(--primary-purple); text-decoration: none; font-weight: 500;
            margin-bottom: 1.5rem; transition: opacity 0.2s;
        }
        .back-button:hover { opacity: 0.8; }

        /* Profile Header Card */
        .profile-header {
            background: white; border-radius: 16px; padding: 2.5rem;
            margin-bottom: 2rem; box-shadow: 0 4px 6px rgba(0,0,0,0.05);
            display: flex; gap: 2rem; align-items: flex-start;
        }
        .profile-avatar {
            width: 150px; height: 150px; border-radius: 50%; object-fit: cover;
            border: 4px solid var(--primary-purple); flex-shrink: 0;
        }
        .profile-info { flex: 1; }
        .profile-name { font-size: 2rem; font-weight: 700; color: var(--text-dark); margin-bottom: 0.5rem; }
        .profile-title { font-size: 1.125rem; color: var(--text-light); margin-bottom: 1rem; }
        .profile-stats { display: flex; gap: 2rem; margin-top: 1.5rem; flex-wrap: wrap; }
        .profile-actions { display: flex; gap: 1rem; margin-top: 1.5rem; }

        /* Content Grid */
        .content-grid { display: grid; grid-template-columns: 2fr 1fr; gap: 2rem; }
        .content-card { background: white; border-radius: 12px; padding: 2rem; box-shadow: 0 2px 4px rgba(0,0,0,0.05); }
        .card-title {
            font-size: 1.25rem; font-weight: 700; color: var(--text-dark);
            margin-bottom: 1.5rem; padding-bottom: 0.75rem;
            border-bottom: 2px solid var(--primary-purple);
        }
        .info-grid { display: grid; gap: 1.5rem; }
        .info-item { display: flex; flex-direction: column; gap: 0.25rem; }
        .info-label { font-size: 0.875rem; font-weight: 600; color: var(--text-light); text-transform: uppercase; letter-spacing: 0.05em; }
        .info-value { font-size: 1rem; color: var(--text-dark); }
        .info-value a { color: var(--primary-purple); text-decoration: none; }
        .info-value a:hover { text-decoration: underline; }
        .bio-text { color: var(--text-dark); line-height: 1.8; white-space: pre-wrap; }
        .expertise-tags { display: flex; flex-wrap: wrap; gap: 0.5rem; margin-top: 1rem; }
        .expertise-tag { background: #f3f4f6; color: var(--text-dark); padding: 0.5rem 1rem; border-radius: 20px; font-size: 0.875rem; font-weight: 500; }
        .availability-badge { display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.5rem 1rem; border-radius: 20px; font-weight: 600; font-size: 0.875rem; }
        .availability-badge.available { background: #d1fae5; color: #065f46; }
        .availability-badge.unavailable { background: #fee2e2; color: #991b1b; }

        /* Alert Messages */
        .alert { padding: 1rem 1.5rem; border-radius: 8px; margin-bottom: 1.5rem; display: flex; align-items: center; gap: 0.75rem; }
        .alert-info { background: #dbeafe; color: #1e40af; border: 1px solid #93c5fd; }
        .alert-success { background: #d1fae5; color: #065f46; border: 1px solid #86efac; }

        /* Request Modal (uses alumni.css vars, not ms-modal system) */
        .modal { display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 1000; align-items: center; justify-content: center; }
        .modal.active { display: flex; }
        .modal-content { background: white; border-radius: 16px; width: 90%; max-width: 500px; padding: 0; overflow: hidden; }
        .modal-header { background: var(--primary-purple); color: white; padding: 1.5rem; display: flex; justify-content: space-between; align-items: center; }
        .modal-header h3 { margin: 0; font-size: 1.25rem; }
        .modal-close { background: none; border: none; color: white; font-size: 1.5rem; cursor: pointer; padding: 0; line-height: 1; min-width: 44px; min-height: 44px; }
        .modal-body { padding: 2rem; }
        .form-group { margin-bottom: 1.5rem; }
        .form-group label { display: block; font-weight: 600; margin-bottom: 0.5rem; color: var(--text-dark); }
        .form-group textarea { width: 100%; padding: 0.75rem; border: 1px solid var(--border-color); border-radius: 8px; font-family: inherit; font-size: 1rem; resize: vertical; min-height: 120px; }
        .form-group textarea:focus { outline: none; border-color: var(--primary-purple); box-shadow: 0 0 0 3px rgba(124, 58, 237, 0.1); }
        .modal-footer { padding: 1.5rem 2rem; background: #f9fafb; display: flex; justify-content: flex-end; gap: 1rem; }

        @media (max-width: 768px) {
            .profile-header { flex-direction: column; align-items: center; text-align: center; }
            .content-grid { grid-template-columns: 1fr; }
            .profile-stats { justify-content: center; }
            .profile-actions { flex-direction: column; }
        }
    </style>
</head>
<body>
    <?php
    // Include appropriate navigation based on user type
    if ($data['user_type'] === 'undergraduate') {
        $navFile = APPROOT . '/views/actors/undergraduate/Unavigation.view.php';
    } elseif ($data['user_type'] === 'alumni') {
        $navFile = APPROOT . '/views/actors/alumni/Anavbar.php';
    } else {
        $navFile = APPROOT . '/views/components/navigation.php';
    }
    
    if (file_exists($navFile)) {
        include $navFile;
    }
    ?>

    <div class="ms-container" style="padding: 2rem 1rem;">
        <!-- Breadcrumb Navigation -->
        <nav class="ms-breadcrumb" aria-label="Breadcrumb">
            <?php if ($data['user_type'] === 'undergraduate'): ?>
                <a href="<?= BASE_URL ?>/umentorships">My Mentorships</a> ›
                <a href="<?= BASE_URL ?>/umentorships/exploreMentors">Explore Mentors</a> ›
                <span aria-current="page"><?= htmlspecialchars($data['mentor']['full_name']) ?></span>
            <?php else: ?>
                <a href="<?= BASE_URL ?>/amentorships">Mentor Dashboard</a> ›
                <span aria-current="page"><?= htmlspecialchars($data['mentor']['full_name']) ?></span>
            <?php endif; ?>
        </nav>

        <a href="<?= BASE_URL ?>/umentorships/exploreMentors" class="back-button">
            ← Back to Mentors
        </a>

        <!-- Profile Header -->
        <div class="profile-header">
            <?php 
            $profilePic = !empty($data['mentor']['profile_picture']) 
                ? BASE_URL . $data['mentor']['profile_picture']
                : BASE_URL . '/assets/images/default-avatar.svg';
            ?>
            <img src="<?= $profilePic ?>" 
                 alt="<?= htmlspecialchars($data['mentor']['full_name']) ?>"
                 class="profile-avatar"
                 onerror="this.src='<?= BASE_URL ?>/assets/images/U.png'">
            
            <div class="profile-info">
                <h1 class="profile-name"><?= htmlspecialchars($data['mentor']['full_name']) ?></h1>
                <p class="profile-title">
                    <?= htmlspecialchars($data['mentor']['current_job_title'] ?? 'Mentor') ?>
                    <?php if (!empty($data['mentor']['current_company'])): ?>
                        at <?= htmlspecialchars($data['mentor']['current_company']) ?>
                    <?php endif; ?>
                </p>

                <?php if ($data['mentor']['is_active']): ?>
                    <span class="availability-badge available">
                        ✓ Available for Mentorship
                    </span>
                <?php else: ?>
                    <span class="availability-badge unavailable">
                        Currently Unavailable
                    </span>
                <?php endif; ?>

                <div class="profile-stats">
                    <div class="ms-stat-item" style="border-bottom: none; padding: 0;">
                        <span class="ms-stat-value" style="color: var(--primary-purple);"><?= $data['stats']['completed_sessions'] ?? 0 ?></span>
                        <span class="ms-stat-label">Sessions Completed</span>
                    </div>
                    <div class="ms-stat-item" style="border-bottom: none; padding: 0;">
                        <span class="ms-stat-value" style="color: var(--primary-purple);"><?= $data['stats']['active_mentees'] ?? 0 ?></span>
                        <span class="ms-stat-label">Active Mentees</span>
                    </div>
                    <div class="ms-stat-item" style="border-bottom: none; padding: 0;">
                        <span class="ms-stat-value" style="color: var(--primary-purple);"><?= $data['mentor']['max_mentees'] ?? 5 ?></span>
                        <span class="ms-stat-label">Max Mentees</span>
                    </div>
                </div>

                <?php if ($data['user_type'] === 'undergraduate'): ?>
                    <div class="profile-actions">
                        <?php if ($data['hasActiveRequest']): ?>
                            <button class="ms-btn ms-btn-primary" disabled>
                                Request Already Sent
                            </button>
                        <?php elseif (!$data['mentor']['is_active']): ?>
                            <button class="ms-btn ms-btn-primary" disabled>
                                Currently Unavailable
                            </button>
                        <?php else: ?>
                            <button class="ms-btn ms-btn-primary" onclick="openRequestModal()">
                                Send Mentorship Request
                            </button>
                        <?php endif; ?>
                        
                        <?php if (!empty($data['mentor']['linkedin_url'])): ?>
                            <a href="<?= htmlspecialchars($data['mentor']['linkedin_url']) ?>" 
                               target="_blank" 
                               class="ms-btn ms-btn-secondary">
                                LinkedIn
                            </a>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Content Grid -->
        <div class="content-grid">
            <!-- Left Column -->
            <div>
                <!-- About Section -->
                <div class="content-card">
                    <h2 class="card-title">About</h2>
                    <p class="bio-text">
                        <?= nl2br(htmlspecialchars($data['profile']->short_bio ?? $data['mentor']['skills_experience'] ?? 'No bio available.')) ?>
                    </p>
                </div>

                <!-- Expertise Section -->
                <?php if (!empty($data['mentor']['expertise_areas'])): ?>
                    <div class="content-card" style="margin-top: 2rem;">
                        <h2 class="card-title">Areas of Expertise</h2>
                        <div class="expertise-tags">
                            <?php 
                            $expertise = json_decode($data['mentor']['expertise_areas'], true);
                            if (is_array($expertise)):
                                foreach ($expertise as $area):
                            ?>
                                <span class="expertise-tag"><?= htmlspecialchars($area) ?></span>
                            <?php 
                                endforeach;
                            endif;
                            ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Right Column -->
            <div>
                <!-- Contact & Links -->
                <div class="content-card">
                    <h2 class="card-title">Contact & Links</h2>
                    <div class="info-grid">
                        <?php if (!empty($data['mentor']['email'])): ?>
                            <div class="info-item">
                                <span class="info-label ms-icon-label ms-icon-label--email">Email</span>
                                <span class="info-value">
                                    <a href="mailto:<?= htmlspecialchars($data['mentor']['email']) ?>">
                                        <?= htmlspecialchars($data['mentor']['email']) ?>
                                    </a>
                                </span>
                            </div>
                        <?php endif; ?>

                        <?php if (!empty($data['mentor']['linkedin_url'])): ?>
                            <div class="info-item">
                                <span class="info-label ms-icon-label ms-icon-label--link">LinkedIn</span>
                                <span class="info-value">
                                    <a href="<?= htmlspecialchars($data['mentor']['linkedin_url']) ?>" target="_blank">
                                        View Profile
                                    </a>
                                </span>
                            </div>
                        <?php endif; ?>

                        <?php if (!empty($data['mentor']['github_url'])): ?>
                            <div class="info-item">
                                <span class="info-label ms-icon-label ms-icon-label--github">GitHub</span>
                                <span class="info-value">
                                    <a href="<?= htmlspecialchars($data['mentor']['github_url']) ?>" target="_blank">
                                        View Profile
                                    </a>
                                </span>
                            </div>
                        <?php endif; ?>

                        <?php if (!empty($data['mentor']['portfolio_url'])): ?>
                            <div class="info-item">
                                <span class="info-label ms-icon-label ms-icon-label--web">Portfolio</span>
                                <span class="info-value">
                                    <a href="<?= htmlspecialchars($data['mentor']['portfolio_url']) ?>" target="_blank">
                                        View Website
                                    </a>
                                </span>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Education -->
                <div class="content-card" style="margin-top: 2rem;">
                    <h2 class="card-title">Education</h2>
                    <div class="info-grid">
                        <?php if (!empty($data['mentor']['university_name'])): ?>
                            <div class="info-item">
                                <span class="info-label ms-icon-label ms-icon-label--edu">University</span>
                                <span class="info-value"><?= htmlspecialchars($data['mentor']['university_name']) ?></span>
                            </div>
                        <?php endif; ?>

                        <?php if (!empty($data['mentor']['degree_program'])): ?>
                            <div class="info-item">
                                <span class="info-label ms-icon-label ms-icon-label--degree">Degree</span>
                                <span class="info-value"><?= htmlspecialchars($data['mentor']['degree_program']) ?></span>
                            </div>
                        <?php endif; ?>

                        <?php if (!empty($data['mentor']['graduation_year'])): ?>
                            <div class="info-item">
                                <span class="info-label ms-icon-label ms-icon-label--date">Graduated</span>
                                <span class="info-value"><?= htmlspecialchars($data['mentor']['graduation_year']) ?></span>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Request Modal -->
    <?php if ($data['user_type'] === 'undergraduate' && !$data['hasActiveRequest'] && $data['mentor']['is_active']): ?>
        <div id="requestModal" class="modal" role="dialog" aria-modal="true" aria-labelledby="requestModalTitle">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 id="requestModalTitle">Send Mentorship Request</h3>
                    <button class="modal-close" onclick="closeRequestModal()" aria-label="Close request dialog"><span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <form id="requestForm" onsubmit="sendRequest(event)">
                        <div class="form-group">
                            <label for="message">Message to <?= htmlspecialchars($data['mentor']['first_name']) ?> <span aria-hidden="true">*</span><span class="visually-hidden">required</span></label>
                            <textarea id="message" 
                                      name="message" 
                                      required
                                      aria-required="true"
                                      placeholder="Introduce yourself and explain why you'd like this person as your mentor..."></textarea>
                        </div>
                        <input type="hidden" name="mentor_id" value="<?= $data['mentor']['mentor_id'] ?>">
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="ms-btn ms-btn-secondary" onclick="closeRequestModal()">Cancel</button>
                    <button type="submit" form="requestForm" class="ms-btn ms-btn-primary">Send Request</button>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <?php include __DIR__ . '/../layout/footer.php'; ?>

    <script>
        function openRequestModal() {
            document.getElementById('requestModal').classList.add('active');
        }

        function closeRequestModal() {
            document.getElementById('requestModal').classList.remove('active');
        }

        async function sendRequest(event) {
            event.preventDefault();
            
            const form = event.target;
            const formData = new FormData(form);
            
            try {
                const response = await fetch('<?= BASE_URL ?>/umentorships/request', {
                    method: 'POST',
                    body: formData
                });
                
                const result = await response.json();
                
                if (result.success) {
                    MentorshipSystem.showNotification('Mentorship request sent successfully!', 'success');
                    setTimeout(() => { window.location.href = '<?= BASE_URL ?>/umentorships'; }, 1500);
                } else {
                    MentorshipSystem.showNotification('Error: ' + (result.message || 'Failed to send request'), 'error');
                }
            } catch (error) {
                console.error('Error:', error);
                MentorshipSystem.showNotification('An error occurred. Please try again.', 'error');
            }
        }

        // Close modal handled globally by mentorship.js
    </script>
    <script src="<?= ROOT ?>/js/mentorship.js"></script>
</body>
</html>
