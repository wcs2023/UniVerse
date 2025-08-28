<?php

class App{
    
    private $controller = 'Home';
    private $method = 'index';
    private function splitURL(){
        $URL = $_GET['url'] ?? 'home';
        $URL = explode('/', $URL);
        return $URL;
    }

    public function loadController(){
        $URL = $this->splitURL();
        
        // Get the controller name from URL
        $filename = "../app/controllers/".ucfirst($URL[0]).".php";

        if(file_exists($filename)){
            require $filename;
            $this->controller = ucfirst($URL[0]);
        }else{
            $filename = "../app/controllers/_404.php";
            require $filename;
            $this->controller = '_404';
        }

        // Check if a method is specified in the URL
        if(isset($URL[1])){
            if(method_exists($this->controller, $URL[1])){
                $this->method = $URL[1];
            }
        }
        
        $controller = new $this->controller;
        call_user_func([$controller, $this->method], []);
    }

}
