<?php
    include('../../include/config.php');
    include('../../include/database.php');
    include('../../include/functions.php');
	
    //database connection
    $db = new database(MYSQL_HOSTNAME, MYSQL_DATABASE, MYSQL_USERNAME, MYSQL_PASSWORD);
    $connection = json_decode($db->connect(), true);
	
	//define table variables
	$table = TABLENAME_PREFIX . 'usuario';
	$fields = array('usuario_id',
					'usuario_nick',
					'usuario_nome',
					'usuario_email',
					'usuario_telefone',
					'usuario_facebook',
					'usuario_twitter',
					'usuario_senha',
					'usuario_foto',
					'usuario_admin',
					'status_id');
	$where = null;

	
	if($connection['success']){
		//retrieve action from GET
		$action = textSanitizer(isset($_GET['action']) && trim($_GET['action']) !== ""  ? trim($_GET['action']) : null);
		$id = textSanitizer(isset($_GET['id']) && trim($_GET['id']) !== ""  ? trim($_GET['id']) : null);

		//receive json payload
		$payload = json_decode(file_get_contents('php://input'),true);
		//print_r($payload);
		
		//check if an action informed
		if($action){
			//execute the requested action
			switch(strtoupper($action)){
				case 'SEL':
					if($id) $where['usuario_id'] = $id;
					echo $db->request($table, $fields, $where);
					break;

					
					
				case 'ADD':
					//check if user already exists in the database
					$where['usuario_email'] = textSanitizer($payload['usuario_email']);
					$check_exists = json_decode($db->request($table, $fields, $where), true);

					//print_r($check_exists);
					if($check_exists['result']['num_records'] == 0){
						unset($fields);
						$fields['usuario_nome']     = textSanitizer($payload['usuario_nome']);
						$fields['usuario_nick']     = textSanitizer($payload['usuario_nick']);
						$fields['usuario_email']    = textSanitizer($payload['usuario_email']);
						$fields['usuario_telefone'] = textSanitizer($payload['usuario_telefone']);
						$fields['usuario_facebook'] = textSanitizer($payload['usuario_facebook']);
						$fields['usuario_twitter']  = textSanitizer($payload['usuario_twitter']);
						$fields['usuario_senha']    = sha1($payload['usuario_senha']);
						$fields['usuario_admin']    = textSanitizer($payload['usuario_admin']);
						$fields['status_id']        = textSanitizer($payload['status_id']);
						$result = json_decode($db->add($table, $fields),true);
						
						//print_r($result);
						//if user updated, upload photo and update table
						if($result['result']['num_records'] > 0){
							//upload base paths and urls. Create the folders if it doesn't exist
							$extensions = array("jpeg","jpg","png","gif");
							$img_path = UPLOAD_BASE_FOLDER . '/' . $result['result']['data'][0]['usuario_id'] . '/user_photo/';
							if(!is_dir($img_path)){
								mkdir($img_path , 0777, true);
							}
							//confirm that the directory exists
							if(is_dir($img_path)){
								//for each file submitted during the post, move the the folder and update the table
								foreach($_FILES as $key=>$value){	
									if(is_uploaded_file($_FILES[$key]['tmp_name']) && $_FILES[$key]['error'] == 0){
										//set the filename for the file
										$filename = $_FILES[$key]['name'];
										$filename = time().rand(0,999).$filename;

										//get the file extension and validate if it's an image
										$file_ext=explode('.',$_FILES[$key]['name']);
										$file_ext=end($file_ext);
										$file_ext=strtolower(end(explode('.',$_FILES[$key]['name'])));
										if(in_array($file_ext,$extensions)){
											if(move_uploaded_file($_FILES[$key]['tmp_name'], $img_upload_base_path . $filename)){
												//ADD THE USER ROW WITH THE PATH URL TO THE USER PHOTO
												unset($fields);
												$fields['usuario_foto'] = addslashes($img_relative_path . $filename);
												$where['usuario_id'] = $result['result']['data'][0]['usuario_id'];
												echo $db->update($table, $fields,$where);
											} else {
												echo json_encode(array('success'=>false,'result'=>array(),"msg"=>"Houve um problema com o upload da foto. Tente novamente"));
											}
										} else {
											echo json_encode(array('success'=>false,'result'=>array(),"msg"=>"Extensão da foto não permitida!". $file_ext));
										}
									} else {
										echo json_encode(array('success'=>false,'result'=>array(),"msg"=>"Houve um problema com a foto enviada!"));
									}
								}
							}
						} else {
							echo json_encode(array('success'=>false,'result'=>$result,"msg"=>"Usuário não foi adicionado devido a um erro!"));
						}
					} else {
						echo json_encode(array('success'=>false,'result'=>array(),"msg"=>"Usuário já existente!"));
					}
					break;



				case 'UPD':
					$where['usuario_id'] = textSanitizer($payload['usuario_id']);
					$result = json_decode($db->request($table, $fields, $where), true);
					
					if($result['result']['num_records'] > 0){
						//upload base paths and urls. Create the folders if it doesn't exist
						$extensions = array("jpeg","jpg","png","gif");
						$img_relative_path = UPLOAD_BASE_FOLDER . '/' . $result['result']['data'][0]['usuario_id'] . '/user_photo/';
						$img_upload_base_path = SITE_BASE_FOLDER . '/' . $img_relative_path;
						if(!is_dir($img_upload_base_path)){
							mkdir($img_upload_base_path , 0777, true);
						}
						//confirm that the directory exists
						if(is_dir($img_upload_base_path)){
							//for each file submitted during the post, move the the folder and update the table
							foreach($_FILES as $key=>$value){	
								if(is_uploaded_file($_FILES[$key]['tmp_name']) && $_FILES[$key]['error'] == 0){
									//set the filename for the file
									$filename = $_FILES[$key]['name'];
									$filename = time().rand(0,999).$filename;

									//get the file extension and validate if it's an image
									$file_ext=explode('.',$_FILES[$key]['name']);
									$file_ext=end($file_ext);
									$file_ext=strtolower(end(explode('.',$_FILES[$key]['name'])));
									if(in_array($file_ext,$extensions)){
										if(move_uploaded_file($_FILES[$key]['tmp_name'], $img_upload_base_path . $filename)){
											//generate a thumbnail of the image to show in the gallery
											$thumb_filename = createThumbnail($img_upload_base_path . $filename, 50);
											if($thumb_filename <> 'Failed'){
												//ADD THE USER ROW WITH THE PATH URL TO THE USER PHOTO
												unset($fields);
												$fields['usuario_foto_path'] = addslashes($img_relative_path . $filename);
												$fields['usuario_foto_thumb_path'] = addslashes($img_relative_path . $thumb_filename);
												$where['usuario_id'] = $result['result']['data'][0]['usuario_id'];
												//array_push($result, json_decode($db->update($table, $fields),true));
												//echo json_decode($result);
												$db->update($table, $fields,$where);
											} else {
												echo json_encode(array('success'=>false,'result'=>array(),"msg"=>"Houve um problema ao gerar o thumbnail da foto!"));
												exit();
											}
										} else {
											echo json_encode(array('success'=>false,'result'=>array(),"msg"=>"Houve um problema com o upload da foto. Tente novamente"));
											exit();
										}
									} else {
										echo json_encode(array('success'=>false,'result'=>array(),"msg"=>"Extensão da foto não permitida!". $file_ext));
										exit();
									}
								} else {
									echo json_encode(array('success'=>false,'result'=>array(),"msg"=>"Houve um problema com a foto enviada!"));
									exit();
								}
							}
						}
						unset($fields);
						unset($where);

						$fields['usuario_nick']     = textSanitizer($payload['usuario_nick']);
						$fields['usuario_nome']     = textSanitizer($payload['usuario_nome']);
						$fields['usuario_email']    = textSanitizer($payload['usuario_email']);
						$fields['usuario_facebook'] = textSanitizer($payload['usuario_facebook']);
						$fields['usuario_twitter']  = textSanitizer($payload['usuario_twitter']);
						$fields['usuario_telefone'] = textSanitizer($payload['usuario_telefone']);
						if(isset($payload['usuario_senha']) && trim($payload['usuario_senha']) !== ""){
							$fields['usuario_senha']    = sha1($payload['usuario_senha']);
						}
						if(isset($payload['status_id']) && trim($payload['status_id']) !== "") $fields['status_id'] = $payload['status_id'];
						if(isset($payload['usuario_admin']) && trim($payload['usuario_admin']) !== "") $fields['usuario_admin'] = $payload['usuario_admin'];
						$where['usuario_id'] = textSanitizer($payload['usuario_id']);
						echo $db->update($table, $fields, $where);
					} else {
						echo json_encode($result,true);
					}
					break;					

				
				
				case 'DEL':
					if($usuario_id == $session_user_id || $session_is_admin){					
						$where['usuario_id'] = $usuario_id;
						echo $db->remove($table, $where);
					}
					break;					

				
				
				case 'DELPHOTO':
					if($usuario_id == $session_user_id || $session_is_admin){					
						$fields[0] = '*';
						$where['usuario_id'] = $usuario_id;
						$result = json_decode($db->request($table, $fields, $where), true);
						
						if($result['result']['num_records'] > 0){
							$img_filename = SITE_BASE_FOLDER . '/' . $result['result']['data'][0]['usuario_foto_path'];
							$thumb_filename = SITE_BASE_FOLDER . '/' . $result['result']['data'][0]['usuario_foto_thumb_path'];
							if(unlink($img_filename) && unlink($thumb_filename)){
								unset($fields);
								unset($where);
								$fields['usuario_foto_path'] = '';
								$fields['usuario_foto_thumb_path'] = '';
								$where['usuario_id'] = $usuario_id;
								echo $db->update($table, $fields, $where);
							}
						}
					}
					break;	
			}
		}
    }
?>