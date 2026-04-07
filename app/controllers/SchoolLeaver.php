<?php
class SchoolLeaver extends Controller
{
    public function __construct()
    {
        // Ensure BASE_URL is defined for the views
        if (!defined('BASE_URL')) {
            define('BASE_URL', 'http://localhost/UniVerse/public');
        }
    }

    /**
     * Display the school leaver's home page
     */
    public function index()
    {
        $data = [
            'title' => "School Leavers' Home",
            'hero' => [
                'title' => 'Personalized Degree Suggestions Just For You',
                'description' => 'Unlock your potential with tailored degree suggestions based on your Z-score and personal preferences. Make informed decisions that align with your career aspirations.',
                'cta_text' => 'Try Now',
                'cta_link' => BASE_URL . '/schoolleaver/degreeSuggestion'
            ],
            'features' => [
                'section_title' => 'Explore Articles and Forums to Enhance Your Career Journey',
                'section_description' => 'Drive into a wealth of articles and engaging forums tailored for you career growth. Connect with peers and industry experts to gain insights and share experiences.',
                'cards' => [
                    [
                        'title' => 'Career Articles',
                        'description' => 'Stay informed with the latest trends and advice in your fields',
                        'link' => BASE_URL . '/schoolleaver/articles'
                    ],
                    [
                        'title' => 'Discussion Forums',
                        'description' => 'Engage in discussion, ask questions and share insights with fellow students',
                        'link' => BASE_URL . '/schoolleaver/forums'
                    ]
                ]
            ]
        ];

        $this->view('actors/students/student_home', $data);
    }

    /**
     * Display degree suggestion page
     */
   

    /**
     * Process degree suggestion form
     */
    // public function processDegreeRequest()
    // {
    //     if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    //         redirect('schoolleaver/degreeSuggestion');
    //     }

    //     // Validate and sanitize input
    //     $zscore = filter_input(INPUT_POST, 'zscore', FILTER_VALIDATE_FLOAT);
    //     $interests = filter_input(INPUT_POST, 'interests', FILTER_SANITIZE_STRING);
    //     $preferred_location = filter_input(INPUT_POST, 'preferred_location', FILTER_SANITIZE_STRING);
    //     $budget_range = filter_input(INPUT_POST, 'budget_range', FILTER_SANITIZE_STRING);

    //     if (!$zscore) {
    //         $data['error'] = 'Please enter a valid Z-score';
    //         $this->view('actors/students/degree_suggestion', $data);
    //         return;
    //     }

    //     // Mock suggestions for now (replace with real logic later)
    //     $suggestions = [
    //         [
    //             'degree' => 'Computer Science',
    //             'university' => 'University of Colombo',
    //             'match_percentage' => 95,
    //             'requirements' => 'Z-score: 1.8+',
    //             'description' => 'Perfect match for your interests in technology'
    //         ],
    //         [
    //             'degree' => 'Information Technology',
    //             'university' => 'SLIIT',
    //             'match_percentage' => 88,
    //             'requirements' => 'Z-score: 1.5+',
    //             'description' => 'Great option for IT career prospects'
    //         ]
    //     ];

    //     $data = [
    //         'title' => 'Your Degree Suggestions',
    //         'suggestions' => $suggestions,
    //         'user_criteria' => [
    //             'zscore' => $zscore,
    //             'interests' => $interests,
    //             'location' => $preferred_location,
    //             'budget' => $budget_range
    //         ]
    //     ];

    //     $this->view('actors/students/degree_result', $data);
    // }

