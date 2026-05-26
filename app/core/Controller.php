<?php
class Controller{
    public function view($name,$data=[]){
        extract($data);
        $filename = APPROOT . "/views/" . $name . ".view.php";
        if(file_exists($filename)){
            require $filename;
        }else{
            $filename = APPROOT . "/views/" . $name . ".view.php";
            if(file_exists($filename)){
                require $filename;
            }else{
                $filename = APPROOT . "/views/404.view.php";
                require $filename;
            }
        }
    }
    public function model($model){
        require_once APPROOT . '/models/' . $model . '.php';
        return new $model();
    }
}