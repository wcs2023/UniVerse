<?php
$current_page = basename($_SERVER['PHP_SELF']);
?>

<nav class="navbar">
    <div class="nav-container">
        <div class="nav-logo">
            <a href="index.php"><img src="/UniVerse/public/assets/images/logo.png" alt="UniVerse Logo" class="logo-img"></a>
        </div>
        
        <ul class="nav-menu" id="nav-menu">
            <li><a href="Ahome.php" class="<?php echo ($current_page == 'Ahome.php') ? 'active' : ''; ?>">Home</a></li>
            
            <li><a href="Aclient-articles.php" class="<?php echo ($current_page == 'Aclient-articles.php') ? 'active' : ''; ?>">Articles</a></li>
            <li><a href="Adiscussion-forums.php" class="<?php echo ($current_page == 'Adiscussion-forums.php') ? 'active' : ''; ?>">Discussion Forums</a></li>
            <li><a href="Amentoring.php" class="<?php echo ($current_page == 'Amentoring.php') ? 'active' : ''; ?>">Mentoring</a></li>

            <li><a href="Amy-profile.php" class="<?php echo ($current_page == 'Amy-profile.php') ? 'active' : ''; ?>">My Profile</a></li>
        </ul>
        
        <div class="hamburger" id="hamburger">
            <span></span>
            <span></span>
            <span></span>
        </div>
    </div>
</nav>

<style>
:root {
  --primary-purple: #6b46c1;
  --secondary-purple: #8b5cf6;
  --purple-gradient: linear-gradient(135deg, #6b46c1, #8b5cf6);
  --text-dark: #1f2937;
  --text-light: #6b7280;
  --white: #ffffff;
  --light-gray: #f9fafb;
  --border-color: #e5e7eb;
}

.navbar {
    background: var(--white);
    padding: 1rem 2rem;
    box-shadow: 0 2px 8px rgba(107, 70, 193, 0.1);
    border-bottom: 1px solid var(--border-color);
}

.nav-container {
    max-width: 1200px;
    margin: 0 auto;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.nav-logo a {
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--primary-purple);
    text-decoration: none;
    display: flex;
    align-items: center;
}

.logo-img {
    max-height: 40px;
    width: auto;
}

.nav-menu {
    display: flex;
    list-style: none;
    margin: 0;
    padding: 0;
    gap: 2rem;
}

.nav-menu a {
    text-decoration: none;
    color: var(--text-light);
    font-weight: 500;
    padding: 0.5rem 1rem;
    border-radius: 6px;
    transition: all 0.3s ease;
}

.nav-menu a:hover {
    color: var(--primary-purple);
    background: rgba(107, 70, 193, 0.05);
}

.nav-menu a.active {
    color: var(--white);
    background: var(--purple-gradient);
}

.hamburger {
    display: none;
    flex-direction: column;
    cursor: pointer;
}

.hamburger span {
    width: 25px;
    height: 3px;
    background: var(--text-dark);
    margin: 3px 0;
    transition: 0.3s;
}

@media (max-width: 768px) {
    .nav-menu {
        position: fixed;
        left: -100%;
        top: 70px;
        flex-direction: column;
        background: var(--white);
        width: 100%;
        text-align: center;
        transition: 0.3s;
        box-shadow: 0 10px 27px rgba(107, 70, 193, 0.1);
        padding: 2rem 0;
        gap: 0;
    }

    .nav-menu.active { left: 0; }

    .nav-menu li { margin: 0.5rem 0; }

    .nav-menu a {
        display: block;
        padding: 1rem 2rem;
        margin: 0 1rem;
    }

    .hamburger { display: flex; }

    .hamburger.active span:nth-child(1) { transform: rotate(45deg) translate(5px, 5px); }
    .hamburger.active span:nth-child(2) { opacity: 0; }
    .hamburger.active span:nth-child(3) { transform: rotate(-45deg) translate(7px, -6px); }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const hamburger = document.getElementById('hamburger');
    const navMenu = document.getElementById('nav-menu');
    
    hamburger.addEventListener('click', function() {
        hamburger.classList.toggle('active');
        navMenu.classList.toggle('active');
    });
});
</script>