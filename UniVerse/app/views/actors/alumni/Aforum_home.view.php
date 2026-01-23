<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title ?? 'Forum') ?></title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/forum_home.css">
    <script>window.__APP_ROOT__=<?= json_encode(BASE_URL) ?>;</script>
</head>
<body>

<?Php include __DIR__ . '/Anavbar.php'; ?>

<main class="main-container">
    <!-- Forum Header -->
    <section class="forum-header fade-in">
        <div class="header-content">
            <h1><i class="fa-solid fa-comments"></i> Discussion Forums</h1>
            <p class="header-description">
                Connect with fellow school leavers, share experiences, and get advice on university selection, 
                career planning, and academic success.
            </p>
        </div>

        <!-- Forum Stats -->
        <?php if (isset($data['stats'])): ?>
        <div class="forum-stats">
            <div class="stat-item">
                <div class="stat-number"><?= $data['stats']['total_threads'] ?? '0' ?></div>
                <div class="stat-label">Discussions</div>
            </div>
            <div class="stat-item">
                <div class="stat-number"><?= $data['stats']['total_posts'] ?? '0' ?></div>
                <div class="stat-label">Posts</div>
            </div>
            <div class="stat-item">
                <div class="stat-number"><?= $data['stats']['total_members'] ?? '0' ?></div>
                <div class="stat-label">Members</div>
            </div>
        </div>
        <?php endif; ?>
    </section>

    <!-- Forum Categories -->
    <section class="forum-categories">
        <h2><i class="fa-solid fa-folder"></i> Forum Categories</h2>
        
        <div class="categories-list">
            <?php if (isset($data['categories']) && is_array($data['categories'])): ?>
                <?php foreach ($data['categories'] as $category): ?>
                    <div class="category-card fade-in">
                        <div class="category-icon">
                            <i class="fa-solid <?= $category['icon'] ?? 'fa-comments' ?>"></i>
                        </div>
                        <div class="category-content">
                            <h3>
                                <a href="<?= BASE_URL ?>/schoolleaver/forumCategory/<?= $category['id'] ?? '#' ?>">
                                    <?= htmlspecialchars($category['name'] ?? 'Category') ?>
                                </a>
                            </h3>
                            <p class="category-description">
                                <?= htmlspecialchars($category['description'] ?? 'No description available') ?>
                            </p>
                            <div class="category-stats">
                                <span><i class="fa-solid fa-comments"></i> <?= $category['thread_count'] ?? '0' ?> threads</span>
                                <span><i class="fa-solid fa-reply"></i> <?= $category['post_count'] ?? '0' ?> posts</span>
                            </div>
                        </div>
                        <div class="category-activity">
                            <div class="last-activity">
                                <div class="activity-time">
                                    <?= isset($category['last_activity']) ? date('M j, Y g:i A', strtotime($category['last_activity'])) : 'No activity' ?>
                                </div>
                                <div class="activity-label">Last activity</div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="no-categories">
                    <p>No forum categories available at the moment.</p>
                </div>
            <?php endif; ?>
        </div>
    </section>

    <!-- Recent Discussions -->
    <section class="recent-discussions">
        <h2><i class="fa-solid fa-clock"></i> Recent Discussions</h2>
        
        <div class="discussions-table">
            <div class="table-header">
                <div class="col-topic">Topic</div>
                <div class="col-category">Category</div>
                <div class="col-replies">Replies</div>
                <div class="col-views">Views</div>
                <div class="col-last-post">Last Post</div>
            </div>
            
            <?php if (isset($data['recent_threads']) && is_array($data['recent_threads']) && count($data['recent_threads']) > 0): ?>
                <?php foreach ($data['recent_threads'] as $thread): ?>
                    <div class="discussion-row fade-in">
                        <div class="col-topic">
                            <div class="topic-title">
                                <a href="<?= BASE_URL ?>/schoolleaver/forumThread/<?= $thread['id'] ?? '#' ?>">
                                    <?= htmlspecialchars($thread['title'] ?? 'No title') ?>
                                </a>
                            </div>
                            <div class="topic-meta">
                                by <strong><?= htmlspecialchars($thread['author'] ?? 'Anonymous') ?></strong>
                            </div>
                        </div>
                        <div class="col-category">
                            <span class="category-tag">
                                <?= htmlspecialchars($thread['category'] ?? 'General') ?>
                            </span>
                        </div>
                        <div class="col-replies">
                            <div class="stat-number"><?= $thread['replies'] ?? '0' ?></div>
                        </div>
                        <div class="col-views">
                            <div class="stat-number"><?= $thread['views'] ?? '0' ?></div>
                        </div>
                        <div class="col-last-post">
                            <div class="last-post-time">
                                <?= isset($thread['last_post_time']) ? date('M j, g:i A', strtotime($thread['last_post_time'])) : 'No posts' ?>
                            </div>
                            <div class="last-post-author">
                                by <?= htmlspecialchars($thread['last_post_author'] ?? 'No one') ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="no-discussions">
                    <div class="no-content">
                        <i class="fa-solid fa-comments"></i>
                        <h3>No recent discussions</h3>
                        <p>Be the first to start a discussion!</p>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </section>

    <!-- Quick Actions -->
    <section class="forum-actions">
        <div class="action-buttons">
            <a href="<?= BASE_URL ?>/adiscussion/create" class="btn btn-primary">
                <i class="fa-solid fa-plus"></i> Start New Discussion
            </a>
        </div>
    </section>
