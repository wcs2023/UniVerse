<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?= BASE_URL ?>/css/styles.css">
    <link rel="icon" type="image/png" href="<?= BASE_URL ?>/assets/images/U.png">
    <title>Apply - <?= htmlspecialchars($data['job']['title']) ?> - UniVerse</title>
    <style>
        body {
            padding-top: 80px;
        }

        .application-container {
            max-width: 800px;
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
            margin-bottom: 1.5rem;
            transition: all 0.3s ease;
        }

        .back-link:hover {
            transform: translateX(-5px);
        }

        .job-summary {
            background: linear-gradient(135deg, var(--primary-purple), var(--secondary-purple));
            color: white;
            border-radius: 12px;
            padding: 2rem;
            margin-bottom: 2rem;
        }

        .job-summary h2 {
            margin: 0 0 0.5rem 0;
            font-size: 1.5rem;
        }

        .job-summary .company {
            opacity: 0.9;
            font-size: 1rem;
        }

        .job-summary .meta {
            display: flex;
            flex-wrap: wrap;
            gap: 1.5rem;
            margin-top: 1rem;
            font-size: 0.9rem;
            opacity: 0.9;
        }

        .application-form-card {
            background: var(--white);
            border-radius: 12px;
            padding: 2.5rem;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        }

        .form-header {
            text-align: center;
            margin-bottom: 2rem;
            padding-bottom: 1.5rem;
            border-bottom: 2px solid var(--border-color);
        }

        .form-header h1 {
            font-size: 1.75rem;
            color: var(--text-dark);
            margin-bottom: 0.5rem;
        }

        .form-header p {
            color: var(--text-light);
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            color: var(--text-dark);
            font-weight: 600;
            font-size: 0.95rem;
        }

        .form-group label .required {
            color: #e53e3e;
        }

        .form-group .hint {
            display: block;
            font-size: 0.85rem;
            color: var(--text-light);
            margin-top: 0.25rem;
        }

        .form-control {
            width: 100%;
            padding: 0.875rem 1rem;
            border: 2px solid var(--border-color);
            border-radius: 8px;
            font-size: 1rem;
            transition: all 0.3s ease;
            background: var(--white);
        }

        .form-control:focus {
            outline: none;
            border-color: var(--primary-purple);
            box-shadow: 0 0 0 3px rgba(107, 70, 193, 0.1);
        }

        textarea.form-control {
            min-height: 200px;
            resize: vertical;
            line-height: 1.6;
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1.5rem;
        }

        .file-upload {
            position: relative;
            border: 2px dashed var(--border-color);
            border-radius: 8px;
            padding: 2rem;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .file-upload:hover {
            border-color: var(--primary-purple);
            background: rgba(107, 70, 193, 0.03);
        }

        .file-upload input[type="file"] {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            opacity: 0;
            cursor: pointer;
        }

        .file-upload-icon {
            font-size: 2.5rem;
            margin-bottom: 0.5rem;
        }

        .file-upload-text {
            color: var(--text-medium);
            font-size: 0.95rem;
        }

        .file-upload-text strong {
            color: var(--primary-purple);
        }

        .file-name {
            margin-top: 0.5rem;
            font-size: 0.9rem;
            color: var(--primary-purple);
            font-weight: 600;
        }

        .form-actions {
            display: flex;
            gap: 1rem;
            margin-top: 2rem;
            padding-top: 1.5rem;
            border-top: 2px solid var(--border-color);
        }

        .btn {
            padding: 1rem 2rem;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            text-align: center;
            border: none;
        }

        .btn-primary {
            background: var(--primary-purple);
            color: white;
            flex: 1;
        }

        .btn-primary:hover {
            background: var(--dark-purple);
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(107, 70, 193, 0.4);
        }

        .btn-secondary {
            background: var(--light-gray);
            color: var(--text-dark);
        }

        .btn-secondary:hover {
            background: var(--border-color);
        }

        .alert {
            padding: 1rem 1.5rem;
            border-radius: 8px;
            margin-bottom: 1.5rem;
        }

        .alert-error {
            background: #fee2e2;
            color: #dc2626;
            border-left: 4px solid #dc2626;
        }

        .alert-success {
            background: #d1fae5;
            color: #047857;
            border-left: 4px solid #047857;
        }

        .tips-section {
            background: #fef3c7;
            border-radius: 8px;
            padding: 1.5rem;
            margin-bottom: 2rem;
        }

        .tips-section h3 {
            color: #92400e;
            font-size: 1rem;
            margin-bottom: 0.75rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .tips-section ul {
            margin: 0;
            padding-left: 1.25rem;
            color: #92400e;
            font-size: 0.9rem;
        }

        .tips-section li {
            margin-bottom: 0.5rem;
        }

        @media (max-width: 768px) {
            body {
                padding-top: 120px;
            }

            .application-container {
                padding: 0 1rem;
            }

            .application-form-card {
                padding: 1.5rem;
            }

            .form-row {
                grid-template-columns: 1fr;
            }

            .form-actions {
                flex-direction: column;
            }

            .job-summary .meta {
                flex-direction: column;
                gap: 0.5rem;
            }
        }
    </style>
</head>
<body>
    <?php include 'Unavigation.view.php'; ?>

    <div class="application-container">
        <a href="<?= BASE_URL ?>/ujobs/viewDetails/<?= $data['job']['job_id'] ?>" class="back-link">← Back to Job Details</a>

        <!-- Job Summary -->
        <div class="job-summary">
            <h2><?= htmlspecialchars($data['job']['title']) ?></h2>
            <p class="company"><?= htmlspecialchars($data['job']['company_name'] ?? 'Company') ?></p>
            <div class="meta">
                <span>📍 <?= htmlspecialchars($data['job']['location'] ?? 'Remote') ?></span>
                <span>💼 <?= ucfirst(str_replace('-', ' ', $data['job']['job_type'] ?? 'full-time')) ?></span>
                <span>📊 <?= ucfirst($data['job']['experience_level'] ?? 'entry') ?> Level</span>
            </div>
        </div>

        <!-- Application Form -->
        <div class="application-form-card">
            <div class="form-header">
                <h1>Submit Your Application</h1>
                <p>Fill out the form below to apply for this position</p>
            </div>

            <!-- Error/Success Messages -->
            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-error">
                    <?= htmlspecialchars($_SESSION['error']) ?>
                    <?php unset($_SESSION['error']); ?>
                </div>
            <?php endif; ?>

            <?php if (isset($_SESSION['success'])): ?>
                <div class="alert alert-success">
                    <?= htmlspecialchars($_SESSION['success']) ?>
                    <?php unset($_SESSION['success']); ?>
                </div>
            <?php endif; ?>

            <!-- Tips Section -->
            <div class="tips-section">
                <h3>💡 Application Tips</h3>
                <ul>
                    <li>Tailor your cover letter to highlight relevant experience</li>
                    <li>Ensure your resume is up-to-date and properly formatted</li>
                    <li>Include a portfolio link if you have relevant work samples</li>
                    <li>Be realistic with your expected salary based on the job level</li>
                </ul>
            </div>

            <form action="<?= BASE_URL ?>/ujobs/submitApplication/<?= $data['job']['job_id'] ?>" method="POST" enctype="multipart/form-data">
                <!-- Cover Letter -->
                <div class="form-group">
                    <label for="cover_letter">
                        Cover Letter <span class="required">*</span>
                    </label>
                    <textarea 
                        name="cover_letter" 
                        id="cover_letter" 
                        class="form-control" 
                        placeholder="Introduce yourself and explain why you're a great fit for this role..."
                        required
                    ><?= htmlspecialchars($_POST['cover_letter'] ?? '') ?></textarea>
                    <span class="hint">Explain your motivation, relevant experience, and what makes you the ideal candidate</span>
                </div>

                <!-- Resume Upload -->
                <div class="form-group">
                    <label for="resume">
                        Resume/CV <span class="required">*</span>
                    </label>
                    <div class="file-upload" id="resume-upload">
                        <div class="file-upload-icon">📄</div>
                        <div class="file-upload-text">
                            <strong>Click to upload</strong> or drag and drop<br>
                            PDF, DOC, DOCX (Max 5MB)
                        </div>
                        <input type="file" name="resume" id="resume" accept=".pdf,.doc,.docx" required>
                        <div class="file-name" id="resume-name"></div>
                    </div>
                </div>

                <!-- Portfolio URL -->
                <div class="form-group">
                    <label for="portfolio_url">Portfolio URL (Optional)</label>
                    <input 
                        type="url" 
                        name="portfolio_url" 
                        id="portfolio_url" 
                        class="form-control" 
                        placeholder="https://your-portfolio.com"
                        value="<?= htmlspecialchars($_POST['portfolio_url'] ?? '') ?>"
                    >
                    <span class="hint">LinkedIn, GitHub, Behance, personal website, etc.</span>
                </div>

                <div class="form-row">
                    <!-- Expected Salary -->
                    <div class="form-group">
                        <label for="expected_salary">Expected Salary (<?= $data['job']['currency'] ?? 'LKR' ?>)</label>
                        <input 
                            type="number" 
                            name="expected_salary" 
                            id="expected_salary" 
                            class="form-control" 
                            placeholder="e.g., 75000"
                            min="0"
                            step="1000"
                            value="<?= htmlspecialchars($_POST['expected_salary'] ?? '') ?>"
                        >
                        <span class="hint">Monthly salary expectation</span>
                    </div>

                    <!-- Availability Date -->
                    <div class="form-group">
                        <label for="availability_date">Available to Start</label>
                        <input 
                            type="date" 
                            name="availability_date" 
                            id="availability_date" 
                            class="form-control"
                            min="<?= date('Y-m-d') ?>"
                            value="<?= htmlspecialchars($_POST['availability_date'] ?? '') ?>"
                        >
                        <span class="hint">When can you start working?</span>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="form-actions">
                    <a href="<?= BASE_URL ?>/ujobs/viewDetails/<?= $data['job']['job_id'] ?>" class="btn btn-secondary">Cancel</a>
                    <button type="submit" class="btn btn-primary">Submit Application →</button>
                </div>
            </form>
        </div>
    </div>

    <?php include __DIR__ . '/../../layout/footer.php'; ?>

    <script>
        // File upload preview
        document.getElementById('resume').addEventListener('change', function(e) {
            const fileName = e.target.files[0]?.name || '';
            document.getElementById('resume-name').textContent = fileName;
            if (fileName) {
                document.getElementById('resume-upload').style.borderColor = '#6b46c1';
                document.getElementById('resume-upload').style.background = 'rgba(107, 70, 193, 0.05)';
            }
        });

        // Scroll to top on load
        window.onload = function() {
            window.scrollTo(0, 0);
        };
    </script>
</body>
</html>
