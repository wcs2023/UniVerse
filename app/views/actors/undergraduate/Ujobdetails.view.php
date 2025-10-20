<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?= BASE_URL ?>/css/styles.css">
    <link rel="icon" type="image/png" href="<?= BASE_URL ?>/assets/images/U.png">
    <title><?= htmlspecialchars($data['job']['title']) ?> - UniVerse</title>
    <style>
        .job-details-container {
            max-width: 1000px;
            margin: 2rem auto;
            padding: 0 2rem;
        }

        .back-link {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            color: var(--primary-purple);
            text-decoration: none;
            font-weight: 600;
            margin-bottom: 2rem;
            padding: 0.5rem 1rem;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .back-link:hover {
            background: rgba(107, 70, 193, 0.1);
            transform: translateX(-3px);
        }

        .job-details-card {
            background: var(--white);
            border-radius: 16px;
            padding: 3rem;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            margin-bottom: 2rem;
        }

        .job-header {
            margin-bottom: 2rem;
            padding-bottom: 2rem;
            border-bottom: 2px solid var(--border-color);
        }

        .company-badge {
            display: inline-block;
            background: rgba(107, 70, 193, 0.1);
            color: var(--primary-purple);
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.9rem;
            margin-bottom: 1rem;
        }

        .job-title-large {
            font-size: 2.5rem;
            color: var(--text-dark);
            font-weight: 700;
            margin-bottom: 1.5rem;
            line-height: 1.2;
        }

        .job-meta-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1.5rem;
            margin-bottom: 1.5rem;
        }

        .meta-box {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 1rem;
            background: var(--light-gray);
            border-radius: 10px;
        }

        .meta-icon-large {
            font-size: 1.5rem;
        }

        .meta-content h4 {
            font-size: 0.85rem;
            color: var(--text-light);
            margin-bottom: 0.25rem;
        }

        .meta-content p {
            font-size: 1rem;
            color: var(--text-dark);
            font-weight: 600;
            margin: 0;
        }

        .salary-banner {
            background: linear-gradient(135deg, var(--primary-purple), var(--secondary-purple));
            color: var(--white);
            padding: 1.5rem;
            border-radius: 12px;
            text-align: center;
            margin-bottom: 2rem;
        }

        .salary-banner h3 {
            font-size: 0.9rem;
            opacity: 0.9;
            margin-bottom: 0.5rem;
        }

        .salary-banner .amount {
            font-size: 2rem;
            font-weight: 700;
        }

        .section {
            margin-bottom: 2.5rem;
        }

        .section-title {
            font-size: 1.5rem;
            color: var(--text-dark);
            font-weight: 700;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .section-content {
            color: var(--text-light);
            line-height: 1.8;
            font-size: 1.05rem;
        }

        .section-content ul {
            list-style: none;
            padding-left: 0;
        }

        .section-content li {
            padding: 0.75rem 0;
            padding-left: 2rem;
            position: relative;
        }

        .section-content li::before {
            content: "✓";
            position: absolute;
            left: 0;
            color: var(--primary-purple);
            font-weight: bold;
            font-size: 1.2rem;
        }

        .skills-grid {
            display: flex;
            flex-wrap: wrap;
            gap: 0.75rem;
        }

        .skill-badge {
            padding: 0.6rem 1.25rem;
            background: rgba(107, 70, 193, 0.1);
            color: var(--primary-purple);
            border-radius: 25px;
            font-weight: 600;
            font-size: 0.95rem;
            border: 2px solid transparent;
            transition: all 0.3s ease;
        }

        .skill-badge:hover {
            border-color: var(--primary-purple);
            transform: translateY(-2px);
        }

        .apply-section {
            background: var(--light-gray);
            padding: 2rem;
            border-radius: 12px;
            text-align: center;
        }

        .apply-section h3 {
            font-size: 1.5rem;
            color: var(--text-dark);
            margin-bottom: 1rem;
        }

        .apply-section p {
            color: var(--text-light);
            margin-bottom: 1.5rem;
        }

        .apply-btn {
            display: inline-block;
            padding: 1rem 3rem;
            background: var(--primary-purple);
            color: var(--white);
            text-decoration: none;
            border-radius: 50px;
            font-weight: 700;
            font-size: 1.1rem;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(107, 70, 193, 0.3);
        }

        .apply-btn:hover {
            background: var(--dark-purple);
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(107, 70, 193, 0.4);
        }

        .deadline-alert {
            background: #fff3cd;
            border-left: 4px solid #ffc107;
            padding: 1rem 1.5rem;
            border-radius: 8px;
            margin-bottom: 2rem;
            color: #856404;
        }

        @media (max-width: 768px) {
            .job-details-container {
                padding: 0 1rem;
            }

            .job-details-card {
                padding: 1.5rem;
            }

            .job-title-large {
                font-size: 1.8rem;
            }

            .job-meta-grid {
                grid-template-columns: 1fr;
            }

            .salary-banner .amount {
                font-size: 1.5rem;
            }
        }
    </style>
</head>
<body>
    <?php include 'Unavigation.view.php'; ?>

    <div class="job-details-container">
        <a href="<?= BASE_URL ?>/ujobs" class="back-link">← Back to Jobs</a>

        <div class="job-details-card">
            <!-- Job Header -->
            <div class="job-header">
                <span class="company-badge"><?= htmlspecialchars($data['job']['company_name'] ?? 'Company Name') ?></span>
                <h1 class="job-title-large"><?= htmlspecialchars($data['job']['title']) ?></h1>

                <div class="job-meta-grid">
                    <div class="meta-box">
                        <span class="meta-icon-large">📍</span>
                        <div class="meta-content">
                            <h4>Location</h4>
                            <p><?= htmlspecialchars($data['job']['location'] ?? 'Remote') ?></p>
                        </div>
                    </div>

                    <div class="meta-box">
                        <span class="meta-icon-large">💼</span>
                        <div class="meta-content">
                            <h4>Job Type</h4>
                            <p><?= ucfirst(str_replace('-', ' ', $data['job']['job_type'])) ?></p>
                        </div>
                    </div>

                    <div class="meta-box">
                        <span class="meta-icon-large">📊</span>
                        <div class="meta-content">
                            <h4>Experience Level</h4>
                            <p><?= ucfirst($data['job']['experience_level']) ?> Level</p>
                        </div>
                    </div>

                    <div class="meta-box">
                        <span class="meta-icon-large">🏢</span>
                        <div class="meta-content">
                            <h4>Work Arrangement</h4>
                            <p><?= ucfirst($data['job']['work_arrangement'] ?? 'Onsite') ?></p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Salary Banner -->
            <?php if ($data['job']['salary_min'] && $data['job']['salary_max']): ?>
                <div class="salary-banner">
                    <h3>Salary Range</h3>
                    <div class="amount">
                        <?= number_format($data['job']['salary_min']) ?> - <?= number_format($data['job']['salary_max']) ?> <?= $data['job']['currency'] ?>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Deadline Alert -->
            <?php if ($data['job']['application_deadline']): ?>
                <div class="deadline-alert">
                    ⏰ <strong>Application Deadline:</strong> <?= date('F d, Y', strtotime($data['job']['application_deadline'])) ?>
                </div>
            <?php endif; ?>

            <!-- Job Description -->
            <div class="section">
                <h2 class="section-title">📄 Job Description</h2>
                <div class="section-content">
                    <p><?= nl2br(htmlspecialchars($data['job']['description'])) ?></p>
                </div>
            </div>

            <!-- Responsibilities -->
            <?php if ($data['job']['responsibilities']): ?>
                <div class="section">
                    <h2 class="section-title">✅ Responsibilities</h2>
                    <div class="section-content">
                        <?php
                        $responsibilities = explode("\n", $data['job']['responsibilities']);
                        echo '<ul>';
                        foreach ($responsibilities as $responsibility) {
                            if (trim($responsibility)) {
                                echo '<li>' . htmlspecialchars(trim($responsibility)) . '</li>';
                            }
                        }
                        echo '</ul>';
                        ?>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Requirements -->
            <?php if ($data['job']['requirements']): ?>
                <div class="section">
                    <h2 class="section-title">📋 Requirements</h2>
                    <div class="section-content">
                        <?php
                        $requirements = explode("\n", $data['job']['requirements']);
                        echo '<ul>';
                        foreach ($requirements as $requirement) {
                            if (trim($requirement)) {
                                echo '<li>' . htmlspecialchars(trim($requirement)) . '</li>';
                            }
                        }
                        echo '</ul>';
                        ?>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Skills Required -->
            <?php if ($data['job']['skills_required']): ?>
                <div class="section">
                    <h2 class="section-title">💡 Skills Required</h2>
                    <div class="skills-grid">
                        <?php
                        $skills = explode(',', $data['job']['skills_required']);
                        foreach ($skills as $skill) {
                            if (trim($skill)) {
                                echo '<span class="skill-badge">' . htmlspecialchars(trim($skill)) . '</span>';
                            }
                        }
                        ?>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Benefits -->
            <?php if ($data['job']['benefits']): ?>
                <div class="section">
                    <h2 class="section-title">🎁 Benefits</h2>
                    <div class="section-content">
                        <?php
                        $benefits = explode("\n", $data['job']['benefits']);
                        echo '<ul>';
                        foreach ($benefits as $benefit) {
                            if (trim($benefit)) {
                                echo '<li>' . htmlspecialchars(trim($benefit)) . '</li>';
                            }
                        }
                        echo '</ul>';
                        ?>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Apply Section -->
            <div class="apply-section">
                <h3>Interested in this opportunity?</h3>
                <p>Submit your application and take the next step in your career journey!</p>
                <a href="<?= BASE_URL ?>/ujobs/apply/<?= $data['job']['job_id'] ?>" class="apply-btn">Apply Now</a>
            </div>
        </div>
    </div>

    <?php include __DIR__ . '/../../layout/footer.php'; ?>

    <script>
        window.onload = function() {
            window.scrollTo(0, 0);
        };
    </script>
</body>
</html>