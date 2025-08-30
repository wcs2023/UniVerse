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
                <img src="<?= BASE_URL ?>/assets/images/logo bw.PNG" alt="Profile Photo">
            </div>
            <div class="profile-info">
                <h1>John Doe</h1>
                <p class="degree-info">B.Sc. Computer Science (Class of 2026)</p>
                <a href="<?= BASE_URL ?>/ueditprofile" class="edit-profile-btn">
                    Edit Profile
                </a>
            </div>
        </div>

        <!-- Profile Navigation -->
        <div class="profile-nav">
            <a href="<?= BASE_URL ?>/umyprofile" class="nav-item">
                <!-- <i class="icon-person"></i> -->
                Profile Overview
            </a>
            <a href="<?= BASE_URL ?>/uachievements" class="nav-item">
                <!-- <i class="icon-achievement"></i> -->
                Achievements
            </a>
            <a href="<?= BASE_URL ?>/usettings" class="nav-item active">
                <!-- <i class="icon-settings"></i> -->
                Settings
            </a>
        </div>

        <!-- Settings Section -->
        <div class="profile-content">
            <!-- <div class="section-header">
                <h2>Settings</h2>
            </div> -->

            <!-- Change Password Form -->
            <div class="settings-form">
                <!-- <h3>Account Settings</h3> -->
                <h3>Change Password</h3>
                
                <div class="form-group">
                    <label for="current-password">Current Password</label>
                    <input type="password" id="current-password" name="current-password" 
                           placeholder="Enter your current password" required>
                </div>

                <div class="form-group">
                    <label for="new-password">New Password</label>
                    <input type="password" id="new-password" name="new-password" 
                           placeholder="Enter your new password" required>
                </div>

                <div class="form-group">
                    <label for="confirm-password">Confirm New Password</label>
                    <input type="password" id="confirm-password" name="confirm-password" 
                           placeholder="Confirm your new password" required>
                </div>

                <div class="form-actions">
                    <button type="button" class="btn btn-secondary cancel-btn">Cancel</button>
                    <button type="submit" class="btn btn-primary save-btn">Save Changes</button>
                </div>
            </div>
        </div>
    </div>

    <?php include 'UFooter.view.php'; ?>
    
    <script src="<?= BASE_URL ?>/js/main.js"></script>
    <script>
        window.onload = function() {
            window.scrollTo(0, 0);
        };

        // Save button validation
        document.querySelector('.save-btn').addEventListener('click', function(e) {
            e.preventDefault();
            
            const currentPassword = document.getElementById('current-password').value;
            const newPassword = document.getElementById('new-password').value;
            const confirmPassword = document.getElementById('confirm-password').value;

            if (!currentPassword || !newPassword || !confirmPassword) {
                alert('Please fill in all fields');
                return;
            }

            if (newPassword !== confirmPassword) {
                alert('New passwords do not match');
                return;
            }

            if (newPassword.length < 8) {
                alert('Password must be at least 8 characters long');
                return;
            }

            // If validation passes, you can submit the form
            alert('Password updated successfully!');
            
            // Clear the form
            document.getElementById('current-password').value = '';
            document.getElementById('new-password').value = '';
            document.getElementById('confirm-password').value = '';
        });

        // Cancel button functionality
        document.querySelector('.cancel-btn').addEventListener('click', function() {
            document.getElementById('current-password').value = '';
            document.getElementById('new-password').value = '';
            document.getElementById('confirm-password').value = '';
        });
    </script>
</body>
</html>
