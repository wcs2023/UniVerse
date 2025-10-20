<?php
class ForumThreadModel extends Model {
  
  protected $table = 'forum_threads';

  public function listByCategory($category_id, $limit = 20, $offset = 0) {
    $sql = "SELECT t.*, u.username FROM {$this->table} t 
            JOIN users u ON u.id = t.user_id
            WHERE t.category_id = :cid
            ORDER BY t.is_pinned DESC, t.last_post_at DESC
            LIMIT $limit OFFSET $offset";
    return $this->query($sql, ['cid' => $category_id]);
  }

  public function getTrending($limit = 6) {
    $sql = "SELECT t.id,t.title,t.views,t.last_post_at,t.is_pinned,u.username
            FROM {$this->table} t
            JOIN users u ON u.id = t.user_id
            ORDER BY t.is_pinned DESC, (t.views*0.6) + TIMESTAMPDIFF(HOUR,t.last_post_at,NOW())*-1 DESC
            LIMIT $limit";
    return $this->query($sql);
  }

  public function getRecent($limit = 9) {
    $sql = "SELECT t.id,t.title,t.last_post_at,u.username
            FROM {$this->table} t
            JOIN users u ON u.id = t.user_id
            ORDER BY t.last_post_at DESC
            LIMIT $limit";
    return $this->query($sql);
  }

  public function findWithAuthor($id) {
    $sql = "SELECT t.*, u.username FROM {$this->table} t 
            JOIN users u ON u.id = t.user_id WHERE t.id = :id LIMIT 1";
    $rows = $this->query($sql, ['id' => $id]);
    return $rows ? $rows[0] : null;
  }

  public function create($data) {
    $keys = array_keys($data);
    $sql = "INSERT INTO {$this->table} (".implode(',', $keys).") VALUES (:".implode(',:', $keys).")";
    $this->query($sql, $data);
    $row = $this->query("SELECT LAST_INSERT_ID() AS id");
    return $row ? (int)$row[0]->id : null;
  }

  public function incrementViews($id) {
    $this->query("UPDATE {$this->table} SET views = views + 1 WHERE id = :id", ['id' => $id]);
  }

  public function bumpLastPostAt($id) {
    $this->query("UPDATE {$this->table} SET last_post_at = NOW() WHERE id = :id", ['id' => $id]);
  }

  public function updateThread($id, $title, $body) {
    $this->query("UPDATE {$this->table} SET title = :t, body = :b, updated_at = NOW() WHERE id = :id",
      ['t' => $title, 'b' => $body, 'id' => $id]);
  }

  public function delete($id) {
    $this->query("DELETE FROM {$this->table} WHERE id = :id", ['id' => $id]);
  }
}
