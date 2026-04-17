<header class="company-header">
   <a href="<?= BASE_URL ?>/company/landing" class="company-logo">UniVerse</a>
<style>
.company-header {
    position: relative;
    display: flex;
    align-items: center;
    justify-content: space-between;
    min-height: 72px;
    padding: 0 24px;
    background: #ffffff;
    border-bottom: 1px solid var(--border-color);
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.04);
    z-index: 1000;
}

.company-logo {
    text-decoration: none;
    font-size: 1.4rem;
    font-weight: 800;
    color: #6C47D4;
    letter-spacing: 0.3px;
    flex-shrink: 0;
}

.company-nav {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    flex: 1;
    margin: 0 24px;
}

.company-nav a {
    position: relative;
    text-decoration: none;
    color: var(--text-light);
    font-size: 14px;
    font-weight: 600;
    padding: 10px 16px;
    border-radius: 10px;
    transition: all 0.2s ease;
    white-space: nowrap;
}

.company-nav a:hover {
    background: #f3f0ff;
    color: #6C47D4;
}

.company-nav a.active {
    background: #6C47D4;
    color: #ffffff;
    box-shadow: 0 4px 12px rgba(108, 71, 212, 0.25);
}

.header-right {
    display: flex;
    align-items: center;
    gap: 12px;
    flex-shrink: 0;
}

.hamburger-btn {
    display: none;
    width: 42px;
    height: 42px;
    border: 1px solid var(--border-color);
    border-radius: 10px;
    background: #fff;
    cursor: pointer;
    align-items: center;
    justify-content: center;
    flex-direction: column;
    gap: 4px;
    transition: all 0.2s ease;
}

.hamburger-btn:hover {
    background: #f8f8f8;
    border-color: #d7d7d7;
}

.hamburger-btn span {
    width: 18px;
    height: 2px;
    background: #333;
    border-radius: 999px;
    transition: all 0.25s ease;
}

.hamburger-btn.open span:nth-child(1) {
    transform: translateY(6px) rotate(45deg);
}

.hamburger-btn.open span:nth-child(2) {
    opacity: 0;
}

.hamburger-btn.open span:nth-child(3) {
    transform: translateY(-6px) rotate(-45deg);
}

.mobile-menu {
    display: none;
    position: absolute;
    top: 100%;
    left: 0;
    width: 100%;
    background: #ffffff;
    border-top: 1px solid var(--border-color);
    border-bottom: 1px solid var(--border-color);
    box-shadow: 0 10px 24px rgba(0, 0, 0, 0.08);
    padding: 12px 16px 16px;
    flex-direction: column;
    gap: 6px;
}

.mobile-menu.open {
    display: flex;
}

.mobile-menu a {
    text-decoration: none;
    color: var(--text-light);
    font-size: 14px;
    font-weight: 600;
    padding: 12px 14px;
    border-radius: 10px;
    transition: all 0.2s ease;
}

.mobile-menu a:hover {
    background: #f3f0ff;
    color: #6C47D4;
}

.mobile-menu a.active {
    background: #6C47D4;
    color: #ffffff;
}

.mobile-divider {
    height: 1px;
    background: var(--border-color);
    margin: 8px 0;
}

/* tablet/mobile */
@media (max-width: 768px) {
    .company-header {
        padding: 0 16px;
        min-height: 64px;
    }

    .company-nav {
        display: none;
    }

    .hamburger-btn {
        display: flex;
    }

    .profile-info,
    .dropdown-arrow {
        display: none;
    }
}

