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
    <title>Manage Articles - Admin Panel</title>
    <link rel="stylesheet" href="<?= URLROOT ?>/css/admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <div class="admin-layout">
        <!-- Sidebar -->
        <?php include __DIR__ . '/components/sidebar.php'; ?>
        
        <!-- Main Content -->
        <div class="admin-main">
            <!-- Header -->
            <div class="admin-header">
                <h1>📝 Manage Articles</h1>
                <div class="admin-header-actions">
                    <a href="<?= URLROOT ?>/admin/createArticle" class="btn btn-primary">
                        <i class="fas fa-plus"></i> New Article
                    </a>
                </div>
            </div>
            
            <!-- Success/Error Messages -->
            <?php if (isset($_GET['success'])): ?>
                <div class="alert alert-success">
                    <?php
                    switch ($_GET['success']) {
                        case 'created':
                            echo '✅ Article created successfully';
                            break;
                        case 'updated':
                            echo '✅ Article updated successfully';
                            break;
                        case 'deleted':
                            echo '✅ Article deleted successfully';
                            break;
                        case 'status_updated':
                            echo '✅ Article status updated successfully';
                            break;
                        case 'bulk_deleted':
                            $count = $_GET['count'] ?? 0;
                            echo "✅ {$count} article(s) deleted successfully";
                            break;
                        default:
                            echo '✅ Action completed successfully';
                    }
                    ?>
                </div>
            <?php endif; ?>
            
            <?php if (isset($_GET['error'])): ?>
                <div class="alert alert-error">
                    <?php
                    switch ($_GET['error']) {
                        case 'missing_id':
                            echo '❌ Article ID is required';
                            break;
                        case 'delete_failed':
                            echo '❌ Failed to delete article';
                            break;
                        case 'not_found':
                            echo '❌ Article not found';
                            break;
                        case 'no_selection':
                            echo '❌ No articles selected';
                            break;
                        default:
                            echo '❌ An error occurred';
                    }
                    ?>
                </div>
            <?php endif; ?>
            
            <!-- Filters and Search -->
            <div class="content-card" style="margin-bottom: 1.5rem;">
                <div class="content-card-body" style="padding: 1rem;">
                    <form method="GET" action="<?= URLROOT ?>/admin/articles" class="search-filter-form">
                        <div class="search-wrapper">
                            <i class="fas fa-search search-icon"></i>
                            <input 
                                type="text" 
                                name="search" 
                                class="search-input-modern" 
                                placeholder="Search articles by title, content..." 
                                value="<?= htmlspecialchars($searchQuery ?? '') ?>"
                            >
                        </div>
                        
                        <select name="status" class="filter-select">
                            <option value="all" <?= (!isset($statusFilter) || $statusFilter === 'all') ? 'selected' : '' ?>>All Status</option>
                            <option value="published" <?= (isset($statusFilter) && $statusFilter === 'published') ? 'selected' : '' ?>>Published</option>
                            <option value="draft" <?= (isset($statusFilter) && $statusFilter === 'draft') ? 'selected' : '' ?>>Draft</option>
                            <option value="archived" <?= (isset($statusFilter) && $statusFilter === 'archived') ? 'selected' : '' ?>>Archived</option>
                        </select>
                        
                        <div class="search-actions">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-search"></i> Search
                            </button>
                            
                            <a href="<?= URLROOT ?>/admin/articles" class="btn btn-outline">
                                <i class="fas fa-redo"></i> Reset
                            </a>
                        </div>
                    </form>
                </div>
            </div>
            
            <!-- Articles Table -->
            <div class="content-card">
                <div class="content-card-header">
                    <h2 class="content-card-title">All Articles (<?= count($articles ?? []) ?>)</h2>
                    <div style="display: flex; gap: 0.5rem;">
                        <button onclick="bulkDelete()" class="btn btn-danger btn-sm" id="bulkDeleteBtn" style="display: none;">
                            <i class="fas fa-trash"></i> Delete Selected
                        </button>
                    </div>
                </div>
                <div class="content-card-body" style="padding: 0; overflow-x: auto;">
                    <?php if (!empty($articles)): ?>
                        <form id="bulkForm" method="POST" action="<?= URLROOT ?>/admin/bulkDeleteArticles">
                            <table class="data-table">
                                <thead>
                                    <tr>
                                        <th style="width: 50px;">
                                            <input type="checkbox" id="selectAll" onclick="toggleSelectAll(this)">
                                        </th>
                                        <th>ID</th>
                                        <th>Title</th>
                                        <th>Author</th>
                                        <th>Status</th>
                                        <th>Category</th>
                                        <th>Created</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($articles as $article): ?>
                                        <tr>
                                            <td>
                                                <input type="checkbox" name="article_ids[]" value="<?= $article['article_id'] ?>" class="article-checkbox" onchange="toggleBulkDelete()">
                                            </td>
                                            <td>#<?= htmlspecialchars($article['article_id']) ?></td>
                                            <td>
                                                <strong><?= htmlspecialchars(substr($article['title'], 0, 50)) ?><?= strlen($article['title']) > 50 ? '...' : '' ?></strong>
                                            </td>
                                            <td><?= htmlspecialchars($article['author_name'] ?? 'Unknown') ?></td>
                                            <td>
                                                <span class="status-badge status-<?= htmlspecialchars($article['status'] ?? 'draft') ?>">
                                                    <?= ucfirst(htmlspecialchars($article['status'] ?? 'draft')) ?>
                                                </span>
                                            </td>
                                            <td><?= htmlspecialchars($article['category'] ?? 'N/A') ?></td>
                                            <td><?= date('M d, Y', strtotime($article['created_at'])) ?></td>
                                            <td>
                                                <div class="action-buttons">
                                                    <button 
                                                        class="btn btn-sm btn-outline" 
                                                        onclick="viewArticle(<?= $article['article_id'] ?>)"
                                                        title="View Details"
                                                        type="button"
                                                    >
                                                        <i class="fas fa-eye"></i>
                                                    </button>
                                                    <a 
                                                        href="<?= URLROOT ?>/admin/editArticle/<?= $article['article_id'] ?>"
                                                        class="btn btn-sm btn-primary" 
                                                        title="Edit Article"
                                                    >
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <button 
                                                        class="btn btn-sm btn-danger" 
                                                        onclick="confirmDelete(<?= $article['article_id'] ?>, '<?= htmlspecialchars(addslashes($article['title'])) ?>')"
                                                        title="Delete Article"
                                                        type="button"
                                                    >
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </form>
                    <?php else: ?>
                        <div class="empty-state">
                            <div class="empty-icon">📝</div>
                            <h3>No Articles Found</h3>
                            <p>There are no articles matching your criteria.</p>
                            <a href="<?= URLROOT ?>/admin/createArticle" class="btn btn-primary" style="margin-top: 1rem;">
                                <i class="fas fa-plus"></i> Create Your First Article
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Article Details Modal -->
    <div id="articleModal" class="modal">
        <div class="modal-content" style="max-width: 800px;">
            <div class="modal-header">
                <h2>Article Details</h2>
                <span class="modal-close" onclick="closeModal()">&times;</span>
            </div>
            <div class="modal-body" id="articleDetailsContent">
                <div class="loading">Loading...</div>
            </div>
        </div>
    </div>
    
    <script>
        // View article details
        function viewArticle(articleId) {
            const modal = document.getElementById('articleModal');
            const content = document.getElementById('articleDetailsContent');
            
            modal.style.display = 'block';
            content.innerHTML = '<div class="loading"><i class="fas fa-spinner fa-spin"></i> Loading...</div>';
            
            fetch('<?= URLROOT ?>/admin/viewArticle/' + articleId)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const article = data.article;
                        content.innerHTML = `
                            <div class="article-details">
                                <div class="article-detail-row">
                                    <span class="detail-label">Article ID:</span>
                                    <span class="detail-value">#${article.article_id}</span>
                                </div>
                                <div class="article-detail-row">
                                    <span class="detail-label">Title:</span>
                                    <span class="detail-value"><strong>${article.title}</strong></span>
                                </div>
                                <div class="article-detail-row">
                                    <span class="detail-label">Author:</span>
                                    <span class="detail-value">${article.author_name || 'Unknown'}</span>
                                </div>
                                <div class="article-detail-row">
                                    <span class="detail-label">Status:</span>
                                    <span class="detail-value"><span class="status-badge status-${article.status}">${article.status}</span></span>
                                </div>
                                <div class="article-detail-row">
                                    <span class="detail-label">Category:</span>
                                    <span class="detail-value">${article.category || 'N/A'}</span>
                                </div>
                                <div class="article-detail-row">
                                    <span class="detail-label">Tags:</span>
                                    <span class="detail-value">${article.tags || 'N/A'}</span>
                                </div>
                                <div class="article-detail-row">
                                    <span class="detail-label">Created:</span>
                                    <span class="detail-value">${new Date(article.created_at).toLocaleString()}</span>
                                </div>
                                <div class="article-detail-row">
                                    <span class="detail-label">Last Updated:</span>
                                    <span class="detail-value">${new Date(article.updated_at).toLocaleString()}</span>
                                </div>
                                <div class="article-content-preview">
                                    <h4>Content Preview:</h4>
                                    <div class="content-box">${article.content.substring(0, 500)}${article.content.length > 500 ? '...' : ''}</div>
                                </div>
                                <div style="margin-top: 1rem; display: flex; gap: 0.5rem;">
                                    <a href="<?= URLROOT ?>/admin/editArticle/${article.article_id}" class="btn btn-primary">
                                        <i class="fas fa-edit"></i> Edit Article
                                    </a>
                                    <button onclick="closeModal()" class="btn btn-outline">Close</button>
                                </div>
                            </div>
                        `;
                    } else {
                        content.innerHTML = `<div class="error-message">❌ ${data.message}</div>`;
                    }
                })
                .catch(error => {
                    content.innerHTML = '<div class="error-message">❌ Error loading article details</div>';
                    console.error('Error:', error);
                });
        }
        
        // Close modal
        function closeModal() {
            document.getElementById('articleModal').style.display = 'none';
        }
        
        // Close modal when clicking outside
        window.onclick = function(event) {
            const modal = document.getElementById('articleModal');
            if (event.target == modal) {
                modal.style.display = 'none';
            }
        }
        
        // Confirm delete
        function confirmDelete(articleId, articleTitle) {
            if (confirm(`Are you sure you want to delete the article "${articleTitle}"?\n\nThis action cannot be undone.`)) {
                window.location.href = '<?= URLROOT ?>/admin/deleteArticle/' + articleId;
            }
        }
        
        // Toggle select all checkboxes
        function toggleSelectAll(checkbox) {
            const checkboxes = document.querySelectorAll('.article-checkbox');
            checkboxes.forEach(cb => cb.checked = checkbox.checked);
            toggleBulkDelete();
        }
        
        // Toggle bulk delete button visibility
        function toggleBulkDelete() {
            const checkboxes = document.querySelectorAll('.article-checkbox:checked');
            const bulkBtn = document.getElementById('bulkDeleteBtn');
            bulkBtn.style.display = checkboxes.length > 0 ? 'block' : 'none';
        }
        
        // Bulk delete
        function bulkDelete() {
            const checkboxes = document.querySelectorAll('.article-checkbox:checked');
            if (checkboxes.length === 0) {
                alert('Please select at least one article to delete');
                return;
            }
            
            if (confirm(`Are you sure you want to delete ${checkboxes.length} article(s)?\n\nThis action cannot be undone.`)) {
                document.getElementById('bulkForm').submit();
            }
        }
        
        // Auto-hide alerts after 5 seconds
        setTimeout(() => {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                alert.style.transition = 'opacity 0.5s';
                alert.style.opacity = '0';
                setTimeout(() => alert.remove(), 500);
            });
        }, 5000);
    </script>
    
    <style>
        .article-details {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }
        
        .article-detail-row {
            display: flex;
            justify-content: space-between;
            padding: 0.75rem;
            background: #f9fafb;
            border-radius: 8px;
        }
        
        .article-content-preview {
            margin-top: 1rem;
        }
        
        .article-content-preview h4 {
            margin-bottom: 0.5rem;
            color: #374151;
        }
        
        .content-box {
            padding: 1rem;
            background: #f9fafb;
            border-radius: 8px;
            border: 1px solid #e5e7eb;
            max-height: 200px;
            overflow-y: auto;
            line-height: 1.6;
        }
        
        .status-published {
            background: #dcfce7;
            color: #059669;
        }
        
        .status-draft {
            background: #fef3c7;
            color: #d97706;
        }
        
        .status-archived {
            background: #e5e7eb;
            color: #6b7280;
        }
        
        /* Modern Search Bar Styles */
        .search-filter-form {
            display: flex;
            align-items: center;
            gap: 1rem;
            flex-wrap: wrap;
        }
        
        .search-wrapper {
            position: relative;
            flex: 1;
            min-width: 250px;
        }
        
        .search-icon {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: #9ca3af;
            font-size: 0.95rem;
            pointer-events: none;
        }
        
        .search-input-modern {
            width: 100%;
            padding: 0.75rem 1rem 0.75rem 2.75rem;
            border: 1px solid #d1d5db;
            border-radius: 10px;
            font-size: 0.95rem;
            transition: all 0.2s ease;
            background: #f9fafb;
        }
        
        .search-input-modern:focus {
            outline: none;
            border-color: #6b46c1;
            background: white;
            box-shadow: 0 0 0 3px rgba(107, 70, 193, 0.1);
        }
        
        .search-input-modern::placeholder {
            color: #9ca3af;
        }
        
        .filter-select {
            padding: 0.75rem 1rem;
            border: 1px solid #d1d5db;
            border-radius: 10px;
            font-size: 0.95rem;
            background: #f9fafb;
            cursor: pointer;
            transition: all 0.2s ease;
            min-width: 150px;
        }
        
        .filter-select:focus {
            outline: none;
            border-color: #6b46c1;
            background: white;
            box-shadow: 0 0 0 3px rgba(107, 70, 193, 0.1);
        }
        
        .search-actions {
            display: flex;
            gap: 0.5rem;
        }
        
        @media (max-width: 768px) {
            .search-filter-form {
                flex-direction: column;
                align-items: stretch;
            }
            
            .search-wrapper {
                min-width: 100%;
            }
            
            .filter-select {
                width: 100%;
            }
            
            .search-actions {
                flex-direction: column;
            }
        }
    </style>
</body>
</html>
