<?php
/**
 * Role-based Navigation Component
 * This file dynamically displays navigation based on user role
 */

// Check if user is logged in and has a role
$userRole = $_SESSION['user_role'] ?? 'guest';
$userId = $_SESSION['user_id'] ?? null;
$userName = $_SESSION['user_name'] ?? 'Guest';
?>

<header class="header">
    <div class="container">
        <div class="nav-brand">
            <a href="<?= BASE_URL ?>/home">
                <img src="<?= BASE_URL ?>/assets/images/U.png" alt="UniVerse Logo" class="logo">
            </a>
        </div>
        
        <nav class="nav-menu" id="nav-menu">
            <ul class="nav-list">
                <?php if ($userRole === 'guest'): ?>
                    <!-- Guest Navigation -->
                    <li class="nav-item">
                        <a href="<?= BASE_URL ?>/home" class="nav-link">Home</a>
                    </li>
                    <li class="nav-item">
                        <a href="<?= BASE_URL ?>/articles" class="nav-link">Articles</a>
                    </li>
                    <li class="nav-item">
                        <a href="<?= BASE_URL ?>/about" class="nav-link">About</a>
                    </li>
                    <li class="nav-item">
                        <a href="<?= BASE_URL ?>/contact" class="nav-link">Contact</a>
                    </li>
                    
                <?php elseif ($userRole === 'undergraduate' || $userRole === 'student'): ?>
                    <?php include __DIR__ . '/../actors/undergraduate/Unavigation.view.php'; ?>
                    
                <?php elseif ($userRole === 'alumni'): ?>
                    <!-- Alumni Navigation -->
                    <li class="nav-item">
                        <a href="<?= BASE_URL ?>/alumni/home" class="nav-link">Home</a>
                    </li>
                    <li class="nav-item">
                        <a href="<?= BASE_URL ?>/alumni/profile" class="nav-link">My Profile</a>
                    </li>
                    <li class="nav-item">
                        <a href="<?= BASE_URL ?>/articles" class="nav-link">Articles</a>
                    </li>
                    <li class="nav-item">
                        <a href="<?= BASE_URL ?>/mentorship" class="nav-link">Mentorship</a>
                    </li>
                    <li class="nav-item">
                        <a href="<?= BASE_URL ?>/networking" class="nav-link">Networking</a>
                    </li>
                    
                <?php elseif ($userRole === 'company' || $userRole === 'employer'): ?>
                    <!-- Company/Employer Navigation -->
                    <li class="nav-item">
                        <a href="<?= BASE_URL ?>/company/dashboard" class="nav-link">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a href="<?= BASE_URL ?>/company/jobs" class="nav-link">Manage Jobs</a>
                    </li>
                    <li class="nav-item">
                        <a href="<?= BASE_URL ?>/company/candidates" class="nav-link">Candidates</a>
                    </li>
                    <li class="nav-item">
                        <a href="<?= BASE_URL ?>/company/profile" class="nav-link">Company Profile</a>
                    </li>
                    
                <?php elseif ($userRole === 'admin'): ?>
                    <!-- Admin Navigation -->
                    <li class="nav-item">
                        <a href="<?= BASE_URL ?>/admin/dashboard" class="nav-link">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a href="<?= BASE_URL ?>/admin/users" class="nav-link">Users</a>
                    </li>
                    <li class="nav-item">
                        <a href="<?= BASE_URL ?>/admin/articles" class="nav-link">Articles</a>
                    </li>
                    <li class="nav-item">
                        <a href="<?= BASE_URL ?>/admin/reports" class="nav-link">Reports</a>
                    </li>
                    <li class="nav-item">
                        <a href="<?= BASE_URL ?>/admin/settings" class="nav-link">Settings</a>
                    </li>
                <?php endif; ?>
            </ul>
        </nav>
        
        <!-- User Actions -->
        <div class="nav-actions">
            <?php if ($userRole === 'guest'): ?>
                <!-- Guest Actions -->
                <a href="<?= BASE_URL ?>/login" class="btn btn-secondary">Login</a>
                <a href="<?= BASE_URL ?>/registration" class="btn btn-primary">Sign Up</a>
            <?php else: ?>
                <!-- Logged In User Actions -->
                <div class="user-menu">
                    <button class="user-menu-btn" id="user-menu-btn">
                        <span class="user-avatar">
                            <?= strtoupper(substr($userName, 0, 1)) ?>
                        </span>
                        <span class="user-name"><?= htmlspecialchars($userName) ?></span>
                        <span class="dropdown-arrow">▼</span>
                    </button>
                    <div class="user-dropdown" id="user-dropdown">
                        <a href="<?= BASE_URL ?>/profile" class="dropdown-item">
                            <span class="item-icon">Profile</span>
                        </a>
                        <a href="<?= BASE_URL ?>/settings" class="dropdown-item">
                            <span class="item-icon">Settings</span>
                        </a>
                        <?php if ($userRole === 'admin'): ?>
                        <a href="<?= BASE_URL ?>/admin" class="dropdown-item">
                            <span class="item-icon">Admin Panel</span>
                        </a>
                        <?php endif; ?>
                        <div class="dropdown-divider"></div>
                        <a href="<?= BASE_URL ?>/logout" class="dropdown-item logout-item">
                            <span class="item-icon">Logout</span>
                        </a>
                    </div>
                </div>
            <?php endif; ?>
        </div>
        
        <!-- Mobile Menu Button -->
        <button class="mobile-menu-btn" id="mobile-menu-btn">
            <span></span>
            <span></span>
            <span></span>
        </button>
    </div>
</header>

<script>
// User dropdown toggle
const userMenuBtn = document.getElementById('user-menu-btn');
const userDropdown = document.getElementById('user-dropdown');

if (userMenuBtn && userDropdown) {
    userMenuBtn.addEventListener('click', function(e) {
        e.stopPropagation();
        userDropdown.classList.toggle('active');
    });
    
    // Close dropdown when clicking outside
    document.addEventListener('click', function(e) {
        if (!userMenuBtn.contains(e.target)) {
            userDropdown.classList.remove('active');
        }
    });
}

// Mobile menu toggle
const mobileMenuBtn = document.getElementById('mobile-menu-btn');
const navMenu = document.getElementById('nav-menu');

if (mobileMenuBtn && navMenu) {
    mobileMenuBtn.addEventListener('click', function() {
        navMenu.classList.toggle('active');
        this.classList.toggle('active');
    });
}
</script>
