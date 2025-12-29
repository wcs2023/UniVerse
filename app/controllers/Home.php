<?php 

class Home extends Controller{

    public function index(){
        // Start session if not already started
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        // Show public home page for non-logged-in users
        $this->view('home');
    }
}
