<?php
session_start();

define('ROOT_DIR', dirname(__FILE__).'/');

// define('DATABASE_HOST','127.0.0.1');
// define('DATABASE_USER','*******************');
// define('DATABASE_PASSWORD','*******************');
// define('DATABASE_NAME','*******************');
@include_once ROOT_DIR . 'password.php';

// @require_once ROOT_DIR . 'waf.php';

function relative($file)
{

    if (defined('RELATIVE_PATH')) {
        return RELATIVE_PATH;
    }

    $relative_path = '';
    
    $absolute_path = str_replace(ROOT_DIR, '', dirname($file).'/');

    $str_length = strlen($absolute_path);
    for ($i = 0; $i < $str_length; $i++) {
        if ($absolute_path[$i] == '/') {
            $relative_path = $relative_path . '../';
        }
    }

    if ($relative_path == '') {
        $relative_path = './';
    }
    define('RELATIVE_PATH', $relative_path);
    return RELATIVE_PATH;
}


function get_sql_conn(){
    
    $conn = mysqli_connect(DATABASE_HOST, DATABASE_USER, DATABASE_PASSWORD, DATABASE_NAME);

    if (!$conn) {
        header('HTTP/1.1 500 Internal Server Error');
        die("Failed to connect to database");
    } 

    if(mysqli_set_charset($conn,"utf8") == false){
        header('HTTP/1.1 500 Internal Server Error');
        die("An error occurred while modifying the database connection character set to utf8");
    }

    return $conn;
}
