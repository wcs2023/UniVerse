<?php
    $title = "Degree Recommendation System";
    $userType = $data['user_type'] ?? 'student';
    include_once __DIR__ . '/includes/header2.view.php';
?>
<head>
    <link rel="stylesheet" href="<?=BASE_URL?>/assets/css/degree_suggestion_styles.css">
</head>

<main class="main-container">
    <!-- Hero Section -->
    <section class="degree-hero fade-in">    
        <div class="hero-content">
            <h1><i class="fa-solid fa-graduation-cap"></i> Discover Your Ideal Degree Path</h1>
            <p>Our advanced recommendation system analyzes your Z-score and individual interests to provide personalized degree suggestions. Explore suitable universities and career pathways tailored to your strengths, aspirations, and long-term goals.</p>
        </div>
    </section>

    <!-- Error Messages -->
    <?php if (isset($data['error'])): ?>
        <div class="error-message fade-in">
            <div class="alert alert-error">
                <i class="fa-solid fa-exclamation-triangle"></i>
                <span><?= htmlspecialchars($data['error']) ?></span>
            </div>
        </div>
    <?php endif; ?>

    <!-- Success Messages -->
    <?php if (isset($data['success'])): ?>
        <div class="success-message fade-in">
            <div class="alert alert-success">
                <i class="fa-solid fa-check-circle"></i>
                <span><?= htmlspecialchars($data['success']) ?></span>
            </div>
        </div>
    <?php endif; ?>

    <!-- Degree Suggestion Form -->
    <section class="suggestion-form-section fade-in">
        <div class="form-container">
            <div class="form-header">
                <h2><i class="fa-solid fa-search"></i> Get Personalized Suggestions</h2>
                <p>Fill in your details below to receive degree recommendations tailored to your profile.</p>
            </div>

            <form method="POST" action="<?= BASE_URL ?>/Degrees/show_result" class="degree-form">
                <!-- Z-Score Input -->
                <div class="form-group">
                    <label for="zscore" class="form-label">
                        <i class="fa-solid fa-chart-line"></i> Z-Score
                    </label>
                    <input 
                        type="number" 
                        id="zscore" 
                        name="zscore" 
                        step="0.01"
                        min="0" 
                        max="3"
                        placeholder="Enter your Z-score (e.g., 1.5)" 
                        required 
                        value="<?= isset($data['old']['zscore']) ? htmlspecialchars($data['old']['zscore']) : '' ?>"
                        class="form-input"
                    >
                    <small class="form-hint">Enter your Z-score from A/L results (typically between 0 and 3)</small>
                </div>

                <!-- Stream Selection -->
                <div class="form-group">
                    <label for="stream" class="form-label">
                        <i class="fa-solid fa-book"></i> Stream
                    </label>
                    <select name="stream" id="stream" required class="form-select">
                        <option value="" disabled <?= empty($data['old']['stream']) ? 'selected' : '' ?>>
                            Choose your A/L stream...
                        </option>
                        <?php 
                        $streams = [
                            'maths' => 'Mathematics',
                            'bio' => 'Bio Science', 
                            'arts' => 'Arts',
                            'commerce' => 'Commerce',
                            'tech' => 'Technology'
                        ];
                        foreach($streams as $value => $label): ?>
                            <option value="<?= $value ?>" <?= (isset($data['old']['stream']) && $data['old']['stream'] === $value) ? 'selected' : '' ?>>
                                <?= $label ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- District Selection -->
                <div class="form-group">
                    <label for="district" class="form-label">
                        <i class="fa-solid fa-map-marker-alt"></i> Preferred District
                    </label>
                    <select name="district" id="district" required class="form-select">
                        <option value="" disabled <?= empty($data['old']['district']) ? 'selected' : '' ?>>
                            Select your preferred district...
                        </option>
                        <?php 
                        $districts = [
                            'Colombo', 'Galle', 'Matara', 'Gampaha', 'Kaluthara', 'Hambanthota', 
                            'Rathnapura', 'Badulla', 'Kandy', 'Mathale', 'Nuwara Eliya', 'Jaffna', 
                            'Kilinochchi', 'Mannar', 'Vavuniya', 'Mulathivu', 'Batticaloa', 'Ampara', 
                            'Trincomalee', 'Kurunegala', 'Puttalama', 'Anuradhapura', 'Polonnaruwa', 
                            'Monaragala', 'Kegalle'
                        ];
                        foreach($districts as $district): ?>
                            <option value="<?= strtolower($district) ?>" <?= (isset($data['old']['district']) && $data['old']['district'] === strtolower($district)) ? 'selected' : '' ?>>
                                <?= $district ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Interests (Optional) -->
                <!-- <div class="form-group">
                    <label for="interests" class="form-label">
                        <i class="fa-solid fa-heart"></i> Areas of Interest (Optional)
                    </label>
                    <textarea 
                        id="interests" 
                        name="interests" 
                        placeholder="Tell us about your interests, hobbies, or career aspirations..."
                        class="form-textarea"
                        rows="4"
                    ><?= isset($data['old']['interests']) ? htmlspecialchars($data['old']['interests']) : '' ?></textarea>
                    <small class="form-hint">This helps us provide more personalized recommendations</small>
                </div> -->

                <!-- Submit Button -->
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary btn-large">
                        <i class="fa-solid fa-magic"></i> Get Suggestions
                    </button>
                </div>
            </form>
        </div>
    </section>

    <!-- Info Cards -->
    <section class="info-cards-section">
        <div class="info-cards">
            <div class="info-card fade-in">
                <div class="card-icon">
                    <i class="fa-solid fa-university"></i>
                </div>
                <h3>University Matching</h3>
                <p>Find universities that match your Z-score and stream requirements</p>
            </div>
            
            <div class="info-card fade-in">
                <div class="card-icon">
                    <i class="fa-solid fa-briefcase"></i>
                </div>
                <h3>Career Pathways</h3>
                <p>Explore career opportunities related to your chosen field of study</p>
            </div>
            
            <div class="info-card fade-in">
                <div class="card-icon">
                    <i class="fa-solid fa-chart-bar"></i>
                </div>
                <h3>Success Predictions</h3>
                <p>Get insights into admission chances and success rates</p>
            </div>
        </div>
    </section>
</main>


<?php include __DIR__ . '/../../layout/footer.php'; ?>

>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Add staggered animation to info cards
    const infoCards = document.querySelectorAll('.info-card');
    infoCards.forEach((card, index) => {
        card.style.animationDelay = `${index * 0.1}s`;
    });

    // Form validation
    const form = document.querySelector('.degree-form');
    const zscoreInput = document.getElementById('zscore');
    
    if (form && zscoreInput) {
        form.addEventListener('submit', function(e) {
            const zscore = parseFloat(zscoreInput.value);
            
            if (isNaN(zscore) || zscore < -3 || zscore > 3) {
                e.preventDefault();
                alert('Please enter a valid Z-score between -3 and 3');
                zscoreInput.focus();
                return false;
            }
        });
        
        // Real-time Z-score validation
        zscoreInput.addEventListener('input', function() {
            const value = parseFloat(this.value);
            
            if (!isNaN(value) && (value < -3 || value > 3)) {
                this.style.borderColor = 'var(--error-color)';
            } else {
                this.style.borderColor = 'var(--border-color)';
            }
        });
    }
});
</script>

</body>
</html>