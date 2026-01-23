<?php
// Define constants if not already defined
if (!defined('APPROOT')) {
    define('APPROOT', dirname(dirname(dirname(dirname(__FILE__)))));
}
if (!defined('URLROOT')) {
    define('URLROOT', 'http://localhost/UniVerse/public');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile - UniVerse</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/css/alumni.css">
    <link rel="icon" type="image/png" href="<?= BASE_URL ?>/assets/images/U.png">
    <style>
        .edit-profile-container {
            max-width: 900px;
            margin: 2rem auto;
            padding: 0 1rem;
        }

        .edit-profile-header {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-bottom: 2rem;
        }

        .edit-profile-header h1 {
            color: var(--text-dark, #1a1a2e);
            font-size: 1.8rem;
            margin: 0;
        }

        .back-btn {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            color: var(--primary-purple, #6c63ff);
            text-decoration: none;
            font-weight: 500;
            transition: opacity 0.2s;
        }

        .back-btn:hover {
            opacity: 0.8;
        }

        .alert {
            padding: 1rem 1.5rem;
            border-radius: 8px;
            margin-bottom: 1.5rem;
            font-weight: 500;
        }

        .alert-error {
            background: #fee2e2;
            color: #dc2626;
            border: 1px solid #fca5a5;
        }

        .alert-success {
            background: #dcfce7;
            color: #16a34a;
            border: 1px solid #86efac;
        }

        .form-section {
            background: white;
            border-radius: 12px;
            padding: 2rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }

        .form-section h2 {
            color: var(--text-dark, #1a1a2e);
            font-size: 1.3rem;
            margin: 0 0 1.5rem 0;
            padding-bottom: 0.75rem;
            border-bottom: 2px solid var(--primary-purple, #6c63ff);
        }

        .form-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-bottom: 1.5rem;
        }

        .form-group {
            display: flex;
            flex-direction: column;
        }

        .form-group label {
            font-weight: 500;
            color: var(--text-dark, #1a1a2e);
            margin-bottom: 0.5rem;
            font-size: 0.9rem;
        }

        .form-group input,
        .form-group select,
        .form-group textarea {
            padding: 0.75rem 1rem;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            font-size: 1rem;
            transition: border-color 0.2s, box-shadow 0.2s;
            font-family: inherit;
        }

        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: var(--primary-purple, #6c63ff);
            box-shadow: 0 0 0 3px rgba(108, 99, 255, 0.1);
        }

        .form-group input:read-only {
            background: #f9fafb;
            color: #6b7280;
            cursor: not-allowed;
        }

        .form-group small {
            color: #6b7280;
            font-size: 0.8rem;
            margin-top: 0.3rem;
        }

        .form-group textarea {
            resize: vertical;
            min-height: 120px;
        }

        .radio-group-horizontal {
            display: flex;
            gap: 1.5rem;
            flex-wrap: wrap;
            padding-top: 0.5rem;
        }

        .radio-option-inline {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            cursor: pointer;
        }

        .radio-option-inline input[type="radio"] {
            width: 18px;
            height: 18px;
            accent-color: var(--primary-purple, #6c63ff);
        }

        /* Profile Picture Section */
        .profile-picture-section {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 1rem;
            padding: 1.5rem;
            background: #f8f9fa;
            border-radius: 12px;
        }

        .current-picture {
            position: relative;
            cursor: pointer;
            transition: transform 0.2s;
        }

        .current-picture:hover {
            transform: scale(1.02);
        }

        .current-picture img {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            object-fit: cover;
            border: 4px solid var(--primary-purple, #6c63ff);
        }

        .picture-note {
            text-align: center;
            color: #6b7280;
            font-size: 0.85rem;
            margin-top: 0.5rem;
        }

        /* Photo Modal */
        .photo-modal {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 1000;
        }

        .photo-modal-content {
            background: white;
            border-radius: 16px;
            width: 90%;
            max-width: 450px;
            overflow: hidden;
        }

        .photo-modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1.25rem 1.5rem;
            background: var(--primary-purple, #6c63ff);
            color: white;
        }

        .photo-modal-header h3 {
            margin: 0;
            font-size: 1.2rem;
        }

        .close-modal {
            background: none;
            border: none;
            color: white;
            font-size: 1.5rem;
            cursor: pointer;
            padding: 0;
            line-height: 1;
        }

        .photo-modal-body {
            padding: 2rem;
            text-align: center;
        }

        .photo-modal-body p {
            color: #6b7280;
            margin-bottom: 1.5rem;
        }

        .photo-preview-container {
            margin-bottom: 1.5rem;
        }

        .photo-preview-container img {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            object-fit: cover;
            border: 4px solid #e5e7eb;
        }

        .upload-photo-btn {
            background: var(--primary-purple, #6c63ff);
            color: white;
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 500;
            transition: background 0.2s;
        }

        .upload-photo-btn:hover {
            background: #5b54e6;
        }

        .photo-modal-footer {
            display: flex;
            justify-content: flex-end;
            gap: 1rem;
            padding: 1rem 1.5rem;
            background: #f8f9fa;
        }

        /* Form Actions */
        .form-actions {
            display: flex;
            justify-content: flex-end;
            gap: 1rem;
            padding-top: 1rem;
        }

        .btn {
            padding: 0.75rem 2rem;
            border-radius: 8px;
            font-weight: 600;
            font-size: 1rem;
            cursor: pointer;
            transition: all 0.2s;
            border: none;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-primary {
            background: var(--primary-purple, #6c63ff);
            color: white;
        }

        .btn-primary:hover {
            background: #5b54e6;
        }

        .btn-secondary {
            background: #e5e7eb;
            color: #374151;
        }

        .btn-secondary:hover {
            background: #d1d5db;
        }

        /* URL Input styling */
        .url-input {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .url-prefix {
            color: #6b7280;
            font-size: 0.9rem;
            white-space: nowrap;
        }

        @media (max-width: 768px) {
            .edit-profile-container {
                padding: 0 0.75rem;
            }

            .form-section {
                padding: 1.5rem;
            }

            .form-row {
                grid-template-columns: 1fr;
            }

            .form-actions {
                flex-direction: column-reverse;
            }

            .form-actions .btn {
                width: 100%;
                justify-content: center;
            }
        }
    </style>
</head>
<body style="padding-top: 80px;">
    <?php
    // Include navigation
    $navFile = APPROOT . '/views/actors/alumni/Anavbar.php';
    if (file_exists($navFile)) {
        include $navFile;
    }
    ?>

    <div class="edit-profile-container">
        <!-- Header -->
        <div class="edit-profile-header">
            <a href="<?= BASE_URL ?>/alumni/profile" class="back-btn">
                ← Back to Profile
            </a>
        </div>
        <h1 style="margin-bottom: 2rem;">Edit Your Profile</h1>

        <?php if (isset($data['error'])): ?>
            <div class="alert alert-error">
                <?= htmlspecialchars($data['error']) ?>
            </div>
        <?php endif; ?>
        
        <?php if (isset($data['success'])): ?>
            <div class="alert alert-success">
                <?= htmlspecialchars($data['success']) ?>
            </div>
        <?php endif; ?>

        <!-- Profile Photo Modal -->
        <div id="photoModal" class="photo-modal" style="display: none;">
            <form method="POST" action="<?= BASE_URL ?>/aeditprofile" enctype="multipart/form-data" id="photo-upload-form">
                <div class="photo-modal-content">
                    <div class="photo-modal-header">
                        <h3>Edit Profile Photo</h3>
                        <button type="button" class="close-modal" onclick="closePhotoModal()">&times;</button>
                    </div>
                    <div class="photo-modal-body">
                        <p>Upload a new profile picture. Click save when you're done.</p>
                        <div class="photo-preview-container">
                            <?php 
                            $profilePic = !empty($data['user']->profile_picture) 
                                ? $data['user']->profile_picture 
                                : '/assets/images/default-avatar.png';
                            ?>
                            <img src="<?= BASE_URL ?><?= $profilePic ?>" 
                                 alt="Profile Preview" 
                                 id="modal-profile-preview"
                                 onerror="this.src='<?= BASE_URL ?>/assets/images/U.png'">
                        </div>
                        <button type="button" class="upload-photo-btn" onclick="document.getElementById('modal-profile-picture').click()">
                            📷 Choose Photo
                        </button>
                        <input type="file" id="modal-profile-picture" name="profile_picture" accept="image/*" onchange="previewModalImage(this)" style="display: none;">
                    </div>
                    <div class="photo-modal-footer">
                        <button type="button" class="btn btn-secondary" onclick="closePhotoModal()">Cancel</button>
                        <button type="submit" class="btn btn-primary">Save Photo</button>
                    </div>
                </div>
            </form>
        </div>

        <form class="edit-profile-form" method="POST" action="<?= BASE_URL ?>/aeditprofile" enctype="multipart/form-data">
            
            <!-- Profile Picture Section -->
            <div class="form-section">
                <h2>📷 Profile Picture</h2>
                <div class="profile-picture-section">
                    <div class="current-picture" onclick="openPhotoModal()">
                        <?php 
                        $profilePicture = !empty($data['user']->profile_picture) 
                            ? $data['user']->profile_picture 
                            : '/assets/images/default-avatar.png';
                        ?>
                        <img src="<?= BASE_URL ?><?= $profilePicture ?>" 
                             alt="Profile Picture" 
                             id="profile-preview"
                             onerror="this.src='<?= BASE_URL ?>/assets/images/U.png'">
                        <p class="picture-note">Click to change photo</p>
                    </div>
                </div>
            </div>

            <!-- Personal Information Section -->
            <div class="form-section">
                <h2>👤 Personal Information</h2>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="first_name">First Name</label>
                        <input type="text" id="first_name" name="first_name" 
                               value="<?= htmlspecialchars($data['user']->first_name ?? '') ?>"
                               placeholder="Enter your first name">
                    </div>

                    <div class="form-group">
                        <label for="last_name">Last Name</label>
                        <input type="text" id="last_name" name="last_name" 
                               value="<?= htmlspecialchars($data['user']->last_name ?? '') ?>"
                               placeholder="Enter your last name">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="email">Email Address</label>
                        <input type="email" id="email" name="email" 
                               value="<?= htmlspecialchars($data['user']->email ?? '') ?>" 
                               readonly>
                        <small>Email cannot be changed</small>
                    </div>

                    <div class="form-group">
                        <label for="phone">Phone Number</label>
                        <input type="tel" id="phone" name="phone" 
                               value="<?= htmlspecialchars($data['user']->phone ?? '') ?>"
                               placeholder="+94771234567" 
                               pattern="\+94\d{9}">
                        <small>Format: +94xxxxxxxxx (e.g., +94771234567)</small>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="date_of_birth">Date of Birth</label>
                        <input type="date" id="date_of_birth" name="date_of_birth" 
                               value="<?= htmlspecialchars($data['user']->date_of_birth ?? '') ?>">
                    </div>

                    <div class="form-group">
                        <label>Gender</label>
                        <div class="radio-group-horizontal">
                            <label class="radio-option-inline">
                                <input type="radio" name="gender" value="male" 
                                       <?= ($data['user']->gender ?? '') === 'male' ? 'checked' : '' ?>>
                                Male
                            </label>
                            <label class="radio-option-inline">
                                <input type="radio" name="gender" value="female" 
                                       <?= ($data['user']->gender ?? '') === 'female' ? 'checked' : '' ?>>
                                Female
                            </label>
                            <label class="radio-option-inline">
                                <input type="radio" name="gender" value="other" 
                                       <?= ($data['user']->gender ?? '') === 'other' ? 'checked' : '' ?>>
                                Other
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Professional Information Section -->
            <div class="form-section">
                <h2>💼 Professional Information</h2>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="current_role">Current Job Title / Role</label>
                        <input type="text" id="current_role" name="current_role" 
                               value="<?= htmlspecialchars($data['user']->current_role ?? '') ?>"
                               placeholder="e.g., Software Engineer, Data Analyst">
                    </div>

                    <div class="form-group">
                        <label for="company">Company / Organization</label>
                        <input type="text" id="company" name="company" 
                               value="<?= htmlspecialchars($data['user']->company ?? '') ?>"
                               placeholder="e.g., Google, Microsoft, Startup Inc.">
                    </div>
                </div>
            </div>

            <!-- Education Section -->
            <div class="form-section">
                <h2>🎓 Education Background</h2>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="university_name">University / Institution</label>
                        <input type="text" id="university_name" name="university_name" 
                               value="<?= htmlspecialchars($data['user']->university_name ?? '') ?>"
                               placeholder="e.g., University of Colombo">
                    </div>

                    <div class="form-group">
                        <label for="degree_program">Degree Program</label>
                        <input type="text" id="degree_program" name="degree_program" 
                               value="<?= htmlspecialchars($data['user']->degree_program ?? '') ?>"
                               placeholder="e.g., B.Sc. in Computer Science">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="graduation_year">Graduation Year</label>
                        <select id="graduation_year" name="graduation_year">
                            <option value="">Select Year</option>
                            <?php 
                            $currentYear = date('Y');
                            for ($year = $currentYear; $year >= 1970; $year--): 
                            ?>
                                <option value="<?= $year ?>" 
                                        <?= ($data['user']->graduation_year ?? '') == $year ? 'selected' : '' ?>>
                                    <?= $year ?>
                                </option>
                            <?php endfor; ?>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Social Links Section -->
            <div class="form-section">
                <h2>🔗 Social & Professional Links</h2>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="linkedin_url">LinkedIn Profile URL</label>
                        <input type="url" id="linkedin_url" name="linkedin_url" 
                               value="<?= htmlspecialchars($data['user']->linkedin_url ?? '') ?>"
                               placeholder="https://linkedin.com/in/yourprofile">
                    </div>

                    <div class="form-group">
                        <label for="github_url">GitHub Profile URL</label>
                        <input type="url" id="github_url" name="github_url" 
                               value="<?= htmlspecialchars($data['user']->github_url ?? '') ?>"
                               placeholder="https://github.com/yourusername">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="portfolio_url">Portfolio / Website URL</label>
                        <input type="url" id="portfolio_url" name="portfolio_url" 
                               value="<?= htmlspecialchars($data['user']->portfolio_url ?? '') ?>"
                               placeholder="https://yourportfolio.com">
                    </div>
                </div>
            </div>

            <!-- About Section -->
            <div class="form-section">
                <h2>📝 About Me</h2>
                
                <div class="form-group">
                    <label for="short_bio">Short Bio / Skills & Experience</label>
                    <textarea id="short_bio" name="short_bio" 
                              placeholder="Tell us about yourself, your skills, experience, and what you can offer as a mentor..."
                    ><?= htmlspecialchars($data['user']->short_bio ?? '') ?></textarea>
                    <small>This will be displayed on your profile and visible to mentees</small>
                </div>
            </div>

            <!-- Mentorship Availability Section -->
            <div class="form-section">
                <h2>🎓 Mentorship Settings</h2>
                
                <div class="form-group">
                    <label style="display: flex; align-items: center; gap: 0.75rem; cursor: pointer;">
                        <input type="checkbox" 
                               id="mentorship_available" 
                               name="mentorship_available" 
                               value="1"
                               <?= ($data['user']->available_for_mentorship ?? false) ? 'checked' : '' ?>
                               style="width: 20px; height: 20px; accent-color: var(--primary-purple, #6c63ff); cursor: pointer;">
                        <span style="font-weight: 600; font-size: 1rem;">
                            I am available for mentorship
                        </span>
                    </label>
                    <small style="margin-left: 2rem; display: block; margin-top: 0.5rem;">
                        When enabled, undergraduate students will be able to see your profile and send mentorship requests.
                        You can disable this at any time.
                    </small>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="form-actions">
                <a href="<?= BASE_URL ?>/alumni/profile" class="btn btn-secondary">Cancel</a>
                <button type="submit" class="btn btn-primary">💾 Save Changes</button>
            </div>
        </form>
    </div>

    <?php
    // Include footer
    $footerFile = APPROOT . '/views/layout/footer.php';
    if (file_exists($footerFile)) {
        include $footerFile;
    }
    ?>

    <script>
        function openPhotoModal() {
            document.getElementById('photoModal').style.display = 'flex';
        }

        function closePhotoModal() {
            document.getElementById('photoModal').style.display = 'none';
        }

        function previewModalImage(input) {
            if (input.files && input.files[0]) {
                const file = input.files[0];
                
                // Check file size (5MB limit)
                if (file.size > 5 * 1024 * 1024) {
                    alert('File size must be less than 5MB');
                    input.value = '';
                    return;
                }
                
                // Check file type
                if (!file.type.match('image.*')) {
                    alert('Please select a valid image file');
                    input.value = '';
                    return;
                }
                
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('modal-profile-preview').src = e.target.result;
                    document.getElementById('profile-preview').src = e.target.result;
                }
                reader.readAsDataURL(file);
            }
        }

        // Close modal when clicking outside
        window.onclick = function(event) {
            const modal = document.getElementById('photoModal');
            if (event.target === modal) {
                closePhotoModal();
            }
        }

        // Close modal with Escape key
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                closePhotoModal();
            }
        });
    </script>
</body>
</html>
