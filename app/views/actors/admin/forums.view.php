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
    <title>Moderate Forums - Admin Panel</title>
    <link rel="stylesheet" href="<?= URLROOT ?>/public/css/admin.css">
</head>
<body>
    <div class="admin-layout">
        <!-- Sidebar -->
        <?php include __DIR__ . '/components/sidebar.php'; ?>
        
        <!-- Main Content -->
        <div class="admin-main">
            <!-- Header -->
            <div class="admin-header">
                <h1>💬 Moderate Forums</h1>
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
            
            <!-- Forum Stats -->
            <div class="stats-grid" style="margin-bottom: 2rem;">
                <div class="stat-card">
                    <div class="stat-card-header">
                        <div class="stat-card-icon primary">
                            💬
                        </div>
                    </div>
                    <div class="stat-card-value">45</div>
                    <div class="stat-card-label">Total Topics</div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-card-header">
                        <div class="stat-card-icon success">
                            ✅
                        </div>
                    </div>
                    <div class="stat-card-value">238</div>
                    <div class="stat-card-label">Total Posts</div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-card-header">
                        <div class="stat-card-icon warning">
                            🚩
                        </div>
                    </div>
                    <div class="stat-card-value">3</div>
                    <div class="stat-card-label">Flagged Posts</div>
                </div>
            </div>
            
            <!-- Flagged Posts -->
            <div class="content-card" style="margin-bottom: 2rem;">
                <div class="content-card-header">
                    <h2 class="content-card-title">Flagged Posts</h2>
                    <span class="status-badge status-warning">3 Pending Review</span>
                </div>
                <div class="content-card-body">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Post ID</th>
                                <th>Topic</th>
                                <th>Author</th>
                                <th>Reason</th>
                                <th>Flagged By</th>
                                <th>Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>#1234</td>
                                <td>Career Advice Discussion</td>
                                <td>John Doe</td>
                                <td><span class="status-badge status-warning">Inappropriate Content</span></td>
                                <td>Admin</td>
                                <td>Oct 20, 2025</td>
                                <td>
                                    <div style="display: flex; gap: 0.5rem;">
                                        <button class="btn btn-sm btn-outline" onclick="viewPost(1234)">
                                            👁️ View
                                        </button>
                                        <button class="btn btn-sm btn-success" onclick="approvePost(1234)">
                                            ✅ Approve
                                        </button>
                                        <button class="btn btn-sm btn-danger" onclick="deletePost(1234)">
                                            🗑️ Delete
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            
            <!-- Recent Forum Activity -->
            <div class="content-card">
                <div class="content-card-header">
                    <h2 class="content-card-title">Recent Forum Activity</h2>
                    <button class="btn btn-primary btn-sm" onclick="refreshActivity()">
                        🔄 Refresh
                    </button>
                </div>
                <div class="content-card-body">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Topic</th>
                                <th>Author</th>
                                <th>Posts</th>
                                <th>Last Activity</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><strong>How to prepare for technical interviews?</strong></td>
                                <td>Sarah Smith</td>
                                <td>15</td>
                                <td>2 hours ago</td>
                                <td><span class="status-badge status-active">Active</span></td>
                                <td>
                                    <div style="display: flex; gap: 0.5rem;">
                                        <button class="btn btn-sm btn-outline" onclick="viewTopic(1)">
                                            👁️ View
                                        </button>
                                        <button class="btn btn-sm btn-warning" onclick="closeTopic(1)">
                                            🔒 Close
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Best companies for fresh graduates</strong></td>
                                <td>Mike Johnson</td>
                                <td>8</td>
                                <td>5 hours ago</td>
                                <td><span class="status-badge status-active">Active</span></td>
                                <td>
                                    <div style="display: flex; gap: 0.5rem;">
                                        <button class="btn btn-sm btn-outline" onclick="viewTopic(2)">
                                            👁️ View
                                        </button>
                                        <button class="btn btn-sm btn-warning" onclick="closeTopic(2)">
                                            🔒 Close
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Networking tips for introverts</strong></td>
                                <td>Emily Chen</td>
                                <td>12</td>
                                <td>1 day ago</td>
                                <td><span class="status-badge status-active">Active</span></td>
                                <td>
                                    <div style="display: flex; gap: 0.5rem;">
                                        <button class="btn btn-sm btn-outline" onclick="viewTopic(3)">
                                            👁️ View
                                        </button>
                                        <button class="btn btn-sm btn-warning" onclick="closeTopic(3)">
                                            🔒 Close
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        function viewPost(postId) {
            alert('View post: ' + postId);
        }
        
        function approvePost(postId) {
            if (confirm('Are you sure you want to approve this post?')) {
                alert('Post approved!');
                location.reload();
            }
        }
        
        function deletePost(postId) {
            if (confirm('Are you sure you want to delete this post? This action cannot be undone.')) {
                alert('Post deleted!');
                location.reload();
            }
        }
        
        function viewTopic(topicId) {
            alert('View topic: ' + topicId);
        }
        
        function closeTopic(topicId) {
            if (confirm('Are you sure you want to close this topic? No more posts can be added.')) {
                alert('Topic closed!');
                location.reload();
            }
        }
        
        function refreshActivity() {
            location.reload();
        }
    </script>
</body>
</html>
