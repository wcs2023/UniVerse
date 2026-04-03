<?php
    $pageTitle = $title ?? 'Discussion Forum';
    include_once __DIR__ . '/Unavigation.view.php';
?>

<body data-base-url="<?= BASE_URL ?>">
    <main class="main-container">
        <section class="forum-header">
            <div class="header-content">
                <h1>Discussion Forum</h1>
                <div class="header-buttons">
                    <?php if (isset($_SESSION['USER'])): ?>
                        <a href="<?= BASE_URL ?>/Udiscussion/view_my_discussion" class="btn btn-secondary"><i class="fa-solid fa-user"></i>My Discussion</a>
                        <a href="<?= BASE_URL ?>/Udiscussion/create_posts" class="btn btn-primary">Start a New Discussion</a>
                    <?php endif; ?>
                </div>
            </div>

            <div class="search-container">
                <i class="fa-solid fa-search"></i>
                <input type="text" id="forum_search" placeholder="Search topic or key words..." class="search-input">
            </div>
        </section>
        
        <?php if (isset($recent_threads) && is_array($recent_threads) && count($recent_threads) > 0): ?>
            <section class="discussion-section">
                <div class="discussion-table">
                    <div class="table-header">
                        <div class="col-topic">Topic</div>
                        <div class="col-Replies">
                            Replies
                        </div>
                        <div class="col-views">Views</div>
                        <div class="col-last-activity">
                            Last Activity
                        </div>
                        <?php if (isset($curr_user_id)): ?>
                            <div class="col-action">Action</div>
                        <?php endif; ?>
                    </div>

                    <?php foreach ($recent_threads as $thread): ?>
                        <div class="discussion-row">
                            <div class="col-topic">
                                <div class="topic-title">
                                    <a href="<?= BASE_URL ?>/Udiscussion/view_thread/<?= $thread['thread_id'] ?? '#' ?>"> <?= htmlspecialchars($thread['title'] ?? 'No title') ?></a>
                                </div>
                                <div class="topic-details">
                                    posted by:<span class="author-name"><?= htmlspecialchars($thread['author_name']) ?></span> in <span class="category-link"><?= htmlspecialchars($thread['category_name']) ?></span>
                                </div>
                            </div>

                            <div class="col-replies">
                                <div class="stat-num">
                                    <?= isset($thread['replies']) ? $thread['replies'] : '0' ?>
                                </div>
                            </div>

                            <div class="col-views">
                                <div class="stat-num">
                                    <?= isset($thread['views']) ? $thread['views'] : '0' ?>
                                </div>
                            </div>

                            <div class="col-last-activity">
                                <div class="activity-details">
                                    <div class="activity-author">by:<?= htmlspecialchars($thread['last_author']) ?></div>
                                    <div class="activity-time">
                                        <?= isset($thread['last_edited']) ? date('M j, Y, g:i A', strtotime($thread['last_edited'])) : date('M j, Y, g:i A') ?>
                                    </div>
                                </div>
                            </div>
                            <?php if (isset($curr_user_id) && $thread['author_id'] == $curr_user_id) : ?>
                                <div class="col-action">
                                    <div class="action-btn">
                                        <a href="<?= BASE_URL ?>/Udiscussion/edit_post/<?= $thread['thread_id'] ?>" class="btn-action btn-edit" data-tooltip="Edit">
                                            <i class="fa-solid fa-pen-to-square">E</i>
                                        </a>
                                        <button class="btn-action btn-delete" data-thread-id="<?= $thread['thread_id'] ?>" data-tooltip="Delete">
                                            <i class="fa-solid fa-trash">D</i>
                                        </button>
                                    </div>
                                </div>
                            <?php elseif (isset($curr_user_id)): ?>
                                <div class="col-action">
                                    <span class="no-action">-</span>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            </section>
        <?php else: ?>
            <section class="discussion-section">
                <div class="no-discussion">
                    <div class="no-content">
                        <i class="fa-solid fa-comments"></i>
                        <h3>No Discussions Found</h3>
                        <p>Be the first one to start a discussion!</p>
                        <a href="<?= BASE_URL ?>/Udiscussion/create_posts" class="btn btn-primary"><i class="fa-solid fa-plus"></i>Start a New Discussion </a>
                    </div>
                </div>
            </section>
        <?php endif; ?>
    </main>
    
    <div id="deleteModal" class="delete-modal hidden">
        <div class="delete-box">
            <h3>Delete Discussion</h3>
            <p>
                Are you sure you want to delete this discussion?
                <strong>This action cannot be undone.</strong>
            </p>
            <div class="delete-action">
                <button id="cancelDelete" class="btn btn-secondary">Cancel</button>
                <button id="confirmDelete" class="btn btn-confirm">Confirm</button>
            </div>
        </div>
    </div>
    
    <style>
        .delete-modal {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 1000;
        }
        .delete-modal.hidden {
            display: none;
        }
        .delete-box {
            background: white;
            padding: 2rem;
            border-radius: 12px;
            max-width: 400px;
            text-align: center;
            box-shadow: 0 10px 25px rgba(0,0,0,0.2);
        }
        .delete-box h3 {
            margin-bottom: 1rem;
            color: #333;
        }
        .delete-box p {
            margin-bottom: 1.5rem;
            color: #666;
        }
        .delete-action {
            display: flex;
            gap: 1rem;
            justify-content: center;
        }
        .btn-confirm {
            background: #dc3545;
            color: white;
            border: none;
            padding: 0.5rem 1.5rem;
            border-radius: 6px;
            cursor: pointer;
        }
        .btn-confirm:hover {
            background: #c82333;
        }
        </style>
        <link rel="stylesheet" href="<?= BASE_URL;?>/css/styles.css">
        <link rel="stylesheet" href="<?= BASE_URL;?>/assets/css/forum/responsive_forum_home.css">
        <link rel="stylesheet" href="<?= BASE_URL;?>/assets/css/forum/forum_home_styles.css"> 


    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const deleteModal = document.getElementById('deleteModal');
            const cancelBtn = document.getElementById('cancelDelete');
            const confirmBtn = document.getElementById('confirmDelete');
            const baseUrl = document.body.getAttribute('data-base-url');
            let threadIdToDelete = null;
            
            // Add click listeners to all delete buttons
            document.querySelectorAll('.btn-delete').forEach(btn => {
                btn.addEventListener('click', function() {
                    threadIdToDelete = this.getAttribute('data-thread-id');
                    deleteModal.classList.remove('hidden');
                });
            });
            
            // Cancel delete
            cancelBtn.addEventListener('click', function() {
                deleteModal.classList.add('hidden');
                threadIdToDelete = null;
            });
            
            // Confirm delete
            confirmBtn.addEventListener('click', function() {
                if (threadIdToDelete) {
                    window.location.href = baseUrl + '/Udiscussion/delete_post/' + threadIdToDelete;
                }
            });
            
            // Close modal on outside click
            deleteModal.addEventListener('click', function(e) {
                if (e.target === deleteModal) {
                    deleteModal.classList.add('hidden');
                    threadIdToDelete = null;
                }
            });
        });
    </script>
<?php include __DIR__ . '/../../layout/footer.php'; ?>

</body>