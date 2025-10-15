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
    <?php include 'Unavigation.view.php'; ?>

    <div class="edit-profile-container">
        <div class="edit-profile-content">
            <div class="edit-main-content">
                <section class="personal-info-section">
                    <!-- <p class="section-description">Update your personal contact details and demographic information.</p> -->
                    
                    <form class="edit-profile-form">
                        <!-- Profile Picture Section -->
                        <div class="form-section">
                            <h2>Profile Picture</h2>
                            
                            <div class="profile-picture-section">
                                <div class="current-picture" onclick="openPhotoModal()">
                                    <img src="<?= BASE_URL ?>/assets/images/logo bw.PNG" alt="Profile Picture" id="profile-preview">
                                    <p class="picture-note">Click to edit</p>
                                </div>
                            </div>
                        </div>

                        <!-- Profile Photo Modal -->
                        <div id="photoModal" class="photo-modal" style="display: none;">
                            <div class="photo-modal-content">
                                <div class="photo-modal-header">
                                    <h3>Edit Profile Photo</h3>
                                    <button type="button" class="close-modal" onclick="closePhotoModal()">&times;</button>
                                </div>
                                <div class="photo-modal-body">
                                    <p>Upload a new profile picture. Click save when you're done.</p>
                                    <div class="photo-preview-container">
                                        <img src="<?= BASE_URL ?>/assets/images/logo bw.PNG" alt="Profile Preview" id="modal-profile-preview">
                                    </div>
                                    <button type="button" class="upload-photo-btn" onclick="document.getElementById('modal-profile-picture').click()">Upload New Photo</button>
                                    <input type="file" id="modal-profile-picture" name="profile-picture" accept="image/*" onchange="previewModalImage(this)" style="display: none;">
                                </div>
                                <div class="photo-modal-footer">
                                    <button type="button" class="btn btn-secondary" onclick="closePhotoModal()">Cancel</button>
                                    <button type="button" class="btn btn-primary" onclick="savePhotoChanges()">Save Changes</button>
                                </div>
                            </div>
                        </div>

                        <div class="form-section">
                            <h2>Personal Information</h2> <br>
                            
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="first-name">First Name</label>
                                    <input type="text" id="first-name" name="first-name" value="John" required>
                                </div>

                                <div class="form-group">
                                    <label for="middle-name">Middle Name</label>
                                    <input type="text" id="middle-name" name="middle-name" value="Michael">
                                </div>

                                <div class="form-group">
                                    <label for="last-name">Last Name</label>
                                    <input type="text" id="last-name" name="last-name" value="Doe" required>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group">
                                    <label for="dob">Date of Birth</label>
                                    <input type="date" id="dob" name="dob" value="2004-07-19" required>
                                </div>

                                <div class="form-group">
                                    <label>Gender</label>
                                    <div class="radio-group-horizontal">
                                        <label class="radio-option-inline">
                                            <input type="radio" name="gender" value="male" checked>
                                            <span class="radio-custom"></span>
                                            Male
                                        </label>
                                        <label class="radio-option-inline">
                                            <input type="radio" name="gender" value="female">
                                            <span class="radio-custom"></span>
                                            Female
                                        </label>
                                        <label class="radio-option-inline">
                                            <input type="radio" name="gender" value="other">
                                            <span class="radio-custom"></span>
                                            Other
                                        </label>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="email">Email Address</label>
                                    <input type="email" id="email" name="email" value="john.doe@universe.edu" required>
                                </div>

                                <div class="form-group">
                                    <label for="phone">Phone Number</label>
                                    <input type="tel" id="phone" name="phone" value="+1 (555) 123-4567" required>
                                </div>
                            </div>

                            <!-- <h3>Address Information</h3> -->

                            <div class="form-group">
                                <label for="address1">Address Line 1</label>
                                <input type="text" id="address1" name="address1" value="123 University Ave">
                            </div>

                            <div class="form-group">
                                <label for="address2">Address Line 2 (Optional)</label>
                                <input type="text" id="address2" name="address2" value="">
                            </div>

                            <div class="form-row">
                                <div class="form-group">
                                    <label for="city">City</label>
                                    <input type="text" id="city" name="city" value="UniCity">
                                </div>

                                <div class="form-group">
                                    <label for="state">State / Province</label>
                                    <input type="text" id="state" name="state" value="State">
                                </div>

                              
                            </div>
                        </div>

                        <!-- Academic Information Section -->
                        <div class="form-section">
                            <h2>Academic Information</h2> <br>
                            
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="university">University</label>
                                    <input type="text" id="university" name="university" value="University of Colombo" required>
                                </div>

                                <div class="form-group">
                                    <label for="faculty">Faculty</label>
                                    <input type="text" id="faculty" name="faculty" value="Faculty of Science" required>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group">
                                    <label for="degree">Degree Program</label>
                                    <input type="text" id="degree" name="degree" value="B.Sc. Computer Science" required>
                                </div>

                                <div class="form-group">
                                    <label for="academic-year">Academic Year</label>
                                    <select id="academic-year" name="academic-year" required>
                                        <option value="">Select Year</option>
                                        <option value="1st Year">1st Year</option>
                                        <option value="2nd Year">2nd Year</option>
                                        <option value="3rd Year" selected>3rd Year</option>
                                        <option value="4th Year">4th Year</option>
                                        <option value="Graduate">Graduate</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group">
                                    <label for="graduation-year">Expected Graduation Year</label>
                                    <input type="number" id="graduation-year" name="graduation-year" value="2026" min="2024" max="2030" required>
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
                }
                reader.readAsDataURL(file);
            }
        }

        function savePhotoChanges() {
            const modalPreview = document.getElementById('modal-profile-preview').src;
            document.getElementById('profile-preview').src = modalPreview;
            closePhotoModal();
            alert('Profile photo updated successfully!');
        }

        // Close modal when clicking outside
        window.onclick = function(event) {
            const modal = document.getElementById('photoModal');
            if (event.target == modal) {
                closePhotoModal();
            }
        }

        // Form submission handling
        document.querySelector('.edit-profile-form').addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Here you would normally send the form data to the server
            // For now, just show a success message
            alert('Profile updated successfully!');
            
            // Redirect to profile page
            window.location.href = '<?= BASE_URL ?>/umyprofile';
        });
    </script>
</body>
</html>
