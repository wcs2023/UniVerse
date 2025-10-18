<?php

class Article extends Model 
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Get all published articles
     */
    public function getAllArticles($limit = null)
    {
        $query = "SELECT a.*, u.first_name, u.last_name 
                  FROM articles a
                  LEFT JOIN users u ON a.user_id = u.user_id
                  WHERE a.status = 'published'
                  ORDER BY a.created_at DESC";
        
        if ($limit) {
            $query .= " LIMIT :limit";
            return $this->fetchAll($query, ['limit' => $limit]);
        }
        
        return $this->fetchAll($query);
    }

    /**
     * Get article by ID
     */
    public function getArticleById($articleId)
    {
        $query = "SELECT a.*, u.first_name, u.last_name, u.email
                  FROM articles a
                  LEFT JOIN users u ON a.user_id = u.user_id
                  WHERE a.article_id = :article_id";
        
        return $this->fetch($query, ['article_id' => $articleId]);
    }

    /**
     * Get articles by category
     */
    public function getArticlesByCategory($category, $limit = null)
    {
        $query = "SELECT a.*, u.first_name, u.last_name 
                  FROM articles a
                  LEFT JOIN users u ON a.user_id = u.user_id
                  WHERE a.category = :category AND a.status = 'published'
                  ORDER BY a.created_at DESC";
        
        if ($limit) {
            $query .= " LIMIT :limit";
            return $this->fetchAll($query, ['category' => $category, 'limit' => $limit]);
        }
        
        return $this->fetchAll($query, ['category' => $category]);
    }

    /**
     * Get articles by author/user
     */
    public function getArticlesByUser($userId, $limit = null)
    {
        $query = "SELECT a.*, u.first_name, u.last_name 
                  FROM articles a
                  LEFT JOIN users u ON a.user_id = u.user_id
                  WHERE a.user_id = :user_id
                  ORDER BY a.created_at DESC";
        
        if ($limit) {
            $query .= " LIMIT :limit";
            return $this->fetchAll($query, ['user_id' => $userId, 'limit' => $limit]);
        }
        
        return $this->fetchAll($query, ['user_id' => $userId]);
    }

    /**
     * Increment article views
     */
    public function incrementViews($articleId)
    {
        $query = "UPDATE articles SET views = views + 1 WHERE article_id = :article_id";
        return $this->query($query, ['article_id' => $articleId]);
    }

    /**
     * Create new article
     */
    public function createArticle($data)
    {
        $query = "INSERT INTO articles (title, slug, excerpt, content, category, user_id, featured_image, status)
                  VALUES (:title, :slug, :excerpt, :content, :category, :user_id, :featured_image, :status)";
        
        return $this->query($query, [
            'title' => $data['title'],
            'slug' => $data['slug'],
            'excerpt' => $data['excerpt'],
            'content' => $data['content'],
            'category' => $data['category'],
            'user_id' => $data['user_id'],
            'featured_image' => $data['featured_image'] ?? null,
            'status' => $data['status'] ?? 'draft'
        ]);
    }

    /**
     * Update article
     */
    public function updateArticle($articleId, $data)
    {
        $query = "UPDATE articles 
                  SET title = :title, slug = :slug, excerpt = :excerpt, content = :content,
                      category = :category, featured_image = :featured_image, status = :status
                  WHERE article_id = :article_id";
        
        return $this->query($query, [
            'article_id' => $articleId,
            'title' => $data['title'],
            'slug' => $data['slug'],
            'excerpt' => $data['excerpt'],
            'content' => $data['content'],
            'category' => $data['category'],
            'featured_image' => $data['featured_image'] ?? null,
            'status' => $data['status'] ?? 'draft'
        ]);
    }

    /**
     * Delete article
     */
    public function deleteArticle($articleId)
    {
        $query = "DELETE FROM articles WHERE article_id = :article_id";
        return $this->query($query, ['article_id' => $articleId]);
    }

    /**
     * Get article categories with count
     */
    public function getCategoriesWithCount()
    {
        $query = "SELECT category, COUNT(*) as count 
                  FROM articles 
                  WHERE status = 'published'
                  GROUP BY category
                  ORDER BY count DESC";
        
        $result = $this->fetchAll($query);
        
        // Debug log
        error_log("Categories query result: " . print_r($result, true));
        
        return $result;
    }

    /**
     * Get all unique categories
     */
    public function getCategories()
    {
        $query = "SELECT DISTINCT category 
                  FROM articles 
                  WHERE status = 'published'
                  ORDER BY category ASC";
        
        return $this->fetchAll($query);
    }

    /**
     * Search articles
     */
    public function searchArticles($searchTerm, $limit = null)
    {
        $query = "SELECT a.*, u.first_name, u.last_name 
                  FROM articles a
                  LEFT JOIN users u ON a.user_id = u.user_id
                  WHERE a.status = 'published' 
                  AND (a.title LIKE :search OR a.excerpt LIKE :search OR a.content LIKE :search)
                  ORDER BY a.created_at DESC";
        
        $searchParam = '%' . $searchTerm . '%';
        
        if ($limit) {
            $query .= " LIMIT :limit";
            return $this->fetchAll($query, ['search' => $searchParam, 'limit' => $limit]);
        }
        
        return $this->fetchAll($query, ['search' => $searchParam]);
    }
}
