<!DOCTYPE html>
<html lang="en">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
<head>          
    <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'undergraduate'): ?>
        <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/styles.css">
    <?php endif; ?>
    
    <link rel="icon" type="image/png" href="<?= BASE_URL ?>/assets/images/U.png">
    
    <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'school_leaver'): ?>
        <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/student_style.css">    
    <?php endif; ?>
    
    <link rel="icon" type="image/png" href="<?= BASE_URL ?>/assets/images/U.png">
    <title>UniVerse - <?= htmlspecialchars($data['article']['title']) ?></title>

<style>
:root {
    --primary-purple: #6b46c1;
    --secondary-purple: #8b5cf6;
    --light-purple: #a78bfa;
    --dark-purple: #553c9a;
    --background:#a78bfa45;
    --gradient-primary: linear-gradient(135deg, #6b46c1, #8b5cf6);
    --gradient-secondary: linear-gradient(135deg, #8b5cf6, #a78bfa);
    --text-dark: #1f2937;
    --text-medium: #4b5563;
    --text-light: #6b7280;
    --white: #ffffff;
    --light-gray: #f9fafb;
    --border-color: #e5e7eb;
    --shadow-sm: 0 1px 3px rgba(0, 0, 0, 0.1);
    --shadow-md: 0 4px 6px rgba(0, 0, 0, 0.1);
    --shadow-lg: 0 10px 25px rgba(0, 0, 0, 0.15);
    --radius: 12px;
    --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1)
}

.back-btn {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.75rem 1.25rem;
    margin-bottom: 1rem;
    border: none;
    border-radius: 999px;
    background: var(--gradient-primary);
    color: var(--white);
    font-size: 0.95rem;
    font-weight: 600;
    cursor: pointer;
    box-shadow: var(--shadow-md);
    transition: var(--transition);
}

.back-btn:hover {
    transform: translateY(-2px);
    box-shadow: var(--shadow-lg);
    background: var(--gradient-secondary);
}

.back-btn:active {
    transform: translateY(0);
    box-shadow: var(--shadow-sm);
}


.article-single-container {
    min-height: 80vh;
    padding: 120px 20px 60px 20px;
    display: flex;
    justify-content: center;
}

.article-header {
    padding: 3rem;
    border-radius: 25px 25px 0 0;
    background-color: var(--light-gray);
}

.article-header h1 {
    margin: 0;
    color: var(--text-dark);
    font-size: 2rem;
    line-height: 1.3;
}
         
.article-card {
    width: 100%;
    max-width: 900px;
}

.article-body {
    font-size: 1.1rem;
    line-height: 1.8;
    background-color: var(--light-gray);
    border-radius: 0 0 25px 25px;
    padding: 2rem 3rem 3rem 3rem;
    color: var(--text-medium);
}

.article-body p {
    margin: 0 0 1.5rem 0;
}

.article-footer {
    margin-top: 2rem;
    padding-top: 1.5rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.article-footer hr {
    display: none;
}

.author {
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
}

.author span:first-child {
    font-weight: 600;
    color: var(--text-dark);
}

.author span:last-child {
    font-size: 0.9rem;
    color: var(--text-light);
}

.counts {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    font-size: 1rem;
    color: var(--text-medium);
}

.view-count {
    display: flex;
    align-items: center;
    gap: 0.35rem;
    padding: 0.45rem 1rem;
    border: 2px solid var(--border-color);
    border-radius: 999px;
    background: white;
    color: var(--text-medium);
    font-size: 0.95rem;
    white-space: nowrap;
}

.like-btn {
    display: flex;
    align-items: center;
    gap: 0.4rem;
    padding: 0.45rem 1rem;
    border: 2px solid var(--border-color);
    border-radius: 999px;
    background: white;
    color: var(--text-medium);
    font-size: 0.95rem;
    cursor: pointer;
    transition: var(--transition);
    font-family: inherit;
}

.like-btn:hover {
    border-color: #e11d48;
    color: #e11d48;
    background: #fff1f2;
}

.like-btn.liked {
    border-color: #e11d48;
    background: #fff1f2;
    color: #e11d48;
}

.like-btn .heart-icon {
    font-size: 1.1rem;
    line-height: 1;
    transition: transform 0.2s ease;
}

.like-btn.pop .heart-icon {
    transform: scale(1.4);
}

.like-btn:disabled {
    opacity: 0.6;
    cursor: not-allowed;
}

@media(max-width:768px) {
    .article-single-container {
        min-height: auto;
        padding: 100px 1rem 2rem 1rem;
    }

    .article-header {
        padding: 2rem 1.5rem;
    }

    .article-header h1 {
        font-size: 1.5rem;
    }

    .article-card {
        width: 100%;
    }

    .article-body {
        padding: 1.5rem;
        font-size: 1rem;
    }

    .article-footer {
        flex-direction: column;
        align-items: flex-start;
        gap: 1rem;
    }
}
    .article-content {
    white-space: pre-wrap;
    word-break: break-word;
    }

</style>
</head>

<body>
    <?php 
    if ($_SESSION['user_role'] === 'undergraduate') 
    {
        include __DIR__ . '/../actors/undergraduate/Unavigation.view.php';
    }
    else if ($_SESSION['user_role'] === 'school_leaver') 
    {
       include __DIR__ . '/../actors/students/includes/header2.view.php';
    } 
    else  
    {
        include __DIR__ . '/../layout/nav_home.php';
    }
    ?>
    <div class="article-single-container">
        <div class="article-card">
           <button class="back-btn" onclick="window.history.back()">
                ← Back
            </button>
            <div class="article-header">
                <h1><?= $data['article']['title'] ?></h1>
            </div>
            <div class="article-body article-content">
                <p>    
                    <?= nl2br(htmlspecialchars($data['article']['content'])) ?>
                </p>
                <hr>
                <footer class="article-footer"> 
                   <div class="author">
                       <span>By <?= $data['article']['author_name'] ?></span>
                       <span><?= date('M j, Y', strtotime($data['article']['created_at'])) ?></span>
                   </div>
                   <div class="counts">
                       <span class="view-count" title="Views">
                           <span>view</span>
                           <span id="view-count"><?= (int)$data['article']['views'] ?></span>
                       </span>
                       <button
                           class="like-btn <?= $data['has_liked'] ? 'liked' : '' ?>"
                           id="like-btn"
                           data-article-id="<?= $data['article']['article_id'] ?>"
                           data-liked="<?= $data['has_liked'] ? 'true' : 'false' ?>"
                           <?= !isset($_SESSION['user_id']) ? 'title="Log in to like this article"' : '' ?>
                            >
                           <span class="heart-icon"><?= $data['has_liked'] ? '❤️' : '🤍' ?></span>
                           <span id="like-count"><?= (int)$data['article']['likes'] ?></span>
                       </button>
                   </div>
                </footer>
            </div>
        </div>      
    </div>
    <?php include __DIR__ . '/../layout/footer.php'; ?>
<script>
(function () {
    const btn      = document.getElementById('like-btn');
    const countEl  = document.getElementById('like-count');

    if (!btn) return;

    <?php if (!isset($_SESSION['user_id'])): ?>
    // Guest – redirect to login on click
    btn.addEventListener('click', function () {
        window.location.href = '<?= BASE_URL ?>/login';
    });
    <?php else: ?>
    btn.addEventListener('click', async function () {
        if (btn.disabled) return;
        btn.disabled = true;

        const articleId = btn.dataset.articleId;

        try {
            const response = await fetch('<?= BASE_URL ?>/uarticles/like/' + articleId, {
                method: 'POST',
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            });

            const data = await response.json();

            if (data.success) {
                const heartIcon = btn.querySelector('.heart-icon');

                if (data.liked) {
                    btn.classList.add('liked');
                    heartIcon.textContent = '❤️';
                } else {
                    btn.classList.remove('liked');
                    heartIcon.textContent = '🤍';
                }

                countEl.textContent = data.likes;

                // Pop animation
                btn.classList.add('pop');
                setTimeout(() => btn.classList.remove('pop'), 200);
            }
        } catch (err) {
            console.error('Like request failed:', err);
        } finally {
            btn.disabled = false;
        }
    });
    <?php endif; ?>
}());
</script>
</body>
</html>