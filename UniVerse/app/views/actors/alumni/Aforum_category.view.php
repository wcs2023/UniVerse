<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title ?? 'Forum Category') ?></title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/forum_home.css">
   
</head>
<body>

<main class="main-container">
    <!-- Category Header -->
    <section class="category-header fade-in">
        <div class="breadcrumb">
            <a href="<?= BASE_URL ?>/schoolleaver/forums">Forums</a>
            <span class="separator">/</span>
            <span><?= htmlspecialchars($data['category']['name'] ?? 'Category') ?></span>
        </div>
        
        <div class="category-info">
            <h1><?= htmlspecialchars($data['category']['name'] ?? 'Forum Category') ?></h1>
            <p class="category-description">
                <?= htmlspecialchars($data['category']['description'] ?? 'Category description') ?>
            </p>
        </div>
        
        <div class="category-actions">
            <a href="<?= BASE_URL ?>/schoolleaver/createThread?category=<?= $data['category']['id'] ?? '' ?>" class="btn btn-primary">
                <i class="fa-solid fa-plus"></i> Start New Thread
            </a>
        </div>
    </section>

    <!-- Threads List -->
    <section class="threads-section">
        <div class="threads-header">
            <h2>Discussions</h2>
            <div class="sort-options">
                <select class="sort-select">
                    <option value="recent">Most Recent</option>
                    <option value="popular">Most Popular</option>
                    <option value="oldest">Oldest First</option>
                </select>
            </div>
        </div>
        
        <div class="threads-table">
            <div class="table-header">
                <div class="col-thread">Thread</div>
                <div class="col-replies">Replies</div>
                <div class="col-views">Views</div>
                <div class="col-last-post">Last Post</div>
            </div>
            
            <?php if (isset($data['threads']) && is_array($data['threads']) && count($data['threads']) > 0): ?>
                <?php foreach ($data['threads'] as $thread): ?>
                    <div class="thread-row fade-in <?= ($thread['is_pinned'] ?? false) ? 'pinned' : '' ?>">
                        <div class="col-thread">
                            <?php if ($thread['is_pinned'] ?? false): ?>
                                <div class="pinned-badge">
                                    <i class="fa-solid fa-thumbtack"></i> Pinned
                                </div>
                            <?php endif; ?>
                            <div class="thread-title">
                                <a href="<?= BASE_URL ?>/schoolleaver/forumThread/<?= $thread['id'] ?? '#' ?>">
                                    <?= htmlspecialchars($thread['title'] ?? 'No title') ?>
                                </a>
                            </div>
                            <div class="thread-meta">
                                Started by <strong><?= htmlspecialchars($thread['author'] ?? 'Anonymous') ?></strong>
                                on <?= isset($thread['created_at']) ? date('M j, Y', strtotime($thread['created_at'])) : 'Unknown date' ?>
                            </div>
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
                <div class="no-threads">
                    <div class="no-content">
                        <i class="fa-solid fa-comments"></i>
                        <h3>No discussions yet</h3>
                        <p>Be the first to start a discussion in this category!</p>
                        <a href="<?= BASE_URL ?>/schoolleaver/createThread?category=<?= $data['category']['id'] ?? '' ?>" class="btn btn-primary">
                            Start First Discussion
                        </a>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </section>
</main>

<!-- Footer -->
<footer style="margin-top: 4rem; padding: 2rem; text-align: center; background: rgba(255, 255, 255, 0.9); border-radius: 15px; max-width: 1200px; margin-left: auto; margin-right: auto;">
    <p style="color: #6b7280; margin: 0;">&copy; 2024 UniVerse. All rights reserved.</p>
</footer>

<style>
/* CSS Variables */
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
}

.category-header {
    background: var(--white);
    border-radius: 12px;
    padding: 2rem;
    margin-bottom: 2rem;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
}

.breadcrumb {
    margin-bottom: 1rem;
    font-size: 0.9rem;
    color: var(--text-light);
}

.breadcrumb a {
    color: var(--primary-purple);
    text-decoration: none;
}

.breadcrumb a:hover {
    text-decoration: underline;
}

.separator {
    margin: 0 0.5rem;
}

.category-info {
    margin-bottom: 1.5rem;
}

.category-info h1 {
    font-size: 2rem;
    color: var(--text-dark);
    margin-bottom: 0.5rem;
}

.category-description {
    color: var(--text-light);
    font-size: 1.1rem;
    line-height: 1.6;
}

.threads-section {
    background: var(--white);
    border-radius: 12px;
    padding: 2rem;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
}

.threads-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1.5rem;
}

.threads-header h2 {
    color: var(--text-dark);
    margin: 0;
}

.sort-select {
    padding: 0.5rem 1rem;
    border: 1px solid var(--border-color);
    border-radius: 6px;
    background: var(--white);
    color: var(--text-dark);
    font-size: 0.9rem;
}

.threads-table {
    background: var(--light-gray);
    border-radius: 8px;
    overflow: hidden;
}

.table-header {
    display: grid;
    grid-template-columns: 2fr 100px 100px 180px;
    gap: 1rem;
    padding: 1rem 1.5rem;
    background: var(--primary-purple);
    color: white;
    font-weight: 600;
    font-size: 0.9rem;
}

.thread-row {
    display: grid;
    grid-template-columns: 2fr 100px 100px 180px;
    gap: 1rem;
    padding: 1rem 1.5rem;
    border-bottom: 1px solid var(--border-color);
    transition: background 0.3s ease;
}

.thread-row:hover {
    background: rgba(107, 70, 193, 0.05);
}

.thread-row:last-child {
    border-bottom: none;
}

.thread-row.pinned {
    background: rgba(245, 158, 11, 0.1);
    border-left: 4px solid var(--warning-color);
}

.pinned-badge {
    color: var(--warning-color);
    font-size: 0.8rem;
    font-weight: 600;
    margin-bottom: 0.25rem;
}

.thread-title a {
    color: var(--text-dark);
    text-decoration: none;
    font-weight: 600;
    font-size: 1.1rem;
    transition: color 0.3s ease;
}

.thread-title a:hover {
    color: var(--primary-purple);
}

.thread-meta {
    font-size: 0.85rem;
    color: var(--text-light);
    margin-top: 0.25rem;
}

.col-replies .stat-number,
.col-views .stat-number {
    font-weight: 600;
    color: var(--text-dark);
    text-align: center;
    font-size: 1.1rem;
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

.no-threads {
    padding: 3rem;
    text-align: center;
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
    .category-header {
        padding: 1.5rem;
    }
    
    .threads-header {
        flex-direction: column;
        gap: 1rem;
        align-items: stretch;
    }
    
    .table-header,
    .thread-row {
        grid-template-columns: 1fr;
        gap: 0.5rem;
    }
    
    .col-replies,
    .col-views,
    .col-last-post {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    .col-replies::before { content: "Replies: "; }
    .col-views::before { content: "Views: "; }
    .col-last-post::before { content: "Last post: "; }
}
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Add staggered animation to thread rows
        const threadRows = document.querySelectorAll('.thread-row');
        threadRows.forEach((row, index) => {
            row.style.animationDelay = `${index * 0.05}s`;
        });
    });
</script>

</body>
</html>