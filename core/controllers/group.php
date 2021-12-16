<?php
	session_start();
	
	require_once '../models/group.php';

	header("Access-Control-Allow-Origin: *");
	header("Access-Control-Allow-Methods: POST");

	if($_SERVER['REQUEST_METHOD'] == 'POST'){
		$put_vars 	= ($_POST) ? $_POST : json_decode(file_get_contents("php://input"), TRUE);
		$groupModel	= new Groupmodel();

		if($put_vars['_method'] == 'POST'){
			$tmpResponse = $groupModel->createGroup($put_vars);

			if($tmpResponse){
				$response = array(
					'codeResponse' 	=> 200
				);
			}else{
				$response = array(
					'codeResponse' 	=> 0
				);
			}

			header('HTTP/1.1 200 Ok');
			header("Content-Type: application/json; charset=UTF-8");			
			exit(json_encode($response));
		} else if($put_vars['_method'] == 'GET'){
			$response = array(
				'codeResponse' 	=> 200,
				'data' 			=> $groupModel->getGroup($put_vars['limit'])
			);

			header('HTTP/1.1 200 Ok');
			header("Content-Type: application/json; charset=UTF-8");			
			exit(json_encode($response));
		}
	}

	header('HTTP/1.1 400 Bad Request');
	header("Content-Type: application/json; charset=UTF-8");
	$response = array(
		'codeResponse' => 400,
		'message' => 'Bad Request'
	);
	exit( json_encode($response) );