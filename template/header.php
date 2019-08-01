
<nav class="navbar" style="border-width: 0 0 2px;border-color: #666;">
  <div class="container-fluid container">
    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed navbar-default" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" href="<?php echo (relative(SELF_FILE)); ?>index.php">PWN Challenge</a>
    </div>

    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
      <ul class="nav navbar-nav">
        <li><a href="<?php echo (relative(SELF_FILE)); ?>challenge.php">Challenge</a></li>
        <li><a href="<?php echo (relative(SELF_FILE)); ?>rank.php">Rank</a></li>
        <li><a href="<?php echo (relative(SELF_FILE)); ?>writeups.php">Writeups</a></li>
      </ul>
      <ul class="nav navbar-nav navbar-right">
      <?php if(isset($_SESSION['user'])){ ?>
        <li><a href="<?php echo (relative(SELF_FILE)); ?>user/profile.php"><?php echo htmlspecialchars($_SESSION['user']); ?></a></li>
        <li><a href="<?php echo (relative(SELF_FILE)); ?>user/logout.php">Logout</a></li>
      <?php }else{?>
        <li><a href="<?php echo (relative(SELF_FILE)); ?>user/login.php">Login</a></li>
        <li><a href="<?php echo (relative(SELF_FILE)); ?>user/register.php">Register</a></li>
      </ul>
      <?php } ?>
    </div><!-- /.navbar-collapse -->
  </div><!-- /.container-fluid -->
</nav>

