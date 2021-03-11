<?php
include_once('../config.php');

define('SELF_FILE', __FILE__);

if(!isset($_SESSION['uid']))
{
    die("You should login first");
}

$uid = (int)addslashes($_SESSION['uid']);
$conn = get_sql_conn();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>PWN Challenge - profile</title>

    <!-- source_header -->
    <?php include_once(ROOT_DIR.'template/source_header.php'); ?>
</head>

<body>
    <div class="body-wrap boxed-container">

        <!-- header -->
        <?php include_once(ROOT_DIR.'template/header.php'); ?>

        <div class="container">
            <h1 class="text-center">Profile</h1>
            <hr />
            <div>
                <?php
                $sql = "select email, nickname, comment, register_time, count(sid) as times from user a left join solved b on a.uid=b.uid where a.uid=$uid";
                $result = $conn->query($sql);

                if($result->num_rows != 1){
                    $conn->close();
                    die("Unknown Error");
                }

                $row = $result->fetch_assoc();
                $comment = htmlspecialchars($row['comment']);
                ?>

                <div class="row">
                    <div class="col-md-3 col-lg-3"></div>
                    <div class="col-md-6 col-lg-6" style="text-align:center">
                        <table class="profile">
                            <tr>
                                <td>Email: </td>
                                <td><?php echo htmlspecialchars($row['email']); ?></td>
                            </tr>
                            <tr>
                                <td>Nickname: </td>
                                <td><?php echo htmlspecialchars($row['nickname']); ?></td>
                            </tr>
                            <tr>
                                <td>Comment:</td>
                                <td><?php echo $comment ? $comment : "NULL"; ?></td>
                            </tr>
                            <tr>
                                <td>Register time: </td>
                                <td><?php echo htmlspecialchars($row['register_time']); ?> </td>
                            </tr>
                            <tr>
                                <td>Solved times: </td>
                                <td><?php echo htmlspecialchars($row['times']); ?></td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-3 col-lg-3"></div>


                </div>

                <p class="text-center" style="margin-top:2em;"><button type="submit" class="btn btn-default"
                        id="edit-profile-button">Edit Profile</button></p>
            </div>

            <h1 class="text-center">Solved Challenge</h1>
            <hr />
            <?php
            $sql = "select b.name as name, a.time as time, b.cid as cid  from solved a right join challenge b on a.cid=b.cid where a.uid=$uid order by a.time";
            $result = $conn->query($sql);
            ?>

            <table class="table table-hover table-bordered" style="margin-bottom:6em;">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Solved time</th>
                        <th>writeup</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $length = $result->num_rows;
                    for($i = 0; $i < $length; $i++){
                        $row = $result->fetch_assoc();
                        echo "<tr>";
                        echo "<td>".(string)($i + 1)."</td>";
                        echo "<td>".htmlspecialchars($row['name'])."</td>";
                        echo "<td>".htmlspecialchars($row['time'])."</td>";
                        echo '<td class="writeup-add"><a href="'.(relative(SELF_FILE)).'user/writeups_challenge.php?cid='.$row['cid'].'">'." + Add</a></td>";
                        echo "</tr>";
                    }
                    ?>
                </tbody>
            </table>

            <h1 class="text-center">Submited Writeup</h1>
            <hr />
            <?php
            $sql = "select b.name as name, a.time as time, c.wid as wid  
                    from solved a right join challenge b on a.cid=b.cid join writeups c on a.sid=c.sid
                    where a.uid=$uid order by a.time";
            $result = $conn->query($sql);
            ?>

            <table class="table table-hover table-bordered" style="margin-bottom:6em;">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Submited time</th>
                        <th>Operator</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $length = $result->num_rows;
                    for($i = 0; $i < $length; $i++){
                        $row = $result->fetch_assoc();
                        echo "<tr>";
                        echo "<td>".(string)($i + 1)."</td>";
                        echo "<td>".htmlspecialchars($row['name'])."</td>";
                        echo "<td>".htmlspecialchars($row['time'])."</td>";
                        echo '<td class="writeup-add"><a href="'.(relative(SELF_FILE)).'user/writeups_content.php?wid='.$row['wid'].'">'.
                            '<button class="btn btn-default" id="registerButton">View</button></a> '.
                            '<a><button class="btn btn-default delete-button" wid="'.$row['wid'].'">Delete</button></a></td>';
                        echo "</tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>

        <!-- source_footer -->
        <?php include_once(ROOT_DIR.'template/source_footer.php'); ?>

    </div>

    <script>
        $(document).ready(function () {
            $("#edit-profile-button").click(function () {
                $("#edit-profile").modal("show");
            });

            $("#edit-profile-submit").click(function () {
                if ($("#new-password").val() != $("#confirm-password").val()) {
                    alert("The new password is different from comfirm password, please check again!");
                    return;
                }

                var length = $("#new-password").val().length;
                if (length != 0 && length < 6) {
                    alert("Password can not be empty for at least six!");
                    return;
                }


                $.post("<?php echo (relative(SELF_FILE)); ?>user/profile_submit.php", {
                    comment: $("#comment").val(),
                    current_password: $("#current-password").val(),
                    new_password: $("#new-password").val(),
                    captcha_code: $("#captcha_code").val()
                },
                    function (data, status) {
                        alert(data);
                    });
            });

            $(".delete-button").click(function () {
                var id = $(this).attr("wid");
                $.post("<?php echo (relative(SELF_FILE)); ?>user/writeup_delete.php", {
                    wid: id
                },
                    function (data, status) {
                        if(data == "true"){
                            alert("Delete successfully.");
                            window.location.reload(true);
                        }else if(data == "false"){
                            alert("Delete failed!");
                        }else{
                            alert("Unknown Error!");
                        }
                    });
            });
        });
    </script>

    <div class="modal fade" id="edit-profile" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title text-center">Edit Profile</h4>
                </div>
                <div class="modal-body">
                    <div class="form-horizontal">
                        <div class="form-group">
                            <label class="col-sm-4 control-label">Comment</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="comment" name="comment"
                                    value="<?php echo $comment ? $comment : "NULL"; ?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-4 control-label">Current Password</label>
                            <div class="col-sm-8">
                                <input type="password" class="form-control" id="current-password"
                                    name="current-password" placeholder="Current Password">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-4 control-label">New Password</label>
                            <div class="col-sm-8">
                                <input type="password" class="form-control" id="new-password" name="new-password"
                                    placeholder="New Password">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-4 control-label">Confirm Password</label>
                            <div class="col-sm-8">
                                <input type="password" class="form-control" id="confirm-password"
                                    name="confirm-password" placeholder="Confirm Password">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-4 control-label">Captcha code</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="captcha_code" name="captcha_code"
                                    placeholder="Captcha code">
                            </div>
                            <img class="col-sm-offset-4 col-sm-8 captcha-code"
                                src="<?php echo (relative(SELF_FILE)); ?>template/captcha.php"
                                onclick="this.src='<?php echo (relative(SELF_FILE)); ?>template/captcha.php?'+Math.random();">
                        </div>
                        <div class="form-group">
                            <div class="col-sm-offset-4 col-sm-8">
                                <button type="submit" class="btn btn-default" id="edit-profile-submit">submit</button>
                            </div>
                        </div>
                    </div>
                </div>
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