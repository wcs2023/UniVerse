<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us - UniVerse</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/css/styles.css">
    <link rel="icon" type="image/x-icon" href="<?= BASE_URL ?>/assets/images/U.png">
</head>
<body>
    <?php include __DIR__ . '/../auth/nav_withback.php'; ?>
    <!-- Contact Content -->
    <div class="legal-page">
        <div class="container">
            <div class="legal-header">
                <h1>Contact Us</h1>
                <p class="subtitle">We're here to help bridge your future</p>
            </div>

            <div class="contact-wrapper">
                <!-- Contact Form Section -->
                <section class="contact-form-section">
                    <div class="form-container">
                        <h2>Send us a Message</h2>

                        <?php if (isset($_SESSION['success'])): ?>
                            <div class="alert alert-success" style="padding: 15px; margin-bottom: 20px; background: #d1fae5; border: 2px solid #10b981; border-radius: 10px; color: #065f46;">
                                <?= $_SESSION['success']; unset($_SESSION['success']); ?>
                            </div>
                        <?php endif; ?>

                        <?php if (isset($_SESSION['error'])): ?>
                            <div class="alert alert-error" style="padding: 15px; margin-bottom: 20px; background: #fee2e2; border: 2px solid #ef4444; border-radius: 10px; color: #991b1b;">
                                <?= $_SESSION['error']; unset($_SESSION['error']); ?>
                            </div>
                        <?php endif; ?>

                        <form class="contact-form" action="<?= BASE_URL ?>/contact" method="POST">
                            <div class="form-group">
                                <label for="name">Full Name <span class="required">*</span></label>
                                <input type="text" id="name" name="name" value="<?= htmlspecialchars($name ?? '') ?>" required>
                            </div>

                            <div class="form-group">
                                <label for="email">Email Address <span class="required">*</span></label>
                                <input type="email" id="email" name="email" value="<?= htmlspecialchars($email ?? '') ?>" required>
                            </div>

                            <div class="form-group">
                                <label for="subject">Subject <span class="required">*</span></label>
                                <select id="subject" name="subject" required>
                                    <option value="">Select a subject</option>
                                    <option value="general" <?= (($subject ?? '') === 'general') ? 'selected' : '' ?>>General Inquiry</option>
                                    <option value="technical" <?= (($subject ?? '') === 'technical') ? 'selected' : '' ?>>Technical Support</option>
                                    <option value="partnerships" <?= (($subject ?? '') === 'partnerships') ? 'selected' : '' ?>>Partnership Opportunities</option>
                                    <option value="feedback" <?= (($subject ?? '') === 'feedback') ? 'selected' : '' ?>>Feedback & Suggestions</option>
                                    <option value="account" <?= (($subject ?? '') === 'account') ? 'selected' : '' ?>>Account Issues</option>
                                    <option value="other" <?= (($subject ?? '') === 'other') ? 'selected' : '' ?>>Other</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="message">Message <span class="required">*</span></label>
                                <textarea id="message" name="message" rows="6" placeholder="Tell us how we can help you..." required><?= htmlspecialchars($message ?? '') ?></textarea>
                            </div>

                            <button type="submit" class="btn btn-primary submit-btn">
                                <span>Send Message</span>
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M2 21L23 12L2 3V10L17 12L2 14V21Z" fill="currentColor"/>
                                </svg>
                            </button>
                        </form>
                    </div>
                </section>

                <!-- FAQ Section -->
                <section class="faq-section">
                    <h2>Frequently Asked Questions</h2>
                    <div class="faq-grid">
                        <div class="faq-item">
                            <h3>How do I create an account?</h3>
                            <p>Click on "Get Started" in the navigation menu and follow the registration process. You'll need to provide basic information and verify your email address.</p>
                        </div>
                        <!-- <div class="faq-item">
                            <h3>Is UniVerse free to use?</h3>
                            <p>Yes, our basic platform is free for students. We offer premium features for enhanced career support and additional resources.</p>
                        </div> -->
                        <div class="faq-item">
                            <h3>How do I connect with mentors?</h3>
                            <p>Once registered, browse our mentor directory, filter by industry or expertise, and send connection requests to mentors who align with your career goals.</p>
                        </div>
                        <div class="faq-item">
                            <h3>Can companies post job opportunities?</h3>
                            <p>Yes, verified companies can post internships, graduate trainee positions, and full-time job opportunities. Contact us for business partnerships.</p>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>

    <!-- Include Footer -->
    <?php include __DIR__ . '/footer.php'; ?>
</body>
</html>
