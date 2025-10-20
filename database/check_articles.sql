-- Check current articles
SELECT article_id, title, category, status, created_at 
FROM articles 
ORDER BY created_at DESC 
LIMIT 10;

-- Update articles to have published status and categories (if needed)
-- Uncomment the lines below to update your articles:

-- UPDATE articles SET status = 'published' WHERE status = 'draft';
-- UPDATE articles SET category = 'technology' WHERE article_id = 26;
-- UPDATE articles SET category = 'career' WHERE article_id = 27;
-- UPDATE articles SET category = 'education' WHERE article_id = 28;
