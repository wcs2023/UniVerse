<header class="header">
    <div class="container">
        <div class="nav-brand">
            <a href="<?= BASE_URL ?>/home" style="font-size: 1.5rem; font-weight: bold; color: #6b46c1; text-decoration: none;">
                <!-- <img src="<?= BASE_URL ?>/assets/images/U.png" alt="UniVerse Logo" class="logo"> -->UniVerse
            <!-- </a> -->
        </div>
        <nav class="nav-menu" id="nav-menu">
            <ul class="nav-list">
                <li class="nav-item">
                    <a href="<?= BASE_URL ?>/uhome" class="nav-link">Home</a> 
                </li>
                <li class="nav-item">
                    <a href="<?= BASE_URL ?>/umyprofile" class="nav-link">My Profile</a>
                </li>
                <li class="nav-item">
                    <a href="<?= BASE_URL ?>/uarticles" class="nav-link">Articles</a>
                </li>
                <li class="nav-item">
                    <a href="<?= BASE_URL ?>/ujobs" class="nav-link">Jobs</a>
                </li>
                <li class="nav-item">
                    <a href="<?= BASE_URL ?>/Discussion_forum" class="nav-link">Discussion Forums</a>
                </li>
                <li class="nav-item">
                    <a href="<?= BASE_URL ?>/umentorships" class="nav-link">Mentorship</a>
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