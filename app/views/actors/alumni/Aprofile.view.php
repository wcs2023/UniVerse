<?php
// Define constants if not already defined
if (!defined('APPROOT')) {
    define('APPROOT', dirname(dirname(dirname(dirname(__FILE__)))));
}
if (!defined('URLROOT')) {
    define('URLROOT', 'http://localhost/UniVerse/public');
}

// Get user data from controller
$user = $data['userData'] ?? null;

// Prepare display data
$userData = [
    'full_name' => $user->full_name ?? 'User Name',
    'email' => $user->email ?? 'user@example.com',
    'current_role' => $user->current_role ?? '',
    'company' => $user->company ?? '',
    'linkedin_url' => $user->linkedin_url ?? '',
    'short_bio' => $user->short_bio ?? '',
    'profile_picture' => $user->profile_picture ?? '/assets/images/default-avatar.svg',
    'available_for_mentorship' => $user->available_for_mentorship ?? false
];
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?= BASE_URL ?>/css/alumni.css">
    <link rel="icon" type="image/png" href="<?= BASE_URL ?>/assets/images/U.png">
    <title>My Profile - UniVerse</title>
</head>

<body style="padding-top: 80px; background-color: #a78bfa45 !important;">
    <?php
    // Include navigation
    $navFile = APPROOT . '/views/actors/alumni/Anavbar.php';
    if (file_exists($navFile)) {
        include $navFile;
    }
    ?>

<?php if (isset($_SESSION['profile_success'])): ?>
        <div class="alert alert-success profile-success-banner">
            <?= htmlspecialchars($_SESSION['profile_success']) ?>
        </div>
        <?php unset($_SESSION['profile_success']); ?>
    <?php endif; ?>

    <div class="profile-container">
        <!-- Profile Header -->
        <div class="profile-header">
            <div class="profile-image">
                <?php
                $profilePicture = !empty($userData['profile_picture'])
                    ? $userData['profile_picture']
                    : '/assets/images/default-avatar.svg';
                ?>
                <img src="<?= BASE_URL ?><?= $profilePicture ?>" alt="Profile Photo"
                    onerror="this.src='<?= BASE_URL ?>/assets/images/U.png'">
            </div>
            <div class="profile-info">
                <h1><?= htmlspecialchars($userData['full_name']) ?></h1>
                <p class="role-info">
                    <?= htmlspecialchars($userData['current_role'] ?: 'Alumni') ?>
                    <?php if ($userData['company']): ?>
                        at <?= htmlspecialchars($userData['company']) ?>
                    <?php endif; ?>
                </p>
                <a href="<?= BASE_URL ?>/aeditprofile" class="edit-profile-btn">
                    Edit Profile
                </a>
            </div>
        </div>

        <!-- Profile Navigation -->
        <div class="profile-nav">
            <a href="<?= BASE_URL ?>/alumni/profile" class="nav-item active">
                Profile Overview
            </a>
            <a href="<?= BASE_URL ?>/alumni/settings" class="nav-item">
                Settings
            </a>
        </div>

        <!-- Profile Content -->
        <div class="profile-content">
            <div class="section-header">
                <h2>Profile Overview</h2>
            </div>

            <div class="info-grid">
                <!-- Email -->
                <div class="info-item">
                    <div class="info-label">
                        Email
                    </div>
                    <div class="info-value"><?= htmlspecialchars($userData['email']) ?></div>
                </div>

                <!-- Current Role -->
                <div class="info-item">
                    <div class="info-label">
                        Current Role
                    </div>
                    <div class="info-value"><?= htmlspecialchars($userData['current_role'] ?: 'Not provided') ?></div>
                </div>

                <!-- Company -->
                <div class="info-item">
                    <div class="info-label">
                        Company/University
                    </div>
                    <div class="info-value"><?= htmlspecialchars($userData['company'] ?: 'Not provided') ?></div>
                </div>

                <!-- LinkedIn -->
                <div class="info-item">
                    <div class="info-label">
                        LinkedIn
                    </div>
                    <div class="info-value">
                        <?php if ($userData['linkedin_url']): ?>
                            <a href="<?= htmlspecialchars($userData['linkedin_url']) ?>" target="_blank"
                                class="profile-link">
                                View Profile
                            </a>
                        <?php else: ?>
                            Not provided
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Mentorship Status -->
                <div class="info-item">
                    <div class="info-label">
                        Mentorship Status
                    </div>
                    <div class="info-value">
                        <?php if (isset($mentorStatus) && $mentorStatus && $mentorStatus['is_active']): ?>
                            <span class="status-badge status-accepted">Available</span>
                        <?php else: ?>
                            <span class="status-badge status-pending">Not Available</span>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Bio Section -->
            <?php if ($userData['short_bio']): ?>
                <div class="profile-bio-section">
                    <h3>About Me</h3>
                    <p>
                        <?= nl2br(htmlspecialchars($userData['short_bio'])) ?></p>
                </div>
            <?php endif; ?>
        </div>

        <!-- Quick Actions -->
        <div class="section-card" style="margin-top: 2rem;">
            <h2 class="card-title">Quick Actions</h2>
            <div class="quick-actions-row">
                <a href="<?= BASE_URL ?>/aarticles/create" class="btn btn-primary">
                     Write an Article
                </a>
                <a href="<?= BASE_URL ?>/amentorships" class="btn btn-secondary">
                     Mentor Dashboard
                </a>
                <a href="<?= BASE_URL ?>/adiscussion" class="btn btn-secondary">
                     Discussion Forums
                </a>
            </div>
        </div>
    </div>

    <?php
    // Include footer
    include __DIR__ . '/../../layout/footer.php';
    ?>
</body>

</html>