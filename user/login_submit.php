<?php
include_once('../config.php');

$conn = get_sql_conn();
$email = addslashes($_POST['email']);
$password = addslashes(hash("sha256", $_POST['password']));

$sql = "select uid, nickname from user where binary email='$email' and binary password='$password'";

$result = $conn->query($sql);

if($result->num_rows == 1){
    $row = $result->fetch_assoc();
    $_SESSION['user'] = $row['nickname'];
    $_SESSION['uid'] = $row['uid'];
    echo "success";
}else{
    echo "failed";
}

$conn->close();
?>