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

        $routeController = strtolower($URL[0] ?? 'home');
        if (isset($_SESSION['user_id']) && !in_array($routeController, ['login', 'logout'], true)) {
            try {
                $userModel = new User();
                $sessionUser = $userModel->getUserById($_SESSION['user_id']);

                if (!$sessionUser || ($sessionUser['account_status'] ?? 'active') !== 'active') {
                    session_unset();
                    session_destroy();
                    session_start();
                    $_SESSION['login_error'] = 'Your account has been deactivated. Please contact an administrator.';
                    header('Location: ' . BASE_URL . '/login');
                    exit;
                }
            } catch (Exception $e) {
                error_log('Session account-status check failed: ' . $e->getMessage());
                session_unset();
                session_destroy();
                session_start();
                $_SESSION['login_error'] = 'Please login again.';
                header('Location: ' . BASE_URL . '/login');
                exit;
            }
        }

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
