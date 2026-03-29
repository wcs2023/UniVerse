<?php

class Discussion_Forum extends Controller
{

    protected $thread_model;
    protected $category_model;
    protected $post_model;
    public function __construct()
    {
        $this->thread_model = $this->model('Forum_thread_model');
        $this->category_model = $this->model('Forum_category_model');
        $this->post_model = $this->model('Forum_post_model');
    }


    public function getCurrentUser()
    {
        return $_SESSION['USER'] ?? NULL;
    }

    public function getCurrentUserId()
    {
        $user = $this->getCurrentUser();
        return $user['user_id'] ?? null;
    }

    private function isAdmin($admin_id)
    {

        $user = $this->getCurrentUser();
        if (!$user) {
            return false;
        }

        $userId = $this->getCurrentUserId();
        $isAdmin = is_object($user) ? ($user->is_admin ?? false) : ($user['is_admin'] ?? false);

        return ((int)$userId === (int)$admin_id) || !empty($isAdmin);
    }
    public function index()
    {
        $categories = $this->category_model->getAllStats();

        if (!is_array($categories)) {
            $categories = [];
        }

        $threads = $this->thread_model->getAllDEtails();
        if (!is_array($threads)) {
            $threads = [];
        }

        $recent_threads = [];
        foreach ($threads as $thread) {
            $recent_threads[] = [
                'thread_id' => $thread['thread_id'],
                'title' => $thread['title'],
                'author_name' => $thread['author_fname'] . ' ' . $thread['author_lname'],
                'author_id' => $thread['user_id'],
                'category_name' => $thread['cat_name'],
                'replies' => $thread['reply_count'] ?? 0,
                'views' => $thread['views'] ?? 0,
                'last_author' => $thread['last_post_author'] ?? $thread['author_fname'] . ' ' . $thread['author_lname'],
                'last_edited' => $thread['last_edited'] ?? $thread['created_at']
            ];
        }
        $currentUserId = $this->getCurrentUserId();

        $data = [
            'title' => 'Discussion Forum',
            'categories' => $categories,
            'recent_threads' => $recent_threads,
            'curr_user_id' => $currentUserId,
            'stats' => [
                'total_threads' => count($threads),
                'total_posts' => 0,
                'total_members' => 0
            ]
        ];

        $this->view('actors/students/forum_home', $data);
    }

    public function create_posts()
    {
        if (!isset($_SESSION['USER'])) {
            header('Location: ' . BASE_URL . '/Login/index?redirect=/Discussion_Forum/create_posts');
            exit;
        }

        $categories = $this->category_model->getOrderedCat();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $title = trim($_POST['title'] ?? '');
            $content = trim($_POST['content'] ?? '');
            $category_id = $_POST['cat_id'];

            $errors = [];

            if (strlen($title) < 5) {
                $errors[] = 'Title must be at least 5 characters long.';
            }

            if (strlen($content) < 5) {
                $errors[] = 'Content must be at least 10 characters long.';
            }

            if (empty($category_id)) {
                $errors[] = 'Please select a category';
            }

            if (!empty($errors)) {
                $data = [
                    'page_title' => 'Create a new thread',
                    'categories' => $categories,
                    'errors' => implode(',', $errors),
                    'old' => $_POST
                ];

                $this->view('actors/students/forum_create', $data);
                return;
            }

            $userId = $this->getCurrentUserId();

            $thread_data = [
                'title' => $title,
                'cat_id' => $category_id,
                'content' => $content,
                'user_id' => $userId
            ];

            $result = $this->thread_model->create_thread($thread_data);

            if ($result) {
                header("Location: " . BASE_URL . "/Discussion_Forum?success=Thread created successfully");
                exit;
            } else {
                $data = [
                    'page_title' => 'Create a new thread',
                    'categories' => $categories,
                    'errors' => 'Failed to create thread.please try again',
                    'old' => $_POST
                ];
                $this->view('actors/students/forum_create', $data);
            }
            return;
        }