</main>

<?php include __DIR__ . '/../../layout/footer.php'; ?>

<style>
/* Define CSS Variables */
:root {
    --primary-purple: #6b46c1;
    --secondary-purple: #8b5cf6;
    --light-purple: #a78bfa;
    --dark-purple: #553c9a;
    --text-dark: #1f2937;
    --text-light: #6b7280;
    --white: #ffffff;
    --light-gray: #f9fafb;
    --border-color: #e5e7eb;
    --success-color: #10b981;
    --warning-color: #f59e0b;
    --error-color: #ef4444;
}

/* Forum-specific styles */
.forum-header {
    background: var(--white);
    border-radius: 12px;
    padding: 2rem;
    margin-bottom: 2rem;
    text-align: center;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
}

.header-content h1 {
    font-size: 2.5rem;
    color: var(--text-dark);
    margin-bottom: 1rem;
}

.header-content h1 i {
    color: var(--primary-purple);
    margin-right: 0.5rem;
}

.header-description {
    font-size: 1.1rem;
    color: var(--text-light);
    max-width: 600px;
    margin: 0 auto 2rem auto;
    line-height: 1.6;
}

.forum-stats {
    display: flex;
    justify-content: center;
    gap: 3rem;
    margin-top: 1.5rem;
}

.stat-item {
    text-align: center;
}

.stat-number {
    font-size: 2rem;
    font-weight: 700;
    color: var(--primary-purple);
    margin-bottom: 0.25rem;
}

