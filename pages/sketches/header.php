<nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="#">Investmatic</a>
        </div>
        <?php if(!isset($_SESSION['username'])){?>
            <div id="navbar" class="navbar-collapse collapse">
                <form class="navbar-form navbar-right" role="form" method="POST" action="ajax/authenticate.php">
                    <a class="btn btn-success" href="<?php echo SITE_BASE_URL."register"; ?>">Create Account</a>
                    <div class="form-group">
                        <input type="text" class="form-control" id="username" name="username" placeholder="Username" required="">
                    </div>
                    <div class="form-group">
                        <input type="password" class="form-control" id="password" name="password" placeholder="Password" required="">
                    </div>
                    <input type="hidden" class="form-control" id="redirect_to" name="redirect_to" value="<?php echo $_SERVER["REQUEST_URI"]; ?>">
                    <button type="submit" class="btn btn-success">Sign in</button>
                    <div class="checkbox form-group text-warning">
                        <label class=""><input type="checkbox" class="" name="remember_me" id="remember_me">Remember me</label>
                    </div>
                </form>
            </div><!--/.navbar-collapse -->
        <?php } else { ?>
            <!-- Single button -->
            <div class="btn-group navbar-form navbar-right">
              <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                Welcome back, <?php echo $_SESSION['username']; ?> <span class="caret"></span>
              </button>
              <ul class="dropdown-menu" role="menu">
                <li><a href="<?php echo SITE_BASE_URL."user/".$_SESSION['username']; ?>">My Account</a></li>
                <li><a href="<?php echo SITE_BASE_URL."dashboard/".$_SESSION['username']; ?>">My Dashboard</a></li>
                <?php if(isset($_SESSION['username']) && $_SESSION['is_admin'] == 1){ ?>
                    <li><a href="<?php echo SITE_BASE_URL."admin"; ?>">Administration</a></li>
                <?php } ?>
                <li class="divider"></li>
                <li><a href="<?php echo SITE_BASE_URL."logout"; ?>">Logout</a></li>
              </ul>
            </div>
        <?php } ?>
    </div>
</nav>
