<?php
// filepath: c:\xampp\htdocs\UniVerse\app\views\actors\undergraduate\Uforum_create_thread.view.php
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title ?? 'Create New Discussion') ?></title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/forum_create.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/css/styles.css">
</head>
<body>

<?php include __DIR__ . '/Unavigation.view.php'; ?>

<main class="main-container">
    <!-- Breadcrumb Navigation -->
    <nav class="breadcrumb">
        <a href="<?= BASE_URL ?>/udiscussion"><i class="fa-solid fa-home"></i> Forums</a>
        <span class="separator"><i class="fa-solid fa-chevron-right"></i></span>
        <span class="current">Start New Discussion</span>
    </nav>

    <!-- Create Thread Form -->
    <div class="create-thread-container">
        <div class="form-header">
            <h1><i class="fa-solid fa-plus-circle"></i> Start New Discussion</h1>
            <p class="form-description">
                Share your thoughts, ask questions, and engage with the community
            </p>
        </div>

        <?php if (isset($error)): ?>
            <div class="alert alert-error">
                <i class="fa-solid fa-exclamation-triangle"></i>
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <?php if (isset($_GET['success'])): ?>
            <div class="alert alert-success">
                <i class="fa-solid fa-check-circle"></i>
                Discussion created successfully!
            </div>
        <?php endif; ?>

        <form method="POST" action="<?= BASE_URL ?>/udiscussion/create" class="create-thread-form" id="createThreadForm">
            <!-- Discussion Title -->
            <div class="form-group">
                <label for="title" class="form-label">
                    <i class="fa-solid fa-heading"></i>
                    Discussion Title <span class="required">*</span>
                </label>
                <input 
                    type="text" 
                    id="title" 
                    name="title" 
                    class="form-input" 
                    placeholder="Enter a descriptive title for your discussion..."
                    maxlength="200"
                    required
                    value="<?= htmlspecialchars($_POST['title'] ?? '') ?>"
                >
                <div class="form-help">
                    Choose a clear, descriptive title (5-200 characters)
                    <span class="char-counter" id="titleCounter">0/200 characters</span>
                </div>
            </div>

            <!-- Category Selection -->
            <div class="form-group">
                <label for="category_id" class="form-label">
                    <i class="fa-solid fa-folder"></i>
                    Category <span class="required">*</span>
                </label>
                <select id="category_id" name="category_id" class="form-select" required>
                    <option value="">Choose a category...</option>
                    <?php if (isset($categories) && is_array($categories)): ?>
                        <?php foreach ($categories as $category): ?>
                            <option 
                                value="<?= $category['id'] ?? '' ?>"
                                <?= (($_POST['category_id'] ?? '') == ($category['id'] ?? '')) ? 'selected' : '' ?>
                            >
                                <?= htmlspecialchars($category['name'] ?? 'Unknown Category') ?>
                            </option>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <option value="1">University Selection</option>
                        <option value="2">Career Planning</option>
                        <option value="3">Study Tips & Advice</option>
                        <option value="4">Scholarships & Financial Aid</option>
                        <option value="5">General Discussion</option>
                    <?php endif; ?>
                </select>
                <div class="form-help">
                    Select the most appropriate category for your discussion
                </div>
            </div>

            <!-- Discussion Content -->
            <div class="form-group">
                <label for="content" class="form-label">
                    <i class="fa-solid fa-edit"></i>
                    Discussion Content <span class="required">*</span>
                </label>
                <textarea 
                    id="content" 
                    name="content" 
                    class="form-textarea" 
                    placeholder="Write the main content of your discussion here. Be detailed and clear about what you want to discuss..."
                    rows="12"
                    minlength="20"
                    required
                ><?= htmlspecialchars($_POST['content'] ?? '') ?></textarea>
                <div class="form-help">
                    Provide detailed information about your topic (minimum 20 characters)
                    <span class="char-counter" id="contentCounter">0 characters</span>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="form-actions">
                <button type="button" class="btn btn-secondary" onclick="window.history.back()">
                    <i class="fa-solid fa-arrow-left"></i>
                    Cancel
                </button>
                <button type="submit" class="btn btn-primary" id="submitBtn">
                    <i class="fa-solid fa-paper-plane"></i>
                    Create Discussion
                </button>
            </div>

            <!-- Form Guidelines -->
            <div class="form-guidelines">
                <h3><i class="fa-solid fa-info-circle"></i> Discussion Guidelines</h3>
                <ul>
                    <li>Be respectful and courteous to all community members</li>
                    <li>Use clear, descriptive titles that reflect your topic</li>
                    <li>Provide enough context for others to understand and respond</li>
                    <li>Choose the most appropriate category for better visibility</li>
                    <li>Search existing discussions before creating a new one</li>
                </ul>
            </div>
        </form>
    </div>
