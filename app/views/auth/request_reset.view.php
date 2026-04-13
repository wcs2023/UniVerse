<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Your Password</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/styles.css">
    <style>
        /* Reusing your CSS variables */
        :root {
            --primary-purple: #6b46c1;
            --light-gray: #f9fafb;
        }
        body {
            font-family: "Segoe UI", sans-serif;
            background-color: #a78bfa45;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            padding-top: 10rem;
        }
        .containerRS {
            margin: 0 auto;
            padding: 3rem;
            background-color: var(--light-gray);
            border-radius: 13px;
            width: 500px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.1);
        }
        .form-group { margin: 2rem 0; }
        .btn-primary { 
            background: var(--primary-purple); 
            color: white; 
            padding: 10px 20px; 
            border: none; 
            border-radius: 5px; 
            cursor: pointer;
            width: 100%;
        }
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
            <button onclick="window.location.href='<?= BASE_URL ?>/login'" class="back-btn" title="Go back">
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


    <div class="containerRS">
        <form action="<?= BASE_URL ?>/reset/generateOTP" method="POST">
            <h2>Reset Password</h2>
            <p>Enter your email address and we'll send you an OTP code to reset your account.</p>
            
            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" id="email" name="email" class="form-row" required style="width: 100%; padding: 10px; margin-top: 5px;">
            </div>
            <button type="submit" class="btn-primary">Send OTP Code</button>
        </form>
    </div>
</body>
</html>