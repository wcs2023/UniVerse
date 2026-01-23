<?php
// filepath: c:\xampp\htdocs\UniVerse\app\views\actors\alumni\Aforum_create_thread.view.php
include __DIR__ . '/Anavbar.php'; 
?>
<html>
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
</html>

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

/* Page Header */
.page-header {
    background: var(--white);
    border-radius: 12px;
    padding: 2rem;
    margin-bottom: 2rem;
    text-align: center;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
}

.header-content h1 {
    font-size: 2rem;
    color: var(--text-dark);
    margin-bottom: 0.5rem;
}

.header-content h1 i {
    color: var(--primary-purple);
    margin-right: 0.5rem;
}

.header-content p {
    color: var(--text-light);
    font-size: 1.1rem;
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
.create-thread-section {
    background: var(--white);
    border-radius: 12px;
    padding: 2rem;
    margin-bottom: 2rem;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
}

.form-container {
    max-width: 800px;
    margin: 0 auto;
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
    font-size: 0.95rem;
}

.form-label i {
    color: var(--primary-purple);
    margin-right: 0.5rem;
    width: 16px;
}

.required {
    color: var(--error-color);
}

.form-input, .form-select, .form-textarea {
    width: 100%;
    padding: 0.875rem 1rem;
    border: 2px solid var(--border-color);
    border-radius: 8px;
    font-size: 1rem;
    transition: all 0.3s ease;
    background: var(--white);
    color: var(--text-dark);
    font-family: inherit;
}

.form-input:focus, .form-select:focus, .form-textarea:focus {
    outline: none;
    border-color: var(--primary-purple);
    box-shadow: 0 0 0 3px rgba(107, 70, 193, 0.1);
}

.form-textarea {
    resize: vertical;
    min-height: 120px;
    font-family: inherit;
    line-height: 1.6;
}

.form-help {
    display: flex;
    justify-content: space-between;
    align-items: center;
    color: var(--text-light);
    font-size: 0.85rem;
    margin-top: 0.5rem;
}

.char-counter {
    font-weight: 500;
}

/* Guidelines */
.form-guidelines {
    background: var(--light-gray);
    border-radius: 8px;
    padding: 1.5rem;
    margin: 1.5rem 0;
    border-left: 4px solid var(--primary-purple);
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
    list-style: none;
    padding: 0;
    margin: 0;
}

.form-guidelines li {
    color: var(--text-light);
    padding: 0.4rem 0;
    position: relative;
    padding-left: 1.5rem;
    font-size: 0.9rem;
    line-height: 1.5;
}

.form-guidelines li::before {
    content: "✓";
    color: var(--primary-purple);
    font-weight: bold;
    position: absolute;
    left: 0;
}

/* Form Actions */
.form-actions {
    display: flex;
    gap: 1rem;
    justify-content: center;
    margin-top: 2rem;
}

.btn {
    padding: 0.875rem 2rem;
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

.btn-secondary {
    background: transparent;
    color: var(--text-light);
    border: 2px solid var(--border-color);
}

.btn-secondary:hover {
    border-color: var(--primary-purple);
    color: var(--primary-purple);
    transform: translateY(-2px);
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
    .page-header {
        padding: 1.5rem;
    }
    
    .header-content h1 {
        font-size: 1.75rem;
    }
    
    .create-thread-section {
        padding: 1.5rem;
    }
    
    .form-actions {
        flex-direction: column;
    }
    
    .btn {
        justify-content: center;
        width: 100%;
    }
}

@media (max-width: 480px) {
    .header-content h1 {
        font-size: 1.5rem;
    }
    
    .create-thread-section {
        padding: 1rem;
    }
    
    .form-help {
        flex-direction: column;
        align-items: flex-start;
        gap: 0.25rem;
    }
}
</style>

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