<?php

// Check if running via command line or web server
if (!isset($_SERVER['SERVER_NAME'])) {
    $_SERVER['SERVER_NAME'] = 'localhost'; // Default for CLI
}

if($_SERVER['SERVER_NAME'] == 'localhost') {
    define('DBHOST', 'localhost');
    define('DBNAME', 'universe_db');  // Updated to match our schema
    define('DBUSER', 'root');
    define('DBPASS', '');
    define('DBDRIVER', '');
    define('BASE_URL', 'http://localhost/UniVerse/public');
} else {
    define('DBNAME', 'universe_db');  // Updated to match our schema
    define('DBHOST', 'localhost');
    define('DBUSER', 'root');
    define('DBPASS', '');
    define('DBDRIVER', '');
    define('BASE_URL', 'https://yourdomain.com/public');
}

define('APPROOT',dirname(dirname(__FILE__))); 
define('URLROOT','http://localhost/UniVerse'); 
define('SITENAME','UniVerse');