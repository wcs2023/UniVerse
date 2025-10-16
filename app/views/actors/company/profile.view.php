<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Company Profile - UniVerse</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/company.css">
</head>
<body>
    <header class="company-header">
        <a href="<?= BASE_URL ?>/company/landing" class="company-logo">UniVerse</a>
        <nav class="company-nav">
            <a href="<?= BASE_URL ?>/company/landing">Dashboard</a>
            <a href="<?= BASE_URL ?>/company/managejobs">Manage Jobs</a>
            <a href="<?= BASE_URL ?>/company/postjobs">Post Jobs</a>
            <a href="<?= BASE_URL ?>/company/applications">View Applications</a>
        </nav>
        
        <!-- User Profile Dropdown -->
        <div class="user-profile-dropdown">
            <div class="profile-trigger">
                <div class="profile-icon">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                    </svg>
                </div>
                <span class="profile-name"><?= $user->firstname ?? 'User' ?></span>
                <div class="dropdown-arrow">▼</div>
            </div>
            
            <div class="dropdown-menu">
                <a href="<?= BASE_URL ?>/company/profile" class="dropdown-item active">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 3c1.66 0 3 1.34 3 3s-1.34 3-3 3-3-1.34-3-3 1.34-3 3-3zm0 14.2c-2.5 0-4.71-1.28-6-3.22.03-1.99 4-3.08 6-3.08 1.99 0 5.97 1.09 6 3.08-1.29 1.94-3.5 3.22-6 3.22z"/>
                    </svg>
                    Update Profile
                </a>
                <a href="<?= BASE_URL ?>/login/logout" class="dropdown-item logout">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M17 7l-1.41 1.41L18.17 11H8v2h10.17l-2.58 2.59L17 17l5-5zM4 5h8V3H4c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h8v-2H4V5z"/>
                    </svg>
                    Logout
                </a>
            </div>
        </div>
    </header>

    <main class="main-content">
        <!-- Profile Form -->
        <div class="card">
            <div class="card-header">
                <h2 class="card-title">Company Profile</h2>
                <p class="card-subtitle">Update your company information</p>
            </div>
            
            <form class="profile-form" action="<?= BASE_URL ?>/company/updateprofile" method="POST">
                <div class="form-row">
                    <div class="form-group">
                        <label for="firstName">First Name</label>
                        <input type="text" id="firstName" name="firstName" value="<?= $user->firstname ?? '' ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="lastName">Last Name</label>
                        <input type="text" id="lastName" name="lastName" value="<?= $user->lastname ?? '' ?>" required>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input type="email" id="email" name="email" value="<?= $user->email ?? '' ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="companyName">Company Name</label>
                    <input type="text" id="companyName" name="companyName" placeholder="Enter company name">
                </div>
                
                <div class="form-group">
                    <label for="industry">Industry</label>
                    <select id="industry" name="industry">
                        <option value="">Select Industry</option>
                        <option value="technology">Technology</option>
                        <option value="healthcare">Healthcare</option>
                        <option value="finance">Finance</option>
                        <option value="education">Education</option>
                        <option value="retail">Retail</option>
                        <option value="manufacturing">Manufacturing</option>
                        <option value="other">Other</option>
                    </select>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="companySize">Company Size</label>
                        <select id="companySize" name="companySize">
                            <option value="">Select Size</option>
                            <option value="1-10">1-10 employees</option>
                            <option value="11-50">11-50 employees</option>
                            <option value="51-200">51-200 employees</option>
                            <option value="201-500">201-500 employees</option>
                            <option value="500+">500+ employees</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="website">Website</label>
                        <input type="url" id="website" name="website" placeholder="https://yourcompany.com">
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="description">Company Description</label>
                    <textarea id="description" name="description" rows="4" placeholder="Tell us about your company..."></textarea>
                </div>
                
                <div class="form-group">
                    <label for="address">Company Address</label>
                    <textarea id="address" name="address" rows="3" placeholder="Enter your company address"></textarea>
                </div>
                
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">Update Profile</button>
                    <button type="button" class="btn btn-secondary" onclick="window.history.back()">Cancel</button>
                </div>
            </form>
        </div>
        
        <!-- Change Password Section -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Change Password</h3>
                <p class="card-subtitle">Update your login password</p>
            </div>
            
            <form class="profile-form" action="<?= BASE_URL ?>/company/changepassword" method="POST">
                <div class="form-group">
                    <label for="currentPassword">Current Password</label>
                    <input type="password" id="currentPassword" name="currentPassword" required>
                </div>
                
                <div class="form-group">
                    <label for="newPassword">New Password</label>
                    <input type="password" id="newPassword" name="newPassword" required>
                </div>
                
                <div class="form-group">
                    <label for="confirmPassword">Confirm New Password</label>
                    <input type="password" id="confirmPassword" name="confirmPassword" required>
                </div>
                
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">Change Password</button>
                </div>
            </form>
        </div>
    </main>

    <script>
        // Profile dropdown functionality
        document.addEventListener('DOMContentLoaded', function() {
            const profileTrigger = document.querySelector('.profile-trigger');
            const dropdownMenu = document.querySelector('.dropdown-menu');
            
            profileTrigger.addEventListener('click', function(e) {
                e.stopPropagation();
                dropdownMenu.classList.toggle('active');
            });
            
            // Close dropdown when clicking outside
            document.addEventListener('click', function() {
                dropdownMenu.classList.remove('active');
            });
            
            // Prevent dropdown from closing when clicking inside
            dropdownMenu.addEventListener('click', function(e) {
                e.stopPropagation();
            });
        });
    </script>
</body>
</html>
