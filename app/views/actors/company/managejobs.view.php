<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Jobs - UniVerse</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/company.css">
    <style>
        .alert { border-radius: 8px; padding: 1rem; margin-bottom: 1rem; }
        .alert-success { background: #e7f8ee; color: #0d6832; border: 1px solid #b6e6c8; }
        .alert-error { background: #fde8e8; color: #8a1c1c; border: 1px solid #f5c2c2; }
    </style>
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
        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success">
                <?= $_SESSION['success']; unset($_SESSION['success']); ?>
            </div>
        <?php endif; ?>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-error">
                <?= $_SESSION['error']; unset($_SESSION['error']); ?>
            </div>
        <?php endif; ?>

        <div class="card">
            <div class="card-header">
                <h2 class="card-title">Job Listings</h2>
                <p class="card-subtitle">Manage your active and past job postings</p>
            </div>

            <div class="filter-section" style="margin-bottom: 1rem;">
                <input type="text" id="jobSearch" class="form-control" placeholder="Search jobs by position..." style="max-width: 300px;">
                <select id="statusFilter" class="form-control" style="max-width: 200px;">
                    <option value="">All Status</option>
                    <option value="active">Active</option>
                    <option value="closed">Closed</option>
                    <option value="draft">Draft</option>
                </select>
                <button id="resetFilters" class="btn btn-secondary" style="margin-left: auto;">Reset Filters</button>
            </div>

            <table class="table" id="jobsTable">
                <thead>
                    <tr>
                        <th class="sortable" data-column="position" data-order="asc">
                            Position <span class="sort-arrow">↕</span>
                        </th>
                        <th class="sortable" data-column="date" data-order="asc">
                            Posted Date <span class="sort-arrow">↕</span>
                        </th>
                        <th class="sortable" data-column="applications" data-order="asc">
                            Applications <span class="sort-arrow">↕</span>
                        </th>
                        <th class="sortable" data-column="status" data-order="asc">
                            Status <span class="sort-arrow">↕</span>
                        </th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="jobsTableBody">
                    <?php if (!empty($data['jobs'])): ?>
                        <?php foreach ($data['jobs'] as $job): ?>
                            <tr data-position="<?= htmlspecialchars($job->job_title) ?>" 
                                data-date="<?= $job->created_at ?>" 
                                data-applications="0" 
                                data-status="<?= $job->status ?>">
                                <td><?= htmlspecialchars($job->job_title) ?></td>
                                <td><?= date('M d, Y', strtotime($job->created_at)) ?></td>
                                <td>0</td>
                                <td>
                                    <?php 
                                    $badgeClass = 'badge-secondary';
                                    if ($job->status === 'active') $badgeClass = 'badge-success';
                                    elseif ($job->status === 'closed') $badgeClass = 'badge-warning';
                                    ?>
                                    <span class="badge <?= $badgeClass ?>"><?= ucfirst($job->status) ?></span>
                                </td>
                                <td class="action-buttons">
                                    <a href="<?= BASE_URL ?>/company/jobdetails?id=<?= $job->id ?>" class="btn btn-sm btn-secondary" style="text-decoration: none;">View</a>
                                    <a href="<?= BASE_URL ?>/company/postjobs?id=<?= $job->id ?>" class="btn btn-sm btn-primary" style="text-decoration: none;">Edit</a>
                                    <button class="btn btn-sm btn-danger" onclick="deleteJob(<?= $job->id ?>, '<?= htmlspecialchars($job->job_title) ?>')">Delete</button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" style="text-align: center; padding: 2rem; color: #666;">
                                No jobs posted yet. <a href="<?= BASE_URL ?>/company/postjobs" style="color: #4f46e5;">Post your first job</a>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const profileTrigger = document.querySelector('.profile-trigger');
            const dropdownMenu = document.querySelector('.dropdown-menu');

            if (profileTrigger && dropdownMenu) {
                profileTrigger.addEventListener('click', function(e) {
                    e.stopPropagation();
                    dropdownMenu.classList.toggle('active');
                });

                document.addEventListener('click', function() {
                    dropdownMenu.classList.remove('active');
                });

                dropdownMenu.addEventListener('click', function(e) {
                    e.stopPropagation();
                });
            }

            // Search & Filter
            const searchInput = document.getElementById('jobSearch');
            const statusFilter = document.getElementById('statusFilter');
            const resetBtn = document.getElementById('resetFilters');
            const tableBody = document.getElementById('jobsTableBody');
            const sortableHeaders = document.querySelectorAll('.sortable');

            function filterJobs() {
                const searchTerm = (searchInput.value || '').toLowerCase();
                const statusValue = (statusFilter.value || '').toLowerCase();
                const rows = tableBody.querySelectorAll('tr');

                rows.forEach(row => {
                    const position = (row.getAttribute('data-position') || '').toLowerCase();
                    const status = (row.getAttribute('data-status') || '').toLowerCase();
                    const matchesSearch = position.includes(searchTerm);
                    const matchesStatus = statusValue === '' || status === statusValue;
                    row.style.display = (matchesSearch && matchesStatus) ? '' : 'none';
                });
            }

            searchInput.addEventListener('input', filterJobs);
            statusFilter.addEventListener('change', filterJobs);
            resetBtn.addEventListener('click', function() {
                searchInput.value = '';
                statusFilter.value = '';
                filterJobs();
            });

            // Sorting
            sortableHeaders.forEach(header => {
                header.addEventListener('click', function() {
                    const column = this.getAttribute('data-column');
                    const currentOrder = this.getAttribute('data-order');
                    const newOrder = currentOrder === 'asc' ? 'desc' : 'asc';

                    sortableHeaders.forEach(h => {
                        h.setAttribute('data-order', 'asc');
                        h.classList.remove('sorted-asc', 'sorted-desc');
                    });

                    this.setAttribute('data-order', newOrder);
                    this.classList.add(newOrder === 'asc' ? 'sorted-asc' : 'sorted-desc');
                    sortTable(column, newOrder);
                });
            });

            function sortTable(column, order) {
                const rows = Array.from(tableBody.querySelectorAll('tr'));

                rows.sort((a, b) => {
                    let aValue = a.getAttribute('data-' + column) || '';
                    let bValue = b.getAttribute('data-' + column) || '';

                    if (column === 'applications') { aValue = parseInt(aValue) || 0; bValue = parseInt(bValue) || 0; }
                    else if (column === 'date') { aValue = new Date(aValue); bValue = new Date(bValue); }
                    else { aValue = aValue.toLowerCase(); bValue = bValue.toLowerCase(); }

                    if (order === 'asc') { return aValue > bValue ? 1 : -1; }
                    else { return aValue < bValue ? 1 : -1; }
                });

                rows.forEach(row => tableBody.appendChild(row));
            }
        });

        // Expose deleteJob globally for inline onclick
        function deleteJob(jobId, jobTitle) {
            if (confirm(`Are you sure you want to delete the job "${jobTitle}"? This action cannot be undone.`)) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = '<?= BASE_URL ?>/company/delete';
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'job_id';
                input.value = jobId;
                form.appendChild(input);
                document.body.appendChild(form);
                form.submit();
            }
        }
        window.deleteJob = deleteJob;
    </script>
</body>
</html>
