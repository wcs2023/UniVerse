<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?= BASE_URL ?>/css/styles.css">
    <link rel="icon" type="image/png" href="<?= BASE_URL ?>/assets/images/U.png">
    <title>UniVerse - <?= $data['title'] ?? 'Add Achievement' ?></title>
</head>
<body>
    <?php include 'Unavigation.view.php'; ?>

    <div class="profile-container">
        <!-- Page Header -->
        <div class="page-header">
            <h1><?= $data['title'] ?? 'Add New Achievement' ?></h1>
            <a href="<?= BASE_URL ?>/uachievements" class="back-link">← Back to Achievements</a>
        </div>

        <!-- Success/Error Messages -->
        <?php if (isset($_SESSION['achievement_error'])): ?>
            <div class="alert alert-error">
                <?= htmlspecialchars($_SESSION['achievement_error']) ?>
            </div>
            <?php unset($_SESSION['achievement_error']); ?>
        <?php endif; ?>

        <!-- Add/Edit Achievement Form -->
        <div class="form-container">
            <form method="POST" action="<?= BASE_URL ?>/uachievements" class="achievement-form">
                
                <!-- Hidden field for edit mode -->
                <?php if (isset($data['achievement']) && $data['achievement']): ?>
                    <input type="hidden" name="achievement_id" value="<?= $data['achievement']['achievement_id'] ?>">
                <?php endif; ?>

                <!-- Achievement Title -->
                <div class="form-group">
                    <label for="title">Achievement Title <span class="required">*</span></label>
                    <input 
                        type="text" 
                        id="title" 
                        name="title" 
                        placeholder="e.g., Dean's List - Fall 2023"
                        value="<?= isset($data['achievement']) ? htmlspecialchars($data['achievement']['title']) : '' ?>"
                        required
                        class="form-input"
                    >
                </div>

                <!-- Achievement Type -->
                <div class="form-group">
                    <label for="achievement_type">Achievement Type <span class="required">*</span></label>
                    <select id="achievement_type" name="achievement_type" required class="form-select">
                        <option value="">Select Type</option>
                        <option value="certificate" <?= (isset($data['achievement']) && $data['achievement']['achievement_type'] == 'certificate') ? 'selected' : '' ?>>Certificate</option>
                        <option value="award" <?= (isset($data['achievement']) && $data['achievement']['achievement_type'] == 'award') ? 'selected' : '' ?>>Award</option>
                        <option value="project" <?= (isset($data['achievement']) && $data['achievement']['achievement_type'] == 'project') ? 'selected' : '' ?>>Project</option>
                        <option value="activity" <?= (isset($data['achievement']) && $data['achievement']['achievement_type'] == 'activity') ? 'selected' : '' ?>>Activity</option>
                        <option value="leadership" <?= (isset($data['achievement']) && $data['achievement']['achievement_type'] == 'leadership') ? 'selected' : '' ?>>Leadership</option>
                        <option value="internship" <?= (isset($data['achievement']) && $data['achievement']['achievement_type'] == 'internship') ? 'selected' : '' ?>>Internship</option>
                        <option value="competition" <?= (isset($data['achievement']) && $data['achievement']['achievement_type'] == 'competition') ? 'selected' : '' ?>>Competition</option>
                        <option value="publication" <?= (isset($data['achievement']) && $data['achievement']['achievement_type'] == 'publication') ? 'selected' : '' ?>>Publication</option>
                        <option value="volunteer" <?= (isset($data['achievement']) && $data['achievement']['achievement_type'] == 'volunteer') ? 'selected' : '' ?>>Volunteer</option>
                    </select>
                </div>

                <!-- Date Achieved -->
                <div class="form-group">
                    <label for="date_achieved">Date Achieved <span class="required">*</span></label>
                    <input 
                        type="date" 
                        id="date_achieved" 
                        name="date_achieved" 
                        value="<?= isset($data['achievement']) ? htmlspecialchars($data['achievement']['date_achieved']) : '' ?>"
                        required
                        max="<?= date('Y-m-d') ?>"
                        class="form-input"
                    >
                </div>

                <!-- Institution/Organization (matches DB: issuing_organization) -->
                <div class="form-group">
                    <label for="issuing_organization">Institution/Organization</label>
                    <input 
                        type="text" 
                        id="issuing_organization" 
                        name="issuing_organization" 
                        placeholder="e.g., University of Colombo"
                        value="<?= isset($data['achievement']) ? htmlspecialchars($data['achievement']['institution'] ?? '') : '' ?>"
                        class="form-input"
                    >
                </div>

                <!-- Description -->
                <div class="form-group">
                    <label for="description">Description <span class="required">*</span></label>
                    <textarea 
                        id="description" 
                        name="description" 
                        rows="5" 
                        placeholder="Describe your achievement in detail..."
                        required
                        class="form-textarea"
                    ><?= isset($data['achievement']) ? htmlspecialchars($data['achievement']['description']) : '' ?></textarea>
                    <small class="form-hint">Provide details about what you accomplished and its significance.</small>
                </div>

                <!-- Certificate URL (matches DB: verification_url) -->
                <div class="form-group">
                    <label for="verification_url">Certificate/Proof URL</label>
                    <input 
                        type="url" 
                        id="verification_url" 
                        name="verification_url" 
                        placeholder="https://example.com/certificate"
                        value="<?= isset($data['achievement']) ? htmlspecialchars($data['achievement']['certificate_url'] ?? '') : '' ?>"
                        class="form-input"
                    >
                    <small class="form-hint">Optional: Add a link to your certificate, badge, or proof of achievement.</small>
                </div>

                <!-- Form Actions -->
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">
                        <?= isset($data['achievement']) ? 'Update Achievement' : 'Save Achievement' ?>
                    </button>
                    <a href="<?= BASE_URL ?>/uachievements" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>

    <?php include __DIR__ . '/../../layout/footer.php'; ?>
    
    <script>
        // Auto-scroll to top on page load
        window.onload = function() {
            window.scrollTo(0, 0);
        };

        // Form validation
        document.querySelector('.achievement-form').addEventListener('submit', function(e) {
            const title = document.getElementById('title').value.trim();
            const description = document.getElementById('description').value.trim();
            
            if (title.length < 3) {
                alert('Title must be at least 3 characters long');
                e.preventDefault();
                return false;
            }
            
            if (description.length < 10) {
                alert('Description must be at least 10 characters long');
                e.preventDefault();
                return false;
            }
        });
    </script>

    <style>
        .alert {
            padding: 15px;
            margin: 20px 0;
            border-radius: 8px;
            font-weight: 500;
        }

        .alert-error {
            background-color: #fee2e2;
            color: #991b1b;
            border-left: 4px solid #ef4444;
        }

        .required {
            color: #ef4444;
        }

        .form-hint {
            display: block;
            margin-top: 5px;
            color: #6b7280;
            font-size: 13px;
        }
    </style>
</body>
</html>
