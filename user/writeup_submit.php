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

if(!isset($_SESSION['uid'])){
    die("You should login first!");
}

$conn = get_sql_conn();

$cid = (int)addslashes($_POST['cid']);
$uid = (int)addslashes($_SESSION['uid']);
$writeup = addslashes($_POST['writeup']);

$sql = "select b.name as name, a.sid as sid from solved as a join challenge as b on a.cid=b.cid where a.uid=$uid and b.cid=$cid";
$result = $conn->query($sql);
if($result->num_rows == 0){
    die("You can share write-up or exploit code in your profile, only players who also solved the same challenge are able to see them.");
    $conn->close();
}else if($result->num_rows != 1){
    die("Unkown error!");
    $conn->close();
}

$row = $result->fetch_assoc();
$sid = (int)addslashes($row['sid']);
$str_time = addslashes(date("Y-m-d H:i:s"));


$sql = "insert into writeups (sid, writeup, time) values ($sid, '$writeup', '$str_time')";


if($conn->query($sql) === TRUE){
    echo "success";
}else{
    echo "insert data failed!";
}

$conn->close();
?>