    /**
     * Display articles for school leavers - CONNECTED TO UNDERGRADUATE ARTICLES
     */
    public function articles()
    {
        try {
            // Load the Article model to get real articles from database
            $articleModel = $this->model('AarticleModel');
            
            // Get all published articles (same as undergraduate system)
            $articles = $articleModel->getAllPublishedArticles();
            
            // Get article categories for filtering
            $categories = $articleModel->getCategories() ?? [
                'Career Guidance',
                'University Selection', 
                'Study Tips',
                'Industry Insights',
                'Scholarship Information',
                'Career Planning'
            ];
            
        } catch (Exception $e) {
            // Fallback to mock data if Article model doesn't exist yet
            $articles = [
                [
                    'article_id' => 1,
                    'title' => 'How to Choose the Right University',
                    'author_name' => 'Career Advisor',
                    'created_at' => '2024-10-15',
                    'excerpt' => 'A comprehensive guide to selecting the university that fits your career goals.',
                    'category' => 'Career Guidance',
                    'image_url' => null,
                    'read_time' => '5 min read'
                ],
                [
                    'article_id' => 2,
                    'title' => 'Understanding Z-Score Requirements',
                    'author_name' => 'Education Expert',
                    'created_at' => '2024-10-10',
                    'excerpt' => 'Everything you need to know about Z-scores and university admissions.',
                    'category' => 'University Admissions',
                    'image_url' => null,
                    'read_time' => '7 min read'
                ],
                [
                    'article_id' => 3,
                    'title' => 'Top 10 Career Paths for 2024',
                    'author_name' => 'Industry Expert',
                    'created_at' => '2024-10-12',
                    'excerpt' => 'Explore the most promising career opportunities in the current job market.',
                    'category' => 'Career Planning',
                    'image_url' => null,
                    'read_time' => '6 min read'
                ]
            ];
            
            $categories = [
                'Career Guidance',
                'University Selection', 
                'Study Tips',
                'Industry Insights',
                'Scholarship Information',
                'Career Planning'
            ];
        }
        
        $data = [
            'title' => 'Career Articles',
            'articles' => $articles,
            'categories' => $categories,
            'user_type' => 'school_leaver' // To customize view for school leavers
        ];

        // Use the same articles view as undergraduates but with school leaver context
        $this->view('actors/students/articles', $data);
    }

    /**
     * Display single article - SHARED WITH UNDERGRADUATE SYSTEM
     */
    public function article($articleId = null)
    {
        if (!$articleId) {
            header('Location: ' . BASE_URL . '/schoolleaver/articles');
            exit;
        }

        try {
            // Load the Article model
            $articleModel = $this->model('Article');
            
            // Get the specific article
            $article = $articleModel->getArticleById($articleId);
            
            if (!$article) {
                // Article not found
                $data = [
                    'title' => 'Article Not Found',
                    'error' => 'The requested article could not be found.'
                ];
                $this->view('actors/students/article_not_found', $data);
                return;
            }
            
            // Get related articles
            $relatedArticles = $articleModel->getRelatedArticles($articleId, $article['category'], 3);
            
            // Increment view count
            $articleModel->incrementViewCount($articleId);
            
        } catch (Exception $e) {
            // Fallback for mock article
            $article = [
                'article_id' => $articleId,
                'title' => 'Sample Article Title',
                'content' => 'This is sample article content. In a real implementation, this would come from the database.',
                'author_name' => 'Career Advisor',
                'created_at' => '2024-10-15',
                'category' => 'Career Guidance',
                'image_url' => null,
                'view_count' => 150
            ];
            
            $relatedArticles = [];
        }

        $data = [
            'title' => $article['title'],
            'article' => $article,
            'related_articles' => $relatedArticles,
            'user_type' => 'school_leaver'
        ];

        $this->view('actors/students/article_detail', $data);
    }

    /**
     * Filter articles by category - SHARED FUNCTIONALITY
     */
    public function articlesByCategory($category = null)
    {
        if (!$category) {
            header('Location: ' . BASE_URL . '/schoolleaver/articles');
            exit;
        }

        try {
            $articleModel = $this->model('Article');
            
            // Get articles by category
            $articles = $articleModel->getArticlesByCategory($category);
            $categories = $articleModel->getCategories();
            
        } catch (Exception $e) {
            // Fallback to filtered mock data
            $mockArticles = [
                [
                    'article_id' => 1,
                    'title' => 'How to Choose the Right University',
                    'author_name' => 'Career Advisor',
                    'created_at' => '2024-10-15',
                    'excerpt' => 'A comprehensive guide to selecting the university that fits your career goals.',
                    'category' => 'Career Guidance',
                    'image_url' => null,
                    'read_time' => '5 min read'
                ]
            ];
            
            $articles = array_filter($mockArticles, function($article) use ($category) {
                return strtolower($article['category']) === strtolower(str_replace('-', ' ', $category));
            });
            
            $categories = ['Career Guidance', 'University Selection', 'Study Tips'];
        }

        $data = [
            'title' => ucfirst(str_replace('-', ' ', $category)) . ' Articles',
            'articles' => $articles,
            'categories' => $categories,
            'active_category' => $category,
            'user_type' => 'school_leaver'
        ];

        $this->view('actors/students/articles', $data);
    }

