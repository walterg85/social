<?php
	session_start();
	
	require_once '../models/user.php';

	header("Access-Control-Allow-Origin: *");
	header("Access-Control-Allow-Methods: POST");

	if($_SERVER['REQUEST_METHOD'] == 'POST'){
		$put_vars 	= ($_POST) ? $_POST : json_decode(file_get_contents("php://input"), TRUE);
		$userModel	= new Usersmodel();

		if($put_vars['_method'] == 'POST'){
			$put_vars['inputPassword'] 	= encryptPass( $put_vars['inputPassword'] );
			$tmpResponse 				= $userModel->createUser($put_vars);

			if($tmpResponse[0]){
				$response = array(
					'codeResponse' 	=> 200
				);
			}else{
				$message = '';
				switch ($tmpResponse[1]) {
					case '23000':
						$message = 'Email is registered, try another email.';
						break;				
					default:
						$message = 'general error in the database.';
						break;
				}

				$response = array(
					'codeResponse' 	=> 0,
					'message' 		=> $message
				);
			}

			header('HTTP/1.1 200 Ok');
			header("Content-Type: application/json; charset=UTF-8");			
			exit(json_encode($response));
		} else if($put_vars['_method'] == 'validarUsuario'){
			$tmpResponse = $userModel->validarUsuario($put_vars['email']);

			$response = array(
				'codeResponse' 	=> 0,
				'message' 		=> 'Username or password incorrect'
			);

			if($tmpResponse){
				if (password_verify($put_vars['password'], $tmpResponse->password)){
					unset($tmpResponse->password);

					$response = array(
						'codeResponse' 	=> 200
					);

					// Primero se setea la foto por defecto del usuario, se valida si existe el archivo de fotografia con su ID, si existe se cambia la ruta de la foto.
					$fotografia = "assets/img/user/default.jpg";
					if( is_file(dirname(__FILE__, 3) . "/assets/img/user/". $tmpResponse->id . ".jpg" ) )
						$fotografia = "assets/img/user/". $tmpResponse->id . ".jpg";

					$_SESSION['socialLogin']		= TRUE;
					$_SESSION['authData'] 			= $tmpResponse;
					$_SESSION['authData']->image 	= $fotografia;
				}
			}

			header('HTTP/1.1 200 Ok');
			header("Content-Type: application/json; charset=UTF-8");			
			exit(json_encode($response));
		} 
	}

	function encryptPass($strPassword) {
		$options = [
		    'cost' => 12
		];

		return password_hash($strPassword, PASSWORD_BCRYPT, $options);
	}

	header('HTTP/1.1 400 Bad Request');
	header("Content-Type: application/json; charset=UTF-8");
	$response = array(
		'codeResponse' => 400,
		'message' => 'Bad Request'
	);
	exit( json_encode($response) );