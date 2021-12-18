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
					$fotografia = "nothing";
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
		} else if($put_vars['_method'] == 'updateData'){
			$setData = array(
				'firstName'	=> $put_vars['firstName'],
				'lastName'	=> $put_vars['lastName'],
				'userId'	=> $_SESSION['authData']->id
			);

			$tmpResponse = $userModel->updateData($setData);

			if($tmpResponse){
				$_SESSION['authData']->name 		= $put_vars['firstName'];
				$_SESSION['authData']->last_name 	= $put_vars['lastName'];

				$folder = "assets/img/user";
				if( !is_dir(dirname(__FILE__, 3) . "/{$folder}") )
					mkdir(dirname(__FILE__, 3) . "/{$folder}", 0777, true);
				
				if (!empty($_FILES['cropImage'])){
					$filename = $_FILES['cropImage']['name'];
					$tempname = $_FILES['cropImage']['tmp_name'];
					       
					if(move_uploaded_file($tempname, "../../{$folder}/{$filename}"))
						$_SESSION['authData']->image = "assets/img/user/". $_SESSION['authData']->id . ".jpg";
				}

				$response = array(
					'codeResponse'	=> 200,
					'data' 			=> $_SESSION['authData']
				);
			}else{
				$response = array(
					'codeResponse' 	=> 0
				);
			}

			header('HTTP/1.1 200 Ok');
			header("Content-Type: application/json; charset=UTF-8");			
			exit(json_encode($response));
		} else if($put_vars['_method'] == 'search'){
			$response = array(
				'codeResponse' 	=> 200,
				'data' 			=> $userModel->getData( $put_vars['parameter'] )
			);

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