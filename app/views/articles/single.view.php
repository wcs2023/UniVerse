<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?= BASE_URL ?>/css/styles.css">
    <link rel="icon" type="image/png" href="<?= BASE_URL ?>/assets/images/U.png">
    <title>UniVerse - <?= htmlspecialchars($data['article']['title']) ?></title>
</head>
<body>
    <?php include __DIR__ . '/../actors/undergraduate/Unavigation.view.php'; ?>
    
    <div class="article-single-container">
        <!-- Breadcrumb -->
        <nav class="breadcrumb">
            <a href="<?= BASE_URL ?>/uarticles">Articles</a>
            <span class="breadcrumb-separator">></span>
            <span class="breadcrumb-current"><?= htmlspecialchars($data['article']['title']) ?></span>
        </nav>

        <!-- Article Header -->
        <article class="article-single">
            <header class="article-header">
                <div class="article-category-badge"><?= ucfirst(str_replace('-', ' ', $data['article']['category'])) ?></div>
                <h1 class="article-title"><?= htmlspecialchars($data['article']['title']) ?></h1>
                
                <div class="article-meta-info">
                    <div class="author-info">
                        <span class="author-name">
                            By <?= htmlspecialchars(($data['article']['first_name'] ?? 'Unknown') . ' ' . ($data['article']['last_name'] ?? 'Author')) ?>
                        </span>
                        <span class="publish-date"><?= date('F j, Y', strtotime($data['article']['created_at'])) ?></span>
                    </div>
                    
                    <div class="article-stats">
                        <span class="stat-item">
                            <i class="icon-views"></i>
                            <?= number_format($data['article']['views'] ?? 0) ?> views
                        </span>
                        <span class="stat-item">
                            <i class="icon-likes"></i>
                            <?= number_format($data['article']['likes_count'] ?? 0) ?> likes
                        </span>
                        <span class="stat-item">
                            <i class="icon-comments"></i>
                            <?= number_format($data['article']['comments_count'] ?? 0) ?> comments
                        </span>
                    </div>
                </div>
            </header>

            <!-- Featured Image -->
            <?php if (!empty($data['article']['featured_image'])): ?>
            <div class="article-featured-image">
                <img src="<?= BASE_URL ?><?= $data['article']['featured_image'] ?>" 
                     alt="<?= htmlspecialchars($data['article']['title']) ?>"
                     onerror="this.src='<?= BASE_URL ?>/assets/images/U.png'">
            </div>
            <?php endif; ?>

            <!-- Article Content -->
            <div class="article-body">
                <div class="article-content">
                    <?php if (!empty($data['article']['excerpt'])): ?>
                    <p class="article-excerpt"><?= htmlspecialchars($data['article']['excerpt']) ?></p>
                    <?php endif; ?>
                    
                    <!-- Article Content from Database -->
                    <div class="article-main-content">
                        <?= $data['article']['content'] ?>
                    </div>
                </div>

                <!-- Article Actions -->
                <div class="article-actions">
                    <button class="action-btn like-btn" data-article-id="<?= $data['article']['article_id'] ?>">
                        <i class="icon-like"></i>
                        Like (<?= number_format($data['article']['likes']) ?>)
                    </button>
                    <button class="action-btn share-btn">
                        <i class="icon-share"></i>
                        Share
                    </button>
                    <button class="action-btn bookmark-btn">
                        <i class="icon-bookmark"></i>
                        Bookmark
                    </button>
                </div>

                <!-- Back to Articles Navigation -->
                <div class="article-navigation">
                    <a href="<?= BASE_URL ?>/uarticles" class="btn-back">← Back to All Articles</a>
                    <!-- <a href="<?= BASE_URL ?>/uarticles/category/<?= strtolower($data['article']['category']) ?>" class="btn-category">
                        More in <?= ucfirst(str_replace('-', ' ', $data['article']['category'])) ?>
                    </a> -->
                </div>
            </div>
        </art>
    </div>

    <?php include __DIR__ . '/../layout/footer.php'; ?>
    
    <script>
        // Article interaction functionality
        document.querySelector('.like-btn')?.addEventListener('click', function() {
            this.classList.toggle('liked');
            // In a real application, this would send an AJAX request
            const articleId = this.getAttribute('data-article-id');
            console.log('Liked article:', articleId);
        });

        document.querySelector('.share-btn')?.addEventListener('click', function() {
            // Simple share functionality
            if (navigator.share) {
                navigator.share({
                    title: '<?= addslashes($data['article']['title']) ?>',
                    text: '<?= addslashes($data['article']['excerpt'] ?? '') ?>',
                    url: window.location.href
                }).catch(error => console.log('Error sharing:', error));
            } else {
                // Fallback: copy URL to clipboard
                navigator.clipboard.writeText(window.location.href)
                    .then(() => {
                        alert('Article URL copied to clipboard!');
                    })
                    .catch(err => {
                        console.error('Could not copy text: ', err);
                    });
            }
        });

        document.querySelector('.bookmark-btn')?.addEventListener('click', function() {
            this.classList.toggle('bookmarked');
            // In a real application, this would save to user's bookmarks
            const isBookmarked = this.classList.contains('bookmarked');
            console.log('Bookmark status:', isBookmarked);
        });
    </script>
</body>
</html>
