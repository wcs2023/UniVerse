<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?= BASE_URL ?>/css/styles.css">
    <link rel="icon" type="image/png" href="<?= BASE_URL ?>/assets/images/U.png">
    <title>UniVerse - Profile Overview</title>
    
</head>
<body>
    <?php include 'Unavigation.view.php';?>

    <div class="profile-container">
        <!-- Profile Header -->
        <div class="profile-header">
            <div class="profile-image">
                <img src="<?= BASE_URL ?>/assets/images/logo bw.PNG" alt="Profile Photo">
            </div>
            <div class="profile-info">
                <h1>John Doe</h1>
                <p class="degree-info">B.Sc. Computer Science (Class of 2026)</p>
                <a href="<?= BASE_URL ?>/ueditprofile" class="edit-profile-btn">
                    Edit Profile
                </a>
            </div>
        </div>

        <!-- Profile Navigation -->
        <div class="profile-nav">
            <a href="<?= BASE_URL ?>/umyprofile" class="nav-item active">
                <!-- <i class="icon-person"></i> -->
                Profile Overview
            </a>
            <a href="<?= BASE_URL ?>/uachievements" class="nav-item">
                <!-- <i class="icon-achievement"></i> -->
                Achievements
            </a>
            <a href="<?= BASE_URL ?>/usettings" class="nav-item">
                <!-- <i class="icon-settings"></i> -->
                Settings
            </a>
        </div>

        <!-- Personal Info Section -->
        <div class="profile-content">
            <div class="section-header">
                <h2>Personal Information</h2>
                <!-- <button class="edit-btn">Edit</button> -->
            </div>

            <div class="info-grid">
                <!-- Email -->
                <div class="info-item">
                    <div class="info-label">
                        <i class="icon-email"></i>
                        Email
                    </div>
                    <div class="info-value">john.doe@universe.edu</div>
                </div>

                <!-- Phone -->
                <div class="info-item">
                    <div class="info-label">
                        <i class="icon-phone"></i>
                        Phone
                    </div>
                    <div class="info-value">+1 (555) 123-4567</div>
                </div>

                <!-- Date of Birth -->
                <div class="info-item">
                    <div class="info-label">
                        <i class="icon-calendar"></i>
                        Date of Birth
                    </div>
                    <div class="info-value">19/07/2004</div>
                </div>

                <!-- Gender -->
                <div class="info-item">
                    <div class="info-label">
                        <i class="icon-person"></i>
                        Gender
                    </div>
                    <div class="info-value">Male</div>
                </div>

                <!-- Address -->
                <div class="info-item">
                    <div class="info-label">
                        <i class="icon-address"></i>
                        Address
                    </div>
                    <div class="info-value">123 University Ave, UniCity, State</div>
                </div>
            </div>
        </div>

        <!-- Academic Info Section -->
        <div class="profile-content">
            <div class="section-header">
                <h2>Academic Information</h2>
                <!-- <button class="edit-btn">Edit</button> -->
            </div>

            <div class="info-grid">
                <!-- University -->
                <div class="info-item">
                    <div class="info-label">
                        <i class="icon-academic"></i>
                        University
                    </div>
                    <div class="info-value">University of Colombo</div>
                </div>

                <!-- Faculty -->
                <div class="info-item">
                    <div class="info-label">
                        <i class="icon-academic"></i>
                        Faculty
                    </div>
                    <div class="info-value">Faculty of Science</div>
                </div>

                <!-- Degree Program -->
                <div class="info-item">
                    <div class="info-label">
                        <i class="icon-academic"></i>
                        Degree Program
                    </div>
                    <div class="info-value">B.Sc. Computer Science</div>
                </div>

                <!-- Academic Year -->
                <div class="info-item">
                    <div class="info-label">
                        <i class="icon-academic"></i>
                        Academic Year
                    </div>
                    <div class="info-value">3rd Year</div>
                </div>

                <!-- Expected Graduation -->
                <div class="info-item">
                    <div class="info-label">
                        <i class="icon-calendar"></i>
                        Expected Graduation
                    </div>
                    <div class="info-value">2026</div>
                </div>
            </div>
        </div>
    </div>

    <?php include 'Ufooter.view.php'; ?>
    
    <!-- JavaScript at the bottom for better performance -->
    <!-- <script src="js/main.js"></script> -->
   
</body>
</html>