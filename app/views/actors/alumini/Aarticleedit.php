<?php
// Define constants if not already defined (for direct access)
if (!defined('APPROOT')) {
    define('APPROOT', dirname(dirname(dirname(dirname(__FILE__)))));
}
if (!defined('URLROOT')) {
    define('URLROOT', 'http://localhost/UniVerse/public');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Article - UniVerse</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --primary-purple: #7c3aed;
            --purple-hover: #6d28d9;
            --text-dark: #1f2937;
            --text-light: #6b7280;
            --bg-light: #f3f4f6;
            --border-color: #e5e7eb;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            background-color: var(--bg-light);
            color: var(--text-dark);
            line-height: 1.6;
        }

        .container {
            max-width: 900px;
            margin: 2rem auto;
            padding: 2rem;
        }

        .form-card {
            background: white;
            border-radius: 12px;
            padding: 2.5rem;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .form-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
            padding-bottom: 1.5rem;
            border-bottom: 2px solid var(--bg-light);
        }

        .form-title {
            font-size: 2rem;
            font-weight: 700;
            color: var(--text-dark);
        }

        .status-badge {
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-size: 0.875rem;
            font-weight: 600;
        }

        .status-draft {
            background: #fef3c7;
            color: #92400e;
        }

        .status-published {
            background: #d1fae5;
            color: #065f46;
        }

        .btn-back {
            color: var(--text-light);
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 1rem;
            border-radius: 6px;
            transition: all 0.3s;
        }

        .btn-back:hover {
            background: var(--bg-light);
            color: var(--primary-purple);
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 600;
            color: var(--text-dark);
            font-size: 0.95rem;
        }

        .required {
            color: #ef4444;
        }

        .form-input,
        .form-select,
        .form-textarea {
            width: 100%;
            padding: 0.875rem;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            font-size: 1rem;
            font-family: inherit;
            transition: border-color 0.3s, box-shadow 0.3s;
        }

        .form-input:focus,
        .form-select:focus,
        .form-textarea:focus {
            outline: none;
            border-color: var(--primary-purple);
            box-shadow: 0 0 0 3px rgba(124, 58, 237, 0.1);
        }

        .form-textarea {
            min-height: 350px;
            resize: vertical;
            line-height: 1.6;
        }

        .form-hint {
            margin-top: 0.5rem;
            font-size: 0.875rem;
            color: var(--text-light);
        }

        .button-group {
            display: flex;
            gap: 1rem;
            margin-top: 2.5rem;
            padding-top: 1.5rem;
            border-top: 2px solid var(--bg-light);
            flex-wrap: wrap;
        }

        .btn {
            padding: 0.875rem 1.75rem;
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            text-decoration: none;
        }

        .btn-primary {
            background: var(--primary-purple);
            color: white;
        }

        .btn-primary:hover {
            background: var(--purple-hover);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(124, 58, 237, 0.3);
        }

        .btn-secondary {
            background: white;
            color: var(--primary-purple);
            border: 2px solid var(--primary-purple);
        }

        .btn-secondary:hover {
            background: var(--bg-light);
        }

        .btn-outline {
            background: white;
            color: var(--text-light);
            border: 1px solid var(--border-color);
        }

        .btn-outline:hover {
            background: var(--bg-light);
            border-color: var(--text-light);
        }

        .btn-danger {
            background: #ef4444;
            color: white;
            margin-left: auto;
        }

        .btn-danger:hover {
            background: #dc2626;
        }

        .btn:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        .char-count {
            text-align: right;
            font-size: 0.875rem;
            color: var(--text-light);
            margin-top: 0.5rem;
        }

        .alert {
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 1.5rem;
            display: none;
        }

        .alert-success {
            background: #d1fae5;
            color: #065f46;
            border: 1px solid #10b981;
        }

        .alert-error {
            background: #fee2e2;
            color: #991b1b;
            border: 1px solid #ef4444;
        }

        @media (max-width: 768px) {
            .container {
                padding: 1rem;
            }

            .form-card {
                padding: 1.5rem;
            }

            .form-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 1rem;
            }

            .button-group {
                flex-direction: column;
            }

            .btn {
                justify-content: center;
            }

            .btn-danger {
                margin-left: 0;
            }
        }
    </style>
