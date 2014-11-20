<?php
    include('../../include/config.php');
    include('../../include/database.php');
    include('../../include/functions.php');

    //database connection
    $db = new database(MYSQL_HOSTNAME, MYSQL_DATABASE, MYSQL_USERNAME, MYSQL_PASSWORD);
    $connection = json_decode($db->connect(), true);
	
	//define table variables
	$table = TABLENAME_PREFIX . 'subsetor';
	$fields = array('subsetor_id',
					'setor_id',
					'subsetor_codigo',
					'subsetor_nome',
					'subsetor_descricao');
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
					if($id) $where['subsetor_id'] = $id;
					echo $db->request($table, $fields, $where, null);
					break;
					
					
				case 'SELCOMBO':
					$query = "select ss.subsetor_id
								 , ss.subsetor_codigo
								 , ss.subsetor_nome
								 , se.setor_id
								 , se.setor_codigo
								 , se.setor_nome
							from subsetor ss
							inner join setor se on ss.setor_id = se.setor_id";
					echo $db->requestSQL($query);
					break;
					
					
				case 'ADD':
					unset($fields);
					$fields['subsetor_codigo']    = $payload['subsetor_codigo'];
					$fields['subsetor_nome']      = $payload['subsetor_nome'];
					$fields['subsetor_descricao'] = $payload['subsetor_descricao'];
					$fields['setor_id']           = $payload['setor_id'];
					echo $db->add($table, $fields);
					break;						
					
				case 'UPD':
					unset($fields);
					$fields['subsetor_codigo']    = $payload['subsetor_codigo'];
					$fields['subsetor_nome']      = $payload['subsetor_nome'];
					$fields['subsetor_descricao'] = $payload['subsetor_descricao'];
					$fields['setor_id']           = $payload['setor_id'];
					$where['subsetor_id']         = $payload['subsetor_id'];
					echo $db->update($table, $fields, $where);
					break;
					
					
				case 'DEL':
					$where['subsetor_id'] = $payload['subsetor_id'];
					echo $db->remove($table, $where);
					break;
			}
		}
    }
?>