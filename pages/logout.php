<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
		<title><?php echo HEAD_TITLE . " - " . gettext("Logout"); ?></title>
    </head>
    <body>
		<script>
			JAVASCRIPT_LIBRARY_PATH = '<?php echo JAVASCRIPT_LIBRARY_PATH; ?>';
			EXTJS_PATH = '<?php echo EXTJS_PATH; ?>';
			CSS_LIBRARY_PATH = '<?php echo CSS_LIBRARY_PATH; ?>';
			IMAGE_LIBRARY_PATH = '<?php echo IMAGE_LIBRARY_PATH; ?>';
			AJAX_LIBRARY_PATH = '<?php echo AJAX_LIBRARY_PATH; ?>';
			SITE_BASE_URL = '<?php echo SITE_BASE_URL; ?>';
			
			page_action = '<?php echo strtoupper($action); ?>';
			page_value_id = '<?php echo strtoupper($value); ?>';

			//eraseCookie('investmatic'); //elimina o cookie criado para fazer autologin, senão não faz o logout corretamente
		</script>		
    </body>
</html>
<?php
	session_unset();
	session_destroy();
	echo "<META HTTP-EQUIV='REFRESH' CONTENT=\"0; URL='./'\">";
    if (isset($_COOKIE['investmatic_login'])) {
        unset($_COOKIE['investmatic_login']);
        setcookie('investmatic_login', null, -1, '/');
    }
?>