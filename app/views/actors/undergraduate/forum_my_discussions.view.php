<html>
<head>
    <!-- <link rel="stylesheet" href="<?= BASE_URL; ?>/assets/css/forum/forum_single_styles.css"> -->
    <title>UniVerse - Discussion Thread</title>
    <style>
        html,body
        {
            height: 100%;
            margin: 0;
        }
        body
        {
            display: flex;
            flex-direction: column;
        }

        /* ===== MAIN CONTENT ===== */
        .main-container {
            max-width: 1200px;
            margin: 0 auto;
            flex: 1;
            justify-content: center;
            align-items: center;
        }

        /* Main Container */
        .container {
            max-width: 900px;
            margin: 0 auto;
        }
        
        /* Post Card */
        .post-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            padding: 40px;
            margin-bottom: 30px;
        }
        
        .post-header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
        }
        
        .post-title-section h1 {
            font-size: 32px;
            color: #111;
            margin-bottom: 12px;
        }
        
        .post-meta {
            font-size: 14px;
            color: #666;
            display: flex;
            gap: 5px;
            flex-wrap: wrap;
        }
        
        .post-meta a {
            color: #7c3aed;
            font-weight: 600;
            text-decoration: none;
        }
        
        .post-timestamp {
            font-size: 13px;
            color: #999;
            margin-top: 8px;
        }
        
        /* Content */
        .post-content {
            line-height: 1.7;
            color: #333;
            margin-bottom: 30px;
            font-size: 16px;
        }
        
        /* Stats */
        .post-stats {
            border-top: 1px solid #eee;
            border-bottom: 1px solid #eee;
            padding: 20px 0;
            display: flex;
            gap: 40px;
            margin-bottom: 20px;
            font-size: 14px;
        }
        
        .stat strong {
            color: #111;
        }
        
        /* Actions */
        .post-actions {
            display: flex;
            gap: 20px;
        }
        
        .action-btn {
            background: none;
            border: none;
            cursor: pointer;
            font-size: 16px;
            font-weight: 600;
            color: #666;
        }
        
        .action-btn:hover {
            color: #7c3aed;
        }
        
        /* Replies */
        .replies-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            padding: 40px;
        }
        
        .replies-card h2 {
            font-size: 24px;
            margin-bottom: 30px;
        }
        
        /* Reply Input */
        .reply-input-section {
            display: flex;
            gap: 20px;
            margin-bottom: 40px;
            padding-bottom: 40px;
            border-bottom: 1px solid #eee;
        }
        
        .avatar,
        .reply-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: linear-gradient(135deg, #7c3aed, #a78bfa);
        }
        
        .reply-input-box textarea {
            height: 160px;
            width: 740px;
            min-height: 100px;
            padding: 12px;
            border-radius: 8px;
            border: 1px solid #ddd;
            font-size: 14px;
        }
        
        .reply-buttons {
            display: flex;
            gap: 12px;
            margin-top: 12px;
        }
        
        .submit-btn {
            background: #7c3aed;
            color: white;
            border: none;
            padding: 10px 24px;
            border-radius: 8px;
            cursor: pointer;
        }
        
        .cancel-btn {
            background: none;
            border: none;
            cursor: pointer;
            color: #666;
        }
        
        /* Replies */
        .reply {
            display: flex;
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .reply-author {
            font-weight: 600;
        }
        
        .reply-time {
            font-size: 13px;
            color: #999;
        }
        
        .reply-text {
            margin: 10px 0;
        }
        
        .reply-actions {
            display: flex;
            gap: 20px;
        }
        
        .reply-action {
            background: none;
            border: none;
            cursor: pointer;
            color: #666;
        }
        
        .reply-action:hover {
            color: #7c3aed;
        }

        .thread-card
        {        
            padding: 3rem;
            margin-top: 12rem;
            /* margin: 2rem auto; */
            max-width: 600px;
            background-color: #ffffff;
            border-radius: 10px;
            text-align: center;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }

        </style>

    <link rel="stylesheet" href="<?= BASE_URL; ?>/css/styles.css">
</head>
<body>
    
    <?php
    $pageTitle = $title ?? 'Discussion';
    include_once __DIR__ . '\Unavigation.view.php';
    ?>

<main class="main-container">
    <!-- <div class="go-back">
        <a href="<?= BASE_URL ?>/Udiscussion"><i class="fa-solid fa-arrow-left"></i> Back to Forum</a>
    </div> -->

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
                <!-- <button class="action-btn">📤 Share</button> -->
                <?php if (isset($can_edit) && $can_edit): ?>
                    <a href="<?= BASE_URL ?>/Udiscussion/edit_post/<?= $thread['thread_id'] ?>" class="action-btn">Edit</a>
                <?php endif; ?>
            </div>
        </div>

        <div class="replies-card">
            <h2>Replies (<?= count($posts ?? []) ?>)</h2>

            <?php if (isset($_SESSION['USER'])): ?>
            <form action="<?= BASE_URL ?>/Udiscussion/reply_post/<?= $thread['thread_id'] ?>" method="POST" class="reply-input-section">
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
                                <a href="<?= BASE_URL ?>/Udiscussion/edit_reply/<?= $post['post_id'] ?>" class="reply-action">Edit</a>
                                <a href="<?= BASE_URL ?>/Udiscussion/delete_reply/<?= $post['post_id'] ?>" class="reply-action" onclick="return confirm('Delete this reply?')">Delete</a>
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
            <h3>
                <p>Thread not found or no longer available.</p>
                <a href="<?= BASE_URL ?>/Udiscussion">Return to Forum</a>
            </h3>
        </div>
    <?php endif; ?>
</main>
<?php include __DIR__ . '/../../layout/footer.php'; ?>
</body>

</html>