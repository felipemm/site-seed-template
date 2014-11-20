<?php
    include('../../include/config.php');
    include('../../include/database.php');
    include('../../include/functions.php');

    //database connection
    $db = new database(MYSQL_HOSTNAME, MYSQL_DATABASE, MYSQL_USERNAME, MYSQL_PASSWORD);
    $connection = json_decode($db->connect(), true);
	
	//define table variables
	$table = TABLENAME_PREFIX . 'ativo';
	$fields = array('ativo_id',
					'ativo_simbolo',
					'ativo_empresa',
					'ativo_ipo_data',
					'ativo_lote_padrao',
					'ativo_imagem',
					'subsetor_id');
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
					if($id) $where['ativo_id'] = $id;
					echo $db->request($table, $fields, $where, null);
					break;
					
					
				case 'ADD':
					unset($fields);
					$fields['ativo_simbolo']     = $payload['ativo_simbolo'];
					$fields['ativo_empresa']     = $payload['ativo_empresa'];
					$fields['ativo_ipo_data']    = $payload['ativo_ipo_data'];
					$fields['ativo_lote_padrao'] = $payload['ativo_lote_padrao'];
					$fields['ativo_imagem']      = $payload['ativo_imagem'];
					$fields['subsetor_id']       = $payload['subsetor_id'];
					echo $db->add($table, $fields);
					break;						
					
				case 'UPD':
					unset($fields);
					$fields['ativo_simbolo']     = $payload['ativo_simbolo'];
					$fields['ativo_empresa']     = $payload['ativo_empresa'];
					$fields['ativo_ipo_data']    = $payload['ativo_ipo_data'];
					$fields['ativo_lote_padrao'] = $payload['ativo_lote_padrao'];
					$fields['ativo_imagem']      = $payload['ativo_imagem'];
					$fields['subsetor_id']       = $payload['subsetor_id'];
					$where['ativo_id']           = $payload['ativo_id'];
					echo $db->update($table, $fields, $where);
					break;
					
					
				case 'DEL':
					$where['ativo_id'] = $payload['ativo_id'];
					echo $db->remove($table, $where);
					break;
			}
		}
    }
?>