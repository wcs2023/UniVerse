<!DOCTYPE html>
<html lang="en">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
<head>          
        <link rel="stylesheet" href="<?= BASE_URL ?>/css/styles.css">
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

.article-single-container
{
    min-height: 80vh;
    padding: 100px 20px 60px 20px;
    display: block;
    justify-content: center;
    /* background: var(--light-gray); */
}

.article-header
{
    padding: 4rem 3rem;
    border-radius: 25px;
    margin-top: 10%;
    background-color: var(--light-gray);
}
         
.article-card
{
    margin-left: 20%;
    margin-right: 20%;
    
}

.article-body
{
    font-size:large;
    background-color: var(--light-gray);
    border-radius: 25px;
    padding: 4rem 3rem;
    margin-top:1% ;
}
    
.footer
{
    /* align-items: end; */
    /* text-align: right; */
    display: flex;
    /* flex-direction: column-reverse; */

}

.author
{
    flex-direction: column;
    display: flex;
}

.count 
{
    text-align: left;



}

 @media(max-width:768px) 
 {
    .article-single-container
    {
        min-height: 40vh;
        padding: 1rem;
    }

    .article-header
    {
        padding:3rem;
        font-size: smaller;
        border-radius: 20px;
    }

    .article-card
    {
        margin-left: 10%;
        margin-right: 10%;
    }
 }

</style>
</head>

<body>
    <?php include __DIR__ . '/../actors/undergraduate/Unavigation.view.php'; ?> 
    <div class="article-single-container">
        <div class="article-card">
            <div class="article-header">
                <h1><?= $data['article']['title'] ?></h1>
            </div>
            <div class="article-body">
                <p>
                    <?= $data['article']['content']?>
                     <?//=print_r($data['article'])?>
                </p>
                <footer class="article-footer"> 
                    <br>
                    <hr> 
                    <br>
                   <div class="author">
                       <span>By <?= $data['article']['author_name'] ?></span>
                       <span> <?= date('M j,Y',strtotime($data['article']['created_at'])) ?></span>
                   </div>
                   <div class="counts">
                    ❤️<span> <?= $data['article']['likes'] ?> </span>
                    💬<span> <?= $data['article']['comments_count'] ?> </span>
                   </div>
                </footer>
        
            </div>
        </div>      
    </div>
    <?php include __DIR__ . '/../layout/footer.php'; ?>
</body>

<!-- <script> 
    // Article interaction functionality
    document.querySelector('.like-btn')?.addEventListener('click', function() {
        this.classList.toggle('liked');
        // In a real application, this would send an AJAX request
        const articleId = this.getAttribute('data-article-id');
        console.log('Liked article:', articleId);
    });

    document.querySelector('.share-btn')?.addEventListener('click', function() {
        // Simple share functionality
        if (navigator.share) {
            navigator.share({
                title: '<//?= addslashes($data['article']['title']) ?>',
                text: '<//?= addslashes($data['article']['excerpt'] ?? '') ?>',
                url: window.location.href
            }).catch(error => console.log('Error sharing:', error));
        } else {
            // Fallback: copy URL to clipboard
            navigator.clipboard.writeText(window.location.href)
                .then(() => {
                    alert('Article URL copied to clipboard!');
                })
                .catch(err => {
                    console.error('Could not copy text: ', err);
                });
        }
    });

    document.querySelector('.bookmark-btn')?.addEventListener('click', function() {
        this.classList.toggle('bookmarked');
        // In a real application, this would save to user's bookmarks
        const isBookmarked = this.classList.contains('bookmarked');
        console.log('Bookmark status:', isBookmarked);
    });
</script> -->

</html>