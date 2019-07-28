<?php
include_once('./config.php');

define('SELF_FILE', __FILE__);

if(!isset($_SESSION['uid'])){
    die("You should login first!");
}

if(isset($_POST['cid']) && isset($_POST['flag']) && isset($_SESSION['uid']) && isset($_SESSION['user'])){
    $cid = (int)addslashes($_POST['cid']);
    $uid = (int)addslashes($_SESSION['uid']);
    $flag = addslashes($_POST['flag']);

    $conn = get_sql_conn();
    $sql = "select flag from challenge where cid=$cid";
    $result = $conn->query($sql);
    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();

        if($flag === $row['flag']){
            $str_time = addslashes(date("Y-m-d H:i:s"));
            $sql = "insert into solved (cid, uid, time)values($cid, $uid, '$str_time')";
            $conn->query($sql);
            echo "success";
        }else{
            echo "failed";
        }
    }

    $conn->close();
}