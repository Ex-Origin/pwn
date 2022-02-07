<?php

include_once('./config.php');

define('SELF_FILE', __FILE__);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>pwn challenge - writeups</title>

    <!-- source_header -->
    <?php include_once(ROOT_DIR.'template/source_header.php'); ?>
</head>

<body>
    <div class="body-wrap boxed-container">

        <!-- header -->
        <?php include_once(ROOT_DIR.'template/header.php'); ?>

        <div class="container">
            <h2 class="text-center">Writeups</h2>
            <p>You can share write-up or exploit code in your profile, only players who also solved the same challenge are able to see them.</p>
            <?php
            $conn = get_sql_conn();
            $sql = "
select * from
(select b.name as name, count(c.wid) writeup, b.cid as cid
from solved as a 

right join challenge as b 
on a.cid=b.cid 

left join writeups as c
on a.sid=c.sid

group by b.name, b.cid
order by b.cid) e

order by writeup desc
";
            $result = $conn->query($sql);
            $conn->close();
            ?>

            <table class="table table-hover table-bordered">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Submit times</th>
                        <th> </th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if(isset($result)){
                        $length = $result->num_rows;
                        for($i = 0; $i < $length; $i++){
                            $row = $result->fetch_assoc();
                            echo "<tr>";
                            echo "<td>".(string)($i + 1)."</td>";
                            echo '<td><a href="'.(relative(SELF_FILE)).'user/writeups_challenge.php?cid='.$row['cid'].'">'.htmlspecialchars($row['name'])."</a></td>";
                            echo "<td>".htmlspecialchars($row['writeup'])."</td>";
                            echo '<td><a href="'.(relative(SELF_FILE)).'user/writeups_challenge.php?cid='.$row['cid'].'">'."View</a></td>";
                            echo "</tr>";
                        }
                    }
                    ?>
                </tbody>
            </table>


        </div>

        <!-- footer -->
        <?php include_once(ROOT_DIR.'template/footer.php'); ?>

        <!-- source_footer -->
        <?php include_once(ROOT_DIR.'template/source_footer.php'); ?>
    </div>
</body>

</html>
