<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UniVerse - Bridge Your Future</title>
    <link rel="stylesheet" href="css/styles.css">
    <link rel="icon" type="image/png" href="assets/images/U.png">
</head>
<body>
    <!-- Scroll Progress Bar -->
    <div class="scroll-progress" id="scroll-progress"></div>

    <!-- Header (included) -->
    <?php include __DIR__ . '/layout/nav_home.php'; ?>

    <!-- Hero Section -->
    <section id="home" class="hero">
        <div class="container">
            <div class="hero-content">
                <h1 class="hero-title">
                    Bridge the Gap Between <span class="highlight">Students</span> and
                    <span class="highlight">Industry</span>
                </h1>
                <p class="hero-description">
                    UniVerse empowers Sri Lankan students with career guidance, degree recommendations, internship
                    opportunities, and industry connections to build successful careers.
                </p>
                <div class="hero-buttons">
                <a href="registration" class="btn btn-primary btn-large">Get Started</a>
                    <button class="btn btn-secondary btn-large" id="learn-more-btn">Learn More</button>
                </div>
            </div>
            <div class="hero-image">
                <div class="hero-main-image">
                    <img src="assets/images/heroImage.jpg" alt="Students connecting with industry professionals" class="main-hero-img">
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="features">
        <div class="container">
            <h2 class="section-title fade-in">Empowering Your Journey</h2>
            <div class="features-grid">
                <div class="feature-card fade-in stagger-1">
                    <!-- <div class="feature-icon"></div> -->
                    <h3>Degree Recommendations</h3>
                    <p>Get personalized degree suggestions based on your Z-score, stream, and career preferences.</p>
                </div>
                <div class="feature-card fade-in stagger-2">
                    <!-- <div class="feature-icon"></div> -->
                    <h3>Career Guidance Hub</h3>
                    <p>Access expert articles, discussion forums, and industry insights to make informed career decisions.</p>
                </div>
                <div class="feature-card fade-in stagger-3">
                    <!-- <div class="feature-icon"></div> -->
                    <h3>Industry Connections</h3>
                    <p>Connect with internships, job opportunities, and companies tailored to your field of study.</p>
                </div>
                <div class="feature-card fade-in stagger-4">
                    <!-- <div class="feature-icon"></div> -->
                    <h3>Mentorship Network</h3>
                    <p>Get guidance from experienced alumni and industry professionals to accelerate your growth.</p>
                </div>
                <div class="feature-card fade-in stagger-5">
                    <!-- <div class="feature-icon"></div> -->
                    <h3>Portfolio Building</h3>
                    <p>Showcase your achievements, projects, and skills to increase industry visibility.</p>
                </div>
                <div class="feature-card fade-in stagger-6">
                    <!-- <div class="feature-icon"></div> -->
                    <h3>Targeted Opportunities</h3>
                    <p>Discover internships and jobs specifically matched to your skills and career goals.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Target Audience Section -->
    <section class="audience" id="audience">
        <div class="container">
            <h2 class="section-title fade-in">Who We Serve</h2>
            <div class="audience-grid">
                <div class="audience-card slide-in-left stagger-1">
                    <!-- <div class="audience-icon">🎓</div> -->
                    <h3>School Leavers</h3>
                    <p>Get degree recommendations based on your A/L results and explore career paths that align with your interests.</p>
                    <ul>
                        <li>Z-score based degree suggestions</li>
                        <li>Career exploration resources</li>
                        <li>University guidance</li>
                    </ul>
                </div>
                <div class="audience-card slide-in-right stagger-2">
                    <!-- <div class="audience-icon">📚</div> -->
                    <h3>Undergraduates</h3>
                    <p>Build your professional network, find internships, and prepare for your dream career with expert guidance.</p>
                    <ul>
                        <li>Internship & job opportunities</li>
                        <li>Mentorship programs</li>
                        <li>Portfolio building tools</li>
                    </ul>
                </div>
                <div class="audience-card slide-in-left stagger-3">
                    <!-- <div class="audience-icon">🏢</div> -->
                    <h3>Companies</h3>
                    <p>Connect with talented students and graduates to find the perfect candidates for your organization.</p>
                    <ul>
                        <li>Post job & internship openings</li>
                        <li>Access student profiles</li>
                        <li>Streamlined hiring process</li>
                    </ul>
                </div>
                <div class="audience-card slide-in-right stagger-4">
                    <!-- <div class="audience-icon">🌟</div> -->
                    <h3>Alumni</h3>
                    <p>Give back to the community by mentoring students and sharing your professional experiences.</p>
                    <ul>
                        <li>Mentorship opportunities</li>
                        <li>Knowledge sharing platform</li>
                        <li>Professional networking</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta">
        <div class="container">
            <div class="cta-content scale-in">
                <h1>Ready to Bridge Your Future?</h1>
                <p>Join thousands of students and professionals already using UniVerse to build successful careers.</p>
                <div class="cta-buttons">
                    <a href="registration" class="btn btn-primary btn-large">Join with us</a>
                    <!-- <a href="register.html" class="btn btn-outline btn-large">Join as Company</a> -->
                </div>
            </div>
        </div>
    </section>
    <?php include __DIR__ . '/layout/footer.php'; ?>
    <script src="js/main.js"></script>
</body>
</html>