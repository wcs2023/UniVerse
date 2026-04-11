<?php
    $title = "My Profile - School Leaver";
    include_once __DIR__ . '/includes/header2.view.php';
?>

<link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/student_style.css">

<main class="main-container">
    <div class="profile-container">
        <!-- Profile Header -->
        <section class="profile-header fade-in">
            <div class="profile-info">
                <div class="profile-avatar">
                    <i class="fa-solid fa-user-graduate"></i>
                </div>
                <div class="profile-details">
                    <h1>Welcome, <?= htmlspecialchars($data['user']['name']) ?>!</h1>
                    <p class="profile-type">School Leaver</p>
                    <p class="profile-email"><?= htmlspecialchars($data['user']['email']) ?></p>
                </div>
            </div>
            <div class="profile-actions">
                <a href="<?= BASE_URL ?>/schoolleaver/profile/edit" class="btn btn-primary">Edit Profile</a>
            </div>
        </section>
        
        <!-- Quick Links -->
        <section class="quick-links fade-in">
            <h2>Quick Actions</h2>
            <div class="links-grid">
                <a href="<?= BASE_URL ?>/schoolleaver/degreeSuggestion" class="link-card">
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
                <a href="<?= BASE_URL ?>/Discussion_forum" class="link-card">
                    <div class="link-icon">
                        <i class="fa-solid fa-users"></i>
                    </div>
                    <div class="link-content">
                        <h3>Join Forums</h3>
                        <p>Connect with other students</p>
                    </div>
                </a>
            </div>
        </section>
    </div>
</main>


<?php include __DIR__ . '/../../layout/footer.php'; ?>
<style>
/* Profile-specific styles */
.profile-container {
    max-width: 1000px;
    margin: 0 auto;
}

.profile-header {
    background: var(--white);
    border-radius: 12px;
    padding: 2rem;
    margin-bottom: 2rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
}

.profile-info {
    display: flex;
    align-items: center;
    gap: 1.5rem;
}

.profile-avatar {
    width: 80px;
    height: 80px;
    background: var(--primary-purple);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 2rem;
}

.profile-details h1 {
    margin: 0 0 0.5rem 0;
    color: var(--text-dark);
    font-size: 1.8rem;
}

.profile-type {
    color: var(--primary-purple);
    font-weight: 600;
    margin: 0;
}

.profile-email {
    color: var(--text-light);
    margin: 0.25rem 0 0 0;
}

.profile-stats {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1.5rem;
    margin-bottom: 2rem;
}

.stat-card {
    background: var(--white);
    padding: 1.5rem;
    border-radius: 12px;
    display: flex;
    align-items: center;
    gap: 1rem;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    transition: transform 0.3s ease;
}

.stat-card:hover {
    transform: translateY(-5px);
}

.stat-icon {
    width: 50px;
    height: 50px;
    background: rgba(107, 70, 193, 0.1);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--primary-purple);
    font-size: 1.5rem;
}

.stat-info h3 {
    margin: 0;
    font-size: 1.8rem;
    color: var(--text-dark);
}

.stat-info p {
    margin: 0;
    color: var(--text-light);
    font-size: 0.9rem;
}

.recent-activities {
    background: var(--white);
    border-radius: 12px;
    padding: 2rem;
    margin-bottom: 2rem;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
}

.recent-activities h2 {
    margin: 0 0 1.5rem 0;
    color: var(--text-dark);
}

.activity-item {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1rem 0;
    border-bottom: 1px solid var(--border-color);
}

.activity-item:last-child {
    border-bottom: none;
}

.activity-icon {
    color: var(--primary-purple);
    font-size: 1.2rem;
}

.activity-content p {
    margin: 0;
    color: var(--text-dark);
    font-weight: 500;
}

.activity-time {
    color: var(--text-light);
    font-size: 0.85rem;
}

.no-activities {
    text-align: center;
    padding: 2rem;
    color: var(--text-light);
}

.no-activities i {
    font-size: 3rem;
    margin-bottom: 1rem;
    color: var(--text-light);
}

.quick-links {
    background: var(--white);
    border-radius: 12px;
    padding: 2rem;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
}

.quick-links h2 {
    margin: 0 0 1.5rem 0;
    color: var(--text-dark);
}

.links-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 1.5rem;
}

.link-card {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1.5rem;
    background: var(--light-gray);
    border-radius: 12px;
    text-decoration: none;
    transition: all 0.3s ease;
    border: 2px solid transparent;
}

.link-card:hover {
    border-color: var(--primary-purple);
    transform: translateY(-3px);
    box-shadow: 0 8px 25px rgba(107, 70, 193, 0.15);
}

.link-icon {
    width: 50px;
    height: 50px;
    background: var(--primary-purple);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.5rem;
}

.link-content h3 {
    margin: 0 0 0.25rem 0;
    color: var(--text-dark);
    font-size: 1.1rem;
}

.link-content p {
    margin: 0;
    color: var(--text-light);
    font-size: 0.9rem;
}

@media (max-width: 768px) {
    .profile-header {
        flex-direction: column;
        gap: 1.5rem;
        text-align: center;
    }

    .profile-info {
        flex-direction: column;
        text-align: center;
    }

    .profile-stats {
        grid-template-columns: 1fr;
    }
    
    .links-grid {
        grid-template-columns: 1fr;
    }
}
</style>

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