<?php

class MyDB extends SQLite3
{
    function __construct()
    {
        $this->open('system.db');
    }
}

class system  {
	
	var $msg = '';
	var $data = '';

	function __construct(){

		$db = new MyDB();
		$db->exec('CREATE TABLE IF NOT EXISTS customer( name VARCHAR(100))');

	}

	function getCustomer(){
		$db = new MyDB();
		$result = $db->query('SELECT * FROM customer');

		while($row = $result->fetchArray(SQLITE3_ASSOC)){ 
			$this->data[] = array_values($row);
		} 

		return true;
	}

	function save( $table, $param ){
		
		$param = (array) $param;
		$db = new MyDB();
		$ok = $db->exec("INSERT INTO ".$table." ( ". implode(',' , array_keys($param)) ." ) VALUES ( '". implode("','" , array_values($param)) ."' )");
		return $ok;
	}		
	
}