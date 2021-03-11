<?php
include_once('../config.php');

if(isset($_SESSION['captcha_code'])){
    if($_SESSION['captcha_code'] && $_SESSION['captcha_code'] !== strtolower($_POST['captcha_code'])){
        die("Error: captcha_code error");
    }else{
        unset($_SESSION['captcha_code']);
    }
}else{
    die("Error: captcha_code error");
}

if(!isset($_SESSION['uid']))
{
    die("Error: You should login first");
}

$comment = addslashes($_POST['comment']);

if(strlen($comment) > 200){
    die("Error: The comment is too long!");
}

$uid = (int)addslashes($_SESSION['uid']);

$conn = get_sql_conn();

$sql = "update user set comment='$comment' where uid=$uid";
if($conn->query($sql)){
    echo "Comment has been updated successfully! ";
}else{
    echo ("Modify comment failed! ");
}


if(isset($_POST['new_password']) && $_POST['new_password']){
    if(strlen($_POST['new_password']) >= 6){
        $current_password = addslashes(hash("sha256", $_POST['current_password']));
        $new_password = addslashes(hash("sha256", $_POST['new_password']));
        $sql = "select uid from user where binary password='$current_password' and uid=$uid";
        $result = $conn->query($sql);

        if($result->num_rows == 1){
            $sql = "update user set password='$new_password' where uid=$uid";
            if($conn->query($sql)){
                echo "Password has been updated successfully! ";
            }else{
                echo "Modify password failed! Unknow Error! ";
            }
        }else{
            echo "Modify password failed! Please enter a correct password. Note that both fields may be case-sensitive. ";
        }
    }else{
        echo "Password can not be empty for at least six! ";
    }
    
}

$conn->close();
?>