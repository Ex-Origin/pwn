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
    <title>challenge</title>
    
    <!-- source_header -->
    <?php include_once(ROOT_DIR.'template/source_header.php'); ?>
</head>

<body>
    <!-- header -->
    <?php include_once(ROOT_DIR.'template/header.php'); ?>

    <div class="container">
        <h1 class="text-center">Challenge</h1>
        <div>
            <?php
            $conn = get_sql_conn();

            $user_solved = array();

            if(isset($_SESSION['uid']) && isset($_SESSION['user'])){
                $sql = "select cid from solved where uid=" . addslashes((string)$_SESSION['uid']);
                $user_result = $conn->query($sql);
                if ($user_result->num_rows > 0) {
                    while($row = $user_result->fetch_assoc()) {
                        array_push($user_solved, $row['cid']);
                    }
                }
            }
            $sql = "select name,times,cid from (select a.name as name, count(b.sid) as times, a.cid as cid from challenge as a left join solved as b on a.cid=b.cid group by a.name) as c order by times desc";
            $result = $conn->query($sql);
            $length = $result->num_rows;

            $conn->close();
            for($i = 0; $i < ceil($length/4); $i ++){
            ?>
            <div class="row">
                <?php
                for($ii = 0; $ii < 4; $ii++){
                    if($row = $result->fetch_assoc()){
                        if($user_solved && in_array($row['cid'], $user_solved)){
                            echo '<div class="col-md-3 col-lg-3 bg-success challenge" cid="'.$row['cid'].'">';
                        }else{
                            echo '<div class="col-md-3 col-lg-3 bg-primary challenge" cid="'.$row['cid'].'">';
                        }
                        echo '<h3 class="text-center">'.htmlspecialchars($row['name']).'</h3>';
                        echo '<h5 class="text-center">solved: '.htmlspecialchars($row['times']).' times</h5>';
                        echo '</div>';
                    }else{
                        break;
                    }
                }
                ?>
            </div>
            <?php } ?>
        </div>
    </div>

    <!-- footer -->
    <?php include_once(ROOT_DIR.'template/footer.php'); ?>
    
    <!-- source_footer -->
    <?php include_once(ROOT_DIR.'template/source_footer.php'); ?>

    <script> 
    $(document).ready(function(){
        $(".challenge").click(function(){
            $.getJSON("<?php echo (relative(SELF_FILE)); ?>challenge_detail.php", {cid: $(this).attr("cid")},function(data){
                $("#challenge-info").attr("cid", data.cid);
                $("#challenge-name").text(data.name);
                $("#challenge-content").html(data.content);
                $("#challenge-file").html(data.file.replace(" ", "<br/>"));
                if(data.isSolved == "true"){
                    $("#challenge-flag").hide();
                    $("#challenge-solved").show();
                }else{
                    $("#challenge-flag").show();
                    $("#challenge-solved").hide();
                }
                
                $('#challenge-info').modal('show');
            });
        });

        $("#flag-submit").click(function(){
            $.post("<?php echo (relative(SELF_FILE)); ?>flag.php",{
                cid:$("#challenge-info").attr("cid"),
                flag:$("#flag-content").val()
            },
            function(data, status){
                if(data == "success"){
                    $("#challenge-flag").hide();
                    $("#challenge-solved").show();
                }else if(data == "failed"){
                    alert("flag error");
                }else{
                    alert(data);
                }
            });
        });
    });
    </script>

    <div class="modal fade" id="challenge-info" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title error text-center" id="challenge-name"></h4>
                </div>
                <div class="modal-body">
                    <div id="challenge-content"></div>
                    <div id="challenge-file"></div>
                    <div id="challenge-flag">

<div style="padding: 100px 100px 10px;">
    <div class="bs-example bs-example-form" role="form">
        <div class="row">
            <div class="">
                <div class="input-group">
                    <input type="text" placeholder="flag" id="flag-content" class="form-control">
                    <span class="input-group-btn">
                        <button class="btn btn-default" id="flag-submit" type="button">Submit</button>
                    </span>
                </div><!-- /input-group -->
            </div>
        </div><!-- /.row -->
    </div>
</div>

                    </div>
                    <div id="challenge-solved">You had solved this challenge.</div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div>
</body>

</html>