    /**
     * Search articles - SHARED SEARCH FUNCTIONALITY
     */
    public function searchArticles()
    {
        $query = $_GET['q'] ?? '';
        
        if (empty($query)) {
            header('Location: ' . BASE_URL . '/schoolleaver/articles');
            exit;
        }

        try {
            $articleModel = $this->model('Article');
            
            // Search articles by title and content
            $articles = $articleModel->searchArticles($query);
            $categories = $articleModel->getCategories();
            
        } catch (Exception $e) {
            // Fallback search in mock data
            $articles = [];
            $categories = ['Career Guidance', 'University Selection', 'Study Tips'];
        }

        $data = [
            'title' => 'Search Results for "' . htmlspecialchars($query) . '"',
            'articles' => $articles,
            'categories' => $categories,
            'search_query' => $query,
            'user_type' => 'school_leaver'
        ];

        $this->view('actors/students/articles', $data);
    }

    /**
     * Display discussion forums - FIXED
     */
    // public function forums()
    // {
    //     // Mock forum data with categories and recent discussions
    //     $categories = [
    //         [
    //             'id' => 1,
    //             'name' => 'University Selection',
    //             'description' => 'Discuss different universities, programs, and admission requirements',
    //             'thread_count' => 24,
    //             'post_count' => 156,
    //             'last_activity' => '2024-10-21 10:30:00',
    //             'icon' => 'fa-graduation-cap'
    //         ],
    //         [
    //             'id' => 2,
    //             'name' => 'Career Planning',
    //             'description' => 'Share career advice, job market insights, and professional development tips',
    //             'thread_count' => 18,
    //             'post_count' => 89,
    //             'last_activity' => '2024-10-21 09:15:00',
    //             'icon' => 'fa-briefcase'
    //         ],
    //         [
    //             'id' => 3,
    //             'name' => 'Study Tips & Advice',
    //             'description' => 'Exchange study methods, exam preparation strategies, and academic support',
    //             'thread_count' => 32,
    //             'post_count' => 201,
    //             'last_activity' => '2024-10-21 11:45:00',
    //             'icon' => 'fa-book-open'
    //         ],
    //         [
    //             'id' => 4,
    //             'name' => 'Scholarships & Financial Aid',
    //             'description' => 'Information about scholarships, grants, and funding opportunities',
    //             'thread_count' => 15,
    //             'post_count' => 67,
    //             'last_activity' => '2024-10-20 16:22:00',
    //             'icon' => 'fa-dollar-sign'
    //         ],
    //         [
    //             'id' => 5,
    //             'name' => 'General Discussion',
    //             'description' => 'Open discussion about student life, experiences, and other topics',
    //             'thread_count' => 41,
    //             'post_count' => 298,
    //             'last_activity' => '2024-10-21 12:10:00',
    //             'icon' => 'fa-comments'
    //         ]
    //     ];

    //     $recent_threads = [
    //         [
    //             'id' => 1,
    //             'title' => 'Best Universities for Engineering in 2024',
    //             'author' => 'TechStudent2024',
    //             'category' => 'University Selection',
    //             'replies' => 15,
    //             'views' => 234,
    //             'last_post_time' => '2024-10-21 10:30:00',
    //             'last_post_author' => 'EngineeringGuru'
    //         ],
    //         [
    //             'id' => 2,
    //             'title' => 'How to improve Z-score for university admission?',
    //             'author' => 'FutureDoc',
    //             'category' => 'Study Tips & Advice',
    //             'replies' => 22,
    //             'views' => 187,
    //             'last_post_time' => '2024-10-21 09:45:00',
    //             'last_post_author' => 'StudyExpert'
    //         ],
    //         [
    //             'id' => 3,
    //             'title' => 'Scholarship opportunities for Computer Science students',
    //             'author' => 'CodeMaster',
    //             'category' => 'Scholarships & Financial Aid',
    //             'replies' => 8,
    //             'views' => 156,
    //             'last_post_time' => '2024-10-21 08:20:00',
    //             'last_post_author' => 'ScholarshipHunter'
    //         ],
    //         [
    //             'id' => 4,
    //             'title' => 'Career prospects in Data Science',
    //             'author' => 'DataAnalyst2024',
    //             'category' => 'Career Planning',
    //             'replies' => 12,
    //             'views' => 198,
    //             'last_post_time' => '2024-10-20 17:30:00',
    //             'last_post_author' => 'DataSciencePro'
    //         ]
    //     ];
        
