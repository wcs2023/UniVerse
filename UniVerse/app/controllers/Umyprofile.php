<?php 

class Umyprofile extends Controller{

    // GET  /?url=umyprofile  (or index.php?url=umyprofile)
    public function index(){
        // load the undergraduate My Profile view
        $this->view('actors/undergraduate/UMyProfile');
    }
}
