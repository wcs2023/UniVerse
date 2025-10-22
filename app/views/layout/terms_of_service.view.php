<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Terms of Service - UniVerse</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/css/styles.css">
    <link rel="icon" type="image/x-icon" href="<?= BASE_URL ?>/assets/images/logo.png">
</head>
<body>
    <?php include __DIR__ . '/../auth/nav_withback.php'; ?>
    <!-- Terms of Service Content -->
    <div class="legal-page">
        <div class="container">
            <div class="legal-header">
                <h1>Terms of Service</h1>
                <p class="last-updated">Last updated: <?= date('F j, Y') ?></p>
            </div>

            <div class="legal-content">
                <section class="legal-section">
                    <h2>1. Acceptance of Terms</h2>
                    <div class="section-content">
                        <p>By accessing and using UniVerse, you accept and agree to be bound by the terms and provision of this agreement. If you do not agree to abide by the above, please do not use this service.</p>
                        <p>These Terms of Service govern your use of our platform located at the Service operated by UniVerse .</p>
                    </div>
                </section>

                <section class="legal-section">
                    <h2>2. User Responsibilities</h2>
                    <div class="section-content">
                        <h3>Account Security</h3>
                        <ul>
                            <li>Maintain the confidentiality of your login credentials</li>
                            <li>Notify us immediately of any unauthorized access</li>
                            <li>Use strong passwords</li>
                            <li>Take responsibility for all activities under your account</li>
                        </ul>

                        <h3>Prohibited Activities</h3>
                        <ul>
                            <li><strong>No Spamming:</strong> Sending unsolicited messages, advertisements, or promotional content</li>
                            <li><strong>No Fake Profiles:</strong> Creating accounts with false, misleading, or impersonated information</li>
                            <li><strong>No Harassment:</strong> Engaging in bullying, harassment, or discriminatory behavior</li>
                            <li><strong>No Illegal Content:</strong> Posting content that violates local or international laws</li>
                            <li><strong>No System Abuse:</strong> Attempting to hack, overload, or interfere with platform operations</li>
                            <li><strong>No Data Scraping:</strong> Automated extraction of user data or platform content</li>
                        </ul>

                        <h3>Content Standards</h3>
                        <ul>
                            <li>Ensure all posted content is accurate and truthful</li>
                            <li>Respect intellectual property rights of others</li>
                            <li>Maintain professional and respectful communication</li>
                            <li>Report inappropriate content or behavior promptly</li>
                        </ul>
                    </div>
                </section>

                <section class="legal-section">
                    <h2>3. Platform Rules by User Type</h2>
                    <div class="section-content">
                        <h3>For Students</h3>
                        <ul>
                            <li>Provide accurate academic and professional information</li>
                            <li>Engage respectfully with mentors and potential employers</li>
                            <li>Use the platform primarily for career development purposes</li>
                        </ul>

                        <h3>For Mentors</h3>
                        <ul>
                            <li>Provide genuine guidance and support to students</li>
                            <li>Maintain professional boundaries in all interactions</li>
                            <li>Verify your professional credentials and experience</li>
                            <li>Respond to student inquiries in a timely manner</li>
                        </ul>

                        <h3>For Companies</h3>
                        <ul>
                            <li>Post only legitimate job opportunities and internships</li>
                            <li>Provide accurate job descriptions and requirements</li>
                            <li>Comply with equal opportunity employment laws</li>
                            <li>Respect candidate privacy and data protection rights</li>
                            <li>Provide feedback to applicants when possible</li>
                        </ul>
                    </div>
                </section>

                <section class="legal-section">
                    <h2>4. Intellectual Property</h2>
                    <div class="section-content">
                        <h3>Platform Content</h3>
                        <ul>
                            <li>UniVerse owns all rights to the platform design, features, and proprietary content</li>
                            <li>Users may not copy, modify, or distribute platform code or design elements</li>
                        </ul>

                        <h3>User Content</h3>
                        <ul>
                            <li>Users retain ownership of content they post (profiles, portfolios, etc.)</li>
                            <li>By posting content, users grant UniVerse a license to display and use it for platform operations</li>
                            <li>Users are responsible for ensuring they have rights to all content they upload</li>
                            <li>UniVerse reserves the right to remove content that violates these terms</li>
                        </ul>
                    </div>
                </section>

                <section class="legal-section">
                    <h2>5. Privacy and Data Protection</h2>
                    <div class="section-content">
                        <p>Your privacy is important to us. Please review our <a href="<?= BASE_URL ?>/privacy-policy" class="legal-link">Privacy Policy</a>, which also governs your use of the Service, to understand our practices.</p>
                        
                        <h3>Data Usage</h3>
                        <ul>
                            <li>We collect and use data as described in our Privacy Policy</li>
                            <li>Users can request data deletion as outlined in our Privacy Policy</li>
                        </ul>
                    </div>
                </section>

                <section class="legal-section">
                    <h2>6. Liability Disclaimers</h2>
                    <div class="section-content">
                        <h3>User Interactions</h3>
                        <ul>
                            <li>UniVerse facilitates connections but is not responsible for user relationships</li>
                            <li>We do not guarantee job placements or mentorship outcomes</li>
                            <li>Users interact at their own risk and discretion</li>
                            <li>We are not liable for disputes between users</li>
                        </ul>
                    </div>
                </section>

                <section class="legal-section">
                    <h2>7. Termination and Suspension</h2>
                    <div class="section-content">
                        <h3>User-Initiated Termination</h3>
                        <ul>
                            <li>Account deletion removes access to all platform features</li>
                        </ul>

                </section>

                    <h2>8. Modifications to Terms</h2>
                    <div class="section-content">
                        <p>We reserve the right to modify these terms at any time. Changes will be effective immediately upon posting on the platform. Significant changes will be communicated via:</p>
                        <ul>
                            <li>Email notifications to registered users</li>
                            <li>In-platform announcements</li>
                        </ul>
                        <p>Continued use of the platform after changes constitutes acceptance of the modified terms.</p>
                    </div>
                </section>

            </div>
        </div>
    </div>

    <!-- Include Footer -->
    <?php include __DIR__ . '/footer.php'; ?>
</body>
</html>
