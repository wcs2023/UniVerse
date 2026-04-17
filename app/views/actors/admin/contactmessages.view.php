<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Messages - UniVerse</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/admin.css">
    <style>
        .main-content {
            padding: 2rem;
            margin-left: 280px;
            min-height: 100vh;
            background: #f8fafc;
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

        .card {
            background: #ffffff;
            border-radius: 18px;
            box-shadow: 0 8px 24px rgba(15, 23, 42, 0.08);
            padding: 1.5rem;
            overflow-x: auto;
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

        .filter-bar {
            display: flex;
            gap: 1rem;
            margin-bottom: 1.25rem;
            flex-wrap: wrap;
            align-items: center;
        }

        .form-control {
            min-height: 46px;
            padding: 0.75rem 1rem;
            border: 1px solid #dbe2ea;
            border-radius: 12px;
            font-size: 0.95rem;
            background: #fff;
            outline: none;
            transition: border-color 0.2s ease, box-shadow 0.2s ease;
        }

        .form-control:focus {
            border-color: #6c47d4;
            box-shadow: 0 0 0 4px rgba(108, 71, 212, 0.12);
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            min-width: 900px;
        }

        .table thead th {
            text-align: left;
            padding: 1rem 0.9rem;
            font-size: 0.95rem;
            font-weight: 700;
            color: #1e293b;
            border-bottom: 2px solid #e5e7eb;
            background: #f8fafc;
        }

        .table tbody td {
            padding: 1rem 0.9rem;
            border-bottom: 1px solid #eef2f7;
            font-size: 0.94rem;
            color: #334155;
            vertical-align: middle;
        }

        .table tbody tr:hover {
            background: #faf7ff;
        }

        .action-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            border: none;
            border-radius: 10px;
            padding: 0.55rem 0.9rem;
            font-size: 0.9rem;
            font-weight: 600;
            cursor: pointer;
            margin-right: 0.45rem;
            transition: transform 0.15s ease, opacity 0.2s ease, box-shadow 0.2s ease;
        }

        .action-btn:hover {
            transform: translateY(-1px);
            opacity: 0.95;
        }

        .edit-btn {
            background: #ede9fe;
            color: #5b21b6;
        }

        .delete-btn {
            background: #fee2e2;
            color: #b91c1c;
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

        .empty-state {
            text-align: center;
            padding: 2rem;
            color: #64748b;
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

            .filter-bar {
                flex-direction: column;
                align-items: stretch;
            }

            .filter-bar .form-control,
            .filter-bar .action-btn {
                width: 100%;
                max-width: 100% !important;
            }
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
        <div class="card-header">
            <h2 class="card-title">Contact Messages</h2>
            <p class="card-subtitle">View and manage user contact submissions</p>
        </div>

        <div class="filter-bar">
            <input
                type="text"
                id="searchInput"
                class="form-control"
                placeholder="Search by user ID, name, email, subject..."
                value="<?= htmlspecialchars($_GET['search'] ?? '') ?>"
                style="max-width: 320px;"
            >

            <button type="button" class="action-btn edit-btn" onclick="applyFilters()">Search</button>
            <button type="button" class="action-btn delete-btn" onclick="resetFilters()">Reset</button>
        </div>

        <table class="table">
            <thead>
                <tr>
                    <!-- <th>User ID</th> -->
                    <th>Name</th>
                    <th>Email</th>
                    <th>Subject</th>
                    <th>Received At</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($data['messages'])): ?>
                    <?php foreach ($data['messages'] as $message): ?>
                        <tr>
                            <!-- <td><?= htmlspecialchars($message['user_id'] ?? 'Guest') ?></td> -->
                            <td><?= htmlspecialchars($message['name']) ?></td>
                            <td><?= htmlspecialchars($message['email']) ?></td>
                            <td><?= htmlspecialchars(ucfirst($message['subject'])) ?></td>
                           
                            <td><?= date('M d, Y h:i A', strtotime($message['created_at'])) ?></td>
                            <td>
                                <a href="<?= BASE_URL ?>/contact/viewcontactmessage/<?= $message['contact_id'] ?>" class="action-btn edit-btn">View</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7" class="empty-state">No contact messages found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</main>

<script>
function applyFilters() {
    const search = document.getElementById('searchInput').value.trim();
    const params = new URLSearchParams();

    if (search) params.append('search', search);

    window.location.href = '<?= BASE_URL ?>/contact/contactmessages' + (params.toString() ? '?' + params.toString() : '');
}

function resetFilters() {
    window.location.href = '<?= BASE_URL ?>/contact/contactmessages';
}

document.getElementById('searchInput').addEventListener('keypress', function(e) {
    if (e.key === 'Enter') {
        applyFilters();
    }
});
</script>

</body>
</html>