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
        <h1>Recommendation</h1>
        <p>
    Z-score: <strong><?= htmlspecialchars($zscore) ?></strong> |
    Stream: <strong><?= htmlspecialchars(ucfirst($stream)) ?></strong> |
    District: <strong><?= htmlspecialchars(ucfirst($district)) ?></strong>
  </p>

  <?php if (!empty($rows)): ?>
    <div class="alert">
        No mathches found for the given criteria. Please try different filters.
    </div>
    <?php else: ?>
        <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>University</th>
                    <th>Degree Name</th>
                    <th>Cutoff Mark</th>
                    <th>Uni code</th>
                    <th>Details</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($rows as $row): ?>
                    <tr>
                        <td><?= htmlspecialchars($row->university) ?></td>
                        <td><?= htmlspecialchars($row->course_name) ?></td>
                        <td><?= htmlspecialchars(ucfirst($row->cutoff_mark)) ?></td>
                        <td><?= nl2br(htmlspecialchars($row->details)) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
            </table>
        </div>

    </div>
    <?php endif; ?>
</body>
</html>