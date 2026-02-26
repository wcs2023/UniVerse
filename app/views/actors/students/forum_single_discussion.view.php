<head>
    <link rel="stylesheet" href="<?= BASE_URL; ?>/assets/css/forum/forum_single_styles.css">
    <style>
        .main-container {
            max-width: 900px;
            margin: 2rem auto;
            padding: 0 1rem;
        }
        .go-back a {
            color: #6b46c1;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 1.5rem;
        }
        .go-back a:hover {
            text-decoration: underline;
        }
        .thread-card, .replies-card {
            background: white;
            border-radius: 12px;
            padding: 2rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        .thread-header {
            border-bottom: 1px solid #e5e7eb;
            padding-bottom: 1rem;
            margin-bottom: 1.5rem;
        }
        .thread-title h1 {
            font-size: 1.75rem;
            color: #1f2937;
            margin: 0 0 0.5rem 0;
        }
        .thread-details {
            color: #6b7280;
            font-size: 0.9rem;
        }
        .thread-details .author-name,
        .thread-details .category {
            color: #6b46c1;
            font-weight: 500;
        }
        .thread-timestamp {
            color: #9ca3af;
            font-size: 0.85rem;
            margin-top: 0.5rem;
        }
        .thread-content {
            line-height: 1.7;
            color: #374151;
            margin-bottom: 1.5rem;
        }
        .post-stats {
            display: flex;
            gap: 2rem;
            padding: 1rem 0;
            border-top: 1px solid #e5e7eb;
            border-bottom: 1px solid #e5e7eb;
        }
        .post-stats .stat {
            text-align: center;
        }
        .post-stats .stat strong {
            display: block;
            font-size: 1.25rem;
            color: #1f2937;
        }
        .post-stats .stat span {
            font-size: 0.85rem;
            color: #6b7280;
        }
        .post-actions {
            display: flex;
            gap: 1rem;
            margin-top: 1rem;
        }
        .action-btn {
            background: #f3f4f6;
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 6px;
            cursor: pointer;
            transition: background 0.2s;
        }
        .action-btn:hover {
            background: #e5e7eb;
        }
        .replies-card h2 {
            margin: 0 0 1.5rem 0;
            color: #1f2937;
        }
        .reply-input-section {
            display: flex;
            gap: 1rem;
            margin-bottom: 2rem;
        }
        .avatar, .reply-avatar {
            width: 40px;
            height: 40px;
            background: #6b46c1;
            border-radius: 50%;
            flex-shrink: 0;
        }
        .reply-input-box {
            flex: 1;
        }
        .reply-input-box textarea {
            width: 100%;
            min-height: 80px;
            padding: 0.75rem;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            resize: vertical;
            font-family: inherit;
        }
        .reply-buttons {
            display: flex;
            gap: 0.5rem;
            margin-top: 0.5rem;
        }
        .submit-btn {
            background: #6b46c1;
            color: white;
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 6px;
            cursor: pointer;
        }
        .submit-btn:hover {
            background: #553c9a;
        }
        .cancel-btn {
            background: #f3f4f6;
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 6px;
            cursor: pointer;
        }
        .reply {
            display: flex;
            gap: 1rem;
            padding: 1rem 0;
            border-top: 1px solid #f3f4f6;
        }
        .reply-content {
            flex: 1;
        }
        .reply-header {
            display: flex;
            gap: 1rem;
            align-items: center;
            margin-bottom: 0.5rem;
        }
        .reply-author {
            font-weight: 600;
            color: #1f2937;
        }
        .reply-time {
            font-size: 0.85rem;
            color: #9ca3af;
        }
        .reply-text {
            color: #374151;
            line-height: 1.6;
            margin: 0;
        }
        .reply-actions {
            display: flex;
            gap: 1rem;
            margin-top: 0.5rem;
        }
        .reply-action {
            background: none;
            border: none;
            color: #6b7280;
            cursor: pointer;
            font-size: 0.9rem;
        }
        .reply-action:hover {
            color: #6b46c1;
        }
        .no-replies {
            text-align: center;
            color: #6b7280;
            padding: 2rem;
        }
    </style>
</head>
<?php
$pageTitle = $title ?? 'Discussion';
include_once __DIR__ . '/includes/header2.view.php';
?>

<main class="main-container">
    <div class="go-back">
        <a href="<?= BASE_URL ?>/Discussion_Forum"><i class="fa-solid fa-arrow-left"></i> Back to Forum</a>
    </div>

    <?php if (isset($thread) && !empty($thread)): ?>
    <div class="thread-container">
        <div class="thread-card">
            <div class="thread-header">
                <div class="thread-title">
                    <h1><?= htmlspecialchars($thread['title'] ?? 'Untitled') ?></h1>
                    <div class="thread-details">
                        <span>Posted by:</span>
                        <span class="author-name"><?= htmlspecialchars($thread['author_name'] ?? 'Unknown') ?></span>
                        <span>in</span>
                        <span class="category"><?= htmlspecialchars($thread['cat_name'] ?? 'General') ?></span>
                    </div>
                    <div class="thread-timestamp">
                        <?= isset($thread['created_at']) ? date('M j, Y, g:i A', strtotime($thread['created_at'])) : '' ?>
                    </div>
                </div>
            </div>

            <div class="thread-content">
                <div><?= nl2br(htmlspecialchars($thread['content'] ?? '')) ?></div>
            </div>

            <div class="post-stats">
                <div class="stat">
                    <strong><?= count($posts ?? []) ?></strong>
                    <span>replies</span>
                </div>
                <div class="stat">
                    <strong><?= $thread['views'] ?? 0 ?></strong>
                    <span>views</span>
                </div>
            </div>

            <div class="post-actions">
                <button class="action-btn">📤 Share</button>
                <?php if (isset($can_edit) && $can_edit): ?>
                    <a href="<?= BASE_URL ?>/Discussion_Forum/edit_post/<?= $thread['thread_id'] ?>" class="action-btn">✏️ Edit</a>
                <?php endif; ?>
            </div>
        </div>

        <div class="replies-card">
            <h2>Replies (<?= count($posts ?? []) ?>)</h2>

            <?php if (isset($_SESSION['USER'])): ?>
            <form action="<?= BASE_URL ?>/Discussion_Forum/reply_post/<?= $thread['thread_id'] ?>" method="POST" class="reply-input-section">
                <div class="avatar"></div>
                <div class="reply-input-box">
                    <textarea name="content" placeholder="Write a reply..." required></textarea>
                    <div class="reply-buttons">
                        <button type="submit" class="submit-btn">Post Reply</button>
                        <button type="reset" class="cancel-btn">Cancel</button>
                    </div>
                </div>
            </form>
            <?php endif; ?>

            <?php if (!empty($posts)): ?>
                <?php foreach ($posts as $post): ?>
                <div class="reply">
                    <div class="reply-avatar"></div>
                    <div class="reply-content">
                        <div class="reply-header">
                            <span class="reply-author"><?= htmlspecialchars($post['author_name'] ?? 'Anonymous') ?></span>
                            <span class="reply-time"><?= isset($post['created_at']) ? date('M j, Y, g:i A', strtotime($post['created_at'])) : '' ?></span>
                            <?php if (!empty($post['is_edited'])): ?>
                                <span class="reply-time">(edited)</span>
                            <?php endif; ?>
                        </div>
                        <p class="reply-text"><?= nl2br(htmlspecialchars($post['content'] ?? '')) ?></p>
                        <div class="reply-actions">
                            <?php if (isset($curr_user_id) && $post['author_id'] == $curr_user_id): ?>
                                <a href="<?= BASE_URL ?>/Discussion_Forum/edit_reply/<?= $post['post_id'] ?>" class="reply-action">✏️ Edit</a>
                                <a href="<?= BASE_URL ?>/Discussion_Forum/delete_reply/<?= $post['post_id'] ?>" class="reply-action" onclick="return confirm('Delete this reply?')">🗑️ Delete</a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="no-replies">
                    <p>No replies yet. Be the first to reply!</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
    <?php else: ?>
        <div class="thread-card">
            <p>Thread not found or no longer available.</p>
            <a href="<?= BASE_URL ?>/Discussion_Forum">Return to Forum</a>
        </div>
    <?php endif; ?>
</main>

<?php include __DIR__ . '/../../layout/footer.php'; ?>