<head>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/forum/forum_single_styles.css">
    <style>
        .reply-actions .btn-action {
            appearance: none;
            border: 1px solid transparent;
            background: #f8fafc;
            color: #846f9a;
            cursor: pointer;
            font-size: 0.875rem;
            font-weight: 600;
            padding: 0.45rem 0.9rem;
            border-radius: 10px;
            transition: all 0.25s ease;
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            line-height: 1;
        }
        .action-btn.js-thread-like.active {
            color: #810fec;
            font-weight: 700;
        }

        .action-btn.js-thread-dislike.active {
            color: #dc2626;
            font-weight: 700;
        }

        .reply-action.js-reply-like.active {
            color: #8b25eb;
            font-weight: 700;
        }

        .reply-action.js-reply-dislike.active {
            color: #dc2626;
            font-weight: 700;
        }
        .reply-actions .btn-action i {
            font-size: 0.9rem;
        }

        .reply-actions .btn-action:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 16px rgba(0, 0, 0, 0.08);
        }

        /* Edit button */
        .reply-actions .btn-action.btn-edit {
            background: rgba(59, 130, 246, 0.08);
            color: #8b25eb;
            border-color: rgba(59, 130, 246, 0.18);
        }

        .reply-actions .btn-action.btn-edit:hover {
            background: #8b25eb;
            color: #ffffff;
            border-color: #8b25eb;
        }

        /* Delete button */
        .reply-actions .btn-action.btn-delete {
            background: rgba(239, 68, 68, 0.08);
            color: #dc2626;
            border-color: rgba(239, 68, 68, 0.18);
        }

        .reply-actions .btn-action.btn-delete:hover {
            background: #dc2626;
            color: #ffffff;
            border-color: #dc2626;
        }

        .reply-actions .btn-action:focus-visible {
            outline: 2px solid #7c3aed;
            outline-offset: 2px;
        }
    </style>
    <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'undergraduate'): ?>
        <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/styles.css">    
        <style>
        .go-back
        {
            margin-top: 7rem;       
        }    
        .post-card
        {
            margin-top: 2rem;
        }
        </style>
    <?php endif; ?>
        
    <link rel="icon" type="image/png" href="<?= BASE_URL ?>/assets/images/U.png">
    
    <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'school_leaver'): ?>
        <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/student_style.css">    
    <?php endif; ?>
        
    <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'alumni'): ?>
        <!-- <link rel="stylesheet" href="<//?= BASE_URL ?>/assets/css/alumni.css">    -->
        <style>
        /* Alumni forum reply form - scoped fix */
        #alumni-reply-form {
            width: 100%;
        }

        #alumni-reply-section {
            display: flex;
            gap: 20px;
            margin-top: 30px;
            margin-bottom: 0;
            padding-bottom: 0;
            border-bottom: none;
            align-items: flex-start;
            width: 100%;
        }

        #alumni-reply-avatar {
            width: 40px;
            height: 40px;
            min-width: 40px;
            flex: 0 0 40px;
            border-radius: 50%;
            background: linear-gradient(135deg, #7c3aed, #a78bfa);
            display: block;
        }

        #alumni-reply-box {
            flex: 1;
            width: 100%;
            min-width: 0;
        }

        #alumni-reply-box textarea {
            width: 100%;
            min-height: 120px;
            height: auto;
            max-width: 100%;
            padding: 12px;
            border-radius: 8px;
            border: 1px solid #ddd;
            font-size: 14px;
            box-sizing: border-box;
            resize: vertical;
            display: block;
        }

        @media (max-width: 480px) {
            #alumni-reply-section {
                flex-direction: column;
                gap: 10px;
            }

            #alumni-reply-avatar {
                width: 40px;
                height: 40px;
                min-width: 40px;
                flex: 0 0 40px;
                align-self: flex-start;
            }

            #alumni-reply-box textarea {
                min-height: 100px;
                font-size: 12px;
                padding: 10px;
            }

            #alumni-reply-buttons {
                gap: 8px;
            }
        }
        </style>
    <?php endif; ?>
    <?php
        $pageTitle = $title ?? '';
    ?>
    <title>Forum - <?= htmlspecialchars($pageTitle) ?></title>
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


