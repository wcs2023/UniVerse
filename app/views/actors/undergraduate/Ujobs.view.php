<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?= BASE_URL ?>/css/styles.css">
    <link rel="icon" type="image/png" href="<?= BASE_URL ?>/assets/images/U.png">
    <title>Job Opportunities - UniVerse</title>
    <style>
        body {
            padding-top: 80px;
        }

        .jobs-container {
            max-width: 1400px;
            margin: 2rem auto;
            padding: 0 2rem;
        }

        .jobs-header {
            text-align: center;
            margin-bottom: 3rem;
            margin-top: 2rem;
        }

        .jobs-header h1 {
            font-size: 2.5rem;
            color: var(--text-dark);
            margin-bottom: 0.5rem;
        }

        .jobs-header p {
            color: var(--text-light);
            font-size: 1.1rem;
        }

        /* Filter Section */
        .filter-section {
            background: var(--white);
            padding: 2rem;
            border-radius: 12px;
            margin-bottom: 2rem;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }

        .filter-form {
            display: grid;
            grid-template-columns: 1fr 1fr 1.5fr;
            gap: 1rem;
            align-items: end;
        }

        .filter-buttons {
            grid-column: 1 / -1;
            display: flex;
            gap: 1rem;
            justify-content: flex-end;
            margin-top: 1rem;
        }

        .filter-group label {
            display: block;
            margin-bottom: 0.5rem;
            color: var(--text-dark);
            font-weight: 600;
            font-size: 0.9rem;
        }

        .filter-select {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 2px solid var(--border-color);
            border-radius: 8px;
            background: var(--white);
            font-size: 0.95rem;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .filter-select:focus {
            outline: none;
            border-color: var(--primary-purple);
            box-shadow: 0 0 0 3px rgba(107, 70, 193, 0.1);
        }

        .filter-select:hover {
            border-color: var(--light-purple);
        }

        .filter-btn {
            padding: 0.875rem 2rem;
            background: var(--primary-purple);
            color: var(--white);
            border: none;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 1rem;
            min-width: 150px;
        }

        .filter-btn:hover {
            background: var(--dark-purple);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(107, 70, 193, 0.3);
        }

        .clear-btn {
            background: var(--white);
            color: var(--primary-purple);
            border: 2px solid var(--primary-purple);
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0.875rem 2rem;
            border-radius: 8px;
            font-weight: 600;
            font-size: 1rem;
            min-width: 150px;
            transition: all 0.3s ease;
        }

        .clear-btn:hover {
            background: var(--primary-purple);
            color: var(--white);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(107, 70, 193, 0.3);
        }

        .my-apps-btn {
            background: var(--secondary-purple);
            color: var(--white);
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0.875rem 2rem;
            border-radius: 8px;
            font-weight: 600;
            font-size: 1rem;
            min-width: 180px;
            transition: all 0.3s ease;
            margin-right: auto;
        }

        .my-apps-btn:hover {
            background: var(--dark-purple);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(139, 92, 246, 0.4);
        }

        .my-apps-btn::before {
            content: "📋 ";
            margin-right: 0.5rem;
        }

        /* Jobs Grid */
        .jobs-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 2rem;
            margin-bottom: 3rem;
        }

        .job-card {
            background: var(--white);
            border-radius: 12px;
            padding: 2rem;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
            border: 1px solid var(--border-color);
            cursor: pointer;
        }

        .job-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.12);
            border-color: var(--primary-purple);
        }

        .job-card-header {
            margin-bottom: 1.5rem;
        }

        .company-name {
            color: var(--primary-purple);
            font-weight: 600;
            font-size: 0.9rem;
            margin-bottom: 0.5rem;
            display: block;
        }

        .job-title {
            font-size: 1.4rem;
            color: var(--text-dark);
            font-weight: 700;
            margin-bottom: 0.75rem;
            line-height: 1.3;
        }

        .job-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
            margin-bottom: 1rem;
        }

        .meta-item {
            display: flex;
            align-items: center;
            gap: 0.4rem;
            color: var(--text-light);
            font-size: 0.9rem;
        }

        .meta-icon {
            font-size: 1rem;
        }

        .job-tags {
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
            margin-bottom: 1rem;
        }

        .job-tag {
            padding: 0.4rem 0.875rem;
            background: rgba(107, 70, 193, 0.1);
            color: var(--primary-purple);
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
        }

        .job-description {
            color: var(--text-light);
            line-height: 1.6;
            margin-bottom: 1.5rem;
            display: -webkit-box;
            -webkit-line-clamp: 3;
            line-clamp: 3 ;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .job-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-top: 1rem;
            border-top: 1px solid var(--border-color);
        }

        .salary-range {
            color: var(--primary-purple);
            font-weight: 700;
            font-size: 1.1rem;
        }

        .view-btn {
            padding: 0.5rem 1.25rem;
            background: var(--primary-purple);
            color: var(--white);
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            font-size: 0.9rem;
            transition: all 0.3s ease;
        }

        .view-btn:hover {
            background: var(--dark-purple);
            transform: translateX(3px);
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 4rem 2rem;
            background: var(--white);
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }

        .empty-state-icon {
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
            font-size: 1rem;
        }

        /* Responsive */
        @media (max-width: 768px) {
            body {
                padding-top: 120px;
            }

            .jobs-container {
                padding: 0 1rem;
            }

            .jobs-header {
                margin-top: 1rem;
            }

            .jobs-header h1 {
                font-size: 2rem;
            }

            .jobs-grid {
                grid-template-columns: 1fr;
                gap: 1.5rem;
            }

            .filter-form {
                grid-template-columns: 1fr;
            }

            .filter-buttons {
                flex-direction: column;
                width: 100%;
            }

            .filter-btn,
            .clear-btn {
                width: 100%;
            }

            .job-card {
                padding: 1.5rem;
            }

            .job-title {
                font-size: 1.2rem;
            }
        }

        @media (max-width: 480px) {
            body {
                padding-top: 140px;
            }
        }
    </style>
