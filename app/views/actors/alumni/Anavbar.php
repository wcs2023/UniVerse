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

<link rel="stylesheet" href="<?= BASE_URL ?>/css/alumni.css">

<header class="header">
    <div class="container">
        <div class="nav-brand">
            <a href="<?= BASE_URL ?>/alumni" style="font-size: 1.5rem; font-weight: bold; color: #6b46c1; text-decoration: none;">
                UniVerse
            </a>
        </div>
        <nav class="nav-menu" id="nav-menu">
            <ul class="nav-list">
                <li class="nav-item">
                    <a href="<?= BASE_URL ?>/alumni" class="nav-link <?= (strpos($current_path, '/alumni') !== false && strpos($current_path, '/articles') === false && strpos($current_path, '/mentorships') === false && strpos($current_path, '/profile') === false) ? 'active' : '' ?>">Home</a>
                </li>
                <li class="nav-item">
                    <a href="<?= BASE_URL ?>/aarticles" class="nav-link <?= (strpos($current_path, '/articles') !== false || strpos($current_path, '/aarticles') !== false) ? 'active' : '' ?>">Articles</a>
                </li>
                <li class="nav-item">
                    <a href="<?= BASE_URL ?>/adiscussion" class="nav-link <?= (strpos($current_path, '/adiscussion') !== false) ? 'active' : '' ?>">Discussion Forums</a>
                </li>
                <li class="nav-item">
                    <a href="<?= BASE_URL ?>/amentorships" class="nav-link <?= (strpos($current_path, '/mentorships') !== false || strpos($current_path, '/amentorships') !== false) ? 'active' : '' ?>">Mentoring</a>
                </li>
                <li class="nav-item">
                    <a href="<?= BASE_URL ?>/alumni/profile" class="nav-link <?= (strpos($current_path, '/profile') !== false) ? 'active' : '' ?>">My Profile</a>
                </li>
                <li class="nav-item">
                    <a href="<?= BASE_URL ?>/logout" class="nav-link logout-btn" onclick="return confirm('Are you sure you want to logout?')">Logout</a>
                </li>
            </ul>
        </nav>
        <button class="mobile-menu-btn" id="mobile-menu-btn">
            <span></span>
            <span></span>
            <span></span>
        </button>
    </div>
</header>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const mobileMenuBtn = document.getElementById('mobile-menu-btn');
    const navMenu = document.getElementById('nav-menu');
    
    if (mobileMenuBtn && navMenu) {
        mobileMenuBtn.addEventListener('click', function() {
            mobileMenuBtn.classList.toggle('active');
            navMenu.classList.toggle('active');
        });
        
        // Close menu when clicking on a link
        const navLinks = navMenu.querySelectorAll('a');
        navLinks.forEach(link => {
            link.addEventListener('click', function() {
                mobileMenuBtn.classList.remove('active');
                navMenu.classList.remove('active');
            });
        });
        
        // Close menu when clicking outside
        document.addEventListener('click', function(event) {
            const isClickInsideNav = navMenu.contains(event.target);
            const isClickOnBtn = mobileMenuBtn.contains(event.target);
            
            if (!isClickInsideNav && !isClickOnBtn && navMenu.classList.contains('active')) {
                mobileMenuBtn.classList.remove('active');
                navMenu.classList.remove('active');
            }
        });
    }
});
</script>