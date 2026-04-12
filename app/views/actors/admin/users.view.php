<?php

// BASE_URL is already available from the controller
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.0.0"></script>  <!-- ← add this -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users - Admin Panel</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/css/admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        .data-analysis {
        background: linear-gradient(135deg, #f8f9ff, #eef0ff);
        border-radius: 12px;
        padding: 1.5rem;
        border-left: 4px solid #6c63ff; /* purple accent line */
    }

    .data-analysis .content-card-title {
        color: #2d2d5e;
        font-weight: 700;
        font-size: 1.3rem;
    }

    .canvas {
        display: flex;
        justify-content: center;
        align-items: center;
        padding: 1rem;
        background: #f5f4f7;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(108, 99, 255, 0.1);
        margin: 1rem 0;
    }
        .exportbtn {
        background: linear-gradient(135deg, #6c63ff, #a855f7);
        border: none;
        border-radius: 8px;
        padding: 0.5rem 1.2rem;
        color: #fff;
        font-weight: 600;
        letter-spacing: 0.5px;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(108, 99, 255, 0.3);
    }

    .exportbtn:hover {
        transform: translateY(-2px);          /* lifts up on hover */
        box-shadow: 0 6px 20px rgba(108, 99, 255, 0.5);
        background: linear-gradient(135deg, #a855f7, #6c63ff); /* reverses gradient */
    }

    .exportbtn:active {
        transform: translateY(0);             /* presses down on click */
    }

    .bar-chart {
    background: linear-gradient(135deg, #f8f9ff, #eef0ff);
    border-radius: 12px;
    padding: 1.5rem 2rem;
    border-left: 4px solid #6c63ff;
    margin: 1.5rem 0;
    display: flex;
    flex-direction: column;
    align-items: center;          /* centres everything */
    gap: 1rem;
}

.bar-chart .content-card-title {
    color: #2d2d5e;
    font-weight: 700;
    font-size: 1.3rem;
    text-align: center;
    width: 100%;
}

.bar-chart canvas {
    max-width: 40rem;             /* wider than your current 20rem */
    max-height: 25rem;
    width: 100% !important;       /* overrides inline style */
}
    </style>


</head>
<body>
    <div class="admin-layout">
        <!-- Sidebar -->
        <?php include __DIR__ . '/components/sidebar.php'; ?>
        <!-- canvas for user type distribution chart -->
        
        <!-- Main Content -->
        <div class="admin-main">
            <!-- Header -->
            <div class="admin-header">
                <h1>Manage Users</h1>
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
            
            <!-- Success/Error Messages -->
            <?php if (isset($_GET['success'])): ?>
                <div class="alert alert-success">
                    <?php
                    switch ($_GET['success']) {
                        case 'activated':
                            echo 'User account activated successfully';
                            break;
                        case 'deactivated':
                            echo 'User account deactivated successfully';
                            break;
                        case 'updated':
                            echo 'User updated successfully';
                            break;
                        case 'deleted':
                            echo 'User account deleted successfully';
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
                            echo 'User ID is required';
                            break;
                        case 'user_not_found':
                            echo 'User not found';
                            break;
                        case 'must_deactivate_first':
                            echo 'Deactivate the account before deleting it';
                            break;
                        case 'cannot_delete_self':
                            echo 'You cannot delete your own admin account';
                            break;
                        case 'update_failed':
                            echo 'Failed to update user';
                            break;
                        case 'delete_failed':
                            echo 'Failed to delete user account';
                            break;
                            case 'validation_failed':
                                echo 'Please check the form and try again';
                                break;
                            case 'email_exists':
                                echo 'Email already exists';
                                break;
                                default:
                            echo 'An error occurred';
                            }
                            ?>
                </div>
                <?php endif; ?>
                
            <!-- pie chart -->
            <div class="canvas bar-chart">
                <!-- <label for="canvas">System Data</label> -->
                <h2 class="content-card-title">All Users</h2>
                <canvas id="userTypeChart" style="max-width: 20rem; max-height: 20rem;"></canvas>
            </div>
            <div class="bar-chart">
                <h2 class="content-card-title">All Content</h2>
                <canvas id="contentChart" style="max-width: 20rem; max-height: 20rem;"></canvas>
            </div>
            
            <!-- Filters and Search -->
            <div class="content-card" style="margin-bottom: 1.5rem;">
                <div class="content-card-body" style="padding: 1rem;">
                    <form method="GET" action="<?= BASE_URL ?>/admin/users" class="search-filter-form">
                        <div class="search-wrapper">
                            <i class="fas fa-search search-icon"></i>
                            <input 
                                type="text" 
                                name="search" 
                                class="search-input-modern" 
                                placeholder="Search users by name, email..." 
                                value="<?= htmlspecialchars($searchQuery ?? '') ?>"
                            >
                        </div>
                        
                        <select name="role" class="filter-select">
                            <option value="all" <?= (!isset($roleFilter) || $roleFilter === 'all') ? 'selected' : '' ?>>All Roles</option>
                            <option value="undergraduate" <?= (isset($roleFilter) && $roleFilter === 'undergraduate') ? 'selected' : '' ?>>Undergraduates</option>
                            <option value="alumni" <?= (isset($roleFilter) && $roleFilter === 'alumni') ? 'selected' : '' ?>>Alumni</option>
                            <option value="company" <?= (isset($roleFilter) && $roleFilter === 'company') ? 'selected' : '' ?>>Companies</option>
                            <option value="admin" <?= (isset($roleFilter) && $roleFilter === 'admin') ? 'selected' : '' ?>>Admins</option>
                        </select>
                        
                        <div class="search-actions">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-search"></i> Search
                            </button>
                            
                            <a href="<?= BASE_URL ?>/admin/users" class="btn btn-outline">
                                <i class="fas fa-redo"></i> Reset
                            </a>
                        </div>
                    </form>
                </div>
            </div>
            
            <!-- Users Table -->
            <div class="content-card">
                <div class="content-card-header data-analysis">
                    <h2 class="content-card-title">All Users (<?= count($users ?? []) ?>)</h2>
                    <div>
                        <a href="<?= BASE_URL ?>/admin/exportUsers" class="btn btn-primary btn-sm exportbtn">
                            <i class="fas fa-download"></i> Export Users
                        </a>
                    </div>
                </div>
                <div class="content-card-body" style="padding: 0; overflow-x: auto;">
                    <?php if (!empty($users)): ?>
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
                                <?php foreach ($users as $user): ?>
                                    <tr>
                                        <td>#<?= htmlspecialchars($user['user_id']) ?></td>
                                        <td>
                                            <div style="display: flex; align-items: center; gap: 0.75rem;">
                                                <div class="user-avatar-sm">
                                                    <?= strtoupper(substr($user['first_name'], 0, 1)) ?>
                                                </div>
                                                <div>
                                                    <strong><?= htmlspecialchars($user['first_name'] . ' ' . $user['last_name']) ?></strong>
                                                </div>
                                            </div>
                                        </td>
                                        <td><?= htmlspecialchars($user['email']) ?></td>
                                        <td>
                                            <span class="status-badge status-<?= htmlspecialchars($user['user_type']) ?>">
                                                <?= ucfirst(htmlspecialchars($user['user_type'])) ?>
                                            </span>
                                        </td>
                                        <td>
                                            <?php 
                                            $status = $user['account_status'] ?? 'active';
                                            $statusClass = $status === 'active' ? 'status-active' : 'status-inactive';
                                            ?>
                                            <span class="status-badge <?= $statusClass ?>">
                                                <?= ucfirst($status) ?>
                                            </span>
                                        </td>
                                        <td><?= date('M d, Y', strtotime($user['created_at'])) ?></td>
                                        <td>
                                            <div class="action-buttons">
                                                <button 
                                                    class="btn btn-sm btn-outline" 
                                                    onclick="viewUser(<?= $user['user_id'] ?>)"
                                                    title="View Details"
                                                >
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                                
                                                <button 
                                                    class="btn btn-sm btn-primary" 
                                                    onclick="editUser(<?= $user['user_id'] ?>)"
                                                    title="Edit User"
                                                >
                                                    <i class="fas fa-edit"></i>
                                                </button>

                                                <?php if (($user['account_status'] ?? 'active') === 'active'): ?>
                                                    <form method="POST" action="<?= BASE_URL ?>/admin/deactivateUser/<?= $user['user_id'] ?>" style="display: inline;">
                                                        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['admin_csrf_token'] ?? '') ?>">
                                                        <button
                                                            type="button"
                                                            class="btn btn-sm btn-warning"
                                                            onclick="confirmDeactivate(this.form, '<?= htmlspecialchars($user['first_name'] . ' ' . $user['last_name']) ?>')"
                                                            title="Deactivate Account"
                                                        >
                                                            <i class="fas fa-user-slash"></i>
                                                        </button>
                                                    </form>
                                                <?php else: ?>
                                                    <form method="POST" action="<?= BASE_URL ?>/admin/activateUser/<?= $user['user_id'] ?>" style="display: inline;">
                                                        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['admin_csrf_token'] ?? '') ?>">
                                                        <button
                                                            type="submit"
                                                            class="btn btn-sm btn-success"
                                                            title="Activate Account"
                                                        >
                                                            <i class="fas fa-user-check"></i>
                                                        </button>
                                                    </form>
                                                <?php endif; ?>

                                                <?php if (($user['account_status'] ?? 'active') === 'inactive'): ?>
                                                    <form method="POST" action="<?= BASE_URL ?>/admin/deleteUser/<?= $user['user_id'] ?>" style="display: inline;">
                                                        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['admin_csrf_token'] ?? '') ?>">
                                                        <button
                                                            type="button"
                                                            class="btn btn-sm btn-danger"
                                                            onclick="confirmDelete(this.form, '<?= htmlspecialchars($user['first_name'] . ' ' . $user['last_name']) ?>')"
                                                            title="Delete Account"
                                                        >
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php else: ?>
                        <div class="empty-state">
                            <div class="empty-icon"></div>
                            <h3>No Users Found</h3>
                            <p>There are no registered users matching your criteria.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    
    <!-- User Details Modal (View Only) -->
    <div id="userModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>User Details</h2>
                <span class="modal-close" onclick="closeModal('userModal')">&times;</span>
            </div>
            <div class="modal-body" id="userDetailsContent">
                <div class="loading">Loading...</div>
            </div>
        </div>
    </div>
    
    <!-- Edit User Modal -->
    <div id="editModal" class="modal">
        <div class="modal-content" style="max-width: 800px;">
            <div class="modal-header">
                <h2>Edit User</h2>
                <span class="modal-close" onclick="closeModal('editModal')">&times;</span>
            </div>
            <div class="modal-body" id="editUserContent">
                <div class="loading">Loading...</div>
            </div>
        </div>
    </div>
    <script>
        
        // View user details
        function viewUser(userId) {
            const modal = document.getElementById('userModal');
            const content = document.getElementById('userDetailsContent');
            
            modal.style.display = 'block';
            content.innerHTML = '<div class="loading"><i class="fas fa-spinner fa-spin"></i> Loading...</div>';
            
            fetch('<?= BASE_URL ?>/admin/viewUser/' + userId)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const user = data.user;
                        const status = user.account_status || 'active';
                        const statusClass = status === 'active' ? 'status-active' : 'status-inactive';
                        
                        content.innerHTML = `
                            <div class="user-details">
                                <div class="user-detail-row">
                                    <span class="detail-label">User ID:</span>
                                    <span class="detail-value">#${user.user_id}</span>
                                </div>
                                <div class="user-detail-row">
                                    <span class="detail-label">Name:</span>
                                    <span class="detail-value">${user.first_name} ${user.last_name}</span>
                                </div>
                                <div class="user-detail-row">
                                    <span class="detail-label">Email:</span>
                                    <span class="detail-value">${user.email}</span>
                                </div>
                                <div class="user-detail-row">
                                    <span class="detail-label">Username:</span>
                                    <span class="detail-value">${user.username || 'N/A'}</span>
                                </div>
                                <div class="user-detail-row">
                                    <span class="detail-label">Phone:</span>
                                    <span class="detail-value">${user.phone || 'N/A'}</span>
                                </div>
                                <div class="user-detail-row">
                                    <span class="detail-label">Gender:</span>
                                    <span class="detail-value">${user.gender ? user.gender.charAt(0).toUpperCase() + user.gender.slice(1) : 'N/A'}</span>
                                </div>
                                <div class="user-detail-row">
                                    <span class="detail-label">Date of Birth:</span>
                                    <span class="detail-value">${user.date_of_birth || 'N/A'}</span>
                                </div>
                                <div class="user-detail-row">
                                    <span class="detail-label">Role:</span>
                                    <span class="detail-value"><span class="status-badge status-${user.user_type}">${user.user_type}</span></span>
                                </div>
                                <div class="user-detail-row">
                                    <span class="detail-label">Account Status:</span>
                                    <span class="detail-value"><span class="status-badge ${statusClass}">${status}</span></span>
                                </div>
                                <div class="user-detail-row">
                                    <span class="detail-label">Registered:</span>
                                    <span class="detail-value">${new Date(user.created_at).toLocaleString()}</span>
                                </div>
                                <div class="user-detail-row">
                                    <span class="detail-label">Last Login:</span>
                                    <span class="detail-value">${user.last_login ? new Date(user.last_login).toLocaleString() : 'Never'}</span>
                                </div>
                            </div>
                        `;
                    } else {
                        content.innerHTML = `<div class="error-message">${data.message}</div>`;
                    }
                })
                .catch(error => {
                    content.innerHTML = '<div class="error-message">Error loading user details</div>';
                    console.error('Error:', error);
                });
        }
        
        // Edit user
        function editUser(userId) {
            const modal = document.getElementById('editModal');
            const content = document.getElementById('editUserContent');
            
            modal.style.display = 'block';
            content.innerHTML = '<div class="loading"><i class="fas fa-spinner fa-spin"></i> Loading...</div>';
            
            fetch('<?= BASE_URL ?>/admin/viewUser/' + userId)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const user = data.user;
                        
                        content.innerHTML = `
                            <form id="editUserForm" method="POST" action="<?= BASE_URL ?>/admin/updateUser/${user.user_id}">
                                <div class="form-grid">
                                    <div class="form-group">
                                        <label for="first_name" class="form-label">First Name <span class="required">*</span></label>
                                        <input type="text" id="first_name" name="first_name" class="form-input" 
                                               value="${user.first_name}" required>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="last_name" class="form-label">Last Name <span class="required">*</span></label>
                                        <input type="text" id="last_name" name="last_name" class="form-input" 
                                               value="${user.last_name}" required>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="email" class="form-label">Email <span class="required">*</span></label>
                                        <input type="email" id="email" name="email" class="form-input" 
                                               value="${user.email}" required>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="username" class="form-label">Username</label>
                                        <input type="text" id="username" name="username" class="form-input" 
                                               value="${user.username || ''}">
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="phone" class="form-label">Phone</label>
                                        <input type="tel" id="phone" name="phone" class="form-input" 
                                               value="${user.phone || ''}"
                                               placeholder="+94771234567" pattern="\\+94\\d{9}">
                                        <small style="color: #666; font-size: 0.85rem;">Format: +94xxxxxxxxx (e.g., +94771234567)</small>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="gender" class="form-label">Gender</label>
                                        <select id="gender" name="gender" class="form-select">
                                            <option value="male" ${user.gender === 'male' ? 'selected' : ''}>Male</option>
                                            <option value="female" ${user.gender === 'female' ? 'selected' : ''}>Female</option>
                                            <option value="other" ${user.gender === 'other' ? 'selected' : ''}>Other</option>
                                        </select>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="date_of_birth" class="form-label">Date of Birth</label>
                                        <input type="date" id="date_of_birth" name="date_of_birth" class="form-input" 
                                               value="${user.date_of_birth || ''}">
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="user_type" class="form-label">User Type <span class="required">*</span></label>
                                        <select id="user_type" name="user_type" class="form-select" required>
                                            <option value="undergraduate" ${user.user_type === 'undergraduate' ? 'selected' : ''}>Undergraduate</option>
                                            <option value="alumni" ${user.user_type === 'alumni' ? 'selected' : ''}>Alumni</option>
                                            <option value="company" ${user.user_type === 'company' ? 'selected' : ''}>Company</option>
                                            <option value="school_leaver" ${user.user_type === 'school_leaver' ? 'selected' : ''}>School Leaver</option>
                                            <option value="admin" ${user.user_type === 'admin' ? 'selected' : ''}>Admin</option>
                                        </select>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="account_status" class="form-label">Account Status <span class="required">*</span></label>
                                        <select id="account_status" name="account_status" class="form-select" required>
                                            <option value="active" ${(user.account_status || 'active') === 'active' ? 'selected' : ''}>Active</option>
                                            <option value="inactive" ${user.account_status === 'inactive' ? 'selected' : ''}>Inactive</option>
                                            <option value="suspended" ${user.account_status === 'suspended' ? 'selected' : ''}>Suspended</option>
                                        </select>
                                    </div>
                                </div>
                                
                                <div class="form-actions" style="margin-top: 1.5rem;">
                                    <button type="submit" class="btn btn-primary btn-lg">
                                        <i class="fas fa-save"></i> Update User
                                    </button>
                                    <button type="button" class="btn btn-outline btn-lg" onclick="closeModal('editModal')">
                                        <i class="fas fa-times"></i> Cancel
                                    </button>
                                </div>
                            </form>
                        `;
                    } else {
                        content.innerHTML = `<div class="error-message">${data.message}</div>`;
                    }
                })
                .catch(error => {
                    content.innerHTML = '<div class="error-message">Error loading user data</div>';
                    console.error('Error:', error);
                });
        }
        
        // Close modal
        function closeModal(modalId) {
            document.getElementById(modalId).style.display = 'none';
        }
        
        // Close modal when clicking outside
        window.onclick = function(event) {
            if (event.target.classList.contains('modal')) {
                event.target.style.display = 'none';
            }
        }
        
        function confirmDeactivate(form, userName) {
            if (confirm(`Deactivate "${userName}"?\n\nThey will not be able to login until reactivated.`)) {
                form.submit();
            }
        }

        function confirmDelete(form, userName) {
            if (confirm(`WARNING: Permanently delete "${userName}"?\n\nThis action CANNOT be undone.`)) {
                if (confirm(`Final confirmation: Delete "${userName}" permanently?`)) {
                    form.submit();
                }
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

        // Wait for DOM to load 
    document.addEventListener("DOMContentLoaded", function() {
        // Get the canvas element
        var ctx = document.getElementById('userTypeChart').getContext('2d');

        // Data for the chart
        var rawRoleData = <?=  json_encode($roledata) ?>;

        var labels = rawRoleData.map(item => item.user_type)
        var count = rawRoleData.map(item => item.count)
        var backgroundColors = [
            '#4F2D7F',
            '#870074', 
            '#5B3256',
            ' #BE93E4',
            ' #645394',
            '#FAE6FA'
        ].slice(0, labels.length);

        console.log(rawRoleData);
        console.log(rawRoleData[0]);

        var data = {
            labels: labels, // Categories (User types)
            datasets: [{
                label: 'User Types',
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
                responsive: true, // Makes the chart responsive to window resizing
                plugins: {
                    datalabels: {
                        color: '#fff',
                        font: { weight: '600', size: 14 },
                        formatter: function(value, context) {
                            let total = context.dataset.data.reduce((a, b) => a + b, 0);
                            let percent = Math.round((value / total) * 100);
                            return value + '\n(' + percent + '%)';  // ← only count and percentage
                        }
                    },  
                    legend: {
                        position: 'bottom', // Position of the legend
                    },
                    tooltip: {
                        callbacks: {
                            label: function(tooltipItem) {
                                return tooltipItem.label + ': ' + tooltipItem.raw + ' users'; // Custom label format
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

    document.addEventListener("DOMContentLoaded", function() {
        // Get the canvas element
        var ctx = document.getElementById('contentChart').getContext('2d');

        // Data for the chart
        var rawContentData = <?=  json_encode($contentdata[0]) ?>;
        // console.log(rawContentData);

        var labels = ['Job Posts', 'Applications', 'Sessions', 'Articles', 'Forum Posts'];
        var count  = [
            rawContentData.jobPostCount,
            rawContentData.jobApplicationCount,
            rawContentData.bookedSessionCount,
            rawContentData.articleCount,
            rawContentData.forumCount
        ]
       
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
                label: 'Content Count',
                data: count, 
                backgroundColor:backgroundColors,
                borderColor: '#fff',
                borderWidth: 1
            }]
        };

        // Pie Chart Configuration
        var config = {
            type: 'bar', // Chart type (bar chart)
            data: data,  // Data for the chart
        options: {
            responsive: true,
            plugins: {
                datalabels: {
                    color: '#fff',
                    font: { weight: '600', size: 14 },
                    formatter: function(value, context) {
                        let total = context.dataset.data.reduce((a, b) => a + b, 0);
                        let percent = Math.round((value / total) * 100);
                        return value + '\n(' + percent + '%)';  // ← only count and percentage
                    }
                },
                legend: {
                    position: 'top',
                    labels: {
                        color: '#2d2d5e',          // purple legend text
                        font: { weight: '600' }
                    }
                },
                tooltip: {
                    backgroundColor: '#4F2D7F',    // dark purple tooltip
                    titleColor: '#fff',
                    bodyColor: '#FAE6FA',
                    callbacks: {
                        label: (ctx) => `${ctx.label}: ${ctx.raw}`
                    }
                }
            },
        scales: {
            x: {
                ticks: { color: '#5B3256' },   // purple axis labels
                grid:  { color: 'rgba(108, 99, 255, 0.08)' }
            },
            y: {
                beginAtZero: true,
                ticks: { color: '#5B3256' },
                grid:  { color: 'rgba(108, 99, 255, 0.08)' }
            }
        }
    }
        };

        // Create the Pie chart
        new Chart(ctx, config);
    });



    </script>
    
    <style>
        .alert {
            padding: 1rem 1.5rem;
            border-radius: 8px;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
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
        
        .user-avatar-sm {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: #6b46c1;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: 0.875rem;
        }
        
        .action-buttons {
            display: flex;
            gap: 0.5rem;
        }
        
        .action-buttons .btn {
            padding: 0.375rem 0.75rem;
        }
        
        .status-active {
            background: #dcfce7;
            color: #059669;
        }
        
        .status-inactive {
            background: #fee2e2;
            color: #dc2626;
        }
        
        /* Modal Styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0,0,0,0.5);
        }
        
        .modal-content {
            background-color: #fefefe;
            margin: 3% auto;
            padding: 0;
            border-radius: 12px;
            width: 90%;
            max-width: 600px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.2);
            max-height: 90vh;
            overflow-y: auto;
        }
        
        .modal-header {
            padding: 1.5rem;
            border-bottom: 1px solid #e5e7eb;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            background: white;
            z-index: 10;
        }
        
        .modal-header h2 {
            margin: 0;
            font-size: 1.5rem;
        }
        
        .modal-close {
            color: #9ca3af;
            font-size: 2rem;
            font-weight: bold;
            cursor: pointer;
            transition: color 0.3s;
        }
        
        .modal-close:hover {
            color: #1f2937;
        }
        
        .modal-body {
            padding: 1.5rem;
        }
        
        .loading {
            text-align: center;
            padding: 2rem;
            color: #6b7280;
        }
        
        .user-details {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }
        
        .user-detail-row {
            display: flex;
            justify-content: space-between;
            padding: 0.75rem;
            background: #f9fafb;
            border-radius: 8px;
        }
        
        .detail-label {
            font-weight: 600;
            color: #374151;
        }
        
        .detail-value {
            color: #6b7280;
        }
        
        .error-message {
            text-align: center;
            padding: 2rem;
            color: #dc2626;
        }
        
        /* Form Styles */
        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1.5rem;
        }
        
        .form-group {
            display: flex;
            flex-direction: column;
        }
        
        .form-label {
            font-weight: 600;
            margin-bottom: 0.5rem;
            color: #374151;
            font-size: 0.95rem;
        }
        
        .required {
            color: #dc2626;
        }
        
        .form-input,
        .form-select {
            padding: 0.75rem;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            font-size: 1rem;
            transition: border-color 0.2s;
        }
        
        .form-input:focus,
        .form-select:focus {
            outline: none;
            border-color: #6b46c1;
            box-shadow: 0 0 0 3px rgba(107, 70, 193, 0.1);
        }
        
        .form-actions {
            display: flex;
            gap: 1rem;
            padding-top: 1.5rem;
            border-top: 1px solid #e5e7eb;
        }
        
        .btn-lg {
            padding: 0.875rem 1.5rem;
            font-size: 1rem;
        }
        
        @media (max-width: 768px) {
            .form-grid {
                grid-template-columns: 1fr;
            }
            
            .form-actions {
                flex-direction: column;
            }
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
