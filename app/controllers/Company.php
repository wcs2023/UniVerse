<?php

class Company extends Controller{
    
    public function index(){
        
        $this->view('actors/company/landing');
    }
    
    public function postjobs(){
        
        $this->view('actors/company/postjobs');
    }
    
    public function managejobs(){
        
        $this->view('actors/company/managejobs');
    }
    
    public function applications(){

        $this->view('actors/company/applications');
    }
    
    public function profile(){

        $this->view('actors/company/profile');
    }
}
