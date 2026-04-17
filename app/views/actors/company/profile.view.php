<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Company Profile - UniVerse</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/css/styles.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/company.css">
    <style>
        .file-input {
            position: absolute;
            width: 1px;
            height: 1px;
            padding: 0;
            margin: -1px;
            overflow: hidden;
            clip: rect(0, 0, 0, 0);
            white-space: nowrap;
            border: 0;
        }
        .logo-uploader {
            display: flex;
            align-items: flex-start;
            gap: 1.5rem;
            flex-wrap: wrap;
            padding: 1.25rem 0;
        }

        .logo-preview {
            width: 128px;
            height: 128px;
            border: 2px solid #e5e7eb;
            border-radius: 16px;
            overflow: hidden;
            background: #f8fafc;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .logo-preview img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }

        .logo-placeholder {
            color: #6b7280;
            font-size: 0.95rem;
            font-weight: 600;
        }

        .logo-controls {
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
            min-width: 260px;
            flex: 1;
        }

        .logo-title {
            font-size: 1rem;
            font-weight: 600;
            color: #1f2937;
        }

        .logo-actions {
            display: flex;
            align-items: center;
            gap: 1rem;
            flex-wrap: wrap;
        }

        .logo-actions .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-height: 44px;
            padding: 0.75rem 1.25rem;
            border-radius: 999px;
            cursor: pointer;
            font-weight: 600;
            text-decoration: none;
            transition: 0.2s ease;
        }

        .logo-actions .btn.btn-secondary {
            border: 2px solid #6c47d4;
            color: #6c47d4;
            background: #fff;
        }

        .logo-actions .btn.btn-secondary:hover {
            background: #f5f1ff;
        }

        .file-name {
            font-size: 0.95rem;
            color: #374151;
            word-break: break-word;
        }

        .help-text {
            font-size: 0.88rem;
            color: #6b7280;
        }

        @media (max-width: 768px) {
            .logo-uploader {
                flex-direction: column;
                align-items: flex-start;
            }

            .logo-controls {
                width: 100%;
            }

            .logo-actions {
                width: 100%;
                flex-direction: column;
                align-items: stretch;
            }

            .logo-actions .btn {
                width: 100%;
            }
        }

                /* ===== Alerts ===== */
        .flash-message,
        .alert {
            width: 100%;
            padding: 14px 18px;
            border-radius: 14px;
            margin-bottom: 1.25rem;
            font-size: 0.95rem;
            font-weight: 500;
            line-height: 1.5;
            border: 1px solid transparent;
            box-shadow: 0 6px 18px rgba(15, 23, 42, 0.06);
        }
        .alert.hide {
                opacity: 0;
                transform: translateY(-8px);
            }
        .alert-success {
            background: #ecfdf3;
            color: #166534;
            border-color: #bbf7d0;
        }

        .alert-error {
            background: #fef2f2;
            color: #b91c1c;
            border-color: #fecaca;
        }

        /* ===== Change Password Card ===== */
        .password-card {
            margin-top: 1.5rem;
        }

        .password-card .profile-form {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .password-card .form-group {
            margin-bottom: 0;
        }

        .password-card label {
            display: inline-block;
            margin-bottom: 0.45rem;
            font-size: 0.95rem;
            font-weight: 600;
            color: #1f2937;
        }

        .password-card input[type="password"] {
            width: 100%;
            min-height: 48px;
            padding: 0.85rem 1rem;
            border: 1.5px solid #d1d5db;
            border-radius: 12px;
            background: #ffffff;
            color: #111827;
            font-size: 0.95rem;
            transition: border-color 0.2s ease, box-shadow 0.2s ease, background 0.2s ease;
        }

        .password-card input[type="password"]::placeholder {
            color: #9ca3af;
        }

        .password-card input[type="password"]:hover {
            border-color: #b8c0cc;
        }

        .password-card input[type="password"]:focus {
            outline: none;
            border-color: #6c47d4;
            box-shadow: 0 0 0 4px rgba(108, 71, 212, 0.12);
            background: #fcfbff;
        }

        .password-card .form-actions {
            margin-top: 0.5rem;
        }

        .password-card .btn.btn-primary {
            min-height: 46px;
            padding: 0.8rem 1.4rem;
            border: none;
            border-radius: 12px;
            background: #6c47d4;
            color: #fff;
            font-weight: 600;
            transition: transform 0.15s ease, box-shadow 0.2s ease, background 0.2s ease;
            box-shadow: 0 8px 20px rgba(108, 71, 212, 0.22);
        }

        .password-card .btn.btn-primary:hover {
            background: #5b38bf;
            transform: translateY(-1px);
        }

        .password-card .btn.btn-primary:active {
            transform: translateY(0);
        }

        /* Optional helper text */
        .password-hint {
            margin-top: -0.25rem;
            font-size: 0.85rem;
            color: #6b7280;
        }

        /* Mobile */
        @media (max-width: 768px) {
            .alert {
                padding: 12px 14px;
                border-radius: 12px;
                font-size: 0.92rem;
            }

            .password-card input[type="password"] {
                min-height: 46px;
                font-size: 0.94rem;
            }

            .password-card .btn.btn-primary {
                width: 100%;
            }
        }
    </style>
</head>
<body>
<?php require_once __DIR__ . '/companyHeader.view.php'; ?>
<?php $profile = is_array($data['profile'] ?? null) ? $data['profile'] : []; ?>
<?php
    $logoPath = $profile['logo_url'] ?? ''; // get value safely
    $logoPath = is_string($logoPath) ? $logoPath : ''; // check if it’s string
    $logoPath = ltrim($logoPath, '/');
    if (strpos($logoPath, 'public/') === 0) {
        $logoPath = substr($logoPath, 7);
    }
    $logoSrc = $logoPath ? (BASE_URL . '/' . $logoPath) : '';
?>
    <main class="main-content">
        <!-- Profile Form -->
            <?php if (isset($_SESSION['success'])): ?>
                <div class="alert alert-success flash-message">
                    <?= $_SESSION['success']; unset($_SESSION['success']); ?>
                </div>
            <?php endif; ?>

            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-error flash-message">
                    <?= $_SESSION['error']; unset($_SESSION['error']); ?>
                </div>
            <?php endif; ?>

    <!-- Profile Form card -->
    <!-- Change Password card -->

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
                                <span class="file-name" id="logoFileName">
                                    <?= !empty($logoSrc) ? 'Current image selected' : 'No file chosen' ?>
                                </span>
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
                    
                    <!-- <div class="form-group">
                        <label for="address">Company Address</label>
                        <textarea class="form-control form-control--compact" id="address" name="company_address" rows="5" placeholder="Enter your company address (optional)"><?= htmlspecialchars($profile['company_address'] ?? '') ?></textarea>
                        <small class="help-text">Optional</small>
                    </div> -->
                </div>
                
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">Update Profile</button>
                    <button type="button" class="btn btn-secondary" onclick="window.history.back()">Cancel</button>
                </div>
            </form>
        </div>
        
        <!-- Change Password Section -->
        <div class="card password-card" >
            <div class="card-header">
                <h3 class="card-title">Change Password</h3>
                <p class="card-subtitle">Update your login password</p>
            </div>
            
            <form class="profile-form" action="<?= BASE_URL ?>/company/changePassword" method="POST">
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
        document.addEventListener('DOMContentLoaded', function() {
            const flashMessages = document.querySelectorAll('.flash-message');
            flashMessages.forEach(function(message) {
                setTimeout(function() {
                    message.classList.add('hide');

                    setTimeout(function() {
                        message.remove();
                    }, 400);
                }, 3000); // disappears after 3 seconds
            });
        });
    </script>
</body>
</html>
