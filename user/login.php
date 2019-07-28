<?php

include_once('../config.php');

define('SELF_FILE', __FILE__);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>pwn challenge - login</title>

    <!-- source_header -->
    <?php include_once(ROOT_DIR.'template/source_header.php'); ?>
</head>

<body>
    <!-- header -->
    <?php include_once(ROOT_DIR.'template/header.php'); ?>

    <div class="container">
        <div class="row">
            <div class="col-md-4 col-lg-4"></div>
            <div class="col-md-4 col-lg-4">
                <div class="form-horizontal">
                    <div class="form-group">
                        <label for="inputEmail3" class="col-sm-4 control-label">Email</label>
                        <div class="col-sm-8">
                            <input type="email" class="form-control" id="email" name="email" placeholder="Email">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="inputPassword3" class="col-sm-4 control-label">Password</label>
                        <div class="col-sm-8">
                            <input type="password" class="form-control" id="password" name="password" placeholder="Password">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="inputPassword3" class="col-sm-4 control-label">Captcha code</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="captcha_code" name="captcha_code" placeholder="Captcha code">
                        </div>
                        <img class="col-sm-offset-4 col-sm-8" src="<?php echo (relative(SELF_FILE)); ?>template/captcha.php" onclick="this.src='<?php echo (relative(SELF_FILE)); ?>template/captcha.php?'+Math.random();">
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-4 col-sm-8">
                            <button type="submit" class="btn btn-default" id="signInButton">Sign in</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4 col-lg-4"></div>
        </div>

    </div>

    <!-- footer -->
    <?php include_once(ROOT_DIR.'template/footer.php'); ?>

    <!-- source_footer -->
    <?php include_once(ROOT_DIR.'template/source_footer.php'); ?>

    <script> 
    $(document).ready(function(){
        $("#signInButton").click(function(){
            if($("#email").val() == '' || $("#password").val() == ''){
                $('#empty-content').modal('show');
                return;
            }

            $.post("<?php echo (relative(SELF_FILE)); ?>user/login_submit.php",{
                email:$("#email").val(),
                password:$("#password").val(),
                captcha_code:$("#captcha_code").val()
            },
            function(data,status){
                if(status == "success"){
                    if(data == "success"){
                        window.location.href = "<?php echo (relative(SELF_FILE)); ?>index.php";
                    }else if(data == "failed"){
                        $('#login-failed').modal('show');
                    }else if(data == "captcha_code error"){
                        $('#captcha_code-error').modal('show');
                    }else{
                        alert("Unknow error!");
                    }
                }
            });
        });
    });
    </script>

    <div class="modal fade" id="empty-content" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
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

    <div class="modal fade" id="login-failed" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title error" id="myModalLabel">Login Failed</h4>
                </div>
                <div class="modal-body">Please enter a correct email and password. Note that both fields may be case-sensitive. </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div>

    <div class="modal fade" id="captcha_code-error" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
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