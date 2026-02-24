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
    <title>My Articles - UniVerse</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/css/alumni.css">
    <link rel="icon" type="image/png" href="<?= BASE_URL ?>/assets/images/U.png">
    <style>
        body {
            padding-top: 80px;
            background-color: #a78bfa45 !important;
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
        <!-- Page Header -->
        <div class="page-header">
            <h1 class="page-title">Manage My Articles</h1>
            <a href="<?= BASE_URL ?>/aarticles/create" class="btn-create">
                <!-- <span class="btn-icon">⊕</span> -->
                <span>Create New Article</span>
            </a>
        </div>

        <!-- Drafts Section -->
        <div class="section">
            <h2 class="section-title">Drafts</h2>

            <div class="articles-grid">
                <?php if (isset($data['drafts']) && count($data['drafts']) > 0): ?>
                    <?php foreach ($data['drafts'] as $index => $article): ?>
                        <div class="article-card" style="animation-delay: <?= $index * 0.1 ?>s">
                            <div class="article-header">
                                <span class="status-badge status-draft">Draft</span>
                                <div class="article-actions">
                                    <a href="<?= BASE_URL ?>/aarticles/edit/<?= $article['article_id'] ?>"
                                        class="btn-icon-action" title="Edit">
                                        Edit
                                    </a>
                                    <button onclick="deleteArticle(<?= $article['article_id'] ?>, 'draft')"
                                        class="btn-icon-action delete" title="Delete">
                                        Delete
                                    </button>
                                </div>
                            </div>

                            <h3 class="article-title"><?= htmlspecialchars($article['title']) ?></h3>
                            <p class="article-meta">Last saved: <?= date('M d, Y', strtotime($article['updated_at'])) ?></p>

                            <div class="article-stats">
                                <div class="stat-item">
                                    <span class="stat-icon">Views:</span>
                                    <span><?= $article['views'] ?? 0 ?></span>
                                </div>
                                <div class="stat-item">
                                    <span class="stat-icon">Likes:</span>
                                    <span><?= $article['likes'] ?? 0 ?></span>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="empty-state">
                        <div class="empty-icon">--</div>
                        <h3>No Drafts</h3>
                        <p>You don't have any saved drafts. Start a new article!</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Published Section -->
        <div class="section">
            <h2 class="section-title">Published</h2>

            <!-- Debug Info (Remove this after testing) -->
            <?php if (isset($_GET['debug'])): ?>
                <div
                    style="background: #fff; padding: 1rem; margin-bottom: 1rem; border: 2px solid #8b5cf6; border-radius: 8px;">
                    <strong>Debug Info:</strong><br>
                    Published Count: <?= isset($data['published']) ? count($data['published']) : 'not set' ?><br>
                    Drafts Count: <?= isset($data['drafts']) ? count($data['drafts']) : 'not set' ?><br>
                    <?php if (isset($data['published'])): ?>
                        <pre><?= print_r($data['published'], true) ?></pre>
                    <?php endif; ?>
                </div>
            <?php endif; ?>

            <div class="articles-grid">
                <?php if (isset($data['published']) && count($data['published']) > 0): ?>
                    <?php foreach ($data['published'] as $index => $article): ?>
                        <div class="article-card"
                            style="animation-delay: <?= ($index + (count($data['drafts'] ?? []))) * 0.1 ?>s">
                            <div class="article-header">
                                <span class="status-badge status-published">Published</span>
                                <div class="article-actions">
                                    <a href="<?= BASE_URL ?>/aarticles/preview/<?= $article['article_id'] ?>"
                                        class="btn-icon-action" title="View Article">
                                        view
                                    </a>
                                    <a href="<?= BASE_URL ?>/aarticles/edit/<?= $article['article_id'] ?>"
                                        class="btn-icon-action" title="Edit">
                                        edit
                                    </a>
                                    <button onclick="deleteArticle(<?= $article['article_id'] ?>, 'published')"
                                        class="btn-icon-action delete" title="Delete">
                                        Delete
                                    </button>
                                </div>
                            </div>

                            <h3 class="article-title"><?= htmlspecialchars($article['title']) ?></h3>
                            <p class="article-meta">Published on: <?= date('M d, Y', strtotime($article['published_at'])) ?></p>

                            <div class="article-stats">
                                <div class="stat-item">
                                    <span class="stat-icon">views</span>
                                    <span><?= number_format($article['views'] ?? 0) ?></span>
                                </div>
                                <div class="stat-item">
                                    <span class="stat-icon">likes</span>
                                    <span><?= number_format($article['likes'] ?? 0) ?></span>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="empty-state">
                        <div class="empty-icon">--</div>
                        <h3>No Published Articles</h3>
                        <p>You haven't published any articles yet. Create your first article!</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script>
        function deleteArticle(articleId, status) {
            const confirmMessage = status === 'draft'
                ? 'Are you sure you want to delete this draft? This action cannot be undone.'
                : 'Are you sure you want to delete this published article? This will remove it from public view.';

            if (confirm(confirmMessage)) {
                // Send delete request
                fetch('<?= BASE_URL ?>/aarticles/delete/' + articleId, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ article_id: articleId })
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Reload page to show updated list
                            location.reload();
                        } else {
                            alert('Error deleting article. Please try again.');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Error deleting article. Please try again.');
                    });
            }
        }

        // Add stagger animation to cards
        document.addEventListener('DOMContentLoaded', function () {
            const cards = document.querySelectorAll('.article-card');
            cards.forEach((card, index) => {
                card.style.animationDelay = (index * 0.1) + 's';
            });
        });
    </script>

    <?php
    // Include footer
    include __DIR__ . '/../../layout/footer.php';
    ?>
</body>

</html>