<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title ?? 'Forum Category') ?></title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/forum_home.css">
   
</head>
<boBASE_URL
<nav class="u-nav">
  <div class="nav-container">
    <div class="logo">✨ logo</div>
    <ul class="nav-links">
      <li><a href="<?= BASE_URL ?>/forum">Home</a></li>
      <li><a href="<?= BASE_URL ?>/forum#categories" class="active">Categories</a></li>
    </ul>
    <div class="nav-right">
      <div class="avatar">👤</div>
    </div>
  </div>
</nav>

<div class="main-wrapper">
  <aside class="sidebar">
    <a class="sidebar-item active"><span>📚</span><span><?= htmlspecialchars($category->name) ?></span></a>
  </aside>
  <div class="content">
    <div class="section-title"><span class="section-arrow">📂</span> <?= htmlspecialchars($category->name) ?></div>
    <div class="discussions-grid">
      <?php foreach ($threads as $t): ?>
        <a class="discussion-card" href="<?= BASE_URL ?>/forum/thread/<?= (int)$t->id ?>">
          <?php if ($t->is_pinned): ?><div class="card-tag">📌 Pinned</div><?php endif; ?>
          <div class="card-title"><?= htmlspecialchars($t->title) ?></div>
          <div class="card-footer">
            <div class="card-avatar"><?= strtoupper(substr($t->username,0,2)) ?></div>
            <div class="card-author">
              <div class="author-name"><?= htmlspecialchars($t->username) ?></div>
              <div class="author-time"><?= htmlspecialchars($t->last_post_at) ?></div>
            </div>
            <div class="card-engagement">👁 <?= (int)$t->views ?></div>
          </div>
        </a>
      <?php endforeach; ?>
    </div>
  </div>
</div>



</body>
</html>