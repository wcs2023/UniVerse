<!-- Admin Sidebar Component -->
<style>
    .admin-sidebar {
        width: 280px;
        height: 100vh;
        position: fixed;
        top: 0;
        left: 0;
        background: linear-gradient(180deg, #6c47d4 0%, #4f34a3 100%);
        color: #fff;
        display: flex;
        flex-direction: column;
        z-index: 1000;
        transition: transform 0.3s ease;
    }

    .sidebar-header {
        padding: 1.5rem;
        border-bottom: 1px solid rgba(255,255,255,0.15);
    }

    .sidebar-header a {
        display: flex;
        align-items: center;
        gap: 0.9rem;
        text-decoration: none;
        color: #fff;
    }

    .sidebar-logo {
        width: 44px;
        height: 44px;
        object-fit: contain;
        border-radius: 10px;
        background: rgba(255,255,255,0.12);
        padding: 6px;
        flex-shrink: 0;
    }

    .sidebar-header h2 {
        font-size: 1.8rem;
        margin: 0;
        font-weight: 700;
    }

    .sidebar-nav {
        display: flex;
        flex-direction: column;
        padding: 1rem 0;
        flex: 1;
    }

    .nav-item {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding: 0.95rem 1.5rem;
        color: rgba(255,255,255,0.92);
        text-decoration: none;
        font-weight: 500;
        transition: background 0.2s ease, color 0.2s ease, padding-left 0.2s ease;
    }

    .nav-item:hover {
        background: rgba(255,255,255,0.12);
        color: #fff;
        padding-left: 1.8rem;
    }

    .nav-item.active {
        background: rgba(255,255,255,0.18);
        color: #fff;
        border-left: 4px solid #fff;
    }

    .sidebar-footer {
        padding: 1rem 0;
        border-top: 1px solid rgba(255,255,255,0.15);
    }

    .nav-item.logout {
        color: #ffe3e3;
    }

    .nav-item.logout:hover {
        background: rgba(255,255,255,0.12);
        color: #fff;
    }

    /* Desktop spacing */
    .main-content {
        margin-left: 280px;
        transition: margin-left 0.3s ease;
    }

    /* Hamburger button */
    .sidebar-toggle {
        display: none;
        position: fixed;
        top: 1rem;
        left: 1rem;
        width: 46px;
        height: 46px;
        border: none;
        border-radius: 12px;
        background: #6c47d4;
        cursor: pointer;
        z-index: 1200;
        padding: 0;
        box-shadow: 0 8px 20px rgba(108, 71, 212, 0.25);
    }

    .sidebar-toggle span {
        display: block;
        width: 22px;
        height: 2.5px;
        background: #fff;
        margin: 5px auto;
        border-radius: 999px;
        transition: 0.25s ease;
    }

    .sidebar-toggle.open span:nth-child(1) {
        transform: translateY(7px) rotate(45deg);
    }

    .sidebar-toggle.open span:nth-child(2) {
        opacity: 0;
    }

    .sidebar-toggle.open span:nth-child(3) {
        transform: translateY(-8px) rotate(-45deg);
    }

    /* Overlay */
    .sidebar-overlay {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(15, 23, 42, 0.45);
        z-index: 1100;
    }

    .admin-sidebar {
        width: 280px;
        height: 100vh;
        position: fixed;
        top: 0;
        left: 0;
        background: linear-gradient(180deg, #6c47d4 0%, #4f34a3 100%);
        color: #fff;
        display: flex;
        flex-direction: column;
        z-index: 1200;
        transition: transform 0.3s ease;
    }

    .sidebar-overlay {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(15, 23, 42, 0.45);
        z-index: 1100;
    }

    .sidebar-toggle {
        display: none;
        position: fixed;
        top: 1rem;
        left: 1rem;
        width: 46px;
        height: 46px;
        border: none;
        border-radius: 12px;
        background: #6c47d4;
        cursor: pointer;
        z-index: 1300;
        padding: 0;
        box-shadow: 0 8px 20px rgba(108, 71, 212, 0.25);
    }

    .sidebar-overlay.show {
        display: block;
    }

    /* Mobile */
    @media (max-width: 992px) {
        .sidebar-toggle {
            display: block;
        }

        .admin-sidebar {
            transform: translateX(-100%);
            width: 260px;
            box-shadow: 8px 0 24px rgba(0,0,0,0.18);
        }

        .admin-sidebar.show {
            transform: translateX(0);
        }

        .main-content {
            margin-left: 0;
            padding-top: 4.5rem;
        }
    }
</style>

<!-- Mobile Sidebar Toggle -->
<button class="sidebar-toggle" id="sidebarToggle" aria-label="Open menu" type="button">
    <span></span>
    <span></span>
    <span></span>
</button>

<!-- Overlay -->
<div class="sidebar-overlay" id="sidebarOverlay"></div>

<!-- Sidebar -->
<aside class="admin-sidebar" id="adminSidebar">
    <div class="sidebar-header">
        <a href="<?= BASE_URL ?>/admin">
            <img src="<?= BASE_URL ?>/assets/images/U.png" alt="UniVerse Logo" class="sidebar-logo">
            <h2>Admin Panel</h2>
        </a>
    </div>
    
    <nav class="sidebar-nav">
        <a href="<?= BASE_URL ?>/admin/users"
           class="nav-item <?= (strpos($_SERVER['REQUEST_URI'], '/admin/users') !== false || preg_match('#/admin/?$#', $_SERVER['REQUEST_URI'])) ? 'active' : '' ?>">
            <span>Users</span>
        </a>
        
        <a href="<?= BASE_URL ?>/admin/articles"
           class="nav-item <?= strpos($_SERVER['REQUEST_URI'], '/admin/articles') !== false ? 'active' : '' ?>">
            <span>Articles</span>
        </a>
        
        <a href="<?= BASE_URL ?>/admin/forums"
           class="nav-item <?= strpos($_SERVER['REQUEST_URI'], '/admin/forums') !== false ? 'active' : '' ?>">
            <span>Forums</span>
        </a>
        
        <a href="<?= BASE_URL ?>/contact/contactmessages"
           class="nav-item <?= strpos($_SERVER['REQUEST_URI'], '/contact/contactmessages') !== false ? 'active' : '' ?>">
            <span>Contact Messages</span>
        </a>

        <a href="<?= BASE_URL ?>/admin/degreeprogramimport"
           class="nav-item <?= strpos($_SERVER['REQUEST_URI'], '/admin/degreeprogramimport') !== false ? 'active' : '' ?>">
            <span>Degree Programs</span>
        </a>
    </nav>
    
    <div class="sidebar-footer">
        <a href="<?= BASE_URL ?>/logout" class="nav-item logout">
            <span>Logout</span>
        </a>
    </div>
</aside>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const sidebar = document.getElementById('adminSidebar');
    const toggle = document.getElementById('sidebarToggle');
    const overlay = document.getElementById('sidebarOverlay');

    if (!sidebar || !toggle || !overlay) return;

    function openSidebar() {
        sidebar.classList.add('show');
        overlay.classList.add('show');
        toggle.classList.add('open');
    }

    function closeSidebar() {
        sidebar.classList.remove('show');
        overlay.classList.remove('show');
        toggle.classList.remove('open');
    }

    toggle.addEventListener('click', function () {
        if (sidebar.classList.contains('show')) {
            closeSidebar();
        } else {
            openSidebar();
        }
    });

    overlay.addEventListener('click', closeSidebar);

    window.addEventListener('resize', function () {
        if (window.innerWidth > 992) {
            closeSidebar();
        }
    });
});
</script>