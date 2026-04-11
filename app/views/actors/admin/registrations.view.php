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
    <title>Registration Requests - Admin Panel</title>
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
                <h1> Registration Requests</h1>
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
            
            <!-- Pending Registrations -->
            <div class="content-card">
                <div class="content-card-header">
                    <h2 class="content-card-title">Pending Approvals</h2>
                    <span class="status-badge status-warning">
                        <?= isset($data['registrations']) ? count($data['registrations']) : 0 ?> Pending
                    </span>
                </div>
                <div class="content-card-body">
                    <?php if (isset($data['registrations']) && count($data['registrations']) > 0): ?>
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                    <th>Applied On</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($data['registrations'] as $registration): ?>
                                    <tr>
                                        <td>#<?= $registration['user_id'] ?></td>
                                        <td>
                                            <div style="display: flex; align-items: center; gap: 0.75rem;">
                                                <div style="width: 32px; height: 32px; border-radius: 50%; background: #fef3c7; color: #92400e; display: flex; align-items: center; justify-content: center; font-weight: 600; font-size: 0.875rem;">
                                                    <?= strtoupper(substr($registration['first_name'], 0, 1)) ?>
                                                </div>
                                                <span><?= htmlspecialchars($registration['first_name'] . ' ' . $registration['last_name']) ?></span>
                                            </div>
                                        </td>
                                        <td><?= htmlspecialchars($registration['email']) ?></td>
                                        <td>
                                            <span class="status-badge status-pending">
                                                <?= ucfirst($registration['user_role']) ?>
                                            </span>
                                        </td>
                                        <td><?= date('M d, Y', strtotime($registration['created_at'])) ?></td>
                                        <td>
                                            <div style="display: flex; gap: 0.5rem;">
                                                <button class="btn btn-sm btn-outline" onclick="viewDetails(<?= $registration['user_id'] ?>)">
                                                    View Details
                                                </button>
                                                <button class="btn btn-sm btn-success" onclick="approveRegistration(<?= $registration['user_id'] ?>)">
                                                    Approve
                                                </button>
                                                <button class="btn btn-sm btn-danger" onclick="rejectRegistration(<?= $registration['user_id'] ?>)">
                                                    Reject
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php else: ?>
                        <div class="empty-state">
                            <div class="empty-icon"></div>
                            <h3>No Pending Registrations</h3>
                            <p>All registration requests have been processed.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Details Modal -->
    <div class="modal-overlay" id="detailsModal">
        <div class="modal">
            <div class="modal-header">
                <h2 class="modal-title">Registration Details</h2>
                <button class="modal-close" onclick="closeModal()">&times;</button>
            </div>
            <div class="modal-body" id="modalContent">
                <div class="spinner"></div>
            </div>
        </div>
    </div>
    
    <script>
        function viewDetails(userId) {
            document.getElementById('detailsModal').classList.add('show');
            document.getElementById('modalContent').innerHTML = '<div class="spinner"></div>';
            
            // Fetch user details
            fetch('<?= URLROOT ?>/admin/getUserDetails/' + userId)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const user = data.user;
                        let html = `
                            <div class="form-group">
                                <label class="form-label">Full Name</label>
                                <p>${user.first_name} ${user.last_name}</p>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Email</label>
                                <p>${user.email}</p>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Role</label>
                                <p>${user.user_role}</p>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Phone</label>
                                <p>${user.phone || 'N/A'}</p>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Applied On</label>
                                <p>${new Date(user.created_at).toLocaleString()}</p>
                            </div>
                        `;
                        
                        if (user.user_role === 'alumni') {
                            html += `
                                <div class="form-group">
                                    <label class="form-label">Company</label>
                                    <p>${user.company || 'N/A'}</p>
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Position</label>
                                    <p>${user.position || 'N/A'}</p>
                                </div>
                            `;
                        }
                        
                        document.getElementById('modalContent').innerHTML = html;
                    } else {
                        document.getElementById('modalContent').innerHTML = '<p>Error loading details</p>';
                    }
                })
                .catch(error => {
                    document.getElementById('modalContent').innerHTML = '<p>Error loading details</p>';
                });
        }
        
        function closeModal() {
            document.getElementById('detailsModal').classList.remove('show');
        }
        
        function approveRegistration(userId) {
            if (confirm('Are you sure you want to approve this registration?')) {
                fetch('<?= URLROOT ?>/admin/approveRegistration', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ user_id: userId })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Registration approved successfully!');
                        location.reload();
                    } else {
                        alert('Error approving registration: ' + data.message);
                    }
                })
                .catch(error => {
                    alert('Error: ' + error.message);
                });
            }
        }
        
        function rejectRegistration(userId) {
            const reason = prompt('Enter reason for rejection (optional):');
            if (reason !== null) {
                fetch('<?= URLROOT ?>/admin/rejectRegistration', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ user_id: userId, reason: reason })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Registration rejected.');
                        location.reload();
                    } else {
                        alert('Error rejecting registration: ' + data.message);
                    }
                })
                .catch(error => {
                    alert('Error: ' + error.message);
                });
            }
        }
        
        // Close modal when clicking outside
        document.getElementById('detailsModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeModal();
            }
        });
    </script>
</body>
</html>
