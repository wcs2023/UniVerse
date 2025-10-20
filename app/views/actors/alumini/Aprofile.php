<?php
// Define constants if not already defined
if (!defined('APPROOT')) {
    define('APPROOT', dirname(dirname(dirname(dirname(__FILE__)))));
}
if (!defined('URLROOT')) {
    define('URLROOT', 'http://localhost/UniVerse/public');
}

// Get user data from controller
$user = $data['userData'] ?? null;

// Prepare display data
$userData = [
    'full_name' => $user->full_name ?? 'User Name',
    'email' => $user->email ?? 'user@example.com',
    'current_role' => $user->current_role ?? '',
    'company' => $user->company ?? '',
    'linkedin_url' => $user->linkedin_url ?? '',
    'short_bio' => $user->short_bio ?? '',
    'profile_picture' => $user->profile_picture ?? '/assets/images/default-avatar.png',
    'available_for_mentorship' => $user->available_for_mentorship ?? false
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Profile - UniVerse</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --primary-purple: #7c3aed;
            --purple-hover: #6d28d9;
            --text-dark: #1f2937;
            --text-light: #6b7280;
            --bg-light: #f3f4f6;
            --bg-white: #ffffff;
            --border-color: #e5e7eb;
            --success-green: #10b981;
            --warning-yellow: #f59e0b;
            --danger-red: #ef4444;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            background-color: var(--bg-light);
            color: var(--text-dark);
            line-height: 1.6;
        }

        .container {
            max-width: 800px;
            margin: 2rem auto;
            padding: 2rem;
        }

        .page-title {
            font-size: 2rem;
            font-weight: 700;
            color: var(--text-dark);
            margin-bottom: 2rem;
        }

        /* Card Sections */
        .profile-card {
            background: var(--bg-white);
            border-radius: 12px;
            padding: 2rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        .card-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: var(--text-dark);
            margin-bottom: 1.5rem;
        }

        /* Profile Picture Section */
        .profile-picture-section {
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 2rem 0;
        }

        .avatar-container {
            position: relative;
            width: 120px;
            height: 120px;
            margin-bottom: 1rem;
        }

        .avatar {
            width: 100%;
            height: 100%;
            border-radius: 50%;
            object-fit: cover;
            background: linear-gradient(135deg, #fbbf24, #f59e0b);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 3rem;
        }

        .avatar-upload-btn {
            position: absolute;
            bottom: 0;
            right: 0;
            width: 36px;
            height: 36px;
            background: var(--bg-white);
            border: 2px solid var(--border-color);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            font-size: 1rem;
        }

        .avatar-upload-btn:hover {
            background: var(--bg-light);
        }

        .profile-name {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--text-dark);
            margin-bottom: 0.25rem;
        }

        .profile-role {
            font-size: 0.95rem;
            color: var(--text-light);
        }

        /* Form Fields */
        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1.5rem;
            margin-bottom: 1.5rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-label {
            display: block;
            font-size: 0.875rem;
            font-weight: 500;
            color: var(--text-dark);
            margin-bottom: 0.5rem;
        }

        .form-input,
        .form-textarea {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            font-size: 0.95rem;
            font-family: inherit;
            background: var(--bg-light);
            transition: border-color 0.3s, background-color 0.3s;
        }

        .form-input:focus,
        .form-textarea:focus {
            outline: none;
            border-color: var(--primary-purple);
            background: var(--bg-white);
        }

        .form-textarea {
            min-height: 100px;
            resize: vertical;
        }

        /* Buttons */
        .btn {
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: 8px;
            font-size: 0.95rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-primary {
            background: var(--primary-purple);
            color: white;
        }

        .btn-primary:hover {
            background: var(--purple-hover);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(124, 58, 237, 0.3);
        }

        .btn-secondary {
            background: white;
            color: var(--primary-purple);
            border: 2px solid var(--primary-purple);
        }

        .btn-secondary:hover {
            background: var(--bg-light);
        }

        .btn-warning {
            background: white;
            color: var(--warning-yellow);
            border: 2px solid var(--warning-yellow);
        }

        .btn-warning:hover {
            background: #fffbeb;
        }

        .btn-danger {
            background: white;
            color: var(--danger-red);
            border: 2px solid var(--danger-red);
        }

        .btn-danger:hover {
            background: #fef2f2;
        }

        .btn-icon {
            font-size: 1.1rem;
        }

        /* Toggle Switch */
        .toggle-container {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            padding: 1rem 0;
        }

        .toggle-info {
            flex: 1;
            margin-right: 2rem;
        }

        .toggle-title {
            font-weight: 600;
            color: var(--text-dark);
            margin-bottom: 0.25rem;
        }

        .toggle-description {
            font-size: 0.875rem;
            color: var(--text-light);
        }

        .toggle-switch {
            position: relative;
            width: 52px;
            height: 28px;
            flex-shrink: 0;
        }

        .toggle-switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        .toggle-slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #cbd5e1;
            transition: 0.3s;
            border-radius: 34px;
        }

        .toggle-slider:before {
            position: absolute;
            content: "";
            height: 20px;
            width: 20px;
            left: 4px;
            bottom: 4px;
            background-color: white;
            transition: 0.3s;
            border-radius: 50%;
        }

        .toggle-switch input:checked + .toggle-slider {
            background: linear-gradient(135deg, #7c3aed, #a78bfa);
        }

        .toggle-switch input:checked + .toggle-slider:before {
            transform: translateX(24px);
        }

        /* Account Management */
        .account-actions {
            display: flex;
            flex-direction: column;
            gap: 1rem;
            margin-top: 1rem;
        }

        .action-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem;
            background: var(--bg-light);
            border-radius: 8px;
        }

        .action-description {
            flex: 1;
            margin-right: 1rem;
        }

        .action-description p {
            font-size: 0.875rem;
            color: var(--text-light);
            margin-top: 0.25rem;
        }

        /* Alert Messages */
        .alert {
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 1.5rem;
            display: none;
        }

        .alert-success {
            background: #d1fae5;
            color: #065f46;
            border: 1px solid #10b981;
        }

        .alert-error {
            background: #fee2e2;
            color: #991b1b;
            border: 1px solid #ef4444;
        }

        /* Save Button Fixed */
        .save-changes-bar {
            position: sticky;
            bottom: 2rem;
            background: var(--bg-white);
            padding: 1rem;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
            display: flex;
            justify-content: flex-end;
            margin-top: 2rem;
        }

        /* Modal */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            animation: fadeIn 0.3s;
        }

        .modal-content {
            background-color: var(--bg-white);
            margin: 10% auto;
            padding: 2rem;
            border-radius: 12px;
            max-width: 500px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
            animation: slideDown 0.3s;
        }

        .modal-header {
            margin-bottom: 1.5rem;
        }

        .modal-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--text-dark);
        }

        .modal-body {
            margin-bottom: 1.5rem;
        }

        .modal-footer {
            display: flex;
            justify-content: flex-end;
            gap: 1rem;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        @keyframes slideDown {
            from {
                transform: translateY(-50px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        /* Responsive */
        @media (max-width: 768px) {
            .container {
                padding: 1rem;
            }

            .profile-card {
                padding: 1.5rem;
            }

            .form-row {
                grid-template-columns: 1fr;
            }

            .toggle-container {
                flex-direction: column;
                align-items: flex-start;
            }

            .toggle-switch {
                margin-top: 1rem;
            }

            .action-item {
                flex-direction: column;
                align-items: flex-start;
            }

            .action-item .btn {
                margin-top: 1rem;
                width: 100%;
                justify-content: center;
            }
        }
    </style>
</head>
<body>
    <?php 
    // Include navigation
    $navFile = APPROOT . '/views/actors/alumini/Anavbar.php';
    if (file_exists($navFile)) {
        include $navFile;
    }
    ?>

    <div class="container">
        <h1 class="page-title">Manage Profile</h1>

        <!-- Success/Error Messages -->
        <div id="alert-success" class="alert alert-success"></div>
        <div id="alert-error" class="alert alert-error"></div>

        <!-- Profile Information Section -->
        <div class="profile-card">
            <h2 class="card-title">Profile Information</h2>
            
            <div class="profile-picture-section">
                <div class="avatar-container">
                    <div class="avatar">
                        <span>👤</span>
                    </div>
                    <label for="profile-picture-upload" class="avatar-upload-btn" title="Change profile picture">
                        📷
                    </label>
                    <input type="file" id="profile-picture-upload" accept="image/*" style="display: none;">
                </div>
                <h3 class="profile-name"><?= htmlspecialchars($userData['full_name']) ?></h3>
                <p class="profile-role"><?= htmlspecialchars($userData['current_role']) ?> at <?= htmlspecialchars($userData['company']) ?></p>
            </div>

            <form id="profile-form">
                <div class="form-row">
                    <div class="form-group">
                        <label for="full_name" class="form-label">Full Name</label>
                        <input 
                            type="text" 
                            id="full_name" 
                            name="full_name" 
                            class="form-input"
                            value="<?= htmlspecialchars($userData['full_name']) ?>"
                            required
                        >
                    </div>
                    <div class="form-group">
                        <label for="email" class="form-label">Email Address</label>
                        <input 
                            type="email" 
                            id="email" 
                            name="email" 
                            class="form-input"
                            value="<?= htmlspecialchars($userData['email']) ?>"
                            required
                        >
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="current_role" class="form-label">Current Role/Major</label>
                        <input 
                            type="text" 
                            id="current_role" 
                            name="current_role" 
                            class="form-input"
                            value="<?= htmlspecialchars($userData['current_role']) ?>"
                        >
                    </div>
                    <div class="form-group">
                        <label for="company" class="form-label">Company/University</label>
                        <input 
                            type="text" 
                            id="company" 
                            name="company" 
                            class="form-input"
                            value="<?= htmlspecialchars($userData['company']) ?>"
                        >
                    </div>
                </div>

                <div class="form-group">
                    <label for="linkedin_url" class="form-label">LinkedIn Profile URL</label>
                    <input 
                        type="url" 
                        id="linkedin_url" 
                        name="linkedin_url" 
                        class="form-input"
                        value="<?= htmlspecialchars($userData['linkedin_url']) ?>"
                        placeholder="https://linkedin.com/in/yourprofile"
                    >
                </div>

                <div class="form-group">
                    <label for="short_bio" class="form-label">Short Bio</label>
                    <textarea 
                        id="short_bio" 
                        name="short_bio" 
                        class="form-textarea"
                        placeholder="Tell us about yourself..."
                    ><?= htmlspecialchars($userData['short_bio']) ?></textarea>
                </div>
            </form>
        </div>

        <!-- Security Settings Section -->
        <div class="profile-card">
            <h2 class="card-title">Security Settings</h2>
            <button class="btn btn-secondary" onclick="openChangePasswordModal()">
                <span class="btn-icon">🔒</span>
                Change Password
            </button>
        </div>

        <!-- Mentorship Settings Section -->
        <div class="profile-card">
            <h2 class="card-title">Mentorship Settings</h2>
            <div class="toggle-container">
                <div class="toggle-info">
                    <div class="toggle-title">Available for Mentorship</div>
                    <div class="toggle-description">Enable this to appear in search results for mentees.</div>
                </div>
                <label class="toggle-switch">
                    <input 
                        type="checkbox" 
                        id="available_for_mentorship" 
                        name="available_for_mentorship"
                        <?= $userData['available_for_mentorship'] ? 'checked' : '' ?>
                    >
                    <span class="toggle-slider"></span>
                </label>
            </div>
        </div>

        <!-- Account Management Section -->
        <div class="profile-card">
            <h2 class="card-title" style="color: var(--danger-red);">Account Management</h2>
            <div class="account-actions">
                <div class="action-item">
                    <div class="action-description">
                        <strong>Deactivate Account</strong>
                        <p>Your profile will be hidden and you won't appear in searches. You can reactivate your account anytime.</p>
                    </div>
                    <button class="btn btn-warning" onclick="openDeactivateModal()">
                        Deactivate Account
                    </button>
                </div>
                <div class="action-item">
                    <div class="action-description">
                        <strong>Delete Account</strong>
                        <p>Warning: This action is permanent and cannot be undone. All your data will be erased.</p>
                    </div>
                    <button class="btn btn-danger" onclick="openDeleteModal()">
                        🗑️ Delete Account
                    </button>
                </div>
            </div>
        </div>

        <!-- Save Changes Bar -->
        <div class="save-changes-bar">
            <button class="btn btn-primary" onclick="saveProfile()">
                💾 Save Changes
            </button>
        </div>
    </div>

    <!-- Change Password Modal -->
    <div id="password-modal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Change Password</h3>
            </div>
            <div class="modal-body">
                <form id="password-form">
                    <div class="form-group">
                        <label for="current_password" class="form-label">Current Password</label>
                        <input 
                            type="password" 
                            id="current_password" 
                            name="current_password" 
                            class="form-input"
                            required
                        >
                    </div>
                    <div class="form-group">
                        <label for="new_password" class="form-label">New Password</label>
                        <input 
                            type="password" 
                            id="new_password" 
                            name="new_password" 
                            class="form-input"
                            required
                        >
                    </div>
                    <div class="form-group">
                        <label for="confirm_password" class="form-label">Confirm New Password</label>
                        <input 
                            type="password" 
                            id="confirm_password" 
                            name="confirm_password" 
                            class="form-input"
                            required
                        >
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" onclick="closeModal('password-modal')">Cancel</button>
                <button class="btn btn-primary" onclick="changePassword()">Update Password</button>
            </div>
        </div>
    </div>

    <!-- Deactivate Account Modal -->
    <div id="deactivate-modal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Deactivate Account</h3>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to deactivate your account? Your profile will be hidden from searches and you won't be able to log in until you reactivate it.</p>
                <p style="margin-top: 1rem;"><strong>You can reactivate your account anytime by logging in.</strong></p>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" onclick="closeModal('deactivate-modal')">Cancel</button>
                <button class="btn btn-warning" onclick="deactivateAccount()">Deactivate</button>
            </div>
        </div>
    </div>

    <!-- Delete Account Modal -->
    <div id="delete-modal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title" style="color: var(--danger-red);">Delete Account</h3>
            </div>
            <div class="modal-body">
                <p><strong>⚠️ Warning: This action is permanent and cannot be undone!</strong></p>
                <p style="margin-top: 1rem;">All your data including:</p>
                <ul style="margin-left: 1.5rem; margin-top: 0.5rem;">
                    <li>Profile information</li>
                    <li>Articles and posts</li>
                    <li>Mentorship history</li>
                    <li>Messages and connections</li>
                </ul>
                <p style="margin-top: 1rem;">...will be permanently deleted.</p>
                <div class="form-group" style="margin-top: 1.5rem;">
                    <label for="delete_confirmation" class="form-label">Type "DELETE" to confirm:</label>
                    <input 
                        type="text" 
                        id="delete_confirmation" 
                        class="form-input"
                        placeholder="DELETE"
                    >
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" onclick="closeModal('delete-modal')">Cancel</button>
                <button class="btn btn-danger" onclick="deleteAccount()">Delete My Account</button>
            </div>
        </div>
    </div>

    <script>
        // Modal functions
        function openModal(modalId) {
            document.getElementById(modalId).style.display = 'block';
        }

        function closeModal(modalId) {
            document.getElementById(modalId).style.display = 'none';
        }

        function openChangePasswordModal() {
            openModal('password-modal');
        }

        function openDeactivateModal() {
            openModal('deactivate-modal');
        }

        function openDeleteModal() {
            openModal('delete-modal');
        }

        // Close modal when clicking outside
        window.onclick = function(event) {
            if (event.target.classList.contains('modal')) {
                event.target.style.display = 'none';
            }
        }

        // Profile picture upload
        document.getElementById('profile-picture-upload').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                // TODO: Upload to server
                showAlert('success', 'Profile picture will be uploaded when you save changes');
            }
        });

        // Save profile
        async function saveProfile() {
            const formData = {
                full_name: document.getElementById('full_name').value,
                email: document.getElementById('email').value,
                current_role: document.getElementById('current_role').value,
                company: document.getElementById('company').value,
                linkedin_url: document.getElementById('linkedin_url').value,
                short_bio: document.getElementById('short_bio').value,
                available_for_mentorship: document.getElementById('available_for_mentorship').checked
            };

            try {
                const response = await fetch('<?= URLROOT ?>/alumni/updateProfile', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(formData)
                });

                const result = await response.json();
                
                if (result.success) {
                    showAlert('success', '✅ Profile updated successfully!');
                } else {
                    showAlert('error', '❌ ' + result.message);
                }
            } catch (error) {
                showAlert('error', '❌ Error updating profile. Please try again.');
            }
        }

        // Change password
        async function changePassword() {
            const currentPassword = document.getElementById('current_password').value;
            const newPassword = document.getElementById('new_password').value;
            const confirmPassword = document.getElementById('confirm_password').value;

            if (newPassword !== confirmPassword) {
                showAlert('error', 'New passwords do not match!');
                return;
            }

            if (newPassword.length < 8) {
                showAlert('error', 'Password must be at least 8 characters long!');
                return;
            }

            try {
                const response = await fetch('<?= URLROOT ?>/alumni/changePassword', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({
                        current_password: currentPassword,
                        new_password: newPassword
                    })
                });

                const result = await response.json();
                
                closeModal('password-modal');
                
                if (result.success) {
                    showAlert('success', '✅ Password changed successfully!');
                    document.getElementById('password-form').reset();
                } else {
                    showAlert('error', '❌ ' + result.message);
                }
            } catch (error) {
                showAlert('error', '❌ Error changing password. Please try again.');
            }
        }

        // Deactivate account
        async function deactivateAccount() {
            try {
                const response = await fetch('<?= URLROOT ?>/alumni/deactivateAccount', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' }
                });

                const result = await response.json();
                
                closeModal('deactivate-modal');
                
                if (result.success) {
                    showAlert('success', '✅ Account deactivated. You can reactivate by logging in.');
                    setTimeout(() => {
                        window.location.href = '<?= URLROOT ?>/users/logout';
                    }, 2000);
                } else {
                    showAlert('error', '❌ ' + result.message);
                }
            } catch (error) {
                showAlert('error', '❌ Error deactivating account. Please try again.');
            }
        }

        // Delete account
        async function deleteAccount() {
            const confirmation = document.getElementById('delete_confirmation').value;
            
            if (confirmation !== 'DELETE') {
                showAlert('error', 'Please type "DELETE" to confirm account deletion');
                return;
            }

            try {
                const response = await fetch('<?= URLROOT ?>/alumni/deleteAccount', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' }
                });

                const result = await response.json();
                
                closeModal('delete-modal');
                
                if (result.success) {
                    showAlert('success', 'Account deleted. Redirecting...');
                    setTimeout(() => {
                        window.location.href = '<?= URLROOT ?>';
                    }, 2000);
                } else {
                    showAlert('error', '❌ ' + result.message);
                }
            } catch (error) {
                showAlert('error', '❌ Error deleting account. Please try again.');
            }
        }

        // Show alert message
        function showAlert(type, message) {
            const alertId = type === 'success' ? 'alert-success' : 'alert-error';
            const alertElement = document.getElementById(alertId);
            
            // Hide all alerts first
            document.querySelectorAll('.alert').forEach(el => el.style.display = 'none');
            
            alertElement.textContent = message;
            alertElement.style.display = 'block';
            
            window.scrollTo({ top: 0, behavior: 'smooth' });
            
            setTimeout(() => {
                alertElement.style.display = 'none';
            }, 5000);
        }
    </script>

    <?php 
    // Include footer
    $footerFile = APPROOT . '/views/actors/alumini/Afooter.php';
    if (file_exists($footerFile)) {
        include $footerFile;
    }
    ?>
</body>
</html>
