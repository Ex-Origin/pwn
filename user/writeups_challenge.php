<?php

include_once('../config.php');

define('SELF_FILE', __FILE__);

if(!isset($_GET['cid'])){
    die("Error argument!");
}

if(!isset($_SESSION['uid'])){
    die("You should login first!");
}


$cid = (int)addslashes($_GET['cid']);
$uid = (int)addslashes($_SESSION['uid']);

$conn = get_sql_conn();
$sql = "select b.name as name from solved as a join challenge as b on a.cid=b.cid where a.uid=$uid and b.cid=$cid";
$result = $conn->query($sql);
if($result->num_rows == 0){
    die("You can share write-up or exploit code in your profile, only players who also solved the same challenge are able to see them.");
    $conn->close();
}else if($result->num_rows != 1){
    die("Unkown error!");
    $conn->close();
}

$row = $result->fetch_assoc();
$name = $row['name'];


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Writeups - <?php echo $name; ?></title>

    <!-- source_header -->
    <?php include_once(ROOT_DIR.'template/source_header.php'); ?>
</head>

<body>
    <div class="body-wrap boxed-container">

        <!-- header -->
        <?php include_once(ROOT_DIR.'template/header.php'); ?>

        <div class="container">
            <h2 class="text-center">Writeups - <?php echo $name; ?> - Submit</h2>
            <div class="form-group">
                <label class="control-label" for="textarea">You can share your own write-up.</label>
                <textarea class="form-control"  name="writeup" id="writeup" rows="6" placeholder="your writeup"></textarea>
            </div>

            <div class="form-group">
                            <label class="control-label">Captcha code</label>
                            <div class="control-label" style="width:200px">
                                <input type="text" class="form-control" id="captcha_code" name="captcha_code"
                                    placeholder="Captcha code">
                            </div>
                            <img class="captcha-code"
                                src="<?php echo (relative(SELF_FILE)); ?>template/captcha.php"
                                onclick="this.src='<?php echo (relative(SELF_FILE)); ?>template/captcha.php?'+Math.random();">
                        </div>

            <div class="form-group">
                <button class="btn btn-default" id="writeup-submit">Submit</button>
            </div>


            <h2 class="text-center">Writeups - <?php echo $name; ?></h2>
            <p>You can share write-up or exploit code in your profile, only players who also solved the same challenge are able to see them.</p>
            <?php
            if(isset($_SESSION['uid'])){
                $uid = (int)addslashes($_SESSION['uid']);
                $sql = "
select c.wid as wid, d.nickname as nickname, c.time as time
from solved as a 

join challenge as b 
on a.cid=b.cid 

join writeups as c
on a.sid=c.sid

join user as d
on a.uid=d.uid

where a.uid=$uid and a.cid=$cid
order by c.time
";
                $result = $conn->query($sql);
            }
            $conn->close();
            ?>

            <table class="table table-hover table-bordered">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nickname</th>
                        <th>time</th>
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
                            echo '<td><a href="'.(relative(SELF_FILE)).'user/writeups_content.php?wid='.$row['wid'].'">'.$row['nickname']."</a></td>";
                            echo "<td>".$row['time']."</td>";
                            echo '<td><a href="'.(relative(SELF_FILE)).'user/writeups_content.php?wid='.$row['wid'].'">'."View</a></td>";
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

    <script>
        $(document).ready(function () {
            $("#writeup-submit").click(function () {
                if ($("#writeup").val() == '' || $("#captcha_code").val() == '') {
                    $('#empty-content').modal('show');
                    return;
                }

                $.post("<?php echo (relative(SELF_FILE)); ?>user/writeup_submit.php", {
                    writeup: $("#writeup").val(),
                    captcha_code: $("#captcha_code").val(),
                    cid: <?php echo $cid; ?>
                },
                function (data, status) {
                    if (status == "success") {
                        if (data == "success") {
                            location.reload();
                        } else if (data == "captcha_code error") {
                            $('#captcha_code-error').modal('show');
                        } else {
                            alert(data);
                        }
                    }
                });
            });
        });
    </script>

<div class="modal fade" id="empty-content" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title error" id="myModalLabel">Submit Error</h4>
                </div>
                <div class="modal-body">Please check the available information you submitted before submitting it.</div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div>

    <div class="modal fade" id="captcha_code-error" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title error" id="myModalLabel">Captcha code Error</h4>
                </div>
                <div class="modal-body">The verification code you entered is incorrect.</div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div>
</body>

</html>