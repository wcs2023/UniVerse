<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/styles.css">
    <style>
        .profile-content {
            margin: 0 auto;
            margin-top: 10rem;
            padding: 2rem;
            background-color: #fff;
            border-radius: 13px;
            width: 70%;
        }
    </style>
</head>
<body>
    <header class="header">
    <div class="container">
        <div class="nav-brand">
            <a href="<?= BASE_URL ?>">
                <img src="<?= BASE_URL ?>/assets/images/U.png" alt="UniVerse Logo" class="logo">
            </a>
        </div>
        
        <!-- Auth Navigation Actions -->
        <div class="auth-nav-actions">
            <button onclick="window.location.href='<?= BASE_URL ?>/reset'" class="back-btn" title="Go back">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M19 12H5M12 19l-7-7 7-7"/>
                </svg>
                <span>Back</span>
            </button>
        </div>
        
        <button class="mobile-menu-btn" id="mobile-menu-btn">
            <span></span>
            <span></span>
            <span></span>
        </button>
    </div>
    </header>

        <!-- Settings Section -->
        <div class="profile-content">
            <!-- Change Password Form -->
            <div class="settings-form">
                <h3>Change Password</h3>
                
                <form method="POST" action="<?= BASE_URL ?>/usettings/changePassword" id="password-form">
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
                        <button type="button" onclick="window.location.href='<?= BASE_URL ?>/reset'" class="btn btn-secondary cancel-btn">Cancel</button>
                        <button type="submit" class="btn btn-primary save-btn">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>    
    <script>
        window.onload = function() {
            window.scrollTo(0, 0);
        };
        
        // Form validation
        document.getElementById('password-form').addEventListener('submit', function(e) {
            const newPassword = document.getElementById('new-password').value;
            const confirmPassword = document.getElementById('confirm-password').value;
            
            if (!newPassword || !confirmPassword) {
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
