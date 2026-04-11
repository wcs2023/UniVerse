<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify OTP</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/css/styles.css">
    <style>
        /* Reuse same styles as above */
        body { font-family: "Segoe UI", sans-serif; background-color: #a78bfa45; padding-top: 10rem; }
        .containerVO { margin: 0 auto; padding: 3rem; background-color: #fff; border-radius: 13px; width: 500px; }
        .otp-input { letter-spacing: 0.5rem; text-align: center; font-size: 1.5rem; font-weight: bold; }
        .btn-primary { background: #6b46c1; color: white; padding: 10px 20px; border: none; width: 100%; border-radius: 5px; }
        .resend-link { display: block; text-align: center; margin-top: 1rem; color: #6b7280; text-decoration: none; font-size: 0.9rem; }
    </style>
</head>
<body>
    <header class="header">
    <div class="container">
        <div class="nav-brand">
            <a href="<?= BASE_URL ?>">
                <img src="<?= BASE_URL ?>/assets/images/U.png" alt="UniVerse Logo" class="logo">
            </a>
        </div>
        
        <!-- Auth Navigation Actions -->
        <div class="auth-nav-actions">
            <button onclick="window.location.href='<?= BASE_URL ?>/reset'" class="back-btn" title="Go back">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M19 12H5M12 19l-7-7 7-7"/>
                </svg>
                <span>Back</span>
            </button>
        </div>
        
        <button class="mobile-menu-btn" id="mobile-menu-btn">
            <span></span>
            <span></span>
            <span></span>
        </button>
    </div>
    </header>


    <div class="containerVO">
        <form action="<?= BASE_URL ?>/reset/verifyOTP" method="POST">
            <h2>Check your email</h2>
            <p>We've sent a 6-digit code to <strong><?= htmlspecialchars($_SESSION['reset_email'] ?? 'your email') ?></strong>.</p>
            
            <div class="form-group">
                <label for="otp">Enter OTP Code</label>
                <input type="text" id="otp" name="otp" class="form-row otp-input" required maxlength="6" autofocus style="width: 100%; padding: 10px; margin-top: 5px;">
            </div>
            <button type="submit" class="btn-primary">Verify & Proceed</button>
            
            <a href="<?= BASE_URL ?>/reset" class="resend-link">Didn't get a code? Try again.</a>
        </form>
    </div>
</body>
</html>