<?php

// Define the application root directory
define('APPROOT', dirname(dirname(__FILE__)));

// Define the URL root
define('URLROOT', 'http://localhost/UniVerse/public');

if($_SERVER['SERVER_NAME'] == 'localhost') {
    define('DBHOST', 'localhost');
    define('DBNAME', 'my_db');
    define('DBUSER', 'root');
    define('DBPASS', '');
    define('DBDRIVER', '');
    define('BASE_URL', 'http://localhost/UniVerse/public');
} else {
    define('DBNAME', 'my_db');
    define('DBHOST', 'localhost');
    define('DBUSER', 'root');
    define('DBPASS', '');
    define('DBDRIVER', '');
    define('BASE_URL', 'https://yourdomain.com/public');
}