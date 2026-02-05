<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Post</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/forum_post.css">
    <style>
        body { background-color: #a78bfa45 !important; }
    </style>
</head>
<body>
<div class="container">
  <div class="post-header">
    <h1>Edit Reply</h1>

    <?php if (!empty($errors)): ?>
      <div class="alert">
        <ul><?php foreach ($errors as $e): ?><li><?= htmlspecialchars($e) ?></li><?php endforeach; ?></ul>
      </div>
    <?php endif; ?>

    <form method="post">
      <label>Body</label>
      <textarea name="body" rows="8" required style="width:100%;padding:8px;"><?= htmlspecialchars($old['body'] ?? $post->body) ?></textarea>

      <div style="margin-top:1rem;">
        <button class="btn">Save Changes</button>
        <a class="btn" href="<?= BASE_URL ?>/forum/thread/<?= (int)$post->thread_id ?>#p<?= (int)$post->id ?>">Cancel</a>
      </div>
    </form>
  </div>
</div>

</body>
</html>