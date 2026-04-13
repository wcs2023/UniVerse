<head>
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/forum/forum_single_styles.css">
</head>

<?php
$pageTitle = $title ?? '';
include_once __DIR__ . '/includes/header2.view.php';
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
            <button class="action-btn js-thread-dislike <?= $thread['user_vote'] == -1 ? 'disliked' : '' ?>" type="button" data-thread-id="<?= (int)$thread['thread_id'] ?>"><i class="fa-regular fa-thumbs-down"></i> dislike</button>
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
                                <i class="fa-regular fa-thumbs-down"></i> dislike
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
            <form action="<?= BASE_URL ?>/Discussion_Forum/reply_post/<?= $thread['thread_id'] ?>" method="post">
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
    const textarea = document.querySelector('textarea');

    textarea.addEventListener("input", () => {
        textarea.style.height = "auto";
        textarea.style.height = textarea.scrollHeight + "px";
    });
</script>
<script src="<?= BASE_URL ?>/assets/js/forum_vote.js"></script>
<script src="<?= BASE_URL ?>/assets/js/reply_delete.js"></script>
<script src="<?= BASE_URL ?>/assets/js/update_reply.js"></script>