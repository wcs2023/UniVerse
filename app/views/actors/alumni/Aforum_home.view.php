<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title ?? 'Forum') ?></title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/css/alumni.css">
    <link rel="icon" type="image/png" href="<?= BASE_URL ?>/assets/images/U.png">
    <script>window.__APP_ROOT__ = <?= json_encode(BASE_URL) ?>;</script>
    <style>
        body {
            padding-top: 80px;
        }
    </style>
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
                                    <span><i class="fa-solid fa-comments"></i> <?= $category['thread_count'] ?? '0' ?>
                                        threads</span>
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

    <script>
        document.addEventListener('DOMContentLoaded', function () {
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