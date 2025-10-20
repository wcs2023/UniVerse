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
        
        .hero-image {
            background: #d1d5db;
            height: 400px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
        }
        
        .hero-image::before {
            content: "🖼️";
            font-size: 4rem;
            opacity: 0.5;
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
        
        .mentor-image {
            background: #d1d5db;
            height: 300px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .mentor-image::before {
            content: "🖼️";
            font-size: 3rem;
            opacity: 0.5;
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
                    <p>Support aspiring professionals by offering guidance, answering questions, and reviewing portfolios or resumes</p>
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