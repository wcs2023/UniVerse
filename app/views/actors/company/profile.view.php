<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Company Profile - UniVerse</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/company.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/css/styles.css">
</head>
<body>
<?php require_once __DIR__ . '/companyHeader.view.php'; ?>
    <main class="main-content">
        <!-- Profile Form -->
        <div class="card">
            <div class="card-header">
                <h2 class="card-title">Company Profile</h2>
                <p class="card-subtitle">Update your company information</p>
            </div>
            
            <form class="profile-form" action="<?= BASE_URL ?>/company/profile" method="POST" enctype="multipart/form-data">
                <!-- Profile Picture Section -->
                <div class="form-group">
                    <label for="profilePicture">Company Profile Picture</label>
                    <div style="display: flex; gap: 2rem; align-items: flex-start; margin-bottom: 1.5rem;">
                        <div style="flex: 1;">
                            <div style="width: 150px; height: 150px; border-radius: 12px; overflow: hidden; background: #f0f0f0; display: flex; align-items: center; justify-content: center; margin-bottom: 1rem; border: 2px solid #e0e0e0;">
                                <?php if ($data['profile'] && $data['profile']['logo_url']): ?>
                                    <img src="<?= BASE_URL . '/' . htmlspecialchars($data['profile']['logo_url']) ?>" alt="Company Logo" style="width: 100%; height: 100%; object-fit: cover;">
                                <?php else: ?>
                                    <span style="color: #999; font-size: 3rem;">🏢</span>
                                <?php endif; ?>
                            </div>
                            <input type="file" id="profilePicture" name="profilePicture" accept="image/*" style="margin-bottom: 0.5rem;">
                            <small style="display: block; color: #666;">Max size: 5MB. Formats: JPG, PNG, GIF</small>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="companyName">Company Name</label>
                    <input type="text" id="companyName" name="company_name" placeholder="Enter company name" value="<?= htmlspecialchars($data['profile']['company_name'] ?? '') ?>">
                </div>
                
                <div class="form-group">
                    <label for="email">Contact Email</label>
                    <input type="email" id="email" name="contact_email" placeholder="company@example.com" value="<?= htmlspecialchars($data['profile']['contact_email'] ?? '') ?>">
                </div>
                
                <div class="form-group">
                    <label for="industry">Industry</label>
                    <select id="industry" name="industry">
                        <option value="">Select Industry</option>
                        <option value="technology" <?= ($data['profile']['industry'] ?? '') === 'technology' ? 'selected' : '' ?>>Technology</option>
                        <option value="healthcare" <?= ($data['profile']['industry'] ?? '') === 'healthcare' ? 'selected' : '' ?>>Healthcare</option>
                        <option value="finance" <?= ($data['profile']['industry'] ?? '') === 'finance' ? 'selected' : '' ?>>Finance</option>
                        <option value="education" <?= ($data['profile']['industry'] ?? '') === 'education' ? 'selected' : '' ?>>Education</option>
                        <option value="retail" <?= ($data['profile']['industry'] ?? '') === 'retail' ? 'selected' : '' ?>>Retail</option>
                        <option value="manufacturing" <?= ($data['profile']['industry'] ?? '') === 'manufacturing' ? 'selected' : '' ?>>Manufacturing</option>
                        <option value="other" <?= ($data['profile']['industry'] ?? '') === 'other' ? 'selected' : '' ?>>Other</option>
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
<?php require_once __DIR__ . '/../../layout/footer.php'; ?>
</body>
</html>
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
