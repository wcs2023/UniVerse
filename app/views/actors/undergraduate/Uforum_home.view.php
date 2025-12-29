<?php
// If no data from controller, use dummy data
if (!isset($recent_threads) || empty($recent_threads)) {
    $recent_threads = [
        [
            'id' => 1,
            'title' => 'Which university has the best Computer Science program?',
            'category_name' => 'University Selection',
            'author_name' => 'John Smith',
            'replies' => 24,
            'views' => 456,
            'last_activity' => date('Y-m-d H:i:s', strtotime('-2 hours'))
        ],
        [
            'id' => 2,
            'title' => 'Tips for writing a compelling personal statement',
            'category_name' => 'Application Help',
            'author_name' => 'Sarah Johnson',
            'replies' => 18,
            'views' => 342,
            'last_activity' => date('Y-m-d H:i:s', strtotime('-3 hours'))
        ],
        [
            'id' => 3,
            'title' => 'Scholarship opportunities for international students',
            'category_name' => 'Financial Aid',
            'author_name' => 'David Lee',
            'replies' => 35,
            'views' => 678,
            'last_activity' => date('Y-m-d H:i:s', strtotime('-5 hours'))
        ],
        [
            'id' => 4,
            'title' => 'How to prepare for university entrance exams?',
            'category_name' => 'Study Tips',
            'author_name' => 'Emily Brown',
            'replies' => 42,
            'views' => 891,
            'last_activity' => date('Y-m-d H:i:s', strtotime('-1 day'))
        ],
        [
            'id' => 5,
            'title' => 'Engineering vs Computer Science - Which should I choose?',
            'category_name' => 'Career Advice',
            'author_name' => 'Michael Chen',
            'replies' => 56,
            'views' => 1203,
            'last_activity' => date('Y-m-d H:i:s', strtotime('-1 day'))
        ],
        [
            'id' => 6,
            'title' => 'Campus life: What to expect in your first year',
            'category_name' => 'Campus Life',
            'author_name' => 'Lisa Wang',
            'replies' => 29,
            'views' => 534,
            'last_activity' => date('Y-m-d H:i:s', strtotime('-2 days'))
        ],
        [
            'id' => 7,
            'title' => 'Best online resources for SAT preparation',
            'category_name' => 'Test Prep',
            'author_name' => 'Tom Anderson',
            'replies' => 31,
            'views' => 612,
            'last_activity' => date('Y-m-d H:i:s', strtotime('-2 days'))
        ],
        [
            'id' => 8,
            'title' => 'Part-time job opportunities for students',
            'category_name' => 'Student Jobs',
            'author_name' => 'Anna Martinez',
            'replies' => 22,
            'views' => 445,
            'last_activity' => date('Y-m-d H:i:s', strtotime('-3 days'))
        ],
        [
            'id' => 9,
            'title' => 'Study abroad programs: Pros and cons',
            'category_name' => 'International',
            'author_name' => 'James Wilson',
            'replies' => 47,
            'views' => 823,
            'last_activity' => date('Y-m-d H:i:s', strtotime('-3 days'))
        ],
        [
            'id' => 10,
            'title' => 'How to balance academics and extracurricular activities?',
            'category_name' => 'Study Tips',
            'author_name' => 'Sophie Taylor',
            'replies' => 38,
            'views' => 567,
            'last_activity' => date('Y-m-d H:i:s', strtotime('-4 days'))
        ],
        [
            'id' => 11,
            'title' => 'Medical school requirements and preparation guide',
            'category_name' => 'Career Advice',
            'author_name' => 'Robert Kim',
            'replies' => 51,
            'views' => 934,
            'last_activity' => date('Y-m-d H:i:s', strtotime('-5 days'))
        ],
        [
            'id' => 12,
            'title' => 'Affordable universities with quality education',
            'category_name' => 'University Selection',
            'author_name' => 'Maria Garcia',
            'replies' => 44,
            'views' => 789,
            'last_activity' => date('Y-m-d H:i:s', strtotime('-5 days'))
        ],
        [
            'id' => 13,
            'title' => 'Resume building tips for university applications',
            'category_name' => 'Application Help',
            'author_name' => 'Kevin Patel',
            'replies' => 26,
            'views' => 478,
            'last_activity' => date('Y-m-d H:i:s', strtotime('-6 days'))
        ],
        [
            'id' => 14,
            'title' => 'Mental health support services on campus',
            'category_name' => 'Campus Life',
            'author_name' => 'Rachel Green',
            'replies' => 33,
            'views' => 591,
            'last_activity' => date('Y-m-d H:i:s', strtotime('-1 week'))
        ],
        [
            'id' => 15,
            'title' => 'STEM fields: Career prospects and salaries',
            'category_name' => 'Career Advice',
            'author_name' => 'Daniel Park',
            'replies' => 59,
            'views' => 1145,
            'last_activity' => date('Y-m-d H:i:s', strtotime('-1 week'))
        ]
    ];
}

