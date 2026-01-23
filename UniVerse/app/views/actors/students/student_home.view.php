<head>
    <link rel="stylesheet" href="<?=BASE_URL?>/assets/css/student_style.css">
</head>
<?php
    $title = "School Leavers' Home";
    include_once __DIR__ . '/includes/header2.view.php';
?>

<main class="main-container">
    <section class="hero-section fade-in">
        <div class="hero-content">
            <h1 class="hero-title">Personalized Degree Suggestions Just For You</h1>
            <p class="hero-description">Unlock your potential with tailored degree suggestions based on your Z-score and personal preferences. Make informed decisions that align with your career aspirations.</p>
            <div class="try-button">
                <a href="<?= BASE_URL ?>/schoolleaver/degreeSuggestion" class="btn btn-try">Try Now</a>
            </div>
        </div>
        <div class="hero-image">
            <div class="placeholder-img"></div>
        </div>
    </section>

    <!-- features section -->
    <section class="features-section fade-in">
        <div class="feature-image">
            <div class="placeholder-img"></div>
        </div>
        <div class="feature-content">
            <h2>Explore Articles and Forums to Enhance Your Career Journey</h2>
            <p class="feature-description">
                Drive into a wealth of articles and engaging forums tailored for your career growth. Connect with peers and industry experts to gain insights and share experiences.
            </p>
            <div class="feature-grid">
                <div class="feature-card">
                    <h3>Career Articles</h3>
                    <p>Stay informed with the latest trends and advice in your fields</p>
                    <a href="<?= BASE_URL ?>/schoolleaver/articles" class="btn btn-outline">Explore Articles</a> 
                </div>
                <div class="feature-card">
                    <h3>Discussion Forums</h3>
                    <p>Engage in discussion, ask questions and share insights with fellow students</p>
                    <a href="<?= BASE_URL ?>/schoolleaver/forums" class="btn btn-outline">Join Forums</a> 
                </div>
            </div>
        </div>
    </section>
</main>


<?php include __DIR__ . '/../../layout/footer.php'; ?>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Add loading animation
        const cards = document.querySelectorAll('.feature-card');
        cards.forEach((card, index) => {
            card.style.animationDelay = `${index * 0.2}s`;
            card.classList.add('fade-in');
        });
    });
</script>

</body>
</html>