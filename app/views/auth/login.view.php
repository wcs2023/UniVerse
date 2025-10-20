<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Login - UniVerse</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/login.css">

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

  <!-- Login Form -->
  <main class="login-main">
    <div class="login-container">
      <h1>UniVerse</h1>
      <form class="login-form">
        <div class="form-group">
          <label for="login-username">Username</label>
          <input type="text" id="login-username" name="login-username" required />
        </div>
        <div class="form-group">
          <label for="login-password">Password</label>
          <input type="password" id="login-password" name="login-password" required />
        </div>
        <div class="login-links">
          <a href="#">Forgot Password?</a>
        </div>
        <button type="submit" class="login-btn">Log In</button>
      </form>
      <p style="margin-top:15px;font-size:0.95rem;">
        Don't have an account? <a href="signup.html" style="color:#1abc9c;">Sign Up</a>
      </p>
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
      <a href="#">Privacy Policy</a>
      <a href="#">Terms of Use</a>
      <a href="#">Cookie Settings</a>
    </div>
  </footer>
</body>
</html>
