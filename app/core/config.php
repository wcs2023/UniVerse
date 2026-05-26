<?php

// Check if running via command line or web server
if (!isset($_SERVER['SERVER_NAME'])) {
    $_SERVER['SERVER_NAME'] = 'localhost'; // Default for CLI
}

if($_SERVER['SERVER_NAME'] == 'localhost') {
    define('DBHOST', getenv('DB_HOST') ?: 'localhost');
    define('DBNAME', getenv('DB_NAME') ?: 'universe_db');
    define('DBUSER', getenv('DB_USER') ?: 'root');
    define('DBPASS', getenv('DB_PASS') ?: '');
    define('DBDRIVER', getenv('DB_DRIVER') ?: '');
    define('BASE_URL', getenv('BASE_URL') ?: 'http://localhost/UniVerse/public');
} else {
    define('DBHOST', getenv('DB_HOST') ?: 'localhost');
    define('DBNAME', getenv('DB_NAME') ?: 'universe_db');
    define('DBUSER', getenv('DB_USER') ?: 'root');
    define('DBPASS', getenv('DB_PASS') ?: '');
    define('DBDRIVER', getenv('DB_DRIVER') ?: '');
    define('BASE_URL', getenv('BASE_URL') ?: 'https://yourdomain.com/public');
}

define('APPROOT',dirname(dirname(__FILE__))); 
define('URLROOT', getenv('URL_ROOT') ?: 'http://localhost/UniVerse'); 
define('SITENAME','UniVerse');