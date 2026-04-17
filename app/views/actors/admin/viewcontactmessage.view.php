<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Contact Message - UniVerse</title>
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
            max-width: 1000px;
        }

        .card-header {
            margin-bottom: 1.5rem;
        }

        .card-title {
            margin: 0;
            font-size: 2rem;
            font-weight: 700;
            color: #6c47d4;
        }

        .card-subtitle {
            margin-top: 0.35rem;
            color: #64748b;
            font-size: 0.98rem;
        }

        .detail-group {
            margin-bottom: 1.25rem;
        }

        .detail-label {
            display: block;
            font-size: 0.92rem;
            font-weight: 700;
            color: #334155;
            margin-bottom: 0.4rem;
        }

        .detail-value {
            width: 100%;
            min-height: 48px;
            padding: 0.85rem 1rem;
            border: 1px solid #dbe2ea;
            border-radius: 12px;
            background: #ffffff;
            color: #111827;
            font-size: 0.95rem;
            line-height: 1.5;
            word-break: break-word;
        }

        .detail-value.message-box {
            min-height: 180px;
            white-space: pre-wrap;
        }

        .badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0.35rem 0.75rem;
            border-radius: 999px;
            font-size: 0.8rem;
            font-weight: 700;
        }

        .badge-warning {
            background: #fef3c7;
            color: #92400e;
        }

        .badge-success {
            background: #dcfce7;
            color: #166534;
        }

        .form-actions {
            margin-top: 1.5rem;
            display: flex;
            gap: 0.75rem;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            border: none;
            border-radius: 10px;
            padding: 0.7rem 1rem;
            font-size: 0.95rem;
            font-weight: 600;
            cursor: pointer;
        }

        .btn-secondary {
            background: #ede9fe;
            color: #5b21b6;
        }

        .btn-primary {
            background: #6c47d4;
            color: #fff;
        }

        @media (max-width: 992px) {
            .main-content {
                margin-left: 0;
                padding: 1rem;
            }

            .card {
                padding: 1rem;
            }

            .card-title {
                font-size: 1.5rem;
            }
        }
    </style>
</head>
<body>

<?php require_once __DIR__ . '/components/sidebar.php'; ?>

<main class="main-content">
    <div class="card">
        <div class="card-header">
            <h2 class="card-title">Message Details</h2>
            <p class="card-subtitle">Full contact message information</p>
        </div>

        <div class="form-group">
            <label>Name</label>
            <input type="text" class="form-control" value="<?= htmlspecialchars($data['message']['name']) ?>" readonly>
        </div>

        <div class="form-group">
            <label>Email</label>
            <input type="text" class="form-control" value="<?= htmlspecialchars($data['message']['email']) ?>" readonly>
        </div>

        <div class="form-group">
            <label>Subject</label>
            <input type="text" class="form-control" value="<?= htmlspecialchars(ucfirst($data['message']['subject'])) ?>" readonly>
        </div>

       

        <div class="form-group">
            <label>Received At</label>
            <input type="text" class="form-control" value="<?= date('M d, Y h:i A', strtotime($data['message']['created_at'])) ?>" readonly>
        </div>

        <div class="form-group">
            <label>Message</label>
            <textarea class="form-control" rows="8" readonly><?= htmlspecialchars($data['message']['message']) ?></textarea>
        </div>

        <div class="form-actions">
            <a href="<?= BASE_URL ?>/contact/contactmessages" class="btn btn-secondary">Back</a>
        </div>
    </div>
</main>

</body>
</html>