<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Login - UniVerse</title>
  <link rel="stylesheet" href="<?= BASE_URL ?>/css/styles.css">
  <link rel="stylesheet" href="<?= BASE_URL ?>/css/login.css">

</head>
<body>
  <?php include __DIR__ . '/nav_withback.php'; ?> 
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
          <a href="#" style="color: var(--primary-purple);">Forgot Password?</a>
        </div>
        <button type="submit" class="login-btn btn btn-primary">Log In</button>
      </form>
      <p class="signup-text">
        Don't have an account? <a href="registration" style="color: var(--primary-purple);">Sign Up</a>
      </p>
    </div>
  </main>


  <!-- Footer -->
  <?php include __DIR__ . '/../layout/footer.php'; ?>

</body>
</html>
