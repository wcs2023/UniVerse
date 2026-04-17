<?php
// Check if URLROOT is defined, if not define it
if (!defined('URLROOT')) {
    define('URLROOT', 'http://localhost/UniVerse/public');
}
if (!defined('BASE_URL')) {
    define('BASE_URL', 'http://localhost/UniVerse/public');
}

// Get current page for active state
$current_uri = $_SERVER['REQUEST_URI'];
$current_path = parse_url($current_uri, PHP_URL_PATH);
?>

<link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/alumni.css">

<header class="header" role="banner">
    <div class="container">
        <div class="nav-brand">
            <a href="<?= BASE_URL ?>/alumni" style="font-size: 1.5rem; font-weight: bold; color: #6b46c1; text-decoration: none;" aria-label="UniVerse Home">
                UniVerse
            </a>
        </div>
        <nav class="nav-menu" id="nav-menu" role="navigation" aria-label="Main navigation">
            <ul class="nav-list">
                <li class="nav-item">
                    <a href="<?= BASE_URL ?>/alumni" class="nav-link <?= (strpos($current_path, '/alumni') !== false && strpos($current_path, '/articles') === false && strpos($current_path, '/mentorships') === false && strpos($current_path, '/profile') === false && strpos($current_path, '/settings') === false) ? 'active' : '' ?>" <?= (strpos($current_path, '/alumni') !== false && strpos($current_path, '/articles') === false && strpos($current_path, '/mentorships') === false && strpos($current_path, '/profile') === false && strpos($current_path, '/settings') === false) ? 'aria-current="page"' : '' ?>>Home</a>
                </li>
                <li class="nav-item">
                    <a href="<?= BASE_URL ?>/aarticles" class="nav-link <?= (strpos($current_path, '/articles') !== false || strpos($current_path, '/aarticles') !== false) ? 'active' : '' ?>" <?= (strpos($current_path, '/articles') !== false || strpos($current_path, '/aarticles') !== false) ? 'aria-current="page"' : '' ?>>Articles</a>
                </li>
                <li class="nav-item">
                    <a href="<?= BASE_URL ?>/Discussion_forum" class="nav-link <?= (strpos($current_path, '/Discussion_forum') !== false) ? 'active' : '' ?>" <?= (strpos($current_path, '/adiscussion') !== false) ? 'aria-current="page"' : '' ?>>Discussion Forums</a>
                </li>
                <li class="nav-item">
                    <a href="<?= BASE_URL ?>/amentorships" class="nav-link <?= (strpos($current_path, '/mentorships') !== false || strpos($current_path, '/amentorships') !== false) ? 'active' : '' ?>" <?= (strpos($current_path, '/mentorships') !== false || strpos($current_path, '/amentorships') !== false) ? 'aria-current="page"' : '' ?>>Mentoring</a>
                </li>
                <li class="nav-item">
                    <a href="<?= BASE_URL ?>/alumni/profile" class="nav-link <?= (strpos($current_path, '/profile') !== false || strpos($current_path, '/settings') !== false) ? 'active' : '' ?>" <?= (strpos($current_path, '/profile') !== false || strpos($current_path, '/settings') !== false) ? 'aria-current="page"' : '' ?>>My Profile</a>
                </li>
                <li class="nav-item">
                    <a href="<?= BASE_URL ?>/logout" class="nav-link logout-btn" onclick="return confirm('Are you sure you want to logout?')" aria-label="Logout from UniVerse">Logout</a>
                </li>
            </ul>
        </nav>
        <button class="mobile-menu-btn" id="mobile-menu-btn" aria-label="Toggle navigation menu" aria-expanded="false" aria-controls="nav-menu">
            <span aria-hidden="true"></span>
            <span aria-hidden="true"></span>
            <span aria-hidden="true"></span>
        </button>
    </div>
</header>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const mobileMenuBtn = document.getElementById('mobile-menu-btn');
    const navMenu = document.getElementById('nav-menu');
    
    if (mobileMenuBtn && navMenu) {
        mobileMenuBtn.addEventListener('click', function() {
            const isExpanded = mobileMenuBtn.getAttribute('aria-expanded') === 'true';
            mobileMenuBtn.setAttribute('aria-expanded', !isExpanded);
            mobileMenuBtn.classList.toggle('active');
            navMenu.classList.toggle('active');
        });
        
        // Close menu when clicking on a link
        const navLinks = navMenu.querySelectorAll('a');
        navLinks.forEach(link => {
            link.addEventListener('click', function() {
                mobileMenuBtn.setAttribute('aria-expanded', 'false');
                mobileMenuBtn.classList.remove('active');
                navMenu.classList.remove('active');
            });
        });
        
        // Close menu when clicking outside
        document.addEventListener('click', function(event) {
            const isClickInsideNav = navMenu.contains(event.target);
            const isClickOnBtn = mobileMenuBtn.contains(event.target);
            
            if (!isClickInsideNav && !isClickOnBtn && navMenu.classList.contains('active')) {
                mobileMenuBtn.setAttribute('aria-expanded', 'false');
                mobileMenuBtn.classList.remove('active');
                navMenu.classList.remove('active');
            }
        });

        // Close menu on Escape key
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape' && navMenu.classList.contains('active')) {
                mobileMenuBtn.setAttribute('aria-expanded', 'false');
                mobileMenuBtn.classList.remove('active');
                navMenu.classList.remove('active');
                mobileMenuBtn.focus();
            }
        });
    }
});
</script>