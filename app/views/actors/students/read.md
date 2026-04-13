<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Degree Result</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/student_degree_result.css">
    <script src="https://kit.fontawesome.com/317f05ac77.js" crossorigin="anonymous"></script>

    <?php include_once __DIR__ . '/includes/header2.view.php'; ?>
</head>

<body>
    <main class="container" data-base-url="<?= BASE_URL ?>">
        <!-- Back Button -->
        <a href="<?= BASE_URL ?>/Degrees/degree_suggestion_index" class="back-btn">← Back</a>
        <h1 class="page-title">Recommendation</h1>

        <!-- Stats Cards -->
        <div class="stats-section">
            <div class="stat-card">
                <label class="stat-label">Z-Score</label>
                <div class="stat-value"><?= htmlspecialchars(ucfirst($zscore) ?? '') ?></div>
            </div>
            <div class="stat-card">
                <label class="stat-label">Stream</label>
                <div class="stat-value"><?= htmlspecialchars(ucfirst($stream) ?? '') ?></div>
            </div>
            <div class="stat-card">
                <label class="stat-label">District</label>
                <div class="stat-value"><?= htmlspecialchars(ucfirst($district) ?? '') ?></div>
            </div>
        </div>

        <!-- Recommended Universities Section -->
        <h2 class="section-title">Recommended Universities and Courses</h2>

        <!-- Table -->
        <div class="table-section">
            <table>
                <thead>
                    <tr>
                        <th>Uni Code</th>
                        <th>University</th>
                        <th>Degree Name</th>
                        <th>Cutoff Mark</th>
                        <th>Details</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($infos)): ?>
                        <?php foreach ($infos as $info): ?>
                            <tr>
                                <td class="uni-code"><?= htmlspecialchars($info['university_code'] ?? '') ?></td>
                                <td><span class="uni-badge"><?= htmlspecialchars($info['university_name'] ?? '') ?></span></td>
                                <td class="degree-name"><?= htmlspecialchars($info['degree_name'] ?? '') ?></td>
                                <td class="cutoff-mark"><?= htmlspecialchars($info['cutoff_mark'] ?? '') ?></td>
                                <td><button class="view-btn">View Details</button></td>
                            </tr>
                </tbody>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="5" class="no-results">No recommendations found based on the provided information.</td>
            </tr>
        <?php endif; ?>
            </table>
        </div>
    </main>


    <div class="modal-backdrop" id="degreeDetailModal" aria-hidden="true" role="dialog" aria-modal="true" aria-labelledby="modalTitle">
        <div class="modal-window" role="document">
            <div class="modal-header">
                <h3 id="modalTitle">Degree Details</h3>
                <button type="button" class="modal-close" aria-label="Close">&times;</button>
            </div>
            <div class="modal-body">
                <dl class="details-list">
                    <dt>Unicode</dt>
                    <dd id="md-unicode"></dd>
                    <dt>University</dt>
                    <dd id="md-university"></dd>
                    <dt>Course Name</dt>
                    <dd id="md-course"></dd>
                    <dt>Cutoff Marks</dt>
                    <dd id="md-cutoff"></dd>
                    <dt>Details</dt>
                    <dd id="md-details"></dd>
                </dl>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn modal-close">Close</button>
            </div>
        </div>
    </div>

    <script>
        window.__APP_ROOT__ = <?= json_encode(BASE_URL) ?>;
    </script>


    <script src="<?= BASE_URL ?>/assets/js/degree_result_modal.js"></script>
</body>

</html>