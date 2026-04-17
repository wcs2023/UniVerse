<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Jobs - UniVerse</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/company.css">
</head>
<body>

<?php require_once __DIR__ . '/companyHeader.view.php'; ?>
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

        <!-- Search and Filter -->
        <div class="card">
            <div class="card-header">
                <h2 class="card-title">Job Listings</h2>
                <p class="card-subtitle">Manage your active and past job postings</p>
            </div>
            <div style="display: flex; gap: 1rem; margin-bottom: 1rem; flex-wrap: wrap;">
                <input 
                    type="text" 
                    id="searchInput" 
                    class="form-control" 
                    placeholder="Search jobs..."
                    value="<?= htmlspecialchars($_GET['search'] ?? '') ?>"
                    style="max-width: 300px;"
                >

                <select id="statusFilter" class="form-control" style="max-width: 200px;">
                    <option value="">All Status</option>
                    <option value="active" <?= (($_GET['status'] ?? '') === 'active') ? 'selected' : '' ?>>Active</option>
                    <option value="closed" <?= (($_GET['status'] ?? '') === 'closed') ? 'selected' : '' ?>>Closed</option>
                    <option value="draft" <?= (($_GET['status'] ?? '') === 'draft') ? 'selected' : '' ?>>Draft</option>
                </select>

                <button type="button" class="action-btn edit-btn" style="padding: 0.5rem 1rem;"
                onclick="applyFilters()">Search</button>
                <button type="button" class="action-btn delete-btn" style="padding: 0.5rem 1rem;"
                onclick="resetFilters()">Reset</button>

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
                    <?php if (!empty($data['jobs'])): ?>
                        <?php foreach ($data['jobs'] as $job): ?>
                            <tr>
                                <td><?= htmlspecialchars($job['title']) ?></td>
                                <td><?= date('M d, Y', strtotime($job['created_at'])) ?></td>
                                <td><?= $job['applications_count'] ?? 0 ?></td>
                                <td>
                                    <?php if ($job['status'] === 'active'): ?>
                                        <span class="badge badge-success">Active</span>
                                    <?php elseif ($job['status'] === 'draft'): ?>
                                        <span class="badge badge-secondary">Draft</span>
                                    <?php else: ?>
                                        <span class="badge badge-warning">Closed</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <a href="<?= BASE_URL ?>/company/jobdetails/<?= $job['job_id'] ?>" class="action-btn edit-btn">View</a>
                                    <a href="<?= BASE_URL ?>/company/editjob/<?= $job['job_id'] ?>" class="action-btn edit-btn">Edit</a>
                                    <?php if ($job['status'] === 'active'): ?>
                                        <button class="action-btn delete-btn" onclick="closeJob(<?= $job['job_id'] ?>)">Close</button>
                                    <?php else: ?>
                                        <button class="action-btn delete-btn" onclick="deleteJob(<?= $job['job_id'] ?>)">Delete</button>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" style="text-align: center; padding: 2rem;">
                                No jobs posted yet. <a href="<?= BASE_URL ?>/company/postjobs">Post your first job</a>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </main>
    <?php require_once __DIR__ . '/../../layout/footer.php'; ?>
</body>
</html>

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


        function applyFilters() {
            const search = document.getElementById('searchInput').value.trim();
            const status = document.getElementById('statusFilter').value;

            const params = new URLSearchParams();

            if (search) params.append('search', search);
            if (status) params.append('status', status);

            window.location.href = '<?= BASE_URL ?>/company/managejobs' + (params.toString() ? '?' + params.toString() : '');
        }

        function resetFilters() {
            document.getElementById('searchInput').value = '';
            document.getElementById('statusFilter').value = '';
            window.location.href = '<?= BASE_URL ?>/company/managejobs';
        }

        document.getElementById('searchInput').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                applyFilters();
            }
        });

        document.getElementById('statusFilter').addEventListener('change', function() {
            applyFilters();
        });

        function closeJob(jobId) {
            if (confirm('Are you sure you want to close this job posting?')) {
                fetch('<?= BASE_URL ?>/company/updatejobstatus', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: 'job_id=' + jobId + '&status=closed'
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    } else {
                        alert('Failed to close job');
                    }
                });
            }
        }

        function deleteJob(jobId) {
            if (confirm('Are you sure you want to delete this job? This action cannot be undone.')) {
                window.location.href = '<?= BASE_URL ?>/company/deletejob/' + jobId;
            }
        }
    </script>