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

<nav class="u-nav">
  <div class="nav-container">
    <div class="logo">✨ logo</div>
    <ul class="nav-links">
      <li><a href="<?= BASE_URL ?>/forum" class="active">Home</a></li>
      <li><a href="#categories">Categories</a></li>
     
    <div class="nav-right">
      <button class="icon-btn" aria-label="Notifications">🔔</button>
      <button class="icon-btn" aria-label="Search">🔍</button>
      <div class="avatar" title="Profile">👤</div>
    </div>
  </div>
</nav>

<div class="main-wrapper">
  <aside class="sidebar" id="categories">
    <?php foreach ($categories as $c): ?>
      <a class="sidebar-item" href="<?= BASE_URL ?>/forum/c/<?= htmlspecialchars($c->slug) ?>">
        <span>📚</span><span><?= htmlspecialchars($c->name) ?></span>
      </a>
    <?php endforeach; ?>
  </aside>

  <div class="content">
    <div class="section-title"><span class="section-arrow">📈</span> Trending Discussions</div>
    <div class="discussions-grid">
      <?php foreach ($trending as $t): ?>
        <a class="discussion-card" href="<?= BASE_URL ?>/forum/thread/<?= (int)$t->id ?>">
          <div class="card-title"><?= htmlspecialchars($t->title) ?></div>
          <div class="card-tags"><span class="card-tag">Trending</span></div>
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

    <div class="recent-section">
      <div class="section-title"><span class="section-arrow">🕐</span> Recent Posts</div>
      <div class="discussions-grid">
        <?php foreach ($recent as $r): ?>
          <a class="discussion-card" href="<?= BASE_URL ?>/forum/thread/<?= (int)$r->id ?>">
            <div class="card-title"><?= htmlspecialchars($r->title) ?></div>
            <div class="card-tags"><span class="card-tag">New</span></div>
            <div class="card-footer">
              <div class="card-avatar"><?= strtoupper(substr($r->username,0,2)) ?></div>
              <div class="card-author">
                <div class="author-name"><?= htmlspecialchars($r->username) ?></div>
                <div class="author-time"><?= htmlspecialchars($r->last_post_at) ?></div>
              </div>
              <div class="card-engagement">💬</div>
            </div>
          </a>
        <?php endforeach; ?>
      </div>
    </div>
  </div>
</div>

<button class="fab" id="fabNew" aria-label="Create new discussion">+</button>

<!-- New Thread Modal -->
<div id="newThreadModal" class="modal-backdrop" aria-hidden="true" role="dialog" aria-modal="true" aria-labelledby="newThreadTitle">
  <div class="modal-window">
    <div class="modal-header">
      <h3 id="newThreadTitle">Start a New Discussion</h3>
      <button type="button" class="modal-close" aria-label="Close">&times;</button>
    </div>
    <div class="modal-body">
      <form id="newThreadForm" action="<?= BASE_URL ?>/forum/create" method="post">
        <label>Category</label>
        <select name="category_id" required>
          <option value="">Select a category</option>
          <?php foreach ($categories as $c): ?>
            <option value="<?= (int)$c->id ?>"><?= htmlspecialchars($c->name) ?></option>
          <?php endforeach; ?>
        </select>

        <label>Title</label>
        <input type="text" name="title" required>

        <label>Body</label>
        <textarea name="body" rows="6" required></textarea>

        <div class="modal-footer">
          <button type="submit" class="btn">Create</button>
        </div>
      </form>
      <div class="modal-errors" id="formErrors" style="display:none;"></div>
    </div>
  </div>
</div>

<script src="<?= BASE_URL ?>/assets/js/forum_home.js"></script>


</body>
</html>