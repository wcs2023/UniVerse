<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UniVerse - Share Your Journey</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/css/alumni.css">
    <link rel="icon" type="image/png" href="<?= BASE_URL ?>/assets/images/U.png">
    <style>
        body {
            padding-top: 80px;
            background-color: #a78bfa45 !important;
        }
    </style>
</head>

<body>
    <?php include __DIR__ . '/Anavbar.php'; ?>

    <!-- Welcome Section -->
    <section class="welcome-section">
        <div class="container">
            <div class="welcome-content">
                <h1>Welcome back <?= htmlspecialchars($_SESSION['first_name'] ?? 'Alumni') ?>!</h1>
                <p>Share your journey with the next generation. Your experiences, career insights, and lessons learned
                    can guide students and peers on their path to success.</p>
                <button class="explore-btn"
                    onclick="document.getElementById('explore-section').scrollIntoView({ behavior: 'smooth' });">Start
                    Exploring</button>
            </div>
        </div>
    </section>

    <!-- Article Creation Section -->
    <section class="hero-section" id="explore-section">
        <div class="container">
            <div class="hero-content">
                <div class="hero-text">
                    <h1>Share Your Journey With the Next Generation</h1>
                    <p>Tell your story – from university to industry. Share career tips, experiences, and lessons that
                        can guide students and peers.</p>

                    <div class="article-section">
                        <h3>Create an Article</h3>
                        <p>Write about your academic or professional journey</p>
                        <a href="<?= BASE_URL ?>/aarticles/create" class="btn">Try Now</a>
                    </div>
                </div>
                <div class="hero-image"></div>
            </div>
        </div>
    </section>

    <!-- Mentor Section -->
    <section class="mentor-section">
        <div class="container">
            <div class="mentor-content">
                <div class="mentor-image"></div>
                <div class="mentor-text">
                    <h2>Become a Mentor, Shape Careers</h2>
                    <p>Support aspiring professionals by offering guidance, answering questions, and reviewing
                        portfolios or resumes. Your experience can make a real difference in someone's career journey.
                    </p>
                    <a href="<?= BASE_URL ?>/amentorships" class="btn-mentor">Become a Mentor</a>
                </div>
            </div>
        </div>
    </section>

    <!-- Discussion Forum Section -->
    <section class="hero-section">
        <div class="container">
            <div class="hero-content">
                <div class="hero-text">
                    <h2>Join the Discussion</h2>
                    <p>Connect with fellow alumni and current students in our discussion forums. Share insights, ask
                        questions, and build meaningful professional connections.</p>
                    <a href="<?= BASE_URL ?>/adiscussion" class="btn" style="margin-top: 1.5rem;">Explore Forums</a>
                </div>
                <div class="mentor-image"
                    style="background-image: url('<?= BASE_URL ?>/assets/images/career.jpg'); background-color: #e0e7ff;">
                </div>
            </div>
        </div>
    </section>

    <!-- Include Footer -->
    <?php include __DIR__ . '/../../layout/footer.php'; ?>

    <script>
        // Smooth scrolling for anchor links
        document.addEventListener('DOMContentLoaded', function () {
            // Add smooth scroll behavior
            document.documentElement.style.scrollBehavior = 'smooth';

            // Button click animations
            const buttons = document.querySelectorAll('.btn, .btn-mentor, .explore-btn');
            buttons.forEach(button => {
                button.addEventListener('click', function (e) {
                    // Add click effect
                    this.style.transform = 'scale(0.95)';
                    setTimeout(() => {
                        this.style.transform = 'scale(1)';
                    }, 150);
                });
            });

            // Fade in animation on scroll
            const observerOptions = {
                threshold: 0.1,
                rootMargin: '0px 0px -50px 0px'
            };

            const observer = new IntersectionObserver(function (entries) {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.style.opacity = '1';
                        entry.target.style.transform = 'translateY(0)';
                    }
                });
            }, observerOptions);

            // Observe sections for animation
            const sections = document.querySelectorAll('.hero-section, .mentor-section');
            sections.forEach(section => {
                section.style.opacity = '0';
                section.style.transform = 'translateY(20px)';
                section.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
                observer.observe(section);
            });
        });
    </script>
</body>

</html>