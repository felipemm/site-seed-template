<?php
	include('../include/config.php');
    include('../include/database.php');
    include('../include/functions.php');
    include('../classes/Authenticator.class.php');

    //database connection
    $db = new database(MYSQL_HOSTNAME, MYSQL_DATABASE, MYSQL_USERNAME, MYSQL_PASSWORD);
    $table = TABLENAME_PREFIX . 'usuario';

    //retrieve username and password from request
    $username = !empty($_POST['username']) ? addslashes(trim($_POST['username'])) : '';
    $password = !empty($_POST['password']) ? addslashes($_POST['password']) : '';
    $redirect_to = !empty($_POST['redirect_to']) ? addslashes($_POST['redirect_to']) : (!empty($_GET['redirect_to']) ? addslashes($_GET['redirect_to']):'');
    $remember_me = !empty($_POST['remember_me']) ? addslashes($_POST['remember_me']) : '';
        
    $auth = new DatabaseAuth($db, $table, $username, $password, $remember_me);
    $response =  json_decode($auth->authenticate(),true);
    
    if($response["success"]){
        header("Location: $redirect_to");
    } else {
        header("Location: ".SITE_BASE_URL."/login");
    }
    print_r($response);
?>