</main>
<?php include __DIR__ . '/../../layout/footer.php'; ?>

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
    --warning-color: #f59e0b;
    --error-color: #ef4444;
    --shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    --shadow-hover: 0 8px 25px rgba(0, 0, 0, 0.15);
}

/* Body and Layout */
body {
    margin: 0;
    padding: 0;
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
    background-color: var(--light-gray);
    padding-top: 90px !important;
}

.main-container {
    max-width: 800px;
    margin: 0 auto;
    padding: 2rem;
    margin-top: 20px;
}

/* Breadcrumb Navigation */
.breadcrumb {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    margin-bottom: 2rem;
    font-size: 0.9rem;
}

.breadcrumb a {
    color: var(--primary-purple);
    text-decoration: none;
    transition: color 0.3s ease;
}

.breadcrumb a:hover {
    color: var(--dark-purple);
}

.breadcrumb .separator {
    color: var(--text-light);
}

.breadcrumb .current {
    color: var(--text-dark);
    font-weight: 500;
}

/* Create Thread Container */
.create-thread-container {
    background: var(--white);
    border-radius: 12px;
    padding: 2rem;
    box-shadow: var(--shadow);
    margin-top: 30px;
}

/* Form Header */
.form-header {
    text-align: center;
    margin-bottom: 2rem;
    padding-bottom: 1.5rem;
    border-bottom: 2px solid var(--light-gray);
}

.form-header h1 {
    font-size: 2rem;
    color: var(--text-dark);
    margin-bottom: 0.5rem;
}

.form-header h1 i {
    color: var(--primary-purple);
    margin-right: 0.5rem;
}

.form-description {
    color: var(--text-light);
    font-size: 1.1rem;
    margin: 0;
}

