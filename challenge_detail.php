<?php
include_once('./config.php');

define('SELF_FILE', __FILE__);

if(!isset($_GET['cid'])){
    die();
}

$cid = (int)addslashes($_GET['cid']);

$conn = get_sql_conn();
$sql = "select cid, name, content, file from challenge where cid=$cid";
$result = $conn->query($sql);
$row = $result->fetch_assoc();
$row['isSolved'] = "false";

if(isset($_SESSION['uid']) && isset($_SESSION['user'])){
    $uid = (int)addslashes($_SESSION['uid']);
    $sql = "select sid from solved where uid=$uid and cid=$cid";
    $result = $conn->query($sql);
    if($result->num_rows == 1){
        $row['isSolved'] = "true";
    }
}

echo json_encode($row);

$conn->close();
?>