        $data = [
            'title' => 'Create a new thread',
            'categories' => $categories
        ];
        $this->view('actors/students/forum_create', $data);
    }
    


    public function view_thread($thread_id = null)
    {
        if (!$thread_id) {
            header("Location: " . BASE_URL . "/Discussion_Forum?error=Invalid thread ID");
            exit;
        }

        $thread = $this->thread_model->getIdWithDetails($thread_id);

        if (!$thread) {
            header("Location: " . BASE_URL . "/Discussion_Forum?error=Thread not found");
            exit;
        }

        if (!isset($_SESSION['viewed_threads'][$thread_id])) {
            $this->thread_model->countViews($thread_id);
            $_SESSION['viewed_threads'][$thread_id] = true;
        }

        $posts = $this->post_model->getByThread($thread_id);

        $thread_data = [
            'thread_id' => $thread['thread_id'],
            'title' => $thread['title'],
            'content' => $thread['content'],
            'author_id' => $thread['author_id'],
            'created_at' => $thread['created_at'],
            'views' => $thread['views'],
            'replies'=>$thread['reply_count'] ?? 0,
            'cat_name' => $thread['cat_name'],
            'is_locked' => $thread['is_locked'],
            'author_name' => $thread['author_fname'] . ' ' . $thread['author_lname']
        ];

        $post_data = [];
        foreach ($posts as $post) {
            $post_data[] = [
                'post_id' => $post['post_id'],
                'content' => $post['content'],
                'author_id' => $post['author_id'],
                'author_name' => $post['author_fname'] . ' ' . $post['author_lname'],
                'created_at' => $post['created_at'],
                'is_edited' => $post['is_edited'] ?? false,
                'edited_at' => $post['edited_at'] ?? null
            ];
        }

        $currentUserId = $this->getCurrentUserId();
        $can_edit = $this->isAdmin($thread_data['author_id']);

        $data = [
            'title' => $thread['title'],
            'thread' => $thread_data,
            'posts' => $post_data,
            'curr_user_id' => $currentUserId,
            'can_edit' => $can_edit
        ];

        $this->view('actors/students/forum_single_discussion', $data);
    }

    //function to view the user's discussions
    public function view_my_discussion()
    {
        //funtion to display own threads and replies
        if (!isset($_SESSION['USER'])) {
            header('Location: ' . BASE_URL . '/Login/index?redirect=/Discussion_Forum/view_my_discussion');
            exit;
        }
        $user_id = $this->getCurrentUserId();

        $threads = $this->thread_model->getByUser($user_id);
        if (!is_array($threads)) {
            $threads = [];
        }

        $my_threads = [];
        foreach ($threads as $thread) {
            $my_threads[] = [
                'thread_id' => $thread['thread_id'],
                'title' => $thread['title'],
                'cat_name' => $thread['cat_name'],
                'replies' => $thread['reply_count'] ?? 0,
                'views' => $thread['views'] ?? 0,
                'created_at' => $thread['created_at'],
                'last_posted_at' => $thread['last_posted_at'] ?? $thread['created_at']

            ];
        }

        $data = [
            'title' => 'My Discussions',
            'threads' => $my_threads,
            'user_name' => $_SESSION['first_name'] ?? 'User'
        ];

        $this->view('actors/students/forum_my_discussions', $data);
    }

    public function edit_post($thread_id = null)
    {

        if (!$thread_id) {
            header("Location: " . BASE_URL . "/Discussion_Forum?error=Invalid thread ID");
            exit;
        }

        if (!isset($_SESSION['USER'])) {
            header('Location: ' . BASE_URL . '/Login/index?redirect=/Discussion_Forum/view_my_discussion');
            exit;
        }

        $thread = $this->thread_model->getIdWithDetails($thread_id);

        if (!$thread) {
            header("Location: " . BASE_URL . "/Discussion_Forum?error=Thread not found");
            exit;
        }

        if (!$this->isAdmin($thread['author_id'])) {
            header("Location: " . BASE_URL . "/Discussion_Forum/thread/{$thread_id}?error=You do not have permission to edit this thread");
            exit;
        }

        $categories = $this->category_model->getOrderedCat();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $title = trim($_POST['title'] ?? '');
            $content = trim($_POST['content'] ?? '');
            $category = $_POST['cat_id'];

            $errors = [];

            if (strlen($title) < 5) {
                $errors[] = 'Title must be at least 5 characters long.';
            }

            if (strlen($content) < 10) {
                $errors[] = 'content must be at least 10 characters long.';
            }

            if (empty($category)) {
                $errors[] = 'Please select a category.';
            }

            if (!empty($errors)) {
                $data = [
                    'title' => 'Edit your Post',
                    'categories' => $categories,
                    'thread' => $thread,
                    'error' => implode(', ', $errors),
                    'old' => $_POST
                ];

                $this->view('actors/students/forum_edit', $data);
                return;
            }

            $updatedData = [
                'title' => $title,
                'content' => $content,
                'category' => $category
            ];

            $result = $this->thread_model->update_post($thread_id, $updatedData);

            if ($result) {
                header("Location: " . BASE_URL . "/Discussion_Forum/view_thread?success=Thread updated successfully!");
                exit;
            } else {
                $data = [
                    'title' => 'Edit your Post',
                    'categories' => $categories,
                    'thread' => $thread,
                    'error' => 'Thread was not updated successfully.Please try again.',
                    'old' => $_POST
                ];
                $this->view('actors/students/forum_edit', $data);
                return;
            }
        }
        $data = [
            'title' => 'Edit your Post',
            'categories' => $categories,
            'thread' => $thread,
        ];

        $this->view('actors/students/forum_edit', $data);
        return;
    }

    public function delete_post($thread_id = null)
    {
        if (!$thread_id) {
            header("Location: " . BASE_URL . "/Discussion_Forum?error=Invalid thread ID");
            exit;
        }

        if (!isset($_SESSION['USER'])) {
            header('Location: ' . BASE_URL . '/Login/index');
            exit;
        }

        $thread = $this->thread_model->getIDwithDetails($thread_id);

        if (!$thread) {
            header("Location: " . BASE_URL . "/Discussion_Forum?error=Thread not found");
            exit;
        }

        if (!$this->isAdmin($thread['author_id'])) {
            header("Location: " . BASE_URL . "/Discussion_Forum/thread/{$thread_id}?error=You do not have permission to edit this thread");
            exit;
        }

        $result = $this->thread_model->delete_post($thread_id);
        $delete_reply = $this->post_model->delete_all_reply($thread_id);

        if ($result && $delete_reply) {
            header("Location: " . BASE_URL . "/Discussion_Forum/view_thread?success=Thread deleted successfully!");
            exit;
        } else {
            header("Location: " . BASE_URL . "/Discussion_Forum/view_thread?error=Delete Failed!");
            exit;
        }
    }

    public function reply_post($thread_id = null)
    {

        if (!isset($_SESSION['USER'])) {
            header('Location: ' . BASE_URL . '/Login/index?redirect=/Discussion_Forum/view_thread/' . $thread_id);
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $user_id = $this->getCurrentUserId();

            $data = [
                'content' => trim($_POST['content'] ?? ''),
                'thread_id' => $thread_id,
                'user_id' => $user_id,

                'content_err' => ''

            ];



            //validate
            if (empty($data['content'])) {
                $data['content_err'] = 'Please enter your content';
            } else if (strlen($data['content']) < 10) {
                $data['content_err'] = 'Your reply must be at least 10 characters long';
            }

            if (empty($data['content_err'])) {
                if ($this->post_model->create_reply($data)) {
                    header("Location: ". BASE_URL . "/Discussion_Forum/view_thread/{")
                } else {
                    redirect("Discussion_Forum/view_thread/{$thread_id}?error=reply_failed");
                }
            } else {
                $this->view('actors/students/forum_single_discussion', $data);
            }
        } else {

            redirect("Discussion_Forum/view_thread/{$thread_id}");
        }
    }

    public function edit_reply($post_id = null)
    {

        if (!isset($_SESSION['USER'])) {
            redirect("Discussion_forum/Login/index");
        }
        $user_id = $this->getCurrentUserId();

        $post = $this->post_model->getPostDetailsWithId($post_id);
        if (!$this->isAdmin($post['author_id'])) {
            redirect("/Discussion_Forum/view_thread/{$post['thread_id']}?error=unauthorized");
        }


        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $content = trim($_POST['content'] ?? '');

            $data = [
                'post_id' => $post['post_id'],
                'content' => $content,

                'content_err' => ''
            ];

            if (empty($data['content'])) {
                $data['content_err'] = 'Please enter your reply';
            } elseif (strlen($data['content']) < 10) {
                $data['content_err'] = 'Your reply must contain at least';
            }

            if (empty($data['content_err'])) {
                if ($this->post_model->update_reply($post_id, $content,$user_id)){
                    redirect("Discussion_Forum/view_thread/{$post['thread_id']}?success=reply_edited_successfully");
                }
                else{
                    redirect("Discussion_Forum/view_thread/{$post['thread_id']}?error=update_failed");
                }
                     
            }
            else{
                $this->view('actors/students/edit_reply',$data);
            }

        }
        else{
            if($post['author_id'] != $_SESSION['USER']['user_id']){
                redirect('Discussion_Forum/index');
            }
            $data= [
                'post_id'=>$post_id,
                'content'=>$post['content'],
                'content_err'=>''
            ];

            $this->view('actors/students/edit_reply',$data);
        }
    }

    public function delete_reply($post_id){
        if(!$post_id){
            redirect("Discussion_Forum/index");
        }

        if(!isset($_SESSION['USER'])){
            redirect("Login/index");
        }

        $post = $this->post_model->getPostDetailsWithId($post_id);

        if(!$post){
            redirect("Discussion_Forum/view_thread/{$post['thread_id']}?error=post_not_found");
        }

        if(!$this->isAdmin($post['author_id'])){
            redirect("Discussion_Forum/index?error=not_authorized");
        }

        if($this->post_model->delete_a_single_post($post_id)){
            redirect("Discussion_Forum/view_my_discussion/{$post['thread _id']}?success=delete_successful");
        }
        else{
            redirect("Discussion_Forum/view_my_discussion/{$post['thread _id']}?error=delete_failed");
        }
    }
}
