<head>
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/student_profile.css">
    <link rel="icon" type="image/png" href="<?= BASE_URL ?>/assets/images/U.png" style="border-radius: 20px;">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">    
</head>
<main class="main-container">
    <?php
        $title = "My Profile - School Leaver";
        include_once __DIR__ . '/includes/header2.view.php';
    ?>
    <div class="profile-container" style="margin-top: 2rem;">
        <!-- Profile Header -->
        <section class="profile-header fade-in">
            <div class="profile-info">
                <div class="profile-avatar">
                    <i class="fa-solid fa-user-graduate"></i>
                </div>
                <div class="profile-details">
                    <h1>Welcome, <?= htmlspecialchars(ucfirst($userData['first_name']) . ' ' . ucfirst($userData['last_name'])) ?></h1>
                    <p class="profile-type"><?= htmlspecialchars(ucfirst($userData['user_type'])) ?></p>
                    <p class="profile-email"><?= htmlspecialchars($userData['email']) ?></p>
                </div>
            </div>
            <div class="profile-actions">
                <a href="<?= BASE_URL ?>/StudentProfile/edit_Profile" class="edit-profile-btn btn btn-primary">Edit Profile</a>
            </div>
        </section>

        <!-- Quick Stats -->
        <section class="profile-stats fade-in">                
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fa-solid fa-comments"></i>
                </div>
                <div class="stat-info">
                    <h3><?= $data['count'] ?></h3>
                    <p>Forum Posts</p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fa-solid fa-newspaper"></i>
                </div>
                <div class="stat-info">
                    <h3>8</h3>
                    <p>Articles Read</p>
                </div>
            </div>
        </section>

        <!-- Quick Links -->
        <section class="quick-links fade-in" style="margin-bottom: 4rem;">
            <h2>Quick Actions</h2>
            <div class="links-grid">
                <a href="<?= BASE_URL ?>/Degrees/degree_suggestion_index" class="link-card">
                    <div class="link-icon">
                        <i class="fa-solid fa-graduation-cap"></i>
                    </div>
                    <div class="link-content">
                        <h3>Find Degrees</h3>
                        <p>Get personalized degree suggestions</p>
                    </div>
                </a>
                <a href="<?= BASE_URL ?>/schoolleaver/articles" class="link-card">
                    <div class="link-icon">
                        <i class="fa-solid fa-newspaper"></i>
                    </div>
                    <div class="link-content">
                        <h3>Read Articles</h3>
                        <p>Career guidance and tips</p>
                    </div>
                </a>
                <a href="<?= BASE_URL ?>/Discussion_Forum/index" class="link-card">
                    <div class="link-icon">
                        <i class="fa-solid fa-users"></i>
                    </div>
                    <div class="link-content">
                        <h3>Join Forums</h3>
                        <p>Connect with other students</p>
                    </div>
                </a>
                <!-- <a href="<?= BASE_URL ?>/schoolleaver/contact" class="link-card">
                    <div class="link-icon">
                        <i class="fa-solid fa-envelope"></i>
                    </div>
                    <div class="link-content">
                        <h3>Get Support</h3>
                        <p>Contact our career advisors</p>
                    </div>
                </a> -->
            </div>
        </section>
    </div>
</main>


<?php include __DIR__ . '/../../layout/footer.php'; ?>


<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Add staggered animation to stats
        const statCards = document.querySelectorAll('.stat-card');
        statCards.forEach((card, index) => {
            card.style.animationDelay = `${index * 0.1}s`;
            card.classList.add('fade-in');
        });

        // Add animation to activity items
        const activities = document.querySelectorAll('.activity-item');
        activities.forEach((item, index) => {
            item.style.animationDelay = `${index * 0.1}s`;
            item.classList.add('fade-in');
        });
    });
</script>

</body>
</html>