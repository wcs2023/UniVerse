<?php
// filepath: c:\xampp\htdocs\UniVerse\app\controllers\Udiscussion.php

class Udiscussion extends Controller
{
  private function isOwnerOrAdmin($ownerId) {
    $u = $_SESSION['USER'] ?? null;
    if (!$u) return false;
    
    // Handle both object and array formats
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
    // Get forum models
    $categoryModel = $this->model('Forum_category_model');
    
    // Get categories using existing method
    $categories = [];
    try {
      $categories = $categoryModel->allOrdered() ?? [];
    } catch (Exception $e) {
      // Fallback to mock data
      $categories = $this->getMockCategories();
    }
    
    // Fetch data for the forum home page
    $data = [
      'title' => 'Discussion Forums',
      'categories' => $categories,
      'recent_threads' => $this->getMockRecentThreads(), // Use mock data for now
      'stats' => [
        'total_threads' => 45,
        'total_posts' => 234,
        'total_members' => 89
      ]
    ];
    
    // Load the view
    $this->view('actors/undergraduate/Uforum_home', $data);
  }

  public function category($categoryId = null) {
    if (!$categoryId) {
      header("Location: " . BASE_URL . '/udiscussion');
      exit;
    }
    
    $categoryModel = $this->model('Forum_category_model');
    
    $data = [
      'title' => 'Forum Category',
      'category' => $categoryModel->findById($categoryId),
      'threads' => $this->getMockThreadsForCategory($categoryId) // Use mock data for now
    ];
    
    $this->view('actors/undergraduate/Uforum_category', $data);
  }

  public function thread($threadId = null) {
    if (!$threadId) {
      header("Location: " . BASE_URL . '/udiscussion');
      exit;
    }
    
    $data = [
      'title' => 'Forum Thread',
      'thread' => $this->getMockThread($threadId),
      'posts' => $this->getMockPostsForThread($threadId)
    ];
    
    $this->view('actors/undergraduate/Uforum_thread', $data);
  }

