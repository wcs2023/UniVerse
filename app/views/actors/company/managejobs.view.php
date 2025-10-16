<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Jobs - UniVerse</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/company.css">
</head>
<body>
    <header class="company-header">
        <a href="<?= BASE_URL ?>/company/landing" class="company-logo">UniVerse</a>
        <nav class="company-nav">
            <a href="<?= BASE_URL ?>/company/landing">Dashboard</a>
            <a href="<?= BASE_URL ?>/company/managejobs" class="active">Manage Jobs</a>
            <a href="<?= BASE_URL ?>/company/postjobs">Post Jobs</a>
            <a href="<?= BASE_URL ?>/company/applications">View Applications</a>
        </nav>
        
        <!-- User Profile Dropdown -->
        <div class="user-profile-dropdown">
            <div class="profile-trigger">
                <div class="profile-icon">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                    </svg>
                </div>
                <span class="profile-name"><?= $user->firstname ?? 'User' ?></span>
                <div class="dropdown-arrow">▼</div>
            </div>
            
            <div class="dropdown-menu">
                <a href="<?= BASE_URL ?>/company/profile" class="dropdown-item">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 3c1.66 0 3 1.34 3 3s-1.34 3-3 3-3-1.34-3-3 1.34-3 3-3zm0 14.2c-2.5 0-4.71-1.28-6-3.22.03-1.99 4-3.08 6-3.08 1.99 0 5.97 1.09 6 3.08-1.29 1.94-3.5 3.22-6 3.22z"/>
                    </svg>
                    Update Profile
                </a>
                <a href="<?= BASE_URL ?>/login/logout" class="dropdown-item logout">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M17 7l-1.41 1.41L18.17 11H8v2h10.17l-2.58 2.59L17 17l5-5zM4 5h8V3H4c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h8v-2H4V5z"/>
                    </svg>
                    Logout
                </a>
            </div>
        </div>
    </header>

    <main class="main-content">
        <!-- Search and Filter -->
        <div class="card">
            <div class="card-header">
                <h2 class="card-title">Job Listings</h2>
                <p class="card-subtitle">Manage your active and past job postings</p>
            </div>
            <div style="display: flex; gap: 1rem; margin-bottom: 1rem;">
                <input type="text" class="form-control" placeholder="Search jobs..." style="max-width: 300px;">
                <select class="form-control" style="max-width: 200px;">
                    <option value="">All Status</option>
                    <option value="active">Active</option>
                    <option value="closed">Closed</option>
                    <option value="draft">Draft</option>
                </select>
            </div>
            
            <table class="table">
                <thead>
                    <tr>
                        <th>Position</th>
                        <th>Posted Date</th>
                        <th>Applications</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Senior Software Engineer</td>
                        <td>Aug 15, 2025</td>
                        <td>12</td>
                        <td><span class="badge badge-success">Active</span></td>
                        <td>
                            <button class="action-btn edit-btn">Edit</button>
                            <button class="action-btn delete-btn">Close</button>
                        </td>
                    </tr>
                    <tr>
                        <td>UX Designer</td>
                        <td>Aug 20, 2025</td>
                        <td>8</td>
                        <td><span class="badge badge-success">Active</span></td>
                        <td>
                            <button class="action-btn edit-btn">Edit</button>
                            <button class="action-btn delete-btn">Close</button>
                        </td>
                    </tr>
                    <tr>
                        <td>Product Manager</td>
                        <td>Aug 10, 2025</td>
                        <td>15</td>
                        <td><span class="badge badge-warning">Closed</span></td>
                        <td>
                            <button class="action-btn edit-btn">View</button>
                            <button class="action-btn delete-btn">Delete</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

    </main>

    <script>
        // Profile dropdown functionality
        document.addEventListener('DOMContentLoaded', function() {
            const profileTrigger = document.querySelector('.profile-trigger');
            const dropdownMenu = document.querySelector('.dropdown-menu');
            
            profileTrigger.addEventListener('click', function(e) {
                e.stopPropagation();
                dropdownMenu.classList.toggle('active');
            });
            
            // Close dropdown when clicking outside
            document.addEventListener('click', function() {
                dropdownMenu.classList.remove('active');
            });
            
            // Prevent dropdown from closing when clicking inside
            dropdownMenu.addEventListener('click', function(e) {
                e.stopPropagation();
            });
        });
    </script>
</body>
</html>