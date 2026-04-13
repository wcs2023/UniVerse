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

// Prepare display data (same as profile page)
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
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/alumni.css">
    <link rel="icon" type="image/png" href="<?= BASE_URL ?>/assets/images/U.png">
    <title>Settings - UniVerse</title>
</head>

<body style="padding-top: 80px; background-color: #a78bfa45 !important;">
    <?php
    // Include navigation
    $navFile = APPROOT . '/views/actors/alumni/Anavbar.php';
    if (file_exists($navFile)) {
        include $navFile;
    }
    ?>

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
            <a href="<?= BASE_URL ?>/alumni/profile" class="nav-item">
                Profile Overview
            </a>
            <a href="<?= BASE_URL ?>/alumni/settings" class="nav-item active">
                Settings
            </a>
        </div>

        <!-- Success/Error Messages -->
        <?php if (isset($data['success'])): ?>
            <div class="alert alert-success">
                <?= htmlspecialchars($data['success']) ?>
            </div>
        <?php endif; ?>
        
        <?php if (isset($data['error'])): ?>
            <div class="alert alert-error">
                <?= htmlspecialchars($data['error']) ?>
            </div>
        <?php endif; ?>

        <!-- Settings Section -->
        <div class="profile-content">
            <!-- Change Password Form -->
            <div class="settings-form">
                <h3 class="section-title">Change Password</h3>
                
                <form method="POST" action="<?= BASE_URL ?>/alumni/updatePassword" id="password-form">
                    <div class="form-group">
                        <label for="current-password">Current Password</label>
                        <input type="password" id="current-password" name="current_password" 
                               placeholder="Enter your current password" required>
                    </div>

                    <div class="form-group">
                        <label for="new-password">New Password</label>
                        <input type="password" id="new-password" name="new_password" 
                               placeholder="Enter your new password" required>
                        <small class="form-hint">Password must be at least 8 characters long</small>
                    </div>

                    <div class="form-group">
                        <label for="confirm-password">Confirm New Password</label>
                        <input type="password" id="confirm-password" name="confirm_password" 
                               placeholder="Confirm your new password" required>
                    </div>

                    <div class="form-actions">
                        <button type="button" class="btn btn-secondary cancel-btn">Cancel</button>
                        <button type="submit" class="btn btn-primary save-btn">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <?php include APPROOT . '/views/layout/footer.php'; ?>
    
    <script>
        window.onload = function() {
            window.scrollTo(0, 0);
        };
        
        // Form validation
        document.getElementById('password-form').addEventListener('submit', function(e) {
            const currentPassword = document.getElementById('current-password').value;
            const newPassword = document.getElementById('new-password').value;
            const confirmPassword = document.getElementById('confirm-password').value;
            
            if (!currentPassword || !newPassword || !confirmPassword) {
                e.preventDefault();
                alert('Please fill in all fields');
                return false;
            }
            
            if (newPassword !== confirmPassword) {
                e.preventDefault();
                alert('New passwords do not match');
                return false;
            }
            
            if (newPassword.length < 8) {
                e.preventDefault();
                alert('Password must be at least 8 characters long');
                return false;
            }
            
            // Form will submit if validation passes
        });
        
        // Cancel button functionality
        document.querySelector('.cancel-btn').addEventListener('click', function() {
            document.getElementById('current-password').value = '';
            document.getElementById('new-password').value = '';
            document.getElementById('confirm-password').value = '';
        });
    </script>

    <style>
        .settings-form {
            background: white;
            padding: 2rem;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }

        .settings-form .section-title {
            color: var(--primary-purple, #6b46c1);
            font-size: 1.3rem;
            margin-bottom: 1.5rem;
            padding-bottom: 0.5rem;
            border-bottom: 2px solid var(--primary-purple, #6b46c1);
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-group label {
            display: block;
            font-weight: 600;
            margin-bottom: 0.5rem;
            color: #1f2937;
        }

        .form-group input {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            font-size: 1rem;
            transition: border-color 0.2s, box-shadow 0.2s;
        }

        .form-group input:focus {
            outline: none;
            border-color: var(--primary-purple, #6b46c1);
            box-shadow: 0 0 0 3px rgba(107, 70, 193, 0.1);
        }

        .form-hint {
            display: block;
            margin-top: 5px;
            color: #6b7280;
            font-size: 13px;
            font-style: italic;
        }

        .form-actions {
            display: flex;
            justify-content: flex-end;
            gap: 1rem;
            margin-top: 2rem;
            padding-top: 1.5rem;
            border-top: 1px solid #e5e7eb;
        }

        .alert {
            padding: 15px 20px;
            margin-bottom: 1.5rem;
            border-radius: 8px;
            font-weight: 500;
        }
        
        .alert-success {
            background-color: #d1fae5;
            color: #065f46;
            border-left: 4px solid #10b981;
        }
        
        .alert-error {
            background-color: #fee2e2;
            color: #991b1b;
            border-left: 4px solid #ef4444;
        }
    </style>
</body>
</html>
