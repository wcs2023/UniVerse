<?php
// Define constants if not already defined (for direct access)
if (!defined('APPROOT')) {
    define('APPROOT', dirname(dirname(dirname(dirname(__FILE__)))));
}
if (!defined('URLROOT')) {
    define('URLROOT', 'http://localhost/UniVerse/public');
}
if (!defined('BASE_URL')) {
    define('BASE_URL', 'http://localhost/UniVerse/public');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
            --draft-yellow: #fef3c7;
            --draft-yellow-text: #92400e;
            --published-green: #d1fae5;
            --published-green-text: #065f46;
        }
        
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            background-color: var(--bg-light);
            color: var(--text-dark);
            line-height: 1.6;
            margin: 0;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 2rem;
        }

        /* Page Header */
        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 3rem;
        }

        .page-title {
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--text-dark);
        }

        .btn-create {
            background-color: var(--primary-purple);
            color: white;
            padding: 0.875rem 1.75rem;
            border-radius: 12px;
            font-weight: 600;
            font-size: 1rem;
            border: none;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.3s;
            text-decoration: none;
        }

        .btn-create:hover {
            background-color: var(--purple-hover);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(124, 58, 237, 0.3);
        }

        .btn-icon {
            font-size: 1.25rem;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* Section */
        .section {
            margin-bottom: 3rem;
        }

        .section-title {
            font-size: 1.75rem;
            font-weight: 700;
            color: var(--text-dark);
            margin-bottom: 1.5rem;
        }

        /* Articles Grid */
        .articles-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 1.5rem;
        }

        /* Article Card */
        .article-card {
            background: var(--bg-white);
            border-radius: 12px;
            padding: 1.5rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            transition: all 0.3s;
            position: relative;
        }

        .article-card:hover {
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            transform: translateY(-2px);
        }

        .article-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 1rem;
        }

        .status-badge {
            padding: 0.375rem 0.875rem;
            border-radius: 6px;
            font-size: 0.875rem;
            font-weight: 600;
            display: inline-block;
        }

        .status-draft {
            background-color: var(--draft-yellow);
            color: var(--draft-yellow-text);
        }

        .status-published {
            background-color: var(--published-green);
            color: var(--published-green-text);
        }

        .article-actions {
            display: flex;
            gap: 0.5rem;
        }

        .btn-icon-action {
            background: none;
            border: none;
            cursor: pointer;
            color: var(--text-light);
            font-size: 1.25rem;
            padding: 0.25rem;
            transition: color 0.3s;
        }

        .btn-icon-action:hover {
            color: var(--text-dark);
        }

        .btn-icon-action.delete:hover {
            color: #ef4444;
        }

        .article-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: var(--text-dark);
            margin-bottom: 0.5rem;
            line-height: 1.4;
        }

        .article-meta {
            font-size: 0.9rem;
            color: var(--text-light);
            margin-bottom: 1rem;
        }

        .article-stats {
            display: flex;
            align-items: center;
            gap: 1.5rem;
            padding-top: 1rem;
            border-top: 1px solid var(--border-color);
        }

        .stat-item {
            display: flex;
            align-items: center;
            gap: 0.375rem;
            color: var(--text-light);
            font-size: 0.9rem;
        }

        .stat-icon {
            font-size: 1rem;
        }

        /* Empty State */
        .empty-state {
            background: var(--bg-white);
            border: 2px dashed var(--border-color);
            border-radius: 12px;
            padding: 4rem 2rem;
            text-align: center;
            color: var(--text-light);
        }

        .empty-icon {
            font-size: 4rem;
            color: var(--border-color);
            margin-bottom: 1rem;
        }

        .empty-state h3 {
            font-size: 1.25rem;
            font-weight: 600;
            color: var(--text-dark);
            margin-bottom: 0.5rem;
        }

        .empty-state p {
            font-size: 1rem;
            color: var(--text-light);
        }

        /* Responsive */
        @media (max-width: 768px) {
            .container {
                padding: 1rem;
            }

            .page-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 1rem;
            }

            .page-title {
                font-size: 2rem;
            }

            .articles-grid {
                grid-template-columns: 1fr;
            }

            .btn-create {
                width: 100%;
                justify-content: center;
            }
        }

        /* Animation */
        @keyframes fadeInUp {
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
            animation: fadeInUp 0.5s ease forwards;
            opacity: 0;
        }
    </style>
