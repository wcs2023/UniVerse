<?php

class Uarticles extends Controller
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
     * Display all published articles (for undergraduates/public viewing)
     */
    public function index()
    {
        // Get all published articles
        $articles = $this->articleModel->getAllPublishedArticles(10, 0);
        
        // Generate excerpt for each article if not exists
        foreach ($articles as &$article) {
            if (!isset($article['excerpt']) || empty($article['excerpt'])) {
                $article['excerpt'] = $this->generateExcerpt($article['content']);
            }
        }
        
        // Get unique categories with counts
        $categories = $this->getCategories($articles);
        
        $data = [
            'title' => 'Articles',
            'articles' => $articles,
            'categories' => $categories
        ];
        
        $this->view('articles/index', $data);
    }
    
    /**
     * Display articles by category
     */
    public function category($categoryName = null)
    {
        if (!$categoryName) {
            header('Location: ' . BASE_URL . '/uarticles');
            exit;
        }
        
        // Get all published articles
        $allArticles = $this->articleModel->getAllPublishedArticles(100, 0);
        
        // Filter by category
        $articles = array_filter($allArticles, function($article) use ($categoryName) {
            return strtolower($article['category']) === strtolower($categoryName);
        });
        
        // Generate excerpt for each article if not exists
        foreach ($articles as &$article) {
            if (!isset($article['excerpt']) || empty($article['excerpt'])) {
                $article['excerpt'] = $this->generateExcerpt($article['content']);
            }
        }
        
        // Get all categories with counts
        $categories = $this->getCategories($allArticles);
        
        $data = [
            'title' => ucfirst(str_replace('-', ' ', $categoryName)) . ' Articles',
            'category' => $categoryName,
            'articles' => array_values($articles), // Re-index array
            'categories' => $categories
        ];
        
        $this->view('articles/category', $data);
    }
    
    /**
     * View single article (public view)
     */
    public function viewDetails($articleId = null)
    {
        if (!$articleId) {
            header('Location: ' . BASE_URL . '/uarticles');
            exit;
        }
        
        // Get article details
        $article = $this->articleModel->getArticleById($articleId);
        
        if (!$article || $article['status'] !== 'published') {
            header('Location: ' . BASE_URL . '/uarticles');
            exit;
        }
        
        // Increment view count
        $this->articleModel->incrementViews($articleId);
        
        $data = [
            'title' => $article['title'],
            'article' => $article
        ];
        
        $this->view('articles/single', $data);
    }
    
    /**
     * Search articles
     */
    public function search()
    {
        $keyword = $_GET['q'] ?? '';
        
        if (empty($keyword)) {
            header('Location: ' . BASE_URL . '/uarticles');
            exit;
        }
        
        $articles = $this->articleModel->searchArticles($keyword, 50);
        
        // Generate excerpt for each article if not exists
        foreach ($articles as &$article) {
            if (!isset($article['excerpt']) || empty($article['excerpt'])) {
                $article['excerpt'] = $this->generateExcerpt($article['content']);
            }
        }
        
        $data = [
            'title' => 'Search Results',
            'keyword' => $keyword,
            'articles' => $articles,
            'categories' => []
        ];
        
        $this->view('articles/index', $data);
    }
    
    /**
     * Helper: Generate excerpt from content
     */
    private function generateExcerpt($content, $length = 150)
    {
        // Strip HTML tags
        $text = strip_tags($content);
        
        // Limit length
        if (strlen($text) > $length) {
            $text = substr($text, 0, $length);
            $text = substr($text, 0, strrpos($text, ' ')) . '...';
        }
        
        return $text;
    }
    
    /**
     * Helper: Get categories with counts
     */
    private function getCategories($articles)
    {
        $categories = [];
        
        foreach ($articles as $article) {
            $category = $article['category'];
            if (!empty($category)) {
                if (!isset($categories[$category])) {
                    $categories[$category] = [
                        'category' => $category,
                        'count' => 0
                    ];
                }
                $categories[$category]['count']++;
            }
        }
        
        return array_values($categories);
    }
}
