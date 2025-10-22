<?php
// filepath: c:\xampp\htdocs\UniVerse\app\controllers\Sdiscussion.php

class Sdiscussion extends Controller
{
  private function isOwnerOrAdmin($ownerId) {
    $u = $_SESSION['USER'] ?? null;
    if (!$u) return false;
    
    $userId = is_object($u) ? $u->id : ($u['id'] ?? 0);
    $isAdmin = is_object($u) ? ($u->is_admin ?? false) : ($u['is_admin'] ?? false);
    
    return ((int)$userId === (int)$ownerId) || !empty($isAdmin);
  }

  private function getCurrentUserId() {
    $user = $_SESSION['USER'] ?? null;
    if (is_object($user)) {
      return $user->id ?? null;
    } elseif (is_array($user)) {
      return $user['id'] ?? null;
    }
    return null;
  }

  public function index() {
    $categoryModel = $this->model('ForumCategory_model');
    
    // Get categories from database
    $categories = $categoryModel->allOrdered();
    
    $data = [
      'title' => 'Discussion Forums',
      'categories' => $categories,
      'recent_threads' => $this->getMockRecentThreads(),
      'stats' => [
        'total_threads' => 156,
        'total_posts' => 923,
        'total_members' => 312
      ]
    ];
    
    $this->view('actors/students/forum_home', $data);
  }

  public function category($categoryId = null) {
    if (!$categoryId) {
      header("Location: " . BASE_URL . '/sdiscussion');
      exit;
    }
    
    $categoryModel = $this->model('ForumCategory_model');
    $category = $categoryModel->findById($categoryId);
    
    if (!$category) {
      header("Location: " . BASE_URL . '/sdiscussion?error=category_not_found');
      exit;
    }
    
    $data = [
      'title' => 'Forum Category - ' . ($category['name'] ?? 'Unknown'),
      'category' => $category,
      'threads' => $this->getMockThreadsForCategory($categoryId)
    ];
    
    $this->view('actors/students/forum_category', $data);
  }

  public function thread($threadId = null) {
    if (!$threadId) {
      header("Location: " . BASE_URL . '/sdiscussion');
      exit;
    }
    
    $data = [
      'title' => 'Forum Thread',
      'thread' => $this->getMockThread($threadId),
      'posts' => $this->getMockPostsForThread($threadId)
    ];
    
    $this->view('actors/students/forum_thread', $data);
  }

  public function create() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      // Get form data
      $title = trim($_POST['title'] ?? '');
      $content = trim($_POST['content'] ?? '');
      $category_id = $_POST['category_id'] ?? '';
      
      // Validate
      $errors = [];
      
      if (strlen($title) < 5) {
        $errors[] = 'Title must be at least 5 characters long';
      }
      
      if (strlen($content) < 10) {
        $errors[] = 'Content must be at least 10 characters long';
      }
      
      if (empty($category_id)) {
        $errors[] = 'Please select a category';
      }
      
      if (!empty($errors)) {
        $categoryModel = $this->model('ForumCategory_model');
        
        $data = [
          'title' => 'Create New Thread',
          'categories' => $categoryModel->allOrdered(),
          'error' => implode(', ', $errors),
          'old' => $_POST
        ];
        
        $this->view('actors/students/forum_create_thread', $data);
        return;
      }
      
      // TODO: Save to database when ForumThread model is ready
      // For now, just redirect with success
      header("Location: " . BASE_URL . '/sdiscussion?success=thread_created');
      exit;
    }
    
    // GET request - show form
    $categoryModel = $this->model('ForumCategory_model');
    $categories = $categoryModel->allOrdered();
    
    $data = [
      'title' => 'Create New Thread',
      'categories' => $categories
    ];

    $this->view('actors/students/forum_create_thread', $data);
  }

  // Mock data methods
  private function getMockRecentThreads() {
    return [
      [
        'id' => 1,
        'title' => 'Which university is best for Computer Science?',
        'category' => 'University Selection',
        'author' => 'John Doe',
        'replies' => 12,
        'views' => 234,
        'last_activity' => '2 hours ago'
      ],
      [
        'id' => 2,
        'title' => 'Study tips for A/L Mathematics',
        'category' => 'Study Tips & Advice',
        'author' => 'Jane Smith',
        'replies' => 8,
        'views' => 156,
        'last_activity' => '5 hours ago'
      ]
    ];
  }

  private function getMockThreadsForCategory($categoryId) {
    return [
      [
        'id' => 1,
        'title' => 'Sample Thread for Category ' . $categoryId,
        'author' => 'Test User',
        'replies' => 5,
        'views' => 100,
        'created_at' => '2024-01-01'
      ]
    ];
  }

  private function getMockThread($threadId) {
    return [
      'id' => $threadId,
      'title' => 'Sample Thread Title',
      'content' => 'This is sample thread content',
      'author' => 'Test User',
      'created_at' => '2024-01-01',
      'views' => 100
    ];
  }

  private function getMockPostsForThread($threadId) {
    return [
      [
        'id' => 1,
        'content' => 'Sample post content',
        'author' => 'Test User',
        'created_at' => '2024-01-01'
      ]
    ];
  }
}