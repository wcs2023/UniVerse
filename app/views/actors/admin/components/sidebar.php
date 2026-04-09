<!-- Admin Sidebar Component -->
<aside class="admin-sidebar">
    <div class="sidebar-header">
        <a href="<?= BASE_URL ?>/admin">
            <img src="<?= BASE_URL ?>/assets/images/U.png" alt="UniVerse Logo" class="sidebar-logo">
            <h2>Admin Panel</h2>
        </a>
    </div>
    
    <nav class="sidebar-nav">
        <a href="<?= BASE_URL ?>/admin/users" class="nav-item <?= (strpos($_SERVER['REQUEST_URI'], '/admin/users') !== false || preg_match('#/admin/?$#', $_SERVER['REQUEST_URI'])) ? 'active' : '' ?>">
            <!-- <i class="icon">👥</i> -->
            <span>Users</span>
        </a>
        
        <a href="<?= BASE_URL ?>/admin/articles" class="nav-item <?= strpos($_SERVER['REQUEST_URI'], '/admin/articles') !== false ? 'active' : '' ?>">
            <!-- <i class="icon">📝</i> -->
            <span>Articles</span>
        </a>
        
        <!-- <a href="<?= BASE_URL ?>/admin/registrations" class="nav-item <?= strpos($_SERVER['REQUEST_URI'], '/admin/registrations') !== false ? 'active' : '' ?>">
            <i class="icon">✅</i>
            <span>Registrations</span>
        </a> -->
        
        <a href="<?= BASE_URL ?>/admin/forums" class="nav-item <?= strpos($_SERVER['REQUEST_URI'], '/admin/forums') !== false ? 'active' : '' ?>">
            <!-- <i class="icon">💬</i> -->
            <span>Forums</span>
        </a>
    </nav>
    
    <div class="sidebar-footer">
        <a href="<?= BASE_URL ?>/logout" class="nav-item logout">
            <!-- <i class="icon">🚪</i> -->
            <span>Logout</span>
        </a>
    </div>
</aside>
