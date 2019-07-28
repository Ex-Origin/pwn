<?php
include_once('../config.php');
define('SELF_FILE', __FILE__);

session_destroy();

header('location:' . relative(SELF_FILE) . 'index.php');

?>