<?php
    $title = $data['title'] ?? 'Career Articles';
    $userType = $data['user_type'] ?? 'student';
    include_once __DIR__ . '/includes/header2.view.php';
?>

<main class="main-container">
    <!-- Page Header -->
    <section class="page-header fade-in">
        <div class="header-content">
            <h1><?= htmlspecialchars($title) ?></h1>
            <p class="header-description">
                Stay informed with the latest career guidance, university insights, and professional development tips
                <?= $userType === 'school_leaver' ? 'tailored for school leavers' : 'for students' ?>.
            </p>
        </div>
        
        <!-- Search Bar -->
        <div class="search-container">
            <form action="<?= BASE_URL ?>/<?= $userType === 'school_leaver' ? 'schoolleaver' : 'undergraduate' ?>/searchArticles" method="GET" class="search-form">
                <div class="search-input-group">
                    <input type="text" name="q" placeholder="Search articles..." value="<?= htmlspecialchars($_GET['q'] ?? '') ?>" required>
                    <button type="submit" class="search-btn">
                        <i class="fa-solid fa-search"></i>
                    </button>
                </div>
            </form>
        </div>
    </section>

    <!-- Category Filter -->
    <?php if (!empty($data['categories'])): ?>
    <section class="category-filter fade-in">
        <div class="filter-container">
            <a href="<?= BASE_URL ?>/<?= $userType === 'school_leaver' ? 'schoolleaver' : 'undergraduate' ?>/articles" 
               class="category-btn <?= !isset($data['active_category']) ? 'active' : '' ?>">
                All Articles
            </a>
            <?php foreach ($data['categories'] as $category): ?>
                <a href="<?= BASE_URL ?>/<?= $userType === 'school_leaver' ? 'schoolleaver' : 'undergraduate' ?>/articlesByCategory/<?= urlencode(strtolower(str_replace(' ', '-', $category))) ?>" 
                   class="category-btn <?= isset($data['active_category']) && strtolower(str_replace(' ', '-', $category)) === $data['active_category'] ? 'active' : '' ?>">
                    <?= htmlspecialchars($category) ?>
                </a>
            <?php endforeach; ?>
        </div>
    </section>
    <?php endif; ?>

    <!-- Articles Grid -->
    <section class="articles-section">
        <?php if (!empty($data['articles'])): ?>
            <div class="articles-grid">
                <?php foreach ($data['articles'] as $article): ?>
                    <article class="article-card fade-in">
                        <?php if (!empty($article['image_url'])): ?>
                            <div class="article-image">
                                <img src="<?= htmlspecialchars($article['image_url']) ?>" alt="<?= htmlspecialchars($article['title']) ?>">
                            </div>
                        <?php else: ?>
                            <div class="article-image placeholder">
                                <i class="fa-solid fa-newspaper"></i>
                            </div>
                        <?php endif; ?>
                        
                        <div class="article-content">
                            <div class="article-meta">
                                <span class="category-tag"><?= htmlspecialchars($article['category']) ?></span>
                                <span class="read-time"><?= $article['read_time'] ?? '5 min read' ?></span>
                            </div>
                            
                            <h2 class="article-title">
                                <a href="<?= BASE_URL ?>/<?= $userType === 'school_leaver' ? 'schoolleaver' : 'undergraduate' ?>/article/<?= $article['article_id'] ?>">
                                    <?= htmlspecialchars($article['title']) ?>
                                </a>
                            </h2>
                            
                            <p class="article-excerpt">
                                <?= htmlspecialchars($article['excerpt'] ?? substr(strip_tags($article['content'] ?? ''), 0, 150) . '...') ?>
                            </p>
                            
                            <div class="article-footer">
                                <div class="author-info">
                                    <i class="fa-solid fa-user"></i>
                                    <span>By <?= htmlspecialchars($article['author_name'] ?? 'Anonymous') ?></span>
                                </div>
                                <div class="article-date">
                                    <i class="fa-solid fa-calendar"></i>
                                    <span><?= date('M j, Y', strtotime($article['created_at'])) ?></span>
                                </div>
                            </div>
                            
                            <a href="<?= BASE_URL ?>/<?= $userType === 'school_leaver' ? 'schoolleaver' : 'undergraduate' ?>/article/<?= $article['article_id'] ?>" 
                               class="read-more-btn">
                                Read More <i class="fa-solid fa-arrow-right"></i>
                            </a>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="no-articles">
                <div class="no-articles-content">
                    <i class="fa-solid fa-newspaper"></i>
                    <h3>No Articles Found</h3>
                    <p>
                        <?php if (isset($data['search_query'])): ?>
                            No articles found for "<?= htmlspecialchars($data['search_query']) ?>". Try a different search term.
                        <?php else: ?>
                            No articles available in this category at the moment.
                        <?php endif; ?>
                    </p>
                    <a href="<?= BASE_URL ?>/<?= $userType === 'school_leaver' ? 'schoolleaver' : 'undergraduate' ?>/articles" class="btn btn-primary">
                        View All Articles
                    </a>
                </div>
            </div>
        <?php endif; ?>
    </section>