</head>
<body>
    <?php 
    // Include navigation
    $navFile = APPROOT . '/views/actors/alumini/Anavbar.php';
    if (file_exists($navFile)) {
        include $navFile;
    }
    ?>

    <div class="container">
        <div class="form-card">
            <div class="form-header">
                <div>
                    <h1 class="form-title">✏️ Edit Article</h1>
                    <?php if (isset($data['article'])): ?>
                        <span class="status-badge status-<?= $data['article']->status ?>">
                            <?= ucfirst($data['article']->status) ?>
                        </span>
                    <?php endif; ?>
                </div>
                <a href="<?= URLROOT ?>/articles" class="btn-back">
                    ← Back to Articles
                </a>
            </div>

            <div id="alert-success" class="alert alert-success">
                Article updated successfully!
            </div>
            <div id="alert-error" class="alert alert-error">
                Error updating article. Please try again.
            </div>

            <?php if (isset($data['article'])): ?>
            <form id="article-form">
                <input type="hidden" id="article_id" value="<?= $data['article']->article_id ?>">
                <input type="hidden" id="current_status" value="<?= $data['article']->status ?>">

                <div class="form-group">
                    <label for="title" class="form-label">
                        Article Title <span class="required">*</span>
                    </label>
                    <input 
                        type="text" 
                        id="title" 
                        name="title" 
                        class="form-input"
                        placeholder="Enter an engaging title for your article"
                        value="<?= htmlspecialchars($data['article']->title) ?>"
                        required
                        maxlength="255"
                    >
                    <div class="char-count">
                        <span id="title-count">0</span> / 255 characters
                    </div>
                </div>

                <div class="form-group">
                    <label for="category" class="form-label">Category</label>
                    <select id="category" name="category" class="form-select">
                        <option value="">Select a category (optional)</option>
                        <option value="Career Advice" <?= $data['article']->category == 'Career Advice' ? 'selected' : '' ?>>Career Advice</option>
                        <option value="Industry Insights" <?= $data['article']->category == 'Industry Insights' ? 'selected' : '' ?>>Industry Insights</option>
                        <option value="Success Stories" <?= $data['article']->category == 'Success Stories' ? 'selected' : '' ?>>Success Stories</option>
                        <option value="Technical Skills" <?= $data['article']->category == 'Technical Skills' ? 'selected' : '' ?>>Technical Skills</option>
                        <option value="Interview Tips" <?= $data['article']->category == 'Interview Tips' ? 'selected' : '' ?>>Interview Tips</option>
                        <option value="Networking" <?= $data['article']->category == 'Networking' ? 'selected' : '' ?>>Networking</option>
                        <option value="Professional Development" <?= $data['article']->category == 'Professional Development' ? 'selected' : '' ?>>Professional Development</option>
                        <option value="Work-Life Balance" <?= $data['article']->category == 'Work-Life Balance' ? 'selected' : '' ?>>Work-Life Balance</option>
                        <option value="Entrepreneurship" <?= $data['article']->category == 'Entrepreneurship' ? 'selected' : '' ?>>Entrepreneurship</option>
                        <option value="Other" <?= $data['article']->category == 'Other' ? 'selected' : '' ?>>Other</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="tags" class="form-label">Tags</label>
                    <input 
                        type="text" 
                        id="tags" 
                        name="tags" 
                        class="form-input"
                        placeholder="e.g., career, technology, interview, tips"
                        value="<?= htmlspecialchars($data['article']->tags ?? '') ?>"
                    >
                    <div class="form-hint">
                        Separate tags with commas. Tags help students find your article.
                    </div>
                </div>

                <div class="form-group">
                    <label for="content" class="form-label">
                        Article Content <span class="required">*</span>
                    </label>
                    <textarea 
                        id="content" 
                        name="content" 
                        class="form-textarea"
                        placeholder="Share your knowledge and experience..."
                        required
                    ><?= htmlspecialchars($data['article']->content) ?></textarea>
                    <div class="char-count">
                        <span id="content-count">0</span> characters
                    </div>
                </div>

                <div class="button-group">
                    <?php if ($data['article']->status == 'draft'): ?>
                        <button type="button" class="btn btn-primary" onclick="saveArticle('published')">
                            📝 Publish Article
                        </button>
                        <button type="button" class="btn btn-secondary" onclick="saveArticle('draft')">
                            💾 Update Draft
                        </button>
                    <?php else: ?>
                        <button type="button" class="btn btn-primary" onclick="saveArticle('published')">
                            💾 Save Changes
                        </button>
                        <button type="button" class="btn btn-secondary" onclick="saveArticle('draft')">
                            ⏸️ Unpublish (Move to Drafts)
                        </button>
                    <?php endif; ?>
                    <a href="<?= URLROOT ?>/articles" class="btn btn-outline">
                        Cancel
                    </a>
                    <button type="button" class="btn btn-danger" onclick="deleteArticle()">
                        🗑️ Delete
                    </button>
                </div>
            </form>
            <?php else: ?>
                <div class="alert alert-error" style="display: block;">
                    Article not found or you don't have permission to edit it.
                </div>
            <?php endif; ?>
        </div>
    </div>

    <script>
        // Initialize character counters
        const titleInput = document.getElementById('title');
        const contentInput = document.getElementById('content');
        const titleCount = document.getElementById('title-count');
        const contentCount = document.getElementById('content-count');

        function updateCounts() {
            if (titleInput) titleCount.textContent = titleInput.value.length;
            if (contentInput) contentCount.textContent = contentInput.value.length;
        }

        // Set initial counts
        updateCounts();

        titleInput?.addEventListener('input', updateCounts);
        contentInput?.addEventListener('input', updateCounts);

        // Save article function
        async function saveArticle(status) {
            const articleId = document.getElementById('article_id').value;
            const title = document.getElementById('title').value.trim();
            const content = document.getElementById('content').value.trim();
            const category = document.getElementById('category').value;
            const tags = document.getElementById('tags').value.trim();

            // Validation
            if (!title) {
                showAlert('error', 'Please enter a title');
                document.getElementById('title').focus();
                return;
            }

            if (title.length < 10) {
                showAlert('error', 'Title must be at least 10 characters long');
                document.getElementById('title').focus();
                return;
            }

            if (!content) {
                showAlert('error', 'Please enter article content');
                document.getElementById('content').focus();
                return;
            }

            if (content.length < 100) {
                showAlert('error', 'Article content must be at least 100 characters long');
                document.getElementById('content').focus();
                return;
            }

            // Disable buttons during save
            const buttons = document.querySelectorAll('.btn');
            buttons.forEach(btn => btn.disabled = true);

            const data = {
                article_id: articleId,
                title: title,
                content: content,
                category: category,
                tags: tags,
                status: status
            };

            try {
                const response = await fetch('<?= URLROOT ?>/articles/save', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify(data)
                });

                const result = await response.json();

                if (result.success) {
                    showAlert('success', result.message || 'Article updated successfully!');
                    setTimeout(() => {
                        window.location.href = '<?= URLROOT ?>/articles';
                    }, 1500);
                } else {
                    showAlert('error', result.message || 'Error updating article. Please try again.');
                    buttons.forEach(btn => btn.disabled = false);
                }
            } catch (error) {
                console.error('Error:', error);
                showAlert('error', 'Network error. Please check your connection and try again.');
                buttons.forEach(btn => btn.disabled = false);
            }
        }

        // Delete article function
        async function deleteArticle() {
            const articleId = document.getElementById('article_id').value;
            const title = document.getElementById('title').value;

            if (!confirm(`Are you sure you want to delete "${title}"?\n\nThis action cannot be undone.`)) {
                return;
            }

            try {
                const response = await fetch('<?= URLROOT ?>/articles/delete/' + articleId, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ article_id: articleId })
                });

                const result = await response.json();

                if (result.success) {
                    showAlert('success', 'Article deleted successfully!');
                    setTimeout(() => {
                        window.location.href = '<?= URLROOT ?>/articles';
                    }, 1500);
                } else {
                    showAlert('error', result.message || 'Error deleting article. Please try again.');
                }
            } catch (error) {
                console.error('Error:', error);
                showAlert('error', 'Network error. Please check your connection and try again.');
            }
        }

        // Show alert message
        function showAlert(type, message) {
            const successAlert = document.getElementById('alert-success');
            const errorAlert = document.getElementById('alert-error');

            // Hide both alerts first
            successAlert.style.display = 'none';
            errorAlert.style.display = 'none';

            if (type === 'success') {
                successAlert.textContent = message;
                successAlert.style.display = 'block';
                window.scrollTo({ top: 0, behavior: 'smooth' });
            } else {
                errorAlert.textContent = message;
                errorAlert.style.display = 'block';
                window.scrollTo({ top: 0, behavior: 'smooth' });
            }
        }
    </script>

    <?php 
    // Include footer
    $footerFile = APPROOT . '/views/actors/alumini/Afooter.php';
    if (file_exists($footerFile)) {
        include $footerFile;
    }
    ?>
</body>
</html>