</head>
<body>
    <?php 
    // Include navigation
    $navFile = APPROOT . '/views/actors/alumni/Anavbar.php';
    if (file_exists($navFile)) {
        include $navFile;
    }
    ?>
    
    <div class="container">
        <!-- Page Header -->
        <div class="page-header">
            <h1 class="page-title">Manage My Articles</h1>
            <a href="<?= BASE_URL ?>/aarticles/create" class="btn-create">
                <!-- <span class="btn-icon">⊕</span> -->
                <span>Create New Article</span>
            </a>
        </div>

        <!-- Drafts Section -->
        <div class="section">
            <h2 class="section-title">Drafts</h2>
            
            <div class="articles-grid">
                <?php if (isset($data['drafts']) && count($data['drafts']) > 0): ?>
                    <?php foreach ($data['drafts'] as $index => $article): ?>
                        <div class="article-card" style="animation-delay: <?= $index * 0.1 ?>s">
                            <div class="article-header">
                                <span class="status-badge status-draft">Draft</span>
                                <div class="article-actions">
                                    <a href="<?= BASE_URL ?>/aarticles/edit/<?= $article['article_id'] ?>" class="btn-icon-action" title="Edit">
                                        ✏️
                                    </a>
                                    <button onclick="deleteArticle(<?= $article['article_id'] ?>, 'draft')" class="btn-icon-action delete" title="Delete">
                                        🗑️
                                    </button>
                                </div>
                            </div>
                            
                            <h3 class="article-title"><?= htmlspecialchars($article['title']) ?></h3>
                            <p class="article-meta">Last saved: <?= date('M d, Y', strtotime($article['updated_at'])) ?></p>
                            
                            <div class="article-stats">
                                <div class="stat-item">
                                    <span class="stat-icon">👁️</span>
                                    <span><?= $article['views'] ?? 0 ?></span>
                                </div>
                                <div class="stat-item">
                                    <span class="stat-icon">👍</span>
                                    <span><?= $article['likes'] ?? 0 ?></span>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="empty-state">
                        <div class="empty-icon">⚠️</div>
                        <h3>No Drafts</h3>
                        <p>You don't have any saved drafts. Start a new article!</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Published Section -->
        <div class="section">
            <h2 class="section-title">Published</h2>
            
            <!-- Debug Info (Remove this after testing) -->
            <?php if (isset($_GET['debug'])): ?>
                <div style="background: #fff; padding: 1rem; margin-bottom: 1rem; border: 2px solid #8b5cf6; border-radius: 8px;">
                    <strong>Debug Info:</strong><br>
                    Published Count: <?= isset($data['published']) ? count($data['published']) : 'not set' ?><br>
                    Drafts Count: <?= isset($data['drafts']) ? count($data['drafts']) : 'not set' ?><br>
                    <?php if (isset($data['published'])): ?>
                        <pre><?= print_r($data['published'], true) ?></pre>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
            
            <div class="articles-grid">
                <?php if (isset($data['published']) && count($data['published']) > 0): ?>
                    <?php foreach ($data['published'] as $index => $article): ?>
                        <div class="article-card" style="animation-delay: <?= ($index + (count($data['drafts'] ?? []))) * 0.1 ?>s">
                            <div class="article-header">
                                <span class="status-badge status-published">Published</span>
                                <div class="article-actions">
                                    <a href="<?= BASE_URL ?>/aarticles/preview/<?= $article['article_id'] ?>" class="btn-icon-action" title="View Article">
                                        view
                                    </a>
                                    <a href="<?= BASE_URL ?>/aarticles/edit/<?= $article['article_id'] ?>" class="btn-icon-action" title="Edit">
                                        edit
                                    </a>
                                    <button onclick="deleteArticle(<?= $article['article_id'] ?>, 'published')" class="btn-icon-action delete" title="Delete">
                                        🗑️
                                    </button>
                                </div>
                            </div>
                            
                            <h3 class="article-title"><?= htmlspecialchars($article['title']) ?></h3>
                            <p class="article-meta">Published on: <?= date('M d, Y', strtotime($article['published_at'])) ?></p>
                            
                            <div class="article-stats">
                                <div class="stat-item">
                                    <span class="stat-icon">views</span>
                                    <span><?= number_format($article['views'] ?? 0) ?></span>
                                </div>
                                <div class="stat-item">
                                    <span class="stat-icon">likes</span>
                                    <span><?= number_format($article['likes'] ?? 0) ?></span>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="empty-state">
                        <div class="empty-icon">📝</div>
                        <h3>No Published Articles</h3>
                        <p>You haven't published any articles yet. Create your first article!</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script>
        function deleteArticle(articleId, status) {
            const confirmMessage = status === 'draft' 
                ? 'Are you sure you want to delete this draft? This action cannot be undone.'
                : 'Are you sure you want to delete this published article? This will remove it from public view.';
            
            if (confirm(confirmMessage)) {
                // Send delete request
                fetch('<?= BASE_URL ?>/aarticles/delete/' + articleId, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ article_id: articleId })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Reload page to show updated list
                        location.reload();
                    } else {
                        alert('Error deleting article. Please try again.');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error deleting article. Please try again.');
                });
            }
        }

        // Add stagger animation to cards
        document.addEventListener('DOMContentLoaded', function() {
            const cards = document.querySelectorAll('.article-card');
            cards.forEach((card, index) => {
                card.style.animationDelay = (index * 0.1) + 's';
            });
        });
    </script>

    <?php 
    // Include footer
    include __DIR__ . '/../../layout/footer.php';
    ?>
</body>
</html>

