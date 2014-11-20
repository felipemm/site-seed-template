<?php
//==============================================================================
// Filename: $sys_dir/ajax/bovespa.php
// 
// Description: php script to handle maintenance of the bovespa quotes table. 
// 
// INPUT: $action -> used to force the load of a specific file (without file
//                      extension).
// 
// OUTPUT: Returns a JSON with a count of rows processed.
//==============================================================================
    include('../include/config.php');
    include('../include/database.php');
    include('../include/functions.php');

	/*
	$xml = new SimpleXMLElement(file_get_contents("http://www.bmfbovespa.com.br/Pregao-Online/ExecutaAcaoAjax.asp?intEstado=1&CodigoPapel=PETR4"));
	$json = json_encode($xml);
	print_r($json);
	*/

	//retrieve action from GET
	$action = textSanitizer(isset($_GET['action']) && trim($_GET['action']) !== ""  ? trim($_GET['action']) : null);

	//$download_path='C:/Users/LOGIC/Dropbox/PortableApps/PortableApps/XAMPP/App/xampp/htdocs/projects/new/downloads/';
	$download_path=realpath('../downloads/');
	
	switch($action){
		case null:
			$date = new DateTime();
			$date->add(DateInterval::createFromDateString('yesterday'));
			$date_str = $date->format('dmY');
			$zipfile='COTAHIST_D'.$date_str.'.ZIP';
			$txtfile='COTAHIST_D'.$date_str.'.TXT';
			downloadFile($download_path,$txtfile,$zipfile);
			loadStagingTable($download_path.$txtfile);
			loadQuotesTable();
			break;
		case "all":
			for($year=1986;$year<=2014;$year++){
				$zipfile='COTAHIST_A'.$year.'.ZIP';
				$txtfile='COTAHIST_A'.$year.'.TXT';
				downloadFile($download_path,$txtfile,$zipfile);
				loadStagingTable($download_path.$txtfile);
				loadQuotesTable();
			}
			break;
		default:
			$zipfile=$action.'.ZIP';
			$txtfile=$action.'.TXT';		
			downloadFile($download_path,$txtfile,$zipfile);
			loadStagingTable($download_path.$txtfile);
			loadQuotesTable();
			break;
	}
	
	
	
	function downloadFile($path,$txtfile,$zipfile){
		if(!file_exists($path.$txtfile)){
			try{
				file_put_contents($path.$zipfile, fopen("http://www.bmfbovespa.com.br/InstDados/SerHist/$zipfile", 'r'));
				$zip = new ZipArchive();
				$x = $zip->open($path.$zipfile);
				if ($x === true) {
					$zip->extractTo($path);
					$zip->close();
					unlink($path.$zipfile);
				}
			} catch (Exception $e) {
				echo $e;
				exit;
			}
		}
	}
	
	function loadStagingTable($filename){
		//database connection
		$db = new database(MYSQL_HOSTNAME, MYSQL_DATABASE, MYSQL_USERNAME, MYSQL_PASSWORD);
		$connection = json_decode($db->connect(), true);
		
		if($connection['success']){	
			$query = "LOAD DATA INFILE '".$filename."'
					INTO TABLE bovespa_history_stg
					FIELDS TERMINATED BY ''
					LINES TERMINATED BY '\r\n';";
			echo $db->execSQL($query);
			
			$query = "delete from bovespa_history_stg where tipreg <> '01';";
			$db->execSQL($query);
			
			$query = "select count(*) from bovespa_history_stg;";
			echo $db->requestSQL($query);
		}
	}
	
	function loadQuotesTable(){
		//database connection
		$db = new database(MYSQL_HOSTNAME, MYSQL_DATABASE, MYSQL_USERNAME, MYSQL_PASSWORD);
		$connection = json_decode($db->connect(), true);
		
		if($connection['success']){	
			//$query = "truncate bovespa_history_stg;";
			//$db->execSQL($query);
		}
	}
?>
