<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Job - UniVerse</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/company.css">
</head>
<body>

<?php require_once __DIR__ . '/companyHeader.view.php'; ?>
    <main class="main-content">
        <!-- Back Button -->
        <div style="margin-bottom: 2rem;">
            <a href="<?= BASE_URL ?>/company/managejobs" class="btn btn-secondary">
                ← Back to Manage Jobs
            </a>
        </div>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-error">
                <?= $_SESSION['error']; unset($_SESSION['error']); ?>
            </div>
        <?php endif; ?>

        <!-- Debug: Show data structure -->
        <?php if (isset($_GET['debug'])): ?>
            <div style="background: #f0f0f0; padding: 1rem; margin-bottom: 1rem; border: 1px solid #ccc;">
                <h3>Debug Info:</h3>
                <pre><?php 
                    echo "Job Data:\n";
                    print_r($job ?? 'Job variable not set'); 
                    echo "\n\nUser Data:\n";
                    print_r($user ?? 'User variable not set');
                ?></pre>
            </div>
        <?php endif; ?>

        <!-- Edit Job Form -->
        <div class="card">
            <div class="card-header">
                <h2 class="card-title">Edit Job Posting</h2>
                <p class="card-subtitle">Update your job listing details</p>
            </div>

            <form action="<?= BASE_URL ?>/company/editjob/<?= $job['job_id'] ?>" method="POST">
                <div class="form-group">
                    <label for="title">Job Title*</label>
                    <input type="text" id="title" name="title" class="form-control" 
                           value="<?= htmlspecialchars($job['title'] ?? '') ?>" required>
                </div>

                <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 1rem;">
                    <div class="form-group">
                        <label for="job_type">Job Type</label>
                        <select id="job_type" name="job_type" class="form-control">
                            <option value="full-time" <?= ($job['job_type'] ?? '') === 'full-time' ? 'selected' : '' ?>>Full Time</option>
                            <option value="part-time" <?= ($job['job_type'] ?? '') === 'part-time' ? 'selected' : '' ?>>Part Time</option>
                            <option value="internship" <?= ($job['job_type'] ?? '') === 'internship' ? 'selected' : '' ?>>Internship</option>
                            <option value="contract" <?= ($job['job_type'] ?? '') === 'contract' ? 'selected' : '' ?>>Contract</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="location">Location</label>
                        <input type="text" id="location" name="location" class="form-control" 
                               value="<?= htmlspecialchars($job['location'] ?? '') ?>">
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 1rem;">
                    <div class="form-group">
                        <label for="experience_level">Experience Level</label>
                        <select id="experience_level" name="experience_level" class="form-control">
                            <option value="entry" <?= ($job['experience_level'] ?? '') === 'entry' ? 'selected' : '' ?>>Entry Level</option>
                            <option value="mid" <?= ($job['experience_level'] ?? '') === 'mid' ? 'selected' : '' ?>>Mid Level</option>
                            <option value="senior" <?= ($job['experience_level'] ?? '') === 'senior' ? 'selected' : '' ?>>Senior Level</option>
                            <option value="lead" <?= ($job['experience_level'] ?? '') === 'lead' ? 'selected' : '' ?>>Lead</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="application_deadline">Application Deadline</label>
                        <input type="date" id="application_deadline" name="application_deadline" class="form-control" 
                               value="<?= $job['application_deadline'] ?? '' ?>">
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 1rem;">
                    <div class="form-group">
                        <label for="salary_min">Minimum Salary (LKR)</label>
                        <input type="number" id="salary_min" name="salary_min" class="form-control" 
                               value="<?= $job['salary_min'] ?? '' ?>" placeholder="e.g., 50000">
                    </div>

                    <div class="form-group">
                        <label for="salary_max">Maximum Salary (LKR)</label>
                        <input type="number" id="salary_max" name="salary_max" class="form-control" 
                               value="<?= $job['salary_max'] ?? '' ?>" placeholder="e.g., 100000">
                    </div>
                </div>

                <div class="form-group">
                    <label for="description">Job Description</label>
                    <textarea id="description" name="description" class="form-control" rows="6"><?= htmlspecialchars($job['description'] ?? '') ?></textarea>
                </div>

                <div class="form-group">
                    <label for="requirements">Requirements</label>
                    <textarea id="requirements" name="requirements" class="form-control" rows="4"><?= htmlspecialchars($job['requirements'] ?? '') ?></textarea>
                </div>

                <div class="form-group">
                    <label for="responsibilities">Responsibilities</label>
                    <textarea id="responsibilities" name="responsibilities" class="form-control" rows="4"><?= htmlspecialchars($job['responsibilities'] ?? '') ?></textarea>
                </div>

                <div class="form-group">
                    <label for="benefits">Benefits</label>
                    <textarea id="benefits" name="benefits" class="form-control" rows="4"><?= htmlspecialchars($job['benefits'] ?? '') ?></textarea>
                </div>

                <div class="form-group">
                    <label for="work_arrangement">Work Arrangement</label>
                    <select id="work_arrangement" name="work_arrangement" class="form-control">
                        <option value="onsite" <?= ($job['work_arrangement'] ?? '') === 'onsite' ? 'selected' : '' ?>>On-site</option>
                        <option value="remote" <?= ($job['work_arrangement'] ?? '') === 'remote' ? 'selected' : '' ?>>Remote</option>
                        <option value="hybrid" <?= ($job['work_arrangement'] ?? '') === 'hybrid' ? 'selected' : '' ?>>Hybrid</option>
                    </select>
                </div>

                <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 1rem;">
                    <div class="form-group">
                        <label for="contact_email">Contact Email</label>
                        <input type="email" id="contact_email" name="contact_email" class="form-control" 
                               value="<?= htmlspecialchars($job['contact_email'] ?? '') ?>">
                    </div>

                    <div class="form-group">
                        <label for="contact_phone">Contact Phone</label>
                        <input type="tel" id="contact_phone" name="contact_phone" class="form-control" 
                               value="<?= htmlspecialchars($job['contact_phone'] ?? '') ?>"
                               placeholder="+94771234567" pattern="\+94\d{9}">
                        <small style="color: #666; font-size: 0.85rem;">Format: +94xxxxxxxxx (e.g., +94771234567)</small>
                    </div>
                </div>

                <div class="form-group">
                    <label for="status">Job Status</label>
                    <select id="status" name="status" class="form-control">
                        <option value="active" <?= ($job['status'] ?? '') === 'active' ? 'selected' : '' ?>>Active</option>
                        <option value="draft" <?= ($job['status'] ?? '') === 'draft' ? 'selected' : '' ?>>Draft</option>
                        <option value="closed" <?= ($job['status'] ?? '') === 'closed' ? 'selected' : '' ?>>Closed</option>
                    </select>
                </div>

                <div style="display: flex; gap: 1rem; justify-content: flex-end; margin-top: 2rem;">
                    <a href="<?= BASE_URL ?>/company/managejobs" class="btn btn-secondary">Cancel</a>
                    <button type="submit" class="btn btn-primary">Update Job</button>
                </div>
            </form>
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
    </script>