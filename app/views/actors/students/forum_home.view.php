<head>
    <link rel="stylesheet" href="<?= BASE_URL; ?>/assets/css/forum/forum_home_styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'undergraduate'): ?>
        <link rel="stylesheet" href="<?= BASE_URL ?>/css/styles.css">
        <style>        
            .main-container {
                margin-top: 10vh; /* Adjust this value based on your header height */
            }
        </style>
    <?php endif; ?>
        
    <link rel="icon" type="image/png" href="<?= BASE_URL ?>/assets/images/U.png">
        
    <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'school_leaver'): ?>
        <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/student_style.css">    
    <?php endif; ?>

    <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'alumni'): ?>
        <link rel="stylesheet" href="<?= BASE_URL ?>/css/alumni.css">
        <style>
            .btn-edit{
                padding:1.4rem ;}
        </style>

    <?php endif; ?>

</head>
    
    <?php 
        if ($_SESSION['user_role'] === 'undergraduate') 
        {
            include __DIR__ . '/../undergraduate/Unavigation.view.php';
        }
        else if ($_SESSION['user_role'] === 'school_leaver') 
        {
        include __DIR__ . '/includes/header2.view.php';
        } 
        else if ($_SESSION['user_role'] === 'alumni') 
        {
            include __DIR__ . '/../alumni/Anavbar.php';
        }
        else  
        {
            include __DIR__ . '/../layout/nav_home.php';
        }
    ?>

<body data-base-url="<?= BASE_URL ?>">
    <main class="main-container">
        <section class="forum-header">
            <div class="header-content">
                <h1>Discussion Forum</h1>
                <div class="header-buttons">
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <a href="<?= BASE_URL ?>/Discussion_Forum/view_my_discussion" class="btn btn-secondary"><i class="fa-solid fa-user"></i>My Discussion</a>
                        <a href="<?= BASE_URL ?> /Discussion_Forum/create_posts" class="btn btn-primary">Start a New Discussion</a>
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
                                    <a href="<?= BASE_URL ?>/Discussion_Forum/view_thread/<?= $thread['thread_id'] ?? '#' ?>"><?= htmlspecialchars($thread['title'] ?? 'No title') ?></a>
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
                            <?php if (isset($curr_user_id) && (int)$thread['author_id'] === (int)$curr_user_id) : ?>
                                <div class="col-action">
                                    <div class="action-btn">
                                        <a href="<?= BASE_URL ?>/Discussion_Forum/edit_post/<?= $thread['thread_id'] ?>" class="btn-action btn-edit" data-tooltip="Edit">
                                            <i class="fa-solid fa-pen-to-square"></i>
                                        </a>

                                        <!-- delete button -->
                                        <button class="btn-action btn-delete js-delete-thread" data-thread-id="<?= (int)$thread['thread_id'] ?>" data-tooltip="Delete">
                                            <i class="fa-solid fa-trash"></i>

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
            </section>
    </main>

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
     
    <script src="<?= BASE_URL ?>/assets/js/forum_delete_modal.js"></script>
    <script src="<?= BASE_URL ?>/assets/js/discussion_forum.js"></script>
    <?php include __DIR__ . '/../../layout/footer.php'; ?>

</body>