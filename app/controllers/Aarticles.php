<?php

class Aarticles extends Controller
{
    private $articleModel;
    
    public function __construct()
    {
        // Start session if not already started
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        $this->articleModel = $this->model('ArticleModel');
    }
    
    /**
     * Display all articles for the logged-in alumni
     */
    public function index()
    {
        // Check authentication
        if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'alumni') {
            header('Location: ' . BASE_URL . '/login');
            exit;
        }
        
        $authorId = $_SESSION['user_id'];
        
        // Get draft articles
        $drafts = $this->articleModel->getArticlesByStatus($authorId, 'draft');
        
        // Get published articles
        $published = $this->articleModel->getArticlesByStatus($authorId, 'published');
        
        // Debug logging (remove after testing)
        error_log("DEBUG: User ID: " . $authorId);
        error_log("DEBUG: Drafts count: " . count($drafts));
        error_log("DEBUG: Published count: " . count($published));
        if (!empty($published)) {
            error_log("DEBUG: Published articles: " . print_r($published, true));
        }
        
        $data = [
            'drafts' => $drafts,
            'published' => $published
        ];
        
        $this->view('actors/alumni/Aarticleview', $data);
    }
    
    /**
     * Display create article form
     */
    public function create()
    {
        // Check authentication
        if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'alumni') {
            header('Location: ' . BASE_URL . '/login');
            exit;
        }
        
        $this->view('actors/alumni/Aarticlecreate');
    }
    
    /**
     * Display edit article form
     */
    public function edit($articleId = null)
    {
        // Check authentication
        if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'alumni') {
            header('Location: ' . BASE_URL . '/login');
            exit;
        }
        
        if (!$articleId) {
            header('Location: ' . BASE_URL . '/aarticles');
            exit;
        }
        
        // Get article details
        $article = $this->articleModel->getArticleById($articleId);
        
        if (!$article) {
            header('Location: ' . BASE_URL . '/aarticles');
            exit;
        }
        
        // Check if user owns this article
        if ($article['user_id'] != $_SESSION['user_id']) {
            header('Location: ' . BASE_URL . '/aarticles');
            exit;
        }
        
        $data = [
            'article' => $article
        ];
        
        $this->view('actors/alumni/Aarticleedit', $data);
    }
    
    /**
     * Save article (create or update)
     */
    public function save()
    {
        header('Content-Type: application/json');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Invalid request method']);
            exit;
        }
        
        // Check authentication
        if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'alumni') {
            echo json_encode(['success' => false, 'message' => 'Unauthorized. Please login as alumni.']);
            exit;
        }
        
        $input = json_decode(file_get_contents('php://input'), true);
        
        $articleId = $input['article_id'] ?? null;
        $title = $input['title'] ?? '';
        $content = $input['content'] ?? '';
        $status = $input['status'] ?? 'draft';
        $category = $input['category'] ?? '';
        $tags = $input['tags'] ?? '';
        
        // Get author ID from session
        $authorId = $_SESSION['user_id'];
        
        if (empty($title) || empty($content)) {
            echo json_encode(['success' => false, 'message' => 'Title and content are required']);
            exit;
        }
        
        if ($articleId) {
            // Verify ownership before updating
            $existingArticle = $this->articleModel->getArticleById($articleId);
            if (!$existingArticle || $existingArticle['user_id'] != $authorId) {
                echo json_encode(['success' => false, 'message' => 'You do not have permission to edit this article']);
                exit;
            }
            
            // Update existing article
            $result = $this->articleModel->updateArticle($articleId, $title, $content, $status, $category, $tags);
        } else {
            // Create new article
            $result = $this->articleModel->createArticle($authorId, $title, $content, $status, $category, $tags);
        }
        
        if ($result) {
            echo json_encode(['success' => true, 'article_id' => $articleId ?? $result]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to save article']);
        }
    }
    
    /**
     * Delete an article
     */
    public function delete($articleId = null)
    {
        header('Content-Type: application/json');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Invalid request method']);
            exit;
        }
        
        // Check authentication
        if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'alumni') {
            echo json_encode(['success' => false, 'message' => 'Unauthorized. Please login as alumni.']);
            exit;
        }
        
        if (!$articleId) {
            $input = json_decode(file_get_contents('php://input'), true);
            $articleId = $input['article_id'] ?? null;
        }
        
        if (!$articleId) {
            echo json_encode(['success' => false, 'message' => 'Missing article ID']);
            exit;
        }
        
        // Check if user owns this article
        $article = $this->articleModel->getArticleById($articleId);
        if (!$article || $article['user_id'] != $_SESSION['user_id']) {
            echo json_encode(['success' => false, 'message' => 'You do not have permission to delete this article']);
            exit;
        }
        
        $result = $this->articleModel->deleteArticle($articleId);
        
        echo json_encode(['success' => $result]);
    }
    
    /**
     * View single article (public view)
     */
    public function viewArticle($articleId = null)
    {
        if (!$articleId) {
            header('Location: ' . URLROOT . '/articles');
            exit;
        }
        
        // Get article details
        $article = $this->articleModel->getArticleById($articleId);
        
        if (!$article || $article->status !== 'published') {
            header('Location: ' . URLROOT . '/articles');
            exit;
        }
        
        // Increment view count
        $this->articleModel->incrementViews($articleId);
        
        $data = [
            'article' => $article
        ];
        
        $this->view('articles/single', $data);
    }
}
