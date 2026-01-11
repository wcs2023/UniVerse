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

<nav class="navbar">
    <div class="nav-container">
        <div class="nav-logo">
            <a href="<?= BASE_URL ?>/alumni">
                <!-- <img src="<?= BASE_URL ?>/assets/images/u.png" alt="UniVerse Logo" class="logo-img"> -->UniVerse
            </a>
        </div>
        
        <ul class="nav-menu" id="nav-menu">
            <li>
                <a href="<?= BASE_URL ?>/alumni" 
                   class="<?= (strpos($current_path, '/alumni') !== false && strpos($current_path, '/articles') === false && strpos($current_path, '/mentorships') === false) ? 'active' : '' ?>">
                    Home
                </a>
            </li>
            <li>
                <a href="<?= BASE_URL ?>/aarticles" 
                   class="<?= (strpos($current_path, '/articles') !== false || strpos($current_path, '/aarticles') !== false) ? 'active' : '' ?>">
                    Articles
                </a>
            </li>
            <li>
                <a href="<?= BASE_URL ?>/adiscussion" 
                   class="<?= (strpos($current_path, '/adiscussion') !== false) ? 'active' : '' ?>">
                    Discussion Forums
                </a>
            </li>
            <li>
                <a href="<?= BASE_URL ?>/amentorships" 
                   class="<?= (strpos($current_path, '/mentorships') !== false || strpos($current_path, '/amentorships') !== false) ? 'active' : '' ?>">
                    Mentoring
                </a>
            </li>
            <li>
                <a href="<?= BASE_URL ?>/alumni/profile" 
                   class="<?= (strpos($current_path, '/profile') !== false) ? 'active' : '' ?>">
                    My Profile
                </a>
            </li>
            <li>
                <a href="<?= BASE_URL ?>/login" class="logout-btn">
                    Logout
                </a>
            </li>
        </ul>
        
        <div class="hamburger" id="hamburger">
            <span></span>
            <span></span>
            <span></span>
        </div>
    </div>
</nav>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const hamburger = document.getElementById('hamburger');
    const navMenu = document.getElementById('nav-menu');
    
    if (hamburger && navMenu) {
        hamburger.addEventListener('click', function() {
            hamburger.classList.toggle('active');
            navMenu.classList.toggle('active');
        });
        
        // Close menu when clicking on a link
        const navLinks = navMenu.querySelectorAll('a');
        navLinks.forEach(link => {
            link.addEventListener('click', function() {
                hamburger.classList.remove('active');
                navMenu.classList.remove('active');
            });
        });
        
        // Close menu when clicking outside
        document.addEventListener('click', function(event) {
            const isClickInsideNav = navMenu.contains(event.target);
            const isClickOnHamburger = hamburger.contains(event.target);
            
            if (!isClickInsideNav && !isClickOnHamburger && navMenu.classList.contains('active')) {
                hamburger.classList.remove('active');
                navMenu.classList.remove('active');
            }
        });
    }
});
    
    // Set active link based on current URL
    const currentPath = window.location.pathname;
    const navLinks = document.querySelectorAll('.nav-menu a');
    
    navLinks.forEach(link => {
        const linkPath = new URL(link.href).pathname;
        if (currentPath.includes(linkPath) && linkPath !== '<?= BASE_URL ?>') {
            link.classList.add('active');
        } else if (currentPath === '<?= BASE_URL ?>' && linkPath === '<?= BASE_URL ?>') {
            link.classList.add('active');
        }
    });

</script>