<?php

class Admin extends Controller
{
    public function __construct()
    {
        // Start session if not already started
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        // Check if user is logged in and is an admin
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
            header('Location: ' . BASE_URL . '/login');
            exit;
        }
    }
    
    /**
     * Admin Dashboard - Default landing page
     */
    public function index()
    {
        $this->dashboard();
    }
    
    /**
     * Dashboard view with statistics
     */
    public function dashboard()
    {
        // Get statistics from models
        $userModel = $this->model('User');
        
        // Get simple counts
        $totalUsers = 0;
        $totalArticles = 0;
        $pendingRegistrations = 0;
        
        try {
            // Get all users and count
            $allUsers = $userModel->getUsersByType(null);
            $totalUsers = is_array($allUsers) ? count($allUsers) : 0;
        } catch (Exception $e) {
            error_log("Error getting users: " . $e->getMessage());
        }
        
        try {
            // Get articles count
            $articleModel = $this->model('AarticleModel');
            $totalArticles = $articleModel->getTotalArticlesCount();
        } catch (Exception $e) {
            error_log("Error getting articles count: " . $e->getMessage());
        }
        
        // Get recent activity (empty for now)
        $recentUsers = [];
        $recentArticles = [];
        
        try {
            $articleModel = $this->model('ArticleModel');
            $recentArticles = $articleModel->getAllPublishedArticles(5, 0);
        } catch (Exception $e) {
            error_log("Error getting recent articles: " . $e->getMessage());
        }
        
        $data = [
            'totalUsers' => $totalUsers,
            'totalArticles' => $totalArticles,
            'pendingRegistrations' => $pendingRegistrations,
            'recentUsers' => $recentUsers,
            'recentArticles' => $recentArticles
        ];
        
        $this->view('actors/admin/dashboard', $data);
    }
    
    /**
     * Manage users
     */
    public function users()
    {
        $userModel = $this->model('User');
        
        // Get all users
        $users = [];
        try {
            $users = $userModel->getUsersByType(null);
        } catch (Exception $e) {
            error_log("Error getting users: " . $e->getMessage());
        }
        
        $data = [
            'users' => $users
        ];
        
        $this->view('actors/admin/users', $data);
    }
    
    /**
     * Manage articles
     */
    public function articles()
    {
        $articleModel = $this->model('AarticleModel');
        
        // Get all articles
        $articles = $articleModel->getAllArticles();
        
        $data = [
            'articles' => $articles
        ];
        
        $this->view('actors/admin/articles', $data);
    }
    
    /**
     * Manage registrations
     */
    public function registrations()
    {
        $userModel = $this->model('User');
        
        // Get pending registrations (users with status 'pending' or similar)
        $pendingUsers = [];
        try {
            // Since getPendingUsers doesn't exist, we'll get all users and filter
            // or show all users for now
            $pendingUsers = $userModel->getUsersByType(null);
        } catch (Exception $e) {
            error_log("Error getting pending users: " . $e->getMessage());
        }
        
        $data = [
            'pendingUsers' => $pendingUsers
        ];
        
        $this->view('actors/admin/registrations', $data);
    }
    
    /**
     * Manage forums
     */
    public function forums()
    {
        // TODO: Implement forum management
        $data = [
            'forums' => []
        ];
        
        $this->view('actors/admin/forums', $data);
    }
    
    /**
     * Manage notifications
     */
    public function notifications()
    {
        // TODO: Implement notification management
        $data = [
            'notifications' => []
        ];
        
        $this->view('actors/admin/notifications', $data);
    }
    
    /**
     * Admin settings
     */
    public function settings()
    {
        $data = [];
        
        $this->view('actors/admin/settings', $data);
    }
    
    /**
     * Approve a user registration
     */
    public function approveUser($userId = null)
    {
        if (!$userId) {
            header('Location: ' . BASE_URL . '/admin/registrations');
            return;
        }
        
        $userModel = $this->model('User');
        // Update user status to approved (if such field exists)
        try {
            $result = $userModel->updateUser($userId, ['status' => 'approved']);
            header('Location: ' . BASE_URL . '/admin/registrations?success=approved');
        } catch (Exception $e) {
            error_log("Error approving user: " . $e->getMessage());
            header('Location: ' . BASE_URL . '/admin/registrations?error=approve_failed');
        }
    }
    
    /**
     * Reject a user registration
     */
    public function rejectUser($userId = null)
    {
        if (!$userId) {
            header('Location: ' . BASE_URL . '/admin/registrations');
            return;
        }
        
        $userModel = $this->model('User');
        // Update user status to rejected
        try {
            $result = $userModel->updateUser($userId, ['status' => 'rejected']);
            header('Location: ' . BASE_URL . '/admin/registrations?success=rejected');
        } catch (Exception $e) {
            error_log("Error rejecting user: " . $e->getMessage());
            header('Location: ' . BASE_URL . '/admin/registrations?error=reject_failed');
        }
    }
    
    /**
     * Delete a user
     */
    public function deleteUser($userId = null)
    {
        if (!$userId) {
            header('Location: ' . BASE_URL . '/admin/users');
            return;
        }
        
        // For now, just redirect back - implement delete functionality later
        header('Location: ' . BASE_URL . '/admin/users?info=delete_not_implemented');
    }
    
    /**
     * Update article status
     */
    public function updateArticleStatus()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . '/admin/articles');
            return;
        }
        
        $articleId = $_POST['article_id'] ?? null;
        $status = $_POST['status'] ?? null;
        
        if (!$articleId || !$status) {
            header('Location: ' . BASE_URL . '/admin/articles?error=missing_data');
            return;
        }
        
        $articleModel = $this->model('AarticleModel');
        $result = $articleModel->updateArticleStatus($articleId, $status);
        
        if ($result) {
            header('Location: ' . BASE_URL . '/admin/articles?success=updated');
        } else {
            header('Location: ' . BASE_URL . '/admin/articles?error=update_failed');
        }
    }
    
    /**
     * Helper method to get articles count
     */
    private function getArticlesCount()
    {
        try {
            $articleModel = $this->model('AarticleModel');
            return $articleModel->getTotalArticlesCount();
        } catch (Exception $e) {
            error_log("Error getting articles count: " . $e->getMessage());
            return 0;
        }
    }
    
    /**
     * Helper method to get pending registrations count
     */
    private function getPendingRegistrationsCount()
    {
        try {
            $userModel = $this->model('User');
            $users = $userModel->getUsersByType(null);
            // Count users with status 'pending' if that field exists
            return is_array($users) ? count($users) : 0;
        } catch (Exception $e) {
            error_log("Error getting pending registrations: " . $e->getMessage());
            return 0;
        }
    }
}
