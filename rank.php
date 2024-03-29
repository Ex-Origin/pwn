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
    <title>pwn challenge - Rank</title>

    <!-- source_header -->
    <?php include_once(ROOT_DIR.'template/source_header.php'); ?>
</head>

<body>
    <div class="body-wrap boxed-container">

        <!-- header -->
        <?php include_once(ROOT_DIR.'template/header.php'); ?>

        <div class="container">
            <h2 class="text-center">Rank</h2>
            <?php
            $conn = get_sql_conn();
            $sql = "select * from (select a.nickname as nickname, a.comment as comment, count(b.sid) as solved, a.uid as uid from user as a left join solved as b on a.uid=b.uid group by a.nickname) as c order by solved desc, uid limit 100";
            $result = $conn->query($sql);
            ?>

            <table class="table table-hover table-bordered">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nickname</th>
                        <th>solved</th>
                        <th>Comment</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $length = $result->num_rows;
                    for($i = 0; $i < $length; $i++){
                        $row = $result->fetch_assoc();
                        echo "<tr>";
                        echo "<td>".(string)($i + 1)."</td>";
                        echo "<td>".htmlspecialchars($row['nickname'])."</td>";
                        echo "<td>".htmlspecialchars($row['solved'])."</td>";
                        echo "<td>".($row['comment'] ? htmlspecialchars($row['comment']) : "NULL")."</td>";
                        echo "</tr>";
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
