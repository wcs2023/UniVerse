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
    <?php include '../app/views/layout/header.php'; ?>

    <div class="articles-container">
        <!-- Breadcrumb -->
        <nav class="breadcrumb">
            <a href="<?= BASE_URL ?>/articles">Articles</a>
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
            <a href="<?= BASE_URL ?>/articles" class="back-to-articles">
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
                                <img src="<?= $article['image'] ?>" alt="<?= $article['title'] ?>" 
                                     onerror="this.src='<?= BASE_URL ?>/assets/images/articles/placeholder.svg'">
                                <span class="article-category"><?= $article['category'] ?></span>
                            </div>
                            
                            <div class="article-content">
                                <h3 class="article-title">
                                    <a href="<?= BASE_URL ?>/articles/article/<?= $article['id'] ?>">
                                        <?= $article['title'] ?>
                                    </a>
                                </h3>
                                
                                <p class="article-excerpt"><?= $article['excerpt'] ?></p>
                                
                                <div class="article-meta">
                                    <div class="article-author">
                                        <span>By <?= $article['author'] ?></span>
                                        <span class="article-date"><?= date('M j, Y', strtotime($article['date'])) ?></span>
                                    </div>
                                    
                                    <div class="article-stats">
                                        <span class="stat-item">
                                            <i class="icon-views"></i>
                                            <?= $article['views'] ?>
                                        </span>
                                        <span class="stat-item">
                                            <i class="icon-likes"></i>
                                            <?= $article['likes'] ?>
                                        </span>
                                        <span class="stat-item">
                                            <i class="icon-shares"></i>
                                            <?= $article['shares'] ?>
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
                    <a href="<?= BASE_URL ?>/articles" class="btn btn-primary">Browse All Articles</a>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <?php include '../app/views/layout/footer.php'; ?>
    
    <script src="<?= BASE_URL ?>/js/main.js"></script>
</body>
</html>
