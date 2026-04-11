  <header class="company-header">
        <a href="<?= BASE_URL ?>/company/landing" class="company-logo">UniVerse</a>
        <nav class="company-nav">
            <a href="<?= BASE_URL ?>/company/landing">Dashboard</a>
            <a href="<?= BASE_URL ?>/company/managejobs">Manage Jobs</a>
            <a href="<?= BASE_URL ?>/company/postjobs">Post Jobs</a>
            <a href="<?= BASE_URL ?>/company/applications">View Applications</a>
        </nav>
        
        <div class="user-profile-dropdown">
            <div class="profile-trigger">
                <div class="profile-icon">
                    <?php
                        $companyLogoPath = $_SESSION['company_logo_url'] ?? '';
                        $companyLogoPath = is_string($companyLogoPath) ? $companyLogoPath : '';
                        $companyLogoPath = ltrim($companyLogoPath, '/');
                        if (strpos($companyLogoPath, 'public/') === 0) {
                            $companyLogoPath = substr($companyLogoPath, 7);
                        }
                        $companyLogoSrc = $companyLogoPath ? (BASE_URL . '/' . $companyLogoPath) : '';
                    ?>

                    <svg class="profile-icon-fallback" width="20" height="20" viewBox="0 0 24 24" fill="currentColor" style="display: <?= !empty($companyLogoSrc) ? 'none' : 'block' ?>;">
                        <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                    </svg>

                    <?php if (!empty($companyLogoSrc)): ?>
                        <img
                            src="<?= htmlspecialchars($companyLogoSrc) ?>"
                            alt=""
                            width="28"
                            height="28"
                            loading="lazy"
                            onload="this.previousElementSibling.style.display='none';"
                            onerror="this.style.display='none'; this.previousElementSibling.style.display='block';"
                        >
                    <?php endif; ?>
                </div>
                <div class="profile-info">
                    <span class="profile-name"><?= htmlspecialchars($data['user']->firstname ?? 'User') ?></span>
                    <span class="profile-role">Company</span>
                </div>
                <div class="dropdown-arrow">▼</div>
            </div>
            
            <div class="dropdown-menu">
                <a href="<?= BASE_URL ?>/company/profile" class="dropdown-item">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 3c1.66 0 3 1.34 3 3s-1.34 3-3 3-3-1.34-3-3 1.34-3 3-3zm0 14.2c-2.5 0-4.71-1.28-6-3.22.03-1.99 4-3.08 6-3.08 1.99 0 5.97 1.09 6 3.08-1.29 1.94-3.5 3.22-6 3.22z"/>
                    </svg>
                    Update Profile
                </a>
                <a href="<?= BASE_URL ?>/logout" class="dropdown-item logout">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M17 7l-1.41 1.41L18.17 11H8v2h10.17l-2.58 2.59L17 17l5-5zM4 5h8V3H4c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h8v-2H4V5z"/>
                    </svg>
                    Logout
                </a>
            </div>
        </div>
    </header>

