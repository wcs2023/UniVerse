<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Applicant Profile - UniVerse</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/company.css">
    <style>
        .profile-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            flex-wrap: wrap;
        }

        .applicant-summary {
            display: flex;
            gap: 1rem;
            align-items: center;
            flex-wrap: wrap;
        }

        .applicant-avatar {
            width: 64px;
            height: 64px;
            border-radius: 50%;
            overflow: hidden;
            border: 2px solid var(--border-color);
            background: var(--light-gray);
            flex: 0 0 auto;
        }

        .applicant-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }

        .applicant-meta {
            display: flex;
            flex-direction: column;
            min-width: 240px;
        }

        .applicant-name {
            font-size: 1.25rem;
            font-weight: 800;
            color: var(--text-dark);
            line-height: 1.2;
        }

        .applicant-sub {
            color: var(--text-light);
            margin-top: 0.25rem;
        }

        .grid-2 {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
        }

        .kv {
            padding: 1rem;
            border: 1px solid var(--border-color);
            border-radius: 16px;
            background: rgba(255, 255, 255, 0.7);
        }

        .kv-label {
            font-weight: 700;
            color: var(--text-dark);
            font-size: 0.9rem;
            margin-bottom: 0.25rem;
        }

        .kv-value {
            color: var(--text-light);
            word-break: break-word;
        }

        .list {
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
        }

        .list-item {
            padding: 1rem;
            border: 1px solid var(--border-color);
            border-radius: 16px;
            background: rgba(255, 255, 255, 0.7);
        }

        .list-title {
            font-weight: 800;
            color: var(--text-dark);
            margin-bottom: 0.25rem;
        }

        .list-sub {
            color: var(--text-light);
            font-size: 0.95rem;
        }

        .pill {
            display: inline-block;
            padding: 0.25rem 0.6rem;
            border-radius: 999px;
            border: 1px solid var(--border-color);
            background: var(--white);
            color: var(--text-dark);
            font-weight: 700;
            font-size: 0.8rem;
            margin-right: 0.5rem;
        }

        @media (max-width: 900px) {
            .grid-2 {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
<?php require_once __DIR__ . '/companyHeader.view.php'; ?>

<?php
    $applicant = is_array($data['applicant'] ?? null) ? $data['applicant'] : [];
    $undergrad = is_array($data['undergradProfile'] ?? null) ? $data['undergradProfile'] : [];

    $pic = is_string($applicant['profile_picture'] ?? '') ? $applicant['profile_picture'] : '';
    if(!empty($pic))
    {
        $pic = ltrim($pic, '/');
        if (strpos($pic, 'public/') === 0) {
            $pic = substr($pic, 7);
        }
    }
    
    $picSrc = $pic ? (BASE_URL . '/' . $pic) : '';

    $fullName = trim(($applicant['first_name'] ?? '') . ' ' . ($applicant['last_name'] ?? ''));
    if ($fullName === '') {
        $fullName = $applicant['username'] ?? 'Applicant';
    }

    $headlineParts = [];
    if (!empty($undergrad['university'])) $headlineParts[] = $undergrad['university'];
    if (!empty($undergrad['degree_program'])) $headlineParts[] = $undergrad['degree_program'];
    if (!empty($undergrad['academic_year'])) $headlineParts[] = $undergrad['academic_year'];
    $headline = implode(' • ', $headlineParts);

    $makeExcerpt = function($text, $max = 180) {
        $clean = trim(strip_tags((string)$text));
        if ($clean === '') return '';
        $strlen = function_exists('mb_strlen') ? 'mb_strlen' : 'strlen';
        $substr = function_exists('mb_substr') ? 'mb_substr' : 'substr';
        $strrpos = function_exists('mb_strrpos') ? 'mb_strrpos' : 'strrpos';

        if ($strlen($clean) <= $max) return $clean;
        $cut = $substr($clean, 0, $max);
        $lastSpace = $strrpos($cut, ' ');
        if ($lastSpace !== false) $cut = $substr($cut, 0, $lastSpace);
        return $cut . '...';
    };

    $formatDate = function($value) {
        $ts = is_string($value) ? strtotime($value) : false;
        if (!$ts) return '';
        return date('M d, Y', $ts);
    };
?>

<main class="main-content">
    <div class="card">
        <div class="card-header">
            <div class="profile-header">
                <div class="applicant-summary">
                    <div class="applicant-avatar">
                        <?php if (!empty($picSrc)): ?>
                            <img src="<?= htmlspecialchars($picSrc) ?>" alt="Applicant profile picture" width="64" height="64" onerror="this.style.display='none'">
                        <?php else:?>
                            <img src="<?= BASE_URL?>/assets/images/U.png" alt="Applicant profile picture" width="64" height="64" onerror="this.style.display='none'">
                        <?php endif; ?>

                    </div>
                    <div class="applicant-meta">
                        <div class="applicant-name"><?= htmlspecialchars($fullName) ?></div>
                        <?php if (!empty($headline)): ?>
                            <div class="applicant-sub"><?= htmlspecialchars($headline) ?></div>
                        <?php endif; ?>
                        <div class="applicant-sub"><?= htmlspecialchars($applicant['email'] ?? '') ?></div>
                    </div>
                </div>

                <div class="form-actions" style="margin:0;">
                    <a class="btn btn-secondary" href="<?= BASE_URL ?>/company/applications">Back to Applications</a>
                </div>
            </div>
        </div>

        <div class="grid-2">
            <div class="kv">
                <div class="kv-label">Contact</div>
                <div class="kv-value">
                    <div><span class="pill">Email</span> <a href="mailto:<?= htmlspecialchars($applicant['email'] ?? '') ?>"><?= htmlspecialchars($applicant['email'] ?? 'Not provided') ?></a></div>
                    <div style="margin-top:0.5rem;"><span class="pill">Phone</span> <?= htmlspecialchars($applicant['phone'] ?? 'Not provided') ?></div>
                </div>
            </div>

            <div class="kv">
                <div class="kv-label">Education</div>
                <div class="kv-value">
                    <div><span class="pill">University</span> <?= htmlspecialchars($undergrad['university'] ?? 'Not provided') ?></div>
                    <div style="margin-top:0.5rem;"><span class="pill">Degree</span> <?= htmlspecialchars($undergrad['degree_program'] ?? 'Not provided') ?></div>
                    <div style="margin-top:0.5rem;"><span class="pill">Academic Year</span> <?= htmlspecialchars($undergrad['academic_year'] ?? 'Not provided') ?></div>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h3 class="card-title" style="font-size:1.4rem;">Achievements</h3>
            <p class="card-subtitle">Certificates, awards, and milestones</p>
        </div>

        <?php if (!empty($data['achievements'])): ?>
            <div class="list">
                <?php foreach ($data['achievements'] as $ach): ?>
                    <div class="list-item">
                        <div class="list-title"><?= htmlspecialchars($ach['title'] ?? 'Achievement') ?></div>
                        <div class="list-sub">
                            <?php if (!empty($ach['achievement_type'])): ?>
                                <span class="pill"><?= htmlspecialchars(ucfirst($ach['achievement_type'])) ?></span>
                            <?php endif; ?>
                            <?php $achDate = $formatDate($ach['date_achieved'] ?? ''); ?>
                            <?php if (!empty($achDate)): ?>
                                <span class="pill"><?= htmlspecialchars($achDate) ?></span>
                            <?php endif; ?>
                            <?php if (!empty($ach['institution'])): ?>
                                <span class="pill"><?= htmlspecialchars($ach['institution']) ?></span>
                            <?php endif; ?>
                        </div>
                        <?php if (!empty($ach['description'])): ?>
                            <div class="list-sub" style="margin-top:0.5rem; color: var(--text-dark);">
                                <?= htmlspecialchars($makeExcerpt($ach['description'], 260)) ?>
                            </div>
                        <?php endif; ?>
                        <?php if (!empty($ach['certificate_url'])): ?>
                            <div class="list-sub" style="margin-top:0.5rem;">
                                <a class="btn btn-secondary" style="padding:0.5rem 1rem; border-radius:999px;" href="<?= htmlspecialchars($ach['certificate_url']) ?>" target="_blank" rel="noopener noreferrer">View Certificate</a>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="empty-state">
                <h3>No achievements yet</h3>
                <p>This applicant hasn’t added achievements.</p>
            </div>
        <?php endif; ?>
    </div>

    <div class="card">
        <div class="card-header">
            <h3 class="card-title" style="font-size:1.4rem;">Published Articles</h3>
            <p class="card-subtitle">Public content posted by the applicant</p>
        </div>

        <?php if (!empty($data['articles'])): ?>
            <div class="list">
                <?php foreach ($data['articles'] as $article): ?>
                    <div class="list-item">
                        <div class="list-title">
                            <a href="<?= BASE_URL ?>/uarticles/viewDetails/<?= (int)($article['article_id'] ?? 0) ?>" target="_blank" rel="noopener noreferrer">
                                <?= htmlspecialchars($article['title'] ?? 'Article') ?>
                            </a>
                        </div>
                        <div class="list-sub">
                            <?php if (!empty($article['category'])): ?>
                                <span class="pill"><?= htmlspecialchars(ucfirst(str_replace('-', ' ', $article['category']))) ?></span>
                            <?php endif; ?>
                            <?php $articleDate = $formatDate($article['published_at'] ?? '') ?: $formatDate($article['created_at'] ?? ''); ?>
                            <?php if (!empty($articleDate)): ?>
                                <span class="pill"><?= htmlspecialchars($articleDate) ?></span>
                            <?php endif; ?>
                            <?php if (isset($article['views'])): ?>
                                <span class="pill">Views: <?= (int)$article['views'] ?></span>
                            <?php endif; ?>
                            <?php if (isset($article['likes'])): ?>
                                <span class="pill">Likes: <?= (int)$article['likes'] ?></span>
                            <?php endif; ?>
                        </div>
                        <?php if (!empty($article['content'])): ?>
                            <div class="list-sub" style="margin-top:0.5rem; color: var(--text-dark);">
                                <?= htmlspecialchars($makeExcerpt($article['content'], 220)) ?>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="empty-state">
                <h3>No published articles</h3>
                <p>This applicant hasn’t published articles yet.</p>
            </div>
        <?php endif; ?>
    </div>
</main>

<?php require_once __DIR__ . '/../../layout/footer.php'; ?>
</body>
</html>
