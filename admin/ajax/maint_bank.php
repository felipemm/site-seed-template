<?php
    include('../../include/config.php');
    include('../../include/database.php');
    include('../../include/functions.php');

    //database connection
    $db = new database(MYSQL_HOSTNAME, MYSQL_DATABASE, MYSQL_USERNAME, MYSQL_PASSWORD);
    $connection = json_decode($db->connect(), true);
	
	//define table variables
	$table = TABLENAME_PREFIX . 'banco';
	$fields = array('banco_id',
					'banco_numero',
					'banco_nome',
					'banco_url',
					'banco_imagem');
	$where = null;
	
	if($connection['success']){
		//retrieve action from GET
		$action = textSanitizer(isset($_GET['action']) && trim($_GET['action']) !== ""  ? trim($_GET['action']) : null);
		$id = textSanitizer(isset($_GET['id']) && trim($_GET['id']) !== ""  ? trim($_GET['id']) : null);

		//receive json payload
		$payload = json_decode(file_get_contents('php://input'),true);
		
		//check if an action informed
		if($action){

			switch(strtoupper($action)){
				case 'SEL':
					if($id) $where['banco_id'] = $id;
					echo $db->request($table, $fields, $where, null);
					break;
					
					
				case 'ADD':
					unset($fields);
					$fields['banco_numero']  = $payload['banco_numero'];
					$fields['banco_nome']    = $payload['banco_nome'];
					$fields['banco_url']     = $payload['banco_url'];
					$fields['banco_imagem']  = $payload['banco_imagem'];
					echo $db->add($table, $fields);
					break;						
					
				case 'UPD':
					unset($fields);
					$fields['banco_numero']  = $payload['banco_numero'];
					$fields['banco_nome']    = $payload['banco_nome'];
					$fields['banco_url']     = $payload['banco_url'];
					$fields['banco_imagem']  = $payload['banco_imagem'];
					$where['banco_id']       = $payload['banco_id'];
					echo $db->update($table, $fields, $where);
					break;
					
					
				case 'DEL':
					$where['banco_id'] = $payload['banco_id'];
					echo $db->remove($table, $where);
					break;
			}
		}
    }
?>