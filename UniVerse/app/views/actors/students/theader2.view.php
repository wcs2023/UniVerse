<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/student_styles.css">
    <script src="https://kit.fontawesome.com/317f05ac77.js" crossorigin="anonymous"></script>
    <title><?php echo $title; ?></title>
</head>

<header class="header">
        
        <nav class="nav-container" >
            <span class="logo">Universe</span>
            <ul class="nav-menu" id="nav_menu">
                <li><a href="school_leaver_home.php" class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'school_leaver_home.php') ? ' active' : ''; ?>">Home</a></li>
                <li><a href="school_leaver_profile.php" class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'school_leaver_profile.php') ? ' active' : ''; ?>">My Profile</a></li>
                <li><a href="#" class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'career_articles.php') ? ' active' : ''; ?>">Carrier Articles</a></li>
                <li> <a href="#" class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'discussion_forum.php') ? ' active' : ''; ?>">Discussion Forums</a></li>
                <li><a href="degree_suggestion.php" class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'degree_suggestion.php') ? ' active' : ''; ?>">Degree Suggestions</a></li>    
                <i class="fa-solid fa-xmark" onclick="closemenu()"></i>
            </ul>
            <i class="fa-solid fa-bars" onclick="openmenu()"></i>
        </nav>
    </header>
    <script src="assets/js/sidebar.js"></script>
    

</html>