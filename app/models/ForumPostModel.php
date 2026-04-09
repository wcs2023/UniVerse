<?php
class ForumPostModel extends Model {
  
  protected $table = 'forum_posts';

  public function listByThread($thread_id, $limit = 50, $offset = 0) {
    $sql = "SELECT p.*, u.username FROM {$this->table} p 
            JOIN users u ON u.id = p.user_id
            WHERE p.thread_id = :tid AND p.is_deleted = 0
            AND u.account_status = 'active'
            ORDER BY p.created_at ASC
            LIMIT $limit OFFSET $offset";
    return $this->query($sql, ['tid' => $thread_id]);
  }

  public function create($data) {
    $keys = array_keys($data);
    $sql = "INSERT INTO {$this->table} (".implode(',', $keys).") VALUES (:".implode(',:', $keys).")";
    $this->query($sql, $data);
    return true;
  }

  public function firstById($id) {
    $rows = $this->query("SELECT * FROM {$this->table} WHERE id = :id LIMIT 1", ['id' => $id]);
    return $rows ? $rows[0] : null;
  }

  public function updateBody($id, $body) {
    $this->query("UPDATE {$this->table} SET body = :b, updated_at = NOW() WHERE id = :id", ['b' => $body, 'id' => $id]);
  }

  public function softDelete($id) {
    $this->query("UPDATE {$this->table} SET is_deleted = 1, updated_at = NOW() WHERE id = :id", ['id' => $id]);
  }

  public function vote($post_id, $user_id, $value) {
    $exists = $this->query("SELECT id FROM forum_post_votes WHERE post_id=:p AND user_id=:u", ['p'=>$post_id,'u'=>$user_id]);
    if ($exists) {
      $this->query("UPDATE forum_post_votes SET value=:v WHERE id=:id", ['v'=>$value,'id'=>$exists[0]->id]);
    } else {
      $this->query("INSERT INTO forum_post_votes (post_id,user_id,value) VALUES (:p,:u,:v)", ['p'=>$post_id,'u'=>$user_id,'v'=>$value]);
    }
    $agg = $this->query("SELECT COALESCE(SUM(value),0) as score FROM forum_post_votes WHERE post_id=:p", ['p'=>$post_id]);
    $score = (int)($agg[0]->score ?? 0);
    $this->query("UPDATE {$this->table} SET upvotes = :s WHERE id=:p", ['s'=>$score,'p'=>$post_id]);
    return $score;
  }

  /**
   * Delete all posts by a specific user
   */
  public function deleteByUserId($userId) {
    try {
      $this->query("DELETE FROM {$this->table} WHERE user_id = :user_id", ['user_id' => $userId]);
      return true;
    } catch (Exception $e) {
      error_log("Error deleting forum posts by user: " . $e->getMessage());
      return false;
    }
  }

  /**
   * Get forum posts for admin moderation with optional filters.
   */
  public function getAllPostsForAdmin($statusFilter = 'all', $search = '') {
    try {
      $query = "SELECT
                  p.id,
                  p.thread_id,
                  p.user_id,
                  p.body,
                  p.upvotes,
                  p.is_deleted,
                  p.created_at,
                  p.updated_at,
                  t.title AS thread_title,
                  c.name AS category_name,
                  u.username,
                  u.email,
                  CONCAT(u.first_name, ' ', u.last_name) AS author_name
                FROM forum_posts p
                LEFT JOIN forum_threads t ON t.id = p.thread_id
                LEFT JOIN forum_categories c ON c.id = t.category_id
                LEFT JOIN users u ON u.user_id = p.user_id
                WHERE 1=1";

      $params = [];

      if ($statusFilter === 'hidden') {
        $query .= " AND p.is_deleted = 1";
      } elseif ($statusFilter === 'active') {
        $query .= " AND p.is_deleted = 0";
      }

      if (!empty($search)) {
        $query .= " AND (t.title LIKE :search OR p.body LIKE :search OR u.username LIKE :search OR u.email LIKE :search)";
        $params['search'] = '%' . $search . '%';
      }

      $query .= " ORDER BY p.created_at DESC";

      return $this->fetchAll($query, $params);
    } catch (Exception $e) {
      error_log('Error getting forum posts for admin: ' . $e->getMessage());
      return [];
    }
  }

  /**
   * Get single forum post details for admin.
   */
  public function getPostByIdForAdmin($postId) {
    try {
      $query = "SELECT
                  p.id,
                  p.thread_id,
                  p.user_id,
                  p.body,
                  p.upvotes,
                  p.is_deleted,
                  p.created_at,
                  p.updated_at,
                  t.title AS thread_title,
                  c.name AS category_name,
                  u.username,
                  u.email,
                  CONCAT(u.first_name, ' ', u.last_name) AS author_name
                FROM forum_posts p
                LEFT JOIN forum_threads t ON t.id = p.thread_id
                LEFT JOIN forum_categories c ON c.id = t.category_id
                LEFT JOIN users u ON u.user_id = p.user_id
                WHERE p.id = :id
                LIMIT 1";

      return $this->fetch($query, ['id' => $postId]);
    } catch (Exception $e) {
      error_log('Error getting forum post by id for admin: ' . $e->getMessage());
      return null;
    }
  }

  /**
   * Hide forum post from users.
   */
  public function hidePost($postId) {
    try {
      $this->query("UPDATE forum_posts SET is_deleted = 1, updated_at = NOW() WHERE id = :id", ['id' => $postId]);
      return true;
    } catch (Exception $e) {
      error_log('Error hiding forum post: ' . $e->getMessage());
      return false;
    }
  }

  /**
   * Unhide forum post to make it visible again.
   */
  public function unhidePost($postId) {
    try {
      $this->query("UPDATE forum_posts SET is_deleted = 0, updated_at = NOW() WHERE id = :id", ['id' => $postId]);
      return true;
    } catch (Exception $e) {
      error_log('Error unhiding forum post: ' . $e->getMessage());
      return false;
    }
  }

  /**
   * Permanently delete forum post.
   */
  public function deletePostPermanently($postId) {
    try {
      $this->query("DELETE FROM forum_posts WHERE id = :id", ['id' => $postId]);
      return true;
    } catch (Exception $e) {
      error_log('Error deleting forum post permanently: ' . $e->getMessage());
      return false;
    }
  }
}
