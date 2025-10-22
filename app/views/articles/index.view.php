<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?= BASE_URL ?>/css/styles.css">
    <link rel="icon" type="image/png" href="<?= BASE_URL ?>/assets/images/U.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <title>UniVerse - <?= $data['title'] ?></title>
</head>
<body>
    <?php include __DIR__ . '/../actors/undergraduate/Unavigation.view.php'; ?>
    
    <div class="articles-page-wrapper">
        <!-- Enhanced Hero Banner -->
        <div class="articles-hero-banner">
            <div class="hero-content">
                <h1 class="hero-title">
                    University Articles & <span class="highlight">Insights</span>
                </h1>
                <p class="hero-subtitle">
                    Discover stories that inspire, inform, and connect our community
                </p>
                
                <!-- Quick Stats -->
                <div class="hero-stats">
                    <div class="stat-item">
                        <span class="stat-number"><?= count($data['articles'] ?? []) ?></span>
                        <span class="stat-label">Articles</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-number"><?= count($data['categories'] ?? []) ?></span>
                        <span class="stat-label">Categories</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-number">24/7</span>
                        <span class="stat-label">Access</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="articles-main-container">
            <!-- Enhanced Categories Filter -->
            <section class="categories-section">
                <div class="section-header">
                    <h2> Browse by Category</h2>
                    <p>Find articles that match your interests</p>
                </div>
                
                <div class="categories-grid">
                    <a href="<?= BASE_URL ?>/uarticles" class="category-card active">
                        
                        <div class="category-info">
                            <span class="category-name">All Articles</span>
                            <span class="category-count"><?= count($data['articles'] ?? []) ?> articles</span>
                        </div>
                    </a>
                    
                    <?php if (!empty($data['categories'])): ?>
                        <?php 
                        $categoryIcons = [
                            'technology' => 'fas fa-laptop-code',
                            'science' => 'fas fa-microscope',
                            'business' => 'fas fa-chart-line',
                            'health' => 'fas fa-heartbeat',
                            'education' => 'fas fa-graduation-cap',
                            'lifestyle' => 'fas fa-leaf',
                            'travel' => 'fas fa-plane',
                            'sports' => 'fas fa-football-ball',
                            'art' => 'fas fa-palette',
                            'music' => 'fas fa-music'
                        ];
                        ?>
                        <?php foreach($data['categories'] as $categoryData): ?>
                            <?php
                            $categoryName = is_array($categoryData) ? ($categoryData['category'] ?? '') : $categoryData;
                            $categoryCount = is_array($categoryData) && isset($categoryData['count']) ? $categoryData['count'] : rand(1, 15);
                            $iconClass = $categoryIcons[strtolower($categoryName)] ?? 'fas fa-bookmark';
                            ?>
                            
                            <?php if (!empty($categoryName)): ?>
                            <a href="<?= BASE_URL ?>/uarticles/category/<?= strtolower($categoryName) ?>" class="category-card">
                               
                                <div class="category-info">
                                    <span class="category-name"><?= ucfirst(str_replace('-', ' ', $categoryName)) ?></span>
                                    <span class="category-count"><?= $categoryCount ?> articles</span>
                                </div>
                            </a>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <!-- Fallback categories for demo -->
                        <?php 
                        $fallbackCategories = [
                            ['name' => 'Technology', 'icon' => 'fas fa-laptop-code', 'count' => 12],
                            ['name' => 'Science', 'icon' => 'fas fa-microscope', 'count' => 8],
                            ['name' => 'Business', 'icon' => 'fas fa-chart-line', 'count' => 15],
                            ['name' => 'Education', 'icon' => 'fas fa-graduation-cap', 'count' => 20]
                        ];
                        ?>
                        <?php foreach($fallbackCategories as $category): ?>
                        <a href="<?= BASE_URL ?>/uarticles/category/<?= strtolower($category['name']) ?>" class="category-card">
                            <div class="category-info">
                                <span class="category-name"><?= $category['name'] ?></span>
                                <span class="category-count"><?= $category['count'] ?> articles</span>
                            </div>
                        </a>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </section>

            <!-- Enhanced Articles Section -->
            <section class="articles-section">
                <div class="section-header">
                    <h2>Latest Articles</h2>
                    <div class="section-controls">
                        <div class="search-box">
                            <!-- <i class="fas fa-search"></i> -->
                            <input type="text" placeholder="Search articles..." id="article-search">
                        </div>
                        <select class="sort-dropdown" id="sort-articles">
                            <option value="newest">Newest First</option>
                            <option value="oldest">Oldest First</option>
                            <option value="popular">Most Popular</option>
                            <option value="trending">Trending</option>
                        </select>
                    </div>
                </div>
                
                <?php if (!empty($data['articles'])): ?>
                <?php else: ?>
                    <div class="no-articles-enhanced">
                        
                        <h3>No Articles Available</h3>
                        <p>We're working hard to bring you amazing content. Check back soon for inspiring articles!</p>
                        
                    </div>
                <?php endif; ?>
            </section>

            
        </div>
    </div>

    <?php include __DIR__ . '/../layout/footer.php'; ?>
    
    <style>
    /* CSS Variables */
    :root {
        --primary-purple: #6b46c1;
        --secondary-purple: #8b5cf6;
        --light-purple: #a78bfa;
        --dark-purple: #553c9a;
        --gradient-primary: linear-gradient(135deg, #6b46c1, #8b5cf6);
        --gradient-secondary: linear-gradient(135deg, #8b5cf6, #a78bfa);
        --text-dark: #1f2937;
        --text-medium: #4b5563;
        --text-light: #6b7280;
        --white: #ffffff;
        --light-gray: #f9fafb;
        --border-color: #e5e7eb;
        --shadow-sm: 0 1px 3px rgba(0, 0, 0, 0.1);
        --shadow-md: 0 4px 6px rgba(0, 0, 0, 0.1);
        --shadow-lg: 0 10px 25px rgba(0, 0, 0, 0.15);
        --radius: 12px;
        --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    /* Page Wrapper */
    .articles-page-wrapper {
        min-height: 100vh;
        background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
    }

    /* Enhanced Hero Banner - Removed Animations */
    .articles-hero-banner {
        position: relative;
        background: var(--gradient-primary);
        padding: 4rem 2rem;
        color: white;
        margin-top: 3rem;
    }

    .hero-content {
        position: relative;
        z-index: 2;
        text-align: center;
        max-width: 800px;
        margin: 0 auto;
    }

    .hero-icon {
        width: 80px;
        height: 80px;
        background: rgba(255, 255, 255, 0.2);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 2rem;
        font-size: 2rem;
    }

    .hero-title {
        font-size: 3rem;
        font-weight: 800;
        margin-bottom: 1rem;
        line-height: 1.2;
    }

    .highlight {
        background: linear-gradient(45deg, #fbbf24, #f59e0b);
        background-clip: text;
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    .hero-subtitle {
        font-size: 1.2rem;
        opacity: 0.9;
        margin-bottom: 2rem;
        line-height: 1.6;
    }

    .hero-stats {
        display: flex;
        justify-content: center;
        gap: 3rem;
        margin-top: 2rem;
    }

    .stat-item {
        text-align: center;
    }

    .stat-number {
        display: block;
        font-size: 2rem;
        font-weight: 800;
        margin-bottom: 0.25rem;
    }

    .stat-label {
        font-size: 0.9rem;
        opacity: 0.8;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    /* Main Container */
    .articles-main-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 3rem 2rem;
    }

    /* Section Headers */
    .section-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
        flex-wrap: wrap;
        gap: 1rem;
    }

    .section-header h2 {
        font-size: 2rem;
        color: var(--text-dark);
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .section-header h2 i {
        color: var(--primary-purple);
    }

    .section-header p {
        color: var(--text-medium);
        margin: 0.5rem 0 0 0;
        font-size: 1rem;
    }

    .section-controls {
        display: flex;
        gap: 1rem;
        align-items: center;
    }

    /* Search Box */
    .search-box {
        position: relative;
        display: flex;
        align-items: center;
    }

    .search-box i {
        position: absolute;
        left: 1rem;
        color: var(--text-light);
        z-index: 2;
    }

    .search-box input {
        padding: 0.75rem 1rem 0.75rem 3rem;
        border: 2px solid var(--border-color);
        border-radius: var(--radius);
        background: white;
        font-size: 0.95rem;
        width: 250px;
        transition: var(--transition);
    }

    .search-box input:focus {
        outline: none;
        border-color: var(--primary-purple);
        box-shadow: 0 0 0 3px rgba(107, 70, 193, 0.1);
    }

    /* Sort Dropdown */
    .sort-dropdown {
        padding: 0.75rem 1rem;
        border: 2px solid var(--border-color);
        border-radius: var(--radius);
        background: white;
        font-size: 0.95rem;
        cursor: pointer;
        transition: var(--transition);
    }

    .sort-dropdown:focus {
        outline: none;
        border-color: var(--primary-purple);
    }

    /* Enhanced Categories Grid */
    .categories-section {
        margin-bottom: 4rem;
    }

    .categories-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 1.5rem;
    }

    .category-card {
        background: white;
        border-radius: var(--radius);
        padding: 1.5rem;
        text-decoration: none;
        color: var(--text-dark);
        box-shadow: var(--shadow-sm);
        transition: var(--transition);
        border: 2px solid transparent;
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .category-card:hover {
        transform: translateY(-5px);
        box-shadow: var(--shadow-lg);
        border-color: var(--primary-purple);
    }

    .category-card.active {
        background: var(--gradient-primary);
        color: white;
        border-color: var(--primary-purple);
    }

    .category-icon {
        width: 50px;
        height: 50px;
        background: rgba(107, 70, 193, 0.1);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
        color: var(--primary-purple);
        flex-shrink: 0;
    }

    .category-card.active .category-icon {
        background: rgba(255, 255, 255, 0.2);
        color: white;
    }

    .category-info {
        flex: 1;
    }

    .category-name {
        display: block;
        font-weight: 600;
        font-size: 1.1rem;
        margin-bottom: 0.25rem;
    }

    .category-count {
        display: block;
        font-size: 0.9rem;
        opacity: 0.8;
    }

    /* Enhanced Articles Grid */
    .articles-grid-enhanced {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(380px, 1fr));
        gap: 2rem;
        margin-bottom: 3rem;
    }

    .article-card-enhanced {
        background: white;
        border-radius: var(--radius);
        overflow: hidden;
        box-shadow: var(--shadow-md);
        transition: var(--transition);
        opacity: 1;
        transform: translateY(0);
    }

    .article-card-enhanced:hover {
        transform: translateY(-10px);
        box-shadow: var(--shadow-lg);
    }

    .article-card-enhanced.hidden-article {
        display: none;
    }

    .article-card-enhanced.fade-in-article {
        animation: fadeInUp 0.6s ease-out;
    }

    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Enhanced Article Image */
    .article-image-wrapper {
        position: relative;
        height: 250px;
        overflow: hidden;
    }

    .article-image {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: var(--transition);
    }

    .article-card-enhanced:hover .article-image {
        transform: scale(1.1);
    }

    .image-overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.7);
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 0;
        transition: var(--transition);
    }

    .article-card-enhanced:hover .image-overlay {
        opacity: 1;
    }

    .read-btn {
        background: white;
        color: var(--primary-purple);
        padding: 1rem 2rem;
        border-radius: 50px;
        text-decoration: none;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        transition: var(--transition);
        transform: translateY(20px);
    }

    .article-card-enhanced:hover .read-btn {
        transform: translateY(0);
    }

    .read-btn:hover {
        background: var(--primary-purple);
        color: white;
    }

    /* Category Badge */
    .article-category-badge {
        position: absolute;
        top: 1rem;
        left: 1rem;
        background: var(--gradient-primary);
        color: white;
        padding: 0.5rem 1rem;
        border-radius: 50px;
        font-size: 0.8rem;
        font-weight: 600;
        z-index: 2;
    }

    /* Article Actions */
    .article-actions {
        position: absolute;
        top: 1rem;
        right: 1rem;
        display: flex;
        gap: 0.5rem;
        z-index: 2;
    }

    .action-btn {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        border: none;
        background: rgba(255, 255, 255, 0.9);
        color: var(--text-dark);
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: var(--transition);
        opacity: 0;
        transform: translateY(-10px);
    }

    .article-card-enhanced:hover .action-btn {
        opacity: 1;
        transform: translateY(0);
    }

    .action-btn:hover {
        background: var(--primary-purple);
        color: white;
    }

    /* Enhanced Article Content */
    .article-content-enhanced {
        padding: 2rem;
    }

    .article-title-enhanced {
        font-size: 1.25rem;
        font-weight: 700;
        margin-bottom: 1rem;
        line-height: 1.4;
    }

    .article-title-enhanced a {
        color: var(--text-dark);
        text-decoration: none;
        transition: var(--transition);
    }

    .article-title-enhanced a:hover {
        color: var(--primary-purple);
    }

    .article-excerpt-enhanced {
        color: var(--text-medium);
        line-height: 1.6;
        margin-bottom: 1.5rem;
    }

    /* Enhanced Meta Section */
    .article-meta-enhanced {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding-top: 1rem;
        border-top: 1px solid var(--border-color);
    }

    .author-section {
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .author-avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        overflow: hidden;
    }

    .author-avatar img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .author-info {
        display: flex;
        flex-direction: column;
    }

    .author-name {
        font-weight: 600;
        color: var(--text-dark);
        font-size: 0.95rem;
    }

    .publish-date {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        color: var(--text-light);
        font-size: 0.85rem;
        margin-top: 0.25rem;
    }

    .article-stats-enhanced {
        display: flex;
        gap: 1rem;
    }

    .article-stats-enhanced .stat-item {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        color: var(--text-light);
        font-size: 0.9rem;
    }

    /* Enhanced Load More Button */
    .load-more-section {
        text-align: center;
        margin-top: 3rem;
    }

    .load-more-btn-enhanced {
        background: var(--gradient-primary);
        color: white;
        border: none;
        padding: 1rem 3rem;
        border-radius: 50px;
        font-weight: 600;
        font-size: 1rem;
        cursor: pointer;
        transition: var(--transition);
        display: inline-flex;
        align-items: center;
        gap: 1rem;
    }

    .load-more-btn-enhanced:hover {
        transform: translateY(-3px);
        box-shadow: var(--shadow-lg);
    }

    .load-more-btn-enhanced:disabled {
        opacity: 0.6;
        cursor: not-allowed;
        transform: none;
    }

    /* Enhanced No Articles State */
    .no-articles-enhanced {
        text-align: center;
        padding: 4rem 2rem;
        background: white;
        border-radius: var(--radius);
        box-shadow: var(--shadow-sm);
    }

    .no-articles-icon {
        width: 100px;
        height: 100px;
        background: var(--gradient-primary);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 2rem;
        font-size: 3rem;
        color: white;
    }

    .no-articles-enhanced h3 {
        font-size: 1.5rem;
        color: var(--text-dark);
        margin-bottom: 1rem;
    }

    .no-articles-enhanced p {
        color: var(--text-medium);
        margin-bottom: 2rem;
        line-height: 1.6;
    }

    .no-articles-actions {
        display: flex;
        gap: 1rem;
        justify-content: center;
        flex-wrap: wrap;
    }

    .btn {
        padding: 0.875rem 2rem;
        border-radius: 50px;
        text-decoration: none;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        transition: var(--transition);
        border: 2px solid transparent;
    }

    .btn-primary {
        background: var(--gradient-primary);
        color: white;
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: var(--shadow-lg);
    }

    .btn-outline {
        background: transparent;
        color: var(--primary-purple);
        border-color: var(--primary-purple);
    }

    .btn-outline:hover {
        background: var(--primary-purple);
        color: white;
    }

    /* Featured Section */
    .featured-section {
        background: white;
        padding: 2rem;
        border-radius: var(--radius);
        box-shadow: var(--shadow-sm);
        margin-top: 3rem;
    }

    .trending-topics {
        display: flex;
        flex-wrap: wrap;
        gap: 1rem;
    }

    .topic-tag {
        background: rgba(107, 70, 193, 0.1);
        color: var(--primary-purple);
        padding: 0.5rem 1rem;
        border-radius: 50px;
        font-size: 0.9rem;
        font-weight: 500;
        transition: var(--transition);
        cursor: pointer;
    }

    .topic-tag:hover {
        background: var(--primary-purple);
        color: white;
        transform: translateY(-2px);
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .articles-hero-banner {
            padding: 3rem 1rem;
        }
        
        .hero-title {
            font-size: 2rem;
        }
        
        .hero-stats {
            flex-direction: column;
            gap: 1rem;
        }
        
        .stat-item {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 1rem;
        }
        
        .stat-number {
            font-size: 1.5rem;
            margin-bottom: 0;
        }
        
        .articles-main-container {
            padding: 2rem 1rem;
        }
        
        .section-header {
            flex-direction: column;
            align-items: flex-start;
        }
        
        .section-controls {
            width: 100%;
            flex-direction: column;
        }
        
        .search-box input {
            width: 100%;
        }
        
        .categories-grid {
            grid-template-columns: 1fr;
        }
        
        .articles-grid-enhanced {
            grid-template-columns: 1fr;
        }
        
        .article-meta-enhanced {
            flex-direction: column;
            align-items: flex-start;
            gap: 1rem;
        }
        
        .trending-topics {
            justify-content: center;
        }
    }
    </style>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Enhanced Load More Functionality
            const loadMoreBtn = document.getElementById('load-more-btn');
            
            if (loadMoreBtn) {
                let currentlyShown = 6; // Show 6 articles initially
                const articlesPerLoad = 3;
                
                loadMoreBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    
                    const hiddenArticles = document.querySelectorAll('.article-card-enhanced.hidden-article');
                    
                    if (hiddenArticles.length === 0) {
                        this.innerHTML = '<span class="btn-text">All Articles Loaded</span><span class="btn-icon"><i class="fas fa-check"></i></span>';
                        this.disabled = true;
                        return;
                    }
                    
                    this.innerHTML = '<span class="btn-text">Loading...</span><span class="btn-icon"><i class="fas fa-spinner fa-spin"></i></span>';
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
                        
                        const remainingHidden = document.querySelectorAll('.article-card-enhanced.hidden-article');
                        
                        if (remainingHidden.length === 0) {
                            this.innerHTML = '<span class="btn-text">All Articles Loaded</span><span class="btn-icon"><i class="fas fa-check"></i></span>';
                            this.disabled = true;
                        } else {
                            this.innerHTML = '<span class="btn-text">Load More Articles</span><span class="btn-icon"><i class="fas fa-chevron-down"></i></span>';
                            this.disabled = false;
                        }
                    }, 1000);
                });
            }
            
            // Search Functionality
            const searchInput = document.getElementById('article-search');
            if (searchInput) {
                searchInput.addEventListener('input', function() {
                    const searchTerm = this.value.toLowerCase();
                    const articles = document.querySelectorAll('.article-card-enhanced');
                    
                    articles.forEach(article => {
                        const title = article.querySelector('.article-title-enhanced').textContent.toLowerCase();
                        const excerpt = article.querySelector('.article-excerpt-enhanced').textContent.toLowerCase();
                        
                        if (title.includes(searchTerm) || excerpt.includes(searchTerm)) {
                            article.style.display = 'block';
                        } else {
                            article.style.display = 'none';
                        }
                    });
                });
            }
            
            // Sort Functionality
            const sortSelect = document.getElementById('sort-articles');
            if (sortSelect) {
                sortSelect.addEventListener('change', function() {
                    // Add sorting logic here
                    console.log('Sorting by:', this.value);
                });
            }
            
            // Bookmark Functionality
            const bookmarkBtns = document.querySelectorAll('.bookmark-btn');
            bookmarkBtns.forEach(btn => {
                btn.addEventListener('click', function(e) {
                    e.preventDefault();
                    const icon = this.querySelector('i');
                    if (icon.classList.contains('far')) {
                        icon.classList.remove('far');
                        icon.classList.add('fas');
                        this.style.background = '#fbbf24';
                        this.style.color = 'white';
                    } else {
                        icon.classList.remove('fas');
                        icon.classList.add('far');
                        this.style.background = '';
                        this.style.color = '';
                    }
                });
            });
            
            // Share Functionality
            const shareBtns = document.querySelectorAll('.share-btn');
            shareBtns.forEach(btn => {
                btn.addEventListener('click', function(e) {
                    e.preventDefault();
                    // Simple share functionality
                    if (navigator.share) {
                        navigator.share({
                            title: 'Check out this article',
                            url: window.location.href
                        });
                    } else {
                        // Fallback: copy to clipboard
                        navigator.clipboard.writeText(window.location.href);
                        const originalIcon = this.innerHTML;
                        this.innerHTML = '<i class="fas fa-check"></i>';
                        setTimeout(() => {
                            this.innerHTML = originalIcon;
                        }, 2000);
                    }
                });
            });
        });
    </script>
</body>
</html>

