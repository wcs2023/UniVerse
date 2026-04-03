<html>
<head>
    
    <link rel="stylesheet" href="<?= BASE_URL ?>/css/styles.css">
    <!-- <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/forum/forum_single_styles.css"> -->
    <style>     
            
        /* ===== MAIN CONTENT ===== */
        body{
            flex:1;
            height:100%;
        }

        .go-back
        {
           margin-top: 8rem; 
        }

        /* Post Card */
        .post-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            padding: 40px;
            margin-bottom: 30px;
            margin-top: 2rem;
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

    </style>
</head>

<?php
    $pageTitle = $title ?? '';
    include 'Unavigation.view.php';
?>

<body>    
    <main class="container" data-base-url="<?= BASE_URL ?>">
        <div class="go-back">
            <a href="<?= BASE_URL ?>/Udiscussion/index">← Back to Home</a>
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
    <div class="stat"><strong><?= htmlspecialchars($thread['likes'])?></strong><span> likes</span></div>
    <div class="stat"><strong><?= htmlspecialchars($thread['dislikes'])?></strong><span> dislikes</span></div>
    <div class="stat"><strong><?= htmlspecialchars($thread['replies']) ?></strong><span> replies</span></div>
    <div class="stat"><strong><?= htmlspecialchars($thread['views']) ?></strong><span> views</span></div>
</div>

<div class="post-actions">
    <button class="action-btn" type="button" data-thread-id="<?= (int)$thread['thread_id'] ?>"><i class="fa-regular fa-thumbs-up"></i> Like</button>
    <button class="action-btn" type="button" data-thread-id="<?= (int)$thread['thread_id'] ?>"><i class="fa-regular fa-thumbs-down"></i> dislike</button>
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
                            <button class="reply-action" type="button"><?= $post['likes'] ?> <i class="fa-regular fa-thumbs-up"></i>  likes</button>
                            <button class="reply-action" type="button"><?= $post['dislikes'] ?> <i class="fa-regular fa-thumbs-down"></i> dislike</button>
                            <?php if (isset($current_user_id) && $current_user_id == $post['author_id']): ?>
                                <div class="post-actions">
                                    <button onclick="edit_reply(<?= $post['post_id'] ?>)" class="btn-action btn-edit">
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
                        <form action="<?= BASE_URL ?>/Udiscussion/reply_post/<?= $thread['thread_id'] ?>" method="post">
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
<script src="<?= BASE_URL ?>/assets/js/forum_vote.js"></script>
</body>

</html>