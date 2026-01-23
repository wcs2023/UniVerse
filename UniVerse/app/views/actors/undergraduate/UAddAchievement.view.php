<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?= BASE_URL ?>/css/styles.css">
    <link rel="icon" type="image/png" href="<?= BASE_URL ?>/assets/images/U.png">
    <title>UniVerse - Add Achievement</title>
</head>
<body>
    <?php include 'Unavigation.view.php'; ?>

    <div class="profile-container">
        <!-- Page Header -->
        <div class="page-header">
            <h1>Add New Achievement</h1>
            <a href="<?= BASE_URL ?>/uachievements" class="back-link">← Back to Achievements</a>
        </div>

        <!-- Add Achievement Form -->
        <div class="form-container">
            <form method="POST" action="<?= BASE_URL ?>/uachievements/add" class="achievement-form">
                
                <!-- Achievement Title -->
                <div class="form-group">
                    <label for="title">Achievement Title <span class="required">*</span></label>
                    <input 
                        type="text" 
                        id="title" 
                        name="title" 
                        placeholder="e.g., Dean's List - Fall 2023"
                        required
                        class="form-input"
                    >
                </div>

                <!-- Achievement Type -->
                <div class="form-group">
                    <label for="achievement_type">Achievement Type <span class="required">*</span></label>
                    <select id="achievement_type" name="achievement_type" required class="form-select">
                        <option value="">Select Type</option>
                        <?php foreach($data['types'] as $key => $value): ?>
                            <option value="<?= $key ?>"><?= $value ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Date Achieved -->
                <div class="form-group">
                    <label for="date_achieved">Date Achieved <span class="required">*</span></label>
                    <input 
                        type="date" 
                        id="date_achieved" 
                        name="date_achieved" 
                        required
                        max="<?= date('Y-m-d') ?>"
                        class="form-input"
                    >
                </div>

                <!-- Institution -->
                <div class="form-group">
                    <label for="institution">Institution/Organization</label>
                    <input 
                        type="text" 
                        id="institution" 
                        name="institution" 
                        placeholder="e.g., University of Colombo"
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
                    ></textarea>
                    <small class="form-hint">Provide details about what you accomplished and its significance.</small>
                </div>

                <!-- Certificate URL -->
                <div class="form-group">
                    <label for="certificate_url">Certificate/Proof URL</label>
                    <input 
                        type="url" 
                        id="certificate_url" 
                        name="certificate_url" 
                        placeholder="https://example.com/certificate"
                        class="form-input"
                    >
                    <small class="form-hint">Optional: Add a link to your certificate, badge, or proof of achievement.</small>
                </div>

                <!-- Form Actions -->
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">Save Achievement</button>
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
</body>
</html>
