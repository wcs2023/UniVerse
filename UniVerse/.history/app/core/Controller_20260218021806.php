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
        if(<!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Document</title>
        </head>
        <body>
            
        </body>
        </html>isset($_POST['csrf_token'],$_SESSION['csrf_token'])){

        }
    }
}