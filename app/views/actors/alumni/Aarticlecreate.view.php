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
    <title>Create New Article - UniVerse</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/css/alumni.css">
    <link rel="icon" type="image/png" href="<?= BASE_URL ?>/assets/images/U.png">
    <style>
        body {
            padding-top: 80px;
            background-color: #a78bfa45 !important;
        }

        /* Override alumni.css - hide alerts by default */
        #alert-success,
        #alert-error {
            display: none !important;
        }
        
        #alert-success.show,
        #alert-error.show {
            display: flex !important;
        }
    </style>
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
                <h1 class="form-title">Create New Article</h1>
                <a href="<?= BASE_URL ?>/aarticles" class="btn-back">
                    ← Back to Articles
                </a>
            </div>

            <div id="alert-success" class="alert alert-success">
                Article saved successfully!
            </div>
            <div id="alert-error" class="alert alert-error">
                Error saving article. Please try again.
            </div>

            <form id="article-form">
                <div class="form-group">
                    <label for="title" class="form-label">
                        Article Title <span class="required">*</span>
                    </label>
                    <input type="text" id="title" name="title" class="form-input"
                        placeholder="Enter an engaging title for your article" required maxlength="255">
                    <div class="char-count">
                        <span id="title-count">0</span> / 255 characters
                    </div>
                </div>

                <div class="form-group">
                    <label for="category" class="form-label">Category</label>
                    <select id="category" name="category" class="form-select">
                        <option value="">Select a category (optional)</option>
                        <option value="technology">Technology</option>
                        <option value="career">Career</option>
                        <option value="education">Education</option>
                        <option value="research">Research</option>
                        <option value="student-life">Student Life</option>
                        <option value="industry-news">Industry News</option>
                        <option value="announcement">Announcement</option>
                        <option value="other">Other</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="tags" class="form-label">Tags</label>
                    <input type="text" id="tags" name="tags" class="form-input"
                        placeholder="e.g., career, technology, interview, tips">
                    <div class="form-hint">
                        Separate tags with commas. Tags help students find article type.
                    </div>
                </div>

                <div class="form-group">
                    <label for="content" class="form-label">
                        Article Content <span class="required">*</span>
                    </label>
                    <textarea id="content" name="content" class="form-textarea"
                        placeholder="Share your knowledge and experience... Write your article content here."
                        required></textarea>
                    <div class="char-count">
                        <span id="content-count">0</span> characters
                    </div>
                </div>

                <div class="button-group">
                    <button type="button" class="btn btn-primary" onclick="saveArticle('published')">
                        Publish Article
                    </button>
                    <button type="button" class="btn btn-secondary" onclick="saveArticle('draft')">
                        Save as Draft
                    </button>
                    <a href="<?= BASE_URL ?>/aarticles" class="btn btn-outline">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Character counters
        const titleInput = document.getElementById('title');
        const contentInput = document.getElementById('content');
        const titleCount = document.getElementById('title-count');
        const contentCount = document.getElementById('content-count');

        titleInput.addEventListener('input', function () {
            titleCount.textContent = this.value.length;
        });

        contentInput.addEventListener('input', function () {
            contentCount.textContent = this.value.length;
        });

        // Save article function
        async function saveArticle(status) {
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

            if (title.length < 4) {
                showAlert('error', 'Title must be at least 5 characters long');
                document.getElementById('title').focus();
                return;
            }

            if (!content) {
                showAlert('error', 'Please enter article content');
                document.getElementById('content').focus();
                return;
            }

            if (content.length < 9) {
                showAlert('error', 'Article content must be at least 10 characters long');
                document.getElementById('content').focus();
                return;
            }

            // Disable buttons during save
            const buttons = document.querySelectorAll('.btn');
            buttons.forEach(btn => btn.disabled = true);

            const data = {
                title: title,
                content: content,
                category: category,
                tags: tags,
                status: status
            };

            try {
                const response = await fetch('<?= BASE_URL ?>/Aarticles/save', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify(data)
                });

                const result = await response.json();

                if (result.success) {
                    showAlert('success', result.message || 'Article saved successfully!');
                    setTimeout(() => {
                        window.location.href = '<?= BASE_URL ?>/aarticles';
                    }, 1500);
                } else {
                    showAlert('error', result.message || 'Error saving article. Please try again.');
                    buttons.forEach(btn => btn.disabled = false);
                }
            } catch (error) {
                console.error('Error:', error);
                showAlert('error', 'Error. Please check your connection and try again.');
                buttons.forEach(btn => btn.disabled = false);
            }
        }

        // Show alert message
        function showAlert(type, message) {
            const successAlert = document.getElementById('alert-success');
            const errorAlert = document.getElementById('alert-error');

            // Hide both alerts first
            successAlert.classList.remove('show');
            errorAlert.classList.remove('show');

            if (type === 'success') {
                successAlert.textContent = message;
                successAlert.classList.add('show');
                window.scrollTo({ top: 0, behavior: 'smooth' });
            } else {
                errorAlert.textContent = message;
                errorAlert.classList.add('show');
                window.scrollTo({ top: 0, behavior: 'smooth' });
            }
        }

        // Auto-hide alerts after 4 seconds
        setTimeout(() => {
            document.getElementById('alert-success').classList.remove('show');
            document.getElementById('alert-error').classList.remove('show');
        }, 4000);
    </script>

    <?php
    // Include footer
    include __DIR__ . '/../../layout/footer.php';
    ?>
</body>

</html>