<?php
// filepath: c:\xampp\htdocs\UniVerse\app\views\actors\students\forum_create_thread.view.php
    $pageTitle = $title ?? 'Start New Discussion';
    include_once __DIR__ . '/includes/header2.view.php';
?>

<main class="main-container">
    <!-- Breadcrumb -->
    <section class="breadcrumb-section fade-in">
        <div class="breadcrumb">
            <a href="<?= BASE_URL ?>/sdiscussion">Forums</a>
            <span class="separator">/</span>
            <span>Start New Discussion</span>
        </div>
    </section>

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
            <form method="POST" action="<?= BASE_URL ?>/sdiscussion/create" class="thread-form" id="createThreadForm">
                
                <!-- Thread Title -->
                <div class="form-group">
                    <label for="title" class="form-label">
                        <i class="fa-solid fa-heading"></i> Discussion Title <span class="required">*</span>
                    </label>
                    <input 
                        type="text" 
                        id="title" 
                        name="title" 
                        placeholder="Enter a descriptive title for your discussion..."
                        required 
                        maxlength="200"
                        value="<?= isset($old['title']) ? htmlspecialchars($old['title']) : '' ?>"
                        class="form-input"
                    >
                    <small class="form-hint">Choose a clear, descriptive title (5-200 characters)</small>
                    <div class="char-counter" id="titleCounter">0/200 characters</div>
                </div>

                <!-- Category Selection -->
                <div class="form-group">
                    <label for="category_id" class="form-label">
                        <i class="fa-solid fa-folder"></i> Category <span class="required">*</span>
                    </label>
                    <select name="category_id" id="category_id" required class="form-select">
                        <option value="" disabled <?= empty($old['category_id']) ? 'selected' : '' ?>>
                            Choose a category...
                        </option>
                        <?php if (isset($categories) && is_array($categories) && count($categories) > 0): ?>
                            <?php foreach($categories as $category): ?>
                                <?php 
                                $isSelected = isset($old['category_id']) && $old['category_id'] == ($category['id'] ?? '');
                                ?>
                                <option 
                                    value="<?= htmlspecialchars($category['id'] ?? '') ?>" 
                                    <?= $isSelected ? 'selected' : '' ?>
                                    data-icon="<?= htmlspecialchars($category['icon'] ?? 'fa-folder') ?>"
                                >
                                    <?= htmlspecialchars($category['name'] ?? 'Unknown Category') ?>
                                </option>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <option value="" disabled>No categories available</option>
                        <?php endif; ?>
                    </select>
                    <small class="form-hint">Select the most appropriate category for your discussion</small>
                    
                    <!-- Category Description Preview -->
                    <div id="categoryDescription" class="category-description"></div>
                </div>

                <!-- Thread Content -->
                <div class="form-group">
                    <label for="content" class="form-label">
                        <i class="fa-solid fa-edit"></i> Discussion Content <span class="required">*</span>
                    </label>
                    <textarea 
                        id="content" 
                        name="content" 
                        placeholder="Write the main content of your discussion here. Be detailed and clear about what you want to discuss..."
                        required
                        minlength="10"
                        class="form-textarea"
                        rows="8"
                    ><?= isset($old['content']) ? htmlspecialchars($old['content']) : '' ?></textarea>
                    <small class="form-hint">Provide detailed information to get better responses (minimum 10 characters)</small>
                    <div class="char-counter" id="contentCounter">0 characters</div>
                </div>

                <!-- Posting Guidelines -->
                <div class="guidelines-section">
                    <h3><i class="fa-solid fa-info-circle"></i> Posting Guidelines</h3>
                    <ul class="guidelines-list">
                        <li>Be respectful and constructive in your discussions</li>
                        <li>Use clear, descriptive titles that summarize your topic</li>
                        <li>Provide enough context for others to understand and help</li>
                        <li>Search existing discussions before creating a new one</li>
                        <li>Stay on topic and avoid spam or promotional content</li>
                    </ul>
                </div>

                <!-- Form Actions -->
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary btn-large" id="submitBtn">
                        <i class="fa-solid fa-paper-plane"></i> Create Discussion
                    </button>
                    <a href="<?= BASE_URL ?>/sdiscussion" class="btn btn-outline">
                        <i class="fa-solid fa-times"></i> Cancel
                    </a>
                </div>
            </form>
        </div>
    </section>
