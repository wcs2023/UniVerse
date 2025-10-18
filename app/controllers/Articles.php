<?php

class Articles extends Controller {
    
    public function index() {
        // Create Article model instance
        $articleModel = new Article();
        
        // Get all published articles
        $articles = $articleModel->getAllArticles();
        
        // Get categories with counts
        $categories = $articleModel->getCategoriesWithCount();
        
        // Prepare data for view
        $data = [
            'articles' => $articles ?? [],
            'categories' => $categories ?? [],
            'title' => 'Articles & Insights'
        ];
        
        // Load the view
        $this->view('articles/index', $data);
    }
    
    public function category($categoryName = null) {
        if (!$categoryName) {
            header('Location: ' . BASE_URL . '/articles');
            exit();
        }
        
        $articleModel = new Article();
        
        // Get articles by category
        $articles = $articleModel->getArticlesByCategory($categoryName);
        
        // Prepare data for view
        $data = [
            'articles' => $articles ?? [],
            'category' => $categoryName,
            'title' => ucfirst(str_replace('-', ' ', $categoryName))
        ];
        
        // Load the category view
        $this->view('articles/category', $data);
    }
    
    public function article($articleId = null) {
        if (!$articleId) {
            header('Location: ' . BASE_URL . '/articles');
            exit();
        }
        
        $articleModel = new Article();
        
        // Get single article
        $article = $articleModel->getArticleById($articleId);
        
        if (!$article) {
            header('Location: ' . BASE_URL . '/articles');
            exit();
        }
        
        // Increment views
        $articleModel->incrementViews($articleId);
        
        // Prepare data for view
        $data = [
            'article' => $article,
            'title' => $article['title']
        ];
        
        // Load the article detail view
        $this->view('articles/single', $data);
    }
}
