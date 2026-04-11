<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UniVerse - Edit Profile</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/css/styles.css">
    <link rel="icon" type="image/png" href="<?= BASE_URL ?>/assets/images/U.png">
</head>

<body>
    <?php include_once __DIR__ . '/includes/header2.view.php'; ?>

    <div class="edit-profile-container">
        <div class="edit-profile-content">
            <div class="edit-main-content">
                <section class="personal-info-section">

                    <?php if (!empty($data['error'])): ?>
                        <div class="alert alert-error">
                            <?= htmlspecialchars($data['error']) ?>
                        </div>
                    <?php endif; ?>

                    <?php if (!empty($data['success'])): ?>
                        <div class="alert alert-success">
                            <?= htmlspecialchars($data['success']) ?>
                        </div>
                    <?php endif; ?>

                    <?php
                    // fallback if controller didn't pass profilePic
                    $profilePic = $data['profilePic']
                        ?? (!empty($data['user']['profile_picture']) ? $data['user']['profile_picture'] : '/assets/images/default-avatar.png');
                    ?>

                    <!-- Profile Photo Modal -->
                    <div id="photoModal" class="photo-modal" style="display: none;">
                        <form method="POST"
                            action="<?= BASE_URL ?>/StudentProfile/edit_Profile"
                            enctype="multipart/form-data"
                            id="photo-upload-form">
                            <div class="photo-modal-content">
                                <div class="photo-modal-header">
                                    <h3>Edit Profile Photo</h3>
                                    <button type="button" class="close-modal" onclick="closePhotoModal()">&times;</button>
                                </div>

                                <div class="photo-modal-body">
                                    <p>Upload a new profile picture. Click save when you're done.</p>

                                    <div class="photo-preview-container">
                                        <img src="<?= BASE_URL ?><?= htmlspecialchars($profilePic) ?>"
                                            alt="Profile Preview"
                                            id="modal-profile-preview"
                                            onerror="this.src='<?= BASE_URL ?>/assets/images/U.png'">
                                    </div>

                                    <button type="button"
                                        class="upload-photo-btn"
                                        onclick="document.getElementById('modal-profile-picture').click()">
                                        Upload New Photo
                                    </button>

                                    <input type="file"
                                        id="modal-profile-picture"
                                        name="profile_picture"
                                        accept="image/*"
                                        onchange="previewModalImage(this)"
                                        style="display: none;">
                                </div>

                                <div class="photo-modal-footer">
                                    <button type="button" class="btn btn-secondary" onclick="closePhotoModal()">Cancel</button>
                                    <button type="submit" class="btn btn-primary">Save Changes</button>
                                </div>
                            </div>
                        </form>
                    </div>

                    <form class="edit-profile-form"
                        method="POST"
                        action="<?= BASE_URL ?>/StudentProfile/edit_Profile"
                        enctype="multipart/form-data">

                        <!-- Profile Picture Section -->
                        <div class="form-section">
                            <h2>Profile Picture</h2>

                            <div class="profile-picture-section">
                                <div class="current-picture" onclick="openPhotoModal()">
                                    <img src="<?= BASE_URL ?><?= htmlspecialchars($profilePic) ?>"
                                        alt="Profile Picture"
                                        id="profile-preview"
                                        onerror="this.src='<?= BASE_URL ?>/assets/images/U.png'"
                                        style="width: 150px; height: 150px; border-radius: 50%; object-fit: cover; cursor: pointer;">
                                    <p class="picture-note">Click to edit</p>
                                </div>
                            </div>
                        </div>

                        <div class="form-section">
                            <h2>Personal Information</h2>
                            <br>

                            <div class="form-row">
                                <div class="form-group">
                                    <label for="first-name">First Name</label>
                                    <input type="text"
                                        id="first-name"
                                        name="first_name"
                                        value="<?= htmlspecialchars($data['user']['first_name'] ?? '') ?>">
                                </div>

                                <!-- <div class="form-group">
                                    <label for="middle-name">Middle Name</label>
                                    <input type="text"
                                        id="middle-name"
                                        name="middle_name"
                                        value="<//?= htmlspecialchars($data['user']['middle_name'] ?? '') ?>">
                                </div> -->

                                <div class="form-group">
                                    <label for="last-name">Last Name</label>
                                    <input type="text"
                                        id="last-name"
                                        name="last_name"
                                        value="<?= htmlspecialchars($data['user']['last_name'] ?? '') ?>">
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group">
                                    <label for="dob">Date of Birth</label>
                                    <input type="date"
                                        id="dob"
                                        name="date_of_birth"
                                        value="<?= htmlspecialchars($data['user']['date_of_birth'] ?? '') ?>">
                                </div>

                                <div class="form-group">
                                    <label>Gender</label>
                                    <?php $gender = $data['user']['gender'] ?? ''; ?>
                                    <div class="radio-group-horizontal">
                                        <label class="radio-option-inline">
                                            <input type="radio" name="gender" value="male" <?= $gender === 'male' ? 'checked' : '' ?>>
                                            <span class="radio-custom"></span>
                                            Male
                                        </label>

                                        <label class="radio-option-inline">
                                            <input type="radio" name="gender" value="female" <?= $gender === 'female' ? 'checked' : '' ?>>
                                            <span class="radio-custom"></span>
                                            Female
                                        </label>

                                        <label class="radio-option-inline">
                                            <input type="radio" name="gender" value="other" <?= $gender === 'other' ? 'checked' : '' ?>>
                                            <span class="radio-custom"></span>
                                            Other
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group">
                                    <label for="email">Email Address</label>
                                    <input type="email"
                                        id="email"
                                        name="email"
                                        value="<?= htmlspecialchars($data['user']['email'] ?? '') ?>"
                                        readonly>
                                    <small style="color: #666;">Email cannot be changed</small>
                                </div>

                                <div class="form-group">
                                    <label for="phone">Phone Number</label>
                                    <input type="tel"
                                        id="phone"
                                        name="phone"
                                        value="<?= htmlspecialchars($data['user']['phone'] ?? '') ?>"
                                        placeholder="+94771234567"
                                        pattern="\+94\d{9}">
                                    <small style="color: #666;">Format: +94xxxxxxxxx (e.g., +94771234567)</small>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="address1">Address</label>
                                <input type="text"
                                    id="address1"
                                    name="address_line1"
                                    value="<?= htmlspecialchars($data['user']['address'] ?? '') ?>">
                            </div>
                        </div>
                        <div class="form-section">
                            <h2>Change Password</h2>
                            <br>

                            <?php if (!empty($data['error'])): ?>
                                <div class="alert alert-error">
                                    <?= htmlspecialchars($data['error']) ?>
                                </div>
                            <?php endif; ?>

                            <?php if (!empty($data['success'])): ?>
                                <div class="alert alert-success">
                                    <?= htmlspecialchars($data['success']) ?>
                                </div>
                            <?php endif; ?>

                            <div class="form-row">
                                <div class="form-group">
                                    <label for="current-password">Current Password</label>
                                    <input
                                        type="password"
                                        id="current-password"
                                        name="current_password"
                                        autocomplete="current-password"
                                        value="">
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group">
                                    <label for="new-password">New Password</label>
                                    <input
                                        type="password"
                                        id="new-password"
                                        name="new_password"
                                        autocomplete="new-password"
                                        minlength="8"
                                        value="">
                                    <small style="color:#666;">Use at least 8 characters (recommended).</small>
                                </div>

                                <div class="form-group">
                                    <label for="confirm-password">Confirm New Password</label>
                                    <input
                                        type="password"
                                        id="confirm-password"
                                        name="confirm_password"
                                        autocomplete="new-password"
                                        minlength="8"
                                        value="">
                                </div>
                            </div>

                            <small style="color:#666;">
                                Leave these fields blank if you don’t want to change your password.
                            </small>
                        </div>

                        <div class="form-actions">
                            <button type="button"
                                class="btn btn-secondary"
                                onclick="window.location.href='<?= BASE_URL ?>/StudentProfile/profile'">
                                Cancel
                            </button>
                            <button type="submit" class="btn btn-primary">Save Changes</button>
                        </div>

                    </form>
                </section>
            </div>
        </div>
    </div>

    <?php include __DIR__ . '/../../layout/footer.php'; ?>

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

        window.onclick = function(event) {
            const modal = document.getElementById('photoModal');
            if (event.target === modal) {
                closePhotoModal();
            }
        }
    </script>
</body>

</html>