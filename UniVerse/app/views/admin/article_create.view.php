<?php
// filepath: c:\xampp\htdocs\UniVerse\app\views\actors\admin\article_create.view.php
if (!defined('URLROOT')) {
    define('URLROOT', BASE_URL);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Article - Admin Panel</title>
    <link rel="stylesheet" href="<?= URLROOT ?>/css/admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- TinyMCE for rich text editing -->
    <script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
</head>
<body>
    <div class="admin-layout">
        <!-- Sidebar -->
        <?php include __DIR__ . '/components/sidebar.php'; ?>
        
        <!-- Main Content -->
        <div class="admin-main">
            <!-- Header -->
            <div class="admin-header">
                <h1>✍️ Create New Article</h1>
                <div class="admin-header-actions">
                    <a href="<?= URLROOT ?>/admin/articles" class="btn btn-outline">
                        <i class="fas fa-arrow-left"></i> Back to Articles
                    </a>
                </div>
            </div>
            
            <!-- Error Messages -->
            <?php if (!empty($errors)): ?>
                <div class="alert alert-error">
                    <strong>❌ Please fix the following errors:</strong>
                    <ul style="margin: 0.5rem 0 0 1.5rem;">
                        <?php foreach ($errors as $error): ?>
                            <li><?= htmlspecialchars($error) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>
            
            <!-- Create Article Form -->
            <div class="content-card">
                <div class="content-card-header">
                    <h2 class="content-card-title">Article Information</h2>
                </div>
                <div class="content-card-body">
                    <form method="POST" action="<?= URLROOT ?>/admin/createArticle" class="article-form">
                        <div class="form-grid">
                            <!-- Title -->
                            <div class="form-group full-width">
                                <label for="title" class="form-label">
                                    Article Title <span class="required">*</span>
                                </label>
                                <input 
                                    type="text" 
                                    id="title" 
                                    name="title" 
                                    class="form-input" 
                                    placeholder="Enter article title..."
                                    value="<?= htmlspecialchars($articleData['title'] ?? '') ?>"
                                    required
                                >
                            </div>
                            
                            <!-- Category and Status Row -->
                            <div class="form-group">
                                <label for="category" class="form-label">Category</label>
                                <select id="category" name="category" class="form-select">
                                    <option value="">Select Category</option>
                                    <option value="Technology" <?= (isset($articleData['category']) && $articleData['category'] === 'Technology') ? 'selected' : '' ?>>Technology</option>
                                    <option value="Career" <?= (isset($articleData['category']) && $articleData['category'] === 'Career') ? 'selected' : '' ?>>Career</option>
                                    <option value="Education" <?= (isset($articleData['category']) && $articleData['category'] === 'Education') ? 'selected' : '' ?>>Education</option>
                                    <option value="Alumni Stories" <?= (isset($articleData['category']) && $articleData['category'] === 'Alumni Stories') ? 'selected' : '' ?>>Alumni Stories</option>
                                    <option value="News" <?= (isset($articleData['category']) && $articleData['category'] === 'News') ? 'selected' : '' ?>>News</option>
                                    <option value="Events" <?= (isset($articleData['category']) && $articleData['category'] === 'Events') ? 'selected' : '' ?>>Events</option>
                                    <option value="Other" <?= (isset($articleData['category']) && $articleData['category'] === 'Other') ? 'selected' : '' ?>>Other</option>
                                </select>
                            </div>
                            
                            <div class="form-group">
                                <label for="status" class="form-label">
                                    Status <span class="required">*</span>
                                </label>
                                <select id="status" name="status" class="form-select" required>
                                    <option value="draft" <?= (!isset($articleData['status']) || $articleData['status'] === 'draft') ? 'selected' : '' ?>>Draft</option>
                                    <option value="published" <?= (isset($articleData['status']) && $articleData['status'] === 'published') ? 'selected' : '' ?>>Published</option>
                                    <option value="archived" <?= (isset($articleData['status']) && $articleData['status'] === 'archived') ? 'selected' : '' ?>>Archived</option>
                                </select>
                            </div>
                            
                            <!-- Tags -->
                            <div class="form-group full-width">
                                <label for="tags" class="form-label">Tags</label>
                                <input 
                                    type="text" 
                                    id="tags" 
                                    name="tags" 
                                    class="form-input" 
                                    placeholder="e.g., technology, career, education (comma separated)"
                                    value="<?= htmlspecialchars($articleData['tags'] ?? '') ?>"
                                >
                                <small class="form-hint">Separate multiple tags with commas</small>
                            </div>
                            
                            <!-- Content -->
                            <div class="form-group full-width">
                                <label for="content" class="form-label">
                                    Article Content <span class="required">*</span>
                                </label>
                                <textarea 
                                    id="content" 
                                    name="content" 
                                    class="form-textarea" 
                                    rows="15"
                                    required
                                ><?= htmlspecialchars($articleData['content'] ?? '') ?></textarea>
                            </div>
                        </div>
                        
                        <!-- Form Actions -->
                        <div class="form-actions">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-save"></i> Create Article
                            </button>
                            <button type="button" onclick="saveDraft()" class="btn btn-outline btn-lg">
                                <i class="fas fa-file"></i> Save as Draft
                            </button>
                            <a href="<?= URLROOT ?>/admin/articles" class="btn btn-outline btn-lg">
                                <i class="fas fa-times"></i> Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        // Initialize TinyMCE
        tinymce.init({
            selector: '#content',
            height: 400,
            menubar: false,
            plugins: [
                'advlist', 'autolink', 'lists', 'link', 'image', 'charmap', 'preview',
                'anchor', 'searchreplace', 'visualblocks', 'code', 'fullscreen',
                'insertdatetime', 'media', 'table', 'help', 'wordcount'
            ],
            toolbar: 'undo redo | blocks | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | removeformat | help',
            content_style: 'body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif; font-size: 16px; line-height: 1.6; }',
            branding: false
        });
        
        // Save as draft
        function saveDraft() {
            document.getElementById('status').value = 'draft';
            document.querySelector('.article-form').submit();
        }
        
        // Warn before leaving with unsaved changes
        let formChanged = false;
        document.querySelector('.article-form').addEventListener('input', function() {
            formChanged = true;
        });
        
        window.addEventListener('beforeunload', function(e) {
            if (formChanged) {
                e.preventDefault();
                e.returnValue = '';
            }
        });
        
        document.querySelector('.article-form').addEventListener('submit', function() {
            formChanged = false;
        });
    </script>
    
    <style>
        .article-form {
            max-width: 100%;
        }
        
        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1.5rem;
            margin-bottom: 2rem;
        }
        
        .form-group {
            display: flex;
            flex-direction: column;
        }
        
        .full-width {
            grid-column: 1 / -1;
        }
        
        .form-label {
            font-weight: 600;
            margin-bottom: 0.5rem;
            color: #374151;
            font-size: 0.95rem;
        }
        
        .required {
            color: #dc2626;
        }
        
        .form-input,
        .form-select,
        .form-textarea {
            padding: 0.75rem;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            font-size: 1rem;
            transition: border-color 0.2s;
        }
        
        .form-input:focus,
        .form-select:focus,
        .form-textarea:focus {
            outline: none;
            border-color: #6b46c1;
            box-shadow: 0 0 0 3px rgba(107, 70, 193, 0.1);
        }
        
        .form-textarea {
            resize: vertical;
            min-height: 150px;
            font-family: inherit;
        }
        
        .form-hint {
            font-size: 0.85rem;
            color: #6b7280;
            margin-top: 0.25rem;
        }
        
        .form-actions {
            display: flex;
            gap: 1rem;
            padding-top: 1.5rem;
            border-top: 1px solid #e5e7eb;
        }
        
        .btn-lg {
            padding: 0.875rem 1.5rem;
            font-size: 1rem;
        }
        
        @media (max-width: 768px) {
            .form-grid {
                grid-template-columns: 1fr;
            }
            
            .form-actions {
                flex-direction: column;
            }
        }
    </style>
</body>
</html>