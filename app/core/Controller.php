<?php
class Controller{
    public function view($name, $data = []){
        // Extract data array to variables
        if(!empty($data)){
            extract($data);
        }
        
        $filename = "../app/views/".$name.".php";
        if(file_exists($filename)){
            require $filename;
        }else{
            $filename = "../app/views/".$name.".view.php";
            if(file_exists($filename)){
                require $filename;
            }else{
                $filename = "../app/views/404.view.php";
                require $filename;
            }
        }
    }
    
    public function model($name){
        $filename = "../app/models/".$name.".php";
        if(file_exists($filename)){
            require_once $filename;
            return new $name();
        }
        return false;
    }
}