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
     * Get all published articles (for public viewing) - FIXED FOR SCHOOL LEAVERS
     */
    public function getAllPublishedArticles($limit = null, $offset = 0)
    {
        $sql = "SELECT 
                    a.article_id,
                    a.title,
                    a.content,
                    a.category,
                    a.tags,
                    a.views,
                    a.likes,
                    a.created_at,
                    a.published_at,
                    u.first_name,
                    u.last_name,
                    u.profile_picture,
                    CONCAT(u.first_name, ' ', u.last_name) as author_name,
                    SUBSTRING(a.content, 1, 200) as excerpt,
                    CONCAT(CEIL(CHAR_LENGTH(a.content) / 200), ' min read') as read_time
                  FROM Articles a
                  JOIN Users u ON a.user_id = u.user_id
                  WHERE a.status = 'published'
                  ORDER BY a.published_at DESC";
        
        if ($limit) {
            $sql .= " LIMIT " . intval($limit) . " OFFSET " . intval($offset);
        }
        
        return $this->fetchAll($sql);
    }

    /**
     * Get articles by category - NEW METHOD
     */
    public function getArticlesByCategory($category, $limit = null)
    {
        $sql = "SELECT 
                    a.article_id,
                    a.title,
                    a.content,
                    a.category,
                    a.tags,
                    a.views,
                    a.likes,
                    a.created_at,
                    a.published_at,
                    u.first_name,
                    u.last_name,
                    CONCAT(u.first_name, ' ', u.last_name) as author_name,
                    SUBSTRING(a.content, 1, 200) as excerpt,
                    CONCAT(CEIL(CHAR_LENGTH(a.content) / 200), ' min read') as read_time
                  FROM Articles a
                  JOIN Users u ON a.user_id = u.user_id
                  WHERE a.status = 'published' AND a.category = :category
                  ORDER BY a.published_at DESC";
        
        if ($limit) {
            $sql .= " LIMIT " . intval($limit);
        }
        
        return $this->fetchAll($sql, ['category' => $category]);
    }

    /**
     * Search articles - NEW METHOD
     */
    public function searchArticles($query, $limit = null)
    {
        $searchTerm = '%' . $query . '%';
        
        $sql = "SELECT 
                    a.article_id,
                    a.title,
                    a.content,
                    a.category,
                    a.tags,
                    a.views,
                    a.likes,
                    a.created_at,
                    a.published_at,
                    u.first_name,
                    u.last_name,
                    CONCAT(u.first_name, ' ', u.last_name) as author_name,
                    SUBSTRING(a.content, 1, 200) as excerpt,
                    CONCAT(CEIL(CHAR_LENGTH(a.content) / 200), ' min read') as read_time
                  FROM Articles a
                  JOIN Users u ON a.user_id = u.user_id
                  WHERE a.status = 'published' 
                  AND (a.title LIKE :search1 OR a.content LIKE :search2 OR a.category LIKE :search3)
                  ORDER BY a.published_at DESC";
        
        if ($limit) {
            $sql .= " LIMIT " . intval($limit);
        }
        
        return $this->fetchAll($sql, [
            'search1' => $searchTerm,
            'search2' => $searchTerm,
            'search3' => $searchTerm
        ]);
    }

    /**
     * Get all categories - NEW METHOD
     */
    public function getCategories()
    {
        try {
            $query = "SELECT DISTINCT category 
                     FROM Articles 
                     WHERE status = 'published' 
                     AND category IS NOT NULL 
                     AND category != ''
                     ORDER BY category ASC";
            
            $result = $this->fetchAll($query);
            
            if ($result && count($result) > 0) {
                return array_column($result, 'category');
            }
            
            // Fallback categories if no articles exist
            return [
                'Career Guidance',
                'University Selection', 
                'Study Tips',
                'Industry Insights',
                'Scholarship Information',
                'Career Planning'
            ];
        } catch (Exception $e) {
            // Return default categories on error
            return [
                'Career Guidance',
                'University Selection', 
                'Study Tips',
                'Industry Insights',
                'Scholarship Information',
                'Career Planning'
            ];
        }
    }

    /**
     * Get related articles - NEW METHOD
     */
    public function getRelatedArticles($articleId, $category, $limit = 3)
    {
        $query = "SELECT 
                    a.article_id,
                    a.title,
                    a.category,
                    a.created_at,
                    a.published_at,
                    CONCAT(u.first_name, ' ', u.last_name) as author_name,
                    SUBSTRING(a.content, 1, 150) as excerpt
                  FROM Articles a
                  JOIN Users u ON a.user_id = u.user_id
                  WHERE a.status = 'published' 
                  AND a.category = :category 
                  AND a.article_id != :article_id
                  ORDER BY a.published_at DESC
                  LIMIT :limit";
        
        return $this->fetchAll($query, [
            'category' => $category,
            'article_id' => $articleId,
            'limit' => $limit
        ]);
    }

    /**
     * Increment view count - NEW METHOD
     */
    public function incrementViewCount($articleId)
    {
        $query = "UPDATE Articles SET views = views + 1 WHERE article_id = :article_id";
        return $this->query($query, ['article_id' => $articleId]);
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

