<?php
    include('../include/config.php');
    include('../include/database.php');
    include('../include/functions.php');
	
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
					//'usuario_senha',
					'usuario_foto',
					'usuario_admin',
					'status_id');
	$where = null;

	
	if($connection['success']){
		//retrieve action from GET
		$action = textSanitizer(isset($_POST['action']) && trim($_POST['action']) !== ""  ? trim($_POST['action']) : null);
		$id = textSanitizer(isset($_POST['id']) && trim($_POST['id']) !== ""  ? trim($_POST['id']) : null);

		//receive json payload
		//$payload = json_decode(file_get_contents('php://input'),true);
		$payload = $_POST;
		print_r($payload);
		
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
            $fields['usuario_foto']     = textSanitizer($payload['usuario_foto']);
            $fields['usuario_twitter']  = textSanitizer($payload['usuario_twitter']);
            $fields['usuario_senha']    = sha1($payload['usuario_senha']);
            $fields['usuario_admin']    = USER_DEFAULT;
            $fields['status_id']        = USER_STATUS_APPROVED;
            $result = json_decode($db->add($table, $fields),true);
            
            //print_r($result);
            //if user updated, upload photo and update table
            if($result['result']['num_records'] > 0){
                //upload base paths and urls. Create the folders if it doesn't exist
                $extensions = array("jpeg","jpg","png","gif");
                $img_path = UPLOAD_BASE_FOLDER . '/' . $result['result']['data'][0]['usuario_id'] . '/user_photo/';
                if(!is_dir($_SERVER['DOCUMENT_ROOT'] . $img_path)){
                    mkdir($_SERVER['DOCUMENT_ROOT'] . $img_path , 0777, true);
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
                                if(move_uploaded_file($_FILES[$key]['tmp_name'], $_SERVER['DOCUMENT_ROOT'] . $img_path . $filename)){
                                    //ADD THE USER ROW WITH THE PATH URL TO THE USER PHOTO
                                    unset($fields);
                                    $fields['usuario_foto'] = addslashes($img_path . $filename);
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
    }
?>