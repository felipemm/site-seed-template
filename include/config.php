<?php
    //============ DATABASE SETTINGS ============
	// LOCAL
	if ($_SERVER['SERVER_ADDR'] == "127.0.0.1") {
		//development environment
		define('MYSQL_HOSTNAME', 'localhost');
		define('MYSQL_USERNAME', 'root');
		define('MYSQL_PASSWORD', '');
		define('MYSQL_DATABASE', 'investmatic');

		define('SITE_BASE_FOLDER', '/projects/new');  //use relative paths
		define('SITE_BASE_URL', 'http://localhost/projects/new/');
		define('UPLOAD_BASE_FOLDER', '/uploads'); //put relative path based on site base folder

	// LIVE SERVER
	} else {
		//production environment
		define('MYSQL_HOSTNAME', '');
		define('MYSQL_USERNAME', '');
		define('MYSQL_PASSWORD', '');
		define('MYSQL_DATABASE', '');

		define('SITE_BASE_FOLDER', '');
		define('SITE_BASE_URL', '');
		define('UPLOAD_BASE_FOLDER', 'uploads');
	}


    //============ SYSTEM MESSAGES ============


    //============ GLOBAL VARIABLES ============
    define('TABLENAME_PREFIX', '');
    define('USER_STATUS_APPROVED', 1);
    define('USER_STATUS_PENDING', 2);
    define('USER_STATUS_REJECTED', 3);
    define('USER_STATUS_IN_APPROVAL', 4);
    define('USER_STATUS_CANCELLED', 5);
    define('USER_STATUS_NO_PAYMENT', 6);
    define('USER_ADMIN', 1);
    define('USER_DEFAULT', 0);
    
    define('HEAD_TITLE', "new");
	
	define('JAVASCRIPT_LIBRARY_PATH', SITE_BASE_FOLDER.'/js');
	define('EXTJS_PATH', SITE_BASE_FOLDER.'/js/ext-4.2.1.883');
	define('CSS_LIBRARY_PATH', SITE_BASE_FOLDER.'/css');
	define('IMAGE_LIBRARY_PATH', SITE_BASE_FOLDER.'/img');
	define('AJAX_LIBRARY_PATH', SITE_BASE_FOLDER.'/ajax');
	define('ADMIN_PATH', SITE_BASE_FOLDER.'/admin');
?>