<?php
// Load configuration FIRST (before session, before anything else)
require_once __DIR__ . '/config.php';

// Start session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Define base paths
define('BASE_PATH', dirname(__DIR__));
// BASE_URL is already defined in config.php, so don't redefine it

// Load core files
require_once BASE_PATH . '/core/App.php';
require_once BASE_PATH . '/core/Controller.php';
require_once BASE_PATH . '/core/Model.php';
require_once BASE_PATH . '/core/Database.php';

// Autoload models dynamically,like the user model loaded in registration controller
spl_autoload_register(function($className) {
    $modelPath = BASE_PATH . '/models/' . $className . '.php';
    if (file_exists($modelPath)) {
        require_once $modelPath;
    }
});

// Initialize database connection (AFTER config.php is loaded)
Database::getInstance();