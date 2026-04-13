<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Privacy Policy - UniVerse</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/styles.css">
    <link rel="icon" type="image/x-icon" href="<?= BASE_URL ?>/assets/images/U.png">
</head>
<body>
    <?php include __DIR__ . '/../auth/nav_withback.php'; ?>
    <!-- Privacy Policy Content -->
    <div class="legal-page">
        <div class="container">
            <div class="legal-header">
                <h1>Privacy Policy</h1>
                <p class="last-updated">Last updated: <?= date('F j, Y') ?></p>
            </div>

            <div class="legal-content">
                <section class="legal-section">
                    <h2>1. Data We Collect</h2>
                    <div class="section-content">
                        <h3>Personal Information</h3>
                        <ul>
                            <li><strong>Account Information:</strong> Name, email address, phone number, university/college details</li>
                            <li><strong>Profile Data:</strong> Academic qualifications, skills, achievements, career interests</li>
                            <!-- <li><strong>Communication Data:</strong> Messages, feedback, support inquiries</li> -->
                            <li><strong>Professional Information:</strong> Resume/CV, work experience, portfolio items</li>
                        </ul>

                    </div>
                </section>

                <section class="legal-section">
                    <h2>2. How We Use Your Data</h2>
                    <div class="section-content">
                        <h3>Service Provision</h3>
                        <ul>
                            <li>Create and maintain your account</li>
                            <!-- <li>Match students with mentors and job opportunities</li> -->
                            <!-- <li>Facilitate communication between users</li> -->
                            <li>Provide personalized degree suggestions</li>
                        </ul>

                        <h3>Communication</h3>
                        <ul>
                            <li>Respond to support inquiries and feedback</li>
                            <li>Share relevant career opportunities and resources</li>
                        </ul>

                        
                    </div>
                </section>

               
                

                

                <section class="legal-section">
                    <h2>3. Policy Updates</h2>
                    <div class="section-content">
                        <p>We may update this privacy policy periodically. Significant changes will be communicated via email or platform notifications. Continued use of UniVerse after updates constitutes acceptance of the revised policy.</p>
                    </div>
                </section>
            </div>
        </div>
    </div>

    <!-- Include Footer -->
    <?php include __DIR__ . '/footer.php'; ?>
</body>
</html>
