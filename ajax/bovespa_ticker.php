<?php
//==============================================================================
// Filename: $sys_dir/ajax/bovespa_ticker.php
// 
// Description: php script to gather latest quote from bovespa. 
// 
// INPUT: $action -> the symbol to get quote for (multiple values by |)
// 
// OUTPUT: Returns a JSON with the data.
//==============================================================================
    include('include/config.php');
    include('include/functions.php');


	//retrieve action from GET
	$action = textSanitizer(isset($_GET['action']) && trim($_GET['action']) !== ""  ? trim($_GET['action']) : null);
	
	
	if($action){
		$sym = explode("|", $action);
		$list = array();
		
		if(array_count_values($sym) > 0){
			foreach($sym as $symbol){
				$xml = new SimpleXMLElement(file_get_contents("http://www.bmfbovespa.com.br/Pregao-Online/ExecutaAcaoAjax.asp?intEstado=1&CodigoPapel=$symbol"));
				array_push($list, $xml);
			}
		}
		header('Content-Type: application/json');
		echo json_encode($list);
	}
?>
