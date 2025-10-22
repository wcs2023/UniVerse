<?php
    $title = "Degree Recommendation System";
    $userType = $data['user_type'] ?? 'student';
    include_once __DIR__ . '/includes/header2.view.php';
?>

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

            <form method="POST" action="<?= BASE_URL ?>/schoolleaver/processDegreeRequest" class="degree-form">
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
                        min="-3" 
                        max="3"
                        placeholder="Enter your Z-score (e.g., 1.5)" 
                        required 
                        value="<?= isset($data['old']['zscore']) ? htmlspecialchars($data['old']['zscore']) : '' ?>"
                        class="form-input"
                    >
                    <small class="form-hint">Enter your Z-score from A/L results (typically between -3 and 3)</small>
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
                <div class="form-group">
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
                </div>

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

<!-- Footer -->
<footer style="margin-top: 4rem; padding: 2rem; text-align: center; background: rgba(255, 255, 255, 0.9); border-radius: 15px; max-width: 1200px; margin-left: auto; margin-right: auto;">
    <p style="color: #6b7280; margin: 0;">&copy; 2024 UniVerse. All rights reserved.</p>
</footer>

<style>
/* CSS Variables */
:root {
    --primary-purple: #6b46c1;
    --secondary-purple: #8b5cf6;
    --light-purple: #a78bfa;
    --dark-purple: #553c9a;
    --text-dark: #1f2937;
    --text-light: #6b7280;
    --white: #ffffff;
    --light-gray: #f9fafb;
    --border-color: #e5e7eb;
    --success-color: #10b981;
    --error-color: #ef4444;
    --warning-color: #f59e0b;
}

/* Hero Section */
.degree-hero {
    background: linear-gradient(135deg, var(--primary-purple), var(--secondary-purple));
    border-radius: 12px;
    padding: 3rem 2rem;
    margin-bottom: 2rem;
    text-align: center;
    color: white;
    box-shadow: 0 10px 30px rgba(107, 70, 193, 0.3);
}

.hero-content h1 {
    font-size: 2.5rem;
    margin-bottom: 1rem;
    font-weight: 700;
}

.hero-content h1 i {
    margin-right: 0.5rem;
}

.hero-content p {
    font-size: 1.1rem;
    line-height: 1.6;
    max-width: 800px;
    margin: 0 auto;
    opacity: 0.9;
}

/* Alert Messages */
.error-message, .success-message {
    margin-bottom: 2rem;
}

.alert {
    padding: 1rem 1.5rem;
    border-radius: 8px;
    display: flex;
    align-items: center;
    gap: 0.75rem;
    font-weight: 500;
}

.alert-error {
    background: rgba(239, 68, 68, 0.1);
    color: var(--error-color);
    border: 1px solid rgba(239, 68, 68, 0.2);
}

.alert-success {
    background: rgba(16, 185, 129, 0.1);
    color: var(--success-color);
    border: 1px solid rgba(16, 185, 129, 0.2);
}

/* Form Section */
.suggestion-form-section {
    background: var(--white);
    border-radius: 12px;
    padding: 2rem;
    margin-bottom: 2rem;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
}

.form-container {
    max-width: 600px;
    margin: 0 auto;
}

.form-header {
    text-align: center;
    margin-bottom: 2rem;
}

.form-header h2 {
    color: var(--text-dark);
    font-size: 1.8rem;
    margin-bottom: 0.5rem;
}

.form-header h2 i {
    color: var(--primary-purple);
    margin-right: 0.5rem;
}

.form-header p {
    color: var(--text-light);
    font-size: 1rem;
}

/* Form Groups */
.form-group {
    margin-bottom: 1.5rem;
}

.form-label {
    display: block;
    color: var(--text-dark);
    font-weight: 600;
    margin-bottom: 0.5rem;
    font-size: 0.9rem;
}

.form-label i {
    color: var(--primary-purple);
    margin-right: 0.5rem;
    width: 16px;
}

.form-input, .form-select, .form-textarea {
    width: 100%;
    padding: 0.75rem 1rem;
    border: 2px solid var(--border-color);
    border-radius: 8px;
    font-size: 1rem;
    transition: all 0.3s ease;
    background: var(--white);
    color: var(--text-dark);
}

.form-input:focus, .form-select:focus, .form-textarea:focus {
    outline: none;
    border-color: var(--primary-purple);
    box-shadow: 0 0 0 3px rgba(107, 70, 193, 0.1);
}

.form-textarea {
    resize: vertical;
    min-height: 100px;
}

.form-hint {
    display: block;
    color: var(--text-light);
    font-size: 0.85rem;
    margin-top: 0.25rem;
}

/* Form Actions */
.form-actions {
    text-align: center;
    margin-top: 2rem;
}

.btn {
    padding: 0.75rem 2rem;
    border: none;
    border-radius: 8px;
    font-size: 1rem;
    font-weight: 600;
    cursor: pointer;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    transition: all 0.3s ease;
}

.btn-primary {
    background: var(--primary-purple);
    color: var(--white);
}

.btn-primary:hover {
    background: var(--dark-purple);
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(107, 70, 193, 0.3);
}

.btn-large {
    padding: 1rem 2.5rem;
    font-size: 1.1rem;
}

/* Info Cards */
.info-cards-section {
    margin-bottom: 2rem;
}

.info-cards {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 1.5rem;
}

.info-card {
    background: var(--white);
    border-radius: 12px;
    padding: 2rem 1.5rem;
    text-align: center;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    transition: all 0.3s ease;
}

.info-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
}

.card-icon {
    width: 60px;
    height: 60px;
    background: linear-gradient(135deg, var(--primary-purple), var(--secondary-purple));
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 1rem auto;
    color: white;
    font-size: 1.5rem;
}

.info-card h3 {
    color: var(--text-dark);
    font-size: 1.2rem;
    margin-bottom: 0.5rem;
}

.info-card p {
    color: var(--text-light);
    line-height: 1.5;
    font-size: 0.9rem;
}

/* Animations */
.fade-in {
    animation: fadeInUp 0.8s ease-out;
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Responsive Design */
@media (max-width: 768px) {
    .degree-hero {
        padding: 2rem 1rem;
    }
    
    .hero-content h1 {
        font-size: 2rem;
    }
    
    .suggestion-form-section {
        padding: 1.5rem;
    }
    
    .form-header h2 {
        font-size: 1.5rem;
    }
    
    .info-cards {
        grid-template-columns: 1fr;
    }
    
    .btn-large {
        padding: 0.875rem 2rem;
        font-size: 1rem;
    }
}

@media (max-width: 480px) {
    .hero-content h1 {
        font-size: 1.75rem;
    }
    
    .hero-content p {
        font-size: 1rem;
    }
    
    .suggestion-form-section {
        padding: 1rem;
    }
}
</style>

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