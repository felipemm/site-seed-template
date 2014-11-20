<?php
    include('../../include/config.php');
    include('../../include/database.php');
    include('../../include/functions.php');

    //database connection
    $db = new database(MYSQL_HOSTNAME, MYSQL_DATABASE, MYSQL_USERNAME, MYSQL_PASSWORD);
    $connection = json_decode($db->connect(), true);
	
	//define table variables
	$table = TABLENAME_PREFIX . 'setor';
	$fields = array('setor_id',
					'setor_codigo',
					'setor_nome',
					'setor_descricao');
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
					if($id) $where['setor_id'] = $id;
					echo $db->request($table, $fields, $where, null);
					break;
					
					
				case 'ADD':
					unset($fields);
					$fields['setor_codigo']    = $payload['setor_codigo'];
					$fields['setor_nome']      = $payload['setor_nome'];
					$fields['setor_descricao'] = $payload['setor_descricao'];
					echo $db->add($table, $fields);
					break;						
					
				case 'UPD':
					unset($fields);
					$fields['setor_codigo']    = $payload['setor_codigo'];
					$fields['setor_nome']      = $payload['setor_nome'];
					$fields['setor_descricao'] = $payload['setor_descricao'];
					$where['setor_id']         = $payload['setor_id'];
					echo $db->update($table, $fields, $where);
					break;
					
					
				case 'DEL':
					$where['setor_id'] = $payload['setor_id'];
					echo $db->remove($table, $where);
					break;
			}
		}
    }
?>