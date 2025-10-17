<?php

class Articles extends Controller
{
    private $articleModel;
    
    public function __construct()
    {
        // Start session if not already started
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        $this->articleModel = $this->model('Article');
    }
    
    /**
     * Display all articles for the logged-in alumni
     */
    public function index()
    {
        // TODO: Uncomment when authentication is ready
        /*
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'alumni') {
            header('Location: ' . URLROOT . '/users/login');
            exit;
        }
        $authorId = $_SESSION['user_id'];
        */
        
        // TEMPORARY: Use first alumni for testing
        $authorId = 1;
        
        // Get draft articles
        $drafts = $this->articleModel->getArticlesByStatus($authorId, 'draft');
        
        // Get published articles
        $published = $this->articleModel->getArticlesByStatus($authorId, 'published');
        
        $data = [
            'drafts' => $drafts,
            'published' => $published
        ];
        
        $this->view('actors/alumini/Aarticleview', $data);
    }
    
    /**
     * Display create article form
     */
    public function create()
    {
        // TODO: Add authentication check
        
        $this->view('actors/alumini/Aarticlecreate');
    }
    
    /**
     * Display edit article form
     */
    public function edit($articleId = null)
    {
        if (!$articleId) {
            header('Location: ' . URLROOT . '/articles');
            exit;
        }
        
        // Get article details
        $article = $this->articleModel->getArticleById($articleId);
        
        if (!$article) {
            header('Location: ' . URLROOT . '/articles');
            exit;
        }
        
        // TODO: Check if user owns this article
        
        $data = [
            'article' => $article
        ];
        
        $this->view('actors/alumini/Aarticleedit', $data);
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
        
        $input = json_decode(file_get_contents('php://input'), true);
        
        $articleId = $input['article_id'] ?? null;
        $title = $input['title'] ?? '';
        $content = $input['content'] ?? '';
        $status = $input['status'] ?? 'draft';
        $category = $input['category'] ?? '';
        $tags = $input['tags'] ?? '';
        
        // TODO: Get from session
        $authorId = 1;
        
        if (empty($title) || empty($content)) {
            echo json_encode(['success' => false, 'message' => 'Title and content are required']);
            exit;
        }
        
        if ($articleId) {
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
        
        if (!$articleId) {
            $input = json_decode(file_get_contents('php://input'), true);
            $articleId = $input['article_id'] ?? null;
        }
        
        if (!$articleId) {
            echo json_encode(['success' => false, 'message' => 'Missing article ID']);
            exit;
        }
        
        // TODO: Check if user owns this article
        
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
