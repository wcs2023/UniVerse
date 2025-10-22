
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title ?? 'UniVerse - School Leavers'; ?></title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="<?= BASE_URL ?>/assets/images/U.png">
    
    <!-- FontAwesome -->
    <script src="https://kit.fontawesome.com/317f05ac77.js" crossorigin="anonymous"></script>
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        /* CSS Variables */
        :root {
            --primary-purple: #6b46c1;
            --dark-purple: #553c9a;
            --light-purple: #8b5cf6;
            --text-dark: #1f2937;
            --text-light: #6b7280;
            --white: #ffffff;
            --light-gray: #f9fafb;
            --border-color: #e5e7eb;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            line-height: 1.6;
            color: var(--text-dark);
        }

        /* Header Styles */
        .header {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            padding: 1rem 0;
            position: sticky;
            top: 0;
            z-index: 1000;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            margin-bottom: 2rem;
        }

        .nav-container {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0 2rem;
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
            gap: 2rem;
            margin: 0;
            padding: 0;
        }

        .nav-menu li {
            display: inline;
        }

        .nav-link {
            text-decoration: none;
            color: var(--text-dark);
            font-weight: 500;
            padding: 0.5rem 1rem;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .nav-link:hover,
        .nav-link.active {
            color: var(--primary-purple);
            background: rgba(107, 70, 193, 0.1);
        }

        /* Mobile Menu */
        .fa-bars,
        .fa-xmark {
            display: none;
            font-size: 1.5rem;
            color: var(--primary-purple);
            cursor: pointer;
            padding: 0.5rem;
        }

        /* Main Container Styles */
        .main-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 2rem;
        }

        /* Hero Section - FIXED STYLES */
        .hero-section {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 3rem;
            align-items: center;
            margin-bottom: 4rem;
            background: var(--white);
            border-radius: 20px;
            padding: 3rem;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }

        .hero-content {
            /* Ensure content is visible */
            z-index: 10;
            position: relative;
        }

        .hero-content h1,
        .hero-title {
            font-size: 2.5rem !important;
            font-weight: 700 !important;
            color: var(--text-dark) !important;
            margin-bottom: 1.5rem !important;
            line-height: 1.2 !important;
            /* Force visibility */
            opacity: 1 !important;
            visibility: visible !important;
        }

        .hero-description {
            font-size: 1.1rem !important;
            color: var(--text-light) !important;
            line-height: 1.6 !important;
            margin-bottom: 2rem !important;
            /* Force visibility */
            opacity: 1 !important;
            visibility: visible !important;
        }

        .try-button .btn {
            display: inline-block;
            padding: 1rem 2rem;
            background: var(--primary-purple);
            color: var(--white);
            text-decoration: none;
            border-radius: 12px;
            font-weight: 600;
            font-size: 1.1rem;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(107, 70, 193, 0.3);
        }

        .try-button .btn:hover {
            background: var(--dark-purple);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(107, 70, 193, 0.4);
        }

        .hero-image {
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .placeholder-img {
            width: 100%;
            max-width: 400px;
            height: 300px;
            background: linear-gradient(45deg, #f0f0f0, #e0e0e0);
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #999;
            font-weight: 600;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        }

        .placeholder-img::before {
            content: "Image Placeholder";
        }

        /* Features Section - FIXED STYLES */
        .features-section {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 3rem;
            align-items: center;
            background: var(--white);
            border-radius: 20px;
            padding: 3rem;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }

        .feature-content h2 {
            font-size: 2rem !important;
            font-weight: 700 !important;
            color: var(--text-dark) !important;
            margin-bottom: 1.5rem !important;
            line-height: 1.3 !important;
            /* Force visibility */
            opacity: 1 !important;
            visibility: visible !important;
        }

        .feature-description {
            font-size: 1.1rem !important;
            color: var(--text-light) !important;
            line-height: 1.6 !important;
            margin-bottom: 2rem !important;
            /* Force visibility */
            opacity: 1 !important;
            visibility: visible !important;
        }

        .feature-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 1.5rem;
        }

        .feature-card {
            background: var(--light-gray);
            padding: 2rem;
            border-radius: 15px;
            border: 2px solid transparent;
            transition: all 0.3s ease;
        }

        .feature-card:hover {
            border-color: var(--primary-purple);
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(107, 70, 193, 0.15);
        }

        .feature-card h3 {
            font-size: 1.3rem !important;
            font-weight: 600 !important;
            color: var(--text-dark) !important;
            margin-bottom: 1rem !important;
            /* Force visibility */
            opacity: 1 !important;
            visibility: visible !important;
        }

        .feature-card p {
            color: var(--text-light) !important;
            margin-bottom: 1.5rem !important;
            line-height: 1.5 !important;
            /* Force visibility */
            opacity: 1 !important;
            visibility: visible !important;
        }

        .feature-card .btn {
            display: inline-block;
            padding: 0.75rem 1.5rem;
            background: transparent;
            color: var(--primary-purple);
            text-decoration: none;
            border: 2px solid var(--primary-purple);
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .feature-card .btn:hover {
            background: var(--primary-purple);
            color: var(--white);
            transform: translateX(3px);
        }

        .feature-image {
            display: flex;
            justify-content: center;
            align-items: center;
        }

        /* Animation */
        .fade-in {
            animation: fadeInUp 0.8s ease-out;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .fa-bars {
                display: block;
            }

            .nav-menu {
                position: fixed;
                top: 0;
                right: -300px;
                height: 100vh;
                width: 250px;
                background: var(--white);
                flex-direction: column;
                justify-content: flex-start;
                padding-top: 80px;
                gap: 1rem;
                box-shadow: -5px 0 15px rgba(0, 0, 0, 0.1);
                transition: right 0.3s ease;
            }

            .nav-menu.active {
                right: 0;
            }

            .nav-menu .fa-xmark {
                display: block;
                position: absolute;
                top: 25px;
                right: 25px;
            }

            .main-container {
                padding: 1rem;
            }

            .hero-section,
            .features-section {
                grid-template-columns: 1fr;
                gap: 2rem;
                padding: 2rem;
            }

            .hero-content h1,
            .hero-title {
                font-size: 2rem !important;
            }

            .feature-content h2 {
                font-size: 1.5rem !important;
            }

            .hero-image,
            .feature-image {
                order: -1;
            }

            .nav-container {
                padding: 0 1rem;
            }
        }

        @media (max-width: 480px) {
            .hero-content h1,
            .hero-title {
                font-size: 1.5rem !important;
            }

            .hero-section,
            .features-section {
                padding: 1.5rem;
            }

            .try-button .btn {
                padding: 0.875rem 1.5rem;
                font-size: 1rem;
            }
        }

      
    </style>
</head>

<header class="header">
    <div class="nav-container">
        <a href="<?= BASE_URL ?>/schoolleaver" class="logo">UniVerse</a>
        <ul class="nav-menu" id="nav_menu">
            <li><a href="<?= BASE_URL ?>/schoolleaver" class="nav-link">Home</a></li>
            <li><a href="<?= BASE_URL ?>/schoolleaver/profile" class="nav-link">My Profile</a></li>
            <li><a href="<?= BASE_URL ?>/schoolleaver/articles" class="nav-link">Career Articles</a></li>
            <li><a href="<?= BASE_URL ?>/sdiscussion" class="nav-link">Discussion Forums</a></li>
            <li><a href="<?= BASE_URL ?>/schoolleaver/degreeSuggestion" class="nav-link">Degree Suggestions</a></li>
            <i class="fa-solid fa-xmark" onclick="closemenu()"></i>
        </ul>
        <i class="fa-solid fa-bars" onclick="openmenu()"></i>
    </div>
</header>

<script>
    // Mobile menu functions
    function openmenu() {
        document.getElementById('nav_menu').classList.add('active');
    }

    function closemenu() {
        document.getElementById('nav_menu').classList.remove('active');
    }
</script>