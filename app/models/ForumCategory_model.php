<?php
class ForumCategoryModel extends Model {
  
  protected $table = 'forum_categories';

  public function allOrdered() {
    $sql = "SELECT * FROM {$this->table} ORDER BY sort_order, name";
    return $this->query($sql);
  }

  public function findBySlug($slug) {
    $rows = $this->query("SELECT * FROM {$this->table} WHERE slug = :slug LIMIT 1", ['slug' => $slug]);
    return $rows ? $rows[0] : null;
  }

  public function findById($id) {
    $rows = $this->query("SELECT * FROM {$this->table} WHERE id = :id LIMIT 1", ['id' => $id]);
    return $rows ? $rows[0] : null;
  }
}
