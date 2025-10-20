<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>UniVerse - Empowering Your Career Journey</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/home.css">
    
  </head>
  <body>
    <!-- Header -->
    <header class="header">
      <div class="logo">Logo</div>
      <nav class="nav">
        <a href="#">For Students</a>
        <a href="#">Resources</a>
        <div class="dropdown">
          <a href="#" class="dropbtn">Contact Us <span>&#9660;</span></a>
          <div class="dropdown-content">
            <a href="#">Email</a>
            <a href="#">Phone</a>
            <a href="#">Office</a>
          </div>
        </div>
      </nav>
      <button class="join-btn" >
        <a href="/UniVerse/public/register">Register</a>
      </button>
      <button class="join-btn" >
        <a href="/UniVerse/public/login">Login</a>
      </button>
    </header>

    <!-- Hero Section -->
    <section class="hero">
      <div class="hero-content">
        <h1>Empowering Your<br />Career Journey Starts<br />Here</h1>
        <p>
          Discover the right path for your future with personalized career
          guidance and opportunities. Join our community to connect with
          mentors, explore internships, and build your professional profile.
        </p>
        <div class="hero-buttons">
          <button class="primary" onclick="window.location.href='signup.html'">
            Get Started
          </button>
          <button class="secondary">Learn More</button>
        </div>
      </div>
    </section>

    <!-- Features Section -->
    <section class="features">
      <h2>Unlock Your Future with UniVerse</h2>
      <p class="features-desc">
        At UniVerse, we provide comprehensive support for students navigating
        their educational and career paths. Our platform connects you with the
        resources you need to make informed decisions about your future.
      </p>
      <div class="features-list">
        <div class="feature">
          <div class="feature-icon">&#128230;</div>
          <h3>Guided Degree Selection<br />Tailored to You</h3>
          <p>
            Receive personalized degree recommendations based on your strengths
            and preferences.
          </p>
        </div>
        <div class="feature">
          <div class="feature-icon">&#128218;</div>
          <h3>Career Advice from<br />Industry Experts</h3>
          <p>
            Access valuable insights and articles to guide your career choices.
          </p>
        </div>
        <div class="feature">
          <div class="feature-icon">&#128188;</div>
          <h3>Internships and Job<br />Opportunities Await</h3>
          <p>
            Explore tailored internship and job listings to kickstart your
            career.
          </p>
        </div>
      </div>
    </section>

    <!-- Contact Section -->
    <section class="contact">
      <div class="contact-methods">
        <div class="contact-item">
          <div class="contact-icon">&#9993;</div>
          <h4>Email</h4>
          <p>For support and inquiries, reach out to us anytime.</p>
          <a href="mailto:support@universe.com">support@universe.com</a>
        </div>
        <div class="contact-item">
          <div class="contact-icon">&#128222;</div>
          <h4>Phone</h4>
          <p>Call us for immediate assistance or questions.</p>
          <a href="tel:+15551234567">+1 (555) 123-4567</a>
        </div>
        <div class="contact-item">
          <div class="contact-icon">&#128205;</div>
          <h4>Office</h4>
          <p>Visit us at our headquarters for more information.</p>
          <address>456 Example Ave, Colombo 07, 10000 LK</address>
        </div>
      </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
      <div class="footer-top">
        <div class="footer-logo">Logo</div>
        <div class="footer-links">
          <a href="#">Blog Posts</a>
          <a href="#">Career Tips</a>
          <a href="#">Events</a>
          <a href="#">About Us</a>
          <a href="#">Contact Us</a>
        </div>
        <div class="footer-social">
          <a href="#"><img src="https://img.icons8.com/ios-filled/20/000000/facebook-new.png" alt="Facebook"/></a>
          <a href="#"><img src="https://img.icons8.com/ios-filled/20/000000/instagram-new.png" alt="Instagram"/></a>
          <a href="#"><img src="https://img.icons8.com/ios-filled/20/000000/linkedin.png" alt="LinkedIn"/></a>
        </div>
      </div>
      <div class="footer-bottom">
        <span>&copy; 2025 UniVerse. All rights reserved.</span>
        <a href="#">Privacy Policy</a>
        <a href="#">Terms of Use</a>
        <a href="#">Cookie Settings</a>
      </div>
    </footer>
  </body>
</html>
