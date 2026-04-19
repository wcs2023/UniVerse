<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UniVerse - Edit Profile</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/styles.css">
    <link rel="icon" type="image/png" href="<?= BASE_URL ?>/assets/images/U.png">
</head>
<body>
    <?php include 'Unavigation.view.php'; ?>

    <div class="edit-profile-container">
        <div class="edit-profile-content">
            <div class="edit-main-content">
                <section class="personal-info-section">
                    
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
                        <form method="POST" action="<?= BASE_URL ?>/ueditprofile" enctype="multipart/form-data" id="photo-upload-form">
                            <div class="photo-modal-content">
                                <div class="photo-modal-header">
                                    <h3>Edit Profile Photo</h3>
                                    <button type="button" class="close-modal" onclick="closePhotoModal()">&times;</button>
                                </div>
                                <div class="photo-modal-body">
                                    <p>Upload a new profile picture. Click save when you're done.</p>
                                    <div class="photo-preview-container">
                                        <?php 
                                        $profilePic = !empty($data['user']['profile_picture']) 
                                            ? $data['user']['profile_picture'] 
                                            : '/assets/images/U.png';
                                        ?>
                                        <img src="<?= BASE_URL ?><?= $profilePic ?>" 
                                             alt="Profile Preview" 
                                             id="modal-profile-preview"
                                             onerror="this.src='<?= BASE_URL ?>/assets/images/U.png'">
                                    </div>
                                    <button type="button" class="upload-photo-btn" onclick="document.getElementById('modal-profile-picture').click()">Upload New Photo</button>
                                    <input type="file" id="modal-profile-picture" name="profile_picture" accept="image/*" onchange="previewModalImage(this)" style="display: none;">
                                </div>
                                <div class="photo-modal-footer">
                                    <button type="button" class="btn btn-secondary" onclick="closePhotoModal()">Cancel</button>
                                    <button type="submit" class="btn btn-primary">Save Changes</button>
                                </div>
                            </div>
                        </form>
                    </div>
                    
                    <form class="edit-profile-form" method="POST" action="<?= BASE_URL ?>/ueditprofile" enctype="multipart/form-data">
                        <!-- Profile Picture Section -->
                        <div class="form-section">
                            <h2>Profile Picture</h2>
                            
                            <div class="profile-picture-section">
                                <div class="current-picture" onclick="openPhotoModal()">
                                    <?php 
                                    $profilePicture = !empty($data['user']['profile_picture']) 
                                        ? $data['user']['profile_picture'] 
                                        : '/assets/images/U.png';
                                    ?>
                                    <img src="<?= BASE_URL ?><?= $profilePicture ?>" 
                                         alt="Profile Picture" 
                                         id="profile-preview"
                                         onerror="this.onerror=null;   this.src='<?= BASE_URL ?>/assets/images/U.png'"
                                         style="width: 150px; height: 150px; border-radius: 50%; object-fit: cover; cursor: pointer;">
                                    <p class="picture-note">Click to edit</p>
                                </div>
                            </div>
                        </div>

                        <div class="form-section">
                            <h2>Personal Information</h2> <br>
                            
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="first-name">First Name </label>
                                    <input type="text" id="first-name" name="first_name" value="<?= htmlspecialchars($data['user']['first_name'] ?? '') ?>">
                                </div>

                                <div class="form-group">
                                    <label for="last-name">Last Name </label>
                                    <input type="text" id="last-name" name="last_name" value="<?= htmlspecialchars($data['user']['last_name'] ?? '') ?>">
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group">
                                    <label for="dob">Date of Birth </label>
                                    <input type="date" id="dob" name="date_of_birth" value="<?= htmlspecialchars($data['user']['date_of_birth'] ?? '') ?>">
                                </div>

                                <div class="form-group">
                                    <label>Gender </label>
                                    <div class="radio-group-horizontal">
                                        <label class="radio-option-inline">
                                            <input type="radio" name="gender" value="male" <?= ($data['user']['gender'] ?? '') === 'male' ? 'checked' : '' ?>>
                                            <span class="radio-custom"></span>
                                            Male
                                        </label>
                                        <label class="radio-option-inline">
                                            <input type="radio" name="gender" value="female" <?= ($data['user']['gender'] ?? '') === 'female' ? 'checked' : '' ?>>
                                            <span class="radio-custom"></span>
                                            Female
                                        </label>
                                        <label class="radio-option-inline">
                                            <input type="radio" name="gender" value="other" <?= ($data['user']['gender'] ?? '') === 'other' ? 'checked' : '' ?>>
                                            <span class="radio-custom"></span>
                                            Other
                                        </label>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="email">Email Address</label>
                                    <input type="email" id="email" name="email" value="<?= htmlspecialchars($data['user']['email'] ?? '') ?>" readonly>
                                    <small style="color: #666;">Email cannot be changed</small>
                                </div>

                                <div class="form-group">
                                    <label for="phone">Phone Number </label>
                                    <input type="tel" id="phone" name="phone" 
                                           value="<?= htmlspecialchars($data['user']['phone'] ?? '') ?>"
                                           placeholder="+94771234567" pattern="\+94\d{9}">
                                    <small style="color: #666;">Format: +94xxxxxxxxx (e.g., +94771234567)</small>
                                </div>
                            </div>
                            <!-- <div class="form-group">
                                <label for="school">School</label>
                                <input type="text" id=school name="school"
                                        value ="<//?= htmlspecialchars($data['profile']['school']) ?>">
                            </div> -->

                        </div>
                        <!-- Academic Information Section -->
                        <div class="form-section">
                            <h2>Academic Information</h2> <br>
                            
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="university">University </label>
                                    <input type="text" id="university" name="university" value="<?= htmlspecialchars($data['profile']['university'] ?? '') ?>">
                                </div>

                                <div class="form-group">
                                    <label for="faculty">Faculty </label>
                                    <input type="text" id="faculty" name="faculty" value="<?= htmlspecialchars($data['profile']['faculty'] ?? '') ?>">
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group">
                                    <label for="degree">Degree Program </label>
                                    <input type="text" id="degree" name="degree_program" value="<?= htmlspecialchars($data['profile']['degree_program'] ?? '') ?>">
                                </div>

                                <div class="form-group">
                                    <label for="academic-year">Academic Year </label>
                                    <select id="academic-year" name="academic_year">
                                        <option value="">Select Year</option>
                                        <option value="1st year" <?= ($data['profile']['academic_year'] ?? '') === '1st year' ? 'selected' : '' ?>>1st Year</option>
                                        <option value="2nd year" <?= ($data['profile']['academic_year'] ?? '') === '2nd year' ? 'selected' : '' ?>>2nd Year</option>
                                        <option value="3rd year" <?= ($data['profile']['academic_year'] ?? '') === '3rd year' ? 'selected' : '' ?>>3rd Year</option>
                                        <option value="4th year" <?= ($data['profile']['academic_year'] ?? '') === '4th year' ? 'selected' : '' ?>>4th Year</option>
                                        <option value="Graduate" <?= ($data['profile']['academic_year'] ?? '') === 'Graduate' ? 'selected' : '' ?>>Graduate</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group">
                                    <label for="graduation-year">Expected Graduation Year *</label>
                                    <input type="number" id="graduation-year" name="expected_graduation_year" value="<?= htmlspecialchars($data['profile']['expected_graduation_year'] ?? '') ?>" min="2024" max="2035">
                                </div>
                            </div>
                        </div>

                        <div class="form-actions">
                            <button type="button" class="btn btn-secondary" onclick="window.location.href='<?= BASE_URL ?>/umyprofile'">Cancel</button>
                            <button type="submit" class="btn btn-primary">Save Changes</button>
                        </div>
                    </form>
                </section>
            </div>
        </div>
    </div>

    <?php include __DIR__ . '/../../layout/footer.php'; ?>
    <script>
        var today = new Date();
        var maxData = new Date(today.setFullYear(today.getFullYear() - 16));
        var formattedDate = maxData.toISOString().split('T')[0];
        document.getElementById('dob').setAttribute('max',formattedDate);
                        
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
            if (event.target == modal) {
                closePhotoModal();
            }
        }
    </script>
</body>
</html>
