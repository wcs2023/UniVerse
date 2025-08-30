<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?= BASE_URL ?>/css/styles.css">
    <link rel="icon" type="image/png" href="<?= BASE_URL ?>/assets/images/U.png">
    <title>UniVerse - <?= $data['article']['title'] ?></title>
</head>
<body>
    <?php include '../app/views/layout/header.php'; ?>

    <div class="article-single-container">
        <!-- Breadcrumb -->
        <nav class="breadcrumb">
            <a href="<?= BASE_URL ?>/articles">Articles</a>
            <span class="breadcrumb-separator">></span>
            <a href="<?= BASE_URL ?>/articles/category/<?= strtolower(str_replace(' ', '-', $data['article']['category'])) ?>">
                <?= $data['article']['category'] ?>
            </a>
            <span class="breadcrumb-separator">></span>
            <span class="breadcrumb-current"><?= $data['article']['title'] ?></span>
        </nav>

        <!-- Article Header -->
        <article class="article-single">
            <header class="article-header">
                <div class="article-category-badge"><?= $data['article']['category'] ?></div>
                <h1 class="article-title"><?= $data['article']['title'] ?></h1>
                
                <div class="article-meta-info">
                    <div class="author-info">
                        <span class="author-name">By <?= $data['article']['author'] ?></span>
                        <span class="publish-date"><?= date('F j, Y', strtotime($data['article']['date'])) ?></span>
                    </div>
                    
                    <div class="article-stats">
                        <span class="stat-item">
                            <i class="icon-views"></i>
                            <?= $data['article']['views'] ?> views
                        </span>
                        <span class="stat-item">
                            <i class="icon-likes"></i>
                            <?= $data['article']['likes'] ?> likes
                        </span>
                    </div>
                </div>
            </header>

            <!-- Featured Image -->
            <div class="article-featured-image">
                <img src="<?= $data['article']['image'] ?>" alt="<?= $data['article']['title'] ?>"
                     onerror="this.src='<?= BASE_URL ?>/assets/images/articles/placeholder.svg'">
            </div>

            <!-- Article Content -->
            <div class="article-body">
                <div class="article-content">
                    <p class="article-excerpt"><?= $data['article']['excerpt'] ?></p>
                    
                    <!-- Sample article content -->
                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>
                    
                    <p>Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
                    
                    <h3>Key Findings</h3>
                    <p>Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo.</p>
                    
                    <ul>
                        <li>Revolutionary approach to problem solving</li>
                        <li>Innovative methodologies and applications</li>
                        <li>Significant impact on academic research</li>
                        <li>Practical implications for industry</li>
                    </ul>
                    
                    <h3>Future Implications</h3>
                    <p>Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt.</p>
                    
                    <p>Neque porro quisquam est, qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit, sed quia non numquam eius modi tempora incidunt ut labore et dolore magnam aliquam quaerat voluptatem.</p>
                </div>

                <!-- Article Actions -->
                <div class="article-actions">
                    <button class="action-btn like-btn">
                        <i class="icon-like"></i>
                        Like (<?= $data['article']['likes'] ?>)
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
            </div>
        </article>

        <!-- Related Articles -->
        <?php if (!empty($data['related_articles'])): ?>
        <section class="related-articles">
            <h2>Related Articles</h2>
            <div class="related-articles-grid">
                <?php foreach (array_slice($data['related_articles'], 0, 3) as $related): ?>
                    <div class="related-article-card">
                        <div class="related-article-image">
                            <img src="<?= $related['image'] ?>" alt="<?= $related['title'] ?>"
                                 onerror="this.src='<?= BASE_URL ?>/assets/images/articles/placeholder.svg'">
                        </div>
                        <div class="related-article-content">
                            <h3>
                                <a href="<?= BASE_URL ?>/articles/article/<?= $related['id'] ?>">
                                    <?= $related['title'] ?>
                                </a>
                            </h3>
                            <p class="related-article-excerpt"><?= substr($related['excerpt'], 0, 100) ?>...</p>
                            <span class="related-article-date"><?= date('M j, Y', strtotime($related['date'])) ?></span>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>
        <?php endif; ?>
    </div>

    <?php include '../app/views/layout/footer.php'; ?>
    
    <script src="<?= BASE_URL ?>/js/main.js"></script>
    <script>
        // Article interaction functionality
        document.querySelector('.like-btn').addEventListener('click', function() {
            this.classList.toggle('liked');
            // In a real application, this would send an AJAX request
        });

        document.querySelector('.share-btn').addEventListener('click', function() {
            // Simple share functionality
            if (navigator.share) {
                navigator.share({
                    title: '<?= $data['article']['title'] ?>',
                    url: window.location.href
                });
            } else {
                // Fallback: copy URL to clipboard
                navigator.clipboard.writeText(window.location.href);
                alert('Article URL copied to clipboard!');
            }
        });

        document.querySelector('.bookmark-btn').addEventListener('click', function() {
            this.classList.toggle('bookmarked');
            // In a real application, this would save to user's bookmarks
        });
    </script>
</body>
</html>
