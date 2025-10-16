<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Post a Job - UniVerse</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/company.css">
</head>
<body>
    <header class="company-header">
        <a href="<?= BASE_URL ?>/company/landing" class="company-logo">UniVerse</a>
        <nav class="company-nav">
            <a href="<?= BASE_URL ?>/company/landing">Dashboard</a>
            <a href="<?= BASE_URL ?>/company/managejobs">Manage Jobs</a>
            <a href="<?= BASE_URL ?>/company/postjobs" class="active">Post Jobs</a>
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
        <div class="card">
            <div class="card-header">
                <h2 class="card-title">Post a New Job</h2>
                <p class="card-subtitle">Fill in the details below to create a new job listing</p>
            </div>
            
            <form action="/company/postjobs/create" method="POST">
                <div class="form-group">
                    <label for="jobTitle">Job Title*</label>
                    <input type="text" id="jobTitle" name="jobTitle" class="form-control" required>
                </div>

                <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 1rem;">
                    <div class="form-group">
                        <label for="jobType">Job Type*</label>
                        <select id="jobType" name="jobType" class="form-control" required>
                            <option value="">Select Job Type</option>
                            <option value="full-time">Full Time</option>
                            <option value="part-time">Part Time</option>
                            <option value="internship">Internship</option>
                            <option value="contract">Contract</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="location">Location*</label>
                        <input type="text" id="location" name="location" class="form-control" required>
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 1rem;">
                    <div class="form-group">
                        <label for="salary">Salary Range</label>
                        <input type="text" id="salary" name="salary" class="form-control" placeholder="e.g., $50,000 - $70,000">
                    </div>

                    <div class="form-group">
                        <label for="deadline">Application Deadline*</label>
                        <input type="date" id="deadline" name="deadline" class="form-control" required>
                    </div>
                </div>

                <div class="form-group">
                    <label for="description">Job Description*</label>
                    <textarea id="description" name="description" class="form-control" rows="6" required></textarea>
                </div>

                <div class="form-group">
                    <label for="requirements">Requirements*</label>
                    <textarea id="requirements" name="requirements" class="form-control" rows="4" required></textarea>
                </div>

                <div class="form-group">
                    <label for="benefits">Benefits</label>
                    <textarea id="benefits" name="benefits" class="form-control" rows="4"></textarea>
                </div>

                <div style="display: flex; gap: 1rem; justify-content: flex-end; margin-top: 2rem;">
                    <button type="button" class="btn btn-secondary">Save as Draft</button>
                    <button type="submit" class="btn btn-primary">Post Job</button>
                </div>
            </form>
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