.stat-label {
    color: var(--text-light);
    font-size: 0.9rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.forum-categories,
.recent-discussions {
    background: var(--white);
    border-radius: 12px;
    padding: 2rem;
    margin-bottom: 2rem;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
}

.forum-categories h2,
.recent-discussions h2 {
    color: var(--text-dark);
    margin-bottom: 1.5rem;
    font-size: 1.5rem;
}

.forum-categories h2 i,
.recent-discussions h2 i {
    color: var(--primary-purple);
    margin-right: 0.5rem;
}

.categories-list {
    display: grid;
    gap: 1rem;
}

.category-card {
    display: flex;
    align-items: center;
    gap: 1.5rem;
    padding: 1.5rem;
    background: var(--light-gray);
    border-radius: 12px;
    transition: all 0.3s ease;
    border: 2px solid transparent;
}

.category-card:hover {
    border-color: var(--primary-purple);
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(107, 70, 193, 0.15);
}

.category-icon {
    width: 60px;
    height: 60px;
    background: var(--primary-purple);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.5rem;
    flex-shrink: 0;
}

.category-content {
    flex: 1;
}

.category-content h3 {
    margin: 0 0 0.5rem 0;
    font-size: 1.2rem;
}

.category-content h3 a {
    color: var(--text-dark);
    text-decoration: none;
    transition: color 0.3s ease;
}

.category-content h3 a:hover {
    color: var(--primary-purple);
}

.category-description {
    color: var(--text-light);
    margin-bottom: 0.75rem;
    line-height: 1.5;
}

.category-stats {
    display: flex;
    gap: 1rem;
    font-size: 0.85rem;
    color: var(--text-light);
}

.category-stats span {
    display: flex;
    align-items: center;
    gap: 0.25rem;
}

.category-activity {
    text-align: right;
    font-size: 0.85rem;
}

.activity-time {
    color: var(--text-dark);
    font-weight: 600;
}

.activity-label {
    color: var(--text-light);
}

.discussions-table {
    background: var(--light-gray);
    border-radius: 8px;
    overflow: hidden;
}

.table-header {
    display: grid;
    grid-template-columns: 2fr 1fr 80px 80px 150px;
    gap: 1rem;
    padding: 1rem 1.5rem;
    background: var(--primary-purple);
    color: white;
    font-weight: 600;
    font-size: 0.9rem;
}

.discussion-row {
    display: grid;
    grid-template-columns: 2fr 1fr 80px 80px 150px;
    gap: 1rem;
    padding: 1rem 1.5rem;
    border-bottom: 1px solid var(--border-color);
    transition: background 0.3s ease;
}

.discussion-row:hover {
    background: rgba(107, 70, 193, 0.05);
}

.discussion-row:last-child {
    border-bottom: none;
}

.topic-title a {
    color: var(--text-dark);
    text-decoration: none;
    font-weight: 600;
    transition: color 0.3s ease;
}

.topic-title a:hover {
    color: var(--primary-purple);
}

.topic-meta {
    font-size: 0.85rem;
    color: var(--text-light);
    margin-top: 0.25rem;
}

.category-tag {
    background: rgba(107, 70, 193, 0.1);
    color: var(--primary-purple);
    padding: 0.25rem 0.5rem;
    border-radius: 6px;
    font-size: 0.8rem;
    font-weight: 600;
}

.col-replies .stat-number,
.col-views .stat-number {
    font-weight: 600;
    color: var(--text-dark);
    text-align: center;
}

.last-post-time {
    font-size: 0.85rem;
    color: var(--text-dark);
    font-weight: 600;
}

.last-post-author {
    font-size: 0.8rem;
    color: var(--text-light);
}

.forum-actions {
    text-align: center;
}

.action-buttons {
    display: flex;
    gap: 1rem;
    justify-content: center;
}

.btn {
    padding: 0.75rem 1.5rem;
    border: none;
    border-radius: 8px;
    font-size: 1rem;
    font-weight: 500;
    cursor: pointer;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    text-align: center;
    transition: all 0.3s ease;
}

.btn-primary {
    background: var(--primary-purple);
    color: var(--white);
}

.btn-primary:hover {
    background: var(--dark-purple);
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(107, 70, 193, 0.3);
}

.btn-outline {
    background: transparent;
    color: var(--primary-purple);
    border: 2px solid var(--primary-purple);
}

.btn-outline:hover {
    background: var(--primary-purple);
    color: var(--white);
    transform: translateY(-2px);
}

.no-categories,
.no-discussions {
    padding: 2rem;
    text-align: center;
    color: var(--text-light);
}

.no-content {
    color: var(--text-light);
}

.no-content i {
    font-size: 3rem;
    margin-bottom: 1rem;
    color: var(--text-light);
}

.no-content h3 {
    color: var(--text-dark);
    margin-bottom: 0.5rem;
}

.fade-in {
    animation: fadeInUp 0.8s ease-out;
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@media (max-width: 768px) {
    .forum-stats {
        gap: 1.5rem;
    }
    
    .stat-number {
        font-size: 1.5rem;
    }
    
    .category-card {
        flex-direction: column;
        text-align: center;
        gap: 1rem;
    }
    
    .category-activity {
        text-align: center;
    }
    
    .table-header,
    .discussion-row {
        grid-template-columns: 1fr;
        gap: 0.5rem;
    }
    
    .col-category,
    .col-replies,
    .col-views,
    .col-last-post {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    .col-category::before { content: "Category: "; }
    .col-replies::before { content: "Replies: "; }
    .col-views::before { content: "Views: "; }
    .col-last-post::before { content: "Last post: "; }
    
    .action-buttons {
        flex-direction: column;
        align-items: center;
    }
}
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Add staggered animation to category cards
        const categoryCards = document.querySelectorAll('.category-card');
        categoryCards.forEach((card, index) => {
            card.style.animationDelay = `${index * 0.1}s`;
        });

        // Add staggered animation to discussion rows
        const discussionRows = document.querySelectorAll('.discussion-row');
        discussionRows.forEach((row, index) => {
            row.style.animationDelay = `${index * 0.05}s`;
        });
    });
</script>

</body>
</html>