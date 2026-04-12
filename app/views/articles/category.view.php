<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <?php if($_SESSION['user_type'] === 'undergraduate') ?>    
        <link rel="stylesheet" href="<?= BASE_URL ?>/css/styles.css">

    <?php if($_SESSION['user_type'] === 'school_leaver') ?>    
        <link rel="stylesheet" href="<?= BASE_URL ?>/css/school_leaver_styles.css">

    <link rel="icon" type="image/png" href="<?= BASE_URL ?>/assets/images/U.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <title>Universe - <?= $data['title'] ?></title>
</head>
<body>

    <?php if($_SESSION['user_type'] === 'undergraduate')   
        include __DIR__ . '/../actors/undergraduate/Unavigation.view.php';

    elseif($_SESSION['user_type'] === 'school_leaver')   
        include __DIR__ . '/../actors/students/includes/header2.view.php';
    ?>

<div class="page-container">
    <div class="articles-container">
        <!-- Breadcrumb -->
        <!-- <nav class="breadcrumb"> -->
            <!-- <a href="<?= BASE_URL ?>/uarticles">Articles</a> -->
            <!-- <span class="breadcrumb-separator">/</span> -->
            <!-- <span class="breadcrumb-current"><?= ucfirst(str_replace('-', ' ', $data['category'])) ?></span> -->
            <!-- Category Header -->
        <!-- </nav> -->
        <div class="category-header">
            <h1><?= ucfirst(str_replace('-', ' ', $data['category'])) ?> Articles</h1>
            <p class="category-description">Explore articles in the <?= strtolower(str_replace('-', ' ', $data['category'])) ?> category</p>
        </div>
            
        <!-- Back to Categories -->
        <div class="category-navigation">
            <a href="<?= BASE_URL ?>/uarticles" class="back-to-articles">
                <i class="fas fa-arrow-left"></i> Back to All Articles
            </a>
        </div>

        <!-- Category Articles -->
        <div class="articles-section">
            <?php if (!empty($data['articles'])): ?>
                <div class="articles-grid">
                    <?php foreach($data['articles'] as $article): ?>
                        <article class="article-card">
                            <div class="article-content">
                                <span class="article-category"><?= ucfirst(str_replace('-', ' ', $article['category'])) ?></span>
                                <h3 class="article-title">
                                <a href="<?= BASE_URL ?>/uarticles/viewDetails/<?= $article['article_id'] ?>">
                                    <?= htmlspecialchars($article['title']) ?>
                                </a>
                                </h3>
                                
                                <p class="article-excerpt"><?= htmlspecialchars(substr($article['excerpt'], 0, 150)) ?><?= strlen($article['excerpt']) > 150 ? '...' : '' ?></p>
                                
                                <div class="article-meta">
                                    <div class="article-author">
                                        <span class="author-name">By <?= htmlspecialchars($article['first_name'] . ' ' . $article['last_name']) ?></span>
                                        <span class="article-date"><?= date('M j, Y', strtotime($article['created_at'])) ?></span>
                                    </div>
                                    
                                    <div class="article-stats">
                                        <span class="stat-item">
                                            <span>views</span>
                                            <?= number_format((int)($article['views'] ?? 0)) ?>
                                        </span>
                                        <span class="stat-item">
                                            <span>likes</span>
                                            <?= number_format((int)($article['likes'] ?? 0)) ?>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </article>
                    <?php endforeach; ?>
                        </div>
                        <?php else: ?>
                            <div class="no-articles">
                                <div class="no-articles-icon">
                                    <i class="fas fa-folder-open"></i>
                                </div>
                                <h3>No articles found in this category</h3>
                                <p>Check back later for new content, or explore other categories.</p>
                                <a href="<?= BASE_URL ?>/uarticles" class="btn btn-primary">Browse All Articles</a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../layout/footer.php'; ?>

<style>
/* Simple, clean styling */
 :root {
        --primary-purple: #6b46c1;
        --secondary-purple: #8b5cf6;
        --light-purple: #a78bfa;
        --dark-purple: #553c9a;
        --background:#a78bfa45;
        
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

.page-container {
    min-height: 100vh;
    background-color: var(--background);
    padding: 2rem 0;
}

.articles-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 1rem;
}

