<?php
// filepath: c:\xampp\htdocs\UniVerse\app\models\ForumCategory_model.php

class ForumCategory_model extends Model {
  
  protected $table = 'forum_categories';

  /**
   * Get all categories ordered by sort_order with icons
   */
  public function allOrdered() {
    try {
      $query = "SELECT * FROM {$this->table} ORDER BY sort_order ASC";
      $result = $this->fetchAll($query);
      
      if (is_array($result)) {
        // Add icons based on slug
        foreach ($result as &$category) {
          $category['icon'] = $this->getCategoryIcon($category['slug'] ?? '');
        }
        return $result;
      }
      
      return [];
      
    } catch (Exception $e) {
      error_log("Error in allOrdered(): " . $e->getMessage());
      return [];
    }
  }

  /**
   * Find category by ID
   */
  public function findById($id) {
    try {
      $query = "SELECT * FROM {$this->table} WHERE id = :id LIMIT 1";
      $result = $this->fetch($query, ['id' => $id]);
      
      if ($result) {
        $result['icon'] = $this->getCategoryIcon($result['slug'] ?? '');
        return $result;
      }
      
      return null;
    } catch (Exception $e) {
      error_log("Error in findById(): " . $e->getMessage());
      return null;
    }
  }

  /**
   * Find category by slug
   */
  public function findBySlug($slug) {
    try {
      $query = "SELECT * FROM {$this->table} WHERE slug = :slug LIMIT 1";
      $result = $this->fetch($query, ['slug' => $slug]);
      
      if ($result) {
        $result['icon'] = $this->getCategoryIcon($result['slug'] ?? '');
        return $result;
      }
      
      return null;
    } catch (Exception $e) {
      error_log("Error in findBySlug(): " . $e->getMessage());
      return null;
    }
  }

  /**
   * Get all categories (alias for allOrdered)
   */
  public function all() {
    return $this->allOrdered();
  }

  /**
   * Create new category
   */
  public function create($data) {
    try {
      $query = "INSERT INTO {$this->table} (slug, name, description, sort_order, created_at) 
                VALUES (:slug, :name, :description, :sort_order, NOW())";
      
      $this->query($query, [
        'slug' => $data['slug'],
        'name' => $data['name'],
        'description' => $data['description'] ?? null,
        'sort_order' => $data['sort_order'] ?? 0
      ]);
      
      return $this->db->lastInsertId();
    } catch (Exception $e) {
      error_log("Error in create(): " . $e->getMessage());
      return false;
    }
  }

  /**
   * Update category
   */
  public function updateC($id, $data) {
    try {
      $query = "UPDATE {$this->table} 
                SET slug = :slug, 
                    name = :name, 
                    description = :description, 
                    sort_order = :sort_order
                WHERE id = :id";
      
      return $this->query($query, [
        'id' => $id,
        'slug' => $data['slug'],
        'name' => $data['name'],
        'description' => $data['description'] ?? null,
        'sort_order' => $data['sort_order'] ?? 0
      ]);
    } catch (Exception $e) {
      error_log("Error in updateC(): " . $e->getMessage());
      return false;
    }
  }

  /**
   * Delete category
   */
  public function deleteC($id) {
    try {
      $query = "DELETE FROM {$this->table} WHERE id = :id";
      return $this->query($query, ['id' => $id]);
    } catch (Exception $e) {
      error_log("Error in deleteC(): " . $e->getMessage());
      return false;
    }
  }

  /**
   * Get FontAwesome icon class based on category slug
   */
  private function getCategoryIcon($slug) {
    $iconMap = [
      'university-selection' => 'fa-graduation-cap',
      'career-planning' => 'fa-briefcase',
      'study-tips-advice' => 'fa-book-open',
      'scholarships-financial-aid' => 'fa-dollar-sign',
      'subject-selection' => 'fa-list-check',
      'exam-preparation' => 'fa-file-pen',
      'international-studies' => 'fa-earth-americas',
      'student-life-wellbeing' => 'fa-heart',
      'degree-programs-courses' => 'fa-certificate',
      'technology-innovation' => 'fa-laptop-code',
      'arts-humanities' => 'fa-palette',
      'science-engineering' => 'fa-flask',
      'business-management' => 'fa-chart-line',
      'general-discussion' => 'fa-comments',
      'other' => 'fa-ellipsis'
    ];
    
    return $iconMap[$slug] ?? 'fa-folder';
  }
}