</main>

<!-- Footer -->
<footer style="margin-top: 4rem; padding: 2rem; text-align: center; background: rgba(255, 255, 255, 0.9); border-radius: 15px; max-width: 1200px; margin-left: auto; margin-right: auto;">
    <p style="color: #6b7280; margin: 0;">&copy; 2024 UniVerse. All rights reserved.</p>
</footer>

<style>
/* Articles page specific styles */
.page-header {
    background: var(--white);
    border-radius: 12px;
    padding: 2rem;
    margin-bottom: 2rem;
    text-align: center;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
}

.header-content h1 {
    font-size: 2.5rem;
    color: var(--text-dark);
    margin-bottom: 1rem;
}

.header-description {
    font-size: 1.1rem;
    color: var(--text-light);
    max-width: 600px;
    margin: 0 auto 2rem auto;
    line-height: 1.6;
}

.search-container {
    max-width: 500px;
    margin: 0 auto;
}

.search-input-group {
    display: flex;
    background: var(--light-gray);
    border-radius: 12px;
    overflow: hidden;
    border: 2px solid transparent;
    transition: border-color 0.3s ease;
}

.search-input-group:focus-within {
    border-color: var(--primary-purple);
}

.search-input-group input {
    flex: 1;
    padding: 1rem 1.5rem;
    border: none;
    background: transparent;
    font-size: 1rem;
    outline: none;
}

.search-btn {
    padding: 1rem 1.5rem;
    background: var(--primary-purple);
    color: white;
    border: none;
    cursor: pointer;
    transition: background 0.3s ease;
}

.search-btn:hover {
    background: var(--dark-purple);
}

.category-filter {
    margin-bottom: 2rem;
}

.filter-container {
    display: flex;
    gap: 1rem;
    flex-wrap: wrap;
    justify-content: center;
    background: var(--white);
    padding: 1.5rem;
    border-radius: 12px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
}

.category-btn {
    padding: 0.5rem 1rem;
    background: var(--light-gray);
    color: var(--text-dark);
    text-decoration: none;
    border-radius: 8px;
    font-size: 0.9rem;
    font-weight: 500;
    transition: all 0.3s ease;
}

.category-btn:hover,
.category-btn.active {
    background: var(--primary-purple);
    color: white;
    transform: translateY(-2px);
}

.articles-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
    gap: 2rem;
}

.article-card {
    background: var(--white);
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    transition: all 0.3s ease;
}

.article-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
}

.article-image {
    height: 200px;
    background: var(--light-gray);
    position: relative;
    overflow: hidden;
}

.article-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.article-image.placeholder {
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(45deg, var(--light-gray), #e5e7eb);
    color: var(--text-light);
    font-size: 2rem;
}

.article-content {
    padding: 1.5rem;
}

.article-meta {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1rem;
}

.category-tag {
    background: rgba(107, 70, 193, 0.1);
    color: var(--primary-purple);
    padding: 0.25rem 0.75rem;
    border-radius: 6px;
    font-size: 0.8rem;
    font-weight: 600;
}

.read-time {
    color: var(--text-light);
    font-size: 0.8rem;
}

.article-title {
    margin: 0 0 1rem 0;
    font-size: 1.3rem;
    line-height: 1.4;
}

.article-title a {
    color: var(--text-dark);
    text-decoration: none;
    transition: color 0.3s ease;
}

.article-title a:hover {
    color: var(--primary-purple);
}

.article-excerpt {
    color: var(--text-light);
    line-height: 1.6;
    margin-bottom: 1.5rem;
}

.article-footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1.5rem;
    font-size: 0.85rem;
    color: var(--text-light);
}

.author-info,
.article-date {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.read-more-btn {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    color: var(--primary-purple);
    text-decoration: none;
    font-weight: 600;
    padding: 0.5rem 0;
    transition: all 0.3s ease;
}

.read-more-btn:hover {
    color: var(--dark-purple);
    transform: translateX(3px);
}

.no-articles {
    display: flex;
    align-items: center;
    justify-content: center;
    min-height: 300px;
    background: var(--white);
    border-radius: 12px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
}

.no-articles-content {
    text-align: center;
    color: var(--text-light);
}

.no-articles-content i {
    font-size: 3rem;
    margin-bottom: 1rem;
    color: var(--text-light);
}

.no-articles-content h3 {
    color: var(--text-dark);
    margin-bottom: 0.5rem;
}

@media (max-width: 768px) {
    .header-content h1 {
        font-size: 2rem;
    }
    
    .articles-grid {
        grid-template-columns: 1fr;
        gap: 1.5rem;
    }
    
    .filter-container {
        justify-content: flex-start;
        overflow-x: auto;
        padding: 1rem;
    }
    
    .category-btn {
        white-space: nowrap;
    }
}
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Add staggered animation to article cards
        const articleCards = document.querySelectorAll('.article-card');
        articleCards.forEach((card, index) => {
            card.style.animationDelay = `${index * 0.1}s`;
        });
    });
</script>

</body>
</html>