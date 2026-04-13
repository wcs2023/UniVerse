<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Login - UniVerse</title>
  <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/styles.css">
  <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/login.css">
</head>
<body>
  <!-- ✅ Custom navigation for login page -->
  <header class="header">
    <div class="container">
        <div class="nav-brand">
            <a href="<?= BASE_URL ?>">
                <img src="<?= BASE_URL ?>/assets/images/U.png" alt="UniVerse Logo" class="logo">
            </a>
        </div>
        
        <!-- Auth Navigation Actions -->
        <div class="auth-nav-actions">
            <!-- ✅ Always go to home page instead of history.back() -->
            <a href="<?= BASE_URL ?>" class="back-btn" title="Go to Home">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M19 12H5M12 19l-7-7 7-7"/>
                </svg>
                <span>Back</span>
            </a>
        </div>

        <button class="mobile-menu-btn" id="mobile-menu-btn">
            <span></span>
            <span></span>
            <span></span>
        </button>
    </div>
  </header>
  
  <!-- Login Form -->
  <main class="login-main">
    <div class="login-container">
      <h1>UniVerse</h1>
      
      <?php if (isset($success)): ?>
        <div class="alert alert-success">
          <?= htmlspecialchars($success) ?>
        </div>
      <?php endif; ?>
      
      <?php if (isset($error)): ?>
        <div class="alert alert-error">
          <?= htmlspecialchars($error) ?>
        </div>
      <?php endif; ?>
      
      <form class="login-form" method="POST" action="<?= BASE_URL ?>/login">
        <div class="form-group">
          <label for="username">Username</label>
          <input type="text" id="username" name="username" required />
        </div>
        <div class="form-group">
          <label for="password">Password</label>
          <input type="password" id="password" name="password" required />
        </div>
        <div class="login-links">
          <a href="<?= BASE_URL ?>/reset" style="color: var(--primary-purple);">Forgot Password?</a>
        </div>
        <button type="submit" class="login-btn btn btn-primary">Log In</button>
      </form>
      <p class="signup-text">
        Don't have an account? <a href="<?= BASE_URL ?>/registration" style="color: var(--primary-purple);">Sign Up</a>
      </p>
    </div>
  </main>

  <!-- Footer -->
  <?php include __DIR__ . '/../layout/footer.php'; ?>
</body>
</html>
