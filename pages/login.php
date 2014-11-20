<?php
	//session_start();
	if(isset($_SESSION['username'])){
		header('Location: '.SITE_BASE_URL);
	}
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
		<title><?php echo HEAD_TITLE . " - " . gettext("Login"); ?></title>

		<!-- LIBRARIES -->
        <!-- Bootstrap -->
        <link href="<?php echo JAVASCRIPT_LIBRARY_PATH; ?>/Bootstrap/3.3.1/css/bootstrap.min.css" rel="stylesheet">
        <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
          <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
          <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->    
    
		<!-- PAGE SPECIFIC RESOURCES -->
        <script type="text/javascript" charset="utf-8" src="<?php echo JAVASCRIPT_LIBRARY_PATH; ?>/cookies.js"></script>
		<script type="text/javascript" charset="utf-8" src="<?php echo JAVASCRIPT_LIBRARY_PATH; ?>/CryptoJS/rollups/sha1.js"></script>
        <link href="<?php echo CSS_LIBRARY_PATH; ?>/base.css" rel="stylesheet">
        </head>
    <body>
         <?php include("sketches/header_no_login.php"); ?>
        <div class="container">
            <div class="row">
                <div class="span9 center"><br><h1><p class="text-center">You are not logged in. Please sign in for access.</p></h1></div>
            </div>

            <div class="row">
                <div class="span9"></div>
                <hr>
                <div class="col-md-4 col-md-offset-4">
                    <div class="panel panel-default">
                        <div class="panel-heading"> <strong class="">Login</strong>

                        </div>
                        <div class="panel-body">
                            <form class="form-horizontal" role="form" method="POST" action="<?php echo SITE_BASE_URL; ?>ajax/authenticate.php">
                                <div class="form-group">
                                    <label for="inputText3" class="col-sm-3 control-label">Username</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" id="username" name="username" placeholder="Username" required="">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="inputPassword3" class="col-sm-3 control-label">Password</label>
                                    <div class="col-sm-9">
                                        <input type="password" class="form-control" id="password" name="password" placeholder="Password" required="">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-offset-3 col-sm-9">
                                        <div class="checkbox">
                                            <label class="">
                                                <input type="checkbox" class="" name="remember_me" id="remember_me">Remember me</label>
                                        </div>
                                    </div>
                                </div>
                                <input type="hidden" class="form-control" id="redirect_to" name="redirect_to" value="<?php echo $_SERVER["REQUEST_URI"]; ?>">
                                <div class="form-group last">
                                    <div class="col-sm-offset-3 col-sm-9">
                                        <button type="submit" class="btn btn-success btn-sm">Sign in</button>
                                        <button type="reset" class="btn btn-default btn-sm">Reset</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="panel-footer">Not Registered? <a href="<?php echo SITE_BASE_URL; ?>register" class="">Register here</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
        <script src="<?php echo JAVASCRIPT_LIBRARY_PATH; ?>/jQuery/jquery-2.1.1.js"></script>
        <!-- Include all compiled plugins (below), or include individual files as needed -->
        <script src="<?php echo JAVASCRIPT_LIBRARY_PATH; ?>/Bootstrap/3.3.1/js/bootstrap.min.js"></script>
    </body>
</html>