<?php
include_once('../config.php');

if(isset($_SESSION['captcha_code'])){
    if($_SESSION['captcha_code'] && $_SESSION['captcha_code'] !== strtolower($_POST['captcha_code'])){
        die("captcha_code error");
    }else{
        unset($_SESSION['captcha_code']);
    }
}else{
    die("captcha_code error");
}

$email = addslashes($_POST['email']);
$nickname = addslashes($_POST['nickname']);
$password = addslashes(hash("sha256", $_POST['password']));

if($email == '' || $nickname == '' || $_POST['password'] == ''){
    die("Email or nickname or password can't be empty!");
}

if(strlen($_POST['password']) < 8){
    die("Password can not be empty for at least eight!");
}

if(strlen($_POST['nickname']) > 20 || strlen($_POST['email']) > 100){
    die("Nickname or email is too long!");
}

if(!preg_match('/^\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/', $email)){
    die("Email is invalid");
}

$conn = get_sql_conn();

$sql = "select uid from user where binary email='$email' or binary nickname='$nickname'";

$result = $conn->query($sql);

if($result->num_rows == 0){
    $str_time = addslashes(date("Y-m-d H:i:s"));
    $sql = "insert into `user` (email, nickname, password, register_time)values('$email', '$nickname', '$password', '$str_time')";
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