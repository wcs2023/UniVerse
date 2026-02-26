<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?= BASE_URL ?>/css/styles.css">
    <link rel="icon" type="image/png" href="<?= BASE_URL ?>/assets/images/U.png">
    <title>My Applications - UniVerse</title>
    <style>
        body {
            padding-top: 80px;
        }

        .applications-container {
            max-width: 1200px;
            margin: 2rem auto;
            padding: 0 2rem;
        }

        .page-header {
            margin-bottom: 2rem;
        }

        .page-header h1 {
            font-size: 2rem;
            color: var(--text-dark);
            margin-bottom: 0.5rem;
        }

        .page-header p {
            color: var(--text-light);
        }

        .alert {
            padding: 1rem 1.5rem;
            border-radius: 8px;
            margin-bottom: 1.5rem;
        }

        .alert-success {
            background: #d1fae5;
            color: #047857;
            border-left: 4px solid #047857;
        }

        .alert-error {
            background: #fee2e2;
            color: #dc2626;
            border-left: 4px solid #dc2626;
        }

        .applications-grid {
            display: grid;
            gap: 1.5rem;
        }

        .application-card {
            background: var(--white);
            border-radius: 12px;
            padding: 1.5rem;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
            border: 1px solid var(--border-color);
            transition: all 0.3s ease;
        }

        .application-card:hover {
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.12);
        }

        .application-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 1rem;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .job-info h3 {
            font-size: 1.25rem;
            color: var(--text-dark);
            margin-bottom: 0.25rem;
        }

        .job-info .company {
            color: var(--primary-purple);
            font-weight: 600;
            font-size: 0.95rem;
        }

        .status-badge {
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
            text-transform: capitalize;
        }

        .status-pending {
            background: #fef3c7;
            color: #92400e;
        }

        .status-under_review {
            background: #dbeafe;
            color: #1e40af;
        }

        .status-shortlisted {
            background: #d1fae5;
            color: #047857;
        }

        .status-interviewed {
            background: #e0e7ff;
            color: #3730a3;
        }

        .status-hired {
            background: #bbf7d0;
            color: #166534;
        }

        .status-rejected {
            background: #fee2e2;
            color: #dc2626;
        }

        .status-withdrawn {
            background: #e5e7eb;
            color: #6b7280;
        }

        .application-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 1.5rem;
            margin-bottom: 1rem;
            font-size: 0.9rem;
            color: var(--text-light);
        }

        .meta-item {
            display: flex;
            align-items: center;
            gap: 0.4rem;
        }

        .application-actions {
            display: flex;
            gap: 0.75rem;
            padding-top: 1rem;
            border-top: 1px solid var(--border-color);
        }

        .btn {
            padding: 0.5rem 1.25rem;
            border-radius: 6px;
            font-size: 0.9rem;
            font-weight: 600;
            text-decoration: none;
            cursor: pointer;
            transition: all 0.3s ease;
            border: none;
        }

        .btn-primary {
            background: var(--primary-purple);
            color: white;
        }

        .btn-primary:hover {
            background: var(--dark-purple);
        }

        .btn-danger {
            background: #fee2e2;
            color: #dc2626;
        }

        .btn-danger:hover {
            background: #fecaca;
        }

        .btn-secondary {
            background: var(--light-gray);
            color: var(--text-dark);
        }

        .btn-secondary:hover {
            background: var(--border-color);
        }

        .empty-state {
            text-align: center;
            padding: 4rem 2rem;
            background: var(--white);
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
        }

        .empty-icon {
            font-size: 4rem;
            margin-bottom: 1rem;
            opacity: 0.5;
        }

        .empty-state h3 {
            font-size: 1.5rem;
            color: var(--text-dark);
            margin-bottom: 0.5rem;
        }

        .empty-state p {
            color: var(--text-light);
            margin-bottom: 1.5rem;
        }

        .stats-bar {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 1rem;
            margin-bottom: 2rem;
        }

        .stat-card {
            background: var(--white);
            padding: 1.25rem;
            border-radius: 10px;
            text-align: center;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }

        .stat-number {
            font-size: 2rem;
            font-weight: 700;
            color: var(--primary-purple);
        }

        .stat-label {
            font-size: 0.85rem;
            color: var(--text-light);
        }

        @media (max-width: 768px) {
            body {
                padding-top: 120px;
            }

            .applications-container {
                padding: 0 1rem;
            }

            .application-header {
                flex-direction: column;
            }

            .application-actions {
                flex-direction: column;
            }

            .stats-bar {
                grid-template-columns: repeat(2, 1fr);
            }
        }
    </style>
