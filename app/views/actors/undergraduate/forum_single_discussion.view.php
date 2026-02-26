<head>
    <!-- <link rel="stylesheet" href="<?= BASE_URL; ?>/assets/css/forum/forum_single_styles.css"> -->
    <link rel="stylesheet" href="<?= BASE_URL; ?>/css/styles.css">
</head>

<?php
$pageTitle = $title ?? '';
include 'Unavigation.view.php';
?>

<main class="main-container">
    <div class="go-back">
        <a href="">Back to Home</a>
    </div>

    <div class="thread-container">
        <div class="thread-card">
            <div class="thread-header">
                <div class="thread-title">
                    <h1>title</h1>
                    <div class="thread-details">
                        <span>Posted by:</span>
                        <span class="author-name">name</span>
                        <span>in</span>
                        <span class="category">category name</span>
                    </div>
                    <div class="thread-timestamp">
                        testing
                    </div>
                </div>

                <!-- <button class="flag"></button> -->


            </div>

            <div class="thread-content">
                <div>content</div>
            </div>

            <div class="post-stats">
                <div class="stat">
                    <strong>3</strong>
                    <span>likes</span>
                </div>
                <div class="stat">
                    <strong>2</strong>
                    <span>replies</span>
                </div>
                <div class="stat">
                    <strong>24</strong>
                    <span>views</span>
                </div>
            </div>

            <div class="post-actions">
                <button class="action-btn">❤️ Like</button>
                <button class="action-btn">📤 Share</button>
            </div>
        </div>

        <div class="replies-card">
            <h2>Replies</h2>

            
            <div class="reply-input-section">
                <div class="avatar"></div>
                <div class="reply-input-box">
                    <textarea placeholder="Write a reply..."></textarea>
                    <div class="reply-buttons">
                        <button class="submit-btn">Post Reply</button>
                        <button class="cancel-btn">Cancel</button>
                    </div>
                </div>
            </div>

            
            <div class="reply">
                <div class="reply-avatar"></div>
                <div class="reply-content">
                    <div class="reply-header">
                        <span class="reply-author">namer</span>
                        <span class="reply-time">jkfjkvkj</span>
                    </div>
                    <p class="reply-text">Lorem ipsum dolor sit, amet consectetur adipisicing elit. Dolores, provident minima perferendis veritatis, earum dolorum unde facere quo laborum, perspiciatis magnam doloremque reiciendis cumque sequi libero ducimus nemo. Aliquam, ipsa.</p>
                    <div class="reply-actions">
                        <button class="reply-action">❤️ 2 likes</button>
                        <button class="reply-action">↩️ Reply</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>