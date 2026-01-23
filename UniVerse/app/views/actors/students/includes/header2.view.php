
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?=BASE_URL?>/assets/css/header2_styles.css">
    
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

        

      
    </style>
</head>

<header class="header">
    <div class="nav-container">
        <a href="<?= BASE_URL ?>/home" class="logo">UniVerse</a>
        <ul class="nav-menu" id="nav_menu">
            <li><a href="<?= BASE_URL ?>/schoolleaver" class="nav-link">Home</a></li>
            <li><a href="<?= BASE_URL ?>/schoolleaver/profile" class="nav-link">My Profile</a></li>
            <li><a href="<?= BASE_URL ?>/schoolleaver/articles" class="nav-link">Career Articles</a></li>
            <li><a href="<?= BASE_URL ?>/Discussion_Forum/index" class="nav-link">Discussion Forums</a></li>
            <li><a href="<?= BASE_URL ?>/schoolleaver/degreeSuggestion" class="nav-link">Degree Suggestions</a></li>
            <li><a href="<?= BASE_URL ?>/logout" class="nav-link">Logout</a></li>
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