if (!isset($stats) || empty($stats)) {
    $stats = [
        'total_threads' => 450,
        'total_posts' => 3521,
        'total_members' => 1247
    ];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title ?? 'Forum') ?></title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/forum_home.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/css/styles.css">
</head>
<body>

<?php include __DIR__ . '/Unavigation.view.php'; ?>

<main class="main-container">
    <!-- Forum Header -->
    <section class="forum-header">
        <div class="header-content">
            <h1>Discussion Forums</h1>
            <p class="header-description">
                Connect with fellow school leavers, share experiences, and get advice on university selection, 
                career planning, and academic success.
            </p>
        </div>

        <!-- Forum Stats -->
        <div class="forum-stats">
            <div class="stat-item">
                <div class="stat-number"><?= $stats['total_threads'] ?></div>
                <div class="stat-label">Discussions</div>
            </div>
            <div class="stat-item">
                <div class="stat-number"><?= $stats['total_posts'] ?></div>
                <div class="stat-label">Posts</div>
            </div>
            <div class="stat-item">
                <div class="stat-number"><?= $stats['total_members'] ?></div>
                <div class="stat-label">Members</div>
            </div>
        </div>
    </section>

    <!-- Recent Discussions -->
    <section class="recent-discussions">
        <h2>Recent Discussions</h2>
        
        <div class="discussions-table">
            <div class="table-header">
                <div class="col-topic">Topic</div>
                <div class="col-category">Category</div>
                <div class="col-replies">Replies</div>
                <div class="col-views">Views</div>
                <div class="col-last-post">Last Post</div>
            </div>
            
            <?php foreach ($recent_threads as $thread): ?>
                <div class="discussion-row">
                    <div class="col-topic">
                        <div class="topic-title">
                            <a href="<?= BASE_URL ?>/udiscussion/thread/<?= $thread['id'] ?>">
                                <?= htmlspecialchars($thread['title']) ?>
                            </a>
                        </div>
                        <div class="topic-meta">
                            by <strong><?= htmlspecialchars($thread['author_name']) ?></strong>
                        </div>
                    </div>
                    <div class="col-category">
                        <span class="category-tag">
                            <?= htmlspecialchars($thread['category_name']) ?>
                        </span>
                    </div>
                    <div class="col-replies">
                        <div class="stat-number"><?= $thread['replies'] ?></div>
                    </div>
                    <div class="col-views">
                        <div class="stat-number"><?= $thread['views'] ?></div>
                    </div>
                    <div class="col-last-post">
                        <div class="last-post-time">
                            <?= date('M j, g:i A', strtotime($thread['last_activity'])) ?>
                        </div>
                        <div class="last-post-author">
                            by <?= htmlspecialchars($thread['author_name']) ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </section>

    <!-- Quick Actions -->
    <section class="forum-actions">
        <div class="action-buttons">
            <a href="<?= BASE_URL ?>/udiscussion/create" class="btn btn-primary">
                Start New Discussion
            </a>
        </div>
    </section>
