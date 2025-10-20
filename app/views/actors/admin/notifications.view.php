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
    <title>Send Notifications - Admin Panel</title>
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
                <h1>🔔 Send Notifications</h1>
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
            
            <!-- Notification Form -->
            <div class="content-card">
                <div class="content-card-header">
                    <h2 class="content-card-title">Create Notification</h2>
                </div>
                <div class="content-card-body">
                    <form id="notificationForm">
                        <div class="form-group">
                            <label class="form-label">Recipient Type</label>
                            <select class="form-select" id="recipientType" required>
                                <option value="">Select recipient type</option>
                                <option value="all">All Users</option>
                                <option value="undergraduate">All Undergraduates</option>
                                <option value="alumni">All Alumni</option>
                                <option value="company">All Companies</option>
                                <option value="specific">Specific User</option>
                            </select>
                        </div>
                        
                        <div class="form-group" id="specificUserGroup" style="display: none;">
                            <label class="form-label">User Email</label>
                            <input type="email" class="form-input" id="specificUser" placeholder="Enter user email">
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">Notification Type</label>
                            <select class="form-select" id="notificationType" required>
                                <option value="">Select notification type</option>
                                <option value="maintenance">System Maintenance</option>
                                <option value="update">Platform Update</option>
                                <option value="announcement">General Announcement</option>
                                <option value="reminder">Reminder</option>
                                <option value="alert">Important Alert</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">Subject</label>
                            <input type="text" class="form-input" id="subject" placeholder="Enter notification subject" required maxlength="200">
                            <div style="text-align: right; font-size: 0.875rem; color: var(--text-light); margin-top: 0.25rem;">
                                <span id="subjectCount">0</span>/200
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">Message</label>
                            <textarea class="form-textarea" id="message" placeholder="Enter notification message" required style="min-height: 200px;"></textarea>
                            <div style="text-align: right; font-size: 0.875rem; color: var(--text-light); margin-top: 0.25rem;">
                                <span id="messageCount">0</span> characters
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">Priority</label>
                            <select class="form-select" id="priority" required>
                                <option value="low">Low</option>
                                <option value="medium" selected>Medium</option>
                                <option value="high">High</option>
                                <option value="urgent">Urgent</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer;">
                                <input type="checkbox" id="sendEmail" style="width: auto;">
                                <span>Also send as email notification</span>
                            </label>
                        </div>
                        
                        <div style="display: flex; gap: 1rem; margin-top: 2rem;">
                            <button type="submit" class="btn btn-primary">
                                📤 Send Notification
                            </button>
                            <button type="button" class="btn btn-outline" onclick="previewNotification()">
                                👁️ Preview
                            </button>
                            <button type="reset" class="btn btn-outline">
                                🔄 Reset
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            
            <!-- Recent Notifications -->
            <div class="content-card" style="margin-top: 2rem;">
                <div class="content-card-header">
                    <h2 class="content-card-title">Recent Notifications</h2>
                </div>
                <div class="content-card-body">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Subject</th>
                                <th>Recipients</th>
                                <th>Type</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Oct 20, 2025</td>
                                <td>Platform Maintenance Notice</td>
                                <td>All Users</td>
                                <td><span class="status-badge status-warning">Maintenance</span></td>
                                <td><span class="status-badge status-active">Sent</span></td>
                            </tr>
                            <tr>
                                <td>Oct 18, 2025</td>
                                <td>New Features Available</td>
                                <td>All Users</td>
                                <td><span class="status-badge status-info">Update</span></td>
                                <td><span class="status-badge status-active">Sent</span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Preview Modal -->
    <div class="modal-overlay" id="previewModal">
        <div class="modal">
            <div class="modal-header">
                <h2 class="modal-title">Notification Preview</h2>
                <button class="modal-close" onclick="closePreview()">&times;</button>
            </div>
            <div class="modal-body" id="previewContent">
            </div>
        </div>
    </div>
    
    <script>
        // Character counters
        document.getElementById('subject').addEventListener('input', function() {
            document.getElementById('subjectCount').textContent = this.value.length;
        });
        
        document.getElementById('message').addEventListener('input', function() {
            document.getElementById('messageCount').textContent = this.value.length;
        });
        
        // Show/hide specific user field
        document.getElementById('recipientType').addEventListener('change', function() {
            const specificUserGroup = document.getElementById('specificUserGroup');
            if (this.value === 'specific') {
                specificUserGroup.style.display = 'block';
                document.getElementById('specificUser').required = true;
            } else {
                specificUserGroup.style.display = 'none';
                document.getElementById('specificUser').required = false;
            }
        });
        
        // Preview notification
        function previewNotification() {
            const subject = document.getElementById('subject').value;
            const message = document.getElementById('message').value;
            const type = document.getElementById('notificationType').value;
            const priority = document.getElementById('priority').value;
            
            if (!subject || !message) {
                alert('Please fill in subject and message fields');
                return;
            }
            
            const previewHTML = `
                <div style="border: 2px solid var(--border-color); border-radius: 8px; padding: 1.5rem;">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
                        <span class="status-badge status-${priority}">${priority.toUpperCase()}</span>
                        <span style="font-size: 0.875rem; color: var(--text-light);">Just now</span>
                    </div>
                    <h3 style="margin-bottom: 1rem; color: var(--text-dark);">${subject}</h3>
                    <p style="white-space: pre-wrap; line-height: 1.6;">${message}</p>
                </div>
            `;
            
            document.getElementById('previewContent').innerHTML = previewHTML;
            document.getElementById('previewModal').classList.add('show');
        }
        
        function closePreview() {
            document.getElementById('previewModal').classList.remove('show');
        }
        
        // Submit form
        document.getElementById('notificationForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const data = {
                recipientType: document.getElementById('recipientType').value,
                specificUser: document.getElementById('specificUser').value,
                notificationType: document.getElementById('notificationType').value,
                subject: document.getElementById('subject').value,
                message: document.getElementById('message').value,
                priority: document.getElementById('priority').value,
                sendEmail: document.getElementById('sendEmail').checked
            };
            
            if (confirm('Are you sure you want to send this notification?')) {
                fetch('<?= URLROOT ?>/admin/sendNotification', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(data)
                })
                .then(response => response.json())
                .then(result => {
                    if (result.success) {
                        alert('Notification sent successfully!');
                        this.reset();
                        document.getElementById('subjectCount').textContent = '0';
                        document.getElementById('messageCount').textContent = '0';
                    } else {
                        alert('Error sending notification: ' + result.message);
                    }
                })
                .catch(error => {
                    alert('Error: ' + error.message);
                });
            }
        });
        
        // Close modal on outside click
        document.getElementById('previewModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closePreview();
            }
        });
    </script>
</body>
</html>
