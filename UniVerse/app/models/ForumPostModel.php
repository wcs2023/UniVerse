<?php
class ForumPostModel extends Model {
  
  protected $table = 'forum_posts';

  public function listByThread($thread_id, $limit = 50, $offset = 0) {
    $sql = "SELECT p.*, u.username FROM {$this->table} p 
            JOIN users u ON u.id = p.user_id
            WHERE p.thread_id = :tid AND p.is_deleted = 0
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
}
