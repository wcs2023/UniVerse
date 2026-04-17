<?php
// Define constants if not already defined
if (!defined('APPROOT')) {
    define('APPROOT', dirname(dirname(dirname(dirname(__FILE__)))));
}
if (!defined('URLROOT')) {
    define('URLROOT', 'http://localhost/UniVerse/public');
}
if (!defined('BASE_URL')) {
    define('BASE_URL', 'http://localhost/UniVerse/public');
}

// Extract article from data
$article = $data['article'] ?? [];
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($article['title'] ?? 'Article') ?> - UniVerse</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/alumni.css">
    <link rel="icon" type="image/png" href="<?= BASE_URL ?>/assets/images/U.png">
    <style>
        body {
            padding-top: 80px;
            background-color: #a78bfa45 !important;
        }
        .article-body {
            white-space: pre-wrap;
            word-break: break-word;
            line-height: 1.8;
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

    <div class="article-container">
        <!-- Header Actions -->
        <div class="article-header-actions">
            <a href="<?= BASE_URL ?>/aarticles" class="back-btn">
                ← Back to My Articles
            </a>

            <div class="action-buttons">
                <a href="<?= BASE_URL ?>/aarticles/edit/<?= $article['article_id'] ?>" class="btn btn-secondary">
                    Edit Article
                </a>
            </div>
        </div>

        <!-- Article Card -->
        <div class="article-card">
            <!-- Article Hero -->
            <div class="article-hero">
                <span class="status-badge status-<?= $article['status'] ?>">
                    <?= ucfirst($article['status']) ?>
                </span>

                <h1 class="article-title"><?= htmlspecialchars($article['title']) ?></h1>

                <div class="article-meta">
                    <div class="meta-item">
                        <span class="meta-icon">Date:</span>
                        <span>
                            <?php if ($article['status'] === 'published' && !empty($article['published_at'])): ?>
                                Published on <?= date('F j, Y', strtotime($article['published_at'])) ?>
                            <?php else: ?>
                                Last updated <?= date('F j, Y', strtotime($article['updated_at'])) ?>
                            <?php endif; ?>
                        </span>
                    </div>

                    <?php if (!empty($article['category'])): ?>
                        <div class="meta-item">
                            <span class="meta-icon">Category:</span>
                            <span><?= htmlspecialchars($article['category']) ?></span>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Article Content -->
           <div class="article-content">
            <div class="article-body">
                <?= nl2br(htmlspecialchars($article['content'] ?? '')) ?>
            </div>

            <!-- Article Stats -->
            <div class="article-stats">
                <div class="stat-item">
                    <span class="stat-icon">Views:</span>
                    <span><?= number_format($article['views'] ?? 0) ?> views</span>
                </div>
                <div class="stat-item">
                    <span class="stat-icon">Likes:</span>
                    <span><?= number_format($article['likes'] ?? 0) ?> likes</span>
                </div>
            </div>
        </div>
    </div>
</body>

</html>