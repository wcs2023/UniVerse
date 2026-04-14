<head>
    <link rel="stylesheet" href="<?= BASE_URL; ?>/assets/css/forum/forum_my_Dis_styles.css">
</head>
<?php
$pageTitle = $title ?? 'My Discussion';
include_once __DIR__ . '/includes/header2.view.php';
?>

<body data-base-url="<?= BASE_URL ?>">
    <div class="form-container">
        <div class="form-header">
            <div>
                <h1>My Discussion</h1>
                <p class="welcome-msg">Hello,<?= htmlspecialchars($user_name) ?>! Here are all your threads</p>
            </div>
            <div class="action-btn">
                <a href="<?= BASE_URL ?>/Discussion_Forum/index" class="btn btn-secondary"><i class="fa-solid fa-arrow-left"></i>Back to the Forum</a>
                <a href="<?= BASE_URL ?>/Discussion_Forum/create_posts" class="btn btn-primary"><i class="fa-solid fa-plus"></i>New Discussion</a>
            </div>
        </div>


        <div class="stat-bar">
            <div class="stat-item">
                <div class="stat-num"><?= count($threads) ?></div>
                <div class="stat-label">Total Discussions</div>
            </div>

            <div class="stat-item">
                <div class="stat-num"><?= array_sum(array_column($threads, 'replies')) ?></div>
                <div class="stat-label">Total Replies</div>
            </div>

            <div class="stat-item">
                <div class="stat-num"><?= array_sum(array_column($threads, 'views')) ?></div>
                <div class="stat-label">Total Views</div>
            </div>
        </div>
        <?php if (!empty($threads)): ?>
            <div class="forum-table">
                <table>
                    <thead>
                        <tr>
                            <th>Discussion</th>
                            <th>Replies</th>
                            <th>Views</th>
                            <th>Last Activity</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($threads as $thread): ?>
                            <tr>
                                <td>
                                    <a href="<?= BASE_URL ?>/Discussion_Forum/view_thread/<?= $thread['thread_id'] ?>" class="thread-title"><?= htmlspecialchars($thread['title']) ?></a>
                                    <br>
                                    <span class="category-name"><?= htmlspecialchars($thread['cat_name']) ?></span>
                                </td>
                                <td class="stat-cell"><?= $thread['replies'] ?></td>
                                <td class="stat-cell"><?= $thread['views'] ?></td>
                                <td class="date-cell"><?php
                                                        $lastPostTime = $thread['last_posted_at'] ?: $thread['created_at'];
                                                        echo date('M d, Y g:i A', strtotime($lastPostTime));
                                                        ?></td>
                                <td class="action-cell">

                                    <a href="<?= BASE_URL ?>/Discussion_Forum/edit_post/<?= $thread['thread_id'] ?>" class="btn-action btn-edit" data-tooltip="Edit">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </a>
                                    <button class="btn-action btn-delete js-delete-thread" data-thread-id="<?= (int)$thread['thread_id'] ?>" data-tooltip="Delete">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div class="no-discussion">
                    <div class="no-content">
                        <i class="fa-solid fa-comments"></i>
                        <h3>No Discussions Found</h3>
                        <p>Be the first one to start a discussion!</p>
                        <a href="<?= BASE_URL ?>/Discussion_Forum/create_posts" class="btn btn-primary"><i class="fa-solid fa-plus"></i>Start a New Discussion </a>

                    </div>
                </div>
            <?php endif; ?>


            </div>
    </div>

    <div id="deleteModal" class="delete-model hidden" aria-hidden="true">
        <div class="delete-box" role="dialog" aria-model="true" aria-labelledby="deleteTitle">
            <h3 id="deleteTitle">Delete Discussion</h3>
            <p>
                Are you sure you want to delete this discussion ?<br>
                <strong>This action cannot be undone.</strong>
            </p>
            <div class="delete-action">
                <button id="cancelDelete" type="button" class="btn btn-secondary">Cancel</button>
                <a id="confirmDelete" class="btn btn-confirm" href="#">Confirm</a>
            </div>
        </div>
    </div>
    <?php include __DIR__ . '/../../layout/footer.php'; ?>
</body>

<script src="<?= BASE_URL ?>/assets/js/forum_delete_modal.js"></script>