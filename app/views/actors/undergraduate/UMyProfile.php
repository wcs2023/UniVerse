<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../public/css/styles.css">
    <link rel="icon" type="image/png" href="../../public/images/U.png">
    <title>UniVerse - My Profile</title>
    <script src="../../public/main.js"></script>
    
</head>
<body>
    <?php include 'Unavigation.php'; ?>

    <div class="profile-container">
        <!-- Profile Header -->
        <div class="profile-header">
            <div class="profile-image">
                <img src="../../public/images/profile-placeholder.jpg" alt="Profile Photo">
            </div>
            <div class="profile-info">
                <h1>John Doe</h1>
                <p class="degree-info">B.Sc. Computer Science (Class of 2026)</p>
                <button class="edit-profile-btn">
                    <i class="icon-edit"></i>
                    Edit Profile
                </button>
            </div>
        </div>

        <!-- Profile Navigation -->
        <div class="profile-nav">
            <button class="nav-item active">
                <i class="icon-person"></i>
                Personal Info
            </button>
            <button class="nav-item">
                <i class="icon-academic"></i>
                Academic Info
            </button>
            <button class="nav-item">
                <i class="icon-achievement"></i>
                Achievements
            </button>
            <button class="nav-item">
                <i class="icon-settings"></i>
                Settings
            </button>
        </div>

        <!-- Personal Info Section -->
        <div class="profile-content">
            <div class="section-header">
                <h2>Personal Info</h2>
                <button class="edit-btn">Edit</button>
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

                <!-- Address -->
                <div class="info-item">
                    <div class="info-label">
                        <i class="icon-address"></i>
                        Address
                    </div>
                    <div class="info-value">123 University Ave, UniCity, State 12345</div>
                </div>

                <!-- Date of Birth -->
                <div class="info-item">
                    <div class="info-label">
                        <i class="icon-calendar"></i>
                        Date of Birth
                    </div>
                    <div class="info-value">19/07/2004</div>
                </div>
            </div>
        </div>
    </div>

    <?php include 'Ufooter.php'; ?>
</body>
</html>