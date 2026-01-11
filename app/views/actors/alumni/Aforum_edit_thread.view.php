<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thread Edit</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/css/alumni.css">
</head>
<body>
    
<div class="container">
  <div class="post-header">
    <h1>Edit Thread</h1>

    <?php if (!empty($errors)): ?>
      <div class="alert">
        <ul><?php foreach ($errors as $e): ?><li><?= htmlspecialchars($e) ?></li><?php endforeach; ?></ul>
      </div>
    <?php endif; ?>

    <form method="post">
      <label>Title</label>
      <input type="text" name="title" value="<?= htmlspecialchars($old['title'] ?? $thread->title) ?>" required style="width:100%;padding:8px;margin:.5rem 0 1rem;">

      <label>Body</label>
      <textarea name="body" rows="8" required style="width:100%;padding:8px;"><?= htmlspecialchars($old['body'] ?? $thread->body) ?></textarea>

      <div style="margin-top:1rem;">
        <button class="btn">Save Changes</button>
        <a class="btn" href="<?= BASE_URL ?>/forum/thread/<?= (int)$thread->id ?>">Cancel</a>
      </div>
    </form>
  </div>
</div>

<?php include __DIR__ . '/../../layout/footer.php'; ?>
</body>
</html>