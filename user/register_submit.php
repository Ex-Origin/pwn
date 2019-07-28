<?php
include_once('../config.php');

if(isset($_SESSION['captcha_code'])){
    if($_SESSION['captcha_code'] && $_SESSION['captcha_code'] !== $_POST['captcha_code']){
        die("captcha_code error");
    }
}

$email = addslashes($_POST['email']);
$nickname = addslashes($_POST['nickname']);
$password = addslashes(hash("sha256", $_POST['password']));

if($email == '' || $nickname == '' || $_POST['password'] == ''){
    die("Email or nickname or password can't be empty!");
}

if(strlen($_POST['password']) < 6){
    die("Password can not be empty for at least six!");
}

if(!preg_match('/^\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/', $email)){
    die("Email is invalid");
}

$conn = get_sql_conn();

$sql = "select uid from user where binary email='$email' or binary nickname='$nickname'";

$result = $conn->query($sql);

if($result->num_rows == 0){
    $sql = "insert into `user` (email, nickname, password)values('$email', '$nickname', '$password')";
    if ($conn->query($sql) === TRUE) {
        echo "success";
    }else{
        echo "insert error!";
    }
}else{
    echo "The nickname or email has already existed!";
}

$conn->close();
?>