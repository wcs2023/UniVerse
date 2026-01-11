<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title ?? 'Forum Category') ?></title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/css/alumni.css">
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
<footer class="forum-footer">
    <p>&copy; 2024 UniVerse. All rights reserved.</p>
</footer>

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