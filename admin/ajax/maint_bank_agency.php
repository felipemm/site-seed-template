<?php
    include('../../include/config.php');
    include('../../include/database.php');
    include('../../include/functions.php');

    //database connection
    $db = new database(MYSQL_HOSTNAME, MYSQL_DATABASE, MYSQL_USERNAME, MYSQL_PASSWORD);
    $connection = json_decode($db->connect(), true);
	
	//define table variables
	$table = TABLENAME_PREFIX . 'agencia';
	$fields = array('agencia_id',
					'agencia_numero',
					'agencia_nome',
					'agencia_endereco',
					'agencia_telefone',
					'banco_id');
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
					if($id) $where['agencia_id'] = $id;
					echo $db->request($table, $fields, $where, null);
					break;
					
					
				case 'ADD':
					unset($fields);
					$fields['agencia_numero']   = $payload['agencia_numero'];
					$fields['agencia_nome']     = $payload['agencia_nome'];
					$fields['agencia_endereco'] = $payload['agencia_endereco'];
					$fields['agencia_telefone'] = $payload['agencia_telefone'];
					$fields['banco_id']         = $payload['banco_id'];
					echo $db->add($table, $fields);
					break;						
					
				case 'UPD':
					unset($fields);
					$fields['agencia_numero']   = $payload['agencia_numero'];
					$fields['agencia_nome']     = $payload['agencia_nome'];
					$fields['agencia_endereco'] = $payload['agencia_endereco'];
					$fields['agencia_telefone'] = $payload['agencia_telefone'];
					$fields['banco_id']         = $payload['banco_id'];
					$where['agencia_id']        = $payload['agencia_id'];
					echo $db->update($table, $fields, $where);
					break;
					
					
				case 'DEL':
					$where['agencia_id'] = $payload['agencia_id'];
					echo $db->remove($table, $where);
					break;
			}
		}
    }
?>