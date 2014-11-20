<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
    <head>
		<script type="text/javascript" src="https://getfirebug.com/firebug-lite.js"></script>
		<meta http-equiv="x-ua-compatible" content="IE=EmulateIE8"/>
		<title>Logic Service Desk</title>

		<!-- EXTJS 4.1.0 LOADER -->
		<link rel="stylesheet" type="text/css" href="<?php echo ADMIN_PATH ?>/js/extjs/resources/ext-theme-neptune/ext-theme-neptune-all.css"" />
        <script type="text/javascript" charset="utf-8" src="<?php echo ADMIN_PATH; ?>/js/extjs/ext-all.js"></script>
        
		<!-- PAGE SPECIFIC RESOURCES -->
		<script type="text/javascript" charset="utf-8" src="<?php echo ADMIN_PATH; ?>/js/common.js"></script>
		<script type="text/javascript" charset="utf-8" src="<?php echo ADMIN_PATH; ?>/js/accordion.js"></script>
		<script type="text/javascript" charset="utf-8" src="<?php echo ADMIN_PATH; ?>/js/main.js"></script>
        <link rel="stylesheet" type="text/css" href="<?php echo ADMIN_PATH; ?>/css/main.css" />

    </head>
    <body>
		<script>
            IMAGE_LIBRARY_PATH = '<?php echo SITE_BASE_URL."/imgs"; ?>';
			AJAX_LIBRARY_PATH = '<?php echo ADMIN_PATH."/ajax"; ?>';
			SITE_BASE_URL = '<?php echo SITE_BASE_URL; ?>';
			ADMIN_PATH = '<?php echo ADMIN_PATH; ?>';
			
			page_action = '<?php echo strtoupper($action); ?>';
			page_value_id = '<?php echo strtoupper($value); ?>';
		</script>

    </body>
</html>