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
<?php $profile = is_array($data['profile'] ?? null) ? $data['profile'] : []; ?>
<?php
    $logoPath = is_string($profile['logo_url'] ?? '') ? $profile['logo_url'] : '';
    $logoPath = ltrim($logoPath, '/');
    if (strpos($logoPath, 'public/') === 0) {
        $logoPath = substr($logoPath, 7);
    }
    $logoSrc = $logoPath ? (BASE_URL . '/' . $logoPath) : '';
?>
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
                    <div class="logo-uploader" aria-describedby="logoHelp">
                        <div class="logo-preview" id="logoPreview">
                            <?php if (!empty($logoSrc)): ?>
                                <img id="logoPreviewImg" src="<?= htmlspecialchars($logoSrc) ?>" alt="Company logo" width="128" height="128">
                            <?php else: ?>
                                <span class="logo-placeholder" id="logoPlaceholder">Logo</span>
                                <img id="logoPreviewImg" src="" alt="Company logo" width="128" height="128" style="display:none;">
                            <?php endif; ?>
                        </div>

                        <div class="logo-controls">
                            <div class="logo-title">Upload a square logo for best results</div>
                            <div class="logo-actions">
                                <input type="file" class="file-input" id="profilePicture" name="profilePicture" accept="image/*">
                                <label for="profilePicture" class="btn btn-secondary">Choose image</label>
                                <span class="file-name" id="logoFileName">No file chosen</span>
                            </div>
                            <small class="help-text" id="logoHelp">Max size: 5MB. Formats: JPG, PNG, GIF</small>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="companyName">Company Name</label>
                    <input class="form-control" type="text" id="companyName" name="company_name" placeholder="Enter company name" value="<?= htmlspecialchars($profile['company_name'] ?? '') ?>" required>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="contactPerson">Contact Person</label>
                        <input class="form-control" type="text" id="contactPerson" name="contact_person" placeholder="e.g., Jane Perera" value="<?= htmlspecialchars($profile['contact_person'] ?? '') ?>">
                    </div>

                    <div class="form-group">
                        <label for="email">Contact Email</label>
                        <input class="form-control" type="email" id="email" name="contact_email" placeholder="company@example.com" value="<?= htmlspecialchars($profile['contact_email'] ?? '') ?>">
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="industry">Industry</label>
                    <select class="form-control" id="industry" name="industry">
                        <option value="">Select Industry</option>
                        <option value="technology" <?= ($profile['industry'] ?? '') === 'technology' ? 'selected' : '' ?>>Technology</option>
                        <option value="healthcare" <?= ($profile['industry'] ?? '') === 'healthcare' ? 'selected' : '' ?>>Healthcare</option>
                        <option value="finance" <?= ($profile['industry'] ?? '') === 'finance' ? 'selected' : '' ?>>Finance</option>
                        <option value="education" <?= ($profile['industry'] ?? '') === 'education' ? 'selected' : '' ?>>Education</option>
                        <option value="retail" <?= ($profile['industry'] ?? '') === 'retail' ? 'selected' : '' ?>>Retail</option>
                        <option value="manufacturing" <?= ($profile['industry'] ?? '') === 'manufacturing' ? 'selected' : '' ?>>Manufacturing</option>
                        <option value="other" <?= ($profile['industry'] ?? '') === 'other' ? 'selected' : '' ?>>Other</option>
                    </select>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="companySize">Company Size</label>
                        <select class="form-control" id="companySize" name="company_size">
                            <option value="">Select Size</option>
                            <option value="startup" <?= ($profile['company_size'] ?? '') === 'startup' ? 'selected' : '' ?>>Startup</option>
                            <option value="small" <?= ($profile['company_size'] ?? '') === 'small' ? 'selected' : '' ?>>Small</option>
                            <option value="medium" <?= ($profile['company_size'] ?? '') === 'medium' ? 'selected' : '' ?>>Medium</option>
                            <option value="large" <?= ($profile['company_size'] ?? '') === 'large' ? 'selected' : '' ?>>Large</option>
                            <option value="enterprise" <?= ($profile['company_size'] ?? '') === 'enterprise' ? 'selected' : '' ?>>Enterprise</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="foundedYear">Founded Year</label>
                        <input class="form-control" type="number" id="foundedYear" name="founded_year" placeholder="e.g., 2015" value="<?= htmlspecialchars($profile['founded_year'] ?? '') ?>" min="1800" max="<?= date('Y') ?>">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="website">Website</label>
                        <input class="form-control" type="url" id="website" name="website" placeholder="https://yourcompany.com" value="<?= htmlspecialchars($profile['website'] ?? '') ?>">
                    </div>

                    <div class="form-group">
                        <label for="contactPhone">Contact Phone</label>
                        <input class="form-control" type="tel" id="contactPhone" name="contact_phone" placeholder="+94771234567" value="<?= htmlspecialchars($profile['contact_phone'] ?? '') ?>">
                        <small class="help-text">Format: +94xxxxxxxxx</small>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="description">Company Description</label>
                        <textarea class="form-control" id="description" name="company_description" rows="5" placeholder="Tell candidates what you do, your culture, and what you’re building..."><?= htmlspecialchars($profile['company_description'] ?? '') ?></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label for="address">Company Address</label>
                        <textarea class="form-control form-control--compact" id="address" name="company_address" rows="5" placeholder="Enter your company address (optional)"><?= htmlspecialchars($profile['company_address'] ?? '') ?></textarea>
                        <small class="help-text">Optional</small>
                    </div>
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
    <script>
        // Profile dropdown functionality
        document.addEventListener('DOMContentLoaded', function() {
            const profileTrigger = document.querySelector('.profile-trigger');
            const dropdownMenu = document.querySelector('.dropdown-menu');

            const fileInput = document.getElementById('profilePicture');
            const fileNameEl = document.getElementById('logoFileName');
            const previewImg = document.getElementById('logoPreviewImg');
            const placeholder = document.getElementById('logoPlaceholder');

            if (fileInput && fileNameEl && previewImg) {
                fileInput.addEventListener('change', function() {
                    const file = this.files && this.files[0] ? this.files[0] : null;
                    fileNameEl.textContent = file ? file.name : 'No file chosen';

                    if (file && file.type && file.type.startsWith('image/')) {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            previewImg.src = e.target.result;
                            previewImg.style.display = 'block';
                            if (placeholder) placeholder.style.display = 'none';
                        };
                        reader.readAsDataURL(file);
                    }
                });
            }
            
            if (profileTrigger && dropdownMenu) {
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
            }
        });
    </script>
</body>
</html>
