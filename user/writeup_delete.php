<?php
include_once('../config.php');

if(!isset($_SESSION['uid'])){
    die("false");
}

$conn = get_sql_conn();

$wid = (int)addslashes($_POST['wid']);
$uid = (int)addslashes($_SESSION['uid']);

$sql = "select c.wid as wid  
from solved a right join challenge b on a.cid=b.cid join writeups c on a.sid=c.sid
where a.uid=$uid and c.wid=$wid";

$result = $conn->query($sql);
if($result->num_rows == 0){
    $conn->close();
    die("false");
}else if($result->num_rows != 1){
    $conn->close();
    die("Unkown error!");
}

$row = $result->fetch_assoc();
$wid = (int)addslashes($row['wid']);

$sql = "delete from writeups where wid=$wid";

if($conn->query($sql) === TRUE){
    echo "true";
}else{
    echo "delete error!";
}

$conn->close();
?>