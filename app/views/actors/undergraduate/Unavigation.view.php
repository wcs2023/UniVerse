<style>
    /* ===== RESPONSIVE NAVIGATION STYLES ===== */
    
    /* Desktop Navigation (>1024px) */
    .header {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        width: 100%;
        background: var(--white, #ffffff);
        backdrop-filter: blur(10px);
        border-bottom: 1px solid var(--border-color, #e5e7eb);
        z-index: 1000;
        padding: 0.45rem 0;
        transition: all 0.3s ease;
    }
    
    .header .container {
        display: flex;
        align-items: center;
        justify-content: space-between;
        max-width: 1400px;
        margin: 0 auto;
        padding: 0 20px;
    }

    .nav-brand {
        display: flex;
        align-items: center;
        z-index: 1001;
        flex-shrink: 0;
    }
    
    .nav-brand a {
        font-size: 1.5rem;
        font-weight: bold;
        color: #6b46c1;
        text-decoration: none;
        white-space: nowrap;
    }

    /* Desktop Menu - Horizontal Layout */
    .nav-menu {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        flex: 1;
        margin: 0 0 0 auto;
    }

    .nav-list {
        display: flex;
        list-style: none;
        gap: 0.5rem;
        margin: 0;
        padding: 0;
        flex-wrap: nowrap;
        justify-content: flex-end;
        width: auto;
    }

    .nav-item {
        white-space: nowrap;
        flex-shrink: 0;
    }

    .nav-link {
        text-decoration: none;
        color: var(--text-dark, #1f2937);
        font-weight: 500;
        font-size: 0.95rem;
        padding: 0.5rem 1rem;
        border-radius: 6px;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        min-height: 40px;
        white-space: nowrap;
    }
    
    .nav-link:hover,
    .nav-link.active {
        color: #6b46c1;
        background-color: rgba(107, 70, 193, 0.1);
    }

    .logout-btn {
        color: #ef4444;
    }

    .logout-btn:hover {
        background-color: #ffebee;
    }
    
    /* Mobile Menu Button */
    .mobile-menu-btn {
        display: none;
        flex-direction: column;
        background: none;
        border: none;
        cursor: pointer;
        padding: 0.5rem;
        z-index: 1002;
        min-width: 48px;
        min-height: 48px;
        justify-content: center;
        align-items: center;
    }

    .mobile-menu-btn span {
        width: 24px;
        height: 2.5px;
        background: var(--text-dark, #1f2937);
        margin: 4px 0;
        transition: all 0.3s ease;
        display: block;
    }

    .mobile-menu-btn.active span:nth-child(1) {
        transform: rotate(-45deg) translate(-8px, 8px);
    }

    .mobile-menu-btn.active span:nth-child(2) {
        opacity: 0;
    }

    .mobile-menu-btn.active span:nth-child(3) {
        transform: rotate(45deg) translate(-8px, -8px);
    }

    /* ===== TABLET VIEW (871px - 1024px) ===== */
    @media (max-width: 1024px) {
        .nav-list {
            gap: 0.3rem;
        }

        .nav-link {
            padding: 0.5rem 0.8rem;
            font-size: 0.9rem;
        }

        .nav-brand a {
            font-size: 1.3rem;
        }

        .header .container {
            padding: 0 15px;
        }
    }

    /* ===== TABLET/SMALL MOBILE (≤1240px) - HAMBURGER MENU STARTS ===== */
    @media (max-width: 1240px) {
        .header {
            padding: 0.75rem 0;
            height: auto;
        }
        
        .header .container {
            padding: 0 1rem;
            gap: 0.5rem;
        }

        .nav-brand a {
            font-size: 1.2rem;
        }

        /* Mobile Menu - Vertical Stack */
        .nav-menu {
            position: fixed;
            top: 100%;
            left: 0;
            right: 0;
            background: var(--white, #ffffff);
            flex-direction: column;
            justify-content: flex-start;
            align-items: stretch;
            margin: 0;
            padding: 0.5rem 0;
            border-top: 1px solid var(--border-color, #e5e7eb);
            max-height: calc(100vh - 70px);
            overflow-y: auto;
            -webkit-overflow-scrolling: touch;
            transform: translateX(100%);
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
            z-index: 999;
            width: 100%;
            max-width: 100%;
        }

        .nav-menu.active {
            transform: translateX(0);
            opacity: 1;
            visibility: visible;
        }

        /* Mobile menu scrollbar - minimal scrolling */
        .nav-menu::-webkit-scrollbar {
            width: 4px;
        }

        .nav-menu::-webkit-scrollbar-track {
            background: #f3f4f6;
        }

        .nav-menu::-webkit-scrollbar-thumb {
            background: #6b46c1;
            border-radius: 2px;
        }
        
        .nav-menu::-webkit-scrollbar-thumb:hover {
            background: #8b5cf6;
        }

        .nav-list {
            flex-direction: column;
            gap: 0;
            padding: 0;
            margin: 0;
        }

        .nav-item {
            width: 100%;
            white-space: normal;
            flex-shrink: unset;
        }

        .nav-link {
            width: 100%;
            padding: 0.65rem 1.25rem;
            font-size: 0.95rem;
            border-radius: 0;
            min-height: 44px;
            border-bottom: 1px solid var(--border-color, #e5e7eb);
            justify-content: flex-start;
            white-space: normal;
        }

        .nav-link:hover,
        .nav-link.active {
            background-color: #f3f4f6;
            color: #6b46c1;
        }

        .logout-btn {
            margin-top: 0.3rem;
            color: #ef4444;
        }

        .logout-btn:hover {
            background-color: #ffebee;
        }
        
        .mobile-menu-btn {
            display: flex;
            flex-shrink: 0;
        }
        
        /* Prevent body scroll when menu is open */
        body.menu-open {
            overflow: hidden;
        }
    }

    /* ===== SMALL MOBILE (601px - 870px) ===== */
    @media (max-width: 600px) {
        .header {
            padding: 0.65rem 0;
        }
        
        .nav-brand a {
            font-size: 1.1rem;
        }
        
        .nav-link {
            padding: 0.6rem 1rem;
            font-size: 0.9rem;
            min-height: 40px;
        }
    }

    /* ===== EXTRA SMALL MOBILE (≤480px) ===== */
    @media (max-width: 480px) {
        .header {
            padding: 0.6rem 0;
        }

        .header .container {
            padding: 0 0.75rem;
        }

        .nav-brand a {
            font-size: 1rem;
        }
        
        .nav-menu {
            max-height: calc(100vh - 60px);
        }
        
        .nav-link {
            padding: 0.55rem 0.85rem;
            font-size: 0.85rem;
            min-height: 38px;
        }
        
        .mobile-menu-btn {
            min-width: 44px;
            min-height: 44px;
        }
        
        .mobile-menu-btn span {
            width: 20px;
            height: 2px;
        }
    }

    /* ===== VERY SMALL MOBILE (≤360px) ===== */
    @media (max-width: 360px) {
        .header {
            padding: 0.55rem 0;
        }

        .header .container {
            padding: 0 0.5rem;
        }

        .nav-brand a {
            font-size: 0.95rem;
        }

        .nav-link {
            padding: 0.5rem 0.75rem;
            font-size: 0.8rem;
            min-height: 36px;
        }

        .mobile-menu-btn {
            min-width: 40px;
            min-height: 40px;
        }

        .mobile-menu-btn span {
            width: 18px;
            height: 2px;
            margin: 3px 0;
        }
    }

    /* ===== HIGH DPI SCREENS (Retina) ===== */
    @media (-webkit-min-device-pixel-ratio: 2), (min-resolution: 192dpi) {
        .mobile-menu-btn span {
            background: var(--text-dark, #1f2937);
        }
    }

    /* ===== ACCESSIBILITY ===== */
    @media (prefers-reduced-motion: reduce) {
        .nav-link,
        .nav-menu,
        .mobile-menu-btn span {
            transition: none;
        }
    }

    /* Focus visible for keyboard navigation */
    .nav-link:focus-visible,
    .mobile-menu-btn:focus-visible {
        outline: 2px solid #6b46c1;
        outline-offset: 2px;
    }
</style>

<script src="<?= BASE_URL ?>/assets/js/main.js"></script>

<header class="header">
    <div class="container">
        <div class="nav-brand">
            <a href="<?= BASE_URL ?>/home">
                UniVerse
            </a>
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