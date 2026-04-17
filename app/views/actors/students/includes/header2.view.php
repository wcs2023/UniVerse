<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title ?? 'UniVerse - School Leavers'; ?></title>
    <link rel="icon" type="image/png" href="<?= BASE_URL ?>/assets/images/U.png">
    <script src="https://kit.fontawesome.com/317f05ac77.js" crossorigin="anonymous"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/header2_styles.css">
    <style>
        :root {
            --primary-purple: #6b46c1;
            --dark-purple: #553c9a;
            --white: #ffffff;
            --text-dark: #1f2937;
            --border-color: #e5e7eb;
        }
        .header {
            background: var(--white);
            border-bottom: 1px solid var(--border-color);
            position: sticky;
            top: 0;
            z-index: 100;
        }
        .nav-container {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0 2rem;
            height: 64px;
        }
        .logo {
            font-size: 1.8rem;
            font-weight: 700;
            color: var(--primary-purple);
            text-decoration: none;
        }
        .nav-menu {
            display: flex;
            list-style: none;
            gap: 0.25rem;
            margin: 0;
            padding: 0;
        }
        .nav-link {
            text-decoration: none;
            color: var(--text-dark);
            font-weight: 500;
            padding: 0.5rem 1rem;
            border-radius: 8px;
            transition: all 0.3s ease;
            display: block;
        }
        .nav-link:hover,
        .nav-link.active {
            background-color: var(--primary-purple);
            color: var(--white);
        }

        /* Hamburger button */
        .hamburger {
            display: none;
            flex-direction: column;
            gap: 5px;
            cursor: pointer;
            padding: 8px;
            background: none;
            border: none;
        }
        .hamburger span {
            display: block;
            width: 24px;
            height: 2px;
            background: var(--primary-purple);
            border-radius: 2px;
            transition: all 0.3s ease;
        }
        .hamburger.open span:nth-child(1) { transform: translateY(7px) rotate(45deg); }
        .hamburger.open span:nth-child(2) { opacity: 0; }
        .hamburger.open span:nth-child(3) { transform: translateY(-7px) rotate(-45deg); }

        /* Mobile styles */
        @media (max-width: 768px) {
            .nav-menu {
                display: none;
                position: absolute;
                top: 64px;
                left: 0;
                right: 0;
                background: var(--white);
                border-bottom: 1px solid var(--border-color);
                flex-direction: column;
                padding: 0.75rem 1rem;
                gap: 0.25rem;
            }
            .nav-menu.open {
                display: flex;
            }
            .hamburger {
                display: flex;
            }
            .nav-link {
                padding: 0.65rem 1rem;
            }
        }
    </style>
</head>

<header class="header">
    <nav class="nav-container">
        <a href="<?= BASE_URL ?>/home" class="logo">UniVerse</a>
        <ul class="nav-menu" id="nav_menu">
            <li><a href="<?= BASE_URL ?>/schoolLeaver" class="nav-link">Home</a></li>
            <li><a href="<?= BASE_URL ?>/studentProfile/profile" class="nav-link">My Profile</a></li>
            <li><a href="<?= BASE_URL ?>/uarticles" class="nav-link">Career Articles</a></li>
            <li><a href="<?= BASE_URL ?>/Discussion_Forum/index" class="nav-link">Discussion Forums</a></li>
            <li><a href="<?= BASE_URL ?>/Degrees/degree_suggestion_index" class="nav-link">Degree Suggestions</a></li>
            <li><a href="<?= BASE_URL ?>/logout" class="nav-link">Logout</a></li>
        </ul>
        <button class="hamburger" id="hamburger" aria-label="Toggle menu">
            <span></span><span></span><span></span>
        </button>
    </nav>
</header>
<script>
    const hamburger = document.getElementById('hamburger');
    const navMenu = document.getElementById('nav_menu');

    hamburger.addEventListener('click', () => {
        hamburger.classList.toggle('open');
        navMenu.classList.toggle('open');
    });

    navMenu.querySelectorAll('.nav-link').forEach(link => {
        link.addEventListener('click', () => {
            hamburger.classList.remove('open');
            navMenu.classList.remove('open');
        });
    });

    const currentPath = window.location.pathname.replace(/\/$/, "");

    navMenu.querySelectorAll('.nav-link').forEach(link => {
        const linkPath = new URL(link.href).pathname.replace(/\/$/, "");

        if (linkPath === currentPath) {
            link.classList.add('active');
        }
    });
</script>