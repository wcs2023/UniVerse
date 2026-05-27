<?php

// Check if running via command line or web server
if (!isset($_SERVER['SERVER_NAME'])) {
    $_SERVER['SERVER_NAME'] = 'localhost'; // Default for CLI
}

if($_SERVER['SERVER_NAME'] == 'localhost') {
    define('DBHOST', getenv('DB_HOST') ?: 'sql303.infinityfree.com');
    define('DBNAME', getenv('DB_NAME') ?: 'if0_42029602_universe');
    define('DBUSER', getenv('DB_USER') ?: 'if0_42029602');
    define('DBPASS', getenv('DB_PASS') ?: '2RdlSJQesHA6');
    define('DBDRIVER', getenv('DB_DRIVER') ?: 'mysql');
    define('BASE_URL', getenv('BASE_URL') ?: 'http://localhost/UniVerse/public');
} else {
    define('DBHOST', getenv('DB_HOST') ?: 'sql303.infinityfree.com');
    define('DBNAME', getenv('DB_NAME') ?: 'if0_42029602_universe');
    define('DBUSER', getenv('DB_USER') ?: 'if0_42029602');
    define('DBPASS', getenv('DB_PASS') ?: '2RdlSJQesHA6');
    define('DBDRIVER', getenv('DB_DRIVER') ?: 'mysql');
    define('BASE_URL', getenv('BASE_URL') ?: 'https://uni-verse-beige.vercel.app/public');
}

define('APPROOT',dirname(dirname(__FILE__))); 
define('URLROOT', getenv('URL_ROOT') ?: 'https://uni-verse-beige.vercel.app'); 
define('SITENAME','UniVerse');