</head>
<body>
    <?php include 'Unavigation.view.php'; ?>

    <div class="applications-container">
        <div class="page-header">
            <h1>My Job Applications</h1>
            <p>Track the status of your submitted applications</p>
        </div>

        <!-- Messages -->
        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success">
                <?= htmlspecialchars($_SESSION['success']) ?>
                <?php unset($_SESSION['success']); ?>
            </div>
        <?php endif; ?>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-error">
                <?= htmlspecialchars($_SESSION['error']) ?>
                <?php unset($_SESSION['error']); ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($data['applications'])): ?>
            <!-- Stats -->
            <?php
            $total = count($data['applications']);
            $pending = count(array_filter($data['applications'], fn($a) => $a['status'] === 'pending'));
            $shortlisted = count(array_filter($data['applications'], fn($a) => $a['status'] === 'shortlisted'));
            $interviewed = count(array_filter($data['applications'], fn($a) => $a['status'] === 'interviewed'));
            ?>
            <div class="stats-bar">
                <div class="stat-card">
                    <div class="stat-number"><?= $total ?></div>
                    <div class="stat-label">Total Applications</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number"><?= $pending ?></div>
                    <div class="stat-label">Pending</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number"><?= $shortlisted ?></div>
                    <div class="stat-label">Shortlisted</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number"><?= $interviewed ?></div>
                    <div class="stat-label">Interviewed</div>
                </div>
            </div>

            <!-- Applications List -->
            <div class="applications-grid">
                <?php foreach ($data['applications'] as $application): ?>
                    <div class="application-card">
                        <div class="application-header">
                            <div class="job-info">
                                <h3><?= htmlspecialchars($application['job_title']) ?></h3>
                                <span class="company"><?= htmlspecialchars($application['company_name'] ?? 'Company') ?></span>
                            </div>
                            <span class="status-badge status-<?= $application['status'] ?>">
                                <?= ucfirst(str_replace('_', ' ', $application['status'])) ?>
                            </span>
                        </div>

                        <div class="application-meta">
                            <span class="meta-item">
                                📍 <?= htmlspecialchars($application['location'] ?? 'Remote') ?>
                            </span>
                            <span class="meta-item">
                                💼 <?= ucfirst(str_replace('-', ' ', $application['job_type'] ?? 'full-time')) ?>
                            </span>
                            <span class="meta-item">
                                📅 Applied: <?= date('M d, Y', strtotime($application['applied_at'])) ?>
                            </span>
                        </div>

                        <div class="application-actions">
                            <a href="<?= BASE_URL ?>/ujobs/viewDetails/<?= $application['job_id'] ?>" class="btn btn-secondary">View Job</a>
                            
                            <?php if ($application['status'] === 'pending'): ?>
                                <button onclick="confirmWithdraw(<?= $application['application_id'] ?>)" class="btn btn-danger">Withdraw</button>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="empty-state">
                <div class="empty-icon">📋</div>
                <h3>No Applications Yet</h3>
                <p>You haven't applied to any jobs yet. Start exploring opportunities!</p>
                <a href="<?= BASE_URL ?>/ujobs" class="btn btn-primary">Browse Jobs</a>
            </div>
        <?php endif; ?>
    </div>

    <?php include __DIR__ . '/../../layout/footer.php'; ?>

    <script>
        function confirmWithdraw(applicationId) {
            if (confirm('Are you sure you want to withdraw this application? This action cannot be undone.')) {
                window.location.href = '<?= BASE_URL ?>/ujobs/withdrawApplication/' + applicationId;
            }
        }
    </script>
</body>
</html>
