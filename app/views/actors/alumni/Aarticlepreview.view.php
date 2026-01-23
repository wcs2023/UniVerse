<?php
// Define constants if not already defined
if (!defined('BASE_URL')) {
    define('BASE_URL', 'http://localhost/UniVerse/public');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($article['title'] ?? 'Article') ?> - UniVerse</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --primary-purple: #7c3aed;
            --purple-hover: #6d28d9;
            --text-dark: #1f2937;
            --text-light: #6b7280;
            --bg-light: #f3f4f6;
            --bg-white: #ffffff;
            --border-color: #e5e7eb;
        }
        
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            background-color: var(--bg-light);
            color: var(--text-dark);
            line-height: 1.6;
            margin: 0;
        }

        .article-container {
            max-width: 900px;
            margin: 2rem auto;
            padding: 0 2rem;
        }

        .article-header-actions {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
        }

        .back-btn {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            color: var(--primary-purple);
            text-decoration: none;
            font-weight: 600;
            padding: 0.5rem 1rem;
            border-radius: 8px;
            transition: all 0.3s;
        }

        .back-btn:hover {
            background-color: rgba(124, 58, 237, 0.1);
        }

        .action-buttons {
            display: flex;
            gap: 1rem;
        }

        .btn {
            padding: 0.625rem 1.25rem;
            border-radius: 8px;
            font-weight: 600;
            text-decoration: none;
            border: none;
            cursor: pointer;
            transition: all 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-primary {
            background-color: var(--primary-purple);
            color: white;
        }

        .btn-primary:hover {
            background-color: var(--purple-hover);
            transform: translateY(-2px);
        }

        .btn-secondary {
            background-color: white;
            color: var(--text-dark);
            border: 2px solid var(--border-color);
        }

        .btn-secondary:hover {
            border-color: var(--primary-purple);
            color: var(--primary-purple);
        }

        .article-card {
            background: white;
            border-radius: 16px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .article-hero {
            padding: 3rem;
            border-bottom: 1px solid var(--border-color);
        }

        .status-badge {
            display: inline-block;
            padding: 0.375rem 0.875rem;
            border-radius: 6px;
            font-size: 0.875rem;
            font-weight: 600;
            margin-bottom: 1rem;
        }

        .status-published {
            background-color: #d1fae5;
            color: #065f46;
        }

        .status-draft {
            background-color: #fef3c7;
            color: #92400e;
        }

        .article-title {
            font-size: 2.5rem;
            font-weight: 800;
            color: var(--text-dark);
            margin-bottom: 1rem;
            line-height: 1.2;
        }

        .article-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 1.5rem;
            color: var(--text-light);
            font-size: 0.95rem;
        }

        .meta-item {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .meta-icon {
            font-size: 1.1rem;
        }

        .article-content {
            padding: 3rem;
        }

        .article-body {
            font-size: 1.125rem;
            line-height: 1.8;
            color: var(--text-dark);
        }

        .article-body h1,
        .article-body h2,
        .article-body h3,
        .article-body h4,
        .article-body h5,
        .article-body h6 {
            margin: 2rem 0 1rem;
            font-weight: 700;
            color: var(--text-dark);
        }

        .article-body h1 { font-size: 2rem; }
        .article-body h2 { font-size: 1.75rem; }
        .article-body h3 { font-size: 1.5rem; }

        .article-body p {
            margin-bottom: 1.5rem;
        }

        .article-body ul,
        .article-body ol {
            margin: 1.5rem 0;
            padding-left: 2rem;
        }

        .article-body li {
            margin-bottom: 0.75rem;
        }

        .article-body a {
            color: var(--primary-purple);
            text-decoration: none;
            border-bottom: 1px solid transparent;
            transition: border-color 0.3s;
        }

        .article-body a:hover {
            border-bottom-color: var(--primary-purple);
        }

        .article-body blockquote {
            border-left: 4px solid var(--primary-purple);
            padding-left: 1.5rem;
            margin: 1.5rem 0;
            font-style: italic;
            color: var(--text-light);
        }

        .article-body code {
            background-color: #f3f4f6;
            padding: 0.2rem 0.4rem;
            border-radius: 4px;
            font-family: 'Courier New', monospace;
            font-size: 0.9em;
        }

        .article-body pre {
            background-color: #1f2937;
            color: #f3f4f6;
            padding: 1.5rem;
            border-radius: 8px;
            overflow-x: auto;
            margin: 1.5rem 0;
        }

        .article-body pre code {
            background: none;
            padding: 0;
            color: inherit;
        }

        .article-body img {
            max-width: 100%;
            height: auto;
            border-radius: 8px;
            margin: 1.5rem 0;
        }

        .article-stats {
            display: flex;
            gap: 2rem;
            padding: 2rem 3rem;
            border-top: 1px solid var(--border-color);
            background-color: #fafbfc;
        }

        .stat-item {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: var(--text-light);
            font-weight: 600;
        }

        .stat-icon {
            font-size: 1.25rem;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .article-container {
                padding: 0 1rem;
                margin: 1rem auto;
            }

            .article-header-actions {
                flex-direction: column;
                align-items: stretch;
                gap: 1rem;
            }

            .action-buttons {
                justify-content: stretch;
            }

            .btn {
                flex: 1;
                justify-content: center;
            }

            .article-hero {
                padding: 2rem 1.5rem;
            }

            .article-title {
                font-size: 1.75rem;
            }

            .article-content {
                padding: 2rem 1.5rem;
            }

            .article-body {
                font-size: 1rem;
            }

            .article-stats {
                padding: 1.5rem;
            }
        }

        /* Animation */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .article-card {
            animation: fadeIn 0.5s ease;
        }
    </style>
</head>
<body>
    <?php 
    // Include navigation
    $navFile = dirname(__FILE__) . '/Anavbar.php';
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
                    ✏️ Edit Article
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
                        <span class="meta-icon">📅</span>
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
                        <span class="meta-icon">🏷️</span>
                        <span><?= htmlspecialchars($article['category']) ?></span>
                    </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Article Content -->
            <div class="article-content">
                <div class="article-body">
                    <?= $article['content'] ?>
                </div>
            </div>

            <!-- Article Stats -->
            <div class="article-stats">
                <div class="stat-item">
                    <span class="stat-icon">👁️</span>
                    <span><?= number_format($article['views'] ?? 0) ?> views</span>
                </div>
                <div class="stat-item">
                    <span class="stat-icon">👍</span>
                    <span><?= number_format($article['likes'] ?? 0) ?> likes</span>
                </div>
            </div>
        </div>
    </div>

    <?php 
    // Include footer
    include dirname(dirname(dirname(__FILE__))) . '/layout/footer.php';
    ?>
</body>
</html>