/* Alerts */
.alert {
    padding: 1rem;
    border-radius: 8px;
    margin-bottom: 1.5rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.alert-error {
    background-color: #fef2f2;
    border: 1px solid #fecaca;
    color: #991b1b;
}

.alert-success {
    background-color: #f0fdf4;
    border: 1px solid #bbf7d0;
    color: #166534;
}

/* Form Styles */
.create-thread-form {
    max-width: 100%;
}

.form-group {
    margin-bottom: 2rem;
}

.form-label {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-weight: 600;
    color: var(--text-dark);
    margin-bottom: 0.5rem;
    font-size: 1rem;
}

.form-label i {
    color: var(--primary-purple);
}

.required {
    color: var(--error-color);
}

.form-input,
.form-select,
.form-textarea {
    width: 100%;
    padding: 0.75rem 1rem;
    border: 2px solid var(--border-color);
    border-radius: 8px;
    font-size: 1rem;
    font-family: inherit;
    transition: all 0.3s ease;
    background-color: var(--white);
    box-sizing: border-box;
}

.form-input:focus,
.form-select:focus,
.form-textarea:focus {
    outline: none;
    border-color: var(--primary-purple);
    box-shadow: 0 0 0 3px rgba(107, 70, 193, 0.1);
}

.form-textarea {
    resize: vertical;
    min-height: 150px;
    line-height: 1.5;
}

.form-help {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-top: 0.5rem;
    font-size: 0.85rem;
    color: var(--text-light);
}

.char-counter {
    font-weight: 500;
    color: var(--primary-purple);
}

/* Form Actions */
.form-actions {
    display: flex;
    gap: 1rem;
    justify-content: flex-end;
    margin-top: 2rem;
    padding-top: 1.5rem;
    border-top: 2px solid var(--light-gray);
}

.btn {
    padding: 0.75rem 1.5rem;
    border: none;
    border-radius: 8px;
    font-size: 1rem;
    font-weight: 500;
    cursor: pointer;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    transition: all 0.3s ease;
    min-width: 140px;
    justify-content: center;
}

.btn-primary {
    background: var(--primary-purple);
    color: var(--white);
}

.btn-primary:hover:not(:disabled) {
    background: var(--dark-purple);
    transform: translateY(-2px);
    box-shadow: var(--shadow-hover);
}

.btn-secondary {
    background: transparent;
    color: var(--text-light);
    border: 2px solid var(--border-color);
}

.btn-secondary:hover {
    background: var(--light-gray);
    border-color: var(--text-light);
}

.btn:disabled {
    opacity: 0.6;
    cursor: not-allowed;
    transform: none;
}

/* Form Guidelines */
.form-guidelines {
    margin-top: 2rem;
    padding: 1.5rem;
    background: var(--light-gray);
    border-radius: 8px;
}

.form-guidelines h3 {
    color: var(--text-dark);
    margin-bottom: 1rem;
    font-size: 1.1rem;
}

.form-guidelines h3 i {
    color: var(--primary-purple);
    margin-right: 0.5rem;
}

.form-guidelines ul {
    margin: 0;
    padding-left: 1.5rem;
    color: var(--text-light);
}

.form-guidelines li {
    margin-bottom: 0.5rem;
    line-height: 1.4;
}

/* Responsive Design */
@media (max-width: 768px) {
    body {
        padding-top: 80px !important;
    }
    
    .main-container {
        padding: 1rem;
        margin-top: 10px;
    }
    
    .create-thread-container {
        padding: 1.5rem;
        margin-top: 20px;
    }
    
    .form-header h1 {
        font-size: 1.5rem;
    }
    
    .form-actions {
        flex-direction: column;
    }
    
    .breadcrumb {
        font-size: 0.8rem;
    }
    
    .form-help {
        flex-direction: column;
        align-items: flex-start;
        gap: 0.25rem;
    }
}

/* Animation */
.create-thread-container {
    animation: fadeInUp 0.6s ease-out;
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
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const titleInput = document.getElementById('title');
    const contentTextarea = document.getElementById('content');
    const titleCounter = document.getElementById('titleCounter');
    const contentCounter = document.getElementById('contentCounter');
    const submitBtn = document.getElementById('submitBtn');
    const form = document.getElementById('createThreadForm');

    // Character counters
    function updateTitleCounter() {
        const length = titleInput.value.length;
        titleCounter.textContent = `${length}/200 characters`;
        
        if (length > 180) {
            titleCounter.style.color = 'var(--warning-color)';
        } else if (length > 200) {
            titleCounter.style.color = 'var(--error-color)';
        } else {
            titleCounter.style.color = 'var(--primary-purple)';
        }
    }

    function updateContentCounter() {
        const length = contentTextarea.value.length;
        contentCounter.textContent = `${length} characters`;
        
        if (length < 20) {
            contentCounter.style.color = 'var(--error-color)';
        } else {
            contentCounter.style.color = 'var(--primary-purple)';
        }
    }

    // Event listeners
    titleInput.addEventListener('input', updateTitleCounter);
    contentTextarea.addEventListener('input', updateContentCounter);

    // Form validation
    function validateForm() {
        const title = titleInput.value.trim();
        const content = contentTextarea.value.trim();
        const category = document.getElementById('category_id').value;

        const isValid = title.length >= 5 && 
                       title.length <= 200 && 
                       content.length >= 20 && 
                       category !== '';

        submitBtn.disabled = !isValid;
        return isValid;
    }

    // Real-time validation
    [titleInput, contentTextarea, document.getElementById('category_id')].forEach(element => {
        element.addEventListener('input', validateForm);
        element.addEventListener('change', validateForm);
    });

    // Form submission
    form.addEventListener('submit', function(e) {
        if (!validateForm()) {
            e.preventDefault();
            alert('Please fill in all required fields correctly.');
            return;
        }

        // Show loading state
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Creating...';
    });

    // Auto-resize textarea
    function autoResize(textarea) {
        textarea.style.height = 'auto';
        textarea.style.height = textarea.scrollHeight + 'px';
    }

    contentTextarea.addEventListener('input', function() {
        autoResize(this);
    });

    // Initial setup
    updateTitleCounter();
    updateContentCounter();
    validateForm();
});
</script>

</body>
</html>