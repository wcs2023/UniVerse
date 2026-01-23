<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Degree Result</title>
    <link rel="stylesheet" href="assets/css/degree-result.css">
    <script src="https://kit.fontawesome.com/317f05ac77.js" crossorigin="anonymous"></script>
</head>
<body>
    <div class="container">
        <div class="header">
        <h1>Recommendation</h1>
        <?php
            $zscore = (string)($zscore ?? '');
            $stream = (string)($stream ?? '');
            $district = (string)($district ?? '');
        ?>
        <div class="input-grid">
    <div class="input-card">
       <div class="input-label"> Z-score</div> 
       <div class="input-value"><strong><?= htmlspecialchars($zscore) ?></strong> </div>
    </div>
    <div class="input-card">
       <div class="input-label"> Stream</div> 
       <div class="input-value"><strong><?= htmlspecialchars(ucfirst($stream)) ?></strong> </div>
    </div>
    <div class="input-card">
       <div class="input-label"> District</div> 
       <div class="input-value"><strong><?= htmlspecialchars(ucfirst($district)) ?></strong> </div>
    </div>
  </div>
</div>
  <?php if (!empty($rows)): ?>
    <div class="alert">
        No mathches found for the given criteria. Please try different filters.
    </div>
    <?php else: ?>
        <div class="recommendation-section">
            <div class="section-header">
                <h2>Recommended Universities and Courses</h2>
            </div>
        <div class="table-wrapper">
            <table>
            <thead>
                <tr>
                    <th>Uni code</th>
                    <th>University</th>
                    <th>Degree Name</th>
                    <th>Cutoff Mark</th>
                    <th>Details</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($rows as $row): ?>
                    <tr>
                        <td>
                            <span class="placeholder"><?= htmlspecialchars($row->unicode ?? '') ?></span>
                        </td>
                        <td><span class="university-badge"><?= htmlspecialchars($row->university ?? '') ?></span></td>
                        <td><span class="placeholder"><?= htmlspecialchars($row->course_name ?? '') ?></span></td>
                        <td><span class="placeholder"><?= htmlspecialchars(ucfirst($row->cutoff_marks ?? '')) ?></span></td>
                        <td>
                            <button type="button" class="btn btn-sm view-details-btn" data-id="<?=htmlspecialchars($row->id)?>" aria-haspopup="dialog"
                            aria-control="degreeDetailModal">View Details</button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
            </table>
        </div>
        </div>

        <?php endif; ?>

        <p class="back-link">
            <a href="<?=BASE_URL?>/degree_suggestion.view.php">Back</a>
        </p>
    </div>

    <!-- reusable modal -->

    <div class="modal-backdrop" id="degreeDetailModal" aria-hidden="true" role="dialog" aria-modal="true" aria-labelledby="modalTitle">
        <div class="modal-window" role ="document">
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
</body>
</html>
<script>window.__APP_ROOT__=<?=json_encode(BASE_URL)?>;</script>
<script src="assets/js/degree_result_modal.js"></script>