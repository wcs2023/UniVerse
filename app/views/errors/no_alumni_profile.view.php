<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Complete Your Profile - UniVerse</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --primary-purple: #7c3aed;
            --purple-hover: #6d28d9;
            --text-dark: #1f2937;
            --text-light: #6b7280;
            --bg-light: #f9fafb;
            --border-color: #e5e7eb;
            --warning: #f59e0b;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            background-color: var(--bg-light);
            color: var(--text-dark);
            line-height: 1.6;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
        }

        .error-container {
            max-width: 600px;
            background: white;
            border-radius: 16px;
            padding: 3rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .error-icon {
            font-size: 4rem;
            margin-bottom: 1.5rem;
        }

        .error-title {
            font-size: 1.75rem;
            font-weight: 700;
            color: var(--text-dark);
            margin-bottom: 1rem;
        }

        .error-message {
            font-size: 1.125rem;
            color: var(--text-light);
            margin-bottom: 2rem;
            line-height: 1.7;
        }

        .info-box {
            background: #fef3c7;
            border: 1px solid #fbbf24;
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 2rem;
            text-align: left;
        }

        .info-title {
            font-weight: 600;
            color: #92400e;
            margin-bottom: 0.75rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .info-list {
            list-style: none;
            padding-left: 0;
        }

        .info-list li {
            padding: 0.5rem 0;
            color: #78350f;
            display: flex;
            align-items: start;
            gap: 0.5rem;
        }

        .info-list li:before {
            content: "✓";
            color: #059669;
            font-weight: bold;
            flex-shrink: 0;
        }

        .debug-info {
            background: var(--bg-light);
            border-radius: 8px;
            padding: 1rem;
            margin-bottom: 2rem;
            text-align: left;
            font-size: 0.9rem;
        }

        .debug-info strong {
            color: var(--text-dark);
        }

        .debug-info span {
            color: var(--text-light);
        }

        .button-group {
            display: flex;
            gap: 1rem;
            justify-content: center;
            flex-wrap: wrap;
        }

        .btn {
            padding: 0.875rem 2rem;
            border-radius: 8px;
            font-weight: 600;
            font-size: 1rem;
            cursor: pointer;
            transition: all 0.3s;
            border: none;
            text-decoration: none;
            display: inline-block;
        }

        .btn-primary {
            background: var(--primary-purple);
            color: white;
        }

        .btn-primary:hover {
            background: var(--purple-hover);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(124, 58, 237, 0.3);
        }

        .btn-secondary {
            background: white;
            color: var(--text-dark);
            border: 2px solid var(--border-color);
        }

        .btn-secondary:hover {
            background: var(--bg-light);
            border-color: var(--text-dark);
        }

        @media (max-width: 640px) {
            .error-container {
                padding: 2rem;
            }

            .error-title {
                font-size: 1.5rem;
            }

            .button-group {
                flex-direction: column;
            }

            .btn {
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <div class="error-container">
        <div class="error-icon">👤</div>
        <h1 class="error-title">Alumni Profile Required</h1>
        <p class="error-message">
            To access the mentorship dashboard, you need to have an alumni profile in our system.
        </p>

        <div class="info-box">
            <div class="info-title">
                <span>ℹ️</span>
                <span>What you need to do:</span>
            </div>
            <ul class="info-list">
                <li>Your alumni profile needs to be created in the database</li>
                <li>Contact the administrator to set up your profile</li>
                <li>Or check if you're logged in with the correct account</li>
            </ul>
        </div>

        <?php if (isset($data['user_id'])): ?>
        <div class="debug-info">
            <div><strong>Your Account Info:</strong></div>
            <div><strong>User ID:</strong> <span><?= htmlspecialchars($data['user_id']) ?></span></div>
            <div><strong>Email:</strong> <span><?= htmlspecialchars($data['user_email']) ?></span></div>
            <div><strong>Name:</strong> <span><?= htmlspecialchars($data['user_name']) ?></span></div>
            <div style="margin-top: 0.5rem; color: #dc2626;">
                <strong>Issue:</strong> <span>No alumni record found in database for this user_id</span>
            </div>
        </div>
        <?php endif; ?>

        <div class="button-group">
            <a href="<?= BASE_URL ?>/alumni" class="btn btn-primary">
                Go to Home
            </a>
            <a href="<?= BASE_URL ?>/alumni/profile" class="btn btn-secondary">
                View My Profile
            </a>
        </div>

        <div style="margin-top: 2rem; padding-top: 2rem; border-top: 1px solid var(--border-color);">
            <p style="font-size: 0.9rem; color: var(--text-light);">
                <strong>For Administrators:</strong><br>
                This user needs an entry in the <code style="background: var(--bg-light); padding: 0.2rem 0.5rem; border-radius: 4px;">Alumni</code> table.<br>
                User ID: <code style="background: var(--bg-light); padding: 0.2rem 0.5rem; border-radius: 4px;"><?= htmlspecialchars($data['user_id'] ?? 'N/A') ?></code>
            </p>
        </div>
    </div>
</body>
</html>
