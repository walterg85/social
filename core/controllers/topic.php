<?php
	session_start();
	
	require_once '../models/topic.php';

	header("Access-Control-Allow-Origin: *");
	header("Access-Control-Allow-Methods: POST");

	if($_SERVER['REQUEST_METHOD'] == 'POST'){
		$put_vars 	= ($_POST) ? $_POST : json_decode(file_get_contents("php://input"), TRUE);
		$topicModel	= new Topicmodel();

		if($put_vars['_method'] == 'POST'){
			$put_vars['userId'] = $_SESSION['authData']->id;

			$tmpResponse = $topicModel->createTopic($put_vars);

			if($tmpResponse[0]){
				$folder = 'assets/img/topic';
				if( !is_dir(dirname(__FILE__, 3) . "/{$folder}") )
					mkdir(dirname(__FILE__, 3) . "/{$folder}", 0777, true);

				if (!empty($_FILES['cropImage'])){
					$filename = $tmpResponse[1] . ".jpg";
					$tempname = $_FILES['cropImage']['tmp_name'];
					       
					move_uploaded_file($tempname, "../../{$folder}/{$filename}");
				}

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
		}
	}

	header('HTTP/1.1 400 Bad Request');
	header("Content-Type: application/json; charset=UTF-8");
	$response = array(
		'codeResponse' => 400,
		'message' => 'Bad Request'
	);
	exit( json_encode($response) );