<?php
// Define constants if not already defined (for direct access)
if (!defined('APPROOT')) {
    define('APPROOT', dirname(dirname(dirname(dirname(__FILE__)))));
}
if (!defined('URLROOT')) {
    define('URLROOT', 'http://localhost/UniVerse/public');
}
if (!defined('BASE_URL')) {
    define('BASE_URL', 'http://localhost/UniVerse/public');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Article - UniVerse</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/css/alumni.css">
</head>
<body>
    <?php 
    // Include navigation
    $navFile = APPROOT . '/views/actors/alumni/Anavbar.php';
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
                        <span class="status-badge status-<?= $data['article']['status'] ?>">
                            <?= ucfirst($data['article']['status']) ?>
                        </span>
                    <?php endif; ?>
                </div>
                <a href="<?= BASE_URL ?>/aarticles" class="btn-back">
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
                <input type="hidden" id="article_id" value="<?= $data['article']['article_id'] ?>">
                <input type="hidden" id="current_status" value="<?= $data['article']['status'] ?>">

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
                        value="<?= htmlspecialchars($data['article']['title']) ?>"
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
                        <option value="Career Advice" <?= $data['article']['category'] == 'Career Advice' ? 'selected' : '' ?>>Career Advice</option>
                        <option value="Industry Insights" <?= $data['article']['category'] == 'Industry Insights' ? 'selected' : '' ?>>Industry Insights</option>
                        <option value="Success Stories" <?= $data['article']['category'] == 'Success Stories' ? 'selected' : '' ?>>Success Stories</option>
                        <option value="Technical Skills" <?= $data['article']['category'] == 'Technical Skills' ? 'selected' : '' ?>>Technical Skills</option>
                        <option value="Interview Tips" <?= $data['article']['category'] == 'Interview Tips' ? 'selected' : '' ?>>Interview Tips</option>
                        <option value="Networking" <?= $data['article']['category'] == 'Networking' ? 'selected' : '' ?>>Networking</option>
                        <option value="Professional Development" <?= $data['article']['category'] == 'Professional Development' ? 'selected' : '' ?>>Professional Development</option>
                        <option value="Work-Life Balance" <?= $data['article']['category'] == 'Work-Life Balance' ? 'selected' : '' ?>>Work-Life Balance</option>
                        <option value="Entrepreneurship" <?= $data['article']['category'] == 'Entrepreneurship' ? 'selected' : '' ?>>Entrepreneurship</option>
                        <option value="Other" <?= $data['article']['category'] == 'Other' ? 'selected' : '' ?>>Other</option>
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
                        value="<?= htmlspecialchars($data['article']['tags'] ?? '') ?>"
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
                    ><?= htmlspecialchars($data['article']['content']) ?></textarea>
                    <div class="char-count">
                        <span id="content-count">0</span> characters
                    </div>
                </div>

                <div class="button-group">
                    <?php if ($data['article']['status'] == 'draft'): ?>
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
                    <a href="<?= BASE_URL ?>/aarticles" class="btn btn-outline">
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

            if (title.length < 1) {
                showAlert('error', 'Title must be at least 10 characters long');
                document.getElementById('title').focus();
                return;
            }

            if (!content) {
                showAlert('error', 'Please enter article content');
                document.getElementById('content').focus();
                return;
            }

            if (content.length < 10) {
                showAlert('error', 'Article content must be at least 10 characters long');
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
                const response = await fetch('<?= BASE_URL ?>/aarticles/save', {
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
                        window.location.href = '<?= BASE_URL ?>/aarticles';
                    }, 1500);
                } else {
                    showAlert('error', result.message || 'Error updating article. Please try again.');
                    buttons.forEach(btn => btn.disabled = false);
                }
            } catch (error) {
                console.error('Error:', error);
                showAlert('error', 'Error. Please check your connection and try again.');
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
                const response = await fetch('<?= BASE_URL ?>/aarticles/delete/' + articleId, {
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
                        window.location.href = '<?= BASE_URL ?>/aarticles';
                    }, 1500);
                } else {
                    showAlert('error', result.message || 'Error deleting article. Please try again.');
                }
            } catch (error) {
                console.error('Error:', error);
                showAlert('error', 'Error. Please check your connection and try again.');
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
    include __DIR__ . '/../../layout/footer.php';
    ?>
</body>
</html>

