<head>
    <link rel="stylesheet" href="<?=BASE_URL?>/assets/css/student_style.css">
    <style>
    /* ===== IMAGE STYLES ===== */
    .placeholder-img {
        width: 100%;
        height: 100%;
        border-radius: 8px;
        overflow: hidden;
        display: flex;
        align-items: stretch;
        justify-content: stretch;
        background: transparent;
        margin-bottom: 0;
    }

    .placeholder-img::before {
        content: none;
    }

    .placeholder-img img {
        width: 100%;
        height: 100%;
        display: block;
        object-fit: cover;
        border-radius: 8px;
    }

    /* ===== HERO SECTION IMAGE ===== */
    .hero-section {
        align-items: stretch;
        gap: 3rem;
    }

    .hero-section > :first-child {
        flex: 1;
    }

    .hero-section > :last-child {
        flex: 0 0 420px;
        display: flex;
    }

    .hero-section > :last-child .placeholder-img {
        min-height: 320px;
    }

    /* ===== FEATURES SECTION IMAGE ===== */
    .features-section {
        display: grid;
        grid-template-columns: 420px 1fr;
        gap: 3rem;
        align-items: stretch;
    }

    .features-section > :first-child {
        display: flex;
        height: 100%;
    }

    .features-section > :first-child .placeholder-img {
        width: 100%;
        height: 100%;
        min-height: 100%;
    }

    /* ===== OPTIONAL: make content area align nicely ===== */
    .feature-content {
        display: flex;
        flex-direction: column;
        justify-content: center;
    }

    /* ===== TABLET ===== */
    @media only screen and (max-width: 992px) {
        .hero-section {
            gap: 2rem;
        }

        .hero-section > :last-child {
            flex: 0 0 340px;
        }

        .hero-section > :last-child .placeholder-img {
            min-height: 280px;
        }

        .features-section {
            grid-template-columns: 320px 1fr;
            gap: 2rem;
        }
    }

    /* ===== MOBILE ===== */
    @media only screen and (max-width: 768px) {
        .hero-section {
            flex-direction: column;
            align-items: stretch;
            text-align: center;
        }

        .hero-section > :first-child,
        .hero-section > :last-child {
            flex: none;
            width: 100%;
        }

        .hero-section > :last-child {
            display: block;
        }

        .hero-section > :last-child .placeholder-img {
            min-height: 250px;
        }

        .features-section {
            grid-template-columns: 1fr;
            gap: 2rem;
        }

        .features-section > :first-child {
            display: block;
        }

        .features-section > :first-child .placeholder-img {
            min-height: 250px;
        }

        .feature-content {
            justify-content: flex-start;
        }
    }

    /* ===== SMALL MOBILE ===== */
    @media only screen and (max-width: 480px) {
        .hero-section > :last-child .placeholder-img,
        .features-section > :first-child .placeholder-img {
            min-height: 220px;
        }
    }
</style>
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
                <a href="<?= BASE_URL ?>/Degrees/degree_suggestion_index" class="btn btn-try">Try Now</a>
            </div>
        </div>
        <div>
            <div class="placeholder-img">
                <img src="<?= BASE_URL ?>/assets/images/zscore.png" alt="Z-Score Image" class="imgS">
            </div>
        </div>
    </section>

    <!-- features section -->
    <section class="features-section fade-in">
        <div>
            <div class="placeholder-img"><img src="<?= BASE_URL ?>/assets/images/article_student.jpeg" alt="Feature Image"></div>
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
                    <a href="<?= BASE_URL ?>/Discussion_Forum/index" class="btn btn-outline">Join Forums</a> 
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