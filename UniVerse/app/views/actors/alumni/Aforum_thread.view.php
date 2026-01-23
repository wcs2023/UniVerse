<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title ?? 'Forum Thread') ?></title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/forum_thread.css">
    <script>window.__APP_ROOT__=<?= json_encode(BASE_URL) ?>;</script>
</head>
<body>

<nav class="u-nav">
  <div class="nav-container">
    <div class="logo">✨ logo</div>
    <ul class="nav-links">
      <li><a href="<?= BASE_URL ?>/forum">Home</a></li>
      <li><a href="<?= BASE_URL ?>/forum#categories">Categories</a></li>
    </ul>
    <div class="nav-right"><div class="avatar">👤</div></div>
  </div>
</nav>

<div class="container">
  <div class="post-header">
    <div class="author-info">
      <div class="author-avatar"><?= strtoupper(substr($thread->username ?? 'U',0,2)) ?></div>
      <div class="author-meta">
        <div class="author-name"><?= htmlspecialchars($thread->username ?? 'User') ?></div>
        <div class="author-time"><?= htmlspecialchars($thread->created_at ?? '') ?></div>
      </div>
    </div>

    <h1 class="post-title"><?= htmlspecialchars($thread->title) ?></h1>

    <div class="post-tags"><span class="tag">Discussion</span></div>

    <div class="post-content"><?= nl2br(htmlspecialchars($thread->body)) ?></div>

    <?php
      $user = $_SESSION['USER'] ?? null;
      $isOwner = $user && ((int)$user->id === (int)$thread->user_id);
      $isMod   = $user && !empty($user->is_admin);
    ?>
    <div class="post-actions">
      <?php if ($isOwner || $isMod): ?>
        <a class="action-item" href="<?= BASE_URL ?>/forum/edit_thread/<?= (int)$thread->id ?>">✏️ Edit</a>
        <form action="<?= BASE_URL ?>/forum/delete_thread/<?= (int)$thread->id ?>" method="post" style="display:inline;">
          <button class="action-item" onclick="return confirm('Delete this thread?')">🗑 Delete</button>
        </form>
      <?php endif; ?>
      <a class="action-item" href="#replyBox"><span>💬</span><span>Reply</span></a>
    </div>
  </div>

  <div class="replies-section">
    <h2 class="replies-title">Replies (<?= count($posts) ?>)</h2>

    <?php foreach ($posts as $p): ?>
      <?php $isReplyOwner = $user && ((int)$user->id === (int)$p->user_id); ?>
      <article class="reply-item" id="p<?= (int)$p->id ?>">
        <div class="reply-author">
          <div class="reply-avatar"><?= strtoupper(substr($p->username ?? 'U',0,2)) ?></div>
          <div class="reply-meta">
            <div class="reply-name"><?= htmlspecialchars($p->username ?? 'User') ?></div>
            <div class="reply-time"><?= htmlspecialchars($p->created_at ?? '') ?></div>
          </div>
        </div>
        <div class="reply-content"><?= nl2br(htmlspecialchars($p->body)) ?></div>
        <div class="reply-actions">
          <button class="reply-action js-post-like" data-id="<?= (int)$p->id ?>">❤️ Like</button>
          <span class="reply-action">Score: <span id="score-<?= (int)$p->id ?>"><?= (int)($p->upvotes ?? 0) ?></span></span>
          <?php if ($isReplyOwner || $isMod): ?>
            <a class="reply-action" href="<?= BASE_URL ?>/forum/edit_post/<?= (int)$p->id ?>">✏️ Edit</a>
            <form action="<?= BASE_URL ?>/forum/delete_post/<?= (int)$p->id ?>" method="post" style="display:inline;">
              <button class="reply-action" onclick="return confirm('Delete this reply?')">🗑 Delete</button>
            </form>
          <?php endif; ?>
        </div>
      </article>
    <?php endforeach; ?>

    <section class="reply-box" id="replyBox" style="margin-top:1.25rem;">
      <form id="replyForm" action="<?= BASE_URL ?>/forum/reply/<?= (int)$thread->id ?>" method="post">
        <textarea name="body" rows="4" required placeholder="Write a reply…"></textarea>
        <div style="margin-top:.5rem;">
          <button type="submit" class="btn">Post Reply</button>
        </div>
        <div id="replyError" class="alert" style="display:none;"></div>
      </form>
    </section>
  </div>
</div>

<script src="<?= BASE_URL ?>/assets/js/forum_thread.js"></script>


</body>
</html>