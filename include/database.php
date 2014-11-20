<?php
//==============================================================================
// CLASS database
// Description: php class that handles all database side access.
//
// USER			DATE		DESCRIPTION
// felipemm		2012-08-27	initial version
//==============================================================================
class database{

	var $hostname;
	var $database;
	var $username;
	var $password;
	var $conexao;


	//==============================================================================
	// FUNCTION: __construct
	// DESCRIPTION: initialize the class
	//==============================================================================
	public function __construct($host, $db, $user, $pwd){
		$this->hostname = $host;
		$this->database = $db;
		$this->username = $user;
		$this->password = $pwd;
	}



	//==============================================================================
	// FUNCTION: connect
	// DESCRIPTION: make the connection to the database.
	//==============================================================================
	public function connect(){
		//try to connect to the database
		$this->conexao = mysql_connect($this->hostname, $this->username, $this->password);

		//if connection is made, try to select the database
		if($this->conexao){
			if(mysql_select_db ($this->database, $this->conexao)){
				mysql_query("SET NAMES 'utf8'");
				mysql_query('SET character_set_connection=utf8');
				mysql_query('SET character_set_client=utf8');
				mysql_query('SET character_set_results=utf8');
				return json_encode(array('success'=>true,'result'=>'{}',"msg"=>"Conexão com o banco bem sucedida"));
			}
		}
		return json_encode(array('success'=>false,'result'=>'{}',"msg"=>mysql_error()));
	}



	//==============================================================================
	// FUNCTION: createWhereClause
	// DESCRIPTION: create the where clause to be used in the sql statements.
	//==============================================================================
	public function createWhereClause($where){

		if(count($where) > 0){
			$i = 0;
			$filter = array();
			foreach($where as $field_name => $field_value){
				//if value is an array, it means we are passing the operator as well
				if(is_array($field_value)){
					$key = array_keys($field_value);
					$filter[$i] = strtolower($field_name) . " " . $key[0] . " " . $field_value[$key[0]];
				} else {
					if($field_value === 'null'){
						$filter[$i] = strtolower($field_name) . " is null";
					} else {
						if(in_array(substr($field_value, 0, 2), array('> ', '< ', '>=', '<='))){
							$filter[$i] = strtolower($field_name) . $field_value;
						} else {
							if(in_array(substr($field_value, 0,11),array('STR_TO_DATE'))){
								$filter[$i] = strtolower($field_name) . " = " . $field_value;
							} else {
								$filter[$i] = strtolower($field_name) . " = '" . $field_value . "'";
							}
						}
					}
				}
				$i++;
			}

			$where_clause = " WHERE ". $filter[0];
			for($j=1;$j<$i;$j++){
				if(in_array(substr($filter[$j], 0, 2), array('OR', 'or'))){
					$where_clause .= " " . $filter[$j];
				} else {
					$where_clause .= " AND ". $filter[$j];
				}
			}
			return $where_clause;
		}
		return "";
	}



