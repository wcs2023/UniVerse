<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home - Share Your Journey</title>
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
        
        * { margin: 0; padding: 0; box-sizing: border-box; }
        
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            background: var(--light-gray);
            color: var(--text-dark);
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 2rem;
        }
        
        /* Hero Section */
        .hero-section {
            background: var(--white);
            padding: 4rem 0;
        }
        
        .hero-content {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 4rem;
            align-items: center;
        }
        
        .hero-text h1 {
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--text-dark);
            margin-bottom: 1.5rem;
            line-height: 1.2;
        }
        
        .hero-text p {
            color: var(--text-light);
            font-size: 1.1rem;
            margin-bottom: 2rem;
            line-height: 1.7;
        }
        
        .article-section h3 {
            color: var(--text-dark);
            font-size: 1.3rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }
        
        .article-section p {
            color: var(--text-light);
            font-size: 0.95rem;
            margin-bottom: 1.5rem;
        }
        
        .btn {
            display: inline-flex;
            align-items: center;
            background: var(--white);
            color: var(--text-dark);
            padding: 12px 20px;
            text-decoration: none;
            border: 2px solid var(--border-color);
            border-radius: 6px;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        
        .btn:hover {
            border-color: var(--primary-purple);
            color: var(--primary-purple);
        }
        
        .btn::before {
            content: "🔍";
            margin-right: 8px;
        }
        
        .hero-image-wrapper {
            position: relative;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
        }
        
        .hero-image {
            width: 100%;
            height: auto;
            display: block;
            border-radius: 16px;
            transition: transform 0.3s ease;
        }
        
        .hero-image-wrapper:hover .hero-image {
            transform: scale(1.05);
        }
        
        .hero-badge {
            position: absolute;
            top: 20px;
            right: 20px;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            padding: 0.75rem 1.25rem;
            border-radius: 50px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
            display: flex;
            align-items: center;
            gap: 0.5rem;
            animation: fadeInDown 0.6s ease;
        }
        
        .hero-badge-icon {
            font-size: 1.25rem;
        }
        
        .hero-badge-text {
            font-size: 0.875rem;
            font-weight: 600;
            color: var(--primary-purple);
        }
        
        /* Mentor Section */
        .mentor-section {
            background: var(--white);
            padding: 4rem 0;
        }
        
        .mentor-content {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 4rem;
            align-items: center;
        }
        
        .mentor-image-wrapper {
            position: relative;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
        }
        
        .mentor-image {
            width: 100%;
            height: auto;
            display: block;
            border-radius: 16px;
            transition: transform 0.3s ease;
        }
        
        .mentor-image-wrapper:hover .mentor-image {
            transform: scale(1.05);
        }
        
        .mentor-badge {
            position: absolute;
            top: 20px;
            left: 20px;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            padding: 0.75rem 1.25rem;
            border-radius: 50px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
            display: flex;
            align-items: center;
            gap: 0.5rem;
            animation: fadeInDown 0.6s ease;
        }
        
        @keyframes fadeInDown {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .mentor-badge-icon {
            font-size: 1.25rem;
        }
        
        .mentor-badge-text {
            font-size: 0.875rem;
            font-weight: 600;
            color: var(--primary-purple);
        }
        
        .mentor-text h2 {
            font-size: 2rem;
            font-weight: 700;
            color: var(--text-dark);
            margin-bottom: 1.5rem;
            line-height: 1.3;
        }
        
        .mentor-text p {
            color: var(--text-light);
            font-size: 1.1rem;
            margin-bottom: 2rem;
            line-height: 1.7;
        }
        
        .btn-mentor {
            display: inline-flex;
            align-items: center;
            background: var(--white);
            color: var(--text-dark);
            padding: 12px 20px;
            text-decoration: none;
            border: 2px solid var(--border-color);
            border-radius: 6px;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        
        .btn-mentor:hover {
            border-color: var(--primary-purple);
            color: var(--primary-purple);
        }
        
        .btn-mentor::before {
            content: "👤";
            margin-right: 8px;
        }
        
        /* Responsive Design */
        @media (max-width: 768px) {
            .hero-content,
            .mentor-content {
                grid-template-columns: 1fr;
                gap: 2rem;
            }
            
            .hero-text h1 {
                font-size: 2rem;
            }
            
            .mentor-text h2 {
                font-size: 1.7rem;
            }
            
            .hero-section,
            .mentor-section {
                padding: 3rem 0;
            }
        }
    </style>
</head>
<body>
    <?php include 'Anavbar.php'; ?>
    
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
                        <a href="client-articles.php" class="btn">Try Now</a>
                    </div>
                </div>
                <div class="hero-image-wrapper">
                    <img src="<?php echo defined('URLROOT') ? URLROOT : '/UniVerse/public'; ?>/assets/img/article.png" 
                         alt="Article creation and content writing" 
                         class="hero-image"
                         onerror="this.style.display='none'; this.parentElement.innerHTML='<div style=\'background:#d1d5db;height:400px;border-radius:16px;display:flex;align-items:center;justify-content:center;font-size:4rem;opacity:0.5;\'>🖼️</div>';">
                       
                </div>
            </div>
        </div>
    </section>
    
    <!-- Mentor Section -->
    <section class="mentor-section">
        <div class="container">
            <div class="mentor-content">
                <div class="mentor-image-wrapper">
                    <img src="<?php echo defined('URLROOT') ? URLROOT : '/UniVerse/public'; ?>/assets/img/mentorship.png" 
                         alt="Professional mentorship meeting between alumni and student" 
                         class="mentor-image"
                         onerror="this.style.display='none'; this.parentElement.innerHTML='<div style=\'background:#d1d5db;height:400px;border-radius:16px;display:flex;align-items:center;justify-content:center;font-size:3rem;opacity:0.5;\'>🖼️</div>';">
                    
                </div>
                <div class="mentor-text">
                    <h2>Become a Mentor, Shape Careers</h2>
                    <p>Support aspiring professionals by offering guidance, answering questions, and reviewing portfolios or resumes. Make a lasting impact on students' futures.</p>
                    <a href="mentoring.php" class="btn-mentor">Become a Mentor</a>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Include Footer -->
    <?php include 'Afooter.php'; ?>
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
            
            // Image placeholder loading effect
            const imageElements = document.querySelectorAll('.hero-image, .mentor-image');
            imageElements.forEach(img => {
                img.addEventListener('mouseenter', function() {
                    this.style.background = 'linear-gradient(45deg, #d1d5db, #9ca3af)';
                });
                
                img.addEventListener('mouseleave', function() {
                    this.style.background = '#d1d5db';
                });
            });
        });
    </script>
</body>
</html>