  public function create() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      // For now, just redirect back with success message
      header("Location: " . BASE_URL . '/udiscussion?success=thread_created');
      exit;
    }
    
    $categoryModel = $this->model('ForumCategory_model');
    $data = [
      'title' => 'Create New Thread',
      'categories' => $categoryModel->allOrdered() ?? []
    ];
    
    $this->view('actors/undergraduate/Uforum_create_thread', $data);
  }

  public function reply($threadId = null) {
    if (!$threadId || $_SERVER['REQUEST_METHOD'] !== 'POST') {
      header("Location: " . BASE_URL . '/udiscussion');
      exit;
    }
    
    // For now, just redirect back with success message
    header("Location: " . BASE_URL . '/udiscussion/thread/' . $threadId . '?success=reply_added');
    exit;
  }

  public function vote() {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
      http_response_code(405);
      echo json_encode(['error' => 'Method not allowed']);
      exit;
    }
    
    $result = ['success' => true, 'message' => 'Vote recorded'];
    
    header('Content-Type: application/json');
    echo json_encode($result);
    exit;
  }

  public function edit_thread($threadId = null) {
    if (!$threadId) {
      header("Location: " . BASE_URL . '/udiscussion');
      exit;
    }
    
    $data = [
      'title' => 'Edit Thread',
      'thread' => $this->getMockThread($threadId)
    ];
    
    $this->view('actors/undergraduate/Uforum_edit_thread', $data);
  }

  public function edit_post($postId = null) {
    if (!$postId) {
      header("Location: " . BASE_URL . '/udiscussion');
      exit;
    }
    
    $data = [
      'title' => 'Edit Post',
      'post' => $this->getMockPost($postId)
    ];
    
    $this->view('actors/undergraduate/Uforum_edit_post', $data);
  }

  public function delete_thread($threadId = null) {
    if (!$threadId) {
      header("Location: " . BASE_URL . '/udiscussion');
      exit;
    }
    
    header("Location: " . BASE_URL . '/udiscussion?success=thread_deleted');
    exit;
  }

  public function delete_post($postId = null) {
    if (!$postId) {
      header("Location: " . BASE_URL . '/udiscussion');
      exit;
    }
    
    header("Location: " . BASE_URL . '/udiscussion?success=post_deleted');
    exit;
  }

  public function search() {
    $searchTerm = $_GET['q'] ?? '';
    
    if (empty($searchTerm)) {
      header("Location: " . BASE_URL . '/udiscussion');
      exit;
    }
    
    $data = [
      'title' => 'Search Results',
      'search_term' => $searchTerm,
      'thread_results' => $this->getMockSearchResults($searchTerm),
      'post_results' => []
    ];
    
    $this->view('actors/undergraduate/Uforum_search', $data);
  }

  // Mock data methods for fallback
  private function getMockCategories() {
    return [
      [
        'id' => 1,
        'name' => 'University Selection',
        'description' => 'Discuss different universities, programs, and admission requirements',
        'thread_count' => 24,
        'post_count' => 156,
        'last_activity' => date('Y-m-d H:i:s', strtotime('-1 hour')),
        'slug' => 'university-selection'
      ],
      [
        'id' => 2,
        'name' => 'Career Planning',
        'description' => 'Share career advice, job market insights, and professional development tips',
        'thread_count' => 18,
        'post_count' => 89,
        'last_activity' => date('Y-m-d H:i:s', strtotime('-2 hours')),
        'slug' => 'career-planning'
      ],
      [
        'id' => 3,
        'name' => 'Study Tips & Advice',
        'description' => 'Exchange study methods, exam preparation strategies, and academic support',
        'thread_count' => 32,
        'post_count' => 201,
        'last_activity' => date('Y-m-d H:i:s', strtotime('-30 minutes')),
        'slug' => 'study-tips-advice'
      ]
    ];
  }

  private function getMockRecentThreads() {
    return [
      [
        'id' => 1,
        'title' => 'Best Engineering Universities in Sri Lanka?',
        'content' => 'Looking for recommendations for engineering programs...',
        'author_name' => 'Saman Perera',
        'category_name' => 'University Selection',
        'replies' => 12,
        'views' => 156,
        'created_at' => date('Y-m-d H:i:s', strtotime('-2 hours')),
        'last_activity' => date('Y-m-d H:i:s', strtotime('-30 minutes'))
      ],
      [
        'id' => 2,
        'title' => 'How to prepare for aptitude tests?',
        'content' => 'Any tips for university entrance exams?',
        'author_name' => 'Nimali Silva',
        'category_name' => 'Study Tips & Advice',
        'replies' => 8,
        'views' => 89,
        'created_at' => date('Y-m-d H:i:s', strtotime('-1 day')),
        'last_activity' => date('Y-m-d H:i:s', strtotime('-3 hours'))
      ]
    ];
  }

  private function getMockThreadsForCategory($categoryId) {
    return [
      [
        'id' => 1,
        'title' => 'Sample Thread for Category ' . $categoryId,
        'content' => 'This is a sample thread content...',
        'author_name' => 'John Doe',
        'replies' => 5,
        'views' => 45,
        'created_at' => date('Y-m-d H:i:s', strtotime('-1 hour')),
        'last_activity' => date('Y-m-d H:i:s', strtotime('-15 minutes'))
      ]
    ];
  }

  private function getMockThread($threadId) {
    return [
      'id' => $threadId,
      'title' => 'Sample Thread Title',
      'content' => 'This is the main thread content. Lorem ipsum dolor sit amet...',
      'author_name' => 'Thread Author',
      'category_name' => 'General Discussion',
      'views' => 123,
      'created_at' => date('Y-m-d H:i:s', strtotime('-2 hours')),
      'user_id' => 1
    ];
  }

  private function getMockPostsForThread($threadId) {
    return [
      [
        'id' => 1,
        'content' => 'Great question! I think...',
        'author_name' => 'Reply Author 1',
        'created_at' => date('Y-m-d H:i:s', strtotime('-1 hour')),
        'user_id' => 2
      ],
      [
        'id' => 2,
        'content' => 'I agree with the previous post...',
        'author_name' => 'Reply Author 2',
        'created_at' => date('Y-m-d H:i:s', strtotime('-30 minutes')),
        'user_id' => 3
      ]
    ];
  }

  private function getMockPost($postId) {
    return [
      'id' => $postId,
      'content' => 'This is a sample post content...',
      'author_name' => 'Post Author',
      'thread_id' => 1,
      'user_id' => 1,
      'created_at' => date('Y-m-d H:i:s', strtotime('-1 hour'))
    ];
  }

  private function getMockSearchResults($searchTerm) {
    return [
      [
        'id' => 1,
        'title' => 'Search Result for: ' . $searchTerm,
        'content' => 'This thread matches your search...',
        'author_name' => 'Search Author',
        'category_name' => 'General',
        'created_at' => date('Y-m-d H:i:s', strtotime('-1 day'))
      ]
    ];
  }
}