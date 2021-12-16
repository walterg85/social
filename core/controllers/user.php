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