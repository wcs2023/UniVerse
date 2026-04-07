<?php
// Include config to get BASE_URL constant
// require_once dirname(dirname(dirname(__FILE__))) . '/core/config.php';
if (!defined('URLROOT')) {
    define('URLROOT', BASE_URL);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - UniVerse</title>
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
                <h1>Dashboard</h1>
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
            
            <!-- Stats Grid -->
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-card-header">
                        
                    </div>
                    <div class="stat-card-value"><?= $data['stats']['total_users'] ?? 0 ?></div>
                    <div class="stat-card-label">Total Users</div>
                    
                </div>
                
                <!-- <div class="stat-card">
                    <div class="stat-card-header">
                        
                    </div>
                    <div class="stat-card-value"><?= $data['stats']['pending_registrations'] ?? 0 ?></div>
                    <div class="stat-card-label">Pending Registrations</div>
                    
                </div> -->
                
                <div class="stat-card">
                    <div class="stat-card-header">
                      
                    </div>
                    <div class="stat-card-value"><?= $data['stats']['total_articles'] ?? 0 ?></div>
                    <div class="stat-card-label">Total Articles</div>
                    
                </div>
                
                <!-- <div class="stat-card">
                    <div class="stat-card-header">
                        
                    </div>
                    <div class="stat-card-value"><?= $data['stats']['pending_articles'] ?? 0 ?></div>
                    <div class="stat-card-label">Pending Moderation</div>
                   
                </div> -->
            </div>
            
            <!-- Recent Activity -->
            <div class="content-card">
                <div class="content-card-header">
                    <h2 class="content-card-title">Recent Activity</h2>
                    <a href="<?= URLROOT ?>/admin/users" class="btn btn-outline btn-sm">View All</a>
                </div>
                <div class="content-card-body">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>User</th>
                                <th>Action</th>
                                <th>Date</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <div style="display: flex; align-items: center; gap: 0.75rem;">
                                        <div style="width: 32px; height: 32px; border-radius: 50%; background: #ede9fe; color: #6b46c1; display: flex; align-items: center; justify-content: center; font-weight: 600;">
                                            J
                                        </div>
                                        <span>John Doe</span>
                                    </div>
                                </td>
                                <td>Registered as Alumni</td>
                                <td>2 hours ago</td>
                                <td><span class="status-badge status-pending">Pending</span></td>
                            </tr>
                            <tr>
                                <td>
                                    <div style="display: flex; align-items: center; gap: 0.75rem;">
                                        <div style="width: 32px; height: 32px; border-radius: 50%; background: #ede9fe; color: #6b46c1; display: flex; align-items: center; justify-content: center; font-weight: 600;">
                                            S
                                        </div>
                                        <span>Sarah Smith</span>
                                    </div>
                                </td>
                                <td>Published Article</td>
                                <td>5 hours ago</td>
                                <td><span class="status-badge status-active">Active</span></td>
                            </tr>
                            <tr>
                                <td>
                                    <div style="display: flex; align-items: center; gap: 0.75rem;">
                                        <div style="width: 32px; height: 32px; border-radius: 50%; background: #ede9fe; color: #6b46c1; display: flex; align-items: center; justify-content: center; font-weight: 600;">
                                            A
                                        </div>
                                        <span>ABC Corp</span>
                                    </div>
                                </td>
                                <td>Posted Job Opening</td>
                                <td>1 day ago</td>
                                <td><span class="status-badge status-active">Active</span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
