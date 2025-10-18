<?php

class Logout extends Controller {
    
    public function index() {
        // Destroy all session data
        session_unset();
        session_destroy();
        
        // Redirect to login page
        header('Location: ' . BASE_URL . '/login');
        exit();
    }
}