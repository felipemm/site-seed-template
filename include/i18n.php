<?php
	if (isset($_GET["locale"])) {
		$locale = $_GET["locale"];
	} else if (isset($_SESSION["locale"])) {
		$locale = $_SESSION["locale"];
	} else if (isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])){
		$locale = str_replace("-","_",substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 5));
	} else {
		$locale = "pt_BR";
	}
	
	putenv("LANG=" . $locale);
	putenv("LC_ALL=" . $locale);
	setlocale(LC_ALL, $locale);

	$domain = "investimatic";
	bindtextdomain($domain, "locale");
	bind_textdomain_codeset($domain, 'UTF-8');
	textdomain($domain);
    $_SESSION["locale"] = $locale;
?>