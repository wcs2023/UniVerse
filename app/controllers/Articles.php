<?php

class Articles extends Controller
{
    private $articleModel;

    public function __construct()
    {
        $this->articleModel = new Article();
    }

    public function index()
    {
        $data = [
            'title' => 'University Articles & Insights',
            'categories' => $this->articleModel->getCategories(),
            'articles' => $this->articleModel->getAllArticles()
        ];
        
        $this->view('articles/index', $data);
    }

    public function category($category = '')
    {
        if(empty($category)) {
            redirect('articles');
        }

        // Convert URL format back to normal (e.g., 'student-life' -> 'Student Life')
        $categoryName = ucwords(str_replace('-', ' ', $category));
        
        $data = [
            'title' => 'Articles - ' . $categoryName,
            'category' => $category,
            'articles' => $this->articleModel->getArticlesByCategory($categoryName)
        ];
        
        $this->view('articles/category', $data);
    }

    public function article($id = '')
    {
        if(empty($id)) {
            redirect('articles');
        }

        $article = $this->articleModel->getArticleById($id);
        
        if(!$article) {
            redirect('articles');
        }

        // Increment view count
        $this->articleModel->incrementViews($id);

        $data = [
            'article' => $article,
            'related_articles' => $this->articleModel->getRelatedArticles($id, $article['category'])
        ];
        
        $this->view('articles/single', $data);
    }
}
