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
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/alumni.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/mentorship.css">
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
                </div>

                <?php if ($data['user_type'] === 'undergraduate'): ?>
                    <div class="profile-actions">
                        <?php if (!$data['mentor']['is_active']): ?>
                            <button class="ms-btn ms-btn-primary" disabled>
                                Currently Unavailable
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
                <!-- Professional Background Section (About + Education Combined) -->
                <div class="content-card">
                    <h2 class="card-title">Professional Background</h2>
                    
                    <!-- About/Bio -->
                    <?php if (!empty($data['mentor']['skills_experience'])): ?>
                        <div style="margin-bottom: 2rem;">
                            <p class="bio-text"><?= nl2br(htmlspecialchars($data['mentor']['skills_experience'])) ?></p>
                        </div>
                    <?php else: ?>
                        <p class="bio-text" style="color: #9ca3af; margin-bottom: 2rem;">Professional background information not yet provided.</p>
                    <?php endif; ?>

                    <!-- Expertise -->
                    <?php if (!empty($data['mentor']['expertise_array'])): ?>
                        <div style="border-top: 1px solid #e5e7eb; padding-top: 1.5rem; margin-top: 1.5rem;">
                            <h3 style="font-size: 0.95rem; font-weight: 600; color: #1f2937; margin: 0 0 1rem 0; text-transform: uppercase; letter-spacing: 0.05em;">Expertise</h3>
                            <div class="expertise-tags">
                                <?php foreach ($data['mentor']['expertise_array'] as $expertise): ?>
                                    <span class="expertise-tag"><?= htmlspecialchars($expertise) ?></span>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endif; ?>

                    <!-- Education -->
                    <?php if (!empty($data['mentor']['university_name']) || !empty($data['mentor']['degree_program']) || !empty($data['mentor']['graduation_year'])): ?>
                        <div style="border-top: 1px solid #e5e7eb; padding-top: 1.5rem;">
                            <h3 style="font-size: 0.95rem; font-weight: 600; color: #1f2937; margin: 0 0 1rem 0; text-transform: uppercase; letter-spacing: 0.05em;">Education</h3>
                            <div class="contact-minimal" style="gap: 1rem;">
                                <?php if (!empty($data['mentor']['university_name'])): ?>
                                    <div class="contact-field">
                                        <span class="contact-label">University</span>
                                        <span class="contact-value"><?= htmlspecialchars($data['mentor']['university_name']) ?></span>
                                    </div>
                                <?php endif; ?>

                                <?php if (!empty($data['mentor']['degree_program'])): ?>
                                    <div class="contact-field">
                                        <span class="contact-label">Degree</span>
                                        <span class="contact-value"><?= htmlspecialchars($data['mentor']['degree_program']) ?></span>
                                    </div>
                                <?php endif; ?>

                                <?php if (!empty($data['mentor']['graduation_year'])): ?>
                                    <div class="contact-field">
                                        <span class="contact-label">Graduated</span>
                                        <span class="contact-value"><?= htmlspecialchars($data['mentor']['graduation_year']) ?></span>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Right Column -->
            <div>
                <!-- Contact & Links -->
                <div class="content-card">
                    <h2 class="card-title">Contact & Links</h2>
                    <div class="contact-minimal">
                        <div class="contact-field">
                            <span class="contact-label">Role</span>
                            <span class="contact-value"><?= htmlspecialchars($data['mentor']['current_job_title'] ?? 'Not provided') ?></span>
                        </div>

                        <div class="contact-field">
                            <span class="contact-label">Company</span>
                            <span class="contact-value"><?= htmlspecialchars($data['mentor']['current_company'] ?? 'Not provided') ?></span>
                        </div>

                        <div class="contact-field">
                            <span class="contact-label">Email</span>
                            <span class="contact-value">
                                <?php if (!empty($data['mentor']['email'])): ?>
                                    <a href="mailto:<?= htmlspecialchars($data['mentor']['email']) ?>"><?= htmlspecialchars($data['mentor']['email']) ?></a>
                                <?php else: ?>
                                    Not provided
                                <?php endif; ?>
                            </span>
                        </div>

                        <div class="contact-field">
                            <span class="contact-label">LinkedIn</span>
                            <span class="contact-value">
                                <?php if (!empty($data['mentor']['linkedin_url'])): ?>
                                    <a href="<?= htmlspecialchars($data['mentor']['linkedin_url']) ?>" target="_blank">View Profile</a>
                                <?php else: ?>
                                    Not provided
                                <?php endif; ?>
                            </span>
                        </div>

                        <div class="contact-field">
                            <span class="contact-label">GitHub</span>
                            <span class="contact-value">
                                <?php if (!empty($data['mentor']['github_url'])): ?>
                                    <a href="<?= htmlspecialchars($data['mentor']['github_url']) ?>" target="_blank">View Profile</a>
                                <?php else: ?>
                                    Not provided
                                <?php endif; ?>
                            </span>
                        </div>

                        <div class="contact-field">
                            <span class="contact-label">Portfolio</span>
                            <span class="contact-value">
                                <?php if (!empty($data['mentor']['portfolio_url'])): ?>
                                    <a href="<?= htmlspecialchars($data['mentor']['portfolio_url']) ?>" target="_blank">View Website</a>
                                <?php else: ?>
                                    Not provided
                                <?php endif; ?>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include __DIR__ . '/../layout/footer.php'; ?>

    <script>
    </script>
    <script src="<?= BASE_URL ?>/assets/js/mentorship.js"></script>
</body>
</html>