</main>

<?php include __DIR__ . '/../../layout/footer.php'; ?>

<style>
:root {
    --primary-purple: #6b46c1;
    --secondary-purple: #8b5cf6;
    --text-dark: #1f2937;
    --text-light: #6b7280;
    --white: #ffffff;
    --light-gray: #f9fafb;
    --border-color: #e5e7eb;
}

body {
    margin: 0;
    padding: 0;
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
    background-color: #f9fafb;
    padding-top: 90px !important;
}

.main-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 2rem;
    margin-top: 20px;
}

.forum-header {
    background: var(--white);
    border-radius: 12px;
    padding: 2rem;
    margin-bottom: 2rem;
    margin-top: 30px;
    text-align: center;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.header-content h1 {
    font-size: 2rem;
    color: var(--text-dark);
    margin-bottom: 1rem;
}

.header-description {
    font-size: 1rem;
    color: var(--text-light);
    max-width: 700px;
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
}

.recent-discussions {
    background: var(--white);
    border-radius: 12px;
    padding: 2rem;
    margin-bottom: 2rem;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.recent-discussions h2 {
    color: var(--text-dark);
    margin-bottom: 1.5rem;
    font-size: 1.5rem;
}

.discussions-table {
    border-radius: 8px;
    overflow: hidden;
    border: 1px solid var(--border-color);
}

.table-header {
    display: grid;
    grid-template-columns: 2fr 1fr 100px 100px 180px;
    gap: 1rem;
    padding: 1rem 1.5rem;
    background: var(--primary-purple);
    color: white;
    font-weight: 600;
    font-size: 0.9rem;
}

.discussion-row {
    display: grid;
    grid-template-columns: 2fr 1fr 100px 100px 180px;
    gap: 1rem;
    padding: 1rem 1.5rem;
    border-bottom: 1px solid var(--border-color);
    background: white;
    transition: background 0.2s ease;
}

.discussion-row:hover {
    background: var(--light-gray);
}

.discussion-row:last-child {
    border-bottom: none;
}

.topic-title a {
    color: var(--text-dark);
    text-decoration: none;
    font-weight: 600;
    transition: color 0.2s ease;
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
    padding: 0.35rem 0.75rem;
    border-radius: 6px;
    font-size: 0.85rem;
    font-weight: 500;
    display: inline-block;
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
    font-weight: 500;
}

.last-post-author {
    font-size: 0.8rem;
    color: var(--text-light);
    margin-top: 0.25rem;
}

.forum-actions {
    text-align: center;
    margin-top: 2rem;
}

.btn {
    padding: 0.85rem 1.75rem;
    border: none;
    border-radius: 8px;
    font-size: 1rem;
    font-weight: 500;
    cursor: pointer;
    text-decoration: none;
    display: inline-block;
    transition: all 0.3s ease;
}

.btn-primary {
    background: var(--primary-purple);
    color: var(--white);
}

.btn-primary:hover {
    background: #553c9a;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(107, 70, 193, 0.3);
}

@media (max-width: 768px) {
    .forum-stats {
        gap: 1.5rem;
    }
    
    .stat-number {
        font-size: 1.5rem;
    }
    
    .table-header,
    .discussion-row {
        grid-template-columns: 1fr;
        gap: 0.5rem;
    }
    
    .table-header {
        display: none;
    }
    
    .col-category,
    .col-replies,
    .col-views,
    .col-last-post {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    .col-category::before { content: "Category: "; font-weight: 600; }
    .col-replies::before { content: "Replies: "; font-weight: 600; }
    .col-views::before { content: "Views: "; font-weight: 600; }
    .col-last-post::before { content: "Last post: "; font-weight: 600; }
}
</style>

</body>
</html>