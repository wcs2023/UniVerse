<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Post a Job - UniVerse</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/company.css">
</head>
<body>

<?php require_once __DIR__ . '/companyHeader.view.php'; ?>
    <main class="main-content">
        <div class="card">
            <div class="card-header">
                <h2 class="card-title">Post a New Job</h2>
                <p class="card-subtitle">Fill in the details below to create a new job listing</p>
            </div>
            
            <form action="<?= BASE_URL ?>/company/postjobs" method="POST">
                <div class="form-group">
                    <label for="title">Job Title*</label>
                    <input type="text" id="title" name="title" class="form-control" required>
                </div>

                <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 1rem;">
                    <div class="form-group">
                        <label for="job_type">Job Type*</label>
                        <select id="job_type" name="job_type" class="form-control" required>
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
                        <label for="experience_level">Experience Level*</label>
                        <select id="experience_level" name="experience_level" class="form-control" required>
                            <option value="">Select Level</option>
                            <option value="entry">Entry Level</option>
                            <option value="mid">Mid Level</option>
                            <option value="senior">Senior Level</option>
                            <option value="lead">Lead</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="application_deadline">Application Deadline*</label>
                        <input type="date" id="application_deadline" name="application_deadline" class="form-control" required>
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 1rem;">
                    <div class="form-group">
                        <label for="salary_min">Minimum Salary (LKR)</label>
                        <input type="number" id="salary_min" name="salary_min" class="form-control" placeholder="e.g., 50000">
                    </div>

                    <div class="form-group">
                        <label for="salary_max">Maximum Salary (LKR)</label>
                        <input type="number" id="salary_max" name="salary_max" class="form-control" placeholder="e.g., 100000">
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
                    <label for="responsibilities">Responsibilities*</label>
                    <textarea id="responsibilities" name="responsibilities" class="form-control" rows="4" required></textarea>
                </div>

                <div class="form-group">
                    <label for="benefits">Benefits</label>
                    <textarea id="benefits" name="benefits" class="form-control" rows="4"></textarea>
                </div>

                <div class="form-group">
                    <label for="work_arrangement">Work Arrangement*</label>
                    <select id="work_arrangement" name="work_arrangement" class="form-control" required>
                        <option value="onsite">On-site</option>
                        <option value="remote">Remote</option>
                        <option value="hybrid">Hybrid</option>
                    </select>
                </div>

                <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 1rem;">
                    <div class="form-group">
                        <label for="contact_email">Contact Email</label>
                        <input type="email" id="contact_email" name="contact_email" class="form-control">
                    </div>

                    <div class="form-group">
                        <label for="contact_phone">Contact Phone</label>
                        <input type="tel" id="contact_phone" name="contact_phone" class="form-control"
                               placeholder="+94771234567" pattern="\+94\d{9}">
                        <small style="color: #666; font-size: 0.85rem;">Format: +94xxxxxxxxx (e.g., +94771234567)</small>
                    </div>
                </div>

                <input type="hidden" name="status" value="active">

                <div style="display: flex; gap: 1rem; justify-content: flex-end; margin-top: 2rem;">
                    <button type="button" class="btn btn-secondary" onclick="saveDraft()">Save as Draft</button>
                    <button type="submit" class="btn btn-primary">Post Job</button>
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

        function saveDraft() {
            document.querySelector('input[name="status"]').value = 'draft';
            document.querySelector('form').submit();
        }
    </script>