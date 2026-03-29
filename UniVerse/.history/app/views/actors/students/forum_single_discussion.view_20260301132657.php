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
            <div class="reply" id="reply-<?= htmlspecialchars($post['id']) ?>">
                <div class="reply-avatar"></div>

                <div class="reply-content">
                    <div class="reply-header">
                        <span class="reply-author">Namer</span>
                        <span class="reply-time">2 hours ago</span>
                    </div>

                    <p class="reply-text">
                        Lorem ipsum dolor sit amet consectetur adipisicing elit.
                    </p>

                    <div class="reply-actions">
                        <button class="reply-action" type="button">❤️ 2 likes</button>
                        <button class="reply-action" type="button">↩️ Reply</button>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <!-- Reply Form -->

        <form action="<?= BASE_URL ?>/Discussion_Forum/reply_post" method="post">
            <div class="reply-input-section">
                <div class="avatar"></div>

                <div class="reply-input-box">
                    <textarea
                        name="reply"
                        placeholder="Write a reply..."
                        required></textarea>

                    <div class="reply-buttons">
                        <button class="submit-btn" type="submit">Post Reply</button>
                        <button class="cancel-btn" type="reset">Cancel</button>
                    </div>
                </div>
            </div>
        </form>

    </div>

</main>

<script>
    const textarea = document.querySelector('textarea');

    textarea.addEventListener("input", () => {
        textarea.style.height = "auto";
        textarea.style.height = textarea.scrollHeight + "px";
    });
</script>