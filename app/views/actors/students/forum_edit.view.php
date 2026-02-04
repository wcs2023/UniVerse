<head>
    <link rel="stylesheet" href="<?= BASE_URL; ?>/assets/css/forum/forum_create_styles.css">
</head>
<?php
$pageTitle = $title ?? 'Start a Discussion';
include_once __DIR__ . '/includes/header2.view.php';
?>

<body>
    <main class="main-container">
        <div class="form-container">
            <div class="form-header">
                <h1><i class="fa-solid fa-edit"></i> Edit your post</h1>
                <a href="<?= BASE_URL ?>/Discussion_Forum/view_thread/<?= $thread['thread_id'] ?>" class="btn-back"><i class="fa-solid fa-arrow-left"></i>Back to Forums</a>
            </div>

            <!-- <div class="alert alert-error">
                <i class="fa-solid fa-exclamation-circle"></i>
                error
            </div> -->

            <form action="POST" action="<?= BASE_URL ?>/Discussion_Forum/edit_post/<?= $thread['thread_id'] ?>" class="create-form">
                <div class="form-group">
                    <label for="cat_id">Category <span class="required">*</span></label>
                    <select name="cat_id" id="cat_id" required>
                        <option value="" disabled selected>Select a Category</option>
                        <?php if (isset($categories)): ?>
                            <?php foreach ($categories as $cat): ?>
                                <option value="<?= $cat['cat_id'] ?>" <?= (isset($old['$cat_id']) ? $old['cat_id'] : $thread['cat_id']) == $cat['cat_id'] ? 'selected' : '' ?>><?= htmlspecialchars($cat['name']) ?></option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="title">Thread Title <span class="required">*</span></label>
                    <input type="text" id="title" name="title" value="<?=htmlspecialchars($old['title'] ?? $thread['title']) ?>" placeholder="Enter the title" minlength="5" required>
                    <small>Minimum 5 characters</small>
                </div>

                <div class="form-group">
                    <label for="content">Content<span class="required">*</span></label>
                    <textarea id="content" name="content" required placeholder="Write your question or discussion here..." minlength="10"><?= htmlspecialchars($old['content'] ?? $thread['content']) ?></textarea>
                    <small>Minimum 10 characters</small>
                </div>

                <div class="form-action">
                    <button type="submit" class="btn btn-primary">
                        <i class="fa-solid fa-save"></i> Save
                    </button>
                    <a href="<?= BASE_URL ?>/Discussion_Forum/index" class="btn btn-cancel">Cancel</a>
                </div>
            </form>
        </div>
    </main>


</body>