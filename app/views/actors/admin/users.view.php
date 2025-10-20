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
    <title>Manage Users - Admin Panel</title>
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
                <h1>👥 Manage Users</h1>
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
            
            <!-- Search and Filter -->
            <div class="content-card" style="margin-bottom: 2rem;">
                <div class="content-card-body">
                    <div class="search-bar">
                        <input type="text" class="search-input" placeholder="Search users by name, email, or ID..." id="searchInput">
                        <select class="form-select" style="width: auto;" id="roleFilter">
                            <option value="">All Roles</option>
                            <option value="undergraduate">Undergraduate</option>
                            <option value="alumni">Alumni</option>
                            <option value="company">Company</option>
                            <option value="admin">Admin</option>
                        </select>
                        <select class="form-select" style="width: auto;" id="statusFilter">
                            <option value="">All Status</option>
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>
                </div>
            </div>
            
            <!-- Users Table -->
            <div class="content-card">
                <div class="content-card-header">
                    <h2 class="content-card-title">All Users</h2>
                    <button class="btn btn-primary btn-sm" onclick="exportUsers()">
                        📥 Export Users
                    </button>
                </div>
                <div class="content-card-body">
                    <?php if (isset($data['users']) && count($data['users']) > 0): ?>
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                    <th>Status</th>
                                    <th>Registered</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($data['users'] as $user): ?>
                                    <tr>
                                        <td>#<?= $user['user_id'] ?></td>
                                        <td>
                                            <div style="display: flex; align-items: center; gap: 0.75rem;">
                                                <div style="width: 32px; height: 32px; border-radius: 50%; background: #ede9fe; color: #6b46c1; display: flex; align-items: center; justify-content: center; font-weight: 600; font-size: 0.875rem;">
                                                    <?= strtoupper(substr($user['first_name'], 0, 1)) ?>
                                                </div>
                                                <span><?= htmlspecialchars($user['first_name'] . ' ' . $user['last_name']) ?></span>
                                            </div>
                                        </td>
                                        <td><?= htmlspecialchars($user['email']) ?></td>
                                        <td>
                                            <span class="status-badge status-<?= $user['user_type'] ?>">
                                                <?= ucfirst($user['user_type']) ?>
                                            </span>
                                        </td>
                                        <td>
                                            <span class="status-badge status-<?= $user['account_status'] ?? 'active' ?>">
                                                <?= ucfirst($user['account_status'] ?? 'active') ?>
                                            </span>
                                        </td>
                                        <td><?= date('M d, Y', strtotime($user['created_at'])) ?></td>
                                        <td>
                                            <div style="display: flex; gap: 0.5rem;">
                                                <button class="btn btn-sm btn-outline" onclick="viewUser(<?= $user['user_id'] ?>)">
                                                    👁️ View
                                                </button>
                                                <?php if ($user['account_status'] === 'active'): ?>
                                                    <button class="btn btn-sm btn-warning" onclick="deactivateUser(<?= $user['user_id'] ?>)">
                                                        ⏸️ Deactivate
                                                    </button>
                                                <?php else: ?>
                                                    <button class="btn btn-sm btn-success" onclick="activateUser(<?= $user['user_id'] ?>)">
                                                        ✅ Activate
                                                    </button>
                                                <?php endif; ?>
                                                <button class="btn btn-sm btn-danger" onclick="deleteUser(<?= $user['user_id'] ?>)">
                                                    🗑️
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php else: ?>
                        <div class="empty-state">
                            <div class="empty-icon">👥</div>
                            <h3>No Users Found</h3>
                            <p>There are no users in the system yet.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        function viewUser(userId) {
            // Implement view user details
            alert('View user: ' + userId);
        }
        
        function activateUser(userId) {
            if (confirm('Are you sure you want to activate this user?')) {
                const formData = new FormData();
                formData.append('user_id', userId);
                
                fetch('<?= URLROOT ?>/admin/activateUser', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    } else {
                        alert('Error activating user: ' + (data.message || ''));
                    }
                })
                .catch(error => {
                    alert('Error: ' + error.message);
                });
            }
        }
        
        function deactivateUser(userId) {
            if (confirm('Are you sure you want to deactivate this user?')) {
                const formData = new FormData();
                formData.append('user_id', userId);
                
                fetch('<?= URLROOT ?>/admin/deactivateUser', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    } else {
                        alert('Error deactivating user: ' + (data.message || ''));
                    }
                })
                .catch(error => {
                    alert('Error: ' + error.message);
                });
            }
        }
        
        function deleteUser(userId) {
            if (confirm('Are you sure you want to delete this user? This action cannot be undone.')) {
                const formData = new FormData();
                formData.append('user_id', userId);
                
                fetch('<?= URLROOT ?>/admin/deleteUser', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    } else {
                        alert('Error deleting user: ' + (data.message || ''));
                    }
                })
                .catch(error => {
                    alert('Error: ' + error.message);
                });
            }
        }
        
        function exportUsers() {
            window.location.href = '<?= URLROOT ?>/admin/exportUsers';
        }
    </script>
</body>
</html>
