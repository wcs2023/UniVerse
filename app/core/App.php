<?php

class App{
    
    private $controller = 'Home';
    private $method = 'index';
    private $params = [];

    private function splitURL(){
        $URL = $_GET['url'] ?? 'home';
        $URL = explode('/', $URL);
        return $URL;
    }

    public function loadController(){
        $URL = $this->splitURL();
        $filename = "../app/controllers/".ucfirst($URL[0]).".php";

        if(file_exists($filename)){
            require $filename;
            $this->controller = ucfirst($URL[0]);
            unset($URL[0]);
        }else{
            $filename = "../app/controllers/_404.php";
            require $filename;
            $this->controller = '_404';
        }

        $controller = new $this->controller;

        // Check for method
        if(isset($URL[1])){
            if(method_exists($controller, $URL[1])){
                $this->method = $URL[1];
                unset($URL[1]);
            }
        }

        // Get parameters
        $this->params = $URL ? array_values($URL) : [];

        // Call the controller method with parameters
        call_user_func_array([$controller, $this->method], $this->params);
    }
}