    //     $data = [
    //         'title' => 'Discussion Forums',
    //         'categories' => $categories,
    //         'recent_threads' => $recent_threads,
    //         'user_type' => 'school_leaver',
    //         'stats' => [
    //             'total_threads' => 130,
    //             'total_posts' => 811,
    //             'total_members' => 245,
    //             'newest_member' => 'NewStudent2024'
    //         ]
    //     ];

    //     // Use the correct view file name
    //     $this->view('actors/students/forum_home', $data);
    // }

    /**
     * Display forum category - NEW METHOD
     */
    // public function forumCategory($categoryId = null)
    // {
    //     if (!$categoryId) {
    //         header('Location: ' . BASE_URL . '/schoolleaver/forums');
    //         exit;
    //     }

    //     // Mock category data
    //     $category = [
    //         'id' => $categoryId,
    //         'name' => 'University Selection',
    //         'description' => 'Discuss different universities, programs, and admission requirements'
    //     ];

    //     $threads = [
    //         [
    //             'id' => 1,
    //             'title' => 'Best Universities for Engineering',
    //             'author' => 'TechStudent2024',
    //             'replies' => 15,
    //             'views' => 234,
    //             'created_at' => '2024-10-15 14:30:00',
    //             'last_post_time' => '2024-10-21 10:30:00',
    //             'last_post_author' => 'EngineeringGuru',
    //             'is_pinned' => true
    //         ],
    //         [
    //             'id' => 2,
    //             'title' => 'University of Colombo vs SLIIT - Which is better?',
    //             'author' => 'ConfusedStudent',
    //             'replies' => 28,
    //             'views' => 456,
    //             'created_at' => '2024-10-12 09:15:00',
    //             'last_post_time' => '2024-10-21 08:45:00',
    //             'last_post_author' => 'UniExpert',
    //             'is_pinned' => false
    //         ]
    //     ];

    //     $data = [
    //         'title' => $category['name'] . ' - Forum Category',
    //         'category' => $category,
    //         'threads' => $threads,
    //         'user_type' => 'school_leaver'
    //     ];

    //     $this->view('actors/students/forum_category', $data);
    // }

    /**
     * Display forum thread - NEW METHOD
     */
    // public function forumThread($threadId = null)
    // {
    //     if (!$threadId) {
    //         header('Location: ' . BASE_URL . '/schoolleaver/forums');
    //         exit;
    //     }

    //     // Mock thread data
    //     $thread = [
    //         'id' => $threadId,
    //         'title' => 'Best Universities for Engineering',
    //         'author' => 'TechStudent2024',
    //         'created_at' => '2024-10-15 14:30:00',
    //         'views' => 234,
    //         'category' => 'University Selection'
    //     ];

    //     $posts = [
    //         [
    //             'id' => 1,
    //             'content' => 'Hi everyone! I\'m looking for advice on the best universities for engineering in Sri Lanka. Can anyone share their experiences?',
    //             'author' => 'TechStudent2024',
    //             'created_at' => '2024-10-15 14:30:00',
    //             'is_original_post' => true
    //         ],
    //         [
    //             'id' => 2,
    //             'content' => 'I would highly recommend University of Moratuwa for engineering. Great faculty and industry connections!',
    //             'author' => 'EngineeringGuru',
    //             'created_at' => '2024-10-15 16:45:00',
    //             'is_original_post' => false
    //         ]
    //     ];

    //     $data = [
    //         'title' => $thread['title'],
    //         'thread' => $thread,
    //         'posts' => $posts,
    //         'user_type' => 'school_leaver'
    //     ];

    //     $this->view('actors/students/forum_thread', $data);
    // }

    /**
     * Display student profile/dashboard - SIMPLIFIED VERSION
     */
   

    /**
     * Handle contact/support requests
     */
    public function contact()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
            $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
            $message = filter_input(INPUT_POST, 'message', FILTER_SANITIZE_STRING);

            if ($name && $email && $message) {
                // For now, just show success (replace with actual email sending later)
                $data['success'] = 'Your message has been sent successfully!';
            } else {
                $data['error'] = 'Please fill in all required fields.';
            }
        }

        $data['title'] = 'Contact Us';
        $this->view('actors/students/contact', $data);
    }

    /**
     * About page for school leavers
     */
    public function about()
    {
        $data = [
            'title' => 'About UniVerse for School Leavers',
            'features' => [
                'Personalized degree recommendations',
                'University comparison tools',
                'Career guidance articles',
                'Peer discussion forums',
                'Application assistance'
            ]
        ];

        $this->view('actors/students/about', $data);
    }
}