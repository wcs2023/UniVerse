<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home - Share Your Journey</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/css/alumni.css">
    <link rel="icon" type="image/png" href="<?= BASE_URL ?>/assets/images/U.png">
   
</head>
<body>
    <?php include __DIR__ . '/Anavbar.php'; ?>
    
    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container">
            <div class="hero-content">
                <div class="hero-text">
                    <h1>Share Your Journey With the Next Generation</h1>
                    <p>Tell your story – from university to industry. Share career tips, experiences, and lessons that can guide students and peers.</p>
                    
                    <div class="article-section">
                        <h3>Create a Article</h3>
                        <p>Write about your academic or professional journey</p>
                        <a href="<?= BASE_URL ?>/aarticles/create" class="btn">Try Now</a>
                    </div>
                </div>
                <div class="hero-image"></div>
            </div>
        </div>
    </section>
    
    <!-- Recent Articles Section
    <?php if (!empty($data['articles'])): ?>
    <section class="articles-section">
        <div class="container">
            <div class="section-header">
                <h2>Recent Articles</h2>
                <a href="<?= BASE_URL ?>/aarticles" class="view-all-link">View All Articles →</a>
            </div>
            <div class="articles-grid">
                <?php foreach ($data['articles'] as $article): ?>
                <article class="article-card">
                    <div class="article-content">
                        <div class="article-meta">
                            <span class="article-category"><?= htmlspecialchars($article['category'] ?? 'General') ?></span>
                            <span class="article-date"><?= date('M d, Y', strtotime($article['published_at'])) ?></span>
                        </div>
                        <h3 class="article-title">
                            <a href="<?= BASE_URL ?>/articles/view/<?= $article['article_id'] ?>">
                                <?= htmlspecialchars($article['title']) ?>
                            </a>
                        </h3>
                        <p class="article-excerpt">
                            <?= htmlspecialchars(substr(strip_tags($article['content']), 0, 150)) ?>...
                        </p>
                        <div class="article-footer">
                            <div class="article-author">
                                <span>By <?= htmlspecialchars($article['author_name']) ?></span>
                            </div>
                            <div class="article-stats">
                                <span>👁️ <?= $article['views'] ?></span>
                                <span>❤️ <?= $article['likes'] ?? 0 ?></span>
                            </div>
                        </div>
                    </div>
                </article>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
    <?php endif; ?> -->
    
    <!-- Mentor Section -->
    <section class="mentor-section">
        <div class="container">
            <div class="mentor-content">
                <div class="mentor-image"></div>
                <div class="mentor-text">
                    <h2>Become a Mentor, Shape Careers</h2>
                    <p>Support aspiring professionals by offering guidance, answering questions, and reviewing portfolios or resumes</p>
                    <a href="<?= BASE_URL ?>/mentorships" class="btn-mentor">Become a Mentor</a>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Include Footer -->
    <?php include __DIR__ . '/../../layout/footer.php'; ?>
    <script>
        // Smooth scrolling for anchor links
        document.addEventListener('DOMContentLoaded', function() {
            // Add smooth scroll behavior
            document.documentElement.style.scrollBehavior = 'smooth';
            
            // Button click animations
            const buttons = document.querySelectorAll('.btn, .btn-mentor');
            buttons.forEach(button => {
                button.addEventListener('click', function(e) {
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
            
            const observer = new IntersectionObserver(function(entries) {
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
            
            // Social media click handlers
            const socialLinks = document.querySelectorAll('.social-icons a');
            socialLinks.forEach(link => {
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    const platform = this.getAttribute('title');
                    alert(`Redirecting to ${platform}...`);
                });
            });
            
            // Form validation for future use
            window.validateForm = function(form) {
                const inputs = form.querySelectorAll('input[required], textarea[required]');
                let isValid = true;
                
                inputs.forEach(input => {
                    if (!input.value.trim()) {
                        input.style.borderColor = 'var(--error-color, #ef4444)';
                        isValid = false;
                    } else {
                        input.style.borderColor = 'var(--border-color)';
                    }
                });
                
                return isValid;
            };
            
            // Optional: Add subtle hover effects to image containers
            const imageElements = document.querySelectorAll('.hero-image, .mentor-image');
            imageElements.forEach(img => {
                img.addEventListener('mouseenter', function() {
                    this.style.transform = 'scale(1.02)';
                    this.style.transition = 'transform 0.3s ease';
                });
                
                img.addEventListener('mouseleave', function() {
                    this.style.transform = 'scale(1)';
                });
            });
        });
    </script>
</body>
</html>