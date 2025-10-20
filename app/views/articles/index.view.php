<html>
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
        <!-- Compact Hero Banner -->
        <div class="articles-compact-banner">
            <div class="banner-gradient"></div>
            <div class="banner-text">
                <span class="banner-icon">✨</span>
                <h1>University Articles & Insights</h1>
            </div>
            <p class="banner-tagline">Discover stories that inspire, inform, and connect our community</p>
        </div>

        <!-- Categories Filter -->
        <div class="articles-categories">
            <h3>Categories</h3>
            <div class="category-filters">
                <a href="<?= BASE_URL ?>/uarticles" class="category-btn active">
                    All (<?= count($data['articles']) ?>)
                </a>
                <?php if (!empty($data['categories'])): ?>
                    <?php foreach($data['categories'] as $categoryData): ?>
                        <?php
                        // Handle both array formats
                        $categoryName = is_array($categoryData) ? ($categoryData['category'] ?? '') : $categoryData;
                        $categoryCount = is_array($categoryData) && isset($categoryData['count']) ? $categoryData['count'] : 0;
                        ?>
                        
                        <?php if (!empty($categoryName)): ?>
                        <a href="<?= BASE_URL ?>/uarticles/category/<?= strtolower($categoryName) ?>" 
                           class="category-btn">
                            <?= ucfirst(str_replace('-', ' ', $categoryName)) ?> (<?= $categoryCount ?>)
                        </a>
                        <?php endif; ?>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p style="color: #666;">No categories available</p>
                <?php endif; ?>
            </div>
        </div>

        <!-- Latest Articles Section -->
        <div class="articles-section">
            <h2>Latest Articles</h2>
            
            <?php if (!empty($data['articles'])): ?>
                <div class="articles-grid" id="articles-grid">
                    <?php 
                    $displayCount = 0;
                    foreach($data['articles'] as $article): 
                        $displayCount++;
                        $hideClass = ($displayCount > 3) ? 'hidden-article' : '';
                    ?>
                        <article class="article-card <?= $hideClass ?>" data-article-index="<?= $displayCount ?>">
                            <div class="article-image">
                                <?php 
                                $imagePath = !empty($article['featured_image']) 
                                    ? $article['featured_image'] 
                                    : '/assets/images/articles/placeholder.svg';
                                ?>

                                <img src="<?= BASE_URL ?><?= $imagePath ?>" 
                                     alt="<?= htmlspecialchars($article['title']) ?>" 
                                     onerror="this.src='<?= BASE_URL ?>/assets/images/U.png'">
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
                                        <span class="stat-item" title="Views">
                                            👁️ <?= number_format($article['views'] ?? 0) ?>
                                        </span>
                                        <span class="stat-item" title="Likes">
                                            ❤️ <?= number_format($article['likes'] ?? 0) ?>
                                        </span>
                                        <span class="stat-item" title="Comments">
                                            💬 <?= number_format($article['comments_count'] ?? 0) ?>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </article>
                    <?php endforeach; ?>
                </div>

                <!-- Load More Button -->
                <?php if(count($data['articles']) > 3): ?>
                <div class="load-more-section">
                    <button class="load-more-btn" id="load-more-btn">Load More Articles</button>
                </div>
                <?php endif; ?>
            <?php else: ?>
                <div class="no-articles">
                    <p>No articles available yet. Check back soon!</p>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <?php include __DIR__ . '/../layout/footer.php'; ?>
    
    <script>
        // Load more functionality
        const loadMoreBtn = document.getElementById('load-more-btn');
        
        if (loadMoreBtn) {
            let currentlyShown = 3;
            const articlesPerLoad = 3;
            
            loadMoreBtn.addEventListener('click', function(e) {
                e.preventDefault();
                
                const hiddenArticles = document.querySelectorAll('.article-card.hidden-article');
                
                if (hiddenArticles.length === 0) {
                    this.textContent = 'No More Articles';
                    this.disabled = true;
                    return;
                }
                
                this.textContent = 'Loading...';
                this.disabled = true;
                
                setTimeout(() => {
                    let shown = 0;
                    
                    hiddenArticles.forEach((article, index) => {
                        if (index < articlesPerLoad) {
                            article.classList.remove('hidden-article');
                            article.classList.add('fade-in-article');
                            shown++;
                        }
                    });
                    
                    currentlyShown += shown;
                    
                    const remainingHidden = document.querySelectorAll('.article-card.hidden-article');
                    
                    if (remainingHidden.length === 0) {
                        this.textContent = 'All Articles Loaded';
                        this.disabled = true;
                        this.style.opacity = '0.6';
                    } else {
                        this.textContent = 'Load More Articles';
                        this.disabled = false;
                    }
                }, 500);
            });
        }
    </script>
</body>
</html>

