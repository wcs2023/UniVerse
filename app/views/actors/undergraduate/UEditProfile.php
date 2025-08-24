<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UniVerse - Edit Profile</title>
    <link rel="stylesheet" href="../../public/css/styles.css">
    <link rel="icon" type="image/png" href="../../public/images/U.png">
</head>
<body>
    <?php include 'Unavigation.php'; ?>

    <div class="edit-profile-container">
        <div class="edit-profile-nav">
            <a href="#" class="nav-link active">Edit Profile</a>
            <a href="#" class="nav-link">Edit Academic Info</a>
            <a href="#" class="nav-link">Edit Achievements</a>
            <a href="#" class="nav-link">Settings</a>
        </div>

        <div class="edit-profile-content">
            <div class="edit-main-content">
                <section class="personal-info-section">
                    <h2>Personal Information</h2>
                    <p class="section-description">Update your personal contact details and demographic information.</p>

                    <form class="edit-profile-form">
                        <div class="form-section">
                            <h3>Contact Information</h3>
                            
                            <div class="form-group">
                                <label for="email">Email Address</label>
                                <input type="email" id="email" name="email" value="sarah.connor@universe.edu">
                            </div>

                            <div class="form-group">
                                <label for="phone">Phone Number</label>
                                <input type="tel" id="phone" name="phone" value="+1 (555) 987-6543">
                            </div>

                            <div class="form-group">
                                <label for="address1">Address Line 1</label>
                                <input type="text" id="address1" name="address1" value="456 Campus Way">
                            </div>

                            <div class="form-group">
                                <label for="address2">Address Line 2 (Optional)</label>
                                <input type="text" id="address2" name="address2" value="Suite 100">
                            </div>

                            <div class="form-row">
                                <div class="form-group">
                                    <label for="city">City</label>
                                    <input type="text" id="city" name="city" value="Academic Heights">
                                </div>

                                <div class="form-group">
                                    <label for="state">State / Province</label>
                                    <input type="text" id="state" name="state" value="New York">
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group">
                                    <label for="postal">Postal Code</label>
                                    <input type="text" id="postal" name="postal" value="10001">
                                </div>

                                <div class="form-group">
                                    <label for="country">Country</label>
                                    <input type="text" id="country" name="country" value="United States">
                                </div>
                            </div>
                        </div>

                        <div class="form-section">
                            <h3>Demographic Details</h3>
                            
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="dob">Date of Birth</label>
                                    <input type="date" id="dob" name="dob" value="2002-09-15">
                                </div>

                                <div class="form-group">
                                    <label for="studentId">Student ID</label>
                                    <input type="text" id="studentId" name="studentId" value="UVP2023001" readonly>
                                </div>
                            </div>
                        </div>

                        <div class="form-actions">
                            <button type="button" class="btn btn-secondary">Cancel</button>
                            <button type="submit" class="btn btn-primary">Save Changes</button>
                        </div>
                    </form>
                </section>
            </div>

            <aside class="profile-preview">
                <div class="preview-card">
                    <h3>My Profile</h3>
                    <div class="profile-image-preview">
                        <img src="../../public/images/profile-placeholder.jpg" alt="Sarah Connor">
                    </div>
                    <h4>Sarah Connor</h4>
                    <p class="student-id">UVP2023001</p>
                    <p class="student-email">sarah.connor@universe.edu</p>
                    <a href="#" class="view-profile-link">View Full Profile</a>
                </div>
            </aside>
        </div>
    </div>

    <?php include 'Ufooter.php'; ?>
</body>
</html>
