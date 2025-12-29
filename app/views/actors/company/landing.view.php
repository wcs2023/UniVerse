<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Company Dashboard - UniVerse</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/company.css">
</head>
<body>

<?php require_once __DIR__ . '/companyHeader.view.php'; ?>
    <main class="main-content">
        <!-- Success/Error Messages -->
        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success" style="background: #d4edda; color: #155724; padding: 1rem; border-radius: 8px; margin-bottom: 1.5rem;">
                <?= htmlspecialchars($_SESSION['success']) ?>
                <?php unset($_SESSION['success']); ?>
            </div>
        <?php endif; ?>
        
        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-error" style="background: #f8d7da; color: #721c24; padding: 1rem; border-radius: 8px; margin-bottom: 1.5rem;">
                <?= htmlspecialchars($_SESSION['error']) ?>
                <?php unset($_SESSION['error']); ?>
            </div>
        <?php endif; ?>

        <!-- Stats Overview -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-number"><?= $data['stats']['active_jobs'] ?? 0 ?></div>
                <div class="stat-label">Active Job Postings</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?= $data['stats']['total_applications'] ?? 0 ?></div>
                <div class="stat-label">Total Applications</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?= $data['stats']['pending_applications'] ?? 0 ?></div>
                <div class="stat-label">Pending Applications</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?= $data['stats']['total_views'] ?? 0 ?></div>
                <div class="stat-label">Total Job Views</div>
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="card">
            <div class="card-header">
                <h2 class="card-title">Recent Applications</h2>
                <p class="card-subtitle">Latest applications from your job postings</p>
            </div>
            <table class="table">
                <thead>
                    <tr>
                        <th>Applicant</th>
                        <th>Position</th>
                        <th>Date</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($data['recentApplications'])): ?>
                        <?php foreach ($data['recentApplications'] as $app): ?>
                            <tr>
                                <td><?= htmlspecialchars($app['first_name'] . ' ' . $app['last_name']) ?></td>
                                <td><?= htmlspecialchars($app['job_title']) ?></td>
                                <td><?= date('M d, Y', strtotime($app['applied_at'])) ?></td>
                                <td>
                                    <span class="badge badge-<?= getBadgeClass($app['status']) ?>">
                                        <?= ucfirst(str_replace('_', ' ', $app['status'])) ?>
                                    </span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="4" style="text-align: center; padding: 2rem;">
                                No recent applications. <a href="<?= BASE_URL ?>/company/postjobs">Post a job</a> to start receiving applications.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </main>

    <?php
    // Helper function for badge classes
    function getBadgeClass($status) {
        switch ($status) {
            case 'pending':
                return 'warning';
            case 'under_review':
                return 'info';
            case 'shortlisted':
                return 'primary';
            case 'interviewed':
                return 'info';
            case 'hired':
                return 'success';
            case 'rejected':
                return 'danger';
            default:
                return 'secondary';
        }
    }
    ?>
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
    </script>