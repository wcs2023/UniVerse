<?php 
// Include navigation if it exists
$navFile = APPROOT . '/views/actors/undergraduate/Unavigation.view.php';
if (file_exists($navFile)) {
    require_once $navFile;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Discover Your Mentor - UniVerse</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --primary-purple: #7c3aed;
            --purple-hover: #6d28d9;
            --text-dark: #1f2937;
            --text-light: #6b7280;
            --bg-light: #f9fafb;
            --border-color: #e5e7eb;
        }
        
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            background-color: var(--bg-light);
            color: var(--text-dark);
            line-height: 1.6;
            margin: 0;
        }

        /* Container */
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 2rem;
        }

        /* Page Header */
        .page-header {
            text-align: center;
            padding: 3rem 0 2rem;
            background-color: white;
            margin-bottom: 2rem;
        }

        .page-header h1 {
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--text-dark);
            margin-bottom: 0.5rem;
        }

        .page-header p {
            font-size: 1.125rem;
            color: var(--text-light);
            max-width: 600px;
            margin: 0 auto;
        }

        /* Search Section */
        .search-section {
            background-color: white;
            padding: 2rem;
            border-radius: 12px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            margin-bottom: 2rem;
        }

        .search-bar {
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
        }

        .search-input-wrapper {
            position: relative;
            flex: 1;
            min-width: 250px;
        }

        .search-icon {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-light);
            font-size: 1rem;
        }

        .search-input {
            width: 100%;
            padding: 0.75rem 1rem 0.75rem 2.5rem;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            font-size: 1rem;
            transition: all 0.3s;
        }

        .search-input:focus {
            outline: none;
            border-color: var(--primary-purple);
            box-shadow: 0 0 0 3px rgba(124, 58, 237, 0.1);
        }

        .filter-select {
            padding: 0.75rem 1rem;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            font-size: 1rem;
            color: var(--text-dark);
            background-color: white;
            cursor: pointer;
            min-width: 150px;
            transition: all 0.3s;
        }

        .filter-select:focus {
            outline: none;
            border-color: var(--primary-purple);
            box-shadow: 0 0 0 3px rgba(124, 58, 237, 0.1);
        }

        .btn-search {
            background-color: var(--primary-purple);
            color: white;
            border: none;
            padding: 0.75rem 2rem;
            border-radius: 8px;
            font-weight: 600;
            font-size: 1rem;
            cursor: pointer;
            transition: all 0.3s;
        }

        .btn-search:hover {
            background-color: var(--purple-hover);
            transform: translateY(-2px);
        }

        /* Mentors Grid */
        .mentors-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
            gap: 2rem;
            margin-bottom: 3rem;
        }

        .mentor-card {
            background: white;
            border-radius: 12px;
            padding: 2rem;
            text-align: center;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .mentor-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(124, 58, 237, 0.15);
        }

        .mentor-avatar {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            object-fit: cover;
            margin: 0 auto 1.25rem;
            border: 3px solid var(--bg-light);
        }

        .mentor-name {
            font-size: 1.25rem;
            font-weight: 600;
            color: var(--text-dark);
            margin-bottom: 0.25rem;
        }

        .mentor-position {
            font-size: 0.95rem;
            color: var(--text-light);
            margin-bottom: 1.25rem;
        }

        .expertise-tags {
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
            justify-content: center;
            margin-bottom: 1.5rem;
            min-height: 60px;
        }

        .expertise-tag {
            background-color: rgba(124, 58, 237, 0.1);
            color: var(--primary-purple);
            padding: 0.375rem 0.875rem;
            border-radius: 20px;
            font-size: 0.875rem;
            font-weight: 500;
        }

        .btn-view-profile {
            background-color: white;
            color: var(--primary-purple);
            border: 2px solid var(--primary-purple);
            padding: 0.625rem 1.5rem;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
            width: 100%;
            cursor: pointer;
            font-size: 1rem;
        }

        .btn-view-profile:hover {
            background-color: var(--primary-purple);
            color: white;
            transform: translateY(-2px);
        }

        /* Pagination */
        .pagination-wrapper {
            display: flex;
            justify-content: center;
            margin: 3rem 0;
        }

        .pagination {
            display: flex;
            gap: 0.5rem;
            list-style: none;
        }

        .page-link {
            padding: 0.625rem 1rem;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            color: var(--text-dark);
            text-decoration: none;
            transition: all 0.3s;
            min-width: 44px;
            text-align: center;
            display: inline-block;
        }

        .page-link:hover {
            background-color: var(--bg-light);
            border-color: var(--primary-purple);
            color: var(--primary-purple);
        }

        .page-link.active {
            background-color: var(--primary-purple);
            color: white;
            border-color: var(--primary-purple);
        }

        .page-link.disabled {
            color: #d1d5db;
            cursor: not-allowed;
            pointer-events: none;
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 4rem 2rem;
            background: white;
            border-radius: 12px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        .empty-icon {
            font-size: 4rem;
            color: var(--text-light);
            margin-bottom: 1rem;
        }

        .empty-state h3 {
            color: var(--text-dark);
            margin-bottom: 0.5rem;
            font-size: 1.5rem;
        }

        .empty-state p {
            color: var(--text-light);
        }

        /* Rating Stars */
        .mentor-rating {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            margin-bottom: 1rem;
            font-size: 0.9rem;
            color: var(--text-light);
        }

        .stars {
            color: #fbbf24;
            letter-spacing: 2px;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .page-header h1 {
                font-size: 2rem;
            }

            .search-bar {
                flex-direction: column;
            }

            .search-input-wrapper,
            .filter-select,
            .btn-search {
                width: 100%;
            }

            .mentors-grid {
                grid-template-columns: 1fr;
            }

            .container {
                padding: 0 1rem;
            }
        }

        /* Animation */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .mentor-card {
            animation: fadeInUp 0.5s ease forwards;
            opacity: 0;
        }
    </style>
</head>
<body>
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
                    <span class="search-icon">🔍</span>
                    <input 
                        type="text" 
                        name="search" 
                        class="search-input" 
                        placeholder="Search by name, role, or company"
                        value="<?= htmlspecialchars($data['searchTerm'] ?? '') ?>"
                    >
                </div>
                
                <select name="industry" class="filter-select">
                    <option value="">Industry</option>
                    <option value="Technology" <?= (isset($data['industry']) && $data['industry'] === 'Technology') ? 'selected' : '' ?>>Technology</option>
                    <option value="Finance" <?= (isset($data['industry']) && $data['industry'] === 'Finance') ? 'selected' : '' ?>>Finance</option>
                    <option value="Healthcare" <?= (isset($data['industry']) && $data['industry'] === 'Healthcare') ? 'selected' : '' ?>>Healthcare</option>
                    <option value="Education" <?= (isset($data['industry']) && $data['industry'] === 'Education') ? 'selected' : '' ?>>Education</option>
                    <option value="Marketing" <?= (isset($data['industry']) && $data['industry'] === 'Marketing') ? 'selected' : '' ?>>Marketing</option>
                </select>
                
                <select name="expertise" class="filter-select">
                    <option value="">Expertise</option>
                    <option value="Software Engineering" <?= (isset($data['expertise']) && $data['expertise'] === 'Software Engineering') ? 'selected' : '' ?>>Software Engineering</option>
                    <option value="Data Science" <?= (isset($data['expertise']) && $data['expertise'] === 'Data Science') ? 'selected' : '' ?>>Data Science</option>
                    <option value="Product Management" <?= (isset($data['expertise']) && $data['expertise'] === 'Product Management') ? 'selected' : '' ?>>Product Management</option>
                    <option value="UX Design" <?= (isset($data['expertise']) && $data['expertise'] === 'UX Design') ? 'selected' : '' ?>>UX Design</option>
                    <option value="Marketing" <?= (isset($data['expertise']) && $data['expertise'] === 'Marketing') ? 'selected' : '' ?>>Marketing</option>
                </select>
                
                <button type="submit" class="btn-search">
                    <span>🔍 Search</span>
                </button>
            </form>
        </div>

        <!-- Mentors Grid -->
        <?php if (isset($data['mentors']) && count($data['mentors']) > 0): ?>
            <div class="mentors-grid">
                <?php foreach ($data['mentors'] as $index => $mentor): ?>
                    <div class="mentor-card" style="animation-delay: <?= $index * 0.05 ?>s" onclick="window.location.href='<?= BASE_URL ?>/mentorships/viewProfile/<?= $mentor['mentor_id'] ?>'">
                        <img 
                            src="<?= !empty($mentor['profile_picture_url']) ? htmlspecialchars($mentor['profile_picture_url']) : 'https://i.pravatar.cc/150?img=' . rand(1, 70) ?>" 
                            alt="<?= htmlspecialchars($mentor['first_name'] . ' ' . $mentor['last_name']) ?>" 
                            class="mentor-avatar"
                        >
                        
                        <h3 class="mentor-name">
                            <?= htmlspecialchars($mentor['first_name'] . ' ' . $mentor['last_name']) ?>
                        </h3>
                        
                        <p class="mentor-position">
                            <?= htmlspecialchars($mentor['current_position']) ?>
                        </p>
                        
                        <?php if ($mentor['average_rating'] > 0): ?>
                            <div class="mentor-rating">
                                <span class="stars">
                                    <?php 
                                    $rating = round($mentor['average_rating'] * 2) / 2;
                                    $fullStars = floor($rating);
                                    $halfStar = ($rating - $fullStars) >= 0.5;
                                    
                                    for ($i = 0; $i < $fullStars; $i++) echo '★';
                                    if ($halfStar) echo '⯨';
                                    for ($i = 0; $i < (5 - $fullStars - ($halfStar ? 1 : 0)); $i++) echo '☆';
                                    ?>
                                </span>
                                <span>(<?= $mentor['total_sessions'] ?> sessions)</span>
                            </div>
                        <?php endif; ?>
                        
                        <div class="expertise-tags">
                            <?php 
                            $expertiseArray = isset($mentor['expertise_array']) ? $mentor['expertise_array'] : [];
                            $displayCount = 0;
                            foreach ($expertiseArray as $expertise): 
                                if ($displayCount >= 3) break;
                                $displayCount++;
                            ?>
                                <span class="expertise-tag"><?= htmlspecialchars(trim($expertise)) ?></span>
                            <?php endforeach; ?>
                            <?php if (count($expertiseArray) > 3): ?>
                                <span class="expertise-tag">+<?= count($expertiseArray) - 3 ?></span>
                            <?php endif; ?>
                        </div>
                        
                        <button class="btn-view-profile">
                            View Profile
                        </button>
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- Pagination -->
            <div class="pagination-wrapper">
                <ul class="pagination">
                    <li>
                        <a class="page-link disabled" href="#" tabindex="-1">
                            ←
                        </a>
                    </li>
                    <li>
                        <a class="page-link active" href="#">1</a>
                    </li>
                    <li>
                        <a class="page-link" href="#">2</a>
                    </li>
                    <li>
                        <a class="page-link" href="#">3</a>
                    </li>
                    <li>
                        <a class="page-link" href="#">
                            →
                        </a>
                    </li>
                </ul>
            </div>
        <?php else: ?>
            <!-- Empty State -->
            <div class="empty-state">
                <div class="empty-icon">👥</div>
                <h3>No Mentors Found</h3>
                <p>Try adjusting your search filters to find more mentors.</p>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
