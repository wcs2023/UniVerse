<?php
// BASE_URL is already available from the controller
if (!defined('URLROOT')) {
    define('URLROOT', BASE_URL);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Moderate Articles - Admin Panel</title>
    <link rel="stylesheet" href="<?= URLROOT ?>/css/admin.css">
</head>
<body>
    <div class="admin-layout">
        <!-- Sidebar -->
        <?php include __DIR__ . '/components/sidebar.php'; ?>
        
        <!-- Main Content -->
        <div class="admin-main">
            <!-- Header -->
            <div class="admin-header">
                <h1>📝 Moderate Articles</h1>
                <div class="admin-header-actions">
                    <div class="admin-user">
                        <div class="admin-user-avatar">
                            <?= strtoupper(substr($_SESSION['first_name'] ?? 'A', 0, 1)) ?>
                        </div>
                        <div class="admin-user-info">
                            <span class="admin-user-name"><?= htmlspecialchars($_SESSION['first_name'] ?? 'Admin') ?></span>
                            <span class="admin-user-role">Administrator</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Filter -->
            <div class="content-card" style="margin-bottom: 2rem;">
                <div class="content-card-body">
                    <div class="search-bar">
                        <input type="text" class="search-input" placeholder="Search articles..." id="searchInput">
                        <select class="form-select" style="width: auto;" id="statusFilter">
                            <option value="">All Status</option>
                            <option value="published">Published</option>
                            <option value="draft">Draft</option>
                        </select>
                        <select class="form-select" style="width: auto;" id="categoryFilter">
                            <option value="">All Categories</option>
                            <option value="Career Advice">Career Advice</option>
                            <option value="Industry Insights">Industry Insights</option>
                            <option value="Success Stories">Success Stories</option>
                        </select>
                    </div>
                </div>
            </div>
            
            <!-- Articles Table -->
            <div class="content-card">
                <div class="content-card-header">
                    <h2 class="content-card-title">All Articles</h2>
                </div>
                <div class="content-card-body">
                    <?php if (isset($data['articles']) && count($data['articles']) > 0): ?>
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Title</th>
                                    <th>Author</th>
                                    <th>Category</th>
                                    <th>Status</th>
                                    <th>Views</th>
                                    <th>Published</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($data['articles'] as $article): ?>
                                    <tr>
                                        <td>#<?= $article['article_id'] ?></td>
                                        <td>
                                            <strong><?= htmlspecialchars($article['title']) ?></strong>
                                        </td>
                                        <td><?= htmlspecialchars($article['author_name'] ?? 'Unknown') ?></td>
                                        <td><?= htmlspecialchars($article['category'] ?? 'Uncategorized') ?></td>
                                        <td>
                                            <span class="status-badge status-<?= $article['status'] ?>">
                                                <?= ucfirst($article['status']) ?>
                                            </span>
                                        </td>
                                        <td><?= number_format($article['views'] ?? 0) ?></td>
                                        <td><?= $article['published_at'] ? date('M d, Y', strtotime($article['published_at'])) : 'N/A' ?></td>
                                        <td>
                                            <div style="display: flex; gap: 0.5rem;">
                                                <button class="btn btn-sm btn-outline" onclick="viewArticle(<?= $article['article_id'] ?>)">
                                                    👁️ View
                                                </button>
                                                <button class="btn btn-sm btn-danger" onclick="deleteArticle(<?= $article['article_id'] ?>)">
                                                    🗑️ Delete
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php else: ?>
                        <div class="empty-state">
                            <div class="empty-icon">📝</div>
                            <h3>No Articles Found</h3>
                            <p>There are no articles to moderate.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        function viewArticle(articleId) {
            window.open('<?= URLROOT ?>/articles/view/' + articleId, '_blank');
        }
        
        function deleteArticle(articleId) {
            if (confirm('Are you sure you want to delete this article? This action cannot be undone.')) {
                fetch('<?= URLROOT ?>/admin/deleteArticle', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ article_id: articleId })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Article deleted successfully!');
                        location.reload();
                    } else {
                        alert('Error deleting article: ' + data.message);
                    }
                })
                .catch(error => {
                    alert('Error: ' + error.message);
                });
            }
        }
    </script>
</body>
</html>
