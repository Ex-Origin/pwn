<?php

include_once('../config.php');

define('SELF_FILE', __FILE__);

$error = "";

if(!isset($_GET['cid'])){
    $error .= ("<h3 class=\"error-wruteup\">Error argument!</h3>");
}

if(!$error && !isset($_SESSION['uid'])){
    $error .= ("<h3 class=\"error-wruteup\">You should login first!</h3>");
}

$conn = get_sql_conn();
$name = "NULL";

if(!$error && isset($_SESSION['uid']) && isset($_GET['cid'])){
    $cid = (int)addslashes($_GET['cid']);
    $uid = (int)addslashes($_SESSION['uid']);

    // Check the user whether to finished the challenge.
    $sql = "select b.name as name from solved as a join challenge as b on a.cid=b.cid where a.uid=$uid and b.cid=$cid";
    $result = $conn->query($sql);
    if($result->num_rows == 0){
        $error .= ("<h3 class=\"error-wruteup\">You haven't finished the challenge, so that you can't see these writeups.</h3>");
    }else if($result->num_rows != 1){
        $error .= ("<h3 class=\"error-wruteup\">Unkown error!</h3>");
    }else{
        $row = $result->fetch_assoc();
        $name = $row['name'];
    }
}

if ($error && isset($_GET['cid'])) {
    $cid = (int)addslashes($_GET['cid']);

    $sql = "select name from challenge where cid=$cid";
    $result = $conn->query($sql);

    if($result->num_rows == 1){
        $row = $result->fetch_assoc();
        $name = $row['name'];
    }
}


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

            <?php if($error){echo $error;}else{ ?>

            <div class="form-group">
                <label class="control-label" for="textarea">You can share your own write-up. ( <span style="color:green;">Support for markdown format</span> )</label>
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

where a.cid=$cid
order by c.time desc
";
                $result = $conn->query($sql);
            }
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
                            echo '<td><a href="'.(relative(SELF_FILE)).'user/writeups_content.php?wid='.$row['wid'].'">'.htmlspecialchars($row['nickname'])."</a></td>";
                            echo "<td>".$row['time']."</td>";
                            echo '<td><a href="'.(relative(SELF_FILE)).'user/writeups_content.php?wid='.$row['wid'].'">'."View</a></td>";
                            echo "</tr>";
                        }
                    }
                    ?>
                </tbody>
            </table>

            <?php } ?>
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
                    cid: <?php if(isset($_GET['cid'])){echo $cid;}else{echo -1;} ?>
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

<?php
$conn->close();
?>