</main>

<!-- Footer -->
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
    --error-color: #ef4444;
    --warning-color: #f59e0b;
}

/* Breadcrumb */
.breadcrumb-section {
    margin-bottom: 1rem;
}

.breadcrumb {
    font-size: 0.9rem;
    color: var(--text-light);
}

.breadcrumb a {
    color: var(--primary-purple);
    text-decoration: none;
}

.breadcrumb a:hover {
    text-decoration: underline;
}

.separator {
    margin: 0 0.5rem;
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
    font-weight: bold;
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

.form-hint {
    display: block;
    color: var(--text-light);
    font-size: 0.85rem;
    margin-top: 0.25rem;
}

.char-counter {
    font-size: 0.8rem;
    color: var(--text-light);
    text-align: right;
    margin-top: 0.25rem;
    font-weight: 500;
}

/* Category Description */
.category-description {
    background: var(--light-gray);
    border-left: 3px solid var(--primary-purple);
    padding: 0.75rem 1rem;
    border-radius: 6px;
    margin-top: 0.5rem;
    font-size: 0.9rem;
    color: var(--text-light);
    display: none;
}

.category-description.show {
    display: block;
    animation: slideDown 0.3s ease-out;
}

@keyframes slideDown {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Guidelines Section */
.guidelines-section {
    background: var(--light-gray);
    border-radius: 8px;
    padding: 1.5rem;
    margin-bottom: 2rem;
    border-left: 4px solid var(--primary-purple);
}

.guidelines-section h3 {
    color: var(--text-dark);
    margin-bottom: 1rem;
    font-size: 1.1rem;
}

.guidelines-section h3 i {
    color: var(--primary-purple);
    margin-right: 0.5rem;
}

.guidelines-list {
    list-style: none;
    padding: 0;
    margin: 0;
}

.guidelines-list li {
    color: var(--text-light);
    padding: 0.4rem 0;
    position: relative;
    padding-left: 1.5rem;
    font-size: 0.9rem;
    line-height: 1.5;
}

.guidelines-list li::before {
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

.btn-primary:hover:not(:disabled) {
    background: var(--dark-purple);
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(107, 70, 193, 0.3);
}

.btn-primary:disabled {
    opacity: 0.6;
    cursor: not-allowed;
}

.btn-outline {
    background: transparent;
    color: var(--text-light);
    border: 2px solid var(--border-color);
}

.btn-outline:hover {
    border-color: var(--primary-purple);
    color: var(--primary-purple);
    transform: translateY(-2px);
}

.btn-large {
    padding: 1rem 2.5rem;
    font-size: 1.1rem;
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
        align-items: stretch;
    }
    
    .btn {
        justify-content: center;
    }
    
    .guidelines-section {
        padding: 1rem;
    }
}

@media (max-width: 480px) {
    .header-content h1 {
        font-size: 1.5rem;
    }
    
    .create-thread-section {
        padding: 1rem;
    }
    
    .btn-large {
        padding: 0.875rem 2rem;
        font-size: 1rem;
    }
}
</style>

<script>
// Store category data from PHP
const categoryData = <?php echo json_encode($categories ?? []); ?>;

document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('createThreadForm');
    const titleInput = document.getElementById('title');
    const contentTextarea = document.getElementById('content');
    const categorySelect = document.getElementById('category_id');
    const titleCounter = document.getElementById('titleCounter');
    const contentCounter = document.getElementById('contentCounter');
    const categoryDescription = document.getElementById('categoryDescription');
    const submitBtn = document.getElementById('submitBtn');
    
    // Title character counter
    if (titleInput && titleCounter) {
        titleInput.addEventListener('input', function() {
            const length = this.value.length;
            titleCounter.textContent = `${length}/200 characters`;
            
            if (length > 180) {
                titleCounter.style.color = 'var(--warning-color)';
            } else if (length > 0 && length < 5) {
                titleCounter.style.color = 'var(--error-color)';
            } else {
                titleCounter.style.color = 'var(--text-light)';
            }
        });
        // Initial count
        titleInput.dispatchEvent(new Event('input'));
    }
    
    // Content character counter
    if (contentTextarea && contentCounter) {
        contentTextarea.addEventListener('input', function() {
            const length = this.value.length;
            contentCounter.textContent = `${length} characters`;
            
            if (length > 0 && length < 10) {
                contentCounter.style.color = 'var(--error-color)';
            } else if (length >= 10) {
                contentCounter.style.color = 'var(--success-color)';
            } else {
                contentCounter.style.color = 'var(--text-light)';
            }
        });
        // Initial count
        contentTextarea.dispatchEvent(new Event('input'));
    }
    
    // Category description display
    if (categorySelect && categoryDescription) {
        categorySelect.addEventListener('change', function() {
            const selectedId = this.value;
            if (selectedId && categoryData.length > 0) {
                const category = categoryData.find(cat => cat.id == selectedId);
                if (category && category.description) {
                    categoryDescription.innerHTML = `
                        <i class="fa-solid ${category.icon || 'fa-folder'}"></i>
                        <strong>${category.name}:</strong> ${category.description}
                    `;
                    categoryDescription.classList.add('show');
                } else {
                    categoryDescription.classList.remove('show');
                }
            } else {
                categoryDescription.classList.remove('show');
            }
        });
        
        // Trigger on page load if category is already selected
        categorySelect.dispatchEvent(new Event('change'));
    }
    
    // Form validation and submission
    if (form) {
        form.addEventListener('submit', function(e) {
            const title = titleInput.value.trim();
            const content = contentTextarea.value.trim();
            const category = categorySelect.value;
            
            let errors = [];
            
            if (title.length < 5) {
                errors.push('Title must be at least 5 characters long');
            }
            
            if (content.length < 10) {
                errors.push('Content must be at least 10 characters long');
            }
            
            if (!category) {
                errors.push('Please select a category');
            }
            
            if (errors.length > 0) {
                e.preventDefault();
                alert('Please fix the following errors:\n\n' + errors.join('\n'));
                return false;
            }
        });
    }
    
    // Auto-save to localStorage (optional enhancement)
    function autoSave() {
        if (titleInput && contentTextarea && categorySelect) {
            const formData = {
                title: titleInput.value,
                content: contentTextarea.value,
                category: categorySelect.value
            };
            localStorage.setItem('draft_thread', JSON.stringify(formData));
        }
    }
    
    // Restore from localStorage
    function restoreDraft() {
        const draft = localStorage.getItem('draft_thread');
        if (draft && !titleInput.value && !contentTextarea.value) {
            try {
                const formData = JSON.parse(draft);
                if (formData.title) titleInput.value = formData.title;
                if (formData.content) contentTextarea.value = formData.content;
                if (formData.category) categorySelect.value = formData.category;
                
                // Trigger input events to update counters
                titleInput.dispatchEvent(new Event('input'));
                contentTextarea.dispatchEvent(new Event('input'));
            } catch (e) {
                console.error('Error restoring draft:', e);
            }
        }
    }
    
    // Clear draft on successful submission
    form?.addEventListener('submit', function() {
        localStorage.removeItem('draft_thread');
    });
    
    // Auto-save on input
    [titleInput, contentTextarea, categorySelect].forEach(element => {
        element?.addEventListener('input', autoSave);
    });
    
    // Restore draft on load
    restoreDraft();
});
</script>

</body>
</html>