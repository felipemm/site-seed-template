<?php
	session_start();

	include 'include/config.php';
	include 'include/database.php';
	include 'include/functions.php';
	include 'include/i18n.php';
	
	ini_set('default_charset', 'utf-8');
	
	//These 3 values will be populated based on the provided in the URL using mod_rewrite (check .htaccess for more info)
	$page = isset($_GET['page']) && $_GET['page'] != "" ? $_GET['page'] : ""; //the php page to redirect the request
	$action = isset($_GET['action']) && $_GET['action'] != "" ? $_GET['action'] : ""; //the action to be done in the selected page
	$value = isset($_GET['value']) && $_GET['value'] != "" ? $_GET['value'] : ""; //the argument to be used in conjuction with the action
	$value2 = isset($_GET['value2']) && $_GET['value2'] != "" ? $_GET['value2'] : ""; //the argument to be used in conjuction with the action
	$value3 = isset($_GET['value3']) && $_GET['value3'] != "" ? $_GET['value3'] : ""; //the argument to be used in conjuction with the action

    //If the page is not provided, it means we need to redirect to the main page, otherwise redirect to provided URI
    if($page != ""){
        if(file_exists("pages/$page.php")){
            //if page is selected, but no user is logged into, redirect to login page
            if(isset($_SESSION['username']) || in_array($page,array('main','register','about','contact'))){
                include "pages/$page.php";
            } else {
                if(isset($_COOKIE['investmatic_login'])){
                    header('Location: '.SITE_BASE_URL.'ajax/authenticate.php');
                } else {
                    //force a login
                    $action = 'login';
                    include "pages/login.php";
                }
            }
        } else {
            include "pages/404.php";
        }
    } else {
        include "pages/main.php";
    }


?>