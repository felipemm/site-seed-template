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
    include('include/config.php');
    include('include/database.php');
    include('include/functions.php');
	include('include/simple_html_dom.php');

	/*
	$xml = new SimpleXMLElement(file_get_contents("http://www.bmfbovespa.com.br/Pregao-Online/ExecutaAcaoAjax.asp?intEstado=1&CodigoPapel=PETR4"));
	$json = json_encode($xml);
	print_r($json);
	*/

	//retrieve action from GET
	$action = textSanitizer(isset($_GET['action']) && trim($_GET['action']) !== ""  ? trim($_GET['action']) : null);
	
	
	if($action){
		$sym = explode("|", $action);
		$list = array();
		
		if(array_count_values($sym) > 0){
			foreach($sym as $symbol){
				array_push($list, getFundamentusData($symbol));
			}
		}
		header('Content-Type: application/json');
		echo json_encode($list);
	}
	
	
	
	function getFundamentusData($symbol){
		// Create DOM from URL or file
		$html = file_get_html('http://www.fundamentus.com.br/detalhes.php?papel='.$symbol);

		$list = array();
		$label = '';
		foreach($html->find('td') as $element){
			if (substr($element->attr['class'],0,5) != 'nivel'){
				if (isset($element->attr['class']) && substr($element->attr['class'],0,5) == 'label'){
					$label = $element->last_child()->innertext;
				} else {
					if(substr($element->last_child()->innertext,0,1) == '<') //starting with '<' means it has another element within
						$list[$label] = $element->last_child()->last_child()->innertext;
					else 
						$list[$label] = $element->last_child()->innertext;
				}
			}
		}
		return $list;
	}
	
?>
