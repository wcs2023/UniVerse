<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Import Degree Programs - UniVerse</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/admin.css">
    <style>
        .main-content {
            padding: 2rem;
            margin-left: 280px;
            min-height: 100vh;
            background: #f8fafc;
        }

        .card {
            background: #fff;
            border-radius: 18px;
            box-shadow: 0 8px 24px rgba(15, 23, 42, 0.08);
            padding: 1.5rem;
            max-width: 900px;
        }

        .card-title {
            margin: 0 0 0.4rem;
            font-size: 2rem;
            font-weight: 700;
            color: #6c47d4;
        }

        .card-subtitle {
            color: #64748b;
            margin-bottom: 1.5rem;
        }

        .alert {
            padding: 14px 18px;
            border-radius: 12px;
            margin-bottom: 1rem;
            font-size: 0.95rem;
            font-weight: 500;
        }

        .alert-success {
            background: #dcfce7;
            color: #166534;
            border: 1px solid #86efac;
        }

        .alert-error {
            background: #fee2e2;
            color: #991b1b;
            border: 1px solid #fca5a5;
        }

        .form-group {
            margin-bottom: 1.2rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.45rem;
            font-weight: 600;
            color: #334155;
        }

        .form-control {
            width: 100%;
            min-height: 48px;
            padding: 0.85rem 1rem;
            border: 1px solid #dbe2ea;
            border-radius: 12px;
            background: #fff;
            font-size: 0.95rem;
        }

        .help-box {
            background: #f8fafc;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            padding: 1rem;
            margin-bottom: 1.5rem;
            color: #475569;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border: none;
            border-radius: 10px;
            padding: 0.8rem 1.2rem;
            font-size: 0.95rem;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
        }

        .btn-primary {
            background: #6c47d4;
            color: #fff;
        }

        code {
            background: #eef2ff;
            padding: 2px 6px;
            border-radius: 6px;
        }
    </style>
</head>
<body>

<?php require_once __DIR__ . '/components/sidebar.php'; ?>

<main class="main-content">
    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success">
            <?= $_SESSION['success']; unset($_SESSION['success']); ?>
        </div>
    <?php endif; ?>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-error">
            <?= $_SESSION['error']; unset($_SESSION['error']); ?>
        </div>
    <?php endif; ?>

    <div class="card">
        <h2 class="card-title">Import Degree Programs</h2>
        <p class="card-subtitle">Upload a CSV file to insert or update degree programs.</p>

        <div class="help-box">
            <strong>Required CSV columns:</strong><br>
            <code>degree_name</code>, <code>unicode</code>, <code>university_name</code>, <code>stream</code>, <code>details</code>
        </div>

        <form action="<?= BASE_URL ?>/admin/importdegreeprogramcsv" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="csv_file">Choose CSV File</label>
                <input type="file" id="csv_file" name="csv_file" class="form-control" accept=".csv" required>
            </div>

            <button type="submit" class="btn btn-primary">Upload and Import</button>
        </form>
    </div>
</main>

</body>
</html>