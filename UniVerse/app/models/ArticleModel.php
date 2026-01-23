<?php

class ArticleModel extends Model
{
    protected $db;
    
    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }
    
    /**
     * Get articles by author and status
     */
    public function getArticlesByStatus($authorId, $status)
    {
        try {
            $query = "SELECT 
                        a.article_id,
                        a.title,
                        a.content,
                        a.status,
                        a.category,
                        a.tags,
                        a.views,
                        a.likes,
                        a.created_at,
                        a.updated_at,
                        a.published_at
                      FROM articles a
                      WHERE a.user_id = ?
                      AND a.status = ?
                      ORDER BY a.updated_at DESC";
            
            $stmt = $this->db->prepare($query);
            $stmt->execute([$authorId, $status]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            error_log("Error getting articles by status: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Get article by ID
     */
    public function getArticleById($articleId)
    {
        try {
            $query = "SELECT 
                        a.*,
                        u.first_name,
                        u.last_name,
                        u.profile_picture,
                        CONCAT(u.first_name, ' ', u.last_name) as author_name
                      FROM articles a
                      JOIN users u ON a.user_id = u.user_id
                      WHERE a.article_id = ?";
            
            $stmt = $this->db->prepare($query);
            $stmt->execute([$articleId]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            error_log("Error getting article by ID: " . $e->getMessage());
            return null;
        }
    }
    
    /**
     * Create a new article
     */
    public function createArticle($authorId, $title, $content, $status = 'draft', $category = '', $tags = '')
    {
        try {
            $publishedAt = ($status === 'published') ? date('Y-m-d H:i:s') : null;
            
            $query = "INSERT INTO articles 
                      (user_id, title, content, status, category, tags, published_at, created_at, updated_at)
                      VALUES (?, ?, ?, ?, ?, ?, ?, NOW(), NOW())";
            
            $stmt = $this->db->prepare($query);
            $stmt->execute([$authorId, $title, $content, $status, $category, $tags, $publishedAt]);
            
            return $this->db->lastInsertId();
        } catch(PDOException $e) {
            error_log("Error creating article: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Update an existing article
     */
    public function updateArticle($articleId, $title, $content, $status, $category = '', $tags = '')
    {
        try {
            // Get current article to check if status changed to published
            $currentArticle = $this->getArticleById($articleId);
            $publishedAt = null;
            
            if ($status === 'published' && $currentArticle['status'] !== 'published') {
                // First time publishing
                $publishedAt = date('Y-m-d H:i:s');
            } elseif ($status === 'published' && $currentArticle['published_at']) {
                // Already published, keep original date
                $publishedAt = $currentArticle['published_at'];
            }
            
            $query = "UPDATE articles 
                      SET title = ?,
                          content = ?,
                          status = ?,
                          category = ?,
                          tags = ?,
                          published_at = ?,
                          updated_at = NOW()
                      WHERE article_id = ?";
            
            $stmt = $this->db->prepare($query);
            return $stmt->execute([$title, $content, $status, $category, $tags, $publishedAt, $articleId]);
        } catch(PDOException $e) {
            error_log("Error updating article: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Delete an article
     */
    public function deleteArticle($articleId)
    {
        try {
            $query = "DELETE FROM articles WHERE article_id = ?";
            $stmt = $this->db->prepare($query);
            return $stmt->execute([$articleId]);
        } catch(PDOException $e) {
            error_log("Error deleting article: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Increment article view count
     */
    public function incrementViews($articleId)
    {
        try {
            $query = "UPDATE articles SET views = views + 1 WHERE article_id = ?";
            $stmt = $this->db->prepare($query);
            return $stmt->execute([$articleId]);
        } catch(PDOException $e) {
            error_log("Error incrementing views: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Increment article like count
     */
    public function incrementLikes($articleId)
    {
        try {
            $query = "UPDATE articles SET likes = likes + 1 WHERE article_id = ?";
            $stmt = $this->db->prepare($query);
            return $stmt->execute([$articleId]);
        } catch(PDOException $e) {
            error_log("Error incrementing likes: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Get all published articles (for public viewing)
     */
    public function getAllPublishedArticles($limit = 10, $offset = 0)
    {
        try {
            $query = "SELECT 
                        a.*,
                        u.first_name,
                        u.last_name,
                        u.profile_picture,
                        CONCAT(u.first_name, ' ', u.last_name) as author_name
                      FROM articles a
                      JOIN users u ON a.user_id = u.user_id
                      WHERE a.status = 'published'
                      ORDER BY a.published_at DESC
                      LIMIT ? OFFSET ?";
            
            $stmt = $this->db->prepare($query);
            $stmt->execute([$limit, $offset]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            error_log("Error getting published articles: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Search articles by keyword
     */
    public function searchArticles($keyword, $limit = 10)
    {
        try {
            $searchTerm = "%$keyword%";
            
            $query = "SELECT 
                        a.*,
                        u.first_name,
                        u.last_name,
                        CONCAT(u.first_name, ' ', u.last_name) as author_name
                      FROM articles a
                      JOIN users u ON a.user_id = u.user_id
                      WHERE a.status = 'published'
                      AND (a.title LIKE ? OR a.content LIKE ? OR a.tags LIKE ?)
                      ORDER BY a.published_at DESC
                      LIMIT ?";
            
            $stmt = $this->db->prepare($query);
            $stmt->execute([$searchTerm, $searchTerm, $searchTerm, $limit]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            error_log("Error searching articles: " . $e->getMessage());
            return [];
        }
    }
}
