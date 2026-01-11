<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?= BASE_URL ?>/css/styles.css">
    <link rel="icon" type="image/png" href="<?= BASE_URL ?>/assets/images/U.png">
    <title>UniVerse - Achievements</title>
</head>
<body>
    <?php include 'Unavigation.view.php'; ?>

    <div class="profile-container">
        <!-- profile header  -->
        <div class="profile-header">
            <div class="profile-image">
                <?php 
                $profilePicture = !empty($data['user']['profile_picture']) 
                    ? $data['user']['profile_picture'] 
                    : '/assets/images/default-avatar.png';
                ?>
                <img src="<?= BASE_URL ?><?= $profilePicture ?>" 
                     alt="Profile Photo"
                     onerror="this.src='<?= BASE_URL ?>/assets/images/U.png'">
            </div>
            <div class="profile-info">
                <h1><?= htmlspecialchars($data['user']['first_name'] . ' ' . $data['user']['last_name']) ?></h1>
                <p class="degree-info">
                    <?= htmlspecialchars($data['profile']['degree_program'] ?? 'Undergraduate Student') ?> 
                    (Class of <?= htmlspecialchars($data['profile']['expected_graduation_year'] ?? 'N/A') ?>)
                </p>
                <a href="<?= BASE_URL ?>/ueditprofile" class="edit-profile-btn">
                    Edit Profile
                </a>
            </div>
        </div>

        <!-- Profile Navigation -->
        <div class="profile-nav">
            <a href="<?= BASE_URL ?>/umyprofile" class="nav-item ">
            Profile Overview
            </a>
            <a href="<?= BASE_URL ?>/uachievements" class="nav-item active">
            Achievements
            </a>
            <!-- <a href="<?= BASE_URL ?>/ubookmarks" class="nav-item">
            Bookmarked Articles
            </a> -->
            <a href="<?= BASE_URL ?>/usettings" class="nav-item">
            Settings
            </a>
        </div>

        <!-- Success/Error Messages -->
        <?php if (isset($data['success'])): ?>
            <div class="alert alert-success">
                <?= htmlspecialchars($data['success']) ?>
            </div>
        <?php endif; ?>
        
        <?php if (isset($data['error'])): ?>
            <div class="alert alert-error">
                <?= htmlspecialchars($data['error']) ?>
            </div>
        <?php endif; ?>

        <!-- Achievements Section -->
        <div class="achievements-container">
            <div class="section-header">
                <h2>Your Achievements</h2>
                <button class="add-achievement-btn">+ Add New</button>
            </div>

            <?php if (isset($data['achievements']) && !empty($data['achievements'])): ?>
            <div class="achievements-grid">
                <?php foreach ($data['achievements'] as $achievement): ?>
                <div class="achievement-card">
                    <div class="achievement-header">
                        <div class="achievement-title"><?= htmlspecialchars($achievement['title']) ?></div>
                        <span class="achievement-tag <?= strtolower(htmlspecialchars($achievement['achievement_type'])) ?>">
                            <?= ucfirst(htmlspecialchars($achievement['achievement_type'])) ?>
                        </span>
                    </div>
                    <div class="achievement-date"><?= date('F Y', strtotime($achievement['date_achieved'])) ?></div>
                    <div class="achievement-description">
                        <?= htmlspecialchars($achievement['description']) ?>
                    </div>
                    <?php if (!empty($achievement['institution'])): ?>
                    <div class="achievement-organization">
                        <strong>Issued by:</strong> <?= htmlspecialchars($achievement['institution']) ?>
                    </div>
                    <?php endif; ?>
                    <div class="achievement-actions">
                        <button class="edit-btn" onclick="editAchievement(<?= $achievement['achievement_id'] ?>)">✏️ Edit</button>
                        <button class="delete-btn" onclick="deleteAchievement(<?= $achievement['achievement_id'] ?>)">🗑️ Delete</button>
                        <?php if (!empty($achievement['certificate_url'])): ?>
                        <a href="<?= htmlspecialchars($achievement['certificate_url']) ?>" target="_blank" class="view-link">🔗 View Certificate</a>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            <?php else: ?>
            <div class="no-achievements">
                <p>No achievements added yet. Click "Add New" to get started!</p>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <?php include __DIR__ . '/../../layout/footer.php'; ?> 
    
    <script>
        function editAchievement(id) {
            window.location.href = '<?= BASE_URL ?>/uachievements/edit/' + id;
        }

        function deleteAchievement(id) {
            if (confirm('Are you sure you want to delete this achievement?')) {
                window.location.href = '<?= BASE_URL ?>/uachievements/delete/' + id;
            }
        }

        // Add achievement button functionality
        document.addEventListener('DOMContentLoaded', function() {
            const addBtn = document.querySelector('.add-achievement-btn');
            if (addBtn) {
                addBtn.addEventListener('click', function() {
                    window.location.href = '<?= BASE_URL ?>/uachievements/add';
                });
            }
        });

        window.onload = function() {
            window.scrollTo(0, 0);
        };
    </script>

    <style>
        .alert {
            padding: 15px;
            margin: 20px 0;
            border-radius: 8px;
            font-weight: 500;
        }

        .alert-success {
            background-color: #d1fae5;
            color: #065f46;
            border-left: 4px solid #10b981;
        }

        .alert-error {
            background-color: #fee2e2;
            color: #991b1b;
            border-left: 4px solid #ef4444;
        }

        .achievement-organization {
            margin-top: 10px;
            font-size: 14px;
            color: #666;
        }
    </style>
</body>
</html>
