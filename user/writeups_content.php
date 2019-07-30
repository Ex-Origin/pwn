<?php

include_once('../config.php');

define('SELF_FILE', __FILE__);

if(!isset($_GET['wid'])){
    die("Error argument!");
}

if(!isset($_SESSION['uid'])){
    die("You should login first!");
}


$wid = (int)addslashes($_GET['wid']);
$uid = (int)addslashes($_SESSION['uid']);

$conn = get_sql_conn();

$sql = "
select c.writeup as writeup, b.name as name, d.nickname as nickname, c.time as time, a.cid as cid
from solved as a 

join challenge as b 
on a.cid=b.cid 

join writeups as c
on a.sid=c.sid

join user as d
on a.uid=d.uid

where a.uid=$uid and c.wid=$wid
";

$result = $conn->query($sql);
if($result->num_rows == 0){
    die("The article does not exist!");
    $conn->close();
}else if($result->num_rows != 1){
    die("Unkown error!");
    $conn->close();
}

$row = $result->fetch_assoc();
$name = $row['name'];
$nickname = $row['nickname'];
$time = $row['time'];
$writeup = $row['writeup'];
$cid = (int)addslashes($row['cid']);

$sql = "select b.name as name from solved as a join challenge as b on a.cid=b.cid where a.uid=$uid and b.cid=$cid";
$result = $conn->query($sql);
if($result->num_rows == 0){
    die("You can share write-up or exploit code in your profile, only players who also solved the same challenge are able to see them.");
    $conn->close();
}else if($result->num_rows != 1){
    die("Unkown error!");
    $conn->close();
}

$conn->close();

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Writeups - <?php echo $name; ?> by <?php echo htmlspecialchars($nickname); ?></title>

    <!-- source_header -->
    <?php include_once(ROOT_DIR.'template/source_header.php'); ?>
</head>

<body>
    <div class="body-wrap boxed-container">

        <!-- header -->
        <?php include_once(ROOT_DIR.'template/header.php'); ?>

        <div class="container">
            <h2 class="text-center">Writeups - <?php echo $name; ?></h2>
            <p class="author text-right">Author: <?php echo htmlspecialchars($nickname); ?></p>
            <p class="text-right">Submit time: <?php echo $time; ?></p>
            <article class="markdown" style="min-height:20em;"><?php echo htmlspecialchars($writeup); ?></article>

        </div>

        <!-- source_footer -->
        <?php include_once(ROOT_DIR.'template/source_footer.php'); ?>
    </div>
</body>

</html>