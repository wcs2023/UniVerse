<?php
// Ensure variables are available
$posts = $posts ?? [];
$searchQuery = $searchQuery ?? '';
$statusFilter = $statusFilter ?? 'all';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Moderate Forums - Admin Panel</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/admin.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.0.0"></script>  <!-- ← add this -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .forumChart 
        {
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 1.5rem;
            background: linear-gradient(135deg, #f8f9ff, #eef0ff);
            border-radius: 12px;
            border-left: 4px solid #6c63ff;
            margin-bottom: 1.5rem;
        }

        .canvas-card 
        {
            display: flex;
            justify-content: center;
            align-items: center;
            width: 100%;
        }

        .canvas-card canvas 
        {
            max-width: 450px !important;
            max-height: 450px !important;
            width: 400px !important;
            height: 400px !important;
        }

    </style>
</head>
<body>
    <div class="admin-layout">
        <?php include __DIR__ . '/components/sidebar.php'; ?>

        <div class="admin-main">
            <div class="admin-header">
                <h1>Moderate Forums</h1>
            </div>

            <!-- canvas -->
            <center>
            <h2>Forum Types</h2>
            </center>
            <div class="forumChart">
                <div class="canvas-card">
                    <span class="chart-title">
                    </span>
                    <canvas id="forumTypeChart"></canvas>
                </div>
            </div>
            <?php if (isset($_GET['success'])): ?>
                <div class="alert alert-success">
                    <?php
                    switch ($_GET['success']) {
                        case 'hidden':
                            echo 'Forum post hidden successfully';
                            break;
                        case 'unhidden':
                            echo 'Forum post restored successfully';
                            break;
                        case 'deleted':
                            echo 'Forum post deleted permanently';
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
                            echo 'Post ID is required';
                            break;
                        case 'hide_failed':
                            echo 'Failed to hide forum post';
                            break;
                        case 'unhide_failed':
                            echo 'Failed to restore forum post';
                            break;
                        case 'delete_failed':
                            echo 'Failed to delete forum post';
                            break;
                        default:
                            echo 'An error occurred';
                    }
                    ?>
                </div>
            <?php endif; ?>

            <div class="content-card" style="margin-bottom: 1.5rem;">
                <div class="content-card-body" style="padding: 1rem;">
                    <form method="GET" action="<?= BASE_URL ?>/admin/forums" class="search-filter-form">
                        <div class="search-wrapper">
                            <i class="fas fa-search search-icon"></i>
                            <input
                                type="text"
                                name="search"
                                class="search-input-modern"
                                placeholder="Search by thread title, post content, username, email..."
                                value="<?= htmlspecialchars($searchQuery ?? '') ?>"
                            >
                        </div>

                        <select name="status" class="filter-select">
                            <option value="all" <?= (!isset($statusFilter) || $statusFilter === 'all') ? 'selected' : '' ?>>All Status</option>
                            <option value="active" <?= (isset($statusFilter) && $statusFilter === 'active') ? 'selected' : '' ?>>Visible</option>
                            <option value="hidden" <?= (isset($statusFilter) && $statusFilter === 'hidden') ? 'selected' : '' ?>>Hidden</option>
                        </select>

                        <div class="search-actions">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-search"></i> Search
                            </button>
                            <a href="<?= BASE_URL ?>/admin/forums" class="btn btn-outline">
                                <i class="fas fa-redo"></i> Reset
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <div class="content-card">
                <div class="content-card-header">
                    <h2 class="content-card-title">Forum Posts (<?= count($posts ?? []) ?>)</h2>
                </div>
                <div class="content-card-body" style="padding: 0; overflow-x: auto;">
                    <?php if (!empty($posts)): ?>
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Thread</th>
                                    <th>Author</th>
                                    <th>Status</th>
                                    <th>Created</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($posts as $post): ?>
                                    <?php $isHidden = (int)($post['is_deleted'] ?? 0) === 1; ?>
                                    <tr>
                                        <td>#<?= htmlspecialchars($post['id']) ?></td>
                                        <td><?= htmlspecialchars($post['thread_title'] ?? 'Unknown Thread') ?></td>
                                        <td><?= htmlspecialchars($post['author_name'] ?: ($post['username'] ?? 'Unknown')) ?></td>
                                        <td>
                                            <span class="status-badge <?= $isHidden ? 'status-archived' : 'status-published' ?>">
                                                <?= $isHidden ? 'Hidden' : 'Visible' ?>
                                            </span>
                                        </td>
                                        <td><?= !empty($post['created_at']) ? date('M d, Y', strtotime($post['created_at'])) : 'N/A' ?></td>
                                        <td>
                                            <div class="action-buttons">
                                                <button
                                                    class="btn btn-sm btn-outline"
                                                    onclick="viewPost(<?= (int)$post['id'] ?>)"
                                                    title="View Details"
                                                    type="button"
                                                >
                                                    <i class="fas fa-eye"></i>
                                                </button>

                                                <?php if (!$isHidden): ?>
                                                    <form method="POST" action="<?= BASE_URL ?>/admin/hideForumPost/<?= (int)$post['id'] ?>" style="display:inline;">
                                                        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['admin_csrf_token'] ?? '') ?>">
                                                        <button
                                                            class="btn btn-sm btn-warning"
                                                            onclick="confirmHide(this.form, '<?= htmlspecialchars(addslashes($post['thread_title'] ?? 'Forum Post')) ?>')"
                                                            title="Hide Post"
                                                            type="button"
                                                        >
                                                            <i class="fas fa-eye-slash"></i>
                                                        </button>
                                                    </form>
                                                <?php else: ?>
                                                    <form method="POST" action="<?= BASE_URL ?>/admin/unhideForumPost/<?= (int)$post['id'] ?>" style="display:inline;">
                                                        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['admin_csrf_token'] ?? '') ?>">
                                                        <button
                                                            class="btn btn-sm btn-success"
                                                            onclick="confirmUnhide(this.form, '<?= htmlspecialchars(addslashes($post['thread_title'] ?? 'Forum Post')) ?>')"
                                                            title="Unhide Post"
                                                            type="button"
                                                        >
                                                            <i class="fas fa-eye"></i>
                                                        </button>
                                                    </form>
                                                <?php endif; ?>

                                                <form method="POST" action="<?= BASE_URL ?>/admin/deleteForumPost/<?= (int)$post['id'] ?>" style="display:inline;">
                                                    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['admin_csrf_token'] ?? '') ?>">
                                                    <button
                                                        class="btn btn-sm btn-danger"
                                                        onclick="confirmDelete(this.form, '<?= htmlspecialchars(addslashes($post['thread_title'] ?? 'Forum Post')) ?>')"
                                                        title="Delete Post Permanently"
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
                    <?php else: ?>
                        <div class="empty-state">
                            <h3>No Forum Posts Found</h3>
                            <p>No forum posts match your current filters.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <div id="forumPostModal" class="modal-overlay" style="background-color: #ddd1f9;">
        <div class="modal-card">
            <div class="modal-header">
                <h2>Forum Post Details</h2>
                <span class="modal-close" onclick="closePostModal()">&times;</span>
            </div>
            <div class="modal-body" id="forumPostDetailsContent" >
                <div class="loading">Loading...</div>
            </div>
        </div>
    </div>

    <script>
        function viewPost(postId) {
            const modal = document.getElementById('forumPostModal');
            const content = document.getElementById('forumPostDetailsContent');

            if (modal.parentElement !== document.body) {
                document.body.appendChild(modal);
            }

            modal.classList.add('is-open');
            content.innerHTML = '<div class="loading"><i class="fas fa-spinner fa-spin"></i> Loading...</div>';

            fetch('<?= BASE_URL ?>/admin/viewForumPost/' + postId, {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
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
                if (!data.success) {
                    content.innerHTML = '<div class="error-message">' + (data.message || 'Post not found') + '</div>';
                    return;
                }

                const post = data.post;
                const isHidden = Number(post.is_deleted || 0) === 1;
                const title = post.thread_title || 'Unknown Thread';
                const author = post.author_name || post.username || 'Unknown';
                const body = post.body || '';

                content.innerHTML = `
                    <div class="post-details">
                        <div class="post-detail-row"><span class="detail-label">Post ID:</span><span class="detail-value">#${post.id}</span></div>
                        <div class="post-detail-row"><span class="detail-label">Thread:</span><span class="detail-value"><strong>${title}</strong></span></div>
                        <div class="post-detail-row"><span class="detail-label">Author:</span><span class="detail-value">${author}</span></div>
                        <div class="post-detail-row"><span class="detail-label">Email:</span><span class="detail-value">${post.email || 'N/A'}</span></div>
                        <div class="post-detail-row"><span class="detail-label">Status:</span><span class="detail-value"><span class="status-badge ${isHidden ? 'status-archived' : 'status-published'}">${isHidden ? 'Hidden' : 'Visible'}</span></span></div>
                        <div class="post-detail-row"><span class="detail-label">Created:</span><span class="detail-value">${post.created_at ? new Date(post.created_at).toLocaleString() : 'N/A'}</span></div>
                        <div class="post-content-preview">
                            <h4>Post Content:</h4>
                            <div class="content-box">${body}</div>
                        </div>
                        <div style="margin-top: 1rem; display: flex; gap: 0.5rem;">
                            ${!isHidden ? `
                                <form method="POST" action="<?= BASE_URL ?>/admin/hideForumPost/${post.id}" style="display:inline;">
                                    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['admin_csrf_token'] ?? '') ?>">
                                    <button type="button" class="btn btn-warning" onclick="confirmHide(this.form, '${title.replace(/'/g, "\\'")}')"><i class="fas fa-eye-slash"></i> Hide</button>
                                </form>` : `
                                <form method="POST" action="<?= BASE_URL ?>/admin/unhideForumPost/${post.id}" style="display:inline;">
                                    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['admin_csrf_token'] ?? '') ?>">
                                    <button type="button" class="btn btn-success" onclick="confirmUnhide(this.form, '${title.replace(/'/g, "\\'")}')"><i class="fas fa-eye"></i> Unhide</button>
                                </form>`}
                            <form method="POST" action="<?= BASE_URL ?>/admin/deleteForumPost/${post.id}" style="display:inline;">
                                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['admin_csrf_token'] ?? '') ?>">
                                <button type="button" class="btn btn-danger" onclick="confirmDelete(this.form, '${title.replace(/'/g, "\\'")}')"><i class="fas fa-trash"></i> Delete</button>
                            </form>
                            <button onclick="closePostModal()" class="btn btn-outline">Close</button>
                        </div>
                    </div>
                `;
            })
            .catch(error => {
                content.innerHTML = '<div class="error-message">Error loading forum post details.</div>';
                console.error(error);
            });
        }

        function closePostModal() {
            document.getElementById('forumPostModal').classList.remove('is-open');
        }

        window.onclick = function(event) {
            const modal = document.getElementById('forumPostModal');
            if (event.target === modal) {
                closePostModal();
            }
        }

        function confirmHide(form, title) {
            if (confirm(`Hide post in "${title}" from users?`)) {
                form.submit();
            }
        }

        function confirmUnhide(form, title) {
            if (confirm(`Restore post in "${title}" to users?`)) {
                form.submit();
            }
        }

        function confirmDelete(form, title) {
            if (confirm(`Permanently delete post in "${title}"?\n\nThis action cannot be undone.`)) {
                form.submit();
            }
        }

        setTimeout(() => {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                alert.style.transition = 'opacity 0.5s';
                alert.style.opacity = '0';
                setTimeout(() => alert.remove(), 500);
            });
        }, 5000);

     document.addEventListener("DOMContentLoaded", function() {
        // Get the canvas element
        var ctx = document.getElementById('forumTypeChart').getContext('2d');

        // Data for the chart
        var rawForumData = <?=  json_encode($forumsCount) ?>;
                        console.log(rawForumData);
        var labels = rawForumData.map(item => item.category)
        var count = rawForumData.map(item => item.count)
        var backgroundColors = [
            '#4F2D7F',
            '#870074', 
            '#5B3256',
            ' #BE93E4',
            ' #645394',
            '#FAE6FA'
        ].slice(0, labels.length);


        var data = {
            labels: labels, // Categories (User types)
            datasets: [{
                label: 'Forum Categories',
                data: count, // Example data: [adminCount, studentCount, undergradCount]
                backgroundColor: backgroundColors,
                borderColor: '#fff',
                borderWidth: 1
            }]
        };

        // Pie Chart Configuration
        var config = {
            type: 'pie', // Chart type (pie chart)
            data: data,  // Data for the chart
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    datalabels: {              // ← ADD THIS WHOLE BLOCK
                        color: '#fff',
                        font: { weight: '600', size: 14 },
                        formatter: function(value, context) {
                            let total = context.dataset.data.reduce((a, b) => a + b, 0);
                            let percent = Math.round((value / total) * 100);
                            return value + '\n(' + percent + '%)';  // ← only count and percentage
                        }
                    },
                    legend: {
                        position: 'bottom',   // ✅ moves legend below pie
                        align: 'centre',
                        labels: {
                            color: '#2d2d5e',
                            font: { weight: '600' },
                            padding: 16
                        }
                    },
                    tooltip: {
                        backgroundColor: '#4F2D7F',
                        titleColor: '#fff',
                        bodyColor: '#FAE6FA',
                        callbacks: {
                            label: function(tooltipItem) {
                                return tooltipItem.label + ': ' + tooltipItem.raw + ' forums';
                            }
                        }
                    }
                }
            }
    };
        // Create the Pie chart
        
        Chart.register(ChartDataLabels);
        new Chart(ctx, config);
    });

    </script>

    <style>
        .alert {
            padding: 1rem 1.5rem;
            border-radius: 8px;
            margin-bottom: 1.5rem;
        }

        .alert-success {
            background: #dcfce7;
            color: #059669;
            border: 1px solid #86efac;
        }

        .alert-error {
            background: #fee2e2;
            color: #dc2626;
            border: 1px solid #fca5a5;
        }

        .status-published {
            background: #dcfce7;
            color: #059669;
        }

        .status-archived {
            background: #e5e7eb;
            color: #6b7280;
        }

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
            background: #f9fafb;
        }

        .filter-select {
            padding: 0.75rem 1rem;
            border: 1px solid #d1d5db;
            border-radius: 10px;
            min-width: 150px;
            background: #f9fafb;
        }

        .search-actions {
            display: flex;
            gap: 0.5rem;
        }

        .modal-overlay {
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

        .modal-overlay.is-open {
            display: flex;
        }

        .modal-card {
            width: min(800px, 100%);
            max-height: 90vh;
            overflow-y: auto;
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 12px 36px rgba(0, 0, 0, 0.2);
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem 1.25rem;
            border-bottom: 1px solid #e5e7eb;
        }

        .modal-body {
            padding: 1rem 1.25rem;
        }

        .modal-close {
            cursor: pointer;
            font-size: 2rem;
            color: #9ca3af;
            line-height: 1;
        }

        .post-detail-row {
            display: flex;
            justify-content: space-between;
            padding: 0.75rem;
            background: #f9fafb;
            border-radius: 8px;
            margin-bottom: 0.5rem;
        }

        .post-content-preview {
            margin-top: 1rem;
        }

        .content-box {
            padding: 1rem;
            background: #f9fafb;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            max-height: 260px;
            overflow-y: auto;
            line-height: 1.6;
            white-space: pre-wrap;
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
