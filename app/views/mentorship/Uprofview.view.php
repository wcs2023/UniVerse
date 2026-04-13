<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/umentorship.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/styles.css">
    <link rel="icon" type="image/png" href="<?= BASE_URL ?>/assets/images/U.png">
    <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"> -->
    <title>Discover Your Mentor - UniVerse</title>
   
</head>

<body>
    <?php include __DIR__ . '/../actors/undergraduate/Unavigation.view.php'; ?>

    <!-- Page Header -->
    <div class="page-header">
        <div class="container">
            <h1>Discover Your Mentor</h1>
            <p>Find the right mentor to guide you on your professional journey.</p>
        </div>
    </div>

    <div class="container">
        <!-- Search Section -->
        <div class="search-section">
            <form action="<?= BASE_URL ?>/mentorships/exploreMentors" method="GET" class="search-bar">
                <div class="search-input-wrapper">
                    <i class="fas fa-search search-icon"></i>
                    <input type="text" name="search" class="search-input" placeholder="Search by name, role, or company"
                        value="<?= htmlspecialchars($data['searchTerm'] ?? '') ?>">
                </div>

                <select name="industry" class="filter-select">
                    <option value="">All Industries</option>
                    <option value="Technology" <?= (isset($data['industry']) && $data['industry'] === 'Technology') ? 'selected' : '' ?>>Technology</option>
                    <option value="Finance" <?= (isset($data['industry']) && $data['industry'] === 'Finance') ? 'selected' : '' ?>>Finance</option>
                    <option value="Healthcare" <?= (isset($data['industry']) && $data['industry'] === 'Healthcare') ? 'selected' : '' ?>>Healthcare</option>
                    <option value="Education" <?= (isset($data['industry']) && $data['industry'] === 'Education') ? 'selected' : '' ?>>Education</option>
                    <option value="Marketing" <?= (isset($data['industry']) && $data['industry'] === 'Marketing') ? 'selected' : '' ?>>Marketing</option>
                </select>

                <select name="expertise" class="filter-select">
                    <option value="">All Expertise</option>
                    <option value="Software Development" <?= (isset($data['expertise']) && $data['expertise'] === 'Software Development') ? 'selected' : '' ?>>Software Development</option>
                    <option value="Cloud &amp; DevOps" <?= (isset($data['expertise']) && $data['expertise'] === 'Cloud & DevOps') ? 'selected' : '' ?>>Cloud &amp; DevOps</option>
                    <option value="Cybersecurity" <?= (isset($data['expertise']) && $data['expertise'] === 'Cybersecurity') ? 'selected' : '' ?>>Cybersecurity</option>
                    <option value="Data &amp; AI/ML" <?= (isset($data['expertise']) && $data['expertise'] === 'Data & AI/ML') ? 'selected' : '' ?>>Data &amp; AI/ML</option>
                    <option value="UI/UX &amp; Product" <?= (isset($data['expertise']) && $data['expertise'] === 'UI/UX & Product') ? 'selected' : '' ?>>UI/UX &amp; Product</option>
                    <option value="Networking &amp; Infra" <?= (isset($data['expertise']) && $data['expertise'] === 'Networking & Infra') ? 'selected' : '' ?>>Networking &amp; Infra</option>
                    <option value="Database Systems" <?= (isset($data['expertise']) && $data['expertise'] === 'Database Systems') ? 'selected' : '' ?>>Database Systems</option>
                    <option value="Embedded &amp; IoT" <?= (isset($data['expertise']) && $data['expertise'] === 'Embedded & IoT') ? 'selected' : '' ?>>Embedded &amp; IoT</option>
                    <option value="QA &amp; Testing" <?= (isset($data['expertise']) && $data['expertise'] === 'QA & Testing') ? 'selected' : '' ?>>QA &amp; Testing</option>
                    <option value="Computer Architecture" <?= (isset($data['expertise']) && $data['expertise'] === 'Computer Architecture') ? 'selected' : '' ?>>Computer Architecture</option>
                    <option value="Open Source &amp; Tools" <?= (isset($data['expertise']) && $data['expertise'] === 'Open Source & Tools') ? 'selected' : '' ?>>Open Source &amp; Tools</option>
                    <option value="Tech Career &amp; Interview Prep" <?= (isset($data['expertise']) && $data['expertise'] === 'Tech Career & Interview Prep') ? 'selected' : '' ?>>Tech Career &amp; Interview Prep</option>
                </select>

                <button type="submit" class="btn-search">
                    <i class="fas fa-search"></i>
                    <span>Search</span>
                </button>
            </form>
        </div>

        <!-- Mentors Grid -->
        <?php if (isset($data['mentors']) && count($data['mentors']) > 0): ?>
            <div class="mentors-grid">
                <?php foreach ($data['mentors'] as $index => $mentor): ?>
                    <div class="mentor-card" style="animation-delay: <?= $index * 0.05 ?>s"
                        onclick="window.location.href='<?= BASE_URL ?>/mentorships/viewProfile/<?= $mentor['mentor_id'] ?>'">
                        <img src="<?= !empty($mentor['profile_picture_url']) ? htmlspecialchars($mentor['profile_picture_url']) : BASE_URL . '/assets/images/avatars/default.png' ?>"
                            alt="<?= htmlspecialchars($mentor['first_name'] . ' ' . $mentor['last_name']) ?>"
                            class="mentor-avatar" onerror="this.src='<?= BASE_URL ?>/assets/images/avatars/default.png'">

                        <h3 class="mentor-name">
                            <?= htmlspecialchars($mentor['first_name'] . ' ' . $mentor['last_name']) ?>
                        </h3>

                        <p class="mentor-position">
                            <?= htmlspecialchars($mentor['current_position'] ?? 'Professional Mentor') ?>
                        </p>

                        <?php if (isset($mentor['average_rating']) && $mentor['average_rating'] > 0): ?>
                            <div class="mentor-rating">
                                <span class="stars">
                                    <?php
                                    $rating = round($mentor['average_rating'] * 2) / 2;
                                    $fullStars = floor($rating);
                                    $halfStar = ($rating - $fullStars) >= 0.5;

                                    for ($i = 0; $i < $fullStars; $i++)
                                        echo '<i class="fas fa-star"></i>';
                                    if ($halfStar)
                                        echo '<i class="fas fa-star-half-alt"></i>';
                                    for ($i = 0; $i < (5 - $fullStars - ($halfStar ? 1 : 0)); $i++)
                                        echo '<i class="far fa-star"></i>';
                                    ?>
                                </span>
                                <span>(<?= $mentor['total_sessions'] ?? 0 ?> sessions)</span>
                            </div>
                        <?php endif; ?>

                        <div class="expertise-tags">
                            <?php
                            $expertiseArray = isset($mentor['expertise_array']) ? $mentor['expertise_array'] :
                                (isset($mentor['expertise']) ? explode(',', $mentor['expertise']) :
                                    ['General Mentoring']);
                            $displayCount = 0;
                            foreach ($expertiseArray as $expertise):
                                if ($displayCount >= 3)
                                    break;
                                $displayCount++;
                                ?>
                                <span class="expertise-tag"><?= htmlspecialchars(trim($expertise)) ?></span>
                            <?php endforeach; ?>
                            <?php if (count($expertiseArray) > 3): ?>
                                <span class="expertise-tag">+<?= count($expertiseArray) - 3 ?></span>
                            <?php endif; ?>
                        </div>

                        <button class="btn-view-profile">
                            <i class="fas fa-user"></i>
                            View Profile
                        </button>
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- Pagination -->
            <?php if (isset($data['pagination']) && $data['pagination']['totalPages'] > 1): ?>
                <div class="pagination-wrapper">
                    <ul class="pagination">
                        <li>
                            <?php if ($data['pagination']['currentPage'] > 1): ?>
                                <a class="page-link" href="?page=<?= $data['pagination']['currentPage'] - 1 ?>">
                                    <i class="fas fa-chevron-left"></i>
                                </a>
                            <?php else: ?>
                                <span class="page-link disabled">
                                    <i class="fas fa-chevron-left"></i>
                                </span>
                            <?php endif; ?>
                        </li>

                        <?php for ($i = 1; $i <= $data['pagination']['totalPages']; $i++): ?>
                            <li>
                                <a class="page-link <?= ($i === $data['pagination']['currentPage']) ? 'active' : '' ?>"
                                    href="?page=<?= $i ?>"><?= $i ?></a>
                            </li>
                        <?php endfor; ?>

                        <li>
                            <?php if ($data['pagination']['currentPage'] < $data['pagination']['totalPages']): ?>
                                <a class="page-link" href="?page=<?= $data['pagination']['currentPage'] + 1 ?>">
                                    <i class="fas fa-chevron-right"></i>
                                </a>
                            <?php else: ?>
                                <span class="page-link disabled">
                                    <i class="fas fa-chevron-right"></i>
                                </span>
                            <?php endif; ?>
                        </li>
                    </ul>
                </div>
            <?php endif; ?>
        <?php else: ?>
            <!-- Empty State -->
            <div class="empty-state">
                <div class="empty-icon">
                    <i class="fas fa-users"></i>
                </div>
                <h3>No Mentors Found</h3>
                <p>Try adjusting your search filters to find more mentors.</p>
            </div>
        <?php endif; ?>
    </div>

    <?php include __DIR__ . '/../layout/footer.php'; ?>
</body>

</html>