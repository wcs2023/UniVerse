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
    <title>Platform Settings - Admin Panel</title>
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
                <h1>Platform Settings</h1>
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
            
            <!-- General Settings -->
            <div class="content-card" style="margin-bottom: 2rem;">
                <div class="content-card-header">
                    <h2 class="content-card-title">General Settings</h2>
                </div>
                <div class="content-card-body">
                    <form id="generalSettingsForm">
                        <div class="form-group">
                            <label class="form-label">Platform Name</label>
                            <input type="text" class="form-input" value="UniVerse" placeholder="Enter platform name">
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">Platform Description</label>
                            <textarea class="form-textarea" placeholder="Enter platform description">A platform connecting university students, alumni, and companies for mentorship and career opportunities.</textarea>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">Contact Email</label>
                            <input type="email" class="form-input" value="admin@universe.edu" placeholder="Enter contact email">
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">Support Email</label>
                            <input type="email" class="form-input" value="support@universe.edu" placeholder="Enter support email">
                        </div>
                        
                        <button type="submit" class="btn btn-primary">
                            Save Changes
                        </button>
                    </form>
                </div>
            </div>
            
            <!-- Registration Settings -->
            <div class="content-card" style="margin-bottom: 2rem;">
                <div class="content-card-header">
                    <h2 class="content-card-title">Registration Settings</h2>
                </div>
                <div class="content-card-body">
                    <form id="registrationSettingsForm">
                        <div class="form-group">
                            <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer;">
                                <input type="checkbox" id="requireApproval" checked style="width: auto;">
                                <span>Require admin approval for new registrations</span>
                            </label>
                        </div>
                        
                        <div class="form-group">
                            <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer;">
                                <input type="checkbox" id="emailVerification" checked style="width: auto;">
                                <span>Require email verification for new users</span>
                            </label>
                        </div>
                        
                        <div class="form-group">
                            <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer;">
                                <input type="checkbox" id="allowUndergraduateReg" checked style="width: auto;">
                                <span>Allow undergraduate registrations</span>
                            </label>
                        </div>
                        
                        <div class="form-group">
                            <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer;">
                                <input type="checkbox" id="allowAlumniReg" checked style="width: auto;">
                                <span>Allow alumni registrations</span>
                            </label>
                        </div>
                        
                        <div class="form-group">
                            <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer;">
                                <input type="checkbox" id="allowCompanyReg" checked style="width: auto;">
                                <span>Allow company registrations</span>
                            </label>
                        </div>
                        
                        <button type="submit" class="btn btn-primary">
                            Save Changes
                        </button>
                    </form>
                </div>
            </div>
            
            <!-- Content Moderation -->
            <div class="content-card" style="margin-bottom: 2rem;">
                <div class="content-card-header">
                    <h2 class="content-card-title">Content Moderation</h2>
                </div>
                <div class="content-card-body">
                    <form id="moderationSettingsForm">
                        <div class="form-group">
                            <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer;">
                                <input type="checkbox" id="moderateArticles" style="width: auto;">
                                <span>Require admin approval before publishing articles</span>
                            </label>
                        </div>
                        
                        <div class="form-group">
                            <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer;">
                                <input type="checkbox" id="moderateForums" style="width: auto;">
                                <span>Require admin approval for forum posts</span>
                            </label>
                        </div>
                        
                        <div class="form-group">
                            <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer;">
                                <input type="checkbox" id="autoModeration" checked style="width: auto;">
                                <span>Enable automatic profanity filter</span>
                            </label>
                        </div>
                        
                        <button type="submit" class="btn btn-primary">
                            Save Changes
                        </button>
                    </form>
                </div>
            </div>
            
            <!-- Email Settings -->
            <div class="content-card" style="margin-bottom: 2rem;">
                <div class="content-card-header">
                    <h2 class="content-card-title">Email Settings</h2>
                </div>
                <div class="content-card-body">
                    <form id="emailSettingsForm">
                        <div class="form-group">
                            <label class="form-label">SMTP Server</label>
                            <input type="text" class="form-input" placeholder="smtp.example.com">
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">SMTP Port</label>
                            <input type="number" class="form-input" value="587" placeholder="587">
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">SMTP Username</label>
                            <input type="text" class="form-input" placeholder="username@example.com">
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">SMTP Password</label>
                            <input type="password" class="form-input" placeholder="••••••••">
                        </div>
                        
                        <div class="form-group">
                            <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer;">
                                <input type="checkbox" id="enableSSL" checked style="width: auto;">
                                <span>Enable SSL/TLS</span>
                            </label>
                        </div>
                        
                        <div style="display: flex; gap: 1rem;">
                            <button type="submit" class="btn btn-primary">
                                Save Changes
                            </button>
                            <button type="button" class="btn btn-outline" onclick="testEmail()">
                                Send Test Email
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            
            <!-- Maintenance Mode -->
            <div class="content-card">
                <div class="content-card-header">
                    <h2 class="content-card-title">Maintenance Mode</h2>
                </div>
                <div class="content-card-body">
                    <form id="maintenanceForm">
                        <div class="form-group">
                            <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer;">
                                <input type="checkbox" id="maintenanceMode" style="width: auto;">
                                <span><strong>Enable Maintenance Mode</strong> (Platform will be inaccessible to regular users)</span>
                            </label>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">Maintenance Message</label>
                            <textarea class="form-textarea" id="maintenanceMessage" placeholder="Enter message to display during maintenance">We're currently performing scheduled maintenance. The platform will be back online shortly. Thank you for your patience!</textarea>
                        </div>
                        
                        <button type="submit" class="btn btn-warning">
                            Update Maintenance Settings
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        // General Settings Form
        document.getElementById('generalSettingsForm').addEventListener('submit', function(e) {
            e.preventDefault();
            alert('General settings saved successfully!');
        });
        
        // Registration Settings Form
        document.getElementById('registrationSettingsForm').addEventListener('submit', function(e) {
            e.preventDefault();
            alert('Registration settings saved successfully!');
        });
        
        // Moderation Settings Form
        document.getElementById('moderationSettingsForm').addEventListener('submit', function(e) {
            e.preventDefault();
            alert('Moderation settings saved successfully!');
        });
        
        // Email Settings Form
        document.getElementById('emailSettingsForm').addEventListener('submit', function(e) {
            e.preventDefault();
            alert('Email settings saved successfully!');
        });
        
        // Test Email
        function testEmail() {
            alert('Test email sent! Check your inbox.');
        }
        
        // Maintenance Form
        document.getElementById('maintenanceForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const maintenanceMode = document.getElementById('maintenanceMode').checked;
            
            if (maintenanceMode) {
                if (confirm('Are you sure you want to enable maintenance mode? Regular users will not be able to access the platform.')) {
                    alert('Maintenance mode enabled!');
                }
            } else {
                alert('Maintenance settings updated!');
            }
        });
    </script>
</body>
</html>