<?php $current_user_id = $_SESSION['user_id'] ?? null; ?>

<main class="container" data-base-url="<?= BASE_URL ?>">

    <div class="go-back">
        <a href="<?= BASE_URL ?>/Discussion_Forum/index">← Back to Home</a>
    </div>

    <!-- Post -->
    <div class="post-card">

        <div class="post-header">
            <div class="post-title-section">
                <h1><?= htmlspecialchars($thread['title']) ?></h1>

                <div class="post-meta">
                    <span>Posted by</span>
                    <span class="author"><?= htmlspecialchars($thread['author_name']) ?></span>
                    <span>in</span>
                    <a href="#"><?= htmlspecialchars($thread['cat_name']) ?></a>
                </div>

                <?php
                $wordCount = str_word_count(strip_tags($thread['content']));
                $readTime = max(1, ceil($wordCount / 200));
                ?>

                <div class="post-timestamp">
                    <?= htmlspecialchars($thread['created_at']) ?> · <?= $readTime ?> min read
                </div>
            </div>
        </div>

        <div class="post-content">
            <p><?= nl2br(htmlspecialchars($thread['content'])) ?></p>
        </div>

        <div class="post-stats">
            <div class="stat"><strong class="js-thread-like-count"><?= htmlspecialchars($thread['likes']) ?></strong><span> likes</span></div>
            <div class="stat"><strong class="js-thread-dislike-count"><?= htmlspecialchars($thread['dislikes']) ?></strong><span> dislikes</span></div>
            <div class="stat"><strong><?= htmlspecialchars($thread['replies']) ?></strong><span> replies</span></div>
            <div class="stat"><strong><?= htmlspecialchars($thread['views']) ?></strong><span> views</span></div>
        </div>

        <div class="post-actions">
            <button class="action-btn js-thread-like <?= $thread['user_vote'] == 1 ? 'active' : '' ?>" type="button" data-thread-id="<?= (int)$thread['thread_id'] ?>"><i class="fa-regular fa-thumbs-up"></i> Like</button>
            <button class="action-btn js-thread-dislike <?= $thread['user_vote'] == -1 ? 'active' : '' ?>" type="button" data-thread-id="<?= (int)$thread['thread_id'] ?>"><i class="fa-regular fa-thumbs-down"></i> Dislike</button>
        </div>

    </div>

    <!-- Replies -->
    <div class="replies-card">

        <?php if (!empty($posts)): ?>
            <div class="replies-header">
                <h2><i class="fa-solid fa-comments"></i> Replies (<?= count($posts) ?>)</h2>
            </div>
            <?php foreach ($posts as $post): ?>
                <div class="reply" id="reply-<?= htmlspecialchars($post['post_id']) ?>">
                    <div class="reply-avatar"></div>

                    <div class="reply-content">
                        <div class="reply-header">
                            <span class="reply-author"><?= htmlspecialchars($post['author_name']) ?></span>
                            <span class="reply-time"><?= htmlspecialchars($post['created_at']) ?></span>
                        </div>

                        <p class="reply-text js-reply-text"
                            data-raw="<?= htmlspecialchars($post['content'], ENT_QUOTES) ?>">
                            <?= nl2br(htmlspecialchars($post['content'])) ?>
                        </p>

                        <!-- ----------------------------here------------- -->
                        <form class="reply-edit-form js-reply-edit-form hidden" method="post" action="<?= BASE_URL ?>/Discussion_Forum/update_reply/<?= (int)$post['post_id'] ?>">
                            <textarea name="content" class="js-edit-textarea" required minlength="10"></textarea>

                            <div class="reply-buttons" style="margin-top:10px;">
                                <button type="submit" class="submit-btn">Save</button>
                                <button type="button" class="cancel-btn js-cancel-edit">Cancel</button>

                            </div>
                        </form>

                        <div class="reply-actions">
                            <button class="reply-action js-reply-like <?= ($post['user_vote'] ?? 0) == 1 ? 'active' : '' ?>" type="button" data-post-id="<?= (int)$post['post_id'] ?>">
                                <span class="js-reply-like-count"><?= $post['likes'] ?> </span>
                                <i class="fa-regular fa-thumbs-up"></i> likes
                            </button>
                            <button class="reply-action js-reply-dislike <?= ($post['user_vote'] ?? 0) == -1 ? 'active' : '' ?>" type="button" data-post-id="<?= (int)$post['post_id'] ?>">
                                <span class="js-reply-dislike-count"><?= $post['dislikes'] ?> </span>
                                <i class="fa-regular fa-thumbs-down"></i> dislikes
                            </button>
                            <?php if ($current_user_id !== null && $current_user_id === $post['author_id']): ?>
                                <div class="post-actions">
                                    <button type="button"
                                        class="btn-action btn-edit js-edit-reply"
                                        data-post-id="<?= (int)$post['post_id'] ?>">
                                        <i class="fa-solid fa-edit"></i> Edit
                                    </button>
                                    <button class="btn-action btn-delete" onclick="openDeleteModal(<?= (int)$post['post_id'] ?>)">
                                        <i class="fa-solid fa-trash"></i> Delete
                                    </button>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>

        <!-- Reply Form -->
        <?php if (isset($_SESSION['USER'])): ?>
            <!-- <form action="<?= BASE_URL ?>/Discussion_Forum/reply_post/<?= $thread['thread_id'] ?>" method="post">
                <div class="reply-input-section">
                    <div class="avatar"></div>

                    <div class="reply-input-box">
                        <textarea
                            name="content"
                            id="reply-content"
                            placeholder="Write your reply here..."
                            required
                            minlength="10"></textarea>

                        <div class="reply-buttons">
                            <button class="submit-btn" type="submit"><i class="fa-solid fa-paper-plane"></i> Post Reply</button>
                            <button class="cancel-btn" type="reset">Cancel</button>
                        </div>
                    </div>
                </div>
            </form> -->
            <form id="alumni-reply-form" action="<?= BASE_URL ?>/Discussion_Forum/reply_post/<?= $thread['thread_id'] ?>" method="post">
    <div id="alumni-reply-section" class="reply-input-section">
        <div id="alumni-reply-avatar" class="avatar"></div>

        <div id="alumni-reply-box" class="reply-input-box">
            <textarea
                name="content"
                id="reply-content"
                placeholder="Write your reply here..."
                required
                minlength="10"></textarea>

            <div id="alumni-reply-buttons" class="reply-buttons">
                <button class="submit-btn" type="submit">
                    <i class="fa-solid fa-paper-plane"></i> Post Reply
                </button>
                <button class="cancel-btn" type="reset">Cancel</button>
            </div>
        </div>
    </div>
</form>
        <?php else: ?>
            <p class="login-prompt">Please <a href="<?= BASE_URL ?>/users/login">log in</a> to post a reply.</p>
        <?php endif; ?>

    </div>


</main>

<div id="deleteModal" class="modal-overlay" aria-hidden="true">
    <div class="modal">
        <h3>Delete reply ?</h3>
        <p>This action can not be undone.</p>

        <div class="modal-actions">
            <button type="button" class="modal-btn cancel" data-modal-cancel>Cancel</button>

            <form action="" id="deleteForm" method="post">
                <button type="submit" class="modal-btn danger">Yes, delete</button>
            </form>
        </div>
    </div>
</div>



<?php include __DIR__ . '/../../layout/footer.php'; ?>

<script>
    const replyTextarea = document.getElementById('reply-content');
    if (replyTextarea) {
        replyTextarea.addEventListener("input", () => {
            replyTextarea.style.height = "auto";
            replyTextarea.style.height = replyTextarea.scrollHeight + "px";
        });
    }
</script>
<script src="<?= BASE_URL ?>/assets/js/forum_vote.js"></script>
<script src="<?= BASE_URL ?>/assets/js/reply_delete.js"></script>
<script src="<?= BASE_URL ?>/assets/js/update_reply.js"></script>