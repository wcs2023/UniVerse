<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Get Started - UniVerse</title>
  <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/register.css">

  
</head>
<body>
  <!-- Header -->
  <header class="header">
    <div class="logo">UniVerse</div>
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
    <button class="join-btn">Join</button>
  </header>

  <!-- Signup Form -->
  <main class="signup-main">
    <div class="signup-container">
      <p class="signup-welcome">Welcome</p>
      <h1 class="signup-title">Get Started</h1>
      <p class="signup-sub">Join us to shape your future today!</p>
      <form class="signup-form">
        <div class="form-group">
          <label for="firstName">First name</label>
          <input type="text" id="firstName" name="firstName" required />
        </div>
        <div class="form-group">
          <label for="lastName">Last name</label>
          <input type="text" id="lastName" name="lastName" required />
        </div>
        <div class="form-group">
          <label for="email">E-Mail</label>
          <input type="email" id="email" name="email" required />
        </div>
        <div class="form-group">
          <label for="phone">Phone number</label>
          <input type="tel" id="phone" name="phone" required />
        </div>
        <div class="form-group">
          <label for="username">Username</label>
          <input type="text" id="username" name="username" required />
        </div>
        <div class="form-group">
          <label for="password">Password</label>
          <input type="password" id="password" name="password" required />
        </div>
        <div class="form-group">
          <label for="confirmPassword">Confirm Password</label>
          <input type="password" id="confirmPassword" name="confirmPassword" required />
        </div>
        <div class="form-group checkbox-group">
          <input type="checkbox" id="terms" required />
          <label for="terms">I agree to all the terms and conditions</label>
        </div>
        <div class="signup-actions">
          <button type="button" class="back-btn">Back</button>
          <button type="submit" class="submit-btn">Submit</button>
        </div>
      </form>
      <div class="signup-login">
        <span>Already have an account? <a href="login.html" class="login-link">Login</a></span>
      </div>
    </div>
  </main>

  <!-- Footer -->
  <footer class="footer">
    <div class="footer-top">
      <div class="footer-logo">UniVerse</div>
      <div class="footer-links">
        <a href="#">Blog Posts</a>
        <a href="#">Career Tips</a>
        <a href="#">Events</a>
        <a href="#">About Us</a>
        <a href="#">Contact Us</a>
      </div>
      <div class="footer-social">
        <a href="#" aria-label="Facebook"><img src="https://img.icons8.com/ios-filled/20/ffffff/facebook-new.png" alt="Facebook"/></a>
        <a href="#" aria-label="Instagram"><img src="https://img.icons8.com/ios-filled/20/ffffff/instagram-new.png" alt="Instagram"/></a>
        <a href="#" aria-label="LinkedIn"><img src="https://img.icons8.com/ios-filled/20/ffffff/linkedin.png" alt="LinkedIn"/></a>
      </div>
    </div>
    <div class="footer-bottom">
      <span>&copy; 2025 UniVerse. All rights reserved.</span>
      <div>
        <a href="#">Privacy Policy</a>
        <a href="#">Terms of Use</a>
        <a href="#">Cookie Settings</a>
      </div>
    </div>
  </footer>
</body>
</html>