/* desktop reset */
@media (min-width: 769px) {
    .mobile-menu {
        display: none !important;
    }

    .hamburger-btn {
        display: none !important;
    }

    .company-nav {
        display: flex !important;
    }
}
</style>
    <nav class="company-nav" id="desktopNav">
        <?php
        $currentPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $navLinks = [
            '/company/landing'      => 'Dashboard',
            '/company/managejobs'   => 'Manage Jobs',
            '/company/postjobs'     => 'Post Jobs',
            '/company/applications' => 'View Applications',
        ];
        foreach ($navLinks as $path => $label):
            $isActive = str_starts_with($currentPath, $path);
        ?>
            <a href="<?= BASE_URL . $path ?>" class="<?= $isActive ? 'active' : '' ?>">
                <?= htmlspecialchars($label) ?>
            </a>
        <?php endforeach; ?>
    </nav>

    <div class="header-right">
        <div style="position: relative;">
            <div class="profile-trigger" id="profileTrigger">
                <div class="profile-icon">
                    <?php
                        $companyLogoPath = $_SESSION['company_logo_url'] ?? '';
                        $companyLogoPath = is_string($companyLogoPath) ? ltrim($companyLogoPath, '/') : '';
                        if (str_starts_with($companyLogoPath, 'public/')) {
                            $companyLogoPath = substr($companyLogoPath, 7);
                        }
                        $companyLogoSrc = $companyLogoPath ? (BASE_URL . '/' . $companyLogoPath) : '';
                    ?>
                    <svg class="profile-icon-fallback" width="16" height="16" viewBox="0 0 24 24" fill="currentColor"
                         style="display: <?= !empty($companyLogoSrc) ? 'none' : 'block' ?>;">
                        <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                    </svg>
                    <?php if (!empty($companyLogoSrc)): ?>
                        <img src="<?= htmlspecialchars($companyLogoSrc) ?>" alt="" width="28" height="28" loading="lazy"
                             onload="this.previousElementSibling.style.display='none';"
                             onerror="this.style.display='none'; this.previousElementSibling.style.display='block';">
                    <?php endif; ?>
                </div>
                <div class="profile-info">
                    <span class="profile-name"><?= htmlspecialchars($data['user']->firstname ?? 'User') ?></span>
                    <span class="profile-role">Company</span>
                </div>
                <div class="dropdown-arrow">▼</div>
            </div>

            <div class="dropdown-menu" id="dropdownMenu">
                <a href="<?= BASE_URL ?>/company/profile" class="dropdown-item">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 3c1.66 0 3 1.34 3 3s-1.34 3-3 3-3-1.34-3-3 1.34-3 3-3zm0 14.2c-2.5 0-4.71-1.28-6-3.22.03-1.99 4-3.08 6-3.08 1.99 0 5.97 1.09 6 3.08-1.29 1.94-3.5 3.22-6 3.22z"/>
                    </svg>
                    Update Profile
                </a>
                <a href="<?= BASE_URL ?>/logout" class="dropdown-item logout">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M17 7l-1.41 1.41L18.17 11H8v2h10.17l-2.58 2.59L17 17l5-5zM4 5h8V3H4c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h8v-2H4V5z"/>
                    </svg>
                    Logout
                </a>
            </div>
        </div>

        <button class="hamburger-btn" id="hamburgerBtn" aria-label="Toggle menu" aria-expanded="false">
            <span></span><span></span><span></span>
        </button>
    </div>

    <nav class="mobile-menu" id="mobileMenu">
        <?php foreach ($navLinks as $path => $label):
            $isActive = str_starts_with($currentPath, $path);
        ?>
            <a href="<?= BASE_URL . $path ?>" class="<?= $isActive ? 'active' : '' ?>">
                <?= htmlspecialchars($label) ?>
            </a>
        <?php endforeach; ?>
        <div class="mobile-divider"></div>
        <a href="<?= BASE_URL ?>/company/profile" style="font-size:13px; color: var(--color-text-secondary);">Update Profile</a>
        <a href="<?= BASE_URL ?>/logout" style="font-size:13px; color: var(--color-text-danger);">Logout</a>
    </nav>
</header>
<script>
const profileTrigger = document.getElementById('profileTrigger');
const dropdownMenu = document.getElementById('dropdownMenu');
const hamburgerBtn = document.getElementById('hamburgerBtn');
const mobileMenu = document.getElementById('mobileMenu');

profileTrigger.addEventListener('click', function(e) {
    e.stopPropagation();
    this.classList.toggle('open');
    dropdownMenu.classList.toggle('open');
});

hamburgerBtn.addEventListener('click', function(e) {
    e.stopPropagation();
    this.classList.toggle('open');
    mobileMenu.classList.toggle('open');
    this.setAttribute('aria-expanded', this.classList.contains('open'));
});

document.addEventListener('click', function(e) {
    if (!profileTrigger.contains(e.target)) {
        profileTrigger.classList.remove('open');
        dropdownMenu.classList.remove('open');
    }

    if (!hamburgerBtn.contains(e.target) && !mobileMenu.contains(e.target)) {
        hamburgerBtn.classList.remove('open');
        mobileMenu.classList.remove('open');
        hamburgerBtn.setAttribute('aria-expanded', 'false');
    }
});

window.addEventListener('resize', function() {
    if (window.innerWidth > 768) {
        hamburgerBtn.classList.remove('open');
        mobileMenu.classList.remove('open');
        hamburgerBtn.setAttribute('aria-expanded', 'false');
    }
});
</script>