<?php
// filepath: c:\xampp\htdocs\UniVerse\app\views\actors\alumni\Aforum_create_thread.view.php
include __DIR__ . '/Anavbar.php'; 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Start New Discussion - UniVerse</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/css/alumni.css">
    <style>
        body { background-color: #a78bfa45 !important; }
    </style>
</head>
<body>
<main class="main-container">
    <!-- Page Header -->
    <section class="page-header fade-in">
        <div class="header-content">
            <h1><i class="fa-solid fa-plus-circle"></i> Start New Discussion</h1>
            <p>Share your thoughts, ask questions, and engage with the community</p>
        </div>
    </section>

    <!-- Error Messages -->
    <?php if (isset($error)): ?>
        <div class="error-message fade-in">
            <div class="alert alert-error">
                <i class="fa-solid fa-exclamation-triangle"></i>
                <span><?= htmlspecialchars($error) ?></span>
            </div>
        </div>
    <?php endif; ?>

    <!-- Success Messages -->
    <?php if (isset($success)): ?>
        <div class="success-message fade-in">
            <div class="alert alert-success">
                <i class="fa-solid fa-check-circle"></i>
                <span><?= htmlspecialchars($success) ?></span>
            </div>
        </div>
    <?php endif; ?>

    <!-- Create Thread Form -->
    <section class="create-thread-section fade-in">
        <div class="form-container">
            
        <form method="POST" action="<?= BASE_URL ?>/adiscussion/create" class="create-thread-form" id="createThreadForm">
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
                    value="<?= htmlspecialchars($old['title'] ?? '') ?>"
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
                    <?php if (isset($categories) && is_array($categories) && count($categories) > 0): ?>
                        <?php foreach ($categories as $category): ?>
                            <option 
                                value="<?= htmlspecialchars($category['id'] ?? '') ?>"
                                <?= (isset($old['category_id']) && $old['category_id'] == ($category['id'] ?? '')) ? 'selected' : '' ?>
                            >
                                <?= htmlspecialchars($category['name'] ?? 'Unknown Category') ?>
                            </option>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <!-- Fallback if no categories loaded -->
                        <option value="">No categories available</option>
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
                ><?= htmlspecialchars($old['content'] ?? '') ?></textarea>
                <div class="form-help">
                    Provide detailed information about your topic (minimum 20 characters)
                    <span class="char-counter" id="contentCounter">0 characters</span>
                </div>
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
                    <li>Stay professional and share valuable insights from your experience</li>
                </ul>
            </div>

            <!-- Form Actions -->
            <div class="form-actions">
                <button type="button" class="btn btn-secondary" onclick="window.location.href='<?= BASE_URL ?>/adiscussion'">
                    <i class="fa-solid fa-arrow-left"></i>
                    Cancel
                </button>
                <button type="submit" class="btn btn-primary" id="submitBtn">
                    <i class="fa-solid fa-paper-plane"></i>
                    Create Discussion
                </button>
            </div>
        </form>
        </div>
    </section>
</main>
<?php include __DIR__ . '/../../layout/footer.php'; ?>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('createThreadForm');
    const titleInput = document.getElementById('title');
    const contentTextarea = document.getElementById('content');
    const categorySelect = document.getElementById('category_id');
    const titleCounter = document.getElementById('titleCounter');
    const contentCounter = document.getElementById('contentCounter');
    
    // Title character counter
    if (titleInput && titleCounter) {
        titleInput.addEventListener('input', function() {
            const length = this.value.length;
            titleCounter.textContent = `${length}/200 characters`;
            titleCounter.style.color = length > 180 ? 'var(--warning-color)' : 'var(--text-light)';
        });
        // Initial count
        titleInput.dispatchEvent(new Event('input'));
    }
    
    // Content character counter
    if (contentTextarea && contentCounter) {
        contentTextarea.addEventListener('input', function() {
            const length = this.value.length;
            contentCounter.textContent = `${length} characters`;
            contentCounter.style.color = length < 20 ? 'var(--error-color)' : 'var(--text-light)';
        });
        // Initial count
        contentTextarea.dispatchEvent(new Event('input'));
    }
    
    // Form validation
    if (form) {
        form.addEventListener('submit', function(e) {
            const title = titleInput.value.trim();
            const content = contentTextarea.value.trim();
            const category = categorySelect.value;
            
            let errors = [];
            
            if (title.length < 5) {
                errors.push('• Title must be at least 5 characters long');
            }
            
            if (title.length > 200) {
                errors.push('• Title must not exceed 200 characters');
            }
            
            if (content.length < 20) {
                errors.push('• Content must be at least 20 characters long');
            }
            
            if (!category) {
                errors.push('• Please select a category');
            }
            
            if (errors.length > 0) {
                e.preventDefault();
                alert('Please fix the following errors:\n\n' + errors.join('\n'));
                return false;
            }
        });
    }
    
    // Auto-save draft functionality
    function saveDraft() {
        if (titleInput && contentTextarea && categorySelect) {
            const draft = {
                title: titleInput.value,
                content: contentTextarea.value,
                category: categorySelect.value,
                timestamp: Date.now()
            };
            localStorage.setItem('alumni_forum_draft', JSON.stringify(draft));
        }
    }
    
    function loadDraft() {
        const savedDraft = localStorage.getItem('alumni_forum_draft');
        if (savedDraft) {
            try {
                const draft = JSON.parse(savedDraft);
                // Only load if less than 24 hours old and fields are empty
                if (Date.now() - draft.timestamp < 86400000 && !titleInput.value && !contentTextarea.value) {
                    if (confirm('A draft was found. Would you like to restore it?')) {
                        titleInput.value = draft.title || '';
                        contentTextarea.value = draft.content || '';
                        categorySelect.value = draft.category || '';
                        
                        // Update counters
                        titleInput.dispatchEvent(new Event('input'));
                        contentTextarea.dispatchEvent(new Event('input'));
                    }
                }
            } catch (e) {
                console.error('Error loading draft:', e);
            }
        }
    }
    
    // Auto-save on input (debounced)
    let saveTimeout;
    [titleInput, contentTextarea, categorySelect].forEach(element => {
        element?.addEventListener('input', function() {
            clearTimeout(saveTimeout);
            saveTimeout = setTimeout(saveDraft, 1000);
        });
    });
    
    // Clear draft on successful submission
    form?.addEventListener('submit', function() {
        localStorage.removeItem('alumni_forum_draft');
    });
    
    // Load draft on page load
    loadDraft();
});
</script>

</body>
</html>