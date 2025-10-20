<?php

class AarticleModel extends Model
{
    /**
     * Get articles by status
     */
    public function getArticlesByStatus($userId, $status)
    {
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
                  FROM Articles a
                  WHERE a.user_id = :user_id
                  AND a.status = :status
                  ORDER BY a.updated_at DESC";
        
        return $this->fetchAll($query, [
            'user_id' => $userId,
            'status' => $status
        ]);
    }
    
    /**
     * Get article by ID
     */
    public function getArticleById($articleId)
    {
        $query = "SELECT 
                    a.*,
                    u.first_name,
                    u.last_name,
                    u.profile_picture,
                    CONCAT(u.first_name, ' ', u.last_name) as author_name
                  FROM Articles a
                  JOIN Users u ON a.user_id = u.user_id
                  WHERE a.article_id = :article_id";
        
        return $this->fetch($query, ['article_id' => $articleId]);
    }
    
    /**
     * Create a new article
     */
    public function createArticle($userId, $title, $content, $status = 'draft', $category = '', $tags = '')
    {
        $publishedAt = ($status === 'published') ? date('Y-m-d H:i:s') : null;
        
        // Handle empty values
        $category = empty($category) ? null : $category;
        $tags = empty($tags) ? null : $tags;
        
        $data = [
            'user_id' => $userId,
            'title' => $title,
            'content' => $content,
            'status' => $status,
            'category' => $category,
            'tags' => $tags,
            'views' => 0,
            'likes' => 0,
            'comments_count' => 0,
            'published_at' => $publishedAt,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ];
        
        return $this->insert('Articles', $data);
    }
    
    /**
     * Update an existing article
     */
    public function updateArticle($articleId, $title, $content, $status, $category = '', $tags = '')
    {
        // Get current article
        $currentArticle = $this->getArticleById($articleId);
        $publishedAt = null;
        
        if ($currentArticle) {
            if ($status === 'published' && $currentArticle['status'] !== 'published') {
                $publishedAt = date('Y-m-d H:i:s');
            } elseif ($status === 'published' && !empty($currentArticle['published_at'])) {
                $publishedAt = $currentArticle['published_at'];
            }
        }
        
        // Handle empty values
        $category = empty($category) ? null : $category;
        $tags = empty($tags) ? null : $tags;
        
        $data = [
            'title' => $title,
            'content' => $content,
            'status' => $status,
            'category' => $category,
            'tags' => $tags,
            'published_at' => $publishedAt,
            'updated_at' => date('Y-m-d H:i:s')
        ];
        
        return $this->update('Articles', $data, 'article_id = :article_id', ['article_id' => $articleId]);
    }
    
    /**
     * Delete an article
     */
    public function deleteArticle($articleId)
    {
        return $this->delete('Articles', 'article_id = :article_id', ['article_id' => $articleId]);
    }
    
    /**
     * Increment article views
     */
    public function incrementViews($articleId)
    {
        $query = "UPDATE Articles SET views = views + 1 WHERE article_id = :article_id";
        return $this->query($query, ['article_id' => $articleId]);
    }
    
    /**
     * Get all published articles (for public viewing)
     */
    public function getAllPublishedArticles($limit = 10, $offset = 0)
    {
        $query = "SELECT 
                    a.*,
                    u.first_name,
                    u.last_name,
                    u.profile_picture,
                    CONCAT(u.first_name, ' ', u.last_name) as author_name
                  FROM Articles a
                  JOIN Users u ON a.user_id = u.user_id
                  WHERE a.status = 'published'
                  ORDER BY a.published_at DESC
                  LIMIT :limit OFFSET :offset";
        
        return $this->fetchAll($query, [
            'limit' => $limit,
            'offset' => $offset
        ]);
    }

    // ==================== ADMIN METHODS ====================

    /**
     * Get all articles (for admin panel)
     */
    public function getAllArticles()
    {
        $query = "SELECT 
                    a.article_id,
                    a.title,
                    a.status,
                    a.category,
                    a.views,
                    a.likes,
                    a.comments_count,
                    a.created_at,
                    a.published_at,
                    CONCAT(u.first_name, ' ', u.last_name) as author_name,
                    u.email as author_email,
                    u.user_type
                  FROM Articles a
                  JOIN Users u ON a.user_id = u.user_id
                  ORDER BY a.created_at DESC";
        
        return $this->fetchAll($query);
    }

    /**
     * Get total articles count
     */
    public function getTotalArticlesCount()
    {
        $query = "SELECT COUNT(*) as count FROM Articles";
        $result = $this->fetch($query);
        return $result['count'] ?? 0;
    }

    /**
     * Get pending articles count (drafts)
     */
    public function getPendingArticlesCount()
    {
        $query = "SELECT COUNT(*) as count FROM Articles WHERE status = 'draft'";
        $result = $this->fetch($query);
        return $result['count'] ?? 0;
    }

    /**
     * Get articles by status (for admin)
     */
    public function getArticlesByStatusAdmin($status)
    {
        $query = "SELECT 
                    a.*,
                    CONCAT(u.first_name, ' ', u.last_name) as author_name,
                    u.email as author_email
                  FROM Articles a
                  JOIN Users u ON a.user_id = u.user_id
                  WHERE a.status = :status
                  ORDER BY a.created_at DESC";
        
        return $this->fetchAll($query, ['status' => $status]);
    }

    /**
     * Update article status (for admin moderation)
     */
    public function updateArticleStatus($articleId, $status)
    {
        $data = [
            'status' => $status,
            'updated_at' => date('Y-m-d H:i:s')
        ];
        
        if ($status === 'published') {
            $data['published_at'] = date('Y-m-d H:i:s');
        }
        
        return $this->update('Articles', $data, 'article_id = :article_id', ['article_id' => $articleId]);
    }
}

