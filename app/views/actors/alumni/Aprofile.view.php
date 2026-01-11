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
        <link rel="stylesheet" href="<?= BASE_URL ?>/css/alumni.css">
        <title>Manage Profile - UniVerse</title>
    </head>
<body>
    <?php 
    // Include navigation
    $navFile = APPROOT . '/views/actors/alumni/Anavbar.php';
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
    include __DIR__ . '/../../layout/footer.php';
    ?>
</body>
</html>

