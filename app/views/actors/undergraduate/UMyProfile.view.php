<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/styles.css">
    <link rel="icon" type="image/png" href="<?= BASE_URL ?>/assets/images/U.png">
    <title>UniVerse - Profile Overview</title>
</head>
<body>
    <?php include 'Unavigation.view.php';?>

    
    <div class="profile-container">
        <!-- Profile Header -->
        <div class="profile-header">
            <div class="profile-image">
                <?php 
                $profilePicture = !empty($data['user']['profile_picture']) 
                    ? $data['user']['profile_picture'] 
                    : '/assets/images/U.png';
                ?>
                <img src="<?= BASE_URL ?><?= $profilePicture ?>" 
                     alt="Profile Photo"
                     onerror="this.onerror=null; this.src='<?= BASE_URL ?>/assets/images/U.png'">
            </div>
            <div class="profile-info">
                <h1><?= htmlspecialchars($data['user']['first_name'] . ' ' . $data['user']['last_name']) ?></h1>
                <p class="degree-info">
                    
                    <?= htmlspecialchars($data['profile']['degree_program'] ?? 'Undergraduate Student') ?> 
                        (<?= htmlspecialchars($data['profile']['expected_graduation_year'] ?? 'N/A') ?>)
                    
                </p>
                <a href="<?= BASE_URL ?>/ueditprofile" class="edit-profile-btn">
                    Edit Profile
                </a>
            </div>
        </div>

        <!-- Profile Navigation -->
        <div class="profile-nav">
            <a href="<?= BASE_URL ?>/umyprofile" class="nav-item active">
            Profile Overview
            </a>
            <a href="<?= BASE_URL ?>/uachievements" class="nav-item">
            Achievements
            </a>
            <!-- <a href="<?= BASE_URL ?>/ubookmarks" class="nav-item">
            Bookmarked Articles
            </a> -->
            <a href="<?= BASE_URL ?>/usettings" class="nav-item">
            Settings
            </a>
        </div>

        <!-- Profile Overview Section -->
        <div class="profile-content">
            <div class="section-header">
                <h2>Profile Overview</h2>
            </div>

            <div class="info-grid">
                <!-- Personal Information -->
                <!-- Email -->
                <div class="info-item">
                    <div class="info-label">
                        <i class="icon-email"></i>
                        Email
                    </div>
                    <div class="info-value"><?= htmlspecialchars($data['user']['email']) ?></div>
                </div>

                <!-- Phone -->
                <div class="info-item">
                    <div class="info-label">
                        <i class="icon-phone"></i>
                        Phone
                    </div>
                    <div class="info-value"><?= htmlspecialchars($data['user']['phone'] ?? 'Not provided') ?></div>
                </div>

                <!-- Date of Birth -->
                <div class="info-item">
                    <div class="info-label">
                        <i class="icon-calendar"></i>
                        Date of Birth
                    </div>
                    <div class="info-value">
                        <?= !empty($data['user']['date_of_birth']) 
                            ? date('d/m/Y', strtotime($data['user']['date_of_birth'])) 
                            : 'Not provided' 
                        ?>
                    </div>
                </div>

                <!-- Gender -->
                <div class="info-item">
                    <div class="info-label">
                        <i class="icon-person"></i>
                        Gender
                    </div>
                    <div class="info-value"><?= htmlspecialchars(ucfirst($data['user']['gender'] ?? 'Not specified')) ?></div>
                </div>

                <!-- Address -->
                <!-- <div class="info-item">
                    <div class="info-label">
                        <i class="icon-address"></i>
                        Address
                    </div>
                    <div class="info-value"><?= htmlspecialchars($data['user']['address'] ?? 'Not provided') ?></div>
                </div> -->

                <!-- Academic Information -->
                <!-- University -->
                <div class="info-item">
                    <div class="info-label">
                        <i class="icon-academic"></i>
                        University
                    </div>
                    <div class="info-value"><?= htmlspecialchars($data['profile']['university'] ?? 'Not provided') ?></div>
                </div>

                <!-- Faculty -->
                <div class="info-item">
                    <div class="info-label">
                        <i class="icon-academic"></i>
                        Faculty
                    </div>
                    <div class="info-value"><?= htmlspecialchars($data['profile']['faculty'] ?? 'Not provided') ?></div>
                </div>

                <!-- Degree Program -->
                <div class="info-item">
                    <div class="info-label">
                        <i class="icon-academic"></i>
                        Degree Program
                    </div>
                    <div class="info-value"><?= htmlspecialchars($data['profile']['degree_program'] ?? 'Not provided') ?></div>
                </div>

                <!-- Academic Year -->
                <div class="info-item">
                    <div class="info-label">
                        <i class="icon-academic"></i>
                        Academic Year
                    </div>
                    <div class="info-value"><?= htmlspecialchars($data['profile']['academic_year'] ?? 'Not provided') ?></div>
                </div>

                <!-- Expected Graduation -->
                <div class="info-item">
                    <div class="info-label">
                        <i class="icon-calendar"></i>
                        Expected Graduation
                    </div>
                    <div class="info-value"><?= htmlspecialchars($data['profile']['expected_graduation_year'] ?? 'Not provided') ?></div>
                </div>
            </div>
        </div>
    </div>

    <?php include __DIR__ . '/../../layout/footer.php'; ?>
    
</body>
</html>