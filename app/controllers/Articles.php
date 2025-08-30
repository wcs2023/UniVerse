<?php

class Articles extends Controller
{
    public function index()
    {
        $data = [
            'title' => 'University Articles & Insights',
            'categories' => [
                'Research',
                'Education', 
                'Student Life',
                'Campus News',
                'Alumni',
                'Technology',
                'Arts & Culture'
            ],
            'articles' => $this->getLatestArticles()
        ];
        
        $this->view('articles/index', $data);
    }

    public function category($category = '')
    {
        $data = [
            'title' => 'Articles - ' . ucfirst($category),
            'category' => $category,
            'articles' => $this->getArticlesByCategory($category)
        ];
        
        $this->view('articles/category', $data);
    }

    public function article($id = '')
    {
        if(empty($id)) {
            redirect('articles');
        }

        $article = $this->getArticleById($id);
        
        if(!$article) {
            redirect('articles');
        }

        $data = [
            'article' => $article,
            'related_articles' => $this->getRelatedArticles($id, $article['category'])
        ];
        
        $this->view('articles/single', $data);
    }

    private function getLatestArticles()
    {
        // Mock data - in a real application, this would come from a database
        return [
            [
                'id' => 1,
                'title' => 'Breakthrough in AI-Powered Drug Discovery',
                'excerpt' => 'Researchers at our university have achieved a significant milestone in artificial intelligence applications for accelerating drug discovery processes.',
                'image' => BASE_URL . '/assets/images/articles/placeholder.svg',
                'category' => 'Research',
                'author' => 'Dr. Sarah Johnson',
                'date' => '2025-08-28',
                'views' => 156,
                'likes' => 23,
                'shares' => 12
            ],
            [
                'id' => 2,
                'title' => 'The Future of Online Learning: Hybrid Models',
                'excerpt' => 'An insightful look into hybrid learning models that combine traditional classroom teaching with digital tools and enhanced engagement for students.',
                'image' => BASE_URL . '/assets/images/articles/placeholder.svg',
                'category' => 'Education',
                'author' => 'Prof. Michael Chen',
                'date' => '2025-08-26',
                'views' => 234,
                'likes' => 45,
                'shares' => 18
            ],
            [
                'id' => 3,
                'title' => 'Navigating Campus Life: Tips for New Students',
                'excerpt' => 'Essential advice for incoming freshmen to make the most of their university experience, from academic success to campus involvement activities.',
                'image' => BASE_URL . '/assets/images/articles/placeholder.svg',
                'category' => 'Student Life',
                'author' => 'Emma Rodriguez',
                'date' => '2025-08-25',
                'views' => 189,
                'likes' => 31,
                'shares' => 15
            ],
            [
                'id' => 4,
                'title' => 'University Celebrates 150 Years of Excellence',
                'excerpt' => 'A milestone celebration reflecting on our institution\'s rich history, highlighting key contributions to research, education, and community development.',
                'image' => BASE_URL . '/assets/images/articles/placeholder.svg',
                'category' => 'Campus News',
                'author' => 'News Team',
                'date' => '2025-08-23',
                'views' => 312,
                'likes' => 67,
                'shares' => 28
            ],
            [
                'id' => 5,
                'title' => 'Alumni Spotlight: From Campus to CEO',
                'excerpt' => 'Meet our distinguished alumnus who transformed their university experience into entrepreneurial success, creating a multi-million-dollar innovation company.',
                'image' => BASE_URL . '/assets/images/articles/placeholder.svg',
                'category' => 'Alumni',
                'author' => 'Alumni Office',
                'date' => '2025-08-22',
                'views' => 198,
                'likes' => 42,
                'shares' => 19
            ],
            [
                'id' => 6,
                'title' => 'Innovations in Quantum Computing at University Lab',
                'excerpt' => 'Groundbreaking research in quantum computing technology brings our team to the forefront of computational science and practical applications.',
                'image' => BASE_URL . '/assets/images/articles/placeholder.svg',
                'category' => 'Technology',
                'author' => 'Dr. James Wilson',
                'date' => '2025-08-20',
                'views' => 275,
                'likes' => 58,
                'shares' => 24
            ],
            [
                'id' => 7,
                'title' => 'Revitalizing Campus Art: A New Exhibition',
                'excerpt' => 'Our campus art gallery unveils a stunning new exhibition showcasing contemporary works by renowned national and local emerging artists.',
                'image' => BASE_URL . '/assets/images/articles/placeholder.svg',
                'category' => 'Arts & Culture',
                'author' => 'Arts Department',
                'date' => '2025-08-18',
                'views' => 142,
                'likes' => 29,
                'shares' => 11
            ],
            [
                'id' => 8,
                'title' => 'Climate Change Adaptation Strategies',
                'excerpt' => 'New research initiatives focusing on sustainable solutions and climate adaptation reveal promising strategies for environmental challenges.',
                'image' => BASE_URL . '/assets/images/articles/placeholder.svg',
                'category' => 'Research',
                'author' => 'Dr. Lisa Green',
                'date' => '2025-08-16',
                'views' => 203,
                'likes' => 36,
                'shares' => 14
            ],
            [
                'id' => 9,
                'title' => 'Personalized Learning Paths: A New Approach',
                'excerpt' => 'Educational innovation meets technology as our faculty develops personalized learning frameworks responsive to student learning styles.',
                'image' => BASE_URL . '/assets/images/articles/placeholder.svg',
                'category' => 'Education',
                'author' => 'Dr. Amanda Foster',
                'date' => '2025-08-14',
                'views' => 167,
                'likes' => 33,
                'shares' => 16
            ]
        ];
    }

    private function getArticlesByCategory($category)
    {
        $allArticles = $this->getLatestArticles();
        return array_filter($allArticles, function($article) use ($category) {
            return strtolower($article['category']) === strtolower($category);
        });
    }

    private function getArticleById($id)
    {
        $articles = $this->getLatestArticles();
        foreach($articles as $article) {
            if($article['id'] == $id) {
                return $article;
            }
        }
        return false;
    }

    private function getRelatedArticles($currentId, $category, $limit = 3)
    {
        $articles = $this->getArticlesByCategory($category);
        return array_filter($articles, function($article) use ($currentId) {
            return $article['id'] != $currentId;
        });
    }
}
