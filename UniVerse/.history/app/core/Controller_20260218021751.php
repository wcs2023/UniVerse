<?php
class Controller{
    public function view($name,$data=[]){
        extract($data);
        $filename = "../app/views/".$name.".view.php";
        if(file_exists($filename)){
            require $filename;
        }else{
            $filename = "../app/views/404.view.php";
            require $filename;
        }
    }
    
    public function model($model){
        require_once '../app/models/' . $model . '.php';
        return new $model();
    }

    private function verifyCSRFToken(){
        if($_isset($_POST['csrf_token'],$_SESSION['csrf']))
    }
}