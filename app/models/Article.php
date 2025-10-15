<?php

class Article extends Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getAllArticles($limit = null)
    {
        $sql = "SELECT a.*, CONCAT(u.first_name, ' ', u.last_name) as author_name 
                FROM articles a 
                LEFT JOIN users u ON a.author_id = u.user_id 
                WHERE a.status = 'published' 
                ORDER BY a.created_at DESC";
        
        if ($limit) {
            $sql .= " LIMIT " . (int)$limit;
        }
        
        return $this->fetchAll($sql);
    }

    public function getArticlesByCategory($category, $limit = null)
    {
        $sql = "SELECT a.*, CONCAT(u.first_name, ' ', u.last_name) as author_name 
                FROM articles a 
                LEFT JOIN users u ON a.author_id = u.user_id 
                WHERE a.category = ? AND a.status = 'published' 
                ORDER BY a.created_at DESC";
        
        if ($limit) {
            $sql .= " LIMIT " . (int)$limit;
        }
        
        return $this->fetchAll($sql, [$category]);
    }

    public function getArticleById($id)
    {
        $sql = "SELECT a.*, CONCAT(u.first_name, ' ', u.last_name) as author_name 
                FROM articles a 
                LEFT JOIN users u ON a.author_id = u.user_id 
                WHERE a.id = ? AND a.status = 'published'";
        
        return $this->fetch($sql, [$id]);
    }

    public function getCategories()
    {
        $sql = "SELECT DISTINCT category FROM articles WHERE status = 'published' ORDER BY category";
        $result = $this->fetchAll($sql);
        
        return array_column($result, 'category');
    }

    public function getRelatedArticles($articleId, $category, $limit = 3)
    {
        $sql = "SELECT a.*, CONCAT(u.first_name, ' ', u.last_name) as author_name 
                FROM articles a 
                LEFT JOIN users u ON a.author_id = u.user_id 
                WHERE a.category = ? AND a.id != ? AND a.status = 'published' 
                ORDER BY a.created_at DESC 
                LIMIT " . (int)$limit;
        
        return $this->fetchAll($sql, [$category, $articleId]);
    }

    public function incrementViews($id)
    {
        $sql = "UPDATE articles SET views = views + 1 WHERE id = ?";
        return $this->query($sql, [$id]);
    }

    public function createArticle($data)
    {
        $sql = "INSERT INTO articles (title, excerpt, content, category, author_id, image, status, created_at) 
                VALUES (?, ?, ?, ?, ?, ?, ?, NOW())";
        
        $params = [
            $data['title'],
            $data['excerpt'],
            $data['content'],
            $data['category'],
            $data['author_id'],
            $data['image'],
            $data['status'] ?? 'draft'
        ];
        
        $this->query($sql, $params);
        return $this->lastInsertId();
    }

    public function updateArticle($id, $data)
    {
        $sql = "UPDATE articles SET 
                title = ?, excerpt = ?, content = ?, category = ?, 
                image = ?, status = ?, updated_at = NOW() 
                WHERE id = ?";
        
        $params = [
            $data['title'],
            $data['excerpt'],
            $data['content'],
            $data['category'],
            $data['image'],
            $data['status'],
            $id
        ];
        
        return $this->query($sql, $params);
    }

    public function deleteArticle($id)
    {
        $sql = "DELETE FROM articles WHERE id = ?";
        return $this->query($sql, [$id]);
    }
}
