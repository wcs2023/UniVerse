<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?= BASE_URL ?>/css/styles.css">
    <link rel="icon" type="image/png" href="<?= BASE_URL ?>/assets/images/U.png">
    <title>UniVerse - <?= $data['title'] ?></title>
</head>
<body>

<?php include __DIR__ . '/../actors/undergraduate/Unavigation.view.php'; ?>

    <div class="articles-container">
        <!-- Breadcrumb -->
        <nav class="breadcrumb">
            <a href="<?= BASE_URL ?>/uarticles">Articles</a>
            <span class="breadcrumb-separator">></span>
            <span class="breadcrumb-current"><?= ucfirst($data['category']) ?></span>
        </nav>

        <!-- Category Header -->
        <div class="category-header">
            <h1><?= ucfirst(str_replace('-', ' ', $data['category'])) ?> Articles</h1>
            <p class="category-description">Explore articles in the <?= strtolower(str_replace('-', ' ', $data['category'])) ?> category</p>
        </div>

        <!-- Back to Categories -->
        <div class="category-navigation">
            <a href="<?= BASE_URL ?>/uarticles" class="back-to-articles">
                ← Back to All Articles
            </a>
        </div>

        <!-- Category Articles -->
        <div class="articles-section">
            <?php if (!empty($data['articles'])): ?>
                <div class="articles-grid">
                    <?php foreach($data['articles'] as $article): ?>
                        <article class="article-card">
                            <div class="article-image">
                                <?php 
                                $imagePath = !empty($article['featured_image']) 
                                    ? $article['featured_image'] 
                                    : '/assets/images/articles/placeholder.svg';
                                ?>
                                <img src="<?= BASE_URL ?><?= $imagePath ?>" 
                                     alt="<?= htmlspecialchars($article['title']) ?>" 
                                     onerror="this.src='<?= BASE_URL ?>/assets/images/articles/placeholder.svg'">
                                <span class="article-category"><?= ucfirst(str_replace('-', ' ', $article['category'])) ?></span>
                            </div>
                            
                            <div class="article-content">
                                <h3 class="article-title">
                                    <a href="<?= BASE_URL ?>/uarticles/viewDetails/<?= $article['article_id'] ?>">
                                        <?= htmlspecialchars($article['title']) ?>
                                    </a>
                                </h3>
                                
                                <p class="article-excerpt"><?= htmlspecialchars($article['excerpt']) ?></p>
                                
                                <div class="article-meta">
                                    <div class="article-author">
                                        <span>By <?= htmlspecialchars($article['first_name'] . ' ' . $article['last_name']) ?></span>
                                        <span class="article-date"><?= date('M j, Y', strtotime($article['created_at'])) ?></span>
                                    </div>
                                    
                                    <div class="article-stats">
                                        <span class="stat-item">
                                            <i class="icon-views"></i>
                                            <?= number_format($article['views'] ?? 0) ?>
                                        </span>
                                        <span class="stat-item">
                                            <i class="icon-likes"></i>
                                            <?= number_format($article['likes_count'] ?? 0) ?>
                                        </span>
                                        <span class="stat-item">
                                            <i class="icon-comments"></i>
                                            <?= number_format($article['comments_count'] ?? 0) ?>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </article>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="no-articles">
                    <h3>No articles found in this category</h3>
                    <p>Check back later for new content, or explore other categories.</p>
                    <a href="<?= BASE_URL ?>/uarticles" class="btn btn-primary">Browse All Articles</a>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <?php include __DIR__ . '/../layout/footer.php'; ?>
    
    <!-- <script src="<?= BASE_URL ?>/js/main.js"></script> -->
</body>
</html>