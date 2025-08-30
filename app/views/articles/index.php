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
        <!-- Articles Header -->
        <div class="articles-header">
            <h1>University Articles & Insights</h1>
            <p class="articles-subtitle">Explore the latest research, campus news and student life updates from our vibrant community</p>
        </div>

        <!-- Categories Filter -->
        <div class="articles-categories">
            <h3>Categories</h3>
            <div class="category-filters">
                <a href="<?= BASE_URL ?>/articles" class="category-btn active">All</a>
                <?php foreach($data['categories'] as $category): ?>
                    <a href="<?= BASE_URL ?>/articles/category/<?= strtolower(str_replace(' ', '-', $category)) ?>" 
                       class="category-btn"><?= $category ?></a>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Latest Articles Section -->
        <div class="articles-section">
            <h2>Latest Articles</h2>
            
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
        </div>

        <!-- Load More Button -->
        <div class="load-more-section">
            <button class="load-more-btn">Load More Articles</button>
        </div>
    </div>

    <?php include '../app/views/layout/footer.php'; ?>
    
    <script src="<?= BASE_URL ?>/js/main.js"></script>
    <script>
        // Category filter functionality
        document.querySelectorAll('.category-btn').forEach(btn => {
            btn.addEventListener('click', function(e) {
                document.querySelectorAll('.category-btn').forEach(b => b.classList.remove('active'));
                this.classList.add('active');
            });
        });

        // Load more functionality (placeholder)
        document.querySelector('.load-more-btn').addEventListener('click', function() {
            // In a real application, this would load more articles via AJAX
            this.textContent = 'Loading...';
            setTimeout(() => {
                this.textContent = 'Load More Articles';
            }, 1000);
        });
    </script>
</body>
</html>
