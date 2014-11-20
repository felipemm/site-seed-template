<?php
    define('AUTH_DB', 1);
    define('AUTH_FB', 2);
    define('AUTH_LDAP', 3);
    

    class Authenticator{
        public function __construct($auth_type){
            switch($auth_type){
                case AUTH_DB:
                    $auth = new DatabaseAuth();
            }
        }
    }


    class Auth{
        protected $username;
        protected $password;
        protected $keepSession;

        public function __construct($usr,$pwd,$keep){
            $this->username = $usr;
            $this->password = $pwd;
            $this->keepSession = $keep;
        }
        
    }

    class LDAPAuth Extends Auth{
    }

    class DatabaseAuth Extends Auth{

        protected $db;
        protected $table;
        
        
        public function __construct($db,$tbl,$usr,$pwd,$keep){
            parent::__construct($usr,$pwd,$keep);
            
            $this->db = $db;
            $this->table = $tbl;
        } 

        public function authenticate(){
            $connection = json_decode($this->db->connect(), true);
            
            if($connection['success']){
                //echo $this->username . ":".$this->password;
                //check if variables are not empty
                if($this->username != '' && $this->password != ''){
                    //Try yo get the user information in the database
                    $where['usuario_nick']  = $this->username;
                    $where['usuario_senha'] = sha1($this->password);
                    $fields[0] = '*';
                    $request = json_decode($this->db->request($this->table, $fields, $where, null));
                    
                    //check if user data is good to login
                    if($request->success && $request->result->num_records == 1 && $request->result->data[0]->status_id == USER_STATUS_APPROVED){
                        session_start();
                        $_SESSION['username']   = $request->result->data[0]->usuario_nick;
                        $_SESSION['userid']     = $request->result->data[0]->usuario_id;
                        $_SESSION['is_admin']    = $request->result->data[0]->usuario_admin;
                        $_SESSION['user_status'] = $request->result->data[0]->status_id;
                        $_SESSION['client_ip']   = get_ip_real();
                        
                        //create the cookie and store it in the user table if the user requested to remember the login
                        if($this->keepSession){
                            $randomNumber = rand(99,999999);  //RANDOM NUMBER TO SERVE AS A KEY
                            $token = dechex(($randomNumber*$randomNumber));  //CONVERT NUMBER TO HEXADECIMAL FORM
                            $timeNow = time()*60*60*24*365*30; 
                            $key = sha1($token . $randomNumber . $timeNow);
                            $query = "update ".$this->table." set usuario_token = '".$key."' where usuario_nick = '".$request->result->data[0]->usuario_nick."'";
							$this->db->requestSQL($query);
                            setcookie('investmatic_login', $key, time()+60*60*24*6000, "/");
                        }
                        
                        return json_encode(array('success'=>true,'usuario'=>$request->result->data[0]->usuario_nick,'usuario_id'=>$request->result->data[0]->usuario_id,"msg"=>"Seja bem vindo, ".$request->result->data[0]->usuario_nick));
                    } else {
                        //if(MYSQL_HOSTNAME == 'localhost'){
                        //	$_SESSION['user_name']   = 'felipeeee';
                        //	$_SESSION['user_id']     = 1;
                        //	$_SESSION['is_admin']    = 1;
                        //	$_SESSION['user_status'] = USER_STATUS_APPROVED;
                        //	echo json_encode(array('success'=>true,'usuario'=>$_SESSION['user_name'],"msg"=>"Seja bem vindo, ".$_SESSION['user_name']));	
                        //} else {
                            return json_encode(array('success'=>false,'usuario'=>'',"msg"=>"Usuário inexistente, inativo ou senha incorreta. Tente novamente."));				
                        //}
                    }
                } else {
                    echo 'else';
                    if(isset($_COOKIE['investmatic_login'])){
                        $token = $_COOKIE['investmatic_login'];
                        $where['usuario_token']  = $token;
                        $fields[0] = '*';
                        $request = json_decode($this->db->request($this->table, $fields, $where, null));
                        echo 'enstro';
                        //check if user data is good to login
                        if($request->success && $request->result->num_records == 1 && $request->result->data[0]->status_id == USER_STATUS_APPROVED){
                            session_start();
                            $_SESSION['username']   = $request->result->data[0]->usuario_nick;
                            $_SESSION['userid']     = $request->result->data[0]->usuario_id;
                            $_SESSION['is_admin']    = $request->result->data[0]->usuario_admin;
                            $_SESSION['user_status'] = $request->result->data[0]->status_id;
                            $_SESSION['client_ip']   = get_ip_real();
                            echo 'achou';
                        }
                        return json_encode(array('success'=>true,'usuario'=>$request->result->data[0]->usuario_nick,'usuario_id'=>$request->result->data[0]->usuario_id,"msg"=>"Seja bem vindo, ".$request->result->data[0]->usuario_nick));

                    } else {
                        return json_encode(array('success'=>false,'usuario'=>'',"msg"=>"Usuário e/ou senha não informados."));
                    }
                }
            }
       }
    }

    class FacebookAuth Extends Auth{
    }

?>