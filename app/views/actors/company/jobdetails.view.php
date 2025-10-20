<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Job Details - UniVerse</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/company.css">
    <style>
        .job-meta { display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 1rem; margin: 1rem 0; }
        .meta-item { background: #fff; border: 1px solid #eee; border-radius: 8px; padding: 1rem; }
        .meta-label { font-size: .8rem; color: #666; text-transform: uppercase; letter-spacing: .05em; }
        .meta-value { font-weight: 600; color: #1f2937; margin-top: .25rem; }
        .section-title { margin: 1.25rem 0 .5rem; color: #111827; font-size: 1.1rem; }
        .section-box { background: #fff; border: 1px solid #eee; border-radius: 8px; padding: 1rem; white-space: pre-wrap; }
        .actions { display:flex; gap:.75rem; margin-top: 1.5rem; }
    </style>
</head>
<body>

<?php require_once __DIR__ . '/companyHeader.view.php'; ?>
    <main class="main-content">
        <!-- Back Button -->
        <div style="margin-bottom: 2rem;">
            <a href="<?= BASE_URL ?>/company/managejobs" class="btn btn-secondary">
                ← Back to Jobs
            </a>
        </div>

        <!-- Job Details Card -->
        <div class="card">
            <div class="card-header">
                <div>
                    <h2 class="card-title"><?= isset($data['job']['title']) ? htmlspecialchars($data['job']['title']) : 'Job Title' ?></h2>
                    <p class="card-subtitle">Posted on <?= isset($data['job']['created_at']) ? date('M d, Y', strtotime($data['job']['created_at'])) : 'Unknown' ?></p>
                </div>
                <div style="display: flex; gap: 1rem;">
                    <a href="<?= BASE_URL ?>/company/editjob/<?= $data['job']['job_id'] ?? '' ?>" class="btn btn-secondary">Edit Job</a>
                    <?php if (isset($data['job']['status']) && $data['job']['status'] === 'active'): ?>
                        <button onclick="closeJob(<?= $data['job']['job_id'] ?>)" class="btn btn-warning">Close Job</button>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Job Info Grid -->
            <div class="job-info-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1.5rem; margin-bottom: 2rem;">
                <div class="info-card">
                    <div class="info-label" style="font-size: 0.75rem; color: #6b7280; font-weight: 600; margin-bottom: 0.5rem;">JOB TYPE</div>
                    <div class="info-value" style="font-size: 1rem; color: #1f2937; font-weight: 500;">
                        <?= isset($data['job']['job_type']) ? ucfirst(str_replace('-', ' ', $data['job']['job_type'])) : 'Not specified' ?>
                    </div>
                </div>
                
                <div class="info-card">
                    <div class="info-label" style="font-size: 0.75rem; color: #6b7280; font-weight: 600; margin-bottom: 0.5rem;">LOCATION</div>
                    <div class="info-value" style="font-size: 1rem; color: #1f2937; font-weight: 500;">
                        <?= isset($data['job']['location']) ? htmlspecialchars($data['job']['location']) : 'Not specified' ?>
                    </div>
                </div>
                
                <div class="info-card">
                    <div class="info-label" style="font-size: 0.75rem; color: #6b7280; font-weight: 600; margin-bottom: 0.5rem;">DEADLINE</div>
                    <div class="info-value" style="font-size: 1rem; color: #1f2937; font-weight: 500;">
                        <?= isset($data['job']['application_deadline']) && $data['job']['application_deadline'] ? date('M d, Y', strtotime($data['job']['application_deadline'])) : 'Not specified' ?>
                    </div>
                </div>
                
                <div class="info-card">
                    <div class="info-label" style="font-size: 0.75rem; color: #6b7280; font-weight: 600; margin-bottom: 0.5rem;">SALARY</div>
                    <div class="info-value" style="font-size: 1rem; color: #1f2937; font-weight: 500;">
                        <?php if (isset($data['job']['salary_min']) && isset($data['job']['salary_max']) && $data['job']['salary_min'] && $data['job']['salary_max']): ?>
                            LKR <?= number_format($data['job']['salary_min']) ?> - <?= number_format($data['job']['salary_max']) ?>
                        <?php else: ?>
                            Not specified
                        <?php endif; ?>
                    </div>
                </div>

                <div class="info-card">
                    <div class="info-label" style="font-size: 0.75rem; color: #6b7280; font-weight: 600; margin-bottom: 0.5rem;">STATUS</div>
                    <div class="info-value" style="font-size: 1rem; color: #1f2937; font-weight: 500;">
                        <?php if (isset($data['job']['status'])): ?>
                            <span class="badge badge-<?= $data['job']['status'] === 'active' ? 'success' : ($data['job']['status'] === 'draft' ? 'secondary' : 'warning') ?>">
                                <?= ucfirst($data['job']['status']) ?>
                            </span>
                        <?php else: ?>
                            <span class="badge badge-secondary">Unknown</span>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Job Description -->
            <div class="section" style="margin-bottom: 2rem;">
                <h3 style="font-size: 1.25rem; font-weight: 600; margin-bottom: 1rem; color: #1f2937;">Job Description</h3>
                <p style="color: #4b5563; line-height: 1.6;"><?= isset($data['job']['description']) ? nl2br(htmlspecialchars($data['job']['description'])) : 'No description available' ?></p>
            </div>

            <!-- Requirements -->
            <div class="section" style="margin-bottom: 2rem;">
                <h3 style="font-size: 1.25rem; font-weight: 600; margin-bottom: 1rem; color: #1f2937;">Requirements</h3>
                <p style="color: #4b5563; line-height: 1.6;"><?= isset($data['job']['requirements']) ? nl2br(htmlspecialchars($data['job']['requirements'])) : 'No requirements specified' ?></p>
            </div>

            <!-- Responsibilities -->
            <div class="section" style="margin-bottom: 2rem;">
                <h3 style="font-size: 1.25rem; font-weight: 600; margin-bottom: 1rem; color: #1f2937;">Responsibilities</h3>
                <p style="color: #4b5563; line-height: 1.6;"><?= isset($data['job']['responsibilities']) ? nl2br(htmlspecialchars($data['job']['responsibilities'])) : 'No responsibilities specified' ?></p>
            </div>

            <!-- Benefits (if available) -->
            <?php if (isset($data['job']['benefits']) && !empty($data['job']['benefits'])): ?>
            <div class="section" style="margin-bottom: 2rem;">
                <h3 style="font-size: 1.25rem; font-weight: 600; margin-bottom: 1rem; color: #1f2937;">Benefits</h3>
                <p style="color: #4b5563; line-height: 1.6;"><?= nl2br(htmlspecialchars($data['job']['benefits'])) ?></p>
            </div>
            <?php endif; ?>
        </div>

        <!-- Applications for this Job -->
        <div class="card" style="margin-top: 2rem;">
            <div class="card-header">
                <h2 class="card-title">Applications (<?= count($data['applications'] ?? []) ?>)</h2>
            </div>

            <?php if (!empty($data['applications'])): ?>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Applicant</th>
                            <th>Applied Date</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($data['applications'] as $application): ?>
                            <tr>
                                <td>
                                    <div class="applicant-info">
                                        <strong><?= htmlspecialchars($application['first_name'] . ' ' . $application['last_name']) ?></strong>
                                        <div style="font-size: 0.875rem; color: #6b7280;"><?= htmlspecialchars($application['email']) ?></div>
                                    </div>
                                </td>
                                <td><?= date('M d, Y', strtotime($application['applied_at'])) ?></td>
                                <td>
                                    <span class="badge badge-<?= getBadgeClass($application['status']) ?>">
                                        <?= ucfirst(str_replace('_', ' ', $application['status'])) ?>
                                    </span>
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-primary" onclick="viewApplication(<?= $application['application_id'] ?>)">View</button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div style="padding: 3rem; text-align: center; color: #6b7280;">
                    <p>No applications received yet for this position.</p>
                </div>
            <?php endif; ?>
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
        });

        function viewApplication(applicationId) {
            window.location.href = '<?= BASE_URL ?>/company/viewapplication/' + applicationId;
        }

        function closeJob(jobId) {
            if (confirm('Are you sure you want to close this job posting? No new applications will be accepted.')) {
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
                        alert('Job posting closed successfully');
                        location.reload();
                    } else {
                        alert('Failed to close job: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred');
                });
            }
        }
    </script>
