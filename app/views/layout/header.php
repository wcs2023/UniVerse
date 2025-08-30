<header class="header">
    <div class="container">
        <div class="nav-brand">
            <a href="<?= BASE_URL ?>">
                <img src="<?= BASE_URL ?>/assets/images/U.png" alt="UniVerse Logo" class="logo">
            </a>
        </div>
        <nav class="nav-menu" id="nav-menu">
            <ul class="nav-list">
                <li class="nav-item">
                    <a href="<?= BASE_URL ?>" class="nav-link">Home</a>
                </li>
                <li class="nav-item">
                    <a href="<?= BASE_URL ?>/articles" class="nav-link">Articles</a>
                </li>
                <li class="nav-item dropdown">
                    <a href="#" class="nav-link">For Students <span class="dropdown-arrow">▼</span></a>
                    <div class="dropdown-menu">
                        <a href="<?= BASE_URL ?>/degree-finder" class="dropdown-item">
                            <span class="dropdown-icon">🎓</span>
                            <div>
                                <div class="dropdown-title">Degree Finder</div>
                                <div class="dropdown-desc">Find your perfect degree match</div>
                            </div>
                        </a>
                        <a href="<?= BASE_URL ?>/jobs" class="dropdown-item">
                            <span class="dropdown-icon">💼</span>
                            <div>
                                <div class="dropdown-title">Jobs & Internships</div>
                                <div class="dropdown-desc">Browse opportunities</div>
                            </div>
                        </a>
                        <a href="<?= BASE_URL ?>/mentorship" class="dropdown-item">
                            <span class="dropdown-icon">👥</span>
                            <div>
                                <div class="dropdown-title">Mentorship</div>
                                <div class="dropdown-desc">Connect with mentors</div>
                            </div>
                        </a>
                    </div>
                </li>
                <li class="nav-item">
                    <a href="<?= BASE_URL ?>/about" class="nav-link">About</a>
                </li>
                <li class="nav-item">
                    <a href="<?= BASE_URL ?>/contact" class="nav-link">Contact</a>
                </li>
            </ul>
        </nav>
        <div class="nav-actions">
            <a href="<?= BASE_URL ?>/login" class="btn btn-outline">Login</a>
            <a href="<?= BASE_URL ?>/register" class="btn btn-primary">Register</a>
        </div>
        <div class="mobile-menu-toggle" id="mobile-menu-toggle">
            <span></span>
            <span></span>
            <span></span>
        </div>
    </div>
</header>
