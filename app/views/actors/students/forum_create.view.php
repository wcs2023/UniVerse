<head>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">    
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/forum/forum_home_styles.css">
    <link rel="stylesheet" href="<?= BASE_URL; ?>/assets/css/forum/forum_create_styles.css">

    <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'undergraduate'): ?>
        <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/styles.css">
    <?php endif; ?>
    
    <link rel="icon" type="image/png" href="<?= BASE_URL ?>/assets/images/U.png">
    
    <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'school_leaver'): ?>
        <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/student_style.css">    
    <?php endif; ?>

    <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'alumni'): ?>
        <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/alumni.css">   
    <?php endif; ?>

</head>
    <?php 
        if ($_SESSION['user_role'] === 'undergraduate') 
        {
            include __DIR__ . '/../undergraduate/Unavigation.view.php';
        }
        else if ($_SESSION['user_role'] === 'school_leaver') 
        {
        include __DIR__ . '/includes/header2.view.php';
        } 
        else if ($_SESSION['user_role'] === 'alumni') 
        {
            include __DIR__ . '/../alumni/Anavbar.php';
        }
        else  
        {
            include __DIR__ . '/../layout/nav_home.php';
        }
    ?>


<body>
    <main class="main-container">
        <div class="form-container">
            <div class="form-header">
                <h1><i class="fa-solid fa-circle-plus"></i> Start a Discussion</h1>
                <a href="<?= BASE_URL ?>/Discussion_Forum/index" class="btn-back"><i class="fa-solid fa-arrow-left"></i>Back to Forums</a>
            </div>

            <!-- <div class="alert alert-error">
                <i class="fa-solid fa-exclamation-circle"></i>
                error
            </div> -->

            <form method="POST" action="<?= BASE_URL ?>/Discussion_Forum/create_posts" class="create-form">
                <div class="form-group">
                    <label for="cat_id">Category <span class="required">*</span></label>
                    <select name="cat_id" id="cat_id" required>
                        <option value="" disabled selected hidden class="placeholder">Select a Category</option>
                        <?php if (isset($categories)): ?>
                            <?php foreach ($categories as $cat): ?>
                                <option value="<?= $cat['cat_id'] ?>" <?= (isset($old['cat_id']) && $old['cat_id'] == $cat['cat_id']) ? 'selected' : '' ?>><?= htmlspecialchars($cat['name']) ?></option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="title">Thread Title <span class="required">*</span></label>
                    <input type="text" id="title" name="title" value="<?= htmlspecialchars($old['title'] ?? '') ?>" placeholder="Enter the title" minlength="5" required>
                    <small>Minimum 5 characters</small>
                </div>

                <div class="form-group">
                    <label for="content">Content<span class="required">*</span></label>
                    <textarea id="content" name="content" required placeholder="Write your question or discussion here..." minlength="10" ><?= htmlspecialchars($old['content'] ?? '') ?></textarea>
                    <small>Minimum 10 characters</small>
                </div>

                <div class="form-action">
                    <button type="submit" class="btn btn-primary">
                        <i class="fa-solid fa-paper-plane"></i> Create Thread
                    </button>
                        <a href="<?= BASE_URL ?>/Discussion_Forum/index" class="btn btn-cancel">Cancel</a>
                </div>
            </form>
        </div>
    </main>
                                
    <?php include __DIR__ . '/../../layout/footer.php'; ?>
</body>