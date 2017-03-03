<?php

class MyDB extends SQLite3
{
    function __construct( $basename )
    {
        if($basename) $this->open($basename);
    }
}

class chat  {
	
	var $msg = '';
	var $data = '';

	function __construct(){

		

	}

	var $username = '';

	var $chatname = '';
	var $dir_base = 'SQLite';

	function enter( $post ){
		
		$post = $this->ConvertPost( $post );
		$basename = $this->dir_base."/".$post['chat'].'.db';

		$db = new MyDB($basename);
		
		$db->exec("CREATE TABLE IF NOT EXISTS chat( `date` DATETIME,
													`name` VARCHAR(50),
													`message` TEXT,
													`read` TEXT
													 )");
	

		$this->chatname = $post['chat'];
		$this->username = $post['nickname'];
		
		return true;
		
	}

	function ConvertPost( $request ){
		$post = [];
		foreach ($request as $r) {
			$post[ $r->name ] = $r->value;
		}
		return $post;
	}
	

	function messages(){
		
		$basename = $this->dir_base."/".$this->chatname.'.db';
		$db = new MyDB($basename);

		$result = $db->query("SELECT strftime('%Y/%m/%d %H:%M:%S', a.`date`) as `date`, a.name, a.message FROM chat a");

		$this->data = '';
		while($row = $result->fetchArray(SQLITE3_ASSOC)){ 
			$this->data[] = array_values($row);
		} 

		return true;
	}

	function send( $message ){
		
		$basename = $this->dir_base."/".$this->chatname.'.db';
		$db = new MyDB($basename);

		$ok = $db->exec("INSERT INTO chat ( `date`, name, message ) VALUES ( datetime('now'), '".$this->username."', '". $message ."' )");
		return $ok;
	}

	function exit(){

		$basename = $this->dir_base."/".$this->chatname.'.db';
		unlink( $basename );

		return true;

	}
	
}