/* Category Header */
.category-header {
    text-align: center;
    margin-bottom: 2rem;
    margin-top: 5rem;
    padding: 2rem;
    background: white;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.category-header h1 {
    font-size: 2.5rem;
    color: #1f2937;
    margin-bottom: 0.5rem;
    font-weight: 700;
}

.category-description {
    font-size: 1.1rem;
    color: #6b7280;
    margin: 0;
}

/* Navigation */
.category-navigation {
    margin-bottom: 2rem;
}

.back-to-articles {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    color: #6b46c1;
    text-decoration: none;
    font-weight: 500;
    padding: 0.75rem 1rem;
    background: white;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    transition: all 0.2s ease;
}

.back-to-articles:hover {
    background: #6b46c1;
    color: white;
    transform: translateY(-1px);
}

/* Articles Section */
.articles-section {
    margin-bottom: 3rem;
}

.articles-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
    gap: 2rem;
}

/* Article Cards */
.article-card {
    background: white;
    border-radius: 12px;
    box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    overflow: hidden;
    transition: all 0.2s ease;
}

.article-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 15px rgba(0,0,0,0.15);
}

.article-content {
    padding: 1.5rem;
}

.article-category {
    display: inline-block;
    background: #6b46c1;
    color: white;
    padding: 0.2rem 0.7rem;
    border-radius: 20px;
    font-size: 0.78rem;
    font-weight: 500;
    margin-bottom: 0.75rem;
}

.article-title {
    margin: 0 0 1rem 0;
    font-size: 1.25rem;
    font-weight: 600;
    line-height: 1.4;
}

.article-title a {
    color: #1f2937;
    text-decoration: none;
}

.article-title a:hover {
    color: #6b46c1;
}

.article-excerpt {
    color: #6b7280;
    line-height: 1.6;
    margin-bottom: 1.5rem;
}

.article-meta {
    border-top: 1px solid #e5e7eb;
    padding-top: 1rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 1rem;
}

.article-author {
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
}

.author-name {
    font-weight: 500;
    color: #374151;
    font-size: 0.9rem;
}

.article-date {
    font-size: 0.8rem;
    color: #6b7280;
}

.article-stats {
    display: flex;
    gap: 1rem;
}

.stat-item {
    display: flex;
    align-items: center;
    gap: 0.25rem;
    font-size: 0.8rem;
    color: #6b7280;
}

.stat-item i {
    font-size: 0.75rem;
}

/* No Articles State */
.no-articles {
    text-align: center;
    padding: 4rem 2rem;
    background: white;
    border-radius: 12px;
    box-shadow: 0 4px 6px rgba(0,0,0,0.1);
}

.no-articles-icon {
    font-size: 4rem;
    color: #d1d5db;
    margin-bottom: 1rem;
}

.no-articles h3 {
    font-size: 1.5rem;
    color: #374151;
    margin-bottom: 0.5rem;
}

.no-articles p {
    color: #6b7280;
    margin-bottom: 2rem;
    line-height: 1.6;
}

.btn {
    display: inline-block;
    padding: 0.75rem 2rem;
    text-decoration: none;
    border-radius: 8px;
    font-weight: 500;
    transition: all 0.2s ease;
}

.btn-primary {
    background: #6b46c1;
    color: white;
}

.btn-primary:hover {
    background: #553c9a;
    transform: translateY(-1px);
}

/* Responsive Design */
@media (max-width: 768px) {
    .page-container {
        padding: 1rem 0;
    }
    
    .articles-container {
        padding: 0 0.5rem;
    }
    
    .category-header {
        padding: 1.5rem 1rem;
    }
    
    .category-header h1 {
        font-size: 2rem;
    }
    
    .articles-grid {
        grid-template-columns: 1fr;
        gap: 1.5rem;
    }
    
    .article-meta {
        flex-direction: column;
        align-items: flex-start;
    }
    
    .article-stats {
        align-self: stretch;
        justify-content: space-around;
    }
}

@media (max-width: 480px) {
    .category-header h1 {
        font-size: 1.5rem;
    }
    
    .article-content {
        padding: 1rem;
    }
    
    .no-articles {
        padding: 2rem 1rem;
    }
}
</style>

</body>
</html>