<head>
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/forum/forum_single_styles.css">
</head>

<?php
$pageTitle = $title ?? '';
include_once __DIR__ . '/includes/header2.view.php';
?>

<main class="container">

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
            <div class="stat"><strong>3</strong><span> likes</span></div>
            <div class="stat"><strong><?= htmlspecialchars($thread['replies']) ?></strong><span> replies</span></div>
            <div class="stat"><strong><?= htmlspecialchars($thread['views']) ?></strong><span> views</span></div>
        </div>

        <div class="post-actions">
            <button class="action-btn" type="button"><i class="fa-regular fa-thumbs-up"></i> Like</button>
            <button class="action-btn" type="button"><i class="fa-regular fa-thumbs-down"></i> Unlike</button>
        </div>

    </div>

    <!-- Replies -->
    <div class="replies-card">
        <!-- Example Reply -->
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

                        <p class="reply-text">
                            <?= nl2br(htmlspecialchars($post['content'])) ?>
                        </p>

                        <div class="reply-actions">
                            <button class="reply-action" type="button"><i class="fa-regular fa-thumbs-up"></i> <?= htmlspecialchars($post['likes']) ?> likes</button>
                            <button class="reply-action" type="button"><i class="fa-regular fa-thumbs-down"></i> Unlike</button>
                            <?php if (isset($current_user_id) && $current_user_id == $post['author_id']): ?>
                                <div class="post-actions">
                                    <button onclick="editPost(<?= $post['post_id'] ?>)" class="btn-action btn-edit">
                                        <i class="fa-solid fa-edit"></i> Edit
                                    </button>
                                    <button onclick="confirmDelete('post', <?= $post['post_id'] ?>)" class="btn-action btn-delete">
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
<?php include __DIR__ . '/../../layout/footer.php'; ?>

<script>
    const textarea = document.querySelector('textarea');

    textarea.addEventListener("input", () => {
        textarea.style.height = "auto";
        textarea.style.height = textarea.scrollHeight + "px";
    });
</script>