	//==============================================================================
	// FUNCTION: createSQL
	// DESCRIPTION: create the sql statement based on the type of DML selected.
	//==============================================================================
	public function createSQL($table, $fields, $where, $sort, $group, $limit, $type){

		switch(strtoupper($type)){

			case "SELECT":
				if(count($fields) > 0){
					$select = "";
					foreach($fields as $name => $value){
						$select .= $value . ",";
					}
					$select = substr($select,0,-1);
				}
				$query = "SELECT ". $select ." FROM ". $table . $this->createWhereClause($where);
				//add sort option
				if(isset($sort) && count($sort) > 0){
					$s = " ORDER BY ";
					foreach($sort as $name => $value){
						$s .= $name . " " . $value . ",";
					}
					$s = substr($s,0,-1);
					$query .= $s;
				}
				//add group option
				if(isset($group) && count($group) > 0){
					$grp = " GROUP BY ";
					foreach($group as $name => $value){
						$grp .= $value . ",";
					}
					$grp = substr($s,0,-1);
					$query .= $grp;
				}
				//add limit option
				if(isset($limit) && $limit > 0){
					$query .= ' limit 0,'.$limit;
				}
				break;


			case "INSERT":
				if(count($fields) > 0){
					$column_name = "";
					$column_value = "";
					foreach($fields as $name => $value){
						if(in_array(substr($value, 0,11),array('STR_TO_DATE'))){
							$column_name .= $name . ",";
							$column_value .= $value . ",";
						} else {
							$column_name .= $name . ",";
							$column_value .= "'" . $value . "',";
						}
					}
					$column_name = substr($column_name,0,-1);
					$column_value = substr($column_value,0,-1);
				}
				$query = "INSERT INTO ". $table . "(" . $column_name . ") VALUES (" . $column_value . ")";
				break;


			case "UPDATE":
				if(count($fields) > 0){
					$update = "";
					foreach($fields as $name => $value){
						$update .= $name . " = '" . $value . "',";
					}
					$update = substr($update,0,-1);
				}
				$query = "UPDATE ". $table . " SET " . $update . $this->createWhereClause($where);
				break;


			case "DELETE":
				$query = "DELETE FROM ". $table . $this->createWhereClause($where);
				break;
		}
		return $query;
	}

	
	//==============================================================================
	// FUNCTION: execSQL
	// DESCRIPTION: execute a DML statement in the database and return a status
	//==============================================================================
	public function execSQL($query){
		if($this->conexao){
			$result = @mysql_query($query);
			//if the result is populated, format the resultset and encode it as JSON for output
			if($result){
				$num = mysql_affected_rows();
				//create json array
				$json = array();
				$json['success'] = true;
				$json['query'] = $query;
				$json['result'] = $result;
				$json['msg'] = "REQUEST OK";
			} else {
				//create json array
				$json = array();
				$json['success'] = false;
				$json['query'] = $query;
				$json['result'] = null;
				$json['msg'] = mysql_error();
			}
		} else {
			//create json array
			$json = array();
			$json['success'] = false;
			$json['query'] = null;
			$json['result'] = null;
			$json['msg'] = "NOT CONNECTED TO DATABASE";
		}
		//return encoded JSON object
		return json_encode($json);
	}
	
	
	//==============================================================================
	// FUNCTION: request
	// DESCRIPTION: execute a SELECT statement in the database and return a JSON
	//              representation of the resultset.
	//==============================================================================
	public function requestSQL($query){
		if($this->conexao){
			$result = @mysql_query($query);
			//if the result is populated, format the resultset and encode it as JSON for output
			if($result){
				$num = mysql_affected_rows();
				//create json array
				$json = array();
				$json['success'] = true;
				$json['query'] = $query;
				$json['result'] = $this->formatResultSet($result, $num);
				$json['msg'] = "REQUEST OK";
			} else {
				//create json array
				$json = array();
				$json['success'] = false;
				$json['query'] = $query;
				$json['result'] = null;
				$json['msg'] = mysql_error();
			}
		} else {
			//create json array
			$json = array();
			$json['success'] = false;
			$json['query'] = null;
			$json['result'] = null;
			$json['msg'] = "NOT CONNECTED TO DATABASE";
		}
		//return encoded JSON object
		return json_encode($json);
	}


	//==============================================================================
	// FUNCTION: request
	// DESCRIPTION: execute a SELECT statement in the database and return a JSON
	//              representation of the resultset.
	//==============================================================================
	public function request($table, $fields, $where, $sort = null, $group = null, $limit = null){
		//if the connection is alive, execute the query
		if($this->conexao){
			//create the SELECT statement
			$query = $this->createSQL($table, $fields, $where, $sort, $group, $limit, "SELECT");
			//execute the query in the database
			$result = @mysql_query($query);
			//if the result is populated, format the resultset and encode it as JSON for output
			if($result){
				$num = mysql_affected_rows();

				//create json array
				$json = array();
				$json['success'] = true;
				$json['query'] = $query;
				$json['result'] = $this->formatResultSet($result, $num);
				$json['msg'] = "REQUEST OK";

			} else {
				//create json array
				$json = array();
				$json['success'] = false;
				$json['query'] = $query;
				$json['result'] = null;
				$json['msg'] = mysql_error();
			}
		} else {
			//create json array
			$json = array();
			$json['success'] = false;
			$json['query'] = null;
			$json['result'] = null;
			$json['msg'] = "NOT CONNECTED TO DATABASE";
		}
		//return encoded JSON object
		return json_encode($json);
	}



	//==============================================================================
	// FUNCTION: add
	// DESCRIPTION: execute an insert statement in the database and return a JSON
	//              with the execution status.
	//==============================================================================
	public function add($table, $values){
		//if the connection is alive, execute the query
		if($this->conexao){
			//create the INSERT statement
			$query = $this->createSQL($table, $values, null, null, null, null, "INSERT");
			//execute the query in the database
			$result = @mysql_query($query);
			//if the result is populated, format the resultset and encode it as JSON for output
			if($result){
				$fields[0] = '*';
				$query = $this->createSQL($table, $fields, $values, null, null, null, "SELECT");
				$result = @mysql_query($query);
				if($result){
					$num = mysql_affected_rows();

					//create json array
					$json = array();
					$json['result'] = $this->formatResultSet($result, $num);
					$json['query'] = $query;
					$json['success'] = true;
					$json['msg'] = "INSERT OK";
				} else {
					//create json array
					$json = array();
					$json['success'] = false;
					$json['query'] = $query;
					$json['msg'] = mysql_error();
				}
			} else {
				//create json array
				$json = array();
				$json['success'] = false;
				$json['query'] = $query;
				$json['msg'] = mysql_error();
			}
		} else {
			//create json array
			$json = array();
			$json['success'] = false;
			$json['msg'] = "NOT CONNECTED TO DATABASE";
		}
		//return encoded JSON object
		return json_encode($json);
	}



