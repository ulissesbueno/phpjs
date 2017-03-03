<?php

	header('Access-Control-Allow-Origin: *');

	//error_reporting(0); // Impede mostrar
	// CAD DYNAMIC
	// Envio de dados para Objetos dinamicamente
	// Aceita POST - somente que condizem com alguma propriedade do objeto
	// Metodos reconhecidos devem conter prefixo ajax_
	// Retorno em Json ... header deve ser ajax
	
	// Só aceita TYPE JSON
	if( @!strpos(' '.$_SERVER['HTTP_ACCEPT'],'/json') ){
		//exit;
	}

	$root = "/";
	// Define variaveis
	$success = false;
	$message = '';
	$data = '';

	// confere padrão
	if( preg_match('/' . str_replace( '/','\/', $root ) . '\/([\w_\-]+)(\/([\w_\-]*)\/*(.*))*/i', $_SERVER['REQUEST_URI'], $matches) ){

		$class = $matches[1];
		$filename = "class/". $class .".class.php";

	    if( file_exists( $filename ) ){

	    	include_once($filename);

	    	if( !class_exists( $class ) ){

	    		$message = "Classe não existe";
				return_json();
	    	}


	    } else {
	    	$message = "Arquivo não existe";
			return_json();
	    }
		
		$object = new $class;

		if( array_key_exists( '2' , $matches) ){
			$method = $matches[3];
			if(method_exists( $object , $method )){

				$POST_JSON = json_decode( file_get_contents('php://input') );
				//print_r($POST_JSON);
				// percorre Post
				foreach( $POST_JSON as $index => $value ){
					//echo $index."\n";
					if( property_exists( $object, $index ) ){
						// Popula variaveis
						$object->$index = $value;
					}else{
						//$message = "Argumento $index inexistente";
						//return_json();
					}
				}

				$success = $object->$method( ...$POST_JSON->params );
				//$func = array($object, $method);
				//$success = call_user_func_array ( $func , $POST_JSON->params );
				$message = $object->msg;
				if( $success ){
					$data = array( 	'attributes' =>	get_object_vars($object),
									'methods'	 => get_class_methods($class),
									'result' 	 => $object->data );	
				} 

			} else {
				$message = "Metodo inexistente :". $method ;
				return_json();	
			}
		} else {
			$success = true;
			$data = array( 	'attributes' =>	get_object_vars($object),
							'methods'	 => get_class_methods($class) );
		}

	}

	return_json();

	// Função retona AJAX 
	function return_json(){
		global $success, $message, $data;

		if( isset($_REQUEST['od'])){
			if( $success ){
				echo json_encode( $data );
			}
		}else{
			// Retorno sempre em ajax
			echo json_encode( array( 	'success' 	=> $success,
										'message' 	=> utf8_encode( $message ),
										'data'		=> $data 
							)		);	
		}

		
		exit;
	}

