<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UniVerse - Bridge Your Future</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/css/styles.css">
    <link rel="icon" type="image/png" href="<?= BASE_URL ?>/assets/images/U.png">
</head>
<body>
    <!-- <div class="scroll-watcher"></div> -->
    <?php include 'Unavigation.view.php'; ?>
    <section class="Uhome welcome-section">
        <div class="container">
            <div class="welcome-content">
                    <h1>Welcome back, Vinuja!</h1>
                    <p>Ready to catch up where you left off? Here's a quick overview of your academic journey and upcoming activities at UniVerse.</p>
                    <button class="explore-btn" onclick="document.getElementById('explore-section').scrollIntoView({ behavior: 'smooth' });">Start Exploring</button>
            </div>
        </div>
    </section>
    
    <section class="Uhome" id="explore-section">
        <div class="container">
            <div class="content">
                <h1>Explore Jobs and Internships Tailored for You</h1><br>
                <p>Discover a world of opportunities with our curated job and internship listings. Connect with companies seeking fresh talent and take the first step towards your dream career.</p>
            </div>
            <div class="image">
                <div class="main-image">
                    <img src="<?= BASE_URL ?>/assets/images/jobs.jpeg" alt="insert" class="image">
                </div>
            </div>
        </div>
    </section>

    <section class="Uhome">
        <div class="container">
            <div class="image"> 
                <div class="main-image">
                    <img src="<?= BASE_URL ?>/assets/images/hero section.jpeg" alt="insert 2">
                </div>         
            </div>
            <div class="content">
                <h2>Explore Mentorship Opportunities Tailored for You</h2><br>
                <p>Mentorship is a powerful tool that connects you with experienced professionals. Gain insights, guidance, and support as you navigate your career path.</p>
            </div>
        </div>
    </section>

    <section class="Uhome">
        <div class="container">
            <div class="content">
            <h2>Explore Articles and Forums to Enhance Your Career Journey</h2><br>
            <p>Dive into a wealth of articles and engaging forums tailored for your career growth. Connect with peers and industry experts to gain insights and share experiences.</p>
            </div>
            <div class="image">
                <div class="main-image">
                    <img src="<?= BASE_URL ?>/assets/images/hero section.jpeg" alt="insert 3">
                </div>
            </div>
        </div>
    </section>

</body>
    <?php include 'Ufooter.view.php'; ?>
</html>