<?php 

class Uhome extends Controller{

    public function index(){
        if(isset($_SESSION['user_id']) && isset($_SESSION['user_type']) &&$_SESSION['user_type'] === 'undergraduate')
        {
            $this->view('actors/undergraduate/UHome');
        }
        else
        {   
            redirect('login');
            exit;
            // $this->view('login');
        }
        // load the undergraduate home view
    }
}