	//==============================================================================
	// FUNCTION: update
	// DESCRIPTION: execute a SELECT statement in the database and return a JSON
	//              with the execution status.
	//==============================================================================
	public function update($table, $values, $where){
		//if the connection is alive, execute the query
		if($this->conexao){
			//create the UPDATE statement
			$query = $this->createSQL($table, $values, $where, null, null, null, "UPDATE");
			//execute the query in the database
			$result = @mysql_query($query);
			//if the result is populated, format the resultset and encode it as JSON for output
			if($result){
				$fields[0] = '*';
				$query2 = $this->createSQL($table, $fields, $where, null, null, null, "SELECT");
				$result = @mysql_query($query2);
				if($result){
					$num = mysql_affected_rows();

					//create json array
					$json = array();
					$json['result'] = $this->formatResultSet($result, $num);
					$json['query'] = $query;
					$json['success'] = true;
					$json['msg'] = "UPDATE OK";
				} else {
					//create json array
					$json = array();
					$json['success'] = false;
					$json['query'] = $query;
					$json['msg'] = mysql_error();
				}
			} else {
				//create json array
				$json = array();
				$json['success'] = false;
				$json['msg'] = mysql_error();
			}
		} else {
			//create json array
			$json = array();
			$json['success'] = false;
			$json['msg'] = "NOT CONNECTED TO DATABASE";
		}
		//return encoded JSON object
		return json_encode($json);
	}



	//==============================================================================
	// FUNCTION: remove
	// DESCRIPTION: execute a DELETE statement in the database and return a JSON
	//              with the execution status.
	//==============================================================================
	public function remove($table, $where){
		//if the connection is alive, execute the query
		if($this->conexao){
			//create the DELETE statement
			$query = $this->createSQL($table, null, $where, null, null, null, "DELETE");
			//execute the query in the database
			$result = @mysql_query($query);
			//if the result is populated, format the resultset and encode it as JSON for output
			if($result){
				//create json array
				$json = array();
				$json['success'] = true;
				$json['msg'] = "DELETE OK";

			} else {
				//create json array
				$json = array();
				$json['success'] = false;
				$json['msg'] = mysql_error();
			}
		} else {
			//create json array
			$json = array();
			$json['success'] = false;
			$json['msg'] = "NOT CONNECTED TO DATABASE";
		}
		//return encoded JSON object
		return json_encode($json);
	}



	//==============================================================================
	// FUNCTION: formatResultSet
	// DESCRIPTION: format the resultset returned from a mysql request and format
	//              it as an array.
	//==============================================================================
	public function formatResultSet($resultSet,$affectedRecords){

		$array_fields = array();
		$array_data = array();

		//populate the fields section with the column names returned from mysql request
		for($i=0; $i < mysql_num_fields($resultSet); $i++){
			$field = mysql_fetch_field($resultSet, $i);
			if($field){
				$array_fields[$i] = $field->name;
			}
		}

		//populate the data section row by row
		$j = 0;
		while($row = mysql_fetch_array($resultSet, MYSQL_NUM)) {
			//for each field, create an array with the column name as index
			for($r=0; $r<count($array_fields); $r++){
				$datarow[$array_fields[$r]] = $row[$r];
			}
			$array_data[$j] = $datarow;
			$j++;
		}

		//populate return array
		$result['fields'] = $array_fields;
		$result['data'] = $array_data;
		$result['num_records'] = $affectedRecords;

		return $result;
	}
}


/*
$db = new database("localhost","fmm_correios_ml","root","");

$result = json_decode($db->connect());

if($result->success){
	$table = "fmm_user";

	/* TEST FOR REQUEST DATA
	$fields[0] = "user_id";
	$fields[1] = "user_name";

	$where['user_id'] = '1';

	$request = $db->request($table, $fields, $where);
	//var_dump(json_decode($request));
	var_dump($request);
	*/

	/* TEST FOR ADDING DATA
	$values['user_name'] = 'usr1';
	$values['user_password'] = sha1('12qwAS');
	$values['user_email'] = 'admin@felipematos.com';
	$values['user_token'] = sha1($values['user_email']);
	$values['user_cep'] = '13020110';
	$values['status_id'] = 2;
	$values['user_admin'] = 'N';

	$insert = json_decode($db->add($table, $values));
	var_dump($insert);
	*/

	/* TEST FOR UPDATING DATA
	$values['user_name'] = 'usr2';
	$values['user_admin'] = 'S';

	$where['user_id'] = '2';

	$update = json_decode($db->update($table, $values, $where));
	var_dump($update);
	*/

	/* TEST FOR DELETING DATA
	$where['user_id'] = '2';

	$delete = json_decode($db->remove($table, $where));
	var_dump($delete);

}
*/



?>