</head>
<body>
    <?php include 'Unavigation.view.php'; ?>

    <div class="jobs-container">
        <!-- Page Header -->
        <div class="jobs-header">
            <h1>Job Opportunities</h1>
            <p>Find your dream career opportunity</p>
        </div>

        <!-- Filter Section -->
        <div class="filter-section">
            <form method="GET" action="<?= BASE_URL ?>/ujobs" class="filter-form">
                <div class="filter-group">
                    <label for="job_type">Job Type</label>
                    <select name="job_type" id="job_type" class="filter-select">
                        <option value="">All Types</option>
                        <option value="full-time" <?= ($data['filters']['job_type'] ?? '') === 'full-time' ? 'selected' : '' ?>>Full Time</option>
                        <option value="part-time" <?= ($data['filters']['job_type'] ?? '') === 'part-time' ? 'selected' : '' ?>>Part Time</option>
                        <option value="internship" <?= ($data['filters']['job_type'] ?? '') === 'internship' ? 'selected' : '' ?>>Internship</option>
                        <option value="contract" <?= ($data['filters']['job_type'] ?? '') === 'contract' ? 'selected' : '' ?>>Contract</option>
                    </select>
                </div>

                <div class="filter-group">
                    <label for="experience_level">Experience Level</label>
                    <select name="experience_level" id="experience_level" class="filter-select">
                        <option value="">All Levels</option>
                        <option value="entry" <?= ($data['filters']['experience_level'] ?? '') === 'entry' ? 'selected' : '' ?>>Entry Level</option>
                        <option value="mid" <?= ($data['filters']['experience_level'] ?? '') === 'mid' ? 'selected' : '' ?>>Mid Level</option>
                        <option value="senior" <?= ($data['filters']['experience_level'] ?? '') === 'senior' ? 'selected' : '' ?>>Senior Level</option>
                    </select>
                </div>

                <div class="filter-group">
                    <label for="location">Location</label>
                    <input type="text" name="location" id="location" class="filter-select" 
                           placeholder="Enter location" 
                           value="<?= htmlspecialchars($data['filters']['location'] ?? '') ?>">
                </div>

                <div class="filter-buttons">
                    <a href="<?= BASE_URL ?>/ujobs/myApplications" class="my-apps-btn">My Applications</a>
                    <a href="<?= BASE_URL ?>/ujobs" class="clear-btn">Clear Filters</a>
                    <button type="submit" class="filter-btn">Apply Filters</button>
                </div>
            </form>
        </div>

        <!-- Jobs Grid -->
        <?php if (!empty($data['jobs'])): ?>
            <div class="jobs-grid">
                <?php foreach ($data['jobs'] as $job): ?>
                    <div class="job-card" onclick="window.location.href='<?= BASE_URL ?>/ujobs/viewDetails/<?= $job['job_id'] ?>'">
                        <div class="job-card-header">
                            <span class="company-name"><?= htmlspecialchars($job['company_name'] ?? 'Company Name') ?></span>
                            <h3 class="job-title"><?= htmlspecialchars($job['title']) ?></h3>
                        </div>

                        <div class="job-meta">
                            <span class="meta-item">
                                <span class="meta-icon">Location:</span>
                                <b>
                                    <?= htmlspecialchars($job['location'] ?? 'Remote') ?>
                                </b>
                            </span>
                            <!-- <span class="meta-item">
                                <span class="meta-icon">Type:</span>
                                <b>
                                    <?= ucfirst(str_replace('-', ' ', $job['job_type'])) ?>
                                </b>
                            </span> -->
                            <span class="meta-item">
                                <span class="meta-icon">Experience:</span>
                                <b>
                                    <?= ucfirst($job['experience_level']) ?> Level
                                </b>
                            </span>
                        </div>

                        <div class="job-tags">
                            <!-- <span class="job-tag"><?= ucfirst(str_replace('-', ' ', $job['job_type'])) ?></span> -->
                            <!-- <span class="job-tag"><?= ucfirst($job['work_arrangement'] ?? 'onsite') ?></span> -->
                            <?php if ($job['application_deadline'] && strtotime($job['application_deadline']) > time()): ?>
                                <span class="job-tag">Application deadline: <?= date('M d', strtotime($job['application_deadline'])) ?></span>
                            <?php endif; ?>
                        </div>

                        <p class="job-description">
                            <?= htmlspecialchars(substr($job['description'], 0, 150)) ?>...
                        </p>

                        <div class="job-footer">
                            <?php if ($job['salary_min'] && $job['salary_max']): ?>
                                <span class="salary-range">
                                    <?= number_format($job['salary_min']) ?> - <?= number_format($job['salary_max']) ?> <?= $job['currency'] ?>
                                </span>
                            <?php else: ?>
                                <span class="salary-range">Competitive Salary</span>
                            <?php endif; ?>
                            <a href="<?= BASE_URL ?>/ujobs/viewDetails/<?= $job['job_id'] ?>" class="view-btn" onclick="event.stopPropagation()">View Details</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="empty-state">
                <div class="empty-state-icon">--</div>
                <h3>No Jobs Found</h3>
                <p>Try adjusting your filters to see more opportunities.</p>
            </div>
        <?php endif; ?>
    </div>

    <?php include __DIR__ . '/../../layout/footer.php'; ?>

    <script>
        // Auto-scroll to top on page load
        window.onload = function() {
            window.scrollTo(0, 0);
        };

        // Mobile menu toggle
        const mobileMenuBtn = document.getElementById('mobile-menu-btn');
        const navMenu = document.getElementById('nav-menu');

        if (mobileMenuBtn) {
            mobileMenuBtn.addEventListener('click', function() {
                navMenu.classList.toggle('active');
                mobileMenuBtn.classList.toggle('active');
            });
        }
    </script>
</body>
</html>