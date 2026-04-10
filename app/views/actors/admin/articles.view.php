<?php
// BASE_URL is already available from the controller

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Articles - Admin Panel</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/css/admin.css">
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
                <h1>Manage Articles</h1>
            </div>
            
            <!-- Success/Error Messages -->
            <?php if (isset($_GET['success'])): ?>
                <div class="alert alert-success">
                    <?php
                    switch ($_GET['success']) {
                        case 'created':
                            echo 'Article created successfully';
                            break;
                        case 'updated':
                            echo 'Article updated successfully';
                            break;
                        case 'hidden':
                            echo 'Article hidden from public users successfully';
                            break;
                        case 'unhidden':
                            echo 'Article restored and visible to public users';
                            break;
                        case 'deleted':
                            echo 'Article deleted successfully';
                            break;
                        case 'status_updated':
                            echo 'Article status updated successfully';
                            break;
                        case 'bulk_deleted':
                            $count = $_GET['count'] ?? 0;
                            echo "{$count} article(s) deleted successfully";
                            break;
                        default:
                            echo 'Action completed successfully';
                    }
                    ?>
                </div>
            <?php endif; ?>
            
            <?php if (isset($_GET['error'])): ?>
                <div class="alert alert-error">
                    <?php
                    switch ($_GET['error']) {
                        case 'invalid_method':
                            echo 'Invalid request method';
                            break;
                        case 'invalid_csrf':
                            echo 'Security validation failed. Please try again';
                            break;
                        case 'missing_id':
                            echo 'Article ID is required';
                            break;
                        case 'hide_failed':
                            echo 'Failed to hide article';
                            break;
                        case 'unhide_failed':
                            echo 'Failed to restore article';
                            break;
                        case 'delete_failed':
                            echo 'Failed to delete article';
                            break;
                        case 'not_found':
                            echo 'Article not found';
                            break;
                        case 'no_selection':
                            echo 'No articles selected';
                            break;
                        default:
                            echo 'An error occurred';
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
                        <div id="bulkForm">
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
                                                <?php $status = $article['status'] ?? 'draft'; ?>
                                                <span class="status-badge status-<?= htmlspecialchars($status) ?>">
                                                    <?= $status === 'archived' ? 'Hidden' : ucfirst(htmlspecialchars($status)) ?>
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
                                                    <?php if (($article['status'] ?? 'draft') !== 'archived'): ?>
                                                        <form method="POST" action="<?= URLROOT ?>/admin/hideArticle/<?= $article['article_id'] ?>" style="display:inline;">
                                                            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['admin_csrf_token'] ?? '') ?>">
                                                            <button 
                                                                class="btn btn-sm btn-warning" 
                                                                onclick="confirmHide(this.form, '<?= htmlspecialchars(addslashes($article['title'])) ?>')"
                                                                title="Hide Article"
                                                                type="button"
                                                            >
                                                                <i class="fas fa-eye-slash"></i>
                                                            </button>
                                                        </form>
                                                    <?php else: ?>
                                                        <form method="POST" action="<?= URLROOT ?>/admin/unhideArticle/<?= $article['article_id'] ?>" style="display:inline;">
                                                            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['admin_csrf_token'] ?? '') ?>">
                                                            <button 
                                                                class="btn btn-sm btn-success" 
                                                                onclick="confirmUnhide(this.form, '<?= htmlspecialchars(addslashes($article['title'])) ?>')"
                                                                title="Unhide Article"
                                                                type="button"
                                                            >
                                                                <i class="fas fa-eye"></i>
                                                            </button>
                                                        </form>
                                                    <?php endif; ?>
                                                    <form method="POST" action="<?= URLROOT ?>/admin/deleteArticle/<?= $article['article_id'] ?>" style="display:inline;">
                                                        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['admin_csrf_token'] ?? '') ?>">
                                                        <button 
                                                            class="btn btn-sm btn-danger" 
                                                            onclick="confirmDelete(this.form, '<?= htmlspecialchars(addslashes($article['title'])) ?>')"
                                                            title="Delete Article Permanently"
                                                            type="button"
                                                        >
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="empty-state">
                            <div class="empty-icon"></div>
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

            // Ensure modal is attached to <body> so fixed positioning is true viewport-based.
            if (modal.parentElement !== document.body) {
                document.body.appendChild(modal);
            }
            
            modal.classList.add('is-open');
            content.innerHTML = '<div class="loading"><i class="fas fa-spinner fa-spin"></i> Loading...</div>';
            
            fetch('<?= URLROOT ?>/admin/viewArticle/' + articleId, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
                .then(async response => {
                    const text = await response.text();
                    try {
                        return JSON.parse(text);
                    } catch (e) {
                        throw new Error('Non-JSON response from server');
                    }
                })
                .then(data => {
                    if (data.success) {
                        const article = data.article;
                        const articleTitle = article.title || 'Untitled';
                        const articleContent = article.content || '';
                        content.innerHTML = `
                            <div class="article-details">
                                <div class="article-detail-row">
                                    <span class="detail-label">Article ID:</span>
                                    <span class="detail-value">#${article.article_id}</span>
                                </div>
                                <div class="article-detail-row">
                                    <span class="detail-label">Title:</span>
                                    <span class="detail-value"><strong>${articleTitle}</strong></span>
                                </div>
                                <div class="article-detail-row">
                                    <span class="detail-label">Author:</span>
                                    <span class="detail-value">${article.author_name || 'Unknown'}</span>
                                </div>
                                <div class="article-detail-row">
                                    <span class="detail-label">Status:</span>
                                    <span class="detail-value"><span class="status-badge status-${article.status}">${article.status === 'archived' ? 'Hidden' : article.status}</span></span>
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
                                    <h4>Content:</h4>
                                    <div class="content-box">${articleContent}</div>
                                </div>
                                <div style="margin-top: 1rem; display: flex; gap: 0.5rem;">
                                    ${article.status !== 'archived' ? `
                                    <form method="POST" action="<?= URLROOT ?>/admin/hideArticle/${article.article_id}" style="display:inline;">
                                        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['admin_csrf_token'] ?? '') ?>">
                                        <button type="button" class="btn btn-warning" onclick="confirmHide(this.form, '${articleTitle.replace(/'/g, "\\'")}')">
                                            <i class="fas fa-eye-slash"></i> Hide
                                        </button>
                                    </form>` : `
                                    <form method="POST" action="<?= URLROOT ?>/admin/unhideArticle/${article.article_id}" style="display:inline;">
                                        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['admin_csrf_token'] ?? '') ?>">
                                        <button type="button" class="btn btn-success" onclick="confirmUnhide(this.form, '${articleTitle.replace(/'/g, "\\'")}')">
                                            <i class="fas fa-eye"></i> Unhide
                                        </button>
                                    </form>`}
                                    <form method="POST" action="<?= URLROOT ?>/admin/deleteArticle/${article.article_id}" style="display:inline;">
                                        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['admin_csrf_token'] ?? '') ?>">
                                        <button type="button" class="btn btn-danger" onclick="confirmDelete(this.form, '${articleTitle.replace(/'/g, "\\'")}')">
                                            <i class="fas fa-trash"></i> Delete Permanently
                                        </button>
                                    </form>
                                    <button onclick="closeModal()" class="btn btn-outline">Close</button>
                                </div>
                            </div>
                        `;
                    } else {
                        content.innerHTML = `<div class="error-message">${data.message}</div>`;
                    }
                })
                .catch(error => {
                    content.innerHTML = '<div class="error-message">Error loading article details. Please refresh and try again.</div>';
                    console.error('Error:', error);
                });
        }
        
        // Close modal
        function closeModal() {
            document.getElementById('articleModal').classList.remove('is-open');
        }
        
        // Close modal when clicking outside
        window.onclick = function(event) {
            const modal = document.getElementById('articleModal');
            if (event.target == modal) {
                modal.classList.remove('is-open');
            }
        }
        
        function confirmHide(form, articleTitle) {
            if (confirm(`Hide "${articleTitle}" from public users?\n\nIt will remain available in the admin panel.`)) {
                form.submit();
            }
        }

        function confirmUnhide(form, articleTitle) {
            if (confirm(`Restore "${articleTitle}" to public users?`)) {
                form.submit();
            }
        }

        function confirmDelete(form, articleTitle) {
            if (confirm(`Permanently delete the article "${articleTitle}"?\n\nThis action cannot be undone.`)) {
                form.submit();
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
            
            alert('Bulk delete is not configured yet in this panel. Use individual Delete for now.');
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
        #articleModal {
            display: none;
            position: fixed;
            left: 0;
            top: 0;
            width: 100vw;
            height: 100vh;
            z-index: 99999;
            background: #f3f4f6;
            align-items: center;
            justify-content: center;
            padding: 1rem;
            box-sizing: border-box;
        }

        #articleModal.is-open {
            display: flex;
        }

        #articleModal .modal-content {
            margin: 0;
            max-height: 90vh;
            overflow-y: auto;
            width: min(800px, 100%);
            background: #ffffff;
            opacity: 1;
            border-radius: 12px;
            box-shadow: 0 12px 36px rgba(0, 0, 0, 0.2);
        }

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
