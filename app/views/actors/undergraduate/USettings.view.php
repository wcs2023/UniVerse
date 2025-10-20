<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?= BASE_URL ?>/css/styles.css">
    <link rel="icon" type="image/png" href="<?= BASE_URL ?>/assets/images/U.png">
    <title>UniVerse - Settings</title>
</head>
<body>
    <?php include 'Unavigation.view.php'; ?>

    <div class="profile-container">
        <!-- Profile Header -->
        <div class="profile-header">
            <div class="profile-image">
                <?php 
                // ✅ Get profile picture from database, fallback to default
                $profilePicture = BASE_URL . '/assets/images/default-avatar.png'; // Default
                
                if (!empty($data['user']['profile_picture'])) {
                    $profilePicture = BASE_URL . $data['user']['profile_picture'];
                }
                ?>
                <img src="<?= $profilePicture ?>" alt="Profile Photo">
            </div>
            <div class="profile-info">
                <?php 
                // ✅ Get user name from database
                $firstName = $data['user']['first_name'] ?? $_SESSION['user_name'] ?? 'User';
                $lastName = $data['user']['last_name'] ?? $_SESSION['last_name'] ?? '';
                
                // ✅ Get degree info from profile
                $degreeProgram = $data['profile']['degree_program'] ?? 'B.Sc. Computer Science';
                $graduationYear = $data['profile']['expected_graduation_year'] ?? '2026';
                ?>
                <h1><?= htmlspecialchars($firstName . ' ' . $lastName) ?></h1>
                <p class="degree-info"><?= htmlspecialchars($degreeProgram) ?> (Class of <?= htmlspecialchars($graduationYear) ?>)</p>
                <a href="<?= BASE_URL ?>/ueditprofile" class="edit-profile-btn">
                    Edit Profile
                </a>
            </div>
        </div>

         <!-- Profile Navigation -->
        <div class="profile-nav">
            <a href="<?= BASE_URL ?>/umyprofile" class="nav-item ">
            Profile Overview
            </a>
            <a href="<?= BASE_URL ?>/uachievements" class="nav-item">
            Achievements
            </a>
            <a href="<?= BASE_URL ?>/ubookmarks" class="nav-item">
            Bookmarked Articles
            </a>
            <a href="<?= BASE_URL ?>/usettings" class="nav-item active">
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
                <h3>Change Password</h3>
                
                <form method="POST" action="<?= BASE_URL ?>/usettings/changePassword" id="password-form">
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

    <?php include __DIR__ . '/../../layout/footer.php'; ?>
    
    <script src="<?= BASE_URL ?>/js/main.js"></script>
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
        .alert {
            padding: 15px;
            margin: 20px 0;
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

        .form-hint {
            display: block;
            margin-top: 5px;
            color: #6b7280;
            font-size: 13px;
        }
    </